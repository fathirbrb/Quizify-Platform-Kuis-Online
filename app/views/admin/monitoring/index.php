<?php

require 'app/views/layouts/header.php';
require 'app/views/layouts/sidebar.php';
?>

<main class="main">
    <?php require 'app/views/layouts/topbar.php'; ?>

    <section class="page-content">

        <div class="section-header">
            <h2>Statistik Login</h2>
        </div>
        <div class="stats-grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom: 1.5rem;">
            <?php
            $loginMap = [];
            foreach ($statsLogin as $s) {
                $loginMap[$s['role']] = $s['total'];
            }
            ?>
            <div class="stat-card blue">
                <div class="stat-label">Login Admin</div>
                <div class="stat-value"><?= $loginMap['admin'] ?? 0 ?></div>
            </div>
            <div class="stat-card green">
                <div class="stat-label">Login Dosen</div>
                <div class="stat-value"><?= $loginMap['dosen'] ?? 0 ?></div>
            </div>
            <div class="stat-card orange">
                <div class="stat-label">Login Mahasiswa</div>
                <div class="stat-value"><?= $loginMap['mahasiswa'] ?? 0 ?></div>
            </div>
        </div>

        <div class="section-header">
            <h2>Log Aktivitas Terbaru</h2>
            <span class="text-muted">50 aktivitas terakhir</span>
        </div>

        <div class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pengguna</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aktivitas</th>
                        <th>IP Address</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($logs)): ?>
                        <?php $no = 1;
                        foreach ($logs as $log): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($log['nama'] ?? 'Tidak diketahui') ?></td>
                                <td class="text-muted"><?= htmlspecialchars($log['email'] ?? '-') ?></td>
                                <td>
                                    <?php if ($log['role']): ?>
                                        <span
                                            class="badge badge-<?= $log['role'] === 'admin' ? 'blue' : ($log['role'] === 'dosen' ? 'green' : 'orange') ?>">
                                            <?= ucfirst($log['role']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($log['aksi']) ?></td>
                                <td class="text-muted"><?= htmlspecialchars($log['ip_address'] ?? '-') ?></td>
                                <td class="text-muted"><?= htmlspecialchars($log['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada aktivitas tercatat.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </section>

    <?php require 'app/views/layouts/footer.php'; ?>