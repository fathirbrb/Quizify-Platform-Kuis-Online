<?php


class DashboardModel {

    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    
    public function getStats() {
        $stats = [];

        $stmt = $this->db->query("SELECT COUNT(*) as total FROM users WHERE role = 'dosen'");
        $stats['dosen'] = $stmt->fetch()['total'];

        $stmt = $this->db->query("SELECT COUNT(*) as total FROM users WHERE role = 'mahasiswa'");
        $stats['mahasiswa'] = $stmt->fetch()['total'];

        $stmt = $this->db->query("SELECT COUNT(*) as total FROM kelas");
        $stats['kelas'] = $stmt->fetch()['total'];

        $stmt = $this->db->query("SELECT COUNT(*) as total FROM mata_kuliah");
        $stats['matkul'] = $stmt->fetch()['total'];

        $stats['kuis'] = 0;

        return $stats;
    }

    
    public function getAktivitasTerbaru($limit = 5) {
        $stmt = $this->db->prepare(
            "SELECT al.aksi, al.ip_address, al.created_at, u.nama, u.role
             FROM activity_log al
             LEFT JOIN users u ON al.user_id = u.id
             ORDER BY al.created_at DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
