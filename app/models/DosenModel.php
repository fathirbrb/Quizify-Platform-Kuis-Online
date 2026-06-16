<?php

require_once __DIR__ . '/../../config/database.php';

class DosenModel
{
    public function getStats($dosenId)
    {
        $matkul = $this->getMatkulDiampu($dosenId);

        return [
            'kuis' => 12,
            'soal' => 120,
            'mahasiswa' => 80,
            'kelas' => count(array_unique(array_column($matkul, 'kelas'))),
            'matkul' => count(array_unique(array_column($matkul, 'kode_matkul'))),
        ];
    }

    public function getMatkulDiampu($dosenId)
    {
        $db = $this->db();

        if ($db) {
            $stmt = $db->prepare(
                'SELECT
                    mk.id AS matkul_id,
                    k.id AS kelas_id,
                    mk.kode AS kode_matkul,
                    mk.nama AS nama_matkul,
                    mk.sks,
                    k.nama AS kelas,
                    k.tahun_ajaran,
                    k.invite_code
                 FROM dosen_kelas dk
                 JOIN mata_kuliah mk ON mk.id = dk.matkul_id
                 JOIN kelas k ON k.id = dk.kelas_id
                 WHERE dk.dosen_id = :dosen_id
                 ORDER BY mk.nama ASC, k.nama ASC'
            );
            $stmt->execute(['dosen_id' => $dosenId]);
            $rows = $stmt->fetchAll();

            if (!empty($rows)) {
                return $rows;
            }
        }

        return $this->dummyMatkul();
    }

    public function getKelasDiampu($dosenId)
    {
        $db = $this->db();

        if ($db) {
            $this->ensureKelasConfigColumns($db);
            $stmt = $db->prepare(
                'SELECT
                    k.id AS kelas_id,
                    k.nama AS kelas,
                    k.tahun_ajaran,
                    COALESCE(j.nama, k.jurusan) AS jurusan,
                    k.jurusan_id,
                    k.kode_kelas,
                    k.invite_code,
                    COUNT(DISTINCT dk.matkul_id) AS jumlah_matkul,
                    COUNT(DISTINCT q.id) AS jumlah_kuis,
                    COUNT(DISTINCT mkel.mahasiswa_id) AS jumlah_mahasiswa
                 FROM dosen_kelas dk
                 JOIN kelas k ON k.id = dk.kelas_id
                 LEFT JOIN jurusan j ON j.id = k.jurusan_id
                 LEFT JOIN kuis q ON q.kelas_id = k.id AND q.dosen_id = dk.dosen_id
                 LEFT JOIN mahasiswa_kelas mkel ON mkel.kelas_id = k.id
                 WHERE dk.dosen_id = :dosen_id
                 GROUP BY k.id, k.nama, k.tahun_ajaran, k.jurusan, k.jurusan_id, j.nama, k.kode_kelas, k.invite_code
                 ORDER BY k.tahun_ajaran DESC, k.nama ASC'
            );
            $stmt->execute(['dosen_id' => $dosenId]);
            $rows = $stmt->fetchAll();

            if (!empty($rows)) {
                return $rows;
            }
        }

        $grouped = [];
        foreach ($this->dummyMatkul() as $mk) {
            $id = $mk['kelas_id'];
            if (!isset($grouped[$id])) {
                $grouped[$id] = [
                    'kelas_id' => $id,
                    'kelas' => $mk['kelas'],
                    'tahun_ajaran' => $mk['tahun_ajaran'],
                    'jurusan' => 'Sistem Informasi',
                    'kode_kelas' => 'KLS-' . $id,
                    'invite_code' => 'KLS' . $id,
                    'jumlah_matkul' => 0,
                    'jumlah_kuis' => 0,
                    'jumlah_mahasiswa' => 0,
                ];
            }
            $grouped[$id]['jumlah_matkul']++;
        }

        return array_values($grouped);
    }

