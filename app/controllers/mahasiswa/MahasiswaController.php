<?php
require_once 'app/models/MahasiswaModel.php';

class MahasiswaController
{

    private $model;

    public function __construct()
    {
        $this->model = new MahasiswaModel();
    }

    // Halaman dashboard
    public function dashboard()
    {
        $stats = $this->model->getStats();
        $aktivitas = $this->model->getAktivitasTerbaru();

        $pageTitle = 'Dashboard Mahasiswa';
        $activePage = 'dashboard';

        include 'app/views/layouts/sidebar.php';
        include 'app/views/mahasiswa/dashboard.php';
        include 'app/views/layouts/footer.php';
    }

    // Halaman kuis tersedia
    public function kuisTersedia()
    {
        $filter = $_GET['filter'] ?? 'semua';
        $kuisList = $this->model->getKuisTersedia($filter);

        $pageTitle = 'Kuis Tersedia';
        $activePage = 'kuis-tersedia';

        include 'app/views/layouts/sidebar.php';
        include 'app/views/mahasiswa/kuis-tersedia.php';
        include 'app/views/layouts/footer.php';
    }

    // Halaman kerjakan kuis
    public function kerjakan()
    {
        $kuis_id = $_GET['id'] ?? 1;
        $kuis = $this->model->getKuisById($kuis_id);
        $soalList = $this->model->getSoalByKuis($kuis_id);

        $pageTitle = 'Kerjakan Kuis';
        $activePage = 'kerjakan';

        include 'app/views/layouts/sidebar.php';
        include 'app/views/mahasiswa/kerjakan.php';
        include 'app/views/layouts/footer.php';
    }

    // Halaman nilai saya
    public function nilaiSaya()
    {
        $filter = $_GET['matkul'] ?? 'semua';
        $stats = $this->model->getNilaiStats();
        $riwayat = $this->model->getRiwayatNilai($filter);
        $matkulList = $this->model->getMatkulList();

        $pageTitle = 'Nilai Saya';
        $activePage = 'nilai';

        include 'app/views/layouts/sidebar.php';
        include 'app/views/mahasiswa/nilai.php';
        include 'app/views/layouts/footer.php';
    }
}