<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/ActivityModel.php';

class DashboardModel
{
    private $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function getStats()
    {
        return [
            'dosen' => (int) $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'dosen'")->fetchColumn(),
            'mahasiswa' => (int) $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'mahasiswa'")->fetchColumn(),
            'kelas' => (int) $this->db->query('SELECT COUNT(*) FROM kelas')->fetchColumn(),
            'matkul' => (int) $this->db->query('SELECT COUNT(*) FROM mata_kuliah')->fetchColumn(),
            'kuis' => (int) $this->db->query('SELECT COUNT(*) FROM kuis')->fetchColumn(),
        ];
    }

    public function getAktivitasTerbaru($limit = 5)
    {
        return ActivityModel::getLogs($limit);
    }
}
