<header class="topbar">
    <div>
        <div class="topbar-title">Dashboard Mahasiswa</div>
        <p class="text-muted">Akses kuis, kerjakan soal, dan lihat hasil nilai.</p>
    </div>
    <?php include 'app/views/layouts/profile-menu.php'; ?>
</header>

<section class="page-content">

    <div class="greeting fade-in">
        <h1>Halo, Mahasiswa </h1>
        <p>Selamat datang kembali! Berikut ringkasan aktivitas kuismu hari ini.</p>
    </div>

    <div class="stats-grid stagger">
        <div class="stat-card orange">
            <div class="stat-label">Kuis Tersedia</div>
            <div class="stat-value">
                <?= $stats['kuis_tersedia'] ?>
            </div>
        </div>
        <div class="stat-card yellow">
            <div class="stat-label">Belum Dikerjakan</div>
            <div class="stat-value">
                <?= $stats['belum_dikerjakan'] ?>
            </div>
        </div>
        <div class="stat-card green">
            <div class="stat-label">Selesai</div>
            <div class="stat-value">
                <?= $stats['selesai'] ?>
            </div>
        </div>
        <div class="stat-card blue">
            <div class="stat-label">Nilai Terakhir</div>
            <div class="stat-value">
                <?= $stats['nilai_terakhir'] ?>
            </div>
        </div>
    </div>

    <div class="section-label">Aktivitas Terbaru</div>

    <?php if (empty($aktivitas)): ?>
        <p class="text-muted">Belum ada aktivitas.</p>
    <?php else: ?>
        <div class="activity-grid stagger">
            <?php foreach ($aktivitas as $item): ?>
                <div class="activity-card">
                    <div class="activity-card-img img-<?= htmlspecialchars($item['warna'] ?? 'blue') ?>">
                        <span class="activity-badge">
                            <?= htmlspecialchars($item['status'] ?? 'Tersedia') ?>
                        </span>
                        <span class="activity-qs">
                            <?= (int) ($item['jumlah_soal'] ?? 0) ?> Soal
                        </span>
                    </div>
                    <div class="activity-card-body">
                        <p class="activity-card-title">
                            <?= htmlspecialchars($item['nama_kuis'] ?? '') ?>
                        </p>
                        <p class="activity-card-sub">
                            <?= htmlspecialchars($item['nama_matkul'] ?? '') ?> ·
                            <?= (int) ($item['durasi'] ?? 0) ?> menit
                        </p>
                        <?php if (!empty($item['nilai'])): ?>
                            <span class="activity-acc">
                                <?= (int) $item['nilai'] ?>% akurasi
                            </span>
                        <?php else: ?>
                            <span class="activity-acc acc-pending">Belum dikerjakan</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</section>