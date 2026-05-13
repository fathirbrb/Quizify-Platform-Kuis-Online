<?php


class AkunModel
{

    private $db;

    public function __construct()
    {
        $this->db = getDB();
    }


    public function getAll($search = '', $role = '')
    {
        $sql = "SELECT id, nama, email, role, nim_nip, created_at FROM users WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (nama LIKE :search OR email LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        if (!empty($role)) {
            $sql .= " AND role = :role";
            $params[':role'] = $role;
        }

        $sql .= " ORDER BY created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }


    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }


    public function emailSudahAda($email, $kecualiId = null)
    {
        $sql = "SELECT COUNT(*) as total FROM users WHERE email = :email";
        $params = [':email' => $email];

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
        $sql = "INSERT INTO users (nama, email, password, role, nim_nip)
                VALUES (:nama, :email, :password, :role, :nim_nip)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nama' => $data['nama'],
            ':email' => $data['email'],
            ':password' => $data['password'],
            ':role' => $data['role'],
            ':nim_nip' => $data['nim_nip'],
        ]);
    }


    public function edit($data)
    {
        if (isset($data['password'])) {
            $sql = "UPDATE users SET nama=:nama, email=:email, password=:password,
                    role=:role, nim_nip=:nim_nip WHERE id=:id";
            $params = [
                ':nama' => $data['nama'],
                ':email' => $data['email'],
                ':password' => $data['password'],
                ':role' => $data['role'],
                ':nim_nip' => $data['nim_nip'],
                ':id' => $data['id'],
            ];
        } else {
            $sql = "UPDATE users SET nama=:nama, email=:email,
                    role=:role, nim_nip=:nim_nip WHERE id=:id";
            $params = [
                ':nama' => $data['nama'],
                ':email' => $data['email'],
                ':role' => $data['role'],
                ':nim_nip' => $data['nim_nip'],
                ':id' => $data['id'],
            ];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
    }


    public function hapus($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }


    public function ubahRole($id, $role)
    {
        $stmt = $this->db->prepare("UPDATE users SET role = :role WHERE id = :id");
        $stmt->execute([':role' => $role, ':id' => $id]);
    }
}