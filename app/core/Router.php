<?php

class Router
{
    private $routes = [
        'dashboard' => ['admin/DashboardController.php', 'DashboardController', 'index', 'admin'],
        'admin-activity-feed' => ['admin/DashboardController.php', 'DashboardController', 'aktivitasFeed', 'admin'],
        'admin-activity-log' => ['admin/DashboardController.php', 'DashboardController', 'activityLog', 'admin'],
        'admin-activity-log-feed' => ['admin/DashboardController.php', 'DashboardController', 'activityLogFeed', 'admin'],
        'akun' => ['admin/AkunController.php', 'AkunController', null, 'admin'],
        'matkul' => ['admin/MatkulController.php', 'MatkulController', null, 'admin'],
        'kelas' => ['admin/KelasController.php', 'KelasController', null, 'admin'],


        'dosen-dashboard' => ['dosen/DosenController.php', 'DosenController', 'dashboard', 'dosen'],
        'dosen-kuis' => ['dosen/DosenController.php', 'DosenController', 'kuis', 'dosen'],
        'kuis' => ['dosen/DosenController.php', 'DosenController', 'kuis', 'dosen'],
        'dosen-tambah-kuis' => ['dosen/DosenController.php', 'DosenController', 'tambahKuis', 'dosen'],
        'tambah-kuis' => ['dosen/DosenController.php', 'DosenController', 'tambahKuis', 'dosen'],
        'dosen-konfigurasi-kuis' => ['dosen/DosenController.php', 'DosenController', 'konfigurasiKuis', 'dosen'],
        'konfigurasi-kuis' => ['dosen/DosenController.php', 'DosenController', 'konfigurasiKuis', 'dosen'],
        'dosen-soal' => ['dosen/DosenController.php', 'DosenController', 'soal', 'dosen'],
        'soal' => ['dosen/DosenController.php', 'DosenController', 'soal', 'dosen'],
        'dosen-tambah-soal' => ['dosen/DosenController.php', 'DosenController', 'tambahSoal', 'dosen'],
        'tambah-soal' => ['dosen/DosenController.php', 'DosenController', 'tambahSoal', 'dosen'],
        'dosen-mahasiswa' => ['dosen/DosenController.php', 'DosenController', 'mahasiswa', 'dosen'],
        'dosen-detail-mahasiswa' => ['dosen/DosenController.php', 'DosenController', 'detailMahasiswa', 'dosen'],
        'dosen-kelas' => ['dosen/DosenController.php', 'DosenController', 'kelas', 'dosen'],
        'dosen-konfigurasi-kelas' => ['dosen/DosenController.php', 'DosenController', 'konfigurasiKelas', 'dosen'],
        'dosen-hapus-mahasiswa-kelas' => ['dosen/DosenController.php', 'DosenController', 'hapusMahasiswaKelas', 'dosen'],
        'dosen-matkul' => ['dosen/DosenController.php', 'DosenController', 'matkul', 'dosen'],
        'dosen-hasil' => ['dosen/DosenController.php', 'DosenController', 'hasil', 'dosen'],
        'hasil' => ['dosen/DosenController.php', 'DosenController', 'hasil', 'dosen'],

        'mahasiswa' => ['mahasiswa/MahasiswaController.php', 'MahasiswaController', 'dashboard', 'mahasiswa'],
        'mahasiswa-dashboard' => ['mahasiswa/MahasiswaController.php', 'MahasiswaController', 'dashboard', 'mahasiswa'],
        'mahasiswa-gabung-kelas' => ['mahasiswa/MahasiswaController.php', 'MahasiswaController', 'gabungKelas', 'mahasiswa'],
        'kuis-tersedia' => ['mahasiswa/MahasiswaController.php', 'MahasiswaController', 'kuisTersedia', 'mahasiswa'],
        'detail-kelas' => ['mahasiswa/MahasiswaController.php', 'MahasiswaController', 'detailKelas', 'mahasiswa'],
        'mahasiswa-kuis-feed' => ['mahasiswa/MahasiswaController.php', 'MahasiswaController', 'kuisFeed', 'mahasiswa'],
        'kerjakan-kuis' => ['mahasiswa/MahasiswaController.php', 'MahasiswaController', 'kerjakanKuis', 'mahasiswa'],
        'mahasiswa-kuis-berjalan-feed' => ['mahasiswa/MahasiswaController.php', 'MahasiswaController', 'kuisBerjalanFeed', 'mahasiswa'],
        'mahasiswa-autosave-kuis' => ['mahasiswa/MahasiswaController.php', 'MahasiswaController', 'autosaveKuis', 'mahasiswa'],
        'kerjakan' => ['mahasiswa/MahasiswaController.php', 'MahasiswaController', 'kerjakan', 'mahasiswa'],
        'nilai' => ['mahasiswa/MahasiswaController.php', 'MahasiswaController', 'nilai', 'mahasiswa'],
    ];

    private $roleHome = [
        'admin' => 'index.php?page=dashboard',
        'dosen' => 'index.php?page=dosen-dashboard',
        'mahasiswa' => 'index.php?page=mahasiswa-dashboard',
    ];

    public function dispatch($page, $action)
    {
        // Landing page: tampilkan jika belum login dan tidak ada page
        if ($page === null || $page === '') {
            if (isset($_SESSION['user_id'])) {
                $this->redirectHome();
            }
            require APP_ROOT . '/app/views/landing.php';
            return;
        }

        // Explicit landing route
        if ($page === 'landing') {
            if (isset($_SESSION['user_id'])) {
                $this->redirectHome();
            }
            require APP_ROOT . '/app/views/landing.php';
            return;
        }

        if ($page === 'login' || $page === 'logout') {
            $this->dispatchAuth($page, $action);
            return;
        }

        $this->guardLogin();

        if (!isset($this->routes[$page])) {
            $this->redirectHome();
        }

        [$file, $class, $defaultMethod, $role] = $this->routes[$page];
        $this->guardRole($role);

        require_once APP_ROOT . '/app/controllers/' . $file;

        $controller = new $class();
        $method = $defaultMethod ?: $this->sanitizeAction($action);

        if (!is_callable([$controller, $method])) {
            $method = 'index';
        }

        $controller->$method();
    }

    private function dispatchAuth($page, $action)
    {
        require_once APP_ROOT . '/app/controllers/AuthController.php';

        $controller = new AuthController();

        if ($page === 'logout') {
            $controller->logout();
            return;
        }

        if ($action === 'process' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->loginProcess();
            return;
        }

        $controller->loginPage();
    }

    private function guardLogin()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
    }

    private function guardRole($requiredRole)
    {
        $sessionRole = $_SESSION['role'] ?? '';

        if ($sessionRole !== $requiredRole) {
            header('Location: index.php?page=login');
            exit;
        }
    }

    private function redirectHome()
    {
        $role = $_SESSION['role'] ?? 'admin';
        header('Location: ' . ($this->roleHome[$role] ?? $this->roleHome['admin']));
        exit;
    }

    private function sanitizeAction($action)
    {
        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $action)) {
            return 'index';
        }

        return $action;
    }
}
