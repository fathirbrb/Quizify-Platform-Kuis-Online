<?php

class ProfileController
{
    public function index()
    {
        $pageTitle    = 'Profile Settings';
        $pageSubtitle = 'Kelola informasi akun Anda.';
        $activePage   = 'profile';
        $error = '';
        require 'app/views/admin/profile.php';
    }

    public function simpan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama  = trim($_POST['nama']  ?? '');
            $email = trim($_POST['email'] ?? '');
            if ($nama && $email) {
                // TODO: update ke DB — UPDATE users SET nama=?, email=? WHERE id=?
                $_SESSION['nama']  = $nama;
                $_SESSION['email'] = $email;
            }
        }
        header('Location: index.php?page=profile&sukses=1');
        exit;
    }

    public function ubahPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lama      = $_POST['password_lama']       ?? '';
            $baru      = $_POST['password_baru']       ?? '';
            $konfirmasi = $_POST['password_konfirmasi'] ?? '';

            if ($baru !== $konfirmasi) {
                $pageTitle  = 'Profile Settings';
                $activePage = 'profile';
                $error = 'Password baru dan konfirmasi tidak cocok.';
                require 'app/views/admin/profile.php';
                return;
            }
            // TODO: verifikasi password lama & update di DB
        }
        header('Location: index.php?page=profile&sukses=1');
        exit;
    }
}
