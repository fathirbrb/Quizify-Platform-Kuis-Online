<<<<<<< HEAD
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($pageTitle) ?> — Quizify</title>
    <link rel="stylesheet" href="public/css/style.css" />
    <link rel="stylesheet" href="public/css/<?= htmlspecialchars($activePage) ?>.css" />
</head>
<body>
<div class="layout">

    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-text">Quizify</div>
            <div class="logo-sub">Dashboard Mahasiswa</div>
        </div>

        <a href="index.php?page=dashboard"
           class="nav-item <?= $activePage === 'dashboard' ? 'active' : '' ?>">
            <span class="nav-icon"></span> Dashboard
        </a>
        <a href="index.php?page=kuis-tersedia"
           class="nav-item <?= $activePage === 'kuis-tersedia' ? 'active' : '' ?>">
            <span class="nav-icon"></span> Kuis Tersedia
        </a>
        <a href="index.php?page=kerjakan"
           class="nav-item <?= $activePage === 'kerjakan' ? 'active' : '' ?>">
            <span class="nav-icon"></span> Kerjakan Kuis
        </a>
        <a href="index.php?page=nilai"
           class="nav-item <?= $activePage === 'nilai' ? 'active' : '' ?>">
            <span class="nav-icon"></span> Nilai Saya
        </a>

        <div class="sidebar-footer">
            <a href="index.php?page=logout" class="nav-item">
                <span class="nav-icon"></span> Keluar
            </a>
        </div>
    </aside>

    <main class="main">
=======
<?php

$activePage = isset($activePage) ? $activePage : 'dashboard';
?>

<aside class="sidebar">

    <div class="sidebar-logo">
        <div class="logo-text">Quizify</div>
        <div class="logo-sub">Panel Admin</div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-group-label">Menu Utama</div>

        <a href="index.php?page=dashboard"
           class="nav-item <?= $activePage === 'dashboard' ? 'active' : '' ?>">
            <span class="nav-icon">&#9632;</span>
            Dashboard
        </a>

        <a href="index.php?page=akun"
           class="nav-item <?= $activePage === 'akun' ? 'active' : '' ?>">
            <span class="nav-icon">&#9632;</span>
            Kelola Akun
        </a>

        <a href="index.php?page=matkul"
           class="nav-item <?= $activePage === 'matkul' ? 'active' : '' ?>">
            <span class="nav-icon">&#9632;</span>
            Mata Kuliah
        </a>

        <a href="index.php?page=kelas"
           class="nav-item <?= $activePage === 'kelas' ? 'active' : '' ?>">
            <span class="nav-icon">&#9632;</span>
            Kelas
        </a>

        <div class="nav-group-label" style="margin-top: 0.75rem;">Pengaturan</div>

        <a href="index.php?page=role"
           class="nav-item <?= $activePage === 'role' ? 'active' : '' ?>">
            <span class="nav-icon">&#9632;</span>
            Role Management
        </a>

        <a href="index.php?page=monitoring"
           class="nav-item <?= $activePage === 'monitoring' ? 'active' : '' ?>">
            <span class="nav-icon">&#9632;</span>
            Monitoring
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="index.php?page=logout" class="nav-item nav-logout">
            <span class="nav-icon">&#8617;</span>
            Keluar
        </a>
    </div>

</aside>
>>>>>>> 35a41c870a522e72542a736bd10557eb44ccaa08
