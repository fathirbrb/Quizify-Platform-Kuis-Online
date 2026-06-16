<?php
require_once __DIR__ . '/../layouts/DosenLayout/header.php';
require_once __DIR__ . '/../layouts/DosenLayout/sidebar.php';
require_once __DIR__ . '/../layouts/DosenLayout/navbar.php';
?>

<section class="page-content">
    <div class="section-header">
        <div>
            <h2>Mata Kuliah Diampu</h2>
            <p class="text-muted">Daftar mata kuliah per kelas, tahun ajaran, dan jumlah SKS.</p>
        </div>
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode</th>
                    <th>Mata Kuliah</th>
                    <th>Kelas</th>
                    <th>Tahun Ajaran</th>
                    <th>SKS</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($matkulDiampu as $i => $mk): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($mk['kode_matkul']) ?></td>
                    <td><?= htmlspecialchars($mk['nama_matkul']) ?></td>
                    <td><?= htmlspecialchars($mk['kelas']) ?></td>
                    <td><?= htmlspecialchars($mk['tahun_ajaran'] ?? '-') ?></td>
                    <td><span class="badge badge-blue"><?= (int) $mk['sks'] ?> SKS</span></td>
                    <td>
                        <a href="index.php?page=dosen-hasil&matkul_id=<?= urlencode($mk['matkul_id']) ?>&kelas_id=<?= urlencode($mk['kelas_id']) ?>"
                           class="btn btn-secondary btn-sm">Lihat Nilai</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/DosenLayout/footer.php'; ?>
