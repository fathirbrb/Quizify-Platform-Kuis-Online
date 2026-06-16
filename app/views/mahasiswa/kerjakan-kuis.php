<section class="page-content">
    <div class="section-header">
        <div>
            <h2>Kerjakan Kuis</h2>
            <p class="text-muted">Lanjutkan kuis yang sudah dibuka dan belum dikumpulkan.</p>
        </div>
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>Kuis</th>
                    <th>Mata Kuliah</th>
                    <th>Kelas</th>
                    <th>Mulai</th>
                    <th>Progress</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="kuis-berjalan-body" data-feed-url="index.php?page=mahasiswa-kuis-berjalan-feed">
                <?php if (!empty($kuisBerjalan)): ?>
                    <?php foreach ($kuisBerjalan as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nama_kuis']) ?></td>
                            <td>
                                <strong><?= htmlspecialchars($item['kode_matkul']) ?></strong>
                                <div class="text-muted"><?= htmlspecialchars($item['nama_matkul']) ?> / <?= (int) $item['sks'] ?> SKS</div>
                            </td>
                            <td><?= htmlspecialchars($item['nama_kelas']) ?> / <?= htmlspecialchars($item['tahun_ajaran'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($item['mulai'] ?? '-') ?></td>
                            <td><?= (int) ($item['progress'] ?? 0) ?>%</td>
                            <td>
                                <a href="index.php?page=kerjakan&id=<?= urlencode($item['kuis_id']) ?>" class="btn btn-primary btn-sm">Lanjutkan</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="empty-row">Belum ada kuis yang sedang dikerjakan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<script>
(() => {
    const tbody = document.getElementById('kuis-berjalan-body');
    if (!tbody) {
        return;
    }

    const feedUrl = tbody.dataset.feedUrl;
    let lastSignature = JSON.stringify(<?= json_encode(array_values($kuisBerjalan ?? [])) ?>);

    const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    }[char]));

    const render = (items) => {
        if (!items.length) {
            tbody.innerHTML = '<tr><td colspan="6" class="empty-row">Belum ada kuis yang sedang dikerjakan.</td></tr>';
            return;
        }

        tbody.innerHTML = items.map((item) => `
            <tr>
                <td>${escapeHtml(item.nama_kuis)}</td>
                <td>
                    <strong>${escapeHtml(item.kode_matkul)}</strong>
                    <div class="text-muted">${escapeHtml(item.nama_matkul)} / ${Number(item.sks || 0)} SKS</div>
                </td>
                <td>${escapeHtml(item.nama_kelas)} / ${escapeHtml(item.tahun_ajaran || '-')}</td>
                <td>${escapeHtml(item.mulai || '-')}</td>
                <td>${Number(item.progress || 0)}%</td>
                <td>
                    <a href="index.php?page=kerjakan&id=${encodeURIComponent(item.kuis_id)}" class="btn btn-primary btn-sm">Lanjutkan</a>
                </td>
            </tr>
        `).join('');
    };

    const refresh = async () => {
        try {
            const response = await fetch(feedUrl, {
                headers: { 'Accept': 'application/json' },
                cache: 'no-store'
            });
            if (!response.ok) {
                return;
            }

            const payload = await response.json();
            const items = payload.kuis || [];
            const signature = JSON.stringify(items);

            if (signature !== lastSignature) {
                lastSignature = signature;
                render(items);
            }
        } catch (error) {
            // Data terakhir tetap dipakai kalau polling gagal sesaat.
        }
    };

    window.setInterval(refresh, 5000);
    refresh();
})();
</script>
