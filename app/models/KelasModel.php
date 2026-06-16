<?php

require_once __DIR__ . '/../../config/database.php';

class KelasModel
{
    private $db;

    public function __construct()
    {
        $this->db = getDB();
        $this->ensureSchema();
    }

    public function getAll()
    {
        $stmt = $this->db->query(
            'SELECT
                k.id,
                k.nama,
                k.tahun_ajaran,
                k.invite_code,
                k.jurusan_id,
                COALESCE(j.nama, k.jurusan) AS jurusan,
                k.kode_kelas,
                k.deskripsi,
                COUNT(DISTINCT dk.dosen_id) AS jumlah_dosen,
                COUNT(DISTINCT mk.mahasiswa_id) AS jumlah_mahasiswa
             FROM kelas k
             LEFT JOIN jurusan j ON j.id = k.jurusan_id
             LEFT JOIN dosen_kelas dk ON dk.kelas_id = k.id
             LEFT JOIN mahasiswa_kelas mk ON mk.kelas_id = k.id
             GROUP BY k.id, k.nama, k.tahun_ajaran, k.invite_code, k.jurusan_id, j.nama, k.jurusan, k.kode_kelas, k.deskripsi
             ORDER BY k.id DESC'
        );

        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare(
            'SELECT k.*, COALESCE(j.nama, k.jurusan) AS jurusan_nama
             FROM kelas k
             LEFT JOIN jurusan j ON j.id = k.jurusan_id
             WHERE k.id = :id
             LIMIT 1'
        );
        $stmt->execute(['id' => $id]);

        return $stmt->fetch() ?: null;
    }

    public function tambah($data)
    {
        $stmt = $this->db->prepare(
            'INSERT INTO kelas (nama, jurusan_id, jurusan, kode_kelas, tahun_ajaran, invite_code, deskripsi)
             VALUES (:nama, :jurusan_id, :jurusan, :kode_kelas, :tahun_ajaran, :invite_code, :deskripsi)'
        );

        $jurusanNama = $this->getJurusanNama($data['jurusan_id'] ?? 0);
        $ok = $stmt->execute([
            'nama' => $data['nama'],
            'jurusan_id' => $data['jurusan_id'] ?: null,
            'jurusan' => $jurusanNama,
            'kode_kelas' => $data['kode_kelas'] ?: null,
            'tahun_ajaran' => $data['tahun_ajaran'] ?: null,
            'invite_code' => $data['invite_code'] ?: $this->generateInviteCode(),
            'deskripsi' => $data['deskripsi'] ?: null,
        ]);

        return $ok ? (int) $this->db->lastInsertId() : false;
    }

    public function edit($data)
    {
        $stmt = $this->db->prepare(
            'UPDATE kelas
             SET nama = :nama,
                 jurusan_id = :jurusan_id,
                 jurusan = :jurusan,
                 kode_kelas = :kode_kelas,
                 tahun_ajaran = :tahun_ajaran,
                 invite_code = :invite_code,
                 deskripsi = :deskripsi
             WHERE id = :id'
        );

        $jurusanNama = $this->getJurusanNama($data['jurusan_id'] ?? 0);
        return $stmt->execute([
            'id' => $data['id'],
            'nama' => $data['nama'],
            'jurusan_id' => $data['jurusan_id'] ?: null,
            'jurusan' => $jurusanNama,
            'kode_kelas' => $data['kode_kelas'] ?: null,
            'tahun_ajaran' => $data['tahun_ajaran'] ?: null,
            'invite_code' => $data['invite_code'] ?: $this->generateInviteCode(),
            'deskripsi' => $data['deskripsi'] ?: null,
        ]);
    }

