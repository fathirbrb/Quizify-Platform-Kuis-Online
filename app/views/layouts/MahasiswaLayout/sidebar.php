<?php
$activePage = $_GET['page'] ?? 'mahasiswa-dashboard';
?>

<aside class="sidebar">

    <div class="sidebar-logo">
        <div class="logo-text">Quizify</div>
        <div class="logo-sub">Panel Mahasiswa</div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-group-label">Menu Utama</div>

        <a href="index.php?page=mahasiswa-dashboard"
           class="nav-item <?= $activePage === 'mahasiswa-dashboard' ? 'active' : '' ?>">
            <span class="nav-icon">&#9679;</span>
            Dashboard
        </a>

        <a href="index.php?page=kuis-tersedia"
           class="nav-item <?= in_array($activePage, ['kuis-tersedia', 'kerjakan']) ? 'active' : '' ?>">
            <span class="nav-icon">&#9679;</span>
            Kuis Tersedia
        </a>

        <a href="index.php?page=kerjakan-kuis"
           class="nav-item <?= in_array($activePage, ['kerjakan-kuis']) ? 'active' : '' ?>">
            <span class="nav-icon">&#9679;</span>
            Kerjakan Kuis
        </a>

        <a href="index.php?page=nilai"
           class="nav-item <?= $activePage === 'nilai' ? 'active' : '' ?>">
            <span class="nav-icon">&#9679;</span>
            Nilai Saya
        </a>

    </nav>

    <div class="sidebar-footer">
        <a href="index.php?page=logout" class="nav-item nav-logout">
            <span class="nav-icon">&#8617;</span>
            Keluar
        </a>
    </div>

</aside>
