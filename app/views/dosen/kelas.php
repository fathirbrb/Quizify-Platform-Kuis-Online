<?php
require_once __DIR__ . '/../layouts/DosenLayout/header.php';
require_once __DIR__ . '/../layouts/DosenLayout/sidebar.php';
require_once __DIR__ . '/../layouts/DosenLayout/navbar.php';
?>

<section class="page-content">
    <div class="section-header">
        <div>
            <h2>Kelola Kelas</h2>
            <p class="text-muted">Konfigurasi kelas dan masuk ke isi kelas untuk mengelola kuis.</p>
        </div>
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Kelas</th>
                    <th>Jurusan</th>
                    <th>Kode Masuk</th>
                    <th>Tahun Ajaran</th>
                    <th>Matkul</th>
                    <th>Kuis</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($kelasDiampu)): ?>
                    <?php foreach ($kelasDiampu as $kelas): ?>
                        <tr>
                            <td><?= htmlspecialchars($kelas['kode_kelas'] ?: '-') ?></td>
                            <td><?= htmlspecialchars($kelas['kelas']) ?></td>
                            <td><?= htmlspecialchars($kelas['jurusan'] ?: '-') ?></td>
                            <td><span class="badge badge-orange"><?= htmlspecialchars($kelas['invite_code'] ?? '-') ?></span></td>
                            <td><?= htmlspecialchars($kelas['tahun_ajaran'] ?? '-') ?></td>
                            <td><span class="badge badge-blue"><?= (int) ($kelas['jumlah_matkul'] ?? 0) ?></span></td>
                            <td><span class="badge badge-green"><?= (int) ($kelas['jumlah_kuis'] ?? 0) ?></span></td>
                            <td>
                                <a href="index.php?page=dosen-konfigurasi-kelas&kelas_id=<?= urlencode($kelas['kelas_id']) ?>" class="btn btn-primary btn-sm">Konfigurasi</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="empty-row">Belum ada kelas yang diampu.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/DosenLayout/footer.php'; ?>
