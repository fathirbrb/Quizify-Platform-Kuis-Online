<?php


require_once 'app/models/DashboardModel.php';

class DashboardController {

    private $model;

    public function __construct() {
        $this->model = new DashboardModel();
    }

    
    public function index() {
        $stats = $this->model->getStats();

        $aktivitas = $this->model->getAktivitasTerbaru(5);

        $pageTitle    = 'Dashboard Admin';
        $pageSubtitle = 'Ringkasan data dan aktivitas sistem Quizify.';
        $activePage   = 'dashboard';

        require 'app/views/admin/dashboard.php';
    }
}
