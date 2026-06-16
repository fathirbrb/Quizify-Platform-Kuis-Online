<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/ActivityModel.php';

class MahasiswaModel
{
    public function searchKelas($keyword, $mahasiswaId)
    {
        $db = $this->db();

        if (!$db || trim($keyword) === '') {
            return [];
        }

        $this->ensureKelasJoinSchema($db);
        $stmt = $db->prepare(
            'SELECT
                k.id,
                k.nama,
                COALESCE(j.nama, k.jurusan) AS jurusan,
                k.kode_kelas,
                k.invite_code,
                k.tahun_ajaran,
                EXISTS (
                    SELECT 1 FROM mahasiswa_kelas mk
                    WHERE mk.kelas_id = k.id AND mk.mahasiswa_id = :mahasiswa_id
                ) AS sudah_gabung
             FROM kelas k
             LEFT JOIN jurusan j ON j.id = k.jurusan_id
             WHERE k.nama LIKE :keyword
                OR k.kode_kelas LIKE :keyword
                OR k.invite_code LIKE :keyword
                OR k.jurusan LIKE :keyword
                OR j.nama LIKE :keyword
             ORDER BY k.nama ASC
             LIMIT 8'
        );
        $stmt->execute([
            'mahasiswa_id' => $mahasiswaId,
            'keyword' => '%' . $keyword . '%',
        ]);

        return $stmt->fetchAll();
    }

    public function gabungKelasDenganKode($mahasiswaId, $kode, $kelasId = 0)
    {
        $db = $this->db();
        $kode = strtoupper(trim($kode));

        if (!$db || $mahasiswaId <= 0 || $kode === '') {
            return ['ok' => false, 'message' => 'Kode kelas wajib diisi.'];
        }

        $this->ensureKelasJoinSchema($db);
        $sql = 'SELECT id, nama FROM kelas
                WHERE (
                    UPPER(TRIM(invite_code)) = :kode
                    OR UPPER(TRIM(kode_kelas)) = :kode
                )';
        $params = ['kode' => $kode];

        if ($kelasId > 0) {
            $sql .= ' AND id = :kelas_id';
            $params['kelas_id'] = $kelasId;
        }

        $sql .= ' ORDER BY id ASC LIMIT 1';
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $kelas = $stmt->fetch();

        if (!$kelas) {
            return ['ok' => false, 'message' => 'Kelas tidak ditemukan. Pastikan kode kelas atau kode masuk yang dimasukkan sudah benar.'];
        }

        $stmt = $db->prepare(
            'SELECT COUNT(*) FROM mahasiswa_kelas
             WHERE mahasiswa_id = :mahasiswa_id AND kelas_id = :kelas_id'
        );
        $stmt->execute([
            'mahasiswa_id' => $mahasiswaId,
            'kelas_id' => $kelas['id'],
        ]);

        if ((int) $stmt->fetchColumn() > 0) {
            return ['ok' => true, 'message' => 'Kamu sudah tergabung di kelas ini.', 'kelas' => $kelas['nama']];
        }

        $this->ensureMahasiswaKelasSchema($db);
        $stmt = $db->prepare(
            'INSERT INTO mahasiswa_kelas (mahasiswa_id, kelas_id, joined_at)
             VALUES (:mahasiswa_id, :kelas_id, NOW())'
        );
        $stmt->execute([
            'mahasiswa_id' => $mahasiswaId,
            'kelas_id' => $kelas['id'],
        ]);

        return ['ok' => true, 'message' => 'Berhasil masuk kelas ' . $kelas['nama'] . '.', 'kelas' => $kelas['nama']];
    }

    public function getStats($mahasiswaId = 0)
    {
        $dbOnline = $mahasiswaId > 0 && (bool) $this->db();
        $kuis = $this->getKuisTersedia($mahasiswaId);
        $riwayat = $this->getRiwayatNilai($mahasiswaId);

        if ($dbOnline || !empty($kuis) || !empty($riwayat)) {
            $tersedia = 0;
            $belum = 0;

            foreach ($kuis as $item) {
                $status = $item['status_filter'] ?? '';
                if (in_array($status, ['dibuka', 'sedang', 'terjadwal'], true)) {
                    $tersedia++;
                }
                if (in_array($status, ['dibuka', 'belum', 'terjadwal'], true)) {
                    $belum++;
                }
            }

            $nilai = array_values(array_filter(array_column($riwayat, 'nilai'), 'is_numeric'));

            return [
                'kuis_tersedia' => $tersedia,
                'belum_dikerjakan' => $belum,
                'selesai' => count($nilai),
                'nilai_terakhir' => $nilai[0] ?? 0,
            ];
        }

        return [
            'kuis_tersedia' => 8,
            'belum_dikerjakan' => 5,
            'selesai' => 3,
            'nilai_terakhir' => 92
        ];
    }

    public function getAktivitasTerbaru($mahasiswaId = 0)
    {
        $dbOnline = $mahasiswaId > 0 && (bool) $this->db();
        $riwayat = array_map(function ($item) {
            return $item + [
                'status_tampil' => !empty($item['auto_submitted']) ? 'Auto Submit' : 'Selesai',
                'status' => !empty($item['auto_submitted']) ? 'Auto Submit' : 'Selesai',
                'warna' => 'green',
                'jumlah_soal' => $item['jumlah_soal'] ?? 0,
                'durasi' => $item['durasi'] ?? ($item['durasi_aktual'] ?? 0),
            ];
        }, array_slice($this->getRiwayatNilai($mahasiswaId), 0, 3));
        $kuis = array_slice(array_merge($riwayat, $this->getKuisTersedia($mahasiswaId)), 0, 3);

        if ($dbOnline || !empty($kuis)) {
            return $kuis;
        }

        return [
            [
                'nama_kuis' => 'HTML Dasar',
                'nama_matkul' => 'Pemrograman Web',
                'jumlah_soal' => 20,
                'durasi' => 30,
                'status' => 'Tersedia',
                'warna' => 'orange'
            ],
            [
                'nama_kuis' => 'CSS Dasar',
                'nama_matkul' => 'Pemrograman Web',
                'jumlah_soal' => 15,
                'durasi' => 25,
                'status' => 'Selesai',
                'nilai' => 90,
                'warna' => 'blue'
            ],
            [
                'nama_kuis' => 'Basis Data',
                'nama_matkul' => 'Basis Data',
                'jumlah_soal' => 25,
                'durasi' => 40,
                'status' => 'Tersedia',
                'warna' => 'green'
            ]
        ];
    }

    public function getFilterOptions($mahasiswaId = 0)
    {
        $kuis = $this->getKuisTersedia($mahasiswaId);
        $matkul = [];
        $kelas = [];
        $relasi = [];

        foreach ($kuis as $item) {
            $matkul[$item['matkul_id']] = $item['kode_matkul'] . ' - ' . $item['nama_matkul'];
            $kelas[$item['kelas_id']] = $item['nama_kelas'] . ' (' . ($item['tahun_ajaran'] ?? '-') . ')';
            $relasi[$item['matkul_id'] . '|' . $item['kelas_id']] = $item['kode_matkul'] . ' - ' . $item['nama_matkul'] . ' / ' . $item['nama_kelas'] . ' / ' . ($item['tahun_ajaran'] ?? '-');
        }

        return [
            'matkul' => $matkul,
            'kelas' => $kelas,
            'relasi' => $relasi,
        ];
    }

    public function getKuisTersedia($mahasiswaId = 0, $status = '', $matkulId = '', $kelasId = '')
    {
        $db = $this->db();

        if ($db && $mahasiswaId > 0) {
            $this->finalisasiKuisKadaluarsa($mahasiswaId);

            $sql = 'SELECT
                        q.id,
                        q.judul AS nama_kuis,
                        q.durasi,
                        q.status AS status_kuis,
                        q.waktu_mulai,
                        q.waktu_selesai,
                        mk.id AS matkul_id,
                        k.id AS kelas_id,
                        mk.kode AS kode_matkul,
                        mk.nama AS nama_matkul,
                        mk.sks,
                        k.nama AS nama_kelas,
                        k.tahun_ajaran,
                        COALESCE(kp.status, "belum") AS status_pengerjaan,
                        hk.nilai,
                        COUNT(DISTINCT s.id) AS jumlah_soal
                    FROM kuis q
                    JOIN kelas k ON k.id = q.kelas_id
                    JOIN mata_kuliah mk ON mk.id = q.matkul_id
                    LEFT JOIN kuis_pengerjaan kp
                        ON kp.kuis_id = q.id
                        AND kp.mahasiswa_id = :mahasiswa_id_pengerjaan
                        AND kp.id = (
                            SELECT MAX(kp2.id)
                            FROM kuis_pengerjaan kp2
                            WHERE kp2.kuis_id = q.id
                            AND kp2.mahasiswa_id = :mahasiswa_id_pengerjaan_latest
                        )
                    LEFT JOIN hasil_kuis hk
                        ON hk.kuis_id = q.id
                        AND hk.mahasiswa_id = :mahasiswa_id_hasil
                    LEFT JOIN soal s ON s.kuis_id = q.id
                    WHERE EXISTS (
                        SELECT 1
                        FROM mahasiswa_kelas mkel
                        WHERE mkel.mahasiswa_id = :mahasiswa_id_kelas
                        AND mkel.kelas_id = q.kelas_id
                    )
                    AND hk.id IS NULL
                    AND COALESCE(kp.status, "belum") NOT IN ("selesai", "completed", "expired")';
            $params = [
                'mahasiswa_id_pengerjaan' => $mahasiswaId,
                'mahasiswa_id_pengerjaan_latest' => $mahasiswaId,
                'mahasiswa_id_hasil' => $mahasiswaId,
                'mahasiswa_id_kelas' => $mahasiswaId,
            ];

            if ($matkulId !== '') {
                $sql .= ' AND mk.id = :matkul_id';
                $params['matkul_id'] = $matkulId;
            }

            if ($kelasId !== '') {
                $sql .= ' AND k.id = :kelas_id';
                $params['kelas_id'] = $kelasId;
            }

            $sql .= ' GROUP BY
                        q.id,
                        q.judul,
                        q.durasi,
                        q.status,
                        q.waktu_mulai,
                        q.waktu_selesai,
                        q.created_at,
                        mk.id,
                        k.id,
                        mk.kode,
                        mk.nama,
                        mk.sks,
                        k.nama,
                        k.tahun_ajaran,
                        kp.status,
                        hk.nilai
                      ORDER BY q.created_at DESC, q.id DESC';

            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $rows = array_map([$this, 'formatKuisRow'], $stmt->fetchAll());

            if (!empty($rows)) {
                return $this->filterByStatus($rows, $status);
            }

            return [];
        }

        return $this->filterByStatus($this->dummyKuis(), $status, $matkulId, $kelasId);
    }

    public function getNilaiStats(array $riwayat = null)
    {
        $data = $riwayat ?? $this->getRiwayatNilai();
        $numbers = array_values(array_filter(array_column($data, 'nilai'), 'is_numeric'));

        if (empty($numbers)) {
            return [
                'rata_rata' => 0,
                'tertinggi' => 0,
                'terendah' => 0,
                'total_selesai' => 0,
            ];
        }

        return [
            'rata_rata' => round(array_sum($numbers) / count($numbers), 1),
            'tertinggi' => max($numbers),
            'terendah' => min($numbers),
            'total_selesai' => count($numbers),
        ];
    }

    public function getRiwayatNilai($mahasiswaId = 0, $matkulId = '', $kelasId = '')
    {
        $db = $this->db();

        if ($db && $mahasiswaId > 0) {
            $sql = 'SELECT
                        q.judul AS nama_kuis,
                        mk.id AS matkul_id,
                        k.id AS kelas_id,
                        mk.kode AS kode_matkul,
                        mk.nama AS nama_matkul,
                        mk.sks,
                        k.nama AS nama_kelas,
                        k.tahun_ajaran,
                        q.durasi,
                        hk.nilai,
                        hk.created_at AS tanggal_selesai,
                        TIMESTAMPDIFF(MINUTE, kp.mulai, kp.selesai) AS durasi_aktual,
                        COALESCE(kp.auto_submitted, 0) AS auto_submitted,
                        COALESCE(kp.status, "selesai") AS status,
                        COUNT(DISTINCT s.id) AS jumlah_soal
                    FROM hasil_kuis hk
                    JOIN kuis q ON q.id = hk.kuis_id
                    JOIN mata_kuliah mk ON mk.id = q.matkul_id
                    JOIN kelas k ON k.id = q.kelas_id
                    LEFT JOIN kuis_pengerjaan kp
                        ON kp.kuis_id = q.id
                        AND kp.mahasiswa_id = hk.mahasiswa_id
                    LEFT JOIN soal s ON s.kuis_id = q.id
                    WHERE hk.mahasiswa_id = :mahasiswa_id';
            $params = ['mahasiswa_id' => $mahasiswaId];

            if ($matkulId !== '') {
                $sql .= ' AND mk.id = :matkul_id';
                $params['matkul_id'] = $matkulId;
            }

            if ($kelasId !== '') {
                $sql .= ' AND k.id = :kelas_id';
                $params['kelas_id'] = $kelasId;
            }

            $sql .= ' GROUP BY
                        q.id,
                        q.judul,
                        q.durasi,
                        mk.id,
                        k.id,
                        mk.kode,
                        mk.nama,
                        mk.sks,
                        k.nama,
                        k.tahun_ajaran,
                        hk.nilai,
                        hk.created_at,
                        kp.mulai,
                        kp.selesai,
                        kp.auto_submitted,
                        kp.status
                      ORDER BY hk.created_at DESC';

            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll();

            if (!empty($rows)) {
                return $rows;
            }

            return [];
        }

        return $this->filterDummyRiwayat($matkulId, $kelasId);
    }

