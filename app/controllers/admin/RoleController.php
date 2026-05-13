<?php


require_once 'app/models/AkunModel.php';

class RoleController {

    private $model;

    public function __construct() {
        $this->model = new AkunModel();
    }

    public function index() {
        $akuns        = $this->model->getAll();
        $pageTitle    = 'Role Management';
        $pageSubtitle = 'Atur hak akses pengguna sistem.';
        $activePage   = 'role';
        require 'app/views/admin/role/index.php';
    }

    
    public function ubahRole() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id        = (int)($_POST['id']        ?? 0);
            $roleBaru  = trim($_POST['role_baru']  ?? '');
            $rolesValid = ['admin', 'dosen', 'mahasiswa'];

            if ($id > 0 && in_array($roleBaru, $rolesValid)) {
                $this->model->ubahRole($id, $roleBaru);
            }
        }
        header('Location: index.php?page=role&sukses=ubah');
        exit;
    }
}
