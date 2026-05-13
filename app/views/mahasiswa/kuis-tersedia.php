<header class="topbar">
    <div>
        <div class="topbar-title">Kuis Tersedia</div>
        <p class="text-muted">Daftar kuis yang bisa kamu kerjakan sekarang.</p>
    </div>
    <?php include 'app/views/layouts/profile-menu.php'; ?>
</header>

<section class="page-content">
    <div class="kuis-filter fade-in">
        <?php
        $filters = ['semua' => 'Semua', 'dibuka' => 'Dibuka', 'terjadwal' => 'Terjadwal', 'selesai' => 'Selesai'];
        foreach ($filters as $val => $label):
            $active = ($filter === $val) ? 'active' : '';
            ?>
            <a href="index.php?page=kuis-tersedia&filter=<?= $val ?>" class="filter-btn <?= $active ?>">
                <?= $label ?>
            </a>
        <?php endforeach; ?>
    </div>

    <?php if (empty($kuisList)): ?>
        <p class="text-muted">Tidak ada kuis untuk filter ini.</p>
    <?php else: ?>
        <div class="kuis-grid stagger">
            <?php foreach ($kuisList as $kuis): ?>

                <?php
                $warna = $kuis['warna'] ?? 'orange';
                $status = $kuis['status'];

                $badgeClass = 'badge-open';
                $badgeText = 'Dibuka';
                if ($status === 'terjadwal') {
                    $badgeClass = 'badge-scheduled';
                    $badgeText = 'Terjadwal';
                }
                if ($status === 'selesai') {
                    $badgeClass = 'badge-done';
                    $badgeText = 'Selesai';
                }
                ?>

                <div class="kuis-card">
                    <div class="kuis-card-img img-<?= htmlspecialchars($warna) ?>">
                        <span class="kuis-badge <?= $badgeClass ?>">
                            <?= $badgeText ?>
                        </span>
                        <span class="kuis-qs">
                            <?= (int) $kuis['jumlah_soal'] ?> Soal
                        </span>
                    </div>
                    <div class="kuis-card-body">
                        <p class="kuis-matkul">
                            <?= htmlspecialchars($kuis['nama_matkul']) ?>
                        </p>
                        <p class="kuis-title">
                            <?= htmlspecialchars($kuis['nama_kuis']) ?>
                        </p>
                        <div class="kuis-meta-row">
                            <span>⏱
                                <?= (int) $kuis['durasi'] ?> menit
                            </span>
                            <span>
                                <?php if ($status === 'selesai'): ?>
                                    Nilai:
                                    <?= (int) $kuis['nilai'] ?>
                                <?php elseif ($status === 'terjadwal'): ?>
                                    Besok
                                <?php else: ?>
                                    Batas
                                    <?= htmlspecialchars($kuis['batas_waktu']) ?>
                                <?php endif; ?>
                            </span>
                        </div>

                        <?php if ($status === 'dibuka'): ?>
                            <a href="#modal-<?= $kuis['id'] ?>" class="btn btn-primary btn-full">Kerjakan →</a>
                        <?php elseif ($status === 'terjadwal'): ?>
                            <a href="#modal-<?= $kuis['id'] ?>" class="btn btn-secondary btn-full">Belum Dibuka</a>
                        <?php else: ?>
                            <a href="#modal-<?= $kuis['id'] ?>" class="btn btn-secondary btn-full">Lihat Nilai</a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="overlay" id="modal-<?= $kuis['id'] ?>">
                    <div class="modal">
                        <a href="#" class="modal-close">✕</a>
                        <div class="modal-header img-<?= htmlspecialchars($warna) ?>">
                            <div class="modal-header-content">
                                <span class="kuis-badge <?= $badgeClass ?>">
                                    <?= $badgeText ?>
                                </span>
                                <span class="kuis-qs" style="position:static">
                                    <?= (int) $kuis['jumlah_soal'] ?> Soal
                                </span>
                            </div>
                        </div>
                        <div class="modal-body">
                            <p class="kuis-matkul">
                                <?= htmlspecialchars($kuis['nama_matkul']) ?>
                            </p>
                            <p class="modal-title">
                                <?= htmlspecialchars($kuis['nama_kuis']) ?>
                            </p>
                            <div class="info-grid">
                                <div class="info-card"><label>Durasi</label><span>
                                        <?= (int) $kuis['durasi'] ?> menit
                                    </span></div>
                                <div class="info-card"><label>Jumlah Soal</label><span>
                                        <?= (int) $kuis['jumlah_soal'] ?> soal
                                    </span></div>
                                <?php if ($status === 'selesai'): ?>
                                    <div class="info-card"><label>Nilai Kamu</label><span style="color:#16a34a;font-size:1.2rem">
                                            <?= (int) $kuis['nilai'] ?>
                                        </span></div>
                                    <div class="info-card"><label>Status</label><span style="color:#0369a1">✓ Selesai</span></div>
                                <?php elseif ($status === 'terjadwal'): ?>
                                    <div class="info-card"><label>Dibuka</label><span>Besok</span></div>
                                    <div class="info-card"><label>Status</label><span style="color:#7c3aed">Terjadwal</span></div>
                                <?php else: ?>
                                    <div class="info-card"><label>Batas Waktu</label><span>
                                            <?= htmlspecialchars($kuis['batas_waktu']) ?> WIB
                                        </span></div>
                                    <div class="info-card"><label>Status</label><span style="color:#16a34a">✓ Dibuka</span></div>
                                <?php endif; ?>
                            </div>

                            <?php if ($status === 'dibuka'): ?>
                                <div class="rules-box">
                                    <h4>⚠ Peraturan Kuis</h4>
                                    <ul>
                                        <li>Pastikan koneksi internet kamu stabil sebelum mulai.</li>
                                        <li>Kuis tidak bisa dijeda setelah dimulai.</li>
                                        <li>Setiap soal hanya bisa dijawab satu kali.</li>
                                        <li>Hasil akan langsung muncul setelah selesai.</li>
                                    </ul>
                                </div>
                                <div class="modal-actions">
                                    <a href="#" class="btn btn-secondary">Batal</a>
                                    <a href="index.php?page=kerjakan&id=<?= $kuis['id'] ?>" class="btn btn-primary">Mulai Kuis →</a>
                                </div>
                            <?php elseif ($status === 'terjadwal'): ?>
                                <div class="rules-box" style="background:#ede9fe;border-color:#c4b5fd;">
                                    <h4 style="color:#7c3aed">Kuis Belum Dibuka</h4>
                                    <ul>
                                        <li>Kuis ini akan dibuka besok.</li>
                                        <li>Kamu akan bisa mengerjakannya setelah dibuka.</li>
                                        <li>Pantau terus halaman ini ya!</li>
                                    </ul>
                                </div>
                                <div class="modal-actions">
                                    <a href="#" class="btn btn-secondary" style="flex:1;text-align:center">Tutup</a>
                                </div>
                            <?php else: ?>
                                <div class="rules-box" style="background:#f0fdf4;border-color:#bbf7d0;">
                                    <h4 style="color:#16a34a">Kuis Sudah Selesai</h4>
                                    <ul>
                                        <li>Kamu telah menyelesaikan kuis ini.</li>
                                        <li>Nilai kamu sudah tersimpan di sistem.</li>
                                        <li>Lihat detail nilai di halaman Nilai Saya.</li>
                                    </ul>
                                </div>
                                <div class="modal-actions">
                                    <a href="#" class="btn btn-secondary">Tutup</a>
                                    <a href="index.php?page=nilai" class="btn btn-primary">Lihat Nilai Saya →</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</section>