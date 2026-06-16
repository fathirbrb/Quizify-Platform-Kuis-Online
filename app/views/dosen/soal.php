<?php
require_once __DIR__ . '/../layouts/DosenLayout/header.php';
require_once __DIR__ . '/../layouts/DosenLayout/sidebar.php';
require_once __DIR__ . '/../layouts/DosenLayout/navbar.php';

$perPage = 5;
$page = max(1, (int) ($_GET['p'] ?? 1));
$total = count($allSoal);
$totalPages = max(1, (int) ceil($total / $perPage));
$page = min($page, $totalPages);
$soal = array_slice($allSoal, ($page - 1) * $perPage, $perPage);
$baseUrl = 'index.php?page=dosen-soal&kuis_id=' . urlencode($kuisId);
?>

<section class="page-content">
    <div class="section-header">
        <div>
            <h2>Daftar Soal</h2>
            <p class="text-muted">
                <?= $kuis ? htmlspecialchars($kuis['judul']) : 'Pilih kuis dulu dari Kelola Kuis.' ?>
            </p>
        </div>
        <?php if ($kuis): ?>
            <div style="display:flex;gap:0.5rem;flex-wrap:wrap;justify-content:flex-end;">
                <a href="index.php?page=dosen-konfigurasi-kuis&kuis_id=<?= urlencode($kuisId) ?>" class="btn btn-secondary">Konfigurasi Kuis/Soal</a>
                <a href="index.php?page=dosen-tambah-soal&kuis_id=<?= urlencode($kuisId) ?>" class="btn btn-primary">&#43; Tambah Soal</a>
            </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <?php if (!$kuis): ?>
            <p class="text-muted">Kuis tidak ditemukan atau bukan milik dosen ini.</p>
            <a href="index.php?page=dosen-kuis" class="btn btn-secondary" style="margin-top:1rem;">Kembali</a>
        <?php else: ?>
        <table class="table">
            <thead>
                <tr><th>#</th><th>Pertanyaan</th><th>Jawaban Benar</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php if (!empty($soal)): ?>
                    <?php $no = ($page - 1) * $perPage + 1; foreach ($soal as $s): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($s['pertanyaan']) ?></td>
                        <td><span class="badge badge-blue"><?= htmlspecialchars($s['jawaban_benar']) ?></span></td>
                        <td>
                            <div style="display:flex;gap:0.35rem;flex-wrap:wrap;">
                                <button class="btn btn-secondary btn-sm">Edit</button>
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="empty-row">Belum ada soal untuk kuis ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:0.75rem;">
            <span class="text-muted">Menampilkan <?= count($soal) ?> dari <?= $total ?> soal</span>
            <?php require 'app/views/layouts/pagination.php'; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/DosenLayout/footer.php'; ?>
