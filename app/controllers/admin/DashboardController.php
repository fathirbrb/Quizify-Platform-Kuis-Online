<?php


require_once 'app/models/DashboardModel.php';

class DashboardController
{

    private $model;

    public function __construct()
    {
        $this->model = new DashboardModel();
    }


    public function index()
    {
        $stats = $this->model->getStats();

        $aktivitas = $this->model->getAktivitasTerbaru(5);

        $pageTitle = 'Dashboard Admin';
        $pageSubtitle = 'Ringkasan data dan aktivitas sistem Quizify.';
        $activePage = 'dashboard';

        require 'app/views/admin/dashboard.php';
    }

    public function aktivitasFeed()
    {
        $limit = max(1, min(50, (int) ($_GET['limit'] ?? 10)));
        header('Content-Type: application/json');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        echo json_encode([
            'ok' => true,
            'logs' => $this->model->getAktivitasTerbaru($limit),
        ]);
        exit;
    }

    public function activityLog()
    {
        $logs = $this->model->getAktivitasTerbaru(50);
        $pageTitle = 'Activity Log';
        $pageSubtitle = 'Aktivitas real-time pengguna Quizify.';
        $activePage = 'activity-log';

        require 'app/views/admin/activity_log.php';
    }

    public function activityLogFeed()
    {
        $this->aktivitasFeed();
    }
}
