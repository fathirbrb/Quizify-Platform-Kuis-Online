        <header class="topbar">
            <div>
                <div class="topbar-title">Nilai Saya</div>
                <p class="text-muted">Rekap nilai dari semua kuis yang sudah dikerjakan.</p>
            </div>
            <?php include 'app/views/layouts/profile-menu.php'; ?>
        </header>

        <section class="page-content">

            <div class="stats-grid stagger">
                <div class="stat-card">
                    <div class="stat-label">Rata-rata Nilai</div>
                    <div class="stat-value"><?= number_format($stats['rata_rata'] ?? 0, 1) ?></div>
                    <div class="stat-sub">dari <?= (int)($stats['total_selesai'] ?? 0) ?> kuis</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Nilai Tertinggi</div>
                    <div class="stat-value"><?= (int)($stats['tertinggi'] ?? 0) ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Nilai Terendah</div>
                    <div class="stat-value"><?= (int)($stats['terendah'] ?? 0) ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Kuis Selesai</div>
                    <div class="stat-value"><?= (int)($stats['total_selesai'] ?? 0) ?></div>
                </div>
            </div>

            <div class="card card-lg">
                <div class="section-header">
                    <h3>Riwayat Nilai</h3>
                    
                    <form method="GET" action="index.php" style="display:flex;gap:0.5rem;align-items:center">
                        <input type="hidden" name="page" value="nilai" />
                        <select name="matkul" class="form-control" style="width:auto;font-size:0.8rem;"
                                onchange="this.form.submit()">
                            <option value="semua" <?= $filter === 'semua' ? 'selected' : '' ?>>Semua Matkul</option>
                            <?php foreach ($matkulList as $mk): ?>
                                <option value="<?= htmlspecialchars($mk) ?>"
                                    <?= $filter === $mk ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($mk) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama Kuis</th>
                                <th>Mata Kuliah</th>
                                <th>Tanggal</th>
                                <th>Durasi</th>
                                <th>Skor</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($riwayat)): ?>
                                <tr>
                                    <td colspan="6" style="text-align:center;color:var(--gray-400);padding:2rem">
                                        Belum ada data nilai.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($riwayat as $baris): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($baris['nama_kuis']) ?></td>
                                        <td><span class="text-muted"><?= htmlspecialchars($baris['nama_matkul']) ?></span></td>
                                        <td><span class="text-muted">
                                            <?= $baris['tanggal_selesai'] ? date('d M Y', strtotime($baris['tanggal_selesai'])) : '—' ?>
                                        </span></td>
                                        <td><span class="text-muted">
                                            <?= $baris['durasi_aktual'] ? $baris['durasi_aktual'] . ' menit' : '—' ?>
                                        </span></td>
                                        <td>
                                            <?php if ($baris['nilai'] !== null): ?>
                                                <?php
                                                $n = (int)$baris['nilai'];
                                                $scoreClass = $n >= 85 ? 'score-high' : ($n >= 70 ? 'score-mid' : 'score-low');
                                                ?>
                                                <span class="nilai-score <?= $scoreClass ?>"><?= $n ?></span>
                                            <?php else: ?>
                                                —
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                            $s = $baris['status'];
                                            $pillClass = 'status-waiting';
                                            if ($s === 'selesai') $pillClass = 'status-finished';
                                            if ($s === 'dibuka')  $pillClass = 'status-active';
                                            ?>
                                            <span class="status-pill <?= $pillClass ?>">
                                                <?= ucfirst(htmlspecialchars($s)) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </section>
