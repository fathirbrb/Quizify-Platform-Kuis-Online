<?php


require_once 'app/models/MatkulModel.php';

class MatkulController {

    private $model;

    public function __construct() {
        $this->model = new MatkulModel();
    }

    public function index() {
        $matkuls      = $this->model->getAll();
        $pageTitle    = 'Kelola Mata Kuliah';
        $pageSubtitle = 'Tambah, edit, dan hapus mata kuliah.';
        $activePage   = 'matkul';

        require 'app/views/admin/matkul/index.php';
    }

    public function tambah() {
        $pageTitle    = 'Tambah Mata Kuliah';
        $pageSubtitle = 'Tambah mata kuliah baru.';
        $activePage   = 'matkul';
        $error        = '';
        $sukses       = '';
        $matkul       = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'kode' => trim($_POST['kode'] ?? ''),
                'nama' => trim($_POST['nama'] ?? ''),
                'sks'  => (int)($_POST['sks'] ?? 2),
            ];

            if (empty($data['kode']) || empty($data['nama'])) {
                $error = 'Kode dan nama mata kuliah wajib diisi.';
            } elseif ($this->model->kodeSudahAda($data['kode'])) {
                $error = 'Kode mata kuliah sudah ada.';
            } else {
                $this->model->tambah($data);
                $sukses = 'Mata kuliah berhasil ditambahkan!';
            }
        }

        require 'app/views/admin/matkul/form.php';
    }

    public function edit() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id === 0) { header('Location: index.php?page=matkul'); exit; }

        $matkul       = $this->model->getById($id);
        $pageTitle    = 'Edit Mata Kuliah';
        $pageSubtitle = 'Ubah data mata kuliah.';
        $activePage   = 'matkul';
        $error        = '';
        $sukses       = '';

        if (!$matkul) { header('Location: index.php?page=matkul'); exit; }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id'   => $id,
                'kode' => trim($_POST['kode'] ?? ''),
                'nama' => trim($_POST['nama'] ?? ''),
                'sks'  => (int)($_POST['sks'] ?? 2),
            ];

            if (empty($data['kode']) || empty($data['nama'])) {
                $error = 'Kode dan nama mata kuliah wajib diisi.';
            } else {
                $this->model->edit($data);
                $sukses = 'Mata kuliah berhasil diperbarui!';
                $matkul = $this->model->getById($id);
            }
        }

        require 'app/views/admin/matkul/form.php';
    }

    public function hapus() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) { $this->model->hapus($id); }
        header('Location: index.php?page=matkul&sukses=hapus');
        exit;
    }
}
