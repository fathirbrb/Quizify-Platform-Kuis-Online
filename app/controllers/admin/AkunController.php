<?php


require_once 'app/models/AkunModel.php';

class AkunController {

    private $model;

    public function __construct() {
        $this->model = new AkunModel();
    }

    
    public function index() {
        $search     = isset($_GET['search']) ? trim($_GET['search']) : '';
        $filterRole = isset($_GET['role'])   ? $_GET['role']         : '';

        $akuns = $this->model->getAll($search, $filterRole);

        $pageTitle    = 'Kelola Akun';
        $pageSubtitle = 'Tambah, edit, dan hapus akun dosen maupun mahasiswa.';
        $activePage   = 'akun';

        require 'app/views/admin/akun/index.php';
    }

    
    public function tambah() {
        $pageTitle    = 'Tambah Akun';
        $pageSubtitle = 'Buat akun pengguna baru.';
        $activePage   = 'akun';
        $error        = '';
        $sukses       = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nama'    => trim($_POST['nama']    ?? ''),
                'email'   => trim($_POST['email']   ?? ''),
                'password'=> trim($_POST['password'] ?? ''),
                'role'    => trim($_POST['role']    ?? 'mahasiswa'),
                'nim_nip' => trim($_POST['nim_nip'] ?? ''),
            ];

            if (empty($data['nama']) || empty($data['email']) || empty($data['password'])) {
                $error = 'Nama, email, dan password wajib diisi.';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $error = 'Format email tidak valid.';
            } elseif ($this->model->emailSudahAda($data['email'])) {
                $error = 'Email sudah terdaftar. Gunakan email lain.';
            } else {
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                $this->model->tambah($data);
                $sukses = 'Akun berhasil ditambahkan!';
            }
        }

        require 'app/views/admin/akun/form.php';
    }

    
    public function edit() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id === 0) {
            header('Location: index.php?page=akun');
            exit;
        }

        $akun         = $this->model->getById($id);
        $pageTitle    = 'Edit Akun';
        $pageSubtitle = 'Ubah data akun pengguna.';
        $activePage   = 'akun';
        $error        = '';
        $sukses       = '';

        if (!$akun) {
            header('Location: index.php?page=akun');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id'      => $id,
                'nama'    => trim($_POST['nama']    ?? ''),
                'email'   => trim($_POST['email']   ?? ''),
                'role'    => trim($_POST['role']    ?? 'mahasiswa'),
                'nim_nip' => trim($_POST['nim_nip'] ?? ''),
            ];

            if (!empty($_POST['password'])) {
                $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            if (empty($data['nama']) || empty($data['email'])) {
                $error = 'Nama dan email wajib diisi.';
            } else {
                $this->model->edit($data);
                $sukses = 'Akun berhasil diperbarui!';
                $akun   = $this->model->getById($id);
            }
        }

        require 'app/views/admin/akun/form.php';
    }

    
    public function hapus() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            $this->model->hapus($id);
        }
        header('Location: index.php?page=akun&sukses=hapus');
        exit;
    }
}
