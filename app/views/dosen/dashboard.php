<?php
require_once __DIR__ . '/../layouts/DosenLayout/header.php';
require_once __DIR__ . '/../layouts/DosenLayout/sidebar.php';
require_once __DIR__ . '/../layouts/DosenLayout/navbar.php';
?>

<section class="page-content">
    <div class="greeting">
        <h1>Halo, <?= htmlspecialchars($_SESSION['nama'] ?? 'Dosen') ?></h1>
        <p class="text-muted">Selamat datang kembali di panel dosen Quizify.</p>
    </div>

    <div class="stats-grid">

        <div class="stat-card blue">
            <div class="stat-label">Total Kuis</div>
            <div class="stat-value"><?= $stats['kuis'] ?? 0 ?></div>
            <div class="stat-desc">Kuis aktif dan tersimpan</div>
        </div>

        <div class="stat-card green">
            <div class="stat-label">Total Soal</div>
            <div class="stat-value"><?= $stats['soal'] ?? 0 ?></div>
            <div class="stat-desc">Bank soal tersedia</div>
        </div>

        <div class="stat-card orange">
            <div class="stat-label">Mahasiswa</div>
            <div class="stat-value"><?= $stats['mahasiswa'] ?? 0 ?></div>
            <div class="stat-desc">Terdaftar di kelas</div>
        </div>

        <div class="stat-card purple">
            <div class="stat-label">Kelas</div>
            <div class="stat-value"><?= $stats['kelas'] ?? 0 ?></div>
            <div class="stat-desc">Kelas yang diampu</div>
        </div>

    </div>

    <div class="section-header">
        <div>
            <h2>Akses Cepat</h2>
            <p class="text-muted">Kelola kuis dan pantau hasil mahasiswa.</p>
        </div>
    </div>

    <div class="quick-menu">
        <a href="index.php?page=dosen-kuis" class="quick-card">
            <span class="quick-icon">&#43;</span>
            Kelola Kuis
        </a>
        <a href="index.php?page=dosen-tambah-kuis" class="quick-card">
            <span class="quick-icon">&#43;</span>
            Tambah Kuis
        </a>
        <a href="index.php?page=dosen-mahasiswa" class="quick-card">
            <span class="quick-icon">&#43;</span>
            Monitoring Mahasiswa
        </a>
        <a href="index.php?page=dosen-hasil" class="quick-card">
            <span class="quick-icon">&#43;</span>
            Laporan Nilai
        </a>
    </div>

    <div class="section-header" style="margin-top:1.5rem;">
        <div>
            <h2>Mata Kuliah Diampu</h2>
            <p class="text-muted">Ringkasan mata kuliah, kelas, tahun ajaran, dan SKS.</p>
        </div>
        <a href="index.php?page=dosen-matkul" class="btn btn-secondary btn-sm">Lihat Semua</a>
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Mata Kuliah</th>
                    <th>Kelas</th>
                    <th>Tahun Ajaran</th>
                    <th>SKS</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($matkulDiampu as $mk): ?>
                <tr>
                    <td><?= htmlspecialchars($mk['kode_matkul']) ?></td>
                    <td><?= htmlspecialchars($mk['nama_matkul']) ?></td>
                    <td><?= htmlspecialchars($mk['kelas']) ?></td>
                    <td><?= htmlspecialchars($mk['tahun_ajaran'] ?? '-') ?></td>
                    <td><span class="badge badge-blue"><?= (int) $mk['sks'] ?> SKS</span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php
require_once __DIR__ . '/../layouts/DosenLayout/footer.php';
?>
