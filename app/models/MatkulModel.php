<?php


class MatkulModel
{

    private $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function getAll()
    {
        return $this->db->query("SELECT * FROM mata_kuliah ORDER BY created_at DESC")->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM mata_kuliah WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function kodeSudahAda($kode, $kecualiId = null)
    {
        $sql = "SELECT COUNT(*) as total FROM mata_kuliah WHERE kode = :kode";
        $params = [':kode' => $kode];
        if ($kecualiId) {
            $sql .= " AND id != :id";
            $params[':id'] = $kecualiId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch()['total'] > 0;
    }

    public function tambah($data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO mata_kuliah (kode, nama, sks) VALUES (:kode, :nama, :sks)"
        );
        $stmt->execute([':kode' => $data['kode'], ':nama' => $data['nama'], ':sks' => $data['sks']]);
    }

    public function edit($data)
    {
        $stmt = $this->db->prepare(
            "UPDATE mata_kuliah SET kode=:kode, nama=:nama, sks=:sks WHERE id=:id"
        );
        $stmt->execute([':kode' => $data['kode'], ':nama' => $data['nama'], ':sks' => $data['sks'], ':id' => $data['id']]);
    }

    public function hapus($id)
    {
        $stmt = $this->db->prepare("DELETE FROM mata_kuliah WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}
