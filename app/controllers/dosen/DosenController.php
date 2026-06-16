<?php

require_once APP_ROOT . '/app/models/DosenModel.php';
require_once APP_ROOT . '/app/models/ActivityModel.php';

class DosenController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new DosenModel();
    }

    public function dashboard()
    {
        $dosenId = (int) ($_SESSION['user_id'] ?? 0);
        $stats = $this->model->getStats($dosenId);
        $matkulDiampu = array_slice($this->model->getMatkulDiampu($dosenId), 0, 4);

        $this->view('dosen/dashboard', compact('stats', 'matkulDiampu'));
    }

    public function kuis()
    {
        $dosenId = (int) ($_SESSION['user_id'] ?? 0);
        [$filterMatkul, $filterKelas] = $this->getRelasiFilter();
        $filterStatus = $_GET['status'] ?? '';
        $matkulDiampu = $this->model->getMatkulDiampu($dosenId);
        $allKuis = $this->model->getKuis($dosenId, $filterMatkul, $filterKelas, $filterStatus);

        $this->view('dosen/kuis', compact('matkulDiampu', 'allKuis', 'filterMatkul', 'filterKelas', 'filterStatus'));
    }

    public function tambahKuis()
    {
        $dosenId = (int) ($_SESSION['user_id'] ?? 0);
        $matkulDiampu = $this->model->getMatkulDiampu($dosenId);
        $preselectKelas = (int) ($_GET['kelas_id'] ?? 0);
        $preselectMatkul = (int) ($_GET['matkul_id'] ?? 0);
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            [$matkulId, $kelasId] = $this->parseRelasi($_POST['relasi'] ?? '');
            $jumlahSoal = max(1, min(50, (int) ($_POST['jumlah_soal'] ?? 1)));
            $data = [
                'matkul_id' => $matkulId,
                'kelas_id' => $kelasId,
                'judul' => trim($_POST['judul'] ?? ''),
                'deskripsi' => trim($_POST['deskripsi'] ?? ''),
                'durasi' => max(1, (int) ($_POST['durasi'] ?? 30)),
                'waktu_mulai' => $_POST['waktu_mulai'] ?? '',
                'waktu_selesai' => $_POST['waktu_selesai'] ?? '',
                'status' => $_POST['status'] ?? 'draft',
            ];

            $allowed = false;
            foreach ($matkulDiampu as $mk) {
                if ((int) $mk['matkul_id'] === $data['matkul_id'] && (int) $mk['kelas_id'] === $data['kelas_id']) {
                    $allowed = true;
                    break;
                }
            }

            if ($data['matkul_id'] <= 0 || $data['kelas_id'] <= 0 || $data['judul'] === '') {
                $error = 'Mata kuliah, kelas, dan judul kuis wajib diisi.';
            } elseif (!$allowed) {
                $error = 'Kombinasi mata kuliah dan kelas tidak sesuai relasi dosen.';
            } else {
                $kuisId = $this->model->tambahKuis($dosenId, $data);
                if ($kuisId) {
                    ActivityModel::log($dosenId, 'Dosen membuat kuis: ' . $data['judul'], 'dosen', $data['kelas_id'], $kuisId);
                    header('Location: index.php?page=dosen-tambah-soal&kuis_id=' . $kuisId . '&jumlah_soal=' . $jumlahSoal);
                    exit;
                }
                $error = 'Kuis gagal disimpan. Pastikan database berjalan dan relasi dosen-kelas tersedia.';
            }
        }

        $this->view('dosen/tambah_kuis', compact('matkulDiampu', 'error', 'preselectKelas', 'preselectMatkul'));
    }

    public function soal()
    {
        $dosenId = (int) ($_SESSION['user_id'] ?? 0);
        $kuisId = (int) ($_GET['kuis_id'] ?? 0);
        $kuis = $kuisId > 0 ? $this->model->getKuisById($dosenId, $kuisId) : null;
        $allSoal = $kuis ? $this->model->getSoalByKuis($dosenId, $kuisId) : [];

        $this->view('dosen/soal', compact('kuis', 'allSoal', 'kuisId'));
    }

    public function konfigurasiKuis()
    {
        $dosenId = (int) ($_SESSION['user_id'] ?? 0);
        $kuisId = (int) ($_GET['kuis_id'] ?? $_POST['kuis_id'] ?? 0);
        $kuis = $kuisId > 0 ? $this->model->getKuisById($dosenId, $kuisId) : null;
        $error = '';
        $success = '';

        if (!$kuis) {
            header('Location: index.php?page=dosen-kuis');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'durasi' => max(1, (int) ($_POST['durasi'] ?? 1)),
                'status' => $_POST['status'] ?? 'draft',
                'waktu_mulai' => $this->normalizeDateTime($_POST['waktu_mulai'] ?? ''),
                'waktu_selesai' => $this->normalizeDateTime($_POST['waktu_selesai'] ?? ''),
            ];

            if (!in_array($data['status'], ['draft', 'terjadwal', 'aktif', 'selesai'], true)) {
                $error = 'Status kuis tidak valid.';
            } elseif ($data['waktu_mulai'] && $data['waktu_selesai'] && strtotime($data['waktu_selesai']) <= strtotime($data['waktu_mulai'])) {
                $error = 'Waktu selesai harus lebih besar dari waktu mulai.';
            } elseif ($this->model->updateKonfigurasiKuis($dosenId, $kuisId, $data)) {
                ActivityModel::log($dosenId, 'Dosen mengedit konfigurasi kuis: ' . ($kuis['judul'] ?? $kuisId), 'dosen', (int) ($kuis['kelas_id'] ?? 0), $kuisId);
                $success = 'Konfigurasi kuis berhasil diperbarui.';
                $kuis = $this->model->getKuisById($dosenId, $kuisId);
            } else {
                $error = 'Konfigurasi gagal disimpan. Pastikan database berjalan.';
            }
        }

        $this->view('dosen/konfigurasi_kuis', compact('kuis', 'kuisId', 'error', 'success'));
    }

    public function tambahSoal()
    {
        $dosenId = (int) ($_SESSION['user_id'] ?? 0);
        $kuisId = (int) ($_GET['kuis_id'] ?? $_POST['kuis_id'] ?? 0);
        $kuis = $kuisId > 0 ? $this->model->getKuisById($dosenId, $kuisId) : null;
        $jumlahSoal = max(1, min(50, (int) ($_GET['jumlah_soal'] ?? $_POST['jumlah_soal'] ?? 1)));
        $error = '';

        if (!$kuis) {
            header('Location: index.php?page=dosen-kuis');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $soalList = [];
            for ($i = 0; $i < $jumlahSoal; $i++) {
                $soalList[] = [
                    'kuis_id' => $kuisId,
                    'pertanyaan' => trim($_POST['pertanyaan'][$i] ?? ''),
                    'opsi_a' => trim($_POST['opsi_a'][$i] ?? ''),
                    'opsi_b' => trim($_POST['opsi_b'][$i] ?? ''),
                    'opsi_c' => trim($_POST['opsi_c'][$i] ?? ''),
                    'opsi_d' => trim($_POST['opsi_d'][$i] ?? ''),
                    'jawaban_benar' => strtoupper(trim($_POST['jawaban_benar'][$i] ?? '')),
                ];
            }

            foreach ($soalList as $index => $data) {
                if ($data['pertanyaan'] === '' || $data['opsi_a'] === '' || $data['opsi_b'] === '' || !in_array($data['jawaban_benar'], ['A', 'B', 'C', 'D'])) {
                    $error = 'Soal nomor ' . ($index + 1) . ': pertanyaan, opsi A-B, dan jawaban benar wajib diisi.';
                    break;
                }
            }

            if ($error === '' && $this->model->tambahBanyakSoal($dosenId, $kuisId, $soalList)) {
                ActivityModel::log($dosenId, 'Dosen menambah ' . count($soalList) . ' soal kuis: ' . ($kuis['judul'] ?? $kuisId), 'dosen', (int) ($kuis['kelas_id'] ?? 0), $kuisId);
                header('Location: index.php?page=dosen-soal&kuis_id=' . $kuisId);
                exit;
            } elseif ($error === '') {
                $error = 'Soal gagal disimpan.';
            }
        }

        $this->view('dosen/tambah_soal', compact('kuis', 'kuisId', 'jumlahSoal', 'error'));
    }

    public function mahasiswa()
    {
        $dosenId = (int) ($_SESSION['user_id'] ?? 0);

        $selectedKelasId = isset($_GET['kelas_id']) && $_GET['kelas_id'] !== '' ? (int) $_GET['kelas_id'] : null;
        $selectedMatkulId = isset($_GET['matkul_id']) && $_GET['matkul_id'] !== '' ? (int) $_GET['matkul_id'] : null;
        $filterStatus = $_GET['status'] ?? '';

        $kelasDiampu = $this->model->getKelasDiampu($dosenId);
        $matkulDiampu = $this->model->getMatkulDiampu($dosenId);

        $allMhs = [];
        if ($selectedKelasId !== null && $selectedMatkulId !== null) {
            $allMhs = $this->model->getMonitoringMahasiswa($dosenId, $selectedMatkulId, $selectedKelasId, $filterStatus);
        }

        $selectedKelas = null;
        if ($selectedKelasId !== null) {
            foreach ($kelasDiampu as $kls) {
                if ((int) $kls['kelas_id'] === $selectedKelasId) {
                    $selectedKelas = $kls;
                    break;
                }
            }
        }

        $selectedMatkul = null;
        if ($selectedMatkulId !== null) {
            foreach ($matkulDiampu as $mk) {
                if ((int) $mk['matkul_id'] === $selectedMatkulId && (int) $mk['kelas_id'] === $selectedKelasId) {
                    $selectedMatkul = $mk;
                    break;
                }
            }
        }

        $this->view('dosen/mahasiswa', compact(
            'kelasDiampu',
            'matkulDiampu',
            'allMhs',
            'selectedKelasId',
            'selectedMatkulId',
            'selectedKelas',
            'selectedMatkul',
            'filterStatus'
        ));
    }

    public function detailMahasiswa()
    {
        $dosenId = (int) ($_SESSION['user_id'] ?? 0);
        $kuisId = (int) ($_GET['kuis_id'] ?? 0);
        $mahasiswaId = (int) ($_GET['mahasiswa_id'] ?? 0);
        $detail = $this->model->getDetailJawabanMahasiswa($dosenId, $kuisId, $mahasiswaId);

        $this->view('dosen/detail_mahasiswa', compact('detail', 'kuisId', 'mahasiswaId'));
    }

    public function matkul()
    {
        $dosenId = (int) ($_SESSION['user_id'] ?? 0);
        $matkulDiampu = $this->model->getMatkulDiampu($dosenId);

        $this->view('dosen/matkul', compact('matkulDiampu'));
    }

    public function kelas()
    {
        $dosenId = (int) ($_SESSION['user_id'] ?? 0);
        $kelasDiampu = $this->model->getKelasDiampu($dosenId);

        $this->view('dosen/kelas', compact('kelasDiampu'));
    }

    public function konfigurasiKelas()
    {
        $dosenId = (int) ($_SESSION['user_id'] ?? 0);
        $kelasId = (int) ($_GET['kelas_id'] ?? $_POST['kelas_id'] ?? 0);
        $kelas = $kelasId > 0 ? $this->model->getKelasById($dosenId, $kelasId) : null;
        $error = '';
        $success = '';

        if (!$kelas) {
            header('Location: index.php?page=dosen-kelas');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action']) && $_POST['action'] === 'tambah_matkul') {
                $pilihan = $_POST['pilihan_matkul'] ?? 'pilih';
                if ($pilihan === 'pilih') {
                    $matkulId = (int) ($_POST['matkul_id'] ?? 0);
                    if ($matkulId > 0) {
                        if ($this->model->tambahMatkulKeKelas($dosenId, $kelasId, $matkulId)) {
                            ActivityModel::log($dosenId, 'Dosen menambahkan mata kuliah ke kelas', 'dosen', $kelasId, null);
                            $success = 'Mata kuliah berhasil ditambahkan ke kelas.';
                        } else {
                            $error = 'Gagal menambahkan mata kuliah ke kelas.';
                        }
                    } else {
                        $error = 'Pilih mata kuliah yang valid.';
                    }
                } else {
                    $kode = trim($_POST['kode'] ?? '');
                    $nama = trim($_POST['nama_matkul'] ?? '');
                    $sks = min(3, max(1, (int) ($_POST['sks'] ?? 2)));
                    if ($kode !== '' && $nama !== '') {
                        if ($this->model->createAndTambahMatkulKeKelas($dosenId, $kelasId, $kode, $nama, $sks)) {
                            ActivityModel::log($dosenId, 'Dosen membuat dan menambahkan mata kuliah baru ke kelas', 'dosen', $kelasId, null);
                            $success = 'Mata kuliah baru berhasil dibuat dan ditambahkan ke kelas.';
                        } else {
                            $error = 'Gagal membuat/menambahkan mata kuliah baru.';
                        }
                    } else {
                        $error = 'Kode dan Nama mata kuliah wajib diisi.';
                    }
                }
            } else {
                $data = [
                    'nama' => trim($_POST['nama'] ?? ''),
                    'jurusan' => trim($_POST['jurusan'] ?? ''),
                    'kode_kelas' => trim($_POST['kode_kelas'] ?? ''),
                ];

                if ($data['nama'] === '') {
                    $error = 'Nama kelas wajib diisi.';
                } elseif ($this->model->updateKelasDosen($dosenId, $kelasId, $data)) {
                    ActivityModel::log($dosenId, 'Dosen mengedit konfigurasi kelas: ' . $data['nama'], 'dosen', $kelasId, null);
                    $success = 'Konfigurasi kelas berhasil diperbarui.';
                    $kelas = $this->model->getKelasById($dosenId, $kelasId);
                } else {
                    $error = 'Konfigurasi kelas gagal disimpan.';
                }
            }
        }

        $matkulDiampu = array_values(array_filter($this->model->getMatkulDiampu($dosenId), fn($mk) => (int) $mk['kelas_id'] === $kelasId));
        $allKuis = $this->model->getKuis($dosenId, '', $kelasId);
        $jurusanList = $this->model->getJurusanList();
        $keywordMahasiswa = trim($_GET['search_mahasiswa'] ?? '');
        $mahasiswaKelas = $this->model->getMahasiswaByKelas($dosenId, $kelasId, $keywordMahasiswa);
        $allMatkulList = $this->model->getAllMatkul();

        $this->view('dosen/konfigurasi_kelas', compact(
            'kelas',
            'kelasId',
            'matkulDiampu',
            'allKuis',
            'jurusanList',
            'mahasiswaKelas',
            'keywordMahasiswa',
            'error',
            'success',
            'allMatkulList'
        ));
    }

    public function hapusMahasiswaKelas()
    {
        $dosenId = (int) ($_SESSION['user_id'] ?? 0);
        $kelasId = (int) ($_GET['kelas_id'] ?? 0);
        $mahasiswaId = (int) ($_GET['mahasiswa_id'] ?? 0);

        if ($kelasId > 0 && $mahasiswaId > 0 && $this->model->hapusMahasiswaDariKelas($dosenId, $kelasId, $mahasiswaId)) {
            ActivityModel::log($dosenId, 'Dosen menghapus mahasiswa dari kelas', 'dosen', $kelasId, null);
        }

        header('Location: index.php?page=dosen-konfigurasi-kelas&kelas_id=' . $kelasId);
        exit;
    }

    public function hasil()
    {
        $dosenId = (int) ($_SESSION['user_id'] ?? 0);

        $selectedKelasId = isset($_GET['kelas_id']) && $_GET['kelas_id'] !== '' ? (int) $_GET['kelas_id'] : null;
        $selectedMatkulId = isset($_GET['matkul_id']) && $_GET['matkul_id'] !== '' ? (int) $_GET['matkul_id'] : null;

        $kelasDiampu = $this->model->getKelasDiampu($dosenId);
        $matkulDiampu = $this->model->getMatkulDiampu($dosenId);

        $nilai = [];
        $stats = [
            'tertinggi' => 0,
            'terendah' => 0,
            'rata_rata' => 0,
            'kelulusan' => 0,
        ];

        if ($selectedKelasId !== null && $selectedMatkulId !== null) {
            $nilai = $this->model->getLaporanNilai($dosenId, $selectedMatkulId, $selectedKelasId);
            $stats = $this->model->getNilaiStats($nilai);
        }

        $selectedKelas = null;
        if ($selectedKelasId !== null) {
            foreach ($kelasDiampu as $kls) {
                if ((int) $kls['kelas_id'] === $selectedKelasId) {
                    $selectedKelas = $kls;
                    break;
                }
            }
        }

        $selectedMatkul = null;
        if ($selectedMatkulId !== null) {
            foreach ($matkulDiampu as $mk) {
                if ((int) $mk['matkul_id'] === $selectedMatkulId && (int) $mk['kelas_id'] === $selectedKelasId) {
                    $selectedMatkul = $mk;
                    break;
                }
            }
        }

        $this->view('dosen/hasil', compact(
            'kelasDiampu',
            'matkulDiampu',
            'nilai',
            'stats',
            'selectedKelasId',
            'selectedMatkulId',
            'selectedKelas',
            'selectedMatkul'
        ));
    }

    private function getRelasiFilter()
    {
        $relasi = $_GET['relasi'] ?? '';
        if ($relasi !== '') {
            return $this->parseRelasi($relasi);
        }

        return [
            $_GET['matkul_id'] ?? '',
            $_GET['kelas_id'] ?? '',
        ];
    }

    private function parseRelasi($relasi)
    {
        $parts = explode('|', (string) $relasi, 2);

        return [
            isset($parts[0]) && $parts[0] !== '' ? (int) $parts[0] : 0,
            isset($parts[1]) && $parts[1] !== '' ? (int) $parts[1] : 0,
        ];
    }

    private function normalizeDateTime($value)
    {
        $value = trim((string) $value);

        return $value === '' ? null : str_replace('T', ' ', $value);
    }
}
