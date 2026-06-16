<?php
$activePage = $_GET['page'] ?? 'mahasiswa-dashboard';
?>

<aside class="sidebar">

    <div class="sidebar-logo" style="cursor: pointer; display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; width: 100%; transition: opacity 0.15s;" title="Klik untuk membuka/tutup sidebar">
        <div class="logo-full">
            <div class="logo-text">Quizify</div>
            <div class="logo-sub">Panel Mahasiswa</div>
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

        <a href="index.php?page=mahasiswa-dashboard"
           class="nav-item <?= $activePage === 'mahasiswa-dashboard' ? 'active' : '' ?>">
            <span class="nav-icon">
                <svg class="nav-svg" viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9"></rect><rect x="14" y="3" width="7" height="5"></rect><rect x="14" y="12" width="7" height="9"></rect><rect x="3" y="16" width="7" height="5"></rect></svg>
            </span>
            <span class="nav-text">Dashboard</span>
        </a>

        <a href="index.php?page=kuis-tersedia"
           class="nav-item <?= in_array($activePage, ['kuis-tersedia', 'detail-kelas', 'kerjakan']) ? 'active' : '' ?>">
            <span class="nav-icon">
                <svg class="nav-svg" viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            </span>
            <span class="nav-text">Kuis Tersedia</span>
        </a>

        <a href="index.php?page=kerjakan-kuis"
           class="nav-item <?= in_array($activePage, ['kerjakan-kuis']) ? 'active' : '' ?>">
            <span class="nav-icon">
                <svg class="nav-svg" viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
            </span>
            <span class="nav-text">Kerjakan Kuis</span>
        </a>

        <a href="index.php?page=nilai"
           class="nav-item <?= $activePage === 'nilai' ? 'active' : '' ?>">
            <span class="nav-icon">
                <svg class="nav-svg" viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
            </span>
            <span class="nav-text">Nilai Saya</span>
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
