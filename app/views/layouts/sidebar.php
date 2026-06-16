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

        <a href="index.php?page=dashboard" class="nav-item <?= $activePage === 'dashboard' ? 'active' : '' ?>">
            <span class="nav-icon">&#9679;</span>
            Dashboard
        </a>

        <a href="index.php?page=akun" class="nav-item <?= $activePage === 'akun' ? 'active' : '' ?>">
            <span class="nav-icon">&#9679;</span>
            Kelola Akun
        </a>

        <a href="index.php?page=matkul" class="nav-item <?= $activePage === 'matkul' ? 'active' : '' ?>">
            <span class="nav-icon">&#9679;</span>
            Mata Kuliah
        </a>

        <a href="index.php?page=kelas" class="nav-item <?= $activePage === 'kelas' ? 'active' : '' ?>">
            <span class="nav-icon">&#9679;</span>
            Kelas
        </a>

        <div class="nav-group-label" style="margin-top: 0.75rem;">Pengaturan</div>

        <a href="index.php?page=role" class="nav-item <?= $activePage === 'role' ? 'active' : '' ?>">
            <span class="nav-icon">&#9679;</span>
            Role Management
        </a>

        <a href="index.php?page=monitoring" class="nav-item <?= $activePage === 'monitoring' ? 'active' : '' ?>">
            <span class="nav-icon">&#9679;</span>
            Monitoring
        </a>

        <a href="index.php?page=admin-activity-log" class="nav-item <?= $activePage === 'activity-log' ? 'active' : '' ?>">
            <span class="nav-icon">&#9679;</span>
            Activity Log
        </a>

        <a href="index.php?page=profile" class="nav-item <?= $activePage === 'profile' ? 'active' : '' ?>">
            <span class="nav-icon">&#9679;</span>
            Profile
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="index.php?page=logout" class="nav-item nav-logout">
            <span class="nav-icon">&#8617;</span>
            Keluar
        </a>
    </div>

</aside>
