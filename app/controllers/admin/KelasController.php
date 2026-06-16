<?php


require_once 'app/models/KelasModel.php';
require_once 'app/models/ActivityModel.php';

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
        $jurusan = $this->model->getJurusanList();
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
        $jurusan = $this->model->getJurusanList();
        $dosenList = $this->model->getDosenList();
        $matkulList = $this->model->getMatkulList();
        $assignments = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nama' => trim($_POST['nama'] ?? ''),
                'jurusan_id' => (int) ($_POST['jurusan_id'] ?? 0),
                'kode_kelas' => trim($_POST['kode_kelas'] ?? ''),
                'tahun_ajaran' => trim($_POST['tahun_ajaran'] ?? ''),
                'invite_code' => trim($_POST['invite_code'] ?? ''),
                'deskripsi' => trim($_POST['deskripsi'] ?? ''),
            ];

            if (empty($data['nama'])) {
                $error = 'Nama kelas wajib diisi.';
            } elseif (empty($data['jurusan_id'])) {
                $error = 'Jurusan wajib dipilih.';
            } else {
                $kelasId = $this->model->tambah($data);
                if ($kelasId) {
                    ActivityModel::log($_SESSION['user_id'] ?? 0, 'Admin membuat kelas: ' . $data['nama'], 'admin', $kelasId, null);
                    $sukses = 'Kelas berhasil ditambahkan!';
                } else {
                    $error = 'Kelas gagal ditambahkan.';
                }
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
        $jurusan = $this->model->getJurusanList();
        $dosenList = $this->model->getDosenList();
        $matkulList = $this->model->getMatkulList();
        $assignments = $this->model->getAssignDosen($id);

        if (!$kls) {
            header('Location: index.php?page=kelas');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id' => $id,
                'nama' => trim($_POST['nama'] ?? ''),
                'jurusan_id' => (int) ($_POST['jurusan_id'] ?? 0),
                'kode_kelas' => trim($_POST['kode_kelas'] ?? ''),
                'tahun_ajaran' => trim($_POST['tahun_ajaran'] ?? ''),
                'invite_code' => trim($_POST['invite_code'] ?? ''),
                'deskripsi' => trim($_POST['deskripsi'] ?? ''),
            ];

            if (empty($data['nama'])) {
                $error = 'Nama kelas wajib diisi.';
            } elseif (empty($data['jurusan_id'])) {
                $error = 'Jurusan wajib dipilih.';
            } else {
                $this->model->edit($data);
                ActivityModel::log($_SESSION['user_id'] ?? 0, 'Admin mengedit kelas: ' . $data['nama'], 'admin', $id, null);
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
            if ($this->model->hapus($id)) {
                ActivityModel::log($_SESSION['user_id'] ?? 0, 'Admin menghapus kelas ID: ' . $id, 'admin');
            }
        }
        header('Location: index.php?page=kelas&sukses=hapus');
        exit;
    }

    public function tambahJurusan()
    {
        $nama = trim($_POST['nama_jurusan'] ?? '');
        if ($nama !== '') {
            $this->model->tambahJurusan($nama);
            ActivityModel::log($_SESSION['user_id'] ?? 0, 'Admin menambah jurusan: ' . $nama, 'admin');
        }
        header('Location: index.php?page=kelas');
        exit;
    }

    public function hapusJurusan()
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->model->hapusJurusan($id);
            ActivityModel::log($_SESSION['user_id'] ?? 0, 'Admin menghapus jurusan ID: ' . $id, 'admin');
        }
        header('Location: index.php?page=kelas');
        exit;
    }

    public function editJurusan()
    {
        $id = (int) ($_POST['jurusan_id'] ?? 0);
        $nama = trim($_POST['nama_jurusan'] ?? '');
        if ($id > 0 && $nama !== '') {
            $this->model->editJurusan($id, $nama);
            ActivityModel::log($_SESSION['user_id'] ?? 0, 'Admin mengedit jurusan: ' . $nama, 'admin');
        }
        header('Location: index.php?page=kelas');
        exit;
    }

    public function assignDosen()
    {
        $kelasId = (int) ($_POST['kelas_id'] ?? 0);
        $dosenId = (int) ($_POST['dosen_id'] ?? 0);
        $matkulId = (int) ($_POST['matkul_id'] ?? 0);
        if ($kelasId > 0 && $dosenId > 0 && $matkulId > 0) {
            $this->model->assignDosen($kelasId, $dosenId, $matkulId);
            ActivityModel::log($_SESSION['user_id'] ?? 0, 'Admin assign dosen ke kelas ID: ' . $kelasId, 'admin', $kelasId, null);
        }
        header('Location: index.php?page=kelas&action=edit&id=' . $kelasId);
        exit;
    }

    public function hapusAssignDosen()
    {
        $id = (int) ($_GET['id'] ?? 0);
        $kelasId = (int) ($_GET['kelas_id'] ?? 0);
        if ($id > 0) {
            $this->model->hapusAssignDosen($id);
            ActivityModel::log($_SESSION['user_id'] ?? 0, 'Admin menghapus assign dosen ID: ' . $id, 'admin', $kelasId, null);
        }
        header('Location: index.php?page=kelas&action=edit&id=' . $kelasId);
        exit;
    }
}
