<?php

require_once 'app/models/AkunModel.php';

class AkunController
{
    private $perPage = 8;
    private $model;

    public function __construct()
    {
        $this->model = new AkunModel();
    }

    public function index()
    {
        $search = trim($_GET['search'] ?? '');
        $filterRole = $_GET['role'] ?? '';
        $page = max(1, (int) ($_GET['p'] ?? 1));
        $perPage = $this->perPage;

        $all = $this->model->getAll($search, $filterRole);
        $total = count($all);
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = min($page, $totalPages);
        $akuns = array_slice($all, ($page - 1) * $perPage, $perPage);

        $pageTitle = 'Kelola Akun';
        $pageSubtitle = 'Tambah, edit, dan hapus akun pengguna.';
        $activePage = 'akun';

        require 'app/views/admin/akun/index.php';
    }

    public function tambah()
    {
        $pageTitle = 'Tambah Akun';
        $pageSubtitle = 'Buat akun pengguna baru.';
        $activePage = 'akun';
        $akun = null;
        $error = $sukses = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = trim($_POST['nama'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $pw = trim($_POST['password'] ?? '');
            $role = trim($_POST['role'] ?? 'mahasiswa');
            $nimNip = trim($_POST['nim_nip'] ?? '');

            if (empty($nama) || empty($email) || empty($pw)) {
                $error = 'Nama, email, dan password wajib diisi.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Format email tidak valid.';
            } elseif (!in_array($role, ['admin', 'dosen', 'mahasiswa'])) {
                $error = 'Role tidak valid.';
            } elseif ($this->model->emailSudahAda($email)) {
                $error = 'Email sudah digunakan.';
            } else {
                $this->model->tambah([
                    'nama'     => $nama,
                    'email'    => $email,
                    'password' => $pw,
                    'role'     => $role,
                    'nim_nip'  => $nimNip,
                ]);
                header('Location: index.php?page=akun&sukses=simpan');
                exit;
            }
        }

        require 'app/views/admin/akun/form.php';
    }

    public function edit()
    {
        $id = (int) ($_GET['id'] ?? 0);
        $akun = $this->model->getById($id);

        if (!$akun) {
            header('Location: index.php?page=akun');
            exit;
        }

        $pageTitle = 'Edit Akun';
        $pageSubtitle = 'Ubah data akun pengguna.';
        $activePage = 'akun';
        $error = $sukses = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = trim($_POST['nama'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $pw = trim($_POST['password'] ?? '');
            $role = trim($_POST['role'] ?? 'mahasiswa');
            $nimNip = trim($_POST['nim_nip'] ?? '');

            if (empty($nama) || empty($email)) {
                $error = 'Nama dan email wajib diisi.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Format email tidak valid.';
            } elseif (!in_array($role, ['admin', 'dosen', 'mahasiswa'])) {
                $error = 'Role tidak valid.';
            } elseif ($this->model->emailSudahAda($email, $id)) {
                $error = 'Email sudah digunakan.';
            } else {
                $this->model->edit([
                    'id'       => $id,
                    'nama'     => $nama,
                    'email'    => $email,
                    'password' => $pw,
                    'role'     => $role,
                    'nim_nip'  => $nimNip,
                ]);
                header('Location: index.php?page=akun&sukses=simpan');
                exit;
            }

            $akun = [
                'id'      => $id,
                'nama'    => $nama,
                'email'   => $email,
                'role'    => $role,
                'nim_nip' => $nimNip,
            ];
        }

        require 'app/views/admin/akun/form.php';
    }

    public function resetPassword()
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->model->resetPassword($id);
        }
        header('Location: index.php?page=akun&sukses=reset');
        exit;
    }

    public function hapus()
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->model->hapus($id);
        }
        header('Location: index.php?page=akun&sukses=hapus');
        exit;
    }
}
