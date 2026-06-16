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

        $pageTitle = 'Monitoring Sistem';
        $pageSubtitle = 'Log aktivitas dan riwayat penggunaan sistem.';
        $activePage = 'monitoring';

        require 'app/views/admin/monitoring/index.php';
    }
}