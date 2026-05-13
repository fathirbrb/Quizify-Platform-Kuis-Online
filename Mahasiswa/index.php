<?php
<<<<<<< HEAD
session_start();

require_once 'config/database.php';
require_once 'app/controllers/MahasiswaController.php';

$page = $_GET['page'] ?? 'dashboard';

$controller = new MahasiswaController();

switch ($page) {
    case 'dashboard':
        $controller->dashboard();
        break;
    case 'kuis-tersedia':
        $controller->kuisTersedia();
        break;
    case 'kerjakan':
        $controller->kerjakan();
        break;
    case 'nilai':
        $controller->nilaiSaya();
        break;
    case 'logout':
        session_destroy();
        header('Location: index.php?page=dashboard');
        exit;
    default:
        $controller->dashboard();
        break;
=======


require_once 'config/database.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

switch ($page) {

    case 'dashboard':
        require_once 'app/controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->index();
        break;

    case 'akun':
        require_once 'app/controllers/AkunController.php';
        $controller = new AkunController();
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';
        if (method_exists($controller, $action)) {
            $controller->$action();
        } else {
            $controller->index();
        }
        break;

    case 'matkul':
        require_once 'app/controllers/MatkulController.php';
        $controller = new MatkulController();
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';
        if (method_exists($controller, $action)) {
            $controller->$action();
        } else {
            $controller->index();
        }
        break;

    case 'kelas':
        require_once 'app/controllers/KelasController.php';
        $controller = new KelasController();
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';
        if (method_exists($controller, $action)) {
            $controller->$action();
        } else {
            $controller->index();
        }
        break;

    case 'role':
        require_once 'app/controllers/RoleController.php';
        $controller = new RoleController();
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';
        if (method_exists($controller, $action)) {
            $controller->$action();
        } else {
            $controller->index();
        }
        break;

    case 'monitoring':
        require_once 'app/controllers/MonitoringController.php';
        $controller = new MonitoringController();
        $controller->index();
        break;

    default:
        header('Location: index.php?page=dashboard');
        exit;
>>>>>>> 35a41c870a522e72542a736bd10557eb44ccaa08
}
