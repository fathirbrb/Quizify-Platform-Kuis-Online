<?php

require_once __DIR__ . '/../../config/database.php';

class MatkulModel
{
    private $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function getAll()
    {
        $stmt = $this->db->query(
            'SELECT id, kode, nama, sks, created_at
             FROM mata_kuliah
             ORDER BY id ASC'
        );

        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare(
            'SELECT id, kode, nama, sks, created_at
             FROM mata_kuliah
             WHERE id = :id
             LIMIT 1'
        );
        $stmt->execute(['id' => $id]);

        return $stmt->fetch() ?: null;
    }

    public function kodeSudahAda($kode, $kecualiId = null)
    {
        $sql = 'SELECT COUNT(*) FROM mata_kuliah WHERE kode = :kode';
        $params = ['kode' => $kode];

        if ($kecualiId !== null) {
            $sql .= ' AND id != :id';
            $params['id'] = $kecualiId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn() > 0;
    }

    public function tambah($data)
    {
        $stmt = $this->db->prepare(
            'INSERT INTO mata_kuliah (kode, nama, sks)
             VALUES (:kode, :nama, :sks)'
        );

        return $stmt->execute([
            'kode' => $data['kode'],
            'nama' => $data['nama'],
            'sks'  => $data['sks'],
        ]);
    }

    public function edit($data)
    {
        $stmt = $this->db->prepare(
            'UPDATE mata_kuliah
             SET kode = :kode, nama = :nama, sks = :sks
             WHERE id = :id'
        );

        return $stmt->execute([
            'id'   => $data['id'],
            'kode' => $data['kode'],
            'nama' => $data['nama'],
            'sks'  => $data['sks'],
        ]);
    }

    public function hapus($id)
    {
        $stmt = $this->db->prepare('DELETE FROM mata_kuliah WHERE id = :id');

        return $stmt->execute(['id' => $id]);
    }
}
