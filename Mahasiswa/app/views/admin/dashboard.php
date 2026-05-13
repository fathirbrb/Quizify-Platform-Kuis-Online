<?php

require 'app/views/layouts/header.php';
require 'app/views/layouts/sidebar.php';
?>

    <main class="main">
        <?php require 'app/views/layouts/topbar.php'; ?>

        <section class="page-content">

            <div class="greeting">
                <h1>Selamat datang, Administrator &#128075;</h1>
                <p>Berikut ringkasan data dan aktivitas sistem Quizify hari ini.</p>
            </div>

            <div class="stats-grid">

                <div class="stat-card blue">
                    <div class="stat-label">Total Dosen</div>
                    <div class="stat-value"><?= $stats['dosen'] ?></div>
                    <div class="stat-desc">Akun dosen aktif</div>
                </div>

                <div class="stat-card green">
                    <div class="stat-label">Total Mahasiswa</div>
                    <div class="stat-value"><?= $stats['mahasiswa'] ?></div>
                    <div class="stat-desc">Akun mahasiswa aktif</div>
                </div>

                <div class="stat-card orange">
                    <div class="stat-label">Total Kelas</div>
                    <div class="stat-value"><?= $stats['kelas'] ?></div>
                    <div class="stat-desc">Kelas terdaftar</div>
                </div>

                <div class="stat-card yellow">
                    <div class="stat-label">Mata Kuliah</div>
                    <div class="stat-value"><?= $stats['matkul'] ?></div>
                    <div class="stat-desc">Mata kuliah aktif</div>
                </div>

                <div class="stat-card purple">
                    <div class="stat-label">Total Kuis</div>
                    <div class="stat-value"><?= $stats['kuis'] ?></div>
                    <div class="stat-desc">Kuis tersedia</div>
                </div>

            </div>

            <div class="section-header">
                <h2>Akses Cepat</h2>
            </div>
            <div class="quick-menu">
                <a href="index.php?page=akun&action=tambah" class="quick-card">
                    <span class="quick-icon">&#43;</span>
                    <span>Tambah Akun</span>
                </a>
                <a href="index.php?page=matkul&action=tambah" class="quick-card">
                    <span class="quick-icon">&#43;</span>
                    <span>Tambah Mata Kuliah</span>
                </a>
                <a href="index.php?page=kelas&action=tambah" class="quick-card">
                    <span class="quick-icon">&#43;</span>
                    <span>Tambah Kelas</span>
                </a>
                <a href="index.php?page=monitoring" class="quick-card">
                    <span class="quick-icon">&#9881;</span>
                    <span>Lihat Log</span>
                </a>
            </div>

            <div class="section-header" style="margin-top: 1.5rem;">
                <h2>Aktivitas Terbaru</h2>
                <a href="index.php?page=monitoring" class="btn btn-secondary btn-sm">Lihat Semua</a>
            </div>

            <div class="card">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Pengguna</th>
                            <th>Role</th>
                            <th>Aktivitas</th>
                            <th>IP Address</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($aktivitas)): ?>
                            <?php foreach ($aktivitas as $log): ?>
                                <tr>
                                    <td><?= htmlspecialchars($log['nama'] ?? 'Tidak diketahui') ?></td>
                                    <td>
                                        <span class="badge badge-<?= $log['role'] === 'admin' ? 'blue' : ($log['role'] === 'dosen' ? 'green' : 'orange') ?>">
                                            <?= ucfirst(htmlspecialchars($log['role'] ?? '-')) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($log['aksi']) ?></td>
                                    <td><?= htmlspecialchars($log['ip_address'] ?? '-') ?></td>
                                    <td class="text-muted"><?= htmlspecialchars($log['created_at']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada aktivitas tercatat.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </section>

        <?php require 'app/views/layouts/footer.php'; ?>
