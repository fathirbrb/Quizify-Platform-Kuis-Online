<?php

require 'app/views/layouts/header.php';
require 'app/views/layouts/sidebar.php';
?>

<main class="main">
    <?php require 'app/views/layouts/topbar.php'; ?>

    <section class="page-content">

        <div class="section-header">
            <div>
                <h2>Role Management</h2>
                <p class="text-muted" style="margin-top:0.15rem;">Hak akses dan permission per role dalam sistem.</p>
            </div>
        </div>

        <!-- Info box -->
        <div class="info-box" style="margin-bottom:1.25rem;">
            <strong>&#9432; Catatan:</strong> Role bersifat tetap (Admin, Dosen, Mahasiswa).
            Untuk mengubah role pengguna, gunakan halaman
            <a href="index.php?page=akun" style="color:var(--primary);font-weight:600;">Kelola Akun</a>.
        </div>

        <!-- Permission matrix -->
        <div class="card">
            <h4 style="margin-bottom:1rem;">Matrix Hak Akses</h4>
            <table class="perm-table">
                <thead>
                    <tr>
                        <th style="width:40%;">Fitur / Aksi</th>
                        <th style="text-align:center;">Admin</th>
                        <th style="text-align:center;">Dosen</th>
                        <th style="text-align:center;">Mahasiswa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $matrix = [
                        // [Fitur, Admin, Dosen, Mahasiswa]
                        ['Dashboard Admin',              true,  false, false],
                        ['Kelola Akun Pengguna',         true,  false, false],
                        ['Kelola Mata Kuliah',           true,  false, false],
                        ['Kelola Kelas',                 true,  false, false],
                        ['Role Management',              true,  false, false],
                        ['Monitoring & Activity Log',    true,  false, false],
                        ['Dashboard Dosen',              false, true,  false],
                        ['Membuat & Mengedit Kuis',      false, true,  false],
                        ['Mengelola Soal Kuis',          false, true,  false],
                        ['Melihat Daftar Mahasiswa',     false, true,  false],
                        ['Melihat Hasil Kuis Mahasiswa', false, true,  false],
                        ['Dashboard Mahasiswa',          false, false, true ],
                        ['Melihat Kuis Tersedia',        false, false, true ],
                        ['Mengerjakan Kuis',             false, false, true ],
                        ['Melihat Nilai & Hasil',        false, false, true ],
                    ];
                    foreach ($matrix as $row):
                    ?>
                    <tr>
                        <td><?= $row[0] ?></td>
                        <?php for ($i = 1; $i <= 3; $i++): ?>
                        <td>
                            <div class="perm-check">
                                <?php if ($row[$i]): ?>
                                    <span class="check-yes">&#10003;</span>
                                <?php else: ?>
                                    <span class="check-no">&#8212;</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <?php endfor; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Role summary cards -->
        <div class="stats-grid" style="grid-template-columns:repeat(3,1fr); margin-top:1.25rem;">
            <div class="stat-card blue">
                <div class="stat-label">Admin</div>
                <div class="stat-value" style="font-size:1.3rem; margin-top:0.3rem;">Akses Penuh</div>
                <div class="stat-desc">Semua fitur sistem</div>
            </div>
            <div class="stat-card green">
                <div class="stat-label">Dosen</div>
                <div class="stat-value" style="font-size:1.3rem; margin-top:0.3rem;">Kelola Kuis</div>
                <div class="stat-desc">Buat soal &amp; pantau mahasiswa</div>
            </div>
            <div class="stat-card orange">
                <div class="stat-label">Mahasiswa</div>
                <div class="stat-value" style="font-size:1.3rem; margin-top:0.3rem;">Ikuti Kuis</div>
                <div class="stat-desc">Kerjakan &amp; lihat nilai</div>
            </div>
        </div>

    </section>

    <?php require 'app/views/layouts/footer.php'; ?>
</main>