    public function getMatkulList()
    {
        return [
            'Pemrograman Web',
            'Basis Data'
        ];
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

    private function dummyKuis()
    {
        return [

            [
                'id' => 1,
                'nama_kuis' => 'HTML Dasar',
                'matkul_id' => 2,
                'kelas_id' => 4,
                'kode_matkul' => 'IF402',
                'nama_matkul' => 'Pemrograman Web',
                'nama_kelas' => 'SI 4A',
                'tahun_ajaran' => '2025/2026',
                'sks' => 3,
                'jumlah_soal' => 20,
                'durasi' => 30,
                'warna' => 'orange',
                'status_tampil' => 'Tersedia',
                'batas_waktu' => '20:00'
            ],

            [
                'id' => 2,
                'nama_kuis' => 'CSS Layout',
                'matkul_id' => 2,
                'kelas_id' => 4,
                'kode_matkul' => 'IF402',
                'nama_matkul' => 'Pemrograman Web',
                'nama_kelas' => 'SI 4A',
                'tahun_ajaran' => '2025/2026',
                'sks' => 3,
                'jumlah_soal' => 15,
                'durasi' => 25,
                'warna' => 'blue',
                'status_tampil' => 'Sedang dikerjakan'
            ],

            [
                'id' => 3,
                'nama_kuis' => 'Basis Data',
                'matkul_id' => 1,
                'kelas_id' => 4,
                'kode_matkul' => 'IF401',
                'nama_matkul' => 'Basis Data',
                'nama_kelas' => 'SI 4A',
                'tahun_ajaran' => '2025/2026',
                'sks' => 3,
                'jumlah_soal' => 25,
                'durasi' => 45,
                'warna' => 'purple',
                'status_tampil' => 'Terjadwal'
            ],

            [
                'id' => 4,
                'nama_kuis' => 'SQL Dasar',
                'matkul_id' => 1,
                'kelas_id' => 4,
                'kode_matkul' => 'IF401',
                'nama_matkul' => 'Basis Data',
                'nama_kelas' => 'SI 4A',
                'tahun_ajaran' => '2025/2026',
                'sks' => 3,
                'jumlah_soal' => 20,
                'durasi' => 30,
                'warna' => 'green',
                'status_tampil' => 'Selesai',
                'nilai' => 88
            ]
        ];
    }

    public function getKuisById($id)
    {
        $db = $this->db();

        if ($db) {
            $stmt = $db->prepare(
                'SELECT
                    q.id,
                    q.kelas_id,
                    q.judul AS nama_kuis,
                    q.durasi,
                    mk.nama AS nama_matkul,
                    k.nama AS nama_kelas,
                    k.tahun_ajaran
                 FROM kuis q
                 JOIN mata_kuliah mk ON mk.id = q.matkul_id
                 JOIN kelas k ON k.id = q.kelas_id
                 WHERE q.id = :id
                 LIMIT 1'
            );
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch();

            if ($row) {
                return $row;
            }

            return null;
        }

        foreach ($this->dummyKuis() as $kuis) {
            if ((string) $kuis['id'] === (string) $id) {
                return $kuis;
            }
        }

        return null;
    }

    public function getSoalByKuis($id)
    {
        $db = $this->db();

        if ($db) {
            $stmt = $db->prepare('SELECT * FROM soal WHERE kuis_id = :kuis_id ORDER BY id ASC');
            $stmt->execute(['kuis_id' => $id]);
            $rows = $stmt->fetchAll();

            if (!empty($rows)) {
                return $rows;
            }

            return [];
        }

        return [
            [
                'id' => 1,
                'kuis_id' => $id,
                'pertanyaan' => 'Apa kepanjangan HTML?',
                'opsi_a' => 'Hyper Text Markup Language',
                'opsi_b' => 'Home Tool Markup Language',
                'opsi_c' => 'Hyper Tool Main Language',
                'opsi_d' => 'Hyper Text Main Language',
                'jawaban_benar' => 'A',
            ],
            [
                'id' => 2,
                'kuis_id' => $id,
                'pertanyaan' => 'Perintah SQL untuk mengambil data adalah?',
                'opsi_a' => 'INSERT',
                'opsi_b' => 'SELECT',
                'opsi_c' => 'UPDATE',
                'opsi_d' => 'DELETE',
                'jawaban_benar' => 'B',
            ],
        ];
    }

    public function submitKuis($mahasiswaId, $kuisId, array $jawaban, bool $autoSubmitted = false)
    {
        $existing = $this->getHasilKuis($mahasiswaId, $kuisId);
        if ($existing) {
            return [
                'nilai' => (float) $existing['nilai'],
                'benar' => null,
                'total' => null,
            ];
        }

        $soalList = $this->getSoalByKuis($kuisId);

        if (empty($soalList)) {
            return null;
        }

        $benar = 0;
        foreach ($soalList as $soal) {
            $id = $soal['id'];
            $pilihan = strtoupper($jawaban[$id] ?? '');

            if ($pilihan !== '' && $pilihan === strtoupper($soal['jawaban_benar'] ?? '')) {
                $benar++;
            }
        }

        $nilai = round(($benar / count($soalList)) * 100, 2);
        $db = $this->db();

        if ($db && $mahasiswaId > 0) {
            $this->ensureKuisPengerjaanSchema($db);
            $pengerjaan = $this->getOrCreatePengerjaan($mahasiswaId, $kuisId);
            $pengerjaanId = (int) ($pengerjaan['id'] ?? 0);

            $db->beginTransaction();
            try {
                if ($pengerjaanId <= 0) {
                    throw new RuntimeException('Pengerjaan tidak ditemukan.');
                }

                $db->prepare('DELETE FROM jawaban_mahasiswa WHERE pengerjaan_id = :pengerjaan_id')
                    ->execute(['pengerjaan_id' => $pengerjaanId]);

                $stmtJawaban = $db->prepare(
                    'INSERT INTO jawaban_mahasiswa (pengerjaan_id, soal_id, jawaban)
                     VALUES (:pengerjaan_id, :soal_id, :jawaban)'
                );
                foreach ($soalList as $soal) {
                    $stmtJawaban->execute([
                        'pengerjaan_id' => $pengerjaanId,
                        'soal_id' => $soal['id'],
                        'jawaban' => strtoupper($jawaban[$soal['id']] ?? ''),
                    ]);
                }

                $db->prepare(
                    'UPDATE kuis_pengerjaan
                     SET selesai = NOW(),
                         submitted_at = NOW(),
                         status = :status,
                         progress = 100,
                         score = :score,
                         auto_submitted = :auto_submitted
                     WHERE id = :id'
                )->execute([
                    'status' => $autoSubmitted ? 'expired' : 'completed',
                    'score' => $nilai,
                    'auto_submitted' => $autoSubmitted ? 1 : 0,
                    'id' => $pengerjaanId,
                ]);

                $db->prepare(
                    'DELETE FROM hasil_kuis WHERE kuis_id = :kuis_id AND mahasiswa_id = :mahasiswa_id'
                )->execute([
                    'kuis_id' => $kuisId,
                    'mahasiswa_id' => $mahasiswaId,
                ]);

                $stmtNilai = $db->prepare(
                    'INSERT INTO hasil_kuis (kuis_id, mahasiswa_id, nilai)
                     VALUES (:kuis_id, :mahasiswa_id, :nilai)'
                );
                $stmtNilai->execute([
                    'kuis_id' => $kuisId,
                    'mahasiswa_id' => $mahasiswaId,
                    'nilai' => $nilai,
                ]);

                $db->commit();
            } catch (Throwable $e) {
                $db->rollBack();
            }
        }

        return [
            'nilai' => $nilai,
            'benar' => $benar,
            'total' => count($soalList),
        ];
    }

    public function getHasilKuis($mahasiswaId, $kuisId)
    {
        $db = $this->db();

        if (!$db || $mahasiswaId <= 0 || $kuisId <= 0) {
            return null;
        }

        $stmt = $db->prepare(
            'SELECT *
             FROM hasil_kuis
             WHERE mahasiswa_id = :mahasiswa_id AND kuis_id = :kuis_id
             ORDER BY id DESC
             LIMIT 1'
        );
        $stmt->execute([
            'mahasiswa_id' => $mahasiswaId,
            'kuis_id' => $kuisId,
        ]);

        return $stmt->fetch() ?: null;
    }

    public function isKuisSelesai($mahasiswaId, $kuisId)
    {
        if ($this->getHasilKuis($mahasiswaId, $kuisId)) {
            return true;
        }

        $db = $this->db();
        if (!$db || $mahasiswaId <= 0 || $kuisId <= 0) {
            return false;
        }

        $stmt = $db->prepare(
            'SELECT 1
             FROM kuis_pengerjaan
             WHERE mahasiswa_id = :mahasiswa_id
             AND kuis_id = :kuis_id
             AND status IN ("selesai", "completed", "expired")
             LIMIT 1'
        );
        $stmt->execute([
            'mahasiswa_id' => $mahasiswaId,
            'kuis_id' => $kuisId,
        ]);

        return (bool) $stmt->fetchColumn();
    }

    public function getOrCreatePengerjaan($mahasiswaId, $kuisId)
    {
        $db = $this->db();

        if (!$db || $mahasiswaId <= 0 || $kuisId <= 0) {
            return ['id' => 0, 'kuis_id' => $kuisId, 'mahasiswa_id' => $mahasiswaId, 'mulai' => date('Y-m-d H:i:s'), 'status' => 'sedang', 'progress' => 0];
        }

        $this->ensureKuisPengerjaanSchema($db);
        $stmt = $db->prepare(
            'SELECT * FROM kuis_pengerjaan
             WHERE kuis_id = :kuis_id AND mahasiswa_id = :mahasiswa_id AND status IN ("sedang", "in_progress")
             ORDER BY id DESC
             LIMIT 1'
        );
        $stmt->execute([
            'kuis_id' => $kuisId,
            'mahasiswa_id' => $mahasiswaId,
        ]);
        $row = $stmt->fetch();

        if ($row) {
            return $row;
        }

        $stmt = $db->prepare(
            'INSERT INTO kuis_pengerjaan (kuis_id, mahasiswa_id, mulai, status, progress)
             VALUES (:kuis_id, :mahasiswa_id, NOW(), "in_progress", 0)'
        );
        $stmt->execute([
            'kuis_id' => $kuisId,
            'mahasiswa_id' => $mahasiswaId,
        ]);

        $id = (int) $db->lastInsertId();
        $stmt = $db->prepare('SELECT * FROM kuis_pengerjaan WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);

        return $stmt->fetch() ?: [
            'id' => $id,
            'kuis_id' => $kuisId,
            'mahasiswa_id' => $mahasiswaId,
            'mulai' => date('Y-m-d H:i:s'),
            'status' => 'sedang',
            'progress' => 0,
        ];
    }

    public function getSisaWaktuKuis($pengerjaanId, $durasiMenit)
    {
        $db = $this->db();
        $durasiDetik = max(1, (int) $durasiMenit) * 60;

        if (!$db || $pengerjaanId <= 0) {
            return $durasiDetik;
        }

        $stmt = $db->prepare(
            'SELECT GREATEST(0, (:durasi_detik - TIMESTAMPDIFF(SECOND, mulai, NOW()))) AS sisa_detik
             FROM kuis_pengerjaan
             WHERE id = :id
             LIMIT 1'
        );
        $stmt->execute([
            'durasi_detik' => $durasiDetik,
            'id' => $pengerjaanId,
        ]);
        $row = $stmt->fetch();

        return isset($row['sisa_detik']) ? (int) $row['sisa_detik'] : $durasiDetik;
    }

    public function getJawabanPengerjaan($pengerjaanId)
    {
        $db = $this->db();

        if (!$db || $pengerjaanId <= 0) {
            return [];
        }

        $stmt = $db->prepare('SELECT soal_id, jawaban FROM jawaban_mahasiswa WHERE pengerjaan_id = :pengerjaan_id');
        $stmt->execute(['pengerjaan_id' => $pengerjaanId]);
        $answers = [];

        foreach ($stmt->fetchAll() as $row) {
            $answers[$row['soal_id']] = $row['jawaban'];
        }

        return $answers;
    }

    public function simpanJawabanSementara($mahasiswaId, $kuisId, array $jawaban)
    {
        $db = $this->db();
        $pengerjaan = $this->getOrCreatePengerjaan($mahasiswaId, $kuisId);
        $pengerjaanId = (int) ($pengerjaan['id'] ?? 0);
        $soalList = $this->getSoalByKuis($kuisId);

        if (!$db || $pengerjaanId <= 0) {
            return false;
        }

        $terjawab = 0;
        $db->beginTransaction();
        try {
            $db->prepare('DELETE FROM jawaban_mahasiswa WHERE pengerjaan_id = :pengerjaan_id')
                ->execute(['pengerjaan_id' => $pengerjaanId]);

            $stmt = $db->prepare(
                'INSERT INTO jawaban_mahasiswa (pengerjaan_id, soal_id, jawaban)
                 VALUES (:pengerjaan_id, :soal_id, :jawaban)'
            );

            foreach ($soalList as $soal) {
                $pilihan = strtoupper($jawaban[$soal['id']] ?? '');
                if ($pilihan === '') {
                    continue;
                }

                $terjawab++;
                $stmt->execute([
                    'pengerjaan_id' => $pengerjaanId,
                    'soal_id' => $soal['id'],
                    'jawaban' => $pilihan,
                ]);
            }

            $progress = count($soalList) > 0 ? round(($terjawab / count($soalList)) * 100) : 0;
            $db->prepare(
                'UPDATE kuis_pengerjaan SET status = "in_progress", progress = :progress WHERE id = :id'
            )->execute([
                'progress' => $progress,
                'id' => $pengerjaanId,
            ]);

            $db->commit();
            return true;
        } catch (Throwable $e) {
            $db->rollBack();
            return false;
        }
    }

    public function getKuisBerjalan($mahasiswaId)
    {
        $db = $this->db();

        if ($db && $mahasiswaId > 0) {
            $this->finalisasiKuisKadaluarsa($mahasiswaId);

            $stmt = $db->prepare(
                'SELECT
                    kp.id AS pengerjaan_id,
                    kp.kuis_id,
                    kp.mulai,
                    kp.progress,
                    q.judul AS nama_kuis,
                    q.durasi,
                    mk.kode AS kode_matkul,
                    mk.nama AS nama_matkul,
                    mk.sks,
                    k.nama AS nama_kelas,
                    k.tahun_ajaran
                 FROM kuis_pengerjaan kp
                 JOIN kuis q ON q.id = kp.kuis_id
                 JOIN mata_kuliah mk ON mk.id = q.matkul_id
                 JOIN kelas k ON k.id = q.kelas_id
                 WHERE kp.mahasiswa_id = :mahasiswa_id AND kp.status IN ("sedang", "in_progress")
                 ORDER BY kp.mulai DESC'
            );
            $stmt->execute(['mahasiswa_id' => $mahasiswaId]);
            $rows = $stmt->fetchAll();

            if (!empty($rows)) {
                return $rows;
            }
        }

        return [];
    }

    public function finalisasiKuisKadaluarsa($mahasiswaId)
    {
        $db = $this->db();

        if (!$db || $mahasiswaId <= 0) {
            return;
        }

        $stmt = $db->prepare(
            'SELECT kp.id, kp.kuis_id, q.kelas_id
             FROM kuis_pengerjaan kp
             JOIN kuis q ON q.id = kp.kuis_id
             LEFT JOIN hasil_kuis hk
                ON hk.kuis_id = kp.kuis_id
                AND hk.mahasiswa_id = kp.mahasiswa_id
             WHERE kp.mahasiswa_id = :mahasiswa_id
             AND kp.status IN ("sedang", "in_progress")
             AND hk.id IS NULL
             AND TIMESTAMPDIFF(SECOND, kp.mulai, NOW()) >= (q.durasi * 60)'
        );
        $stmt->execute(['mahasiswa_id' => $mahasiswaId]);

        foreach ($stmt->fetchAll() as $row) {
            $this->submitKuis($mahasiswaId, (int) $row['kuis_id'], $this->getJawabanPengerjaan((int) $row['id']), true);
            ActivityModel::log($mahasiswaId, 'Timer kuis berakhir otomatis', 'mahasiswa', (int) ($row['kelas_id'] ?? 0), (int) $row['kuis_id']);
        }
    }

    private function ensureKelasJoinSchema(PDO $db)
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

        $this->ensureMahasiswaKelasSchema($db);
    }

    private function ensureMahasiswaKelasSchema(PDO $db)
    {
        $stmt = $db->prepare('SHOW COLUMNS FROM mahasiswa_kelas LIKE :column_name');
        $stmt->execute(['column_name' => 'joined_at']);
        if (!$stmt->fetch()) {
            $db->exec('ALTER TABLE mahasiswa_kelas ADD COLUMN joined_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP');
        }
    }

    private function ensureKuisPengerjaanSchema(PDO $db)
    {
        foreach ([
            'submitted_at' => 'DATETIME NULL',
            'score' => 'DECIMAL(5,2) NULL',
            'auto_submitted' => 'TINYINT(1) NOT NULL DEFAULT 0',
        ] as $column => $definition) {
            $stmt = $db->prepare('SHOW COLUMNS FROM kuis_pengerjaan LIKE :column_name');
            $stmt->execute(['column_name' => $column]);
            if (!$stmt->fetch()) {
                $db->exec('ALTER TABLE kuis_pengerjaan ADD COLUMN ' . $column . ' ' . $definition);
            }
        }

        try {
            $db->exec("ALTER TABLE kuis_pengerjaan MODIFY status ENUM('available','in_progress','completed','expired','sedang','selesai') DEFAULT 'in_progress'");
        } catch (Throwable $e) {
            // Ignore if the database engine or existing data rejects enum modification.
        }
    }

    private function dummyRiwayat()
    {
        return [

            [
                'nama_kuis' => 'HTML Dasar',
                'matkul_id' => 2,
                'kelas_id' => 4,
                'kode_matkul' => 'IF402',
                'nama_matkul' => 'Pemrograman Web',
                'nama_kelas' => 'SI 4A',
                'tahun_ajaran' => '2025/2026',
                'sks' => 3,
                'tanggal_selesai' => '2025-06-15',
                'durasi_aktual' => 25,
                'nilai' => 95,
                'status' => 'selesai'
            ],

            [
                'nama_kuis' => 'CSS Layout',
                'matkul_id' => 2,
                'kelas_id' => 4,
                'kode_matkul' => 'IF402',
                'nama_matkul' => 'Pemrograman Web',
                'nama_kelas' => 'SI 4A',
                'tahun_ajaran' => '2025/2026',
                'sks' => 3,
                'tanggal_selesai' => '2025-06-20',
                'durasi_aktual' => 20,
                'nilai' => 88,
                'status' => 'selesai'
            ],

            [
                'nama_kuis' => 'SQL Dasar',
                'matkul_id' => 1,
                'kelas_id' => 4,
                'kode_matkul' => 'IF401',
                'nama_matkul' => 'Basis Data',
                'nama_kelas' => 'SI 4A',
                'tahun_ajaran' => '2025/2026',
                'sks' => 3,
                'tanggal_selesai' => '2025-06-25',
                'durasi_aktual' => 35,
                'nilai' => 90,
                'status' => 'selesai'
            ]
        ];
    }

    private function formatKuisRow($row)
    {
        $status = $row['status_pengerjaan'];
        if ($status === 'belum') {
            $status = match ($row['status_kuis']) {
                'aktif' => 'dibuka',
                'terjadwal' => 'terjadwal',
                'completed' => 'selesai',
                'expired' => 'selesai',
                'selesai' => 'selesai',
                default => 'belum',
            };
        }

        $labels = [
            'dibuka' => 'Tersedia',
            'sedang' => 'Sedang dikerjakan',
            'terjadwal' => 'Terjadwal',
            'selesai' => 'Selesai',
            'belum' => 'Belum dibuka',
        ];

        return $row + [
            'status_tampil' => $labels[$status] ?? ucfirst($status),
            'status_filter' => $status,
            'jumlah_soal' => 20,
            'warna' => $status === 'selesai' ? 'green' : ($status === 'terjadwal' ? 'purple' : 'orange'),
        ];
    }

    private function filterByStatus(array $data, $status = '', $matkulId = '', $kelasId = '')
    {
        return array_values(array_filter($data, function ($item) use ($status, $matkulId, $kelasId) {
            $statusValue = $item['status_filter'] ?? match ($item['status_tampil']) {
                'Tersedia' => 'dibuka',
                'Sedang dikerjakan' => 'sedang',
                'Terjadwal' => 'terjadwal',
                'Selesai' => 'selesai',
                default => '',
            };

            if ($status !== '' && $status !== 'semua' && $statusValue !== $status) {
                return false;
            }

            if ($matkulId !== '' && (string) $item['matkul_id'] !== (string) $matkulId) {
                return false;
            }

            if ($kelasId !== '' && (string) $item['kelas_id'] !== (string) $kelasId) {
                return false;
            }

            return true;
        }));
    }

    private function filterDummyRiwayat($matkulId, $kelasId)
    {
        return array_values(array_filter($this->dummyRiwayat(), function ($item) use ($matkulId, $kelasId) {
            if ($matkulId !== '' && (string) $item['matkul_id'] !== (string) $matkulId) {
                return false;
            }

            if ($kelasId !== '' && (string) $item['kelas_id'] !== (string) $kelasId) {
                return false;
            }

            return true;
        }));
    }
}
