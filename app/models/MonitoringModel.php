<?php


class MonitoringModel {

    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    
    public function getLogs($limit = 50) {
        $stmt = $this->db->prepare(
            "SELECT al.id, al.aksi, al.ip_address, al.created_at,
                    u.nama, u.email, u.role
             FROM activity_log al
             LEFT JOIN users u ON al.user_id = u.id
             ORDER BY al.created_at DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
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
