<?php

require_once 'app/models/MonitoringModel.php';

class MonitoringController
{
    private $model;

    public function __construct()
    {
        $this->model = new MonitoringModel();
    }

    public function index()
    {
        $logs = $this->model->getLogs(50);
        $statsLogin = $this->model->getStatsLogin();

        $pageTitle = 'Activity Log';
        $pageSubtitle = 'Riwayat aktivitas utama pengguna.';
        $activePage = 'monitoring';

        require 'app/views/admin/monitoring/index.php';
    }
}
