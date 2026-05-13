<?php
class MahasiswaModel {

    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getStats() {
        return [
            'kuis_tersedia'   => 4,
            'belum_dikerjakan' => 2,
            'selesai'         => 6,
            'nilai_terakhir'  => 88,
        ];
    }

    public function getAktivitasTerbaru() {
        $sql = "SELECT k.nama_kuis, mk.nama_matkul, k.durasi, k.jumlah_soal,
                       hk.nilai,
                       COALESCE(hk.status, 'Tersedia') AS status,
                       COALESCE(k.warna, 'blue') AS warna
                FROM kuis k
                LEFT JOIN mata_kuliah mk ON k.matkul_id = mk.id
                LEFT JOIN hasil_kuis hk ON hk.kuis_id = k.id
                ORDER BY hk.created_at DESC
                LIMIT 3";

        $result = $this->db->query($sql);

        if (!$result) return [];

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getKuisTersedia($filter = 'semua') {
        $sql = "SELECT k.id, k.nama_kuis, mk.nama_matkul, k.durasi,
                       k.jumlah_soal, k.status, k.batas_waktu, k.warna,
                       hk.nilai
                FROM kuis k
                LEFT JOIN mata_kuliah mk ON k.matkul_id = mk.id
                LEFT JOIN hasil_kuis hk ON hk.kuis_id = k.id";

        if ($filter !== 'semua') {
            $filter = $this->db->real_escape_string($filter);
            $sql .= " WHERE k.status = '$filter'";
        }

        $sql .= " ORDER BY k.created_at DESC";

        $result = $this->db->query($sql);

        if (!$result) return [];

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getKuisById($id) {
        $id     = (int) $id;
        $sql    = "SELECT k.*, mk.nama_matkul
                   FROM kuis k
                   LEFT JOIN mata_kuliah mk ON k.matkul_id = mk.id
                   WHERE k.id = $id
                   LIMIT 1";

        $result = $this->db->query($sql);
        if (!$result) return null;
        return $result->fetch_assoc();
    }

    public function getSoalByKuis($kuis_id) {
        $kuis_id = (int) $kuis_id;
        $sql     = "SELECT * FROM soal WHERE kuis_id = $kuis_id ORDER BY nomor ASC";

        $result  = $this->db->query($sql);
        if (!$result) return [];

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getNilaiStats() {
        $sql = "SELECT
                    AVG(nilai)  AS rata_rata,
                    MAX(nilai)  AS tertinggi,
                    MIN(nilai)  AS terendah,
                    COUNT(*)    AS total_selesai
                FROM hasil_kuis
                WHERE status = 'selesai'";

        $result = $this->db->query($sql);
        if (!$result) return [];
        return $result->fetch_assoc();
    }

    public function getRiwayatNilai($filter = 'semua') {
        $sql = "SELECT k.nama_kuis, mk.nama_matkul, hk.tanggal_selesai,
                       hk.durasi_aktual, hk.nilai, hk.status
                FROM hasil_kuis hk
                JOIN kuis k ON hk.kuis_id = k.id
                JOIN mata_kuliah mk ON k.matkul_id = mk.id";

        if ($filter !== 'semua') {
            $filter = $this->db->real_escape_string($filter);
            $sql .= " WHERE mk.nama_matkul = '$filter'";
        }

        $sql .= " ORDER BY hk.tanggal_selesai DESC";

        $result = $this->db->query($sql);
        if (!$result) return [];

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getMatkulList() {
        $sql    = "SELECT DISTINCT nama_matkul FROM mata_kuliah ORDER BY nama_matkul";
        $result = $this->db->query($sql);
        if (!$result) return [];

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row['nama_matkul'];
        }
        return $data;
    }
}
