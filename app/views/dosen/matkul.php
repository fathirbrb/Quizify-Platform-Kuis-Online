<?php
require_once __DIR__ . '/../layouts/DosenLayout/header.php';
require_once __DIR__ . '/../layouts/DosenLayout/sidebar.php';
require_once __DIR__ . '/../layouts/DosenLayout/navbar.php';
?>

<section class="page-content">
    <div class="section-header">
        <div>
            <h2>Mata Kuliah Diampu</h2>
            <p class="text-muted">Daftar mata kuliah per kelas, tahun ajaran, dan jumlah SKS.</p>
        </div>
    </div>

    <div class="kelas-grid">
        <?php if (!empty($matkulDiampu)): ?>
            <?php $colors = ['blue', 'green', 'purple', 'orange']; ?>
            <?php foreach ($matkulDiampu as $index => $mk): ?>
                <?php $color = $colors[$index % count($colors)]; ?>
                <div class="kelas-card card">
                    <div class="kelas-card-header kelas-<?= $color ?>">
                        <div class="kelas-card-icon">
                            <svg viewBox="0 0 24 24" width="28" height="28" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                        </div>
                        <div class="kelas-card-stats">
                            <span class="kelas-badge"><?= (int) $mk['sks'] ?> SKS</span>
                        </div>
                    </div>
                    <div class="kelas-card-body">
                        <h3 class="kelas-nama"><?= htmlspecialchars($mk['nama_matkul']) ?></h3>
                        <p class="kelas-jurusan"><?= htmlspecialchars($mk['kode_matkul']) ?></p>
                        
                        <div class="kelas-meta" style="margin-bottom:0.75rem;">
                            <span class="kelas-meta-item">
                                <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                TA: <?= htmlspecialchars($mk['tahun_ajaran'] ?? '-') ?>
                            </span>
                            <span class="kelas-meta-item">
                                <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"></path></svg>
                                Kelas: <?= htmlspecialchars($mk['kelas'] ?? '-') ?>
                            </span>
                        </div>
                        
                        <div style="margin-top:auto;">
                            <a href="index.php?page=dosen-hasil&matkul_id=<?= urlencode($mk['matkul_id']) ?>&kelas_id=<?= urlencode($mk['kelas_id']) ?>"
                               class="btn btn-secondary btn-sm" style="width:100%; text-align:center;">Lihat Nilai</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="card empty-row" style="grid-column: 1 / -1;">
                Belum ada mata kuliah yang diampu.
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/DosenLayout/footer.php'; ?>
