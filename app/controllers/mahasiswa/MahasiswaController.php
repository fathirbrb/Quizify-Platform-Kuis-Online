<?php
require_once 'app/models/MahasiswaModel.php';
require_once 'app/models/ActivityModel.php';

class MahasiswaController
{
    private $model;

    public function __construct()
    {
        $this->model = new MahasiswaModel();
    }

    public function dashboard()
{
    $mahasiswaId = (int) ($_SESSION['user_id'] ?? 0);
    $stats = $this->model->getStats($mahasiswaId);
    $aktivitas = $this->model->getAktivitasTerbaru($mahasiswaId);
    $searchKelas = trim($_GET['search_kelas'] ?? '');
    $kelasResults = $searchKelas !== '' ? $this->model->searchKelas($searchKelas, $mahasiswaId) : [];
    $joinMessage = $_SESSION['join_kelas_message'] ?? null;
    unset($_SESSION['join_kelas_message']);

    include 'app/views/layouts/MahasiswaLayout/header.php';
    include 'app/views/layouts/MahasiswaLayout/sidebar.php';
    include 'app/views/layouts/MahasiswaLayout/navbar.php';
    include 'app/views/mahasiswa/dashboard.php';
    include 'app/views/layouts/MahasiswaLayout/footer.php';
}

    public function gabungKelas()
    {
        $mahasiswaId = (int) ($_SESSION['user_id'] ?? 0);
        $kelasId = (int) ($_POST['kelas_id'] ?? 0);
        $kode = trim($_POST['kode_kelas'] ?? '');
        $result = $this->model->gabungKelasDenganKode($mahasiswaId, $kode, $kelasId);
        $_SESSION['join_kelas_message'] = [
            'ok' => !empty($result['ok']),
            'text' => $result['message'] ?? '',
        ];

        if (!empty($result['ok'])) {
            ActivityModel::log($mahasiswaId, 'Mahasiswa join kelas', 'mahasiswa', $kelasId, null);
        }

        header('Location: index.php?page=mahasiswa-dashboard');
        exit;
    }

    public function kuisTersedia()
    {
        $mahasiswaId = (int) ($_SESSION['user_id'] ?? 0);
        $kelasList = $this->model->getKelasSaya($mahasiswaId);

        include 'app/views/layouts/MahasiswaLayout/header.php';
        include 'app/views/layouts/MahasiswaLayout/sidebar.php';
        include 'app/views/layouts/MahasiswaLayout/navbar.php';

        include 'app/views/mahasiswa/kuis-tersedia.php';

        include 'app/views/layouts/MahasiswaLayout/footer.php';
    }

    public function detailKelas()
    {
        $mahasiswaId = (int) ($_SESSION['user_id'] ?? 0);
        $kelasId = (int) ($_GET['id'] ?? 0);

        $kelas = $this->model->getKelasById($kelasId);
        if (!$kelas) {
            header('Location: index.php?page=kuis-tersedia');
            exit;
        }

        $kuisList = $this->model->getKuisPerKelas($mahasiswaId, $kelasId);

        include 'app/views/layouts/MahasiswaLayout/header.php';
        include 'app/views/layouts/MahasiswaLayout/sidebar.php';
        include 'app/views/layouts/MahasiswaLayout/navbar.php';

        include 'app/views/mahasiswa/detail-kelas.php';

        include 'app/views/layouts/MahasiswaLayout/footer.php';
    }

    public function kuisFeed()
    {
        $mahasiswaId = (int) ($_SESSION['user_id'] ?? 0);
        $filterStatus = $_GET['status'] ?? '';
        [$filterMatkul, $filterKelas] = $this->getRelasiFilter();
        $kuisList = $this->model->getKuisTersedia($mahasiswaId, $filterStatus, $filterMatkul, $filterKelas);

        header('Content-Type: application/json');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        echo json_encode([
            'ok' => true,
            'updated_at' => date('Y-m-d H:i:s'),
            'kuis' => $kuisList,
        ]);
        exit;
    }

    public function kerjakan()
    {
        $mahasiswaId = (int) ($_SESSION['user_id'] ?? 0);
        $kuis_id = (int) ($_GET['id'] ?? $_POST['kuis_id'] ?? 1);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mode = $_POST['mode'] ?? 'kumpulkan';

            if ($this->model->isKuisSelesai($mahasiswaId, $kuis_id)) {
                header('Location: index.php?page=nilai');
                exit;
            }
            $kuisInfo = $this->model->getKuisById($kuis_id);
            $pengerjaan = $this->model->getOrCreatePengerjaan($mahasiswaId, $kuis_id);
            $sisaDetik = $this->model->getSisaWaktuKuis((int) ($pengerjaan['id'] ?? 0), (int) ($kuisInfo['durasi'] ?? 30));
            if ($sisaDetik <= 0) {
                $result = $this->model->submitKuis($mahasiswaId, $kuis_id, $_POST['jawaban'] ?? [], true);
                if ($result) {
                    $_SESSION['kuis_result'] = $result;
                    ActivityModel::log($mahasiswaId, 'Timer kuis berakhir otomatis', 'mahasiswa', (int) ($kuisInfo['kelas_id'] ?? 0), $kuis_id);
                }
                header('Location: index.php?page=nilai');
                exit;
            }

            if ($mode === 'simpan') {
                $this->model->simpanJawabanSementara($mahasiswaId, $kuis_id, $_POST['jawaban'] ?? []);
                header('Location: index.php?page=kerjakan-kuis');
                exit;
            }

            $result = $this->model->submitKuis($mahasiswaId, $kuis_id, $_POST['jawaban'] ?? [], false);
            if ($result) {
                $_SESSION['kuis_result'] = $result;
                ActivityModel::log($mahasiswaId, 'Mahasiswa menyelesaikan kuis', 'mahasiswa', (int) ($kuisInfo['kelas_id'] ?? 0), $kuis_id);
            }
            header('Location: index.php?page=nilai');
            exit;
        }

        $kuis = $this->model->getKuisById($kuis_id);
        if (!$kuis || $this->model->isKuisSelesai($mahasiswaId, $kuis_id)) {
            header('Location: index.php?page=nilai');
            exit;
        }

        $soalList = $this->model->getSoalByKuis($kuis_id);
        $pengerjaan = $this->model->getOrCreatePengerjaan($mahasiswaId, $kuis_id);
        ActivityModel::log($mahasiswaId, 'Mahasiswa membuka kuis: ' . ($kuis['nama_kuis'] ?? $kuis_id), 'mahasiswa', (int) ($kuis['kelas_id'] ?? 0), $kuis_id);
        $jawabanTersimpan = $this->model->getJawabanPengerjaan((int) ($pengerjaan['id'] ?? 0));
        $sisaDetik = $this->model->getSisaWaktuKuis((int) ($pengerjaan['id'] ?? 0), (int) ($kuis['durasi'] ?? 30));

        if ($sisaDetik <= 0) {
            $this->model->submitKuis($mahasiswaId, $kuis_id, $jawabanTersimpan, true);
            ActivityModel::log($mahasiswaId, 'Timer kuis berakhir otomatis', 'mahasiswa', (int) ($kuis['kelas_id'] ?? 0), $kuis_id);
            header('Location: index.php?page=nilai');
            exit;
        }

        include 'app/views/layouts/MahasiswaLayout/header.php';
        include 'app/views/layouts/MahasiswaLayout/sidebar.php';
        include 'app/views/layouts/MahasiswaLayout/navbar.php';

        include 'app/views/mahasiswa/kerjakan.php';

        include 'app/views/layouts/MahasiswaLayout/footer.php';
    }

    public function kerjakanKuis()
    {
        $mahasiswaId = (int) ($_SESSION['user_id'] ?? 0);
        $kuisBerjalan = $this->model->getKuisBerjalan($mahasiswaId);

        include 'app/views/layouts/MahasiswaLayout/header.php';
        include 'app/views/layouts/MahasiswaLayout/sidebar.php';
        include 'app/views/layouts/MahasiswaLayout/navbar.php';

        include 'app/views/mahasiswa/kerjakan-kuis.php';

        include 'app/views/layouts/MahasiswaLayout/footer.php';
    }

    public function kuisBerjalanFeed()
    {
        $mahasiswaId = (int) ($_SESSION['user_id'] ?? 0);
        $kuisBerjalan = $this->model->getKuisBerjalan($mahasiswaId);

        header('Content-Type: application/json');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        echo json_encode([
            'ok' => true,
            'updated_at' => date('Y-m-d H:i:s'),
            'kuis' => $kuisBerjalan,
        ]);
        exit;
    }

    public function autosaveKuis()
    {
        $mahasiswaId = (int) ($_SESSION['user_id'] ?? 0);
        $kuisId = (int) ($_POST['kuis_id'] ?? 0);

        header('Content-Type: application/json');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

        if ($this->model->isKuisSelesai($mahasiswaId, $kuisId)) {
            echo json_encode(['ok' => false, 'finished' => true]);
            exit;
        }

        $kuis = $this->model->getKuisById($kuisId);
        $pengerjaan = $this->model->getOrCreatePengerjaan($mahasiswaId, $kuisId);
        $sisaDetik = $this->model->getSisaWaktuKuis((int) ($pengerjaan['id'] ?? 0), (int) ($kuis['durasi'] ?? 30));
        if ($sisaDetik <= 0) {
            $result = $this->model->submitKuis($mahasiswaId, $kuisId, $_POST['jawaban'] ?? [], true);
            ActivityModel::log($mahasiswaId, 'Timer kuis berakhir otomatis', 'mahasiswa', (int) ($kuis['kelas_id'] ?? 0), $kuisId);
            echo json_encode(['ok' => (bool) $result, 'finished' => true, 'auto_submitted' => true]);
            exit;
        }

        echo json_encode([
            'ok' => $this->model->simpanJawabanSementara($mahasiswaId, $kuisId, $_POST['jawaban'] ?? []),
        ]);
        exit;
    }

    public function nilaiSaya()
    {
        $mahasiswaId = (int) ($_SESSION['user_id'] ?? 0);
        [$filterMatkul, $filterKelas] = $this->getRelasiFilter();
        $filterOptions = $this->model->getFilterOptions($mahasiswaId);
        $allRiwayat = $this->model->getRiwayatNilai($mahasiswaId, $filterMatkul, $filterKelas);
        $stats = $this->model->getNilaiStats($allRiwayat);
        $perPage = 5;
        $page = max(1, (int) ($_GET['p'] ?? 1));
        $total = count($allRiwayat);
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = min($page, $totalPages);
        $riwayat = array_slice($allRiwayat, ($page - 1) * $perPage, $perPage);
        $baseUrl = 'index.php?page=nilai'
            . ($filterMatkul !== '' ? '&matkul_id=' . urlencode($filterMatkul) : '')
            . ($filterKelas !== '' ? '&kelas_id=' . urlencode($filterKelas) : '');

        include 'app/views/layouts/MahasiswaLayout/header.php';
        include 'app/views/layouts/MahasiswaLayout/sidebar.php';
        include 'app/views/layouts/MahasiswaLayout/navbar.php';

        include 'app/views/mahasiswa/nilai.php';

        include 'app/views/layouts/MahasiswaLayout/footer.php';
    }

    public function nilai()
    {
        $this->nilaiSaya();
    }

    private function getRelasiFilter()
    {
        $relasi = $_GET['relasi'] ?? '';
        if ($relasi !== '') {
            $parts = explode('|', $relasi, 2);

            return [
                isset($parts[0]) && $parts[0] !== '' ? (int) $parts[0] : 0,
                isset($parts[1]) && $parts[1] !== '' ? (int) $parts[1] : 0,
            ];
        }

        return [
            $_GET['matkul_id'] ?? '',
            $_GET['kelas_id'] ?? '',
        ];
    }
}
