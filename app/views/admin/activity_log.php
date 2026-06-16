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
                <p class="text-muted">Log aktivitas pengguna, kelas, dan kuis secara real-time.</p>
            </div>
        </div>

        <div class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Aktivitas</th>
                        <th>Kelas</th>
                        <th>Kuis</th>
                        <th>IP</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody id="activity-log-body" data-feed-url="index.php?page=admin-activity-log-feed&limit=50">
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><?= htmlspecialchars($log['nama'] ?? '-') ?></td>
                            <td><span class="badge badge-<?= ($log['role'] ?? '') === 'admin' ? 'blue' : (($log['role'] ?? '') === 'dosen' ? 'green' : 'orange') ?>"><?= ucfirst(htmlspecialchars($log['role'] ?? '-')) ?></span></td>
                            <td><?= htmlspecialchars($log['aksi'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($log['nama_kelas'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($log['nama_kuis'] ?? '-') ?></td>
                            <td class="text-muted"><?= htmlspecialchars($log['ip_address'] ?? '-') ?></td>
                            <td class="text-muted"><?= htmlspecialchars($log['created_at'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <script>
    (() => {
        const body = document.getElementById('activity-log-body');
        if (!body) return;
        let signature = JSON.stringify(<?= json_encode(array_values($logs ?? [])) ?>);
        const esc = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[char]));
        const badge = (role) => role === 'admin' ? 'blue' : (role === 'dosen' ? 'green' : 'orange');
        const render = (logs) => {
            if (!logs.length) {
                body.innerHTML = '<tr><td colspan="7" class="text-center text-muted">Belum ada aktivitas.</td></tr>';
                return;
            }
            body.innerHTML = logs.map((log) => `
                <tr>
                    <td>${esc(log.nama || '-')}</td>
                    <td><span class="badge badge-${badge(log.role)}">${esc(log.role ? log.role.charAt(0).toUpperCase() + log.role.slice(1) : '-')}</span></td>
                    <td>${esc(log.aksi || '-')}</td>
                    <td>${esc(log.nama_kelas || '-')}</td>
                    <td>${esc(log.nama_kuis || '-')}</td>
                    <td class="text-muted">${esc(log.ip_address || '-')}</td>
                    <td class="text-muted">${esc(log.created_at || '-')}</td>
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
        setInterval(refresh, 5000);
        refresh();
    })();
    </script>

    <?php require 'app/views/layouts/footer.php'; ?>
</main>
