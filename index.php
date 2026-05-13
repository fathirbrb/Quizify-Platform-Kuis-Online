<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/database.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

switch ($page) {

    // Ini buat admin 

    case 'dashboard':
        require_once __DIR__ . '/app/controllers/admin/DashboardController.php';
        $controller = new DashboardController();
        $controller->index();
        break;

    case 'akun':
        require_once __DIR__ . '/app/controllers/admin/AkunController.php';
        $controller = new AkunController();
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';

        if (method_exists($controller, $action)) {
            $controller->$action();
        } else {
            $controller->index();
        }
        break;

    case 'matkul':
        require_once __DIR__ . '/app/controllers/admin/MatkulController.php';
        $controller = new MatkulController();
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';

        if (method_exists($controller, $action)) {
            $controller->$action();
        } else {
            $controller->index();
        }
        break;

    case 'kelas':
        require_once __DIR__ . '/app/controllers/admin/KelasController.php';
        $controller = new KelasController();
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';

        if (method_exists($controller, $action)) {
            $controller->$action();
        } else {
            $controller->index();
        }
        break;

    case 'role':
        require_once __DIR__ . '/app/controllers/admin/RoleController.php';
        $controller = new RoleController();
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';

        if (method_exists($controller, $action)) {
            $controller->$action();
        } else {
            $controller->index();
        }
        break;

    case 'monitoring':
        require_once __DIR__ . '/app/controllers/admin/MonitoringController.php';
        $controller = new MonitoringController();
        $controller->index();
        break;


    // ini buat mahasiswa

    case 'mahasiswa':
    case 'mahasiswa-dashboard':
        require_once __DIR__ . '/app/controllers/mahasiswa/MahasiswaController.php';
        $controller = new MahasiswaController();

        if (method_exists($controller, 'dashboard')) {
            $controller->dashboard();
        } else {
            require_once __DIR__ . '/app/views/mahasiswa/dashboard.php';
        }
        break;

    case 'kuis-tersedia':
        require_once __DIR__ . '/app/controllers/mahasiswa/MahasiswaController.php';
        $controller = new MahasiswaController();

        if (method_exists($controller, 'kuisTersedia')) {
            $controller->kuisTersedia();
        } else {
            require_once __DIR__ . '/app/views/mahasiswa/kuis-tersedia.php';
        }
        break;

    case 'kerjakan':
        require_once __DIR__ . '/app/controllers/mahasiswa/MahasiswaController.php';
        $controller = new MahasiswaController();

        if (method_exists($controller, 'kerjakan')) {
            $controller->kerjakan();
        } else {
            require_once __DIR__ . '/app/views/mahasiswa/kerjakan.php';
        }
        break;

    case 'nilai':
        require_once __DIR__ . '/app/controllers/mahasiswa/MahasiswaController.php';
        $controller = new MahasiswaController();

        if (method_exists($controller, 'nilai')) {
            $controller->nilai();
        } else {
            require_once __DIR__ . '/app/views/mahasiswa/nilai.php';
        }
        break;

    default:
        header('Location: index.php?page=dashboard');
        exit;
}