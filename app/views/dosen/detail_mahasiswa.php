<?php
require_once __DIR__ . '/../layouts/DosenLayout/header.php';
require_once __DIR__ . '/../layouts/DosenLayout/sidebar.php';
require_once __DIR__ . '/../layouts/DosenLayout/navbar.php';

$summary = $detail['summary'] ?? null;
$jawaban = $detail['jawaban'] ?? [];
?>

<section class="page-content">
    <div class="breadcrumb">
        <a href="index.php?page=dosen-mahasiswa">Monitoring Mahasiswa</a>
        <span>&rsaquo;</span>
        <span>Detail Jawaban</span>
    </div>

    <?php if (!$summary): ?>
        <div class="card">
            <p class="text-muted">Detail jawaban belum tersedia.</p>
            <a href="index.php?page=dosen-mahasiswa" class="btn btn-secondary" style="margin-top:1rem;">Kembali</a>
        </div>
    <?php else: ?>
        <div class="stats-grid">
            <div class="stat-card blue">
                <div class="stat-label">Mahasiswa</div>
                <div class="stat-value" style="font-size:1.2rem;"><?= htmlspecialchars($summary['nama_mahasiswa']) ?></div>
                <div class="stat-desc"><?= htmlspecialchars($summary['kelas']) ?> / <?= htmlspecialchars($summary['tahun_ajaran'] ?? '-') ?></div>
            </div>
            <div class="stat-card green">
                <div class="stat-label">Nilai</div>
                <div class="stat-value"><?= htmlspecialchars($summary['nilai'] ?? 0) ?></div>
                <div class="stat-desc"><?= htmlspecialchars($summary['judul_kuis']) ?></div>
            </div>
            <div class="stat-card orange">
                <div class="stat-label">Mata Kuliah</div>
                <div class="stat-value" style="font-size:1.2rem;"><?= htmlspecialchars($summary['kode_matkul']) ?></div>
                <div class="stat-desc"><?= htmlspecialchars($summary['nama_matkul']) ?> / <?= (int) $summary['sks'] ?> SKS</div>
            </div>
            <div class="stat-card purple">
                <div class="stat-label">Progress</div>
                <div class="stat-value"><?= (int) ($summary['progress'] ?? 0) ?>%</div>
                <div class="stat-desc"><?= htmlspecialchars(ucfirst($summary['status'] ?? '-')) ?></div>
            </div>
        </div>

        <div class="card">
            <div class="section-header">
                <div>
                    <h2>Detail Benar / Salah</h2>
                    <p class="text-muted">Cek jawaban mahasiswa per soal beserta kunci jawaban.</p>
                </div>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pertanyaan</th>
                        <th>Jawaban Mahasiswa</th>
                        <th>Kunci</th>
                        <th>Hasil</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jawaban as $i => $row): ?>
                        <?php
                            $jawabanMahasiswa = strtoupper($row['jawaban_mahasiswa'] ?? '');
                            $kunci = strtoupper($row['jawaban_benar'] ?? '');
                            $benar = $jawabanMahasiswa !== '' && $jawabanMahasiswa === $kunci;
                        ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td>
                                <?= htmlspecialchars($row['pertanyaan']) ?>
                                <div class="text-muted">A. <?= htmlspecialchars($row['opsi_a'] ?? '-') ?></div>
                                <div class="text-muted">B. <?= htmlspecialchars($row['opsi_b'] ?? '-') ?></div>
                                <?php if (!empty($row['opsi_c'])): ?><div class="text-muted">C. <?= htmlspecialchars($row['opsi_c']) ?></div><?php endif; ?>
                                <?php if (!empty($row['opsi_d'])): ?><div class="text-muted">D. <?= htmlspecialchars($row['opsi_d']) ?></div><?php endif; ?>
                            </td>
                            <td><span class="badge badge-<?= $benar ? 'green' : 'red' ?>"><?= htmlspecialchars($jawabanMahasiswa ?: '-') ?></span></td>
                            <td><span class="badge badge-blue"><?= htmlspecialchars($kunci ?: '-') ?></span></td>
                            <td><span class="badge badge-<?= $benar ? 'green' : 'red' ?>"><?= $benar ? 'Benar' : 'Salah' ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/../layouts/DosenLayout/footer.php'; ?>
