<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/ActivityModel.php';

class MonitoringModel {

    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    
    public function getLogs($limit = 50) {
        return ActivityModel::getLogs($limit);
    }

    
    public function getStatsLogin() {
        $stmt = $this->db->query(
            "SELECT u.role, COUNT(al.id) as total
             FROM activity_log al
             LEFT JOIN users u ON al.user_id = u.id
             WHERE al.aksi LIKE '%Login%'
             GROUP BY u.role"
        );
        return $stmt->fetchAll();
    }
}
