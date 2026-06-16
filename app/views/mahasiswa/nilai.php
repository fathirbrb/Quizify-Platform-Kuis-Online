<section class="page-content">

    <div class="stats-grid">

        <div class="stat-card blue">
            <div class="stat-label">Rata-rata Nilai</div>
            <div class="stat-value">
                <?= $stats['rata_rata'] ?? 0 ?>
            </div>
            <div class="stat-desc">Dari <?= $stats['total_kuis'] ?? $stats['total_selesai'] ?? 0 ?> kuis</div>
        </div>

        <div class="stat-card green">
            <div class="stat-label">Nilai Tertinggi</div>
            <div class="stat-value">
                <?= $stats['tertinggi'] ?? 0 ?>
            </div>
        </div>

        <div class="stat-card orange">
            <div class="stat-label">Nilai Terendah</div>
            <div class="stat-value">
                <?= $stats['terendah'] ?? 0 ?>
            </div>
        </div>

        <div class="stat-card purple">
            <div class="stat-label">Kuis Selesai</div>
            <div class="stat-value">
                <?= $stats['total_kuis'] ?? $stats['total_selesai'] ?? 0 ?>
            </div>
        </div>

    </div>

    <div class="card">

        <div class="section-header">

            <div>
                <h2>Riwayat Nilai</h2>
                <p class="text-muted">Daftar kuis yang sudah kamu selesaikan.</p>
            </div>

            <form method="GET">

                <input type="hidden" name="page" value="nilai">

                <div class="filter-row">
                <select name="relasi" class="form-control" onchange="this.form.submit()" style="max-width:420px;">

                    <option value="" <?= ($filterMatkul ?? '') === '' && ($filterKelas ?? '') === '' ? 'selected' : '' ?>>Semua Mata Kuliah & Kelas</option>

                    <?php foreach(($filterOptions['relasi'] ?? []) as $value => $label): ?>

                        <?php [$matkulId, $kelasId] = explode('|', $value, 2); ?>
                        <option value="<?= htmlspecialchars($value) ?>" <?= (string) ($filterMatkul ?? '') === (string) $matkulId && (string) ($filterKelas ?? '') === (string) $kelasId ? 'selected' : '' ?>>
                            <?= htmlspecialchars($label) ?>
                        </option>

                    <?php endforeach; ?>

                </select>
                </div>

            </form>

        </div>

        <table class="table">

            <thead>

                <tr>
                    <th>Nama Kuis</th>
                    <th>Mata Kuliah</th>
                    <th>Kelas</th>
                    <th>Tahun Ajaran</th>
                    <th>Tanggal</th>
                    <th>Durasi</th>
                    <th>Skor</th>
                    <th>Status</th>
                </tr>

            </thead>

            <tbody>

            <?php if(empty($riwayat)): ?>

                <tr>
                    <td colspan="8" class="empty-row">
                        Belum ada data nilai.
                    </td>
                </tr>

            <?php else: ?>

                <?php foreach($riwayat as $item): ?>

                <tr>

                    <td><?= htmlspecialchars($item['nama_kuis']) ?></td>

                    <td>
                        <strong><?= htmlspecialchars($item['kode_matkul'] ?? '') ?></strong>
                        <div class="text-muted"><?= htmlspecialchars($item['nama_matkul']) ?> / <?= (int) ($item['sks'] ?? 0) ?> SKS</div>
                    </td>

                    <td><?= htmlspecialchars($item['nama_kelas'] ?? '-') ?></td>

                    <td><?= htmlspecialchars($item['tahun_ajaran'] ?? '-') ?></td>

                    <td><?= htmlspecialchars($item['tanggal'] ?? $item['tanggal_selesai'] ?? '-') ?></td>

                    <td><?= htmlspecialchars($item['durasi'] ?? $item['durasi_aktual'] ?? '-') ?> menit</td>

                    <td><?= htmlspecialchars($item['nilai']) ?></td>

                    <td>
                        <span class="badge badge-green">
                            <?= $item['status'] ?>
                        </span>
                    </td>

                </tr>

                <?php endforeach; ?>

            <?php endif; ?>

            </tbody>

        </table>

        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:0.75rem;">
            <span class="text-muted">Menampilkan <?= count($riwayat) ?> dari <?= $total ?> riwayat</span>
            <?php require 'app/views/layouts/pagination.php'; ?>
        </div>

    </div>

</section>
