<?php
require_once __DIR__ . '/../layouts/DosenLayout/header.php';
require_once __DIR__ . '/../layouts/DosenLayout/sidebar.php';
require_once __DIR__ . '/../layouts/DosenLayout/navbar.php';

?>

<section class="page-content">
    <div class="section-header">
        <div>
            <h2>Laporan Nilai</h2>
            <p class="text-muted">Nilai mahasiswa berdasarkan mata kuliah, kelas, tahun ajaran, dan relasi dosen.</p>
        </div>
    </div>

    <form method="GET" action="index.php" class="card" style="margin-bottom:1rem;">
        <input type="hidden" name="page" value="dosen-hasil" />
        <div class="filter-row">
            <select name="relasi" class="form-control" style="max-width:420px;">
                <option value="">Semua Mata Kuliah & Kelas</option>
                <?php foreach ($matkulDiampu as $mk): ?>
                    <?php $value = $mk['matkul_id'] . '|' . $mk['kelas_id']; ?>
                    <option value="<?= htmlspecialchars($value) ?>" <?= (string) $filterMatkul === (string) $mk['matkul_id'] && (string) $filterKelas === (string) $mk['kelas_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($mk['kode_matkul'] . ' - ' . $mk['nama_matkul'] . ' / ' . $mk['kelas'] . ' / ' . ($mk['tahun_ajaran'] ?? '-')) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn-primary">Terapkan</button>
            <a href="index.php?page=dosen-hasil" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <div class="stats-grid">
        <div class="stat-card green">
            <div class="stat-label">Nilai Tertinggi</div>
            <div class="stat-value"><?= $stats['tertinggi'] ?></div>
        </div>

        <div class="stat-card orange">
            <div class="stat-label">Nilai Terendah</div>
            <div class="stat-value"><?= $stats['terendah'] ?></div>
        </div>

        <div class="stat-card blue">
            <div class="stat-label">Rata-rata Nilai</div>
            <div class="stat-value"><?= $stats['rata_rata'] ?></div>
        </div>

        <div class="stat-card purple">
            <div class="stat-label">Kelulusan</div>
            <div class="stat-value"><?= $stats['kelulusan'] ?>%</div>
        </div>
    </div>

    <div class="card">
        <div class="section-header">
            <div>
                <h2>Nilai Per Mata Kuliah</h2>
                <p class="text-muted">Data yang tampil mengikuti mata kuliah dan kelas yang diampu dosen.</p>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Mahasiswa</th>
                    <th>Mata Kuliah</th>
                    <th>Kelas</th>
                    <th>Tahun Ajaran</th>
                    <th>Kuis</th>
                    <th>SKS</th>
                    <th>Nilai</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($nilai)): ?>
                    <?php foreach ($nilai as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nama_mahasiswa']) ?></td>
                        <td>
                            <strong><?= htmlspecialchars($row['kode_matkul']) ?></strong>
                            <div class="text-muted"><?= htmlspecialchars($row['nama_matkul']) ?></div>
                        </td>
                        <td><?= htmlspecialchars($row['kelas']) ?></td>
                        <td><?= htmlspecialchars($row['tahun_ajaran'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($row['judul_kuis']) ?></td>
                        <td><span class="badge badge-blue"><?= (int) $row['sks'] ?> SKS</span></td>
                        <td><span class="badge badge-<?= $row['nilai'] >= 70 ? 'green' : 'red' ?>"><?= htmlspecialchars($row['nilai']) ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="empty-row">Belum ada nilai untuk filter ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/DosenLayout/footer.php'; ?>
