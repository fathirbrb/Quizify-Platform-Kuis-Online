<?php

require_once __DIR__ . '/../../config/database.php';

class AkunModel
{
    private $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function getAll($search = '', $role = '')
    {
        $sql = 'SELECT id, nama, email, role, nim_nip, created_at FROM users WHERE 1=1';
        $params = [];

        if ($search !== '') {
            $sql .= ' AND (nama LIKE :search OR email LIKE :search)';
            $params['search'] = '%' . $search . '%';
        }

        if ($role !== '') {
            $sql .= ' AND role = :role';
            $params['role'] = $role;
        }

        $sql .= ' ORDER BY id ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare(
            'SELECT id, nama, email, role, nim_nip, created_at FROM users WHERE id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $id]);

        return $stmt->fetch() ?: null;
    }

    public function emailSudahAda($email, $kecualiId = null)
    {
        $sql = 'SELECT COUNT(*) FROM users WHERE email = :email';
        $params = ['email' => $email];

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
            'INSERT INTO users (nama, email, password, role, nim_nip)
             VALUES (:nama, :email, :password, :role, :nim_nip)'
        );

        return $stmt->execute([
            'nama'     => $data['nama'],
            'email'    => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role'     => $data['role'],
            'nim_nip'  => $data['nim_nip'] ?: null,
        ]);
    }

    public function edit($data)
    {
        $params = [
            'id'      => $data['id'],
            'nama'    => $data['nama'],
            'email'   => $data['email'],
            'role'    => $data['role'],
            'nim_nip' => $data['nim_nip'] ?: null,
        ];

        $passwordSql = '';
        if (!empty($data['password'])) {
            $passwordSql = ', password = :password';
            $params['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $stmt = $this->db->prepare(
            'UPDATE users
             SET nama = :nama, email = :email, role = :role, nim_nip = :nim_nip' . $passwordSql . '
             WHERE id = :id'
        );

        return $stmt->execute($params);
    }

    public function resetPassword($id, $password = 'password123')
    {
        $stmt = $this->db->prepare('UPDATE users SET password = :password WHERE id = :id');

        return $stmt->execute([
            'id'       => $id,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);
    }

    public function hapus($id)
    {
        $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');

        return $stmt->execute(['id' => $id]);
    }

    public function ubahRole($id, $role)
    {
        $stmt = $this->db->prepare('UPDATE users SET role = :role WHERE id = :id');

        return $stmt->execute([
            'id'   => $id,
            'role' => $role,
        ]);
    }
}
