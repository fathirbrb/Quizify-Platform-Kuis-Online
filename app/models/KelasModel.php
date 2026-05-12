<?php


class KelasModel {

    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll() {
        return $this->db->query("SELECT * FROM kelas ORDER BY created_at DESC")->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM kelas WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function tambah($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO kelas (nama, tahun_ajaran) VALUES (:nama, :tahun_ajaran)"
        );
        $stmt->execute([':nama' => $data['nama'], ':tahun_ajaran' => $data['tahun_ajaran']]);
    }

    public function edit($data) {
        $stmt = $this->db->prepare(
            "UPDATE kelas SET nama=:nama, tahun_ajaran=:tahun_ajaran WHERE id=:id"
        );
        $stmt->execute([':nama' => $data['nama'], ':tahun_ajaran' => $data['tahun_ajaran'], ':id' => $data['id']]);
    }

    public function hapus($id) {
        $stmt = $this->db->prepare("DELETE FROM kelas WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}
