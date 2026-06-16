<?php

class AuthController
{
    // ── Redirect per role ──────────────────────────────────────────────────────
    private $redirectMap = [
        'admin'     => 'index.php?page=dashboard',
        'dosen'     => 'index.php?page=dosen-dashboard',
        'mahasiswa' => 'index.php?page=mahasiswa-dashboard',
    ];

    public function loginPage()
    {
        if (isset($_SESSION['user_id'])) {
            $dest = $this->redirectMap[$_SESSION['role']] ?? 'index.php?page=dashboard';
            header('Location: ' . $dest);
            exit;
        }

        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']);

        require 'app/views/login.php';
    }

    public function loginProcess()
    {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['login_error'] = 'Email dan password wajib diisi.';
            header('Location: index.php?page=login');
            exit;
        }

        require_once __DIR__ . '/../../config/database.php';

        $db = getDB();
        $stmt = $db->prepare(
            'SELECT id, nama, email, password, role FROM users WHERE email = :email LIMIT 1'
        );
        $stmt->execute([
            'email' => $email,
        ]);
        $found = $stmt->fetch();

        if ($found && $this->passwordMatches($password, $found['password'])) {
            $_SESSION['user_id'] = $found['id'];
            $_SESSION['nama']    = $found['nama'];
            $_SESSION['email']   = $found['email'];
            $_SESSION['role']    = $found['role'];

            require_once __DIR__ . '/../models/ActivityModel.php';
            ActivityModel::log($found['id'], 'Login sebagai ' . $found['role']);

            header('Location: ' . $this->redirectMap[$found['role']]);
            exit;
        }

        $_SESSION['login_error'] = 'Email atau password salah.';
        header('Location: index.php?page=login');
        exit;
    }

    private function passwordMatches($inputPassword, $storedPassword)
    {
        if (password_get_info($storedPassword)['algo'] !== 0) {
            return password_verify($inputPassword, $storedPassword);
        }

        return hash_equals($storedPassword, $inputPassword);
    }

    public function logout()
    {
        require_once __DIR__ . '/../models/ActivityModel.php';
        ActivityModel::log($_SESSION['user_id'] ?? 0, 'Logout');
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }
}
