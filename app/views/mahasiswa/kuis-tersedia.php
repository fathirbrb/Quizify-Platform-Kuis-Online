<?php
$colors = ['blue', 'green', 'purple', 'orange'];
?>

<section class="page-content">

    <div class="section-header">
        <div>
            <h2>Kelas Saya</h2>
            <p class="text-muted">Pilih kelas untuk melihat kuis yang tersedia.</p>
        </div>
    </div>

    <?php if (!empty($kelasList)): ?>
        <div class="kelas-grid">
            <?php foreach ($kelasList as $index => $kelas): ?>
                <?php $color = $colors[$index % count($colors)]; ?>
                <a href="index.php?page=detail-kelas&id=<?= urlencode($kelas['id']) ?>" class="kelas-card card">
                    <div class="kelas-card-header kelas-<?= $color ?>">
                        <div class="kelas-card-icon">
                            <svg viewBox="0 0 24 24" width="28" height="28" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"></path></svg>
                        </div>
                        <div class="kelas-card-stats">
                            <span class="kelas-badge"><?= (int) ($kelas['jumlah_kuis'] ?? 0) ?> Kuis</span>
                        </div>
                    </div>
                    <div class="kelas-card-body">
                        <h3 class="kelas-nama"><?= htmlspecialchars($kelas['nama'] ?? '') ?></h3>
                        <p class="kelas-jurusan"><?= htmlspecialchars($kelas['jurusan'] ?? '-') ?></p>

                        <div class="kelas-meta">
                            <span class="kelas-meta-item">
                                <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                <?= htmlspecialchars($kelas['tahun_ajaran'] ?? '-') ?>
                            </span>
                            <span class="kelas-meta-item">
                                <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle></svg>
                                <?= htmlspecialchars($kelas['nama_dosen'] ?? 'Belum ada dosen') ?>
                            </span>
                        </div>

                        <?php if ((int) ($kelas['kuis_belum_selesai'] ?? 0) > 0): ?>
                            <span class="badge badge-orange" style="margin-top:0.5rem;">
                                <?= (int) $kelas['kuis_belum_selesai'] ?> kuis belum selesai
                            </span>
                        <?php else: ?>
                            <span class="badge badge-green" style="margin-top:0.5rem;">Semua kuis selesai</span>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="card empty-row">
            <p>Kamu belum bergabung ke kelas manapun.</p>
            <p class="text-muted" style="margin-top:0.5rem;">Cari dan gabung kelas melalui <a href="index.php?page=mahasiswa-dashboard" style="color:var(--accent);font-weight:600;">Dashboard</a>.</p>
        </div>
    <?php endif; ?>

</section>
