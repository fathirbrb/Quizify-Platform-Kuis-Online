<?php
$activePage = $_GET['page'] ?? 'dosen-dashboard';
?>

<aside class="sidebar">

    <div class="sidebar-logo" style="cursor: pointer; display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; width: 100%; transition: opacity 0.15s;" title="Klik untuk membuka/tutup sidebar">
        <div class="logo-full">
            <div class="logo-text">Quizify</div>
            <div class="logo-sub">Panel Dosen</div>
        </div>
        <div class="logo-short" style="display: none;">
            <div class="logo-text" style="font-size: 1.4rem; font-weight: 800; color: var(--primary);">Q</div>
        </div>
        <div class="sidebar-chevron" style="color: var(--gray-400); display: flex; align-items: center; justify-content: center; transition: transform 0.25s ease, color 0.15s;">
            <svg class="chevron-icon" viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-group-label">Menu Utama</div>

        <a href="index.php?page=dosen-dashboard"
           class="nav-item <?= $activePage === 'dosen-dashboard' ? 'active' : '' ?>">
            <span class="nav-icon">
                <svg class="nav-svg" viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9"></rect><rect x="14" y="3" width="7" height="5"></rect><rect x="14" y="12" width="7" height="9"></rect><rect x="3" y="16" width="7" height="5"></rect></svg>
            </span>
            <span class="nav-text">Dashboard</span>
        </a>

        <a href="index.php?page=dosen-kelas"
           class="nav-item <?= in_array($activePage, ['dosen-kelas', 'dosen-konfigurasi-kelas', 'dosen-kuis', 'dosen-tambah-kuis', 'dosen-soal', 'dosen-tambah-soal', 'dosen-konfigurasi-kuis']) ? 'active' : '' ?>">
            <span class="nav-icon">
                <svg class="nav-svg" viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"></path></svg>
            </span>
            <span class="nav-text">Kelola Kelas</span>
        </a>

        <a href="index.php?page=dosen-mahasiswa"
           class="nav-item <?= $activePage === 'dosen-mahasiswa' ? 'active' : '' ?>">
            <span class="nav-icon">
                <svg class="nav-svg" viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </span>
            <span class="nav-text">Monitoring Mahasiswa</span>
        </a>

        <a href="index.php?page=dosen-hasil"
           class="nav-item <?= $activePage === 'dosen-hasil' ? 'active' : '' ?>">
            <span class="nav-icon">
                <svg class="nav-svg" viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            </span>
            <span class="nav-text">Laporan Nilai</span>
        </a>

    </nav>

    <div class="sidebar-footer">
        <a href="#modal-logout" class="nav-item nav-logout">
            <span class="nav-icon">
                <svg class="nav-svg" viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
            </span>
            <span class="nav-text">Keluar</span>
        </a>
    </div>

</aside>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.querySelector('.sidebar-logo');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            const isCollapsed = document.documentElement.classList.toggle('sidebar-collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed ? 'true' : 'false');
        });
    }
});
</script>
