<?php


require_once 'app/models/KelasModel.php';

class KelasController
{

    private $model;

    public function __construct()
    {
        $this->model = new KelasModel();
    }

    public function index()
    {
        $kelas = $this->model->getAll();
        $pageTitle = 'Kelola Kelas';
        $pageSubtitle = 'Tambah, edit, dan hapus kelas.';
        $activePage = 'kelas';
        require 'app/views/admin/kelas/index.php';
    }

    public function tambah()
    {
        $pageTitle = 'Tambah Kelas';
        $pageSubtitle = 'Tambah kelas baru.';
        $activePage = 'kelas';
        $error = '';
        $sukses = '';
        $kls = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nama' => trim($_POST['nama'] ?? ''),
                'tahun_ajaran' => trim($_POST['tahun_ajaran'] ?? ''),
            ];

            if (empty($data['nama'])) {
                $error = 'Nama kelas wajib diisi.';
            } else {
                $this->model->tambah($data);
                $sukses = 'Kelas berhasil ditambahkan!';
            }
        }

        require 'app/views/admin/kelas/form.php';
    }

    public function edit()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($id === 0) {
            header('Location: index.php?page=kelas');
            exit;
        }

        $kls = $this->model->getById($id);
        $pageTitle = 'Edit Kelas';
        $pageSubtitle = 'Ubah data kelas.';
        $activePage = 'kelas';
        $error = '';
        $sukses = '';

        if (!$kls) {
            header('Location: index.php?page=kelas');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id' => $id,
                'nama' => trim($_POST['nama'] ?? ''),
                'tahun_ajaran' => trim($_POST['tahun_ajaran'] ?? ''),
            ];

            if (empty($data['nama'])) {
                $error = 'Nama kelas wajib diisi.';
            } else {
                $this->model->edit($data);
                $sukses = 'Kelas berhasil diperbarui!';
                $kls = $this->model->getById($id);
            }
        }

        require 'app/views/admin/kelas/form.php';
    }

    public function hapus()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($id > 0) {
            $this->model->hapus($id);
        }
        header('Location: index.php?page=kelas&sukses=hapus');
        exit;
    }
}