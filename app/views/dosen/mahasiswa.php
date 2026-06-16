<?php
require_once __DIR__ . '/../layouts/DosenLayout/header.php';
require_once __DIR__ . '/../layouts/DosenLayout/sidebar.php';
require_once __DIR__ . '/../layouts/DosenLayout/navbar.php';

$perPage = 5;
$page = max(1, (int) ($_GET['p'] ?? 1));
$total = count($allMhs);
$totalPages = max(1, (int) ceil($total / $perPage));
$page = min($page, $totalPages);
$mhs = array_slice($allMhs, ($page - 1) * $perPage, $perPage);
$baseUrl = 'index.php?page=dosen-mahasiswa'
    . ($filterMatkul !== '' ? '&matkul_id=' . urlencode($filterMatkul) : '')
    . ($filterKelas !== '' ? '&kelas_id=' . urlencode($filterKelas) : '')
    . ($filterStatus !== '' ? '&status=' . urlencode($filterStatus) : '');
?>

<section class="page-content">
    <div class="section-header">
        <div>
            <h2>Monitoring Mahasiswa</h2>
            <p class="text-muted">Pantau progres mahasiswa berdasarkan mata kuliah, kelas, dan status pengerjaan.</p>
        </div>
    </div>

    <form method="GET" action="index.php" class="card" style="margin-bottom:1rem;">
        <input type="hidden" name="page" value="dosen-mahasiswa" />
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

            <select name="status" class="form-control" style="max-width:190px;">
                <option value="">Semua Status</option>
                <option value="belum" <?= $filterStatus === 'belum' ? 'selected' : '' ?>>Belum</option>
                <option value="sedang" <?= $filterStatus === 'sedang' ? 'selected' : '' ?>>Sedang</option>
                <option value="selesai" <?= $filterStatus === 'selesai' ? 'selected' : '' ?>>Selesai</option>
            </select>

            <button type="submit" class="btn btn-primary">Terapkan</button>
            <a href="index.php?page=dosen-mahasiswa" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Mata Kuliah</th>
                    <th>Kelas</th>
                    <th>Tahun Ajaran</th>
                    <th>Kuis</th>
                    <th>Status</th>
                    <th>Progress</th>
                    <th>Nilai</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($mhs)): ?>
                    <?php $no = ($page - 1) * $perPage + 1; foreach ($mhs as $m): ?>
                    <?php
                        $status = $m['status'] ?? 'belum';
                        $badge = $status === 'selesai' ? 'green' : ($status === 'sedang' ? 'yellow' : 'red');
                        $statusLabel = ['belum' => 'Belum', 'sedang' => 'Sedang', 'selesai' => 'Selesai'][$status] ?? ucfirst($status);
                        $kuisId = $m['kuis_id'] ?? null;
                        $mahasiswaId = $m['mahasiswa_id'] ?? null;
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($m['nama_mahasiswa']) ?></td>
                        <td>
                            <strong><?= htmlspecialchars($m['kode_matkul']) ?></strong>
                            <div class="text-muted"><?= htmlspecialchars($m['nama_matkul']) ?></div>
                        </td>
                        <td><?= htmlspecialchars($m['kelas']) ?></td>
                        <td><?= htmlspecialchars($m['tahun_ajaran'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($m['judul_kuis']) ?></td>
                        <td><span class="badge badge-<?= $badge ?>"><?= htmlspecialchars($statusLabel) ?></span></td>
                        <td><?= (int) ($m['progress'] ?? 0) ?>%</td>
                        <td><?= $m['nilai'] !== null ? htmlspecialchars($m['nilai']) : '-' ?></td>
                        <td>
                            <?php if (($m['status'] ?? '') === 'selesai' && $kuisId !== null && $mahasiswaId !== null): ?>
                                <a href="index.php?page=dosen-detail-mahasiswa&kuis_id=<?= urlencode((string) $kuisId) ?>&mahasiswa_id=<?= urlencode((string) $mahasiswaId) ?>"
                                   class="btn btn-secondary btn-sm">Detail</a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="empty-row">Belum ada data monitoring untuk filter ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:0.75rem;">
            <span class="text-muted">Menampilkan <?= count($mhs) ?> dari <?= $total ?> mahasiswa</span>
            <?php require 'app/views/layouts/pagination.php'; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/DosenLayout/footer.php'; ?>
