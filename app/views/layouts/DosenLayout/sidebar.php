<?php
$activePage = $_GET['page'] ?? 'dosen-dashboard';
?>

<aside class="sidebar">

    <div class="sidebar-logo">
        <div class="logo-text">Quizify</div>
        <div class="logo-sub">Panel Dosen</div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-group-label">Menu Utama</div>

        <a href="index.php?page=dosen-dashboard"
           class="nav-item <?= $activePage === 'dosen-dashboard' ? 'active' : '' ?>">
            <span class="nav-icon">&#9679;</span>
            Dashboard
        </a>

        <a href="index.php?page=dosen-kelas"
           class="nav-item <?= in_array($activePage, ['dosen-kelas', 'dosen-konfigurasi-kelas', 'dosen-kuis', 'dosen-tambah-kuis', 'dosen-soal', 'dosen-tambah-soal', 'dosen-konfigurasi-kuis']) ? 'active' : '' ?>">
            <span class="nav-icon">&#9679;</span>
            Kelola Kelas
        </a>

        <a href="index.php?page=dosen-mahasiswa"
           class="nav-item <?= $activePage === 'dosen-mahasiswa' ? 'active' : '' ?>">
            <span class="nav-icon">&#9679;</span>
            Monitoring Mahasiswa
        </a>

        <a href="index.php?page=dosen-matkul"
           class="nav-item <?= $activePage === 'dosen-matkul' ? 'active' : '' ?>">
            <span class="nav-icon">&#9679;</span>
            Mata Kuliah
        </a>

        <a href="index.php?page=dosen-hasil"
           class="nav-item <?= $activePage === 'dosen-hasil' ? 'active' : '' ?>">
            <span class="nav-icon">&#9679;</span>
            Laporan Nilai
        </a>

    </nav>

    <div class="sidebar-footer">
        <a href="index.php?page=logout" class="nav-item nav-logout">
            <span class="nav-icon">&#8617;</span>
            Keluar
        </a>
    </div>

</aside>
