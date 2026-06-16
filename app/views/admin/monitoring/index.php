<?php

require 'app/views/layouts/header.php';
require 'app/views/layouts/sidebar.php';
?>

<main class="main">
    <?php require 'app/views/layouts/topbar.php'; ?>

    <section class="page-content">

        <div class="section-header">
            <div>
                <h2>Activity Log</h2>
                <p class="text-muted" style="margin-top:0.15rem;">Riwayat aktivitas utama pengguna.</p>
            </div>
        </div>

        <!-- Stats login -->
        <div class="stats-grid" style="grid-template-columns:repeat(3,1fr); margin-bottom:1.25rem;">
            <?php
            $loginMap = [];
            foreach ($statsLogin as $s) { $loginMap[$s['role']] = $s['total']; }
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

        <div class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pengguna</th>
                        <th>Role</th>
                        <th>Aktivitas</th>
                        <th>IP</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody id="monitoring-activity-body" data-feed-url="index.php?page=admin-activity-feed&limit=50">
                    <?php if (!empty($logs)): ?>
                        <?php $no = 1; foreach ($logs as $log): ?>
                            <?php
                            // Tentukan tag warna berdasarkan jenis aktivitas
                            $aksi = strtolower($log['aksi'] ?? '');
                            if (str_contains($aksi,'login'))  { $tagCls = 'log-tag-login';  }
                            elseif (str_contains($aksi,'logout')) { $tagCls = 'log-tag-logout'; }
                            elseif (str_contains($aksi,'submit') || str_contains($aksi,'kerjakan')) { $tagCls = 'log-tag-submit'; }
                            else  { $tagCls = 'log-tag-kuis'; }
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($log['nama'] ?? '-') ?></td>
                                <td>
                                    <?php if (!empty($log['role'])): ?>
                                        <span class="badge badge-<?= $log['role']==='admin'?'blue':($log['role']==='dosen'?'green':'orange') ?>">
                                            <?= ucfirst($log['role']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="log-tag <?= $tagCls ?>"><?= htmlspecialchars($log['aksi']) ?></span></td>
                                <td class="text-muted"><?= htmlspecialchars($log['ip_address'] ?? '-') ?></td>
                                <td class="text-muted"><?= htmlspecialchars($log['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted" style="padding:2rem;">Belum ada aktivitas tercatat.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <p class="text-muted" style="margin-top:0.75rem; font-size:0.75rem;">
            &#9432; Hanya mencatat: login, logout, buat kuis, edit kuis, kerjakan kuis, submit kuis.
        </p>

    </section>

    <script>
    (() => {
        const body = document.getElementById('monitoring-activity-body');
        if (!body) return;
        let signature = '';
        const esc = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[char]));
        const badge = (role) => role === 'admin' ? 'blue' : (role === 'dosen' ? 'green' : 'orange');
        const render = (logs) => {
            if (!logs.length) {
                body.innerHTML = '<tr><td colspan="6" class="text-center text-muted" style="padding:2rem;">Belum ada aktivitas tercatat.</td></tr>';
                return;
            }
            body.innerHTML = logs.map((log, index) => `
                <tr>
                    <td>${index + 1}</td>
                    <td>${esc(log.nama || '-')}</td>
                    <td><span class="badge badge-${badge(log.role)}">${esc(log.role ? log.role.charAt(0).toUpperCase() + log.role.slice(1) : '-')}</span></td>
                    <td><span class="log-tag">${esc(log.aksi)}</span></td>
                    <td class="text-muted">${esc(log.ip_address || '-')}</td>
                    <td class="text-muted">${esc(log.created_at)}</td>
                </tr>
            `).join('');
        };
        const refresh = async () => {
            try {
                const response = await fetch(body.dataset.feedUrl, { headers: { Accept: 'application/json' }, cache: 'no-store' });
                if (!response.ok) return;
                const data = await response.json();
                const logs = data.logs || [];
                const next = JSON.stringify(logs);
                if (next !== signature) {
                    signature = next;
                    render(logs);
                }
            } catch (error) {}
        };
        signature = JSON.stringify(<?= json_encode(array_values($logs ?? [])) ?>);
        setInterval(refresh, 5000);
        refresh();
    })();
    </script>

    <?php require 'app/views/layouts/footer.php'; ?>
</main>