    public function getKelasById($dosenId, $kelasId)
    {
        $db = $this->db();

        if ($db) {
            $this->ensureKelasConfigColumns($db);
            $stmt = $db->prepare(
                'SELECT
                    k.id AS kelas_id,
                    k.nama AS kelas,
                    k.tahun_ajaran,
                    COALESCE(j.nama, k.jurusan) AS jurusan,
                    k.jurusan_id,
                    k.kode_kelas,
                    k.invite_code,
                    k.deskripsi
                 FROM kelas k
                 LEFT JOIN jurusan j ON j.id = k.jurusan_id
                 WHERE k.id = :kelas_id
                 AND (
                    EXISTS (
                        SELECT 1 FROM dosen_kelas dk
                        WHERE dk.kelas_id = k.id AND dk.dosen_id = :dosen_id
                    )
                    OR EXISTS (
                        SELECT 1 FROM kuis q
                        WHERE q.kelas_id = k.id AND q.dosen_id = :dosen_id_kuis
                    )
                 )
                 LIMIT 1'
            );
            $stmt->execute([
                'kelas_id' => $kelasId,
                'dosen_id' => $dosenId,
                'dosen_id_kuis' => $dosenId,
            ]);
            $row = $stmt->fetch();

            if ($row) {
                return $row;
            }
        }

        foreach ($this->getKelasDiampu($dosenId) as $kelas) {
            if ((string) $kelas['kelas_id'] === (string) $kelasId) {
                return $kelas;
            }
        }

        return null;
    }

    public function updateKelasDosen($dosenId, $kelasId, array $data)
    {
        $db = $this->db();

        if (!$db || !$this->getKelasById($dosenId, $kelasId)) {
            return false;
        }

        $this->ensureKelasConfigColumns($db);
        $stmt = $db->prepare(
            'UPDATE kelas
             SET nama = :nama,
                 jurusan = :jurusan,
                 jurusan_id = COALESCE((SELECT j.id FROM jurusan j WHERE j.nama = :jurusan_lookup LIMIT 1), jurusan_id),
                 kode_kelas = :kode_kelas
             WHERE id = :id'
        );

        return $stmt->execute([
            'nama' => $data['nama'],
            'jurusan' => $data['jurusan'] ?: null,
            'jurusan_lookup' => $data['jurusan'] ?: null,
            'kode_kelas' => $data['kode_kelas'] ?: null,
            'id' => $kelasId,
        ]);
    }

