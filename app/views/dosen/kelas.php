<?php
require_once __DIR__ . '/../layouts/DosenLayout/header.php';
require_once __DIR__ . '/../layouts/DosenLayout/sidebar.php';
require_once __DIR__ . '/../layouts/DosenLayout/navbar.php';

$colors = ['blue', 'green', 'purple', 'orange'];
?>

<section class="page-content">
    <div class="section-header">
        <div>
            <h2>Kelola Kelas</h2>
            <p class="text-muted">Pilih kelas untuk mengelola kuis, mahasiswa, dan konfigurasi.</p>
        </div>
    </div>

    <?php if (!empty($kelasDiampu)): ?>
        <div class="kelas-grid">
            <?php foreach ($kelasDiampu as $index => $kelas): ?>
                <?php $color = $colors[$index % count($colors)]; ?>
                <a href="index.php?page=dosen-konfigurasi-kelas&kelas_id=<?= urlencode($kelas['kelas_id']) ?>" class="kelas-card card">
                    <div class="kelas-card-header kelas-<?= $color ?>">
                        <div class="kelas-card-icon">
                            <svg viewBox="0 0 24 24" width="28" height="28" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"></path></svg>
                        </div>
                        <div class="kelas-card-stats">
                            <span class="kelas-badge"><?= (int) ($kelas['jumlah_kuis'] ?? 0) ?> Kuis</span>
                        </div>
                    </div>
                    <div class="kelas-card-body">
                        <h3 class="kelas-nama"><?= htmlspecialchars($kelas['kelas'] ?? '') ?></h3>
                        <p class="kelas-jurusan"><?= htmlspecialchars($kelas['jurusan'] ?: '-') ?></p>

                        <div class="kelas-meta">
                            <span class="kelas-meta-item">
                                <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                <?= htmlspecialchars($kelas['tahun_ajaran'] ?? '-') ?>
                            </span>
                            <span class="kelas-meta-item">
                                <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                                <?= (int) ($kelas['jumlah_matkul'] ?? 0) ?> Mata Kuliah
                            </span>
                            <span class="kelas-meta-item">
                                <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                                Kode: <?= htmlspecialchars($kelas['invite_code'] ?? '-') ?>
                            </span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="card empty-row">
            <p>Belum ada kelas yang diampu.</p>
            <p class="text-muted" style="margin-top:0.5rem;">Hubungi admin untuk menambahkan relasi dosen-kelas.</p>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/../layouts/DosenLayout/footer.php'; ?>