    public function hapus($id)
    {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare('SELECT id FROM kuis WHERE kelas_id = :id');
            $stmt->execute(['id' => $id]);
            $kuisIds = array_map('intval', array_column($stmt->fetchAll(), 'id'));

            if (!empty($kuisIds)) {
                $placeholders = implode(',', array_fill(0, count($kuisIds), '?'));
                $this->db->prepare(
                    'DELETE jm FROM jawaban_mahasiswa jm
                     JOIN kuis_pengerjaan kp ON kp.id = jm.pengerjaan_id
                     WHERE kp.kuis_id IN (' . $placeholders . ')'
                )->execute($kuisIds);
                $this->db->prepare('DELETE FROM hasil_kuis WHERE kuis_id IN (' . $placeholders . ')')->execute($kuisIds);
                $this->db->prepare('DELETE FROM kuis_pengerjaan WHERE kuis_id IN (' . $placeholders . ')')->execute($kuisIds);
                $this->db->prepare('DELETE FROM soal WHERE kuis_id IN (' . $placeholders . ')')->execute($kuisIds);
                if ($this->columnExists('activity_log', 'quiz_id')) {
                    $this->db->prepare('UPDATE activity_log SET quiz_id = NULL WHERE quiz_id IN (' . $placeholders . ')')->execute($kuisIds);
                }
            }

            $this->db->prepare('DELETE FROM dosen_kelas WHERE kelas_id = :id')->execute(['id' => $id]);
            $this->db->prepare('DELETE FROM mahasiswa_kelas WHERE kelas_id = :id')->execute(['id' => $id]);
            if ($this->columnExists('activity_log', 'class_id')) {
                $this->db->prepare('UPDATE activity_log SET class_id = NULL WHERE class_id = :id')->execute(['id' => $id]);
            }
            $this->db->prepare('DELETE FROM kuis WHERE kelas_id = :id')->execute(['id' => $id]);
            $stmt = $this->db->prepare('DELETE FROM kelas WHERE id = :id');
            $ok = $stmt->execute(['id' => $id]);

            $this->db->commit();
            return $ok;
        } catch (Throwable $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getDosenList()
    {
        return $this->getUsersByRole('dosen');
    }

    public function getMahasiswaList()
    {
        return $this->getUsersByRole('mahasiswa');
    }

    public function getMatkulList()
    {
        return $this->db->query('SELECT id, kode, nama, sks FROM mata_kuliah ORDER BY nama ASC')->fetchAll();
    }

    public function getJurusanList()
    {
        return $this->db->query('SELECT id, nama FROM jurusan ORDER BY nama ASC')->fetchAll();
    }

    public function tambahJurusan($nama)
    {
        $stmt = $this->db->prepare('INSERT INTO jurusan (nama) VALUES (:nama)');

        return $stmt->execute(['nama' => $nama]);
    }

    public function hapusJurusan($id)
    {
        $stmt = $this->db->prepare('DELETE FROM jurusan WHERE id = :id');

        return $stmt->execute(['id' => $id]);
    }

    public function editJurusan($id, $nama)
    {
        $stmt = $this->db->prepare('UPDATE jurusan SET nama = :nama WHERE id = :id');

        return $stmt->execute([
            'id' => $id,
            'nama' => $nama,
        ]);
    }

    public function getAssignDosen($kelasId)
    {
        $stmt = $this->db->prepare(
            'SELECT dk.id, dk.dosen_id, dk.matkul_id, u.nama AS nama_dosen, mk.kode, mk.nama AS nama_matkul
             FROM dosen_kelas dk
             JOIN users u ON u.id = dk.dosen_id
             JOIN mata_kuliah mk ON mk.id = dk.matkul_id
             WHERE dk.kelas_id = :kelas_id
             ORDER BY u.nama ASC, mk.nama ASC'
        );
        $stmt->execute(['kelas_id' => $kelasId]);

        return $stmt->fetchAll();
    }

    public function assignDosen($kelasId, $dosenId, $matkulId)
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM dosen_kelas
             WHERE kelas_id = :kelas_id AND dosen_id = :dosen_id AND matkul_id = :matkul_id'
        );
        $stmt->execute([
            'kelas_id' => $kelasId,
            'dosen_id' => $dosenId,
            'matkul_id' => $matkulId,
        ]);

        if ((int) $stmt->fetchColumn() > 0) {
            return true;
        }

        $stmt = $this->db->prepare(
            'INSERT INTO dosen_kelas (kelas_id, dosen_id, matkul_id)
             VALUES (:kelas_id, :dosen_id, :matkul_id)'
        );

        return $stmt->execute([
            'kelas_id' => $kelasId,
            'dosen_id' => $dosenId,
            'matkul_id' => $matkulId,
        ]);
    }

    public function hapusAssignDosen($id)
    {
        $stmt = $this->db->prepare('DELETE FROM dosen_kelas WHERE id = :id');

        return $stmt->execute(['id' => $id]);
    }

    private function getUsersByRole($role)
    {
        $stmt = $this->db->prepare('SELECT id, nama, email, nim_nip FROM users WHERE role = :role ORDER BY nama ASC');
        $stmt->execute(['role' => $role]);

        return $stmt->fetchAll();
    }

    private function columnExists($table, $column)
    {
        $stmt = $this->db->prepare('SHOW COLUMNS FROM ' . $table . ' LIKE :column_name');
        $stmt->execute(['column_name' => $column]);

        return (bool) $stmt->fetch();
    }

    private function getJurusanNama($id)
    {
        if (!$id) {
            return null;
        }

        $stmt = $this->db->prepare('SELECT nama FROM jurusan WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);

        return $stmt->fetchColumn() ?: null;
    }

    private function generateInviteCode()
    {
        return strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
    }

    private function ensureSchema()
    {
        foreach ([
            'jurusan' => 'VARCHAR(100) NULL',
            'jurusan_id' => 'INT NULL',
            'kode_kelas' => 'VARCHAR(50) NULL',
            'deskripsi' => 'TEXT NULL',
            'updated_at' => 'TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP',
        ] as $column => $definition) {
            $stmt = $this->db->prepare('SHOW COLUMNS FROM kelas LIKE :column_name');
            $stmt->execute(['column_name' => $column]);
            if (!$stmt->fetch()) {
                $this->db->exec('ALTER TABLE kelas ADD COLUMN ' . $column . ' ' . $definition);
            }
        }

        $this->db->exec(
            'CREATE TABLE IF NOT EXISTS jurusan (
                id INT NOT NULL AUTO_INCREMENT,
                nama VARCHAR(100) NOT NULL,
                created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                UNIQUE KEY unique_jurusan_nama (nama)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
        );

        foreach (['Sistem Informasi', 'Teknik Informatika', 'Ilmu Komputer'] as $nama) {
            $stmt = $this->db->prepare('INSERT IGNORE INTO jurusan (nama) VALUES (:nama)');
            $stmt->execute(['nama' => $nama]);
        }

        $this->db->exec(
            'UPDATE kelas k
             JOIN jurusan j ON j.nama = k.jurusan
             SET k.jurusan_id = j.id
             WHERE k.jurusan_id IS NULL AND k.jurusan IS NOT NULL'
        );

        $stmt = $this->db->prepare('SHOW COLUMNS FROM mahasiswa_kelas LIKE :column_name');
        $stmt->execute(['column_name' => 'joined_at']);
        if (!$stmt->fetch()) {
            $this->db->exec('ALTER TABLE mahasiswa_kelas ADD COLUMN joined_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP');
        }
    }
}