    public function getMahasiswaByKelas($dosenId, $kelasId, $keyword = '')
    {
        $db = $this->db();
        if (!$db || !$this->getKelasById($dosenId, $kelasId)) {
            return [];
        }

        $this->ensureKelasConfigColumns($db);
        $stmt = $db->prepare('SHOW COLUMNS FROM mahasiswa_kelas LIKE :column_name');
        $stmt->execute(['column_name' => 'joined_at']);
        if (!$stmt->fetch()) {
            $db->exec('ALTER TABLE mahasiswa_kelas ADD COLUMN joined_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP');
        }

        $sql = 'SELECT u.id, u.nama, u.email, u.nim_nip, mk.joined_at
                FROM mahasiswa_kelas mk
                JOIN users u ON u.id = mk.mahasiswa_id
                WHERE mk.kelas_id = :kelas_id AND u.role = "mahasiswa"';
        $params = ['kelas_id' => $kelasId];

        if ($keyword !== '') {
            $sql .= ' AND (u.nama LIKE :keyword OR u.email LIKE :keyword OR u.nim_nip LIKE :keyword)';
            $params['keyword'] = '%' . $keyword . '%';
        }

        $sql .= ' ORDER BY u.nama ASC';
        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function hapusMahasiswaDariKelas($dosenId, $kelasId, $mahasiswaId)
    {
        $db = $this->db();
        if (!$db || !$this->getKelasById($dosenId, $kelasId)) {
            return false;
        }

        $stmt = $db->prepare(
            'DELETE FROM mahasiswa_kelas
             WHERE kelas_id = :kelas_id AND mahasiswa_id = :mahasiswa_id'
        );

        return $stmt->execute([
            'kelas_id' => $kelasId,
            'mahasiswa_id' => $mahasiswaId,
        ]);
    }

    public function getJurusanList()
    {
        $db = $this->db();

        if ($db) {
            $db->exec(
                'CREATE TABLE IF NOT EXISTS jurusan (
                    id INT NOT NULL AUTO_INCREMENT,
                    nama VARCHAR(100) NOT NULL,
                    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (id),
                    UNIQUE KEY unique_jurusan_nama (nama)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
            );
            foreach (['Sistem Informasi', 'Teknik Informatika', 'Ilmu Komputer'] as $nama) {
                $stmt = $db->prepare('INSERT IGNORE INTO jurusan (nama) VALUES (:nama)');
                $stmt->execute(['nama' => $nama]);
            }

            return $db->query('SELECT id, nama FROM jurusan ORDER BY nama ASC')->fetchAll();
        }

        return [
            ['id' => 1, 'nama' => 'Sistem Informasi'],
            ['id' => 2, 'nama' => 'Teknik Informatika'],
        ];
    }

    public function getLaporanNilai($dosenId, $matkulId = '', $kelasId = '')
    {
        $db = $this->db();

        if ($db) {
            $sql = 'SELECT
                        q.id AS kuis_id,
                        u.id AS mahasiswa_id,
                        u.nama AS nama_mahasiswa,
                        mk.id AS matkul_id,
                        k.id AS kelas_id,
                        mk.kode AS kode_matkul,
                        mk.nama AS nama_matkul,
                        mk.sks,
                        k.nama AS kelas,
                        k.tahun_ajaran,
                        q.judul AS judul_kuis,
                        hk.nilai,
                        hk.created_at
                    FROM hasil_kuis hk
                    JOIN kuis q ON q.id = hk.kuis_id
                    JOIN users u ON u.id = hk.mahasiswa_id
                    JOIN mata_kuliah mk ON mk.id = q.matkul_id
                    JOIN kelas k ON k.id = q.kelas_id
                    WHERE q.dosen_id = :dosen_id';
            $params = ['dosen_id' => $dosenId];

            if ($matkulId !== '') {
                $sql .= ' AND mk.id = :matkul_id';
                $params['matkul_id'] = $matkulId;
            }

            if ($kelasId !== '') {
                $sql .= ' AND k.id = :kelas_id';
                $params['kelas_id'] = $kelasId;
            }

            $sql .= ' ORDER BY mk.nama ASC, k.nama ASC, hk.nilai DESC';

            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll();

            if (!empty($rows)) {
                return $rows;
            }
        }

        return $this->filterDummyNilai($matkulId, $kelasId);
    }

    public function getNilaiStats(array $nilai)
    {
        $numbers = array_values(array_filter(array_column($nilai, 'nilai'), 'is_numeric'));

        if (empty($numbers)) {
            return [
                'tertinggi' => 0,
                'terendah' => 0,
                'rata_rata' => 0,
                'kelulusan' => 0,
            ];
        }

        $lulus = count(array_filter($numbers, fn($n) => $n >= 70));

        return [
            'tertinggi' => max($numbers),
            'terendah' => min($numbers),
            'rata_rata' => round(array_sum($numbers) / count($numbers), 1),
            'kelulusan' => round(($lulus / count($numbers)) * 100),
        ];
    }

    public function getMonitoringMahasiswa($dosenId, $matkulId = '', $kelasId = '', $status = '')
    {
        $db = $this->db();

        if ($db) {
            $sql = 'SELECT
                        q.id AS kuis_id,
                        u.id AS mahasiswa_id,
                        u.nama AS nama_mahasiswa,
                        mk.id AS matkul_id,
                        k.id AS kelas_id,
                        mk.kode AS kode_matkul,
                        mk.nama AS nama_matkul,
                        k.nama AS kelas,
                        k.tahun_ajaran,
                        q.judul AS judul_kuis,
                        COALESCE(kp.status, "belum") AS status,
                        kp.progress,
                        hk.nilai
                    FROM kuis q
                    JOIN mata_kuliah mk ON mk.id = q.matkul_id
                    JOIN kelas k ON k.id = q.kelas_id
                    JOIN mahasiswa_kelas mkel ON mkel.kelas_id = q.kelas_id
                    JOIN users u ON u.id = mkel.mahasiswa_id
                    LEFT JOIN kuis_pengerjaan kp
                        ON kp.kuis_id = q.id
                        AND kp.mahasiswa_id = u.id
                    LEFT JOIN hasil_kuis hk
                        ON hk.kuis_id = q.id
                        AND hk.mahasiswa_id = u.id
                    WHERE q.dosen_id = :dosen_id';
            $params = ['dosen_id' => $dosenId];

            if ($matkulId !== '') {
                $sql .= ' AND mk.id = :matkul_id';
                $params['matkul_id'] = $matkulId;
            }

            if ($kelasId !== '') {
                $sql .= ' AND k.id = :kelas_id';
                $params['kelas_id'] = $kelasId;
            }

            if ($status !== '') {
                $sql .= ' AND COALESCE(kp.status, "belum") = :status';
                $params['status'] = $status;
            }

            $sql .= ' ORDER BY mk.nama ASC, k.nama ASC, u.nama ASC';

            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll();

            if (!empty($rows)) {
                return $rows;
            }
        }

        return $this->filterDummyMonitoring($matkulId, $kelasId, $status);
    }

    public function getDetailJawabanMahasiswa($dosenId, $kuisId, $mahasiswaId)
    {
        $db = $this->db();

        if ($db) {
            $stmt = $db->prepare(
                'SELECT
                    kp.id AS pengerjaan_id,
                    kp.status,
                    kp.progress,
                    kp.mulai,
                    kp.selesai,
                    q.judul AS judul_kuis,
                    q.durasi,
                    u.nama AS nama_mahasiswa,
                    mk.kode AS kode_matkul,
                    mk.nama AS nama_matkul,
                    mk.sks,
                    k.nama AS kelas,
                    k.tahun_ajaran,
                    hk.nilai
                 FROM kuis_pengerjaan kp
                 JOIN kuis q ON q.id = kp.kuis_id
                 JOIN users u ON u.id = kp.mahasiswa_id
                 JOIN mata_kuliah mk ON mk.id = q.matkul_id
                 JOIN kelas k ON k.id = q.kelas_id
                 LEFT JOIN hasil_kuis hk
                    ON hk.kuis_id = q.id
                    AND hk.mahasiswa_id = u.id
                 WHERE kp.kuis_id = :kuis_id
                    AND kp.mahasiswa_id = :mahasiswa_id
                    AND q.dosen_id = :dosen_id
                 ORDER BY kp.id DESC
                 LIMIT 1'
            );
            $stmt->execute([
                'kuis_id' => $kuisId,
                'mahasiswa_id' => $mahasiswaId,
                'dosen_id' => $dosenId,
            ]);
            $summary = $stmt->fetch();

            if ($summary) {
                $stmt = $db->prepare(
                    'SELECT
                        s.id,
                        s.pertanyaan,
                        s.opsi_a,
                        s.opsi_b,
                        s.opsi_c,
                        s.opsi_d,
                        s.jawaban_benar,
                        jm.jawaban AS jawaban_mahasiswa
                     FROM soal s
                     LEFT JOIN jawaban_mahasiswa jm
                        ON jm.soal_id = s.id
                        AND jm.pengerjaan_id = :pengerjaan_id
                     WHERE s.kuis_id = :kuis_id
                     ORDER BY s.id ASC'
                );
                $stmt->execute([
                    'pengerjaan_id' => $summary['pengerjaan_id'],
                    'kuis_id' => $kuisId,
                ]);

                return [
                    'summary' => $summary,
                    'jawaban' => $stmt->fetchAll(),
                ];
            }
        }

        return $this->dummyDetailJawaban($kuisId, $mahasiswaId);
    }

    public function getKuis($dosenId, $matkulId = '', $kelasId = '', $status = '')
    {
        $db = $this->db();

        if ($db) {
            $sql = 'SELECT
                        q.id,
                        q.judul,
                        q.durasi,
                        q.status,
                        q.waktu_mulai,
                        q.waktu_selesai,
                        mk.id AS matkul_id,
                        k.id AS kelas_id,
                        mk.kode AS kode_matkul,
                        mk.nama AS nama_matkul,
                        mk.sks,
                        k.nama AS kelas,
                        k.tahun_ajaran
                    FROM kuis q
                    JOIN mata_kuliah mk ON mk.id = q.matkul_id
                    JOIN kelas k ON k.id = q.kelas_id
                    WHERE q.dosen_id = :dosen_id';
            $params = ['dosen_id' => $dosenId];

            if ($matkulId !== '') {
                $sql .= ' AND mk.id = :matkul_id';
                $params['matkul_id'] = $matkulId;
            }

            if ($kelasId !== '') {
                $sql .= ' AND k.id = :kelas_id';
                $params['kelas_id'] = $kelasId;
            }

            if ($status !== '') {
                $sql .= ' AND q.status = :status';
                $params['status'] = $status;
            }

            $sql .= ' ORDER BY mk.nama ASC, k.nama ASC, q.created_at DESC';

            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll();

            if (!empty($rows)) {
                return $rows;
            }
        }

        return $this->filterDummyKuis($matkulId, $kelasId, $status);
    }

    public function tambahKuis($dosenId, array $data)
    {
        $db = $this->db();

        if (!$db) {
            return false;
        }

        $stmt = $db->prepare(
            'INSERT INTO kuis (kelas_id, matkul_id, dosen_id, judul, deskripsi, durasi, waktu_mulai, waktu_selesai, status)
             VALUES (:kelas_id, :matkul_id, :dosen_id, :judul, :deskripsi, :durasi, :waktu_mulai, :waktu_selesai, :status)'
        );
        $stmt->execute([
            'kelas_id' => $data['kelas_id'],
            'matkul_id' => $data['matkul_id'],
            'dosen_id' => $dosenId,
            'judul' => $data['judul'],
            'deskripsi' => $data['deskripsi'] ?: null,
            'durasi' => $data['durasi'],
            'waktu_mulai' => $data['waktu_mulai'] ?: null,
            'waktu_selesai' => $data['waktu_selesai'] ?: null,
            'status' => $data['status'],
        ]);

        return (int) $db->lastInsertId();
    }

    public function getKuisById($dosenId, $kuisId)
    {
        $db = $this->db();

        if ($db) {
            $stmt = $db->prepare(
                'SELECT
                    q.*,
                    mk.kode AS kode_matkul,
                    mk.nama AS nama_matkul,
                    mk.sks,
                    k.nama AS kelas,
                    k.tahun_ajaran
                 FROM kuis q
                 JOIN mata_kuliah mk ON mk.id = q.matkul_id
                 JOIN kelas k ON k.id = q.kelas_id
                 WHERE q.id = :id AND q.dosen_id = :dosen_id
                 LIMIT 1'
            );
            $stmt->execute([
                'id' => $kuisId,
                'dosen_id' => $dosenId,
            ]);

            return $stmt->fetch() ?: null;
        }

        foreach ($this->dummyKuis() as $kuis) {
            if ((string) $kuis['id'] === (string) $kuisId) {
                return $kuis;
            }
        }

        return null;
    }

    public function getSoalByKuis($dosenId, $kuisId)
    {
        $db = $this->db();

        if ($db) {
            $stmt = $db->prepare(
                'SELECT s.*
                 FROM soal s
                 JOIN kuis q ON q.id = s.kuis_id
                 WHERE s.kuis_id = :kuis_id AND q.dosen_id = :dosen_id
                 ORDER BY s.id ASC'
            );
            $stmt->execute([
                'kuis_id' => $kuisId,
                'dosen_id' => $dosenId,
            ]);
            $rows = $stmt->fetchAll();

            if (!empty($rows)) {
                return $rows;
            }
        }

        return $this->dummySoal($kuisId);
    }

    public function tambahSoal($dosenId, array $data)
    {
        $db = $this->db();

        if (!$db || !$this->getKuisById($dosenId, $data['kuis_id'])) {
            return false;
        }

        $stmt = $db->prepare(
            'INSERT INTO soal (kuis_id, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar)
             VALUES (:kuis_id, :pertanyaan, :opsi_a, :opsi_b, :opsi_c, :opsi_d, :jawaban_benar)'
        );

        return $stmt->execute([
            'kuis_id' => $data['kuis_id'],
            'pertanyaan' => $data['pertanyaan'],
            'opsi_a' => $data['opsi_a'],
            'opsi_b' => $data['opsi_b'],
            'opsi_c' => $data['opsi_c'],
            'opsi_d' => $data['opsi_d'],
            'jawaban_benar' => $data['jawaban_benar'],
        ]);
    }

    public function updateKonfigurasiKuis($dosenId, $kuisId, array $data)
    {
        $db = $this->db();

        if (!$db) {
            return false;
        }

        $stmt = $db->prepare(
            'UPDATE kuis
             SET durasi = :durasi,
                 status = :status,
                 waktu_mulai = :waktu_mulai,
                 waktu_selesai = :waktu_selesai
             WHERE id = :id AND dosen_id = :dosen_id'
        );

        return $stmt->execute([
            'durasi' => $data['durasi'],
            'status' => $data['status'],
            'waktu_mulai' => $data['waktu_mulai'] ?: null,
            'waktu_selesai' => $data['waktu_selesai'] ?: null,
            'id' => $kuisId,
            'dosen_id' => $dosenId,
        ]);
    }

    public function tambahBanyakSoal($dosenId, $kuisId, array $soalList)
    {
        $db = $this->db();

        if (!$db || !$this->getKuisById($dosenId, $kuisId) || empty($soalList)) {
            return false;
        }

        $stmt = $db->prepare(
            'INSERT INTO soal (kuis_id, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar)
             VALUES (:kuis_id, :pertanyaan, :opsi_a, :opsi_b, :opsi_c, :opsi_d, :jawaban_benar)'
        );

        $db->beginTransaction();
        try {
            foreach ($soalList as $soal) {
                $stmt->execute([
                    'kuis_id' => $kuisId,
                    'pertanyaan' => $soal['pertanyaan'],
                    'opsi_a' => $soal['opsi_a'],
                    'opsi_b' => $soal['opsi_b'],
                    'opsi_c' => $soal['opsi_c'] ?: null,
                    'opsi_d' => $soal['opsi_d'] ?: null,
                    'jawaban_benar' => $soal['jawaban_benar'],
                ]);
            }

            $db->commit();
            return true;
        } catch (Throwable $e) {
            $db->rollBack();
            return false;
        }
    }

    private function db()
    {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            $pdo = new PDO($dsn, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $pdo;
        } catch (PDOException $e) {
            return null;
        }
    }

    private function ensureKelasConfigColumns(PDO $db)
    {
        foreach ([
            'jurusan' => 'VARCHAR(100) NULL',
            'jurusan_id' => 'INT NULL',
            'kode_kelas' => 'VARCHAR(50) NULL',
            'deskripsi' => 'TEXT NULL',
            'updated_at' => 'TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP',
        ] as $column => $definition) {
            $stmt = $db->prepare('SHOW COLUMNS FROM kelas LIKE :column_name');
            $stmt->execute(['column_name' => $column]);

            if (!$stmt->fetch()) {
                $db->exec('ALTER TABLE kelas ADD COLUMN ' . $column . ' ' . $definition);
            }
        }

        $db->exec(
            'CREATE TABLE IF NOT EXISTS jurusan (
                id INT NOT NULL AUTO_INCREMENT,
                nama VARCHAR(100) NOT NULL,
                created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                UNIQUE KEY unique_jurusan_nama (nama)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
        );

        foreach (['Sistem Informasi', 'Teknik Informatika', 'Ilmu Komputer'] as $nama) {
            $stmt = $db->prepare('INSERT IGNORE INTO jurusan (nama) VALUES (:nama)');
            $stmt->execute(['nama' => $nama]);
        }

        $db->exec(
            'UPDATE kelas k
             JOIN jurusan j ON j.nama = k.jurusan
             SET k.jurusan_id = j.id
             WHERE k.jurusan_id IS NULL AND k.jurusan IS NOT NULL'
        );
    }

    private function dummyMatkul()
    {
        return [
            [
                'matkul_id' => 1,
                'kelas_id' => 4,
                'kode_matkul' => 'IF401',
                'nama_matkul' => 'Basis Data',
                'sks' => 3,
                'kelas' => 'SI 4A',
                'tahun_ajaran' => '2025/2026',
            ],
            [
                'matkul_id' => 1,
                'kelas_id' => 5,
                'kode_matkul' => 'IF401',
                'nama_matkul' => 'Basis Data',
                'sks' => 3,
                'kelas' => 'SI 4B',
                'tahun_ajaran' => '2025/2026',
            ],
            [
                'matkul_id' => 2,
                'kelas_id' => 4,
                'kode_matkul' => 'IF402',
                'nama_matkul' => 'Pemrograman Web',
                'sks' => 3,
                'kelas' => 'SI 4A',
                'tahun_ajaran' => '2025/2026',
            ],
        ];
    }

    private function dummyNilai()
    {
        return [
            [
                'kuis_id' => 1,
                'mahasiswa_id' => 4,
                'nama_mahasiswa' => 'Andi Mahasiswa',
                'matkul_id' => 1,
                'kelas_id' => 4,
                'kode_matkul' => 'IF401',
                'nama_matkul' => 'Basis Data',
                'sks' => 3,
                'kelas' => 'SI 4A',
                'tahun_ajaran' => '2025/2026',
                'judul_kuis' => 'Kuis SQL Dasar',
                'nilai' => 94,
                'created_at' => '2026-05-25 09:30:00',
            ],
            [
                'kuis_id' => 1,
                'mahasiswa_id' => 5,
                'nama_mahasiswa' => 'Bela Mahasiswi',
                'matkul_id' => 1,
                'kelas_id' => 4,
                'kode_matkul' => 'IF401',
                'nama_matkul' => 'Basis Data',
                'sks' => 3,
                'kelas' => 'SI 4A',
                'tahun_ajaran' => '2025/2026',
                'judul_kuis' => 'Kuis SQL Dasar',
                'nilai' => 86,
                'created_at' => '2026-05-25 10:10:00',
            ],
            [
                'kuis_id' => 2,
                'mahasiswa_id' => 6,
                'nama_mahasiswa' => 'Candra Putra',
                'matkul_id' => 1,
                'kelas_id' => 5,
                'kode_matkul' => 'IF401',
                'nama_matkul' => 'Basis Data',
                'sks' => 3,
                'kelas' => 'SI 4B',
                'tahun_ajaran' => '2025/2026',
                'judul_kuis' => 'Kuis Normalisasi',
                'nilai' => 78,
                'created_at' => '2026-05-26 11:00:00',
            ],
            [
                'kuis_id' => 3,
                'mahasiswa_id' => 7,
                'nama_mahasiswa' => 'Dewi Lestari',
                'matkul_id' => 2,
                'kelas_id' => 4,
                'kode_matkul' => 'IF402',
                'nama_matkul' => 'Pemrograman Web',
                'sks' => 3,
                'kelas' => 'SI 4A',
                'tahun_ajaran' => '2025/2026',
                'judul_kuis' => 'Kuis HTML CSS',
                'nilai' => 91,
                'created_at' => '2026-05-27 08:45:00',
            ],
        ];
    }

    private function filterDummyNilai($matkulId, $kelasId)
    {
        return array_values(array_filter($this->dummyNilai(), function ($row) use ($matkulId, $kelasId) {
            if ($matkulId !== '' && (string) $row['matkul_id'] !== (string) $matkulId) {
                return false;
            }

            if ($kelasId !== '' && (string) $row['kelas_id'] !== (string) $kelasId) {
                return false;
            }

            return true;
        }));
    }

    private function dummyMonitoring()
    {
        return [
            [
                'kuis_id' => 1,
                'mahasiswa_id' => 4,
                'nama_mahasiswa' => 'Andi Mahasiswa',
                'matkul_id' => 1,
                'kelas_id' => 4,
                'kode_matkul' => 'IF401',
                'nama_matkul' => 'Basis Data',
                'kelas' => 'SI 4A',
                'tahun_ajaran' => '2025/2026',
                'judul_kuis' => 'Kuis SQL Dasar',
                'status' => 'selesai',
                'progress' => 100,
                'nilai' => 94,
            ],
            [
                'kuis_id' => 1,
                'mahasiswa_id' => 5,
                'nama_mahasiswa' => 'Bela Mahasiswi',
                'matkul_id' => 1,
                'kelas_id' => 4,
                'kode_matkul' => 'IF401',
                'nama_matkul' => 'Basis Data',
                'kelas' => 'SI 4A',
                'tahun_ajaran' => '2025/2026',
                'judul_kuis' => 'Kuis SQL Dasar',
                'status' => 'selesai',
                'progress' => 100,
                'nilai' => 86,
            ],
            [
                'kuis_id' => 2,
                'mahasiswa_id' => 6,
                'nama_mahasiswa' => 'Candra Putra',
                'matkul_id' => 1,
                'kelas_id' => 5,
                'kode_matkul' => 'IF401',
                'nama_matkul' => 'Basis Data',
                'kelas' => 'SI 4B',
                'tahun_ajaran' => '2025/2026',
                'judul_kuis' => 'Kuis Normalisasi',
                'status' => 'sedang',
                'progress' => 55,
                'nilai' => null,
            ],
            [
                'kuis_id' => 3,
                'mahasiswa_id' => 7,
                'nama_mahasiswa' => 'Dewi Lestari',
                'matkul_id' => 2,
                'kelas_id' => 4,
                'kode_matkul' => 'IF402',
                'nama_matkul' => 'Pemrograman Web',
                'kelas' => 'SI 4A',
                'tahun_ajaran' => '2025/2026',
                'judul_kuis' => 'Kuis HTML CSS',
                'status' => 'belum',
                'progress' => 0,
                'nilai' => null,
            ],
        ];
    }

    private function filterDummyMonitoring($matkulId, $kelasId, $status)
    {
        return array_values(array_filter($this->dummyMonitoring(), function ($row) use ($matkulId, $kelasId, $status) {
            if ($matkulId !== '' && (string) $row['matkul_id'] !== (string) $matkulId) {
                return false;
            }

            if ($kelasId !== '' && (string) $row['kelas_id'] !== (string) $kelasId) {
                return false;
            }

            if ($status !== '' && (string) $row['status'] !== (string) $status) {
                return false;
            }

            return true;
        }));
    }

    private function dummyDetailJawaban($kuisId, $mahasiswaId)
    {
        $nama = (string) $mahasiswaId === '5' ? 'Bela Mahasiswi' : 'Andi Mahasiswa';

        return [
            'summary' => [
                'pengerjaan_id' => 1,
                'status' => 'selesai',
                'progress' => 100,
                'mulai' => '2026-05-25 09:00:00',
                'selesai' => '2026-05-25 09:28:00',
                'judul_kuis' => 'Kuis SQL Dasar',
                'durasi' => 30,
                'nama_mahasiswa' => $nama,
                'kode_matkul' => 'IF401',
                'nama_matkul' => 'Basis Data',
                'sks' => 3,
                'kelas' => 'SI 4A',
                'tahun_ajaran' => '2025/2026',
                'nilai' => (string) $mahasiswaId === '5' ? 50 : 100,
            ],
            'jawaban' => [
                [
                    'id' => 1,
                    'pertanyaan' => 'Apa kepanjangan HTML?',
                    'opsi_a' => 'Hyper Text Markup Language',
                    'opsi_b' => 'Home Tool Markup Language',
                    'opsi_c' => 'Hyper Tool Main Language',
                    'opsi_d' => 'Hyper Text Main Language',
                    'jawaban_benar' => 'A',
                    'jawaban_mahasiswa' => 'A',
                ],
                [
                    'id' => 2,
                    'pertanyaan' => 'Perintah SQL untuk mengambil data adalah?',
                    'opsi_a' => 'INSERT',
                    'opsi_b' => 'SELECT',
                    'opsi_c' => 'UPDATE',
                    'opsi_d' => 'DELETE',
                    'jawaban_benar' => 'B',
                    'jawaban_mahasiswa' => (string) $mahasiswaId === '5' ? 'A' : 'B',
                ],
            ],
        ];
    }

    private function dummyKuis()
    {
        return [
            [
                'id' => 1,
                'judul' => 'Kuis SQL Dasar',
                'durasi' => 30,
                'status' => 'aktif',
                'waktu_mulai' => '2026-05-25 08:00:00',
                'waktu_selesai' => '2026-05-25 10:00:00',
                'matkul_id' => 1,
                'kelas_id' => 4,
                'kode_matkul' => 'IF401',
                'nama_matkul' => 'Basis Data',
                'sks' => 3,
                'kelas' => 'SI 4A',
                'tahun_ajaran' => '2025/2026',
            ],
            [
                'id' => 2,
                'judul' => 'Kuis Normalisasi',
                'durasi' => 45,
                'status' => 'terjadwal',
                'waktu_mulai' => '2026-05-28 09:00:00',
                'waktu_selesai' => '2026-05-28 11:00:00',
                'matkul_id' => 1,
                'kelas_id' => 5,
                'kode_matkul' => 'IF401',
                'nama_matkul' => 'Basis Data',
                'sks' => 3,
                'kelas' => 'SI 4B',
                'tahun_ajaran' => '2025/2026',
            ],
            [
                'id' => 3,
                'judul' => 'Kuis HTML CSS',
                'durasi' => 40,
                'status' => 'selesai',
                'waktu_mulai' => '2026-05-20 08:00:00',
                'waktu_selesai' => '2026-05-20 09:30:00',
                'matkul_id' => 2,
                'kelas_id' => 4,
                'kode_matkul' => 'IF402',
                'nama_matkul' => 'Pemrograman Web',
                'sks' => 3,
                'kelas' => 'SI 4A',
                'tahun_ajaran' => '2025/2026',
            ],
            [
                'id' => 4,
                'judul' => 'Draft JavaScript Dasar',
                'durasi' => 35,
                'status' => 'draft',
                'waktu_mulai' => null,
                'waktu_selesai' => null,
                'matkul_id' => 2,
                'kelas_id' => 4,
                'kode_matkul' => 'IF402',
                'nama_matkul' => 'Pemrograman Web',
                'sks' => 3,
                'kelas' => 'SI 4A',
                'tahun_ajaran' => '2025/2026',
            ],
        ];
    }

    private function dummySoal($kuisId)
    {
        return [
            [
                'id' => 1,
                'kuis_id' => $kuisId,
                'pertanyaan' => 'Apa kepanjangan HTML?',
                'opsi_a' => 'Hyper Text Markup Language',
                'opsi_b' => 'Home Tool Markup Language',
                'opsi_c' => 'Hyper Tool Main Language',
                'opsi_d' => 'Hyper Text Main Language',
                'jawaban_benar' => 'A',
            ],
            [
                'id' => 2,
                'kuis_id' => $kuisId,
                'pertanyaan' => 'Perintah SQL untuk mengambil data adalah?',
                'opsi_a' => 'INSERT',
                'opsi_b' => 'SELECT',
                'opsi_c' => 'UPDATE',
                'opsi_d' => 'DELETE',
                'jawaban_benar' => 'B',
            ],
        ];
    }

    private function filterDummyKuis($matkulId, $kelasId, $status)
    {
        return array_values(array_filter($this->dummyKuis(), function ($row) use ($matkulId, $kelasId, $status) {
            if ($matkulId !== '' && (string) $row['matkul_id'] !== (string) $matkulId) {
                return false;
            }

            if ($kelasId !== '' && (string) $row['kelas_id'] !== (string) $kelasId) {
                return false;
            }

            if ($status !== '' && (string) $row['status'] !== (string) $status) {
                return false;
            }

            return true;
        }));
    }
}
