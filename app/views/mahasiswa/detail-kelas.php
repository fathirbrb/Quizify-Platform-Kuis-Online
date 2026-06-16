<section class="page-content">

    <div class="breadcrumb">
        <a href="index.php?page=kuis-tersedia">Kelas Saya</a>
        <span>›</span>
        <span><?= htmlspecialchars($kelas['nama'] ?? '') ?></span>
    </div>

    <div class="kelas-detail-header card">
        <div class="kelas-detail-info">
            <h1><?= htmlspecialchars($kelas['nama'] ?? '') ?></h1>
            <p class="text-muted"><?= htmlspecialchars($kelas['jurusan'] ?? '-') ?></p>
        </div>
        <div class="kelas-detail-meta">
            <span class="badge badge-blue"><?= htmlspecialchars($kelas['kode_kelas'] ?? '-') ?></span>
            <span class="badge badge-purple"><?= htmlspecialchars($kelas['tahun_ajaran'] ?? '-') ?></span>
            <span class="badge badge-green"><?= count($kuisList) ?> Kuis</span>
        </div>
    </div>

    <div class="section-header" style="margin-top:1.5rem;">
        <h2>Timeline Kuis</h2>
    </div>

    <?php if (!empty($kuisList)): ?>
        <div class="timeline">
            <?php foreach ($kuisList as $item): ?>
                <?php
                    $statusFilter = $item['status_filter'] ?? 'belum';
                    $statusTampil = $item['status_tampil'] ?? 'Belum dibuka';
                    $nilai = $item['nilai'] ?? null;
                    $canStart = in_array($statusFilter, ['dibuka', 'sedang']);
                    $isSelesai = $nilai !== null;

                    if ($isSelesai) {
                        $dotClass = 'timeline-dot-green';
                        $badgeClass = 'badge-green';
                        $statusTampil = 'Selesai — Nilai: ' . number_format((float) $nilai, 0) . '%';
                    } elseif ($statusFilter === 'sedang') {
                        $dotClass = 'timeline-dot-orange';
                        $badgeClass = 'badge-orange';
                    } elseif ($statusFilter === 'terjadwal') {
                        $dotClass = 'timeline-dot-purple';
                        $badgeClass = 'badge-purple';
                    } elseif ($statusFilter === 'dibuka') {
                        $dotClass = 'timeline-dot-blue';
                        $badgeClass = 'badge-blue';
                    } else {
                        $dotClass = 'timeline-dot-gray';
                        $badgeClass = 'badge-yellow';
                    }
                ?>
                <div class="timeline-item">
                    <div class="timeline-dot <?= $dotClass ?>"></div>
                    <div class="timeline-content card">
                        <div class="timeline-top">
                            <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($statusTampil) ?></span>
                            <span class="text-muted" style="font-size:0.75rem;">
                                <?= htmlspecialchars($item['kode_matkul'] ?? '') ?> · <?= (int) ($item['jumlah_soal'] ?? 0) ?> soal · <?= (int) ($item['durasi'] ?? 30) ?> menit
                            </span>
                        </div>

                        <h3 class="timeline-title"><?= htmlspecialchars($item['nama_kuis'] ?? '') ?></h3>
                        <p class="timeline-matkul"><?= htmlspecialchars($item['nama_matkul'] ?? '') ?></p>

                        <?php if (!empty($item['deskripsi'])): ?>
                            <p class="text-muted" style="margin-top:0.35rem;font-size:0.82rem;"><?= htmlspecialchars($item['deskripsi']) ?></p>
                        <?php endif; ?>

                        <?php if (!empty($item['waktu_mulai']) || !empty($item['waktu_selesai'])): ?>
                            <div class="timeline-schedule">
                                <?php if (!empty($item['waktu_mulai'])): ?>
                                    <span class="text-muted" style="font-size:0.78rem;">
                                        Mulai: <?= htmlspecialchars($item['waktu_mulai']) ?>
                                    </span>
                                <?php endif; ?>
                                <?php if (!empty($item['waktu_selesai'])): ?>
                                    <span class="text-muted" style="font-size:0.78rem;">
                                        Selesai: <?= htmlspecialchars($item['waktu_selesai']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($canStart && !$isSelesai): ?>
                            <a href="index.php?page=kerjakan&id=<?= urlencode($item['id'] ?? 1) ?>"
                               class="btn btn-primary" style="margin-top:0.75rem;align-self:flex-start;">
                                Kerjakan Kuis
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="card empty-row">
            Belum ada kuis di kelas ini.
        </div>
    <?php endif; ?>

    <div style="margin-top:1.5rem;">
        <a href="index.php?page=kuis-tersedia" class="btn btn-secondary">
            ← Kembali ke Kelas Saya
        </a>
    </div>

</section>
