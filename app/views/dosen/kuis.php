<?php
require_once __DIR__ . '/../layouts/DosenLayout/header.php';
require_once __DIR__ . '/../layouts/DosenLayout/sidebar.php';
require_once __DIR__ . '/../layouts/DosenLayout/navbar.php';

$perPage = 5;
$page = max(1, (int) ($_GET['p'] ?? 1));
$total = count($allKuis);
$totalPages = max(1, (int) ceil($total / $perPage));
$page = min($page, $totalPages);
$kuis = array_slice($allKuis, ($page - 1) * $perPage, $perPage);
$baseUrl = 'index.php?page=dosen-kuis'
    . ($filterMatkul !== '' ? '&matkul_id=' . urlencode($filterMatkul) : '')
    . ($filterKelas !== '' ? '&kelas_id=' . urlencode($filterKelas) : '')
    . ($filterStatus !== '' ? '&status=' . urlencode($filterStatus) : '');
?>

<section class="page-content">
    <div class="section-header">
        <div>
            <h2>Kelola Kuis</h2>
            <p class="text-muted">Kelola kuis berdasarkan mata kuliah, kelas, dan tahun ajaran yang diampu.</p>
        </div>
        <a href="index.php?page=dosen-tambah-kuis" class="btn btn-primary">&#43; Tambah Kuis</a>
    </div>

    <form method="GET" action="index.php" class="card" style="margin-bottom:1rem;">
        <input type="hidden" name="page" value="dosen-kuis" />
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
                <option value="draft" <?= $filterStatus === 'draft' ? 'selected' : '' ?>>Draft</option>
                <option value="terjadwal" <?= $filterStatus === 'terjadwal' ? 'selected' : '' ?>>Terjadwal</option>
                <option value="aktif" <?= $filterStatus === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                <option value="selesai" <?= $filterStatus === 'selesai' ? 'selected' : '' ?>>Selesai</option>
            </select>

            <button type="submit" class="btn btn-primary">Terapkan</button>
            <a href="index.php?page=dosen-kuis" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Mata Kuliah</th>
                    <th>Kelas</th>
                    <th>Tahun Ajaran</th>
                    <th>Durasi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($kuis)): ?>
                    <?php foreach ($kuis as $k): ?>
                    <?php
                        $status = $k['status'] ?? 'draft';
                        $badge = $status === 'aktif' ? 'green' : ($status === 'terjadwal' ? 'yellow' : ($status === 'selesai' ? 'blue' : 'red'));
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($k['judul']) ?></td>
                        <td>
                            <strong><?= htmlspecialchars($k['kode_matkul']) ?></strong>
                            <div class="text-muted"><?= htmlspecialchars($k['nama_matkul']) ?> / <?= (int) $k['sks'] ?> SKS</div>
                        </td>
                        <td><?= htmlspecialchars($k['kelas']) ?></td>
                        <td><?= htmlspecialchars($k['tahun_ajaran'] ?? '-') ?></td>
                        <td><?= (int) $k['durasi'] ?> Menit</td>
                        <td><span class="badge badge-<?= $badge ?>"><?= ucfirst(htmlspecialchars($status)) ?></span></td>
                        <td>
                            <div style="display:flex;gap:0.35rem;flex-wrap:wrap;">
                                <a href="index.php?page=dosen-soal&kuis_id=<?= urlencode($k['id']) ?>" class="btn btn-secondary btn-sm">Soal</a>
                                <a href="index.php?page=dosen-konfigurasi-kuis&kuis_id=<?= urlencode($k['id']) ?>" class="btn btn-secondary btn-sm">Timer</a>
                                <a href="index.php?page=dosen-hasil&matkul_id=<?= urlencode($k['matkul_id']) ?>&kelas_id=<?= urlencode($k['kelas_id']) ?>" class="btn btn-secondary btn-sm">Nilai</a>
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="empty-row">Belum ada kuis untuk filter ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:0.75rem;">
            <span class="text-muted">Menampilkan <?= count($kuis) ?> dari <?= $total ?> kuis</span>
            <?php require 'app/views/layouts/pagination.php'; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/DosenLayout/footer.php'; ?>
