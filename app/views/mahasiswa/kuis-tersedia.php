<section class="page-content">

    <form method="GET" action="index.php" class="card" style="margin-bottom:1rem;">
        <input type="hidden" name="page" value="kuis-tersedia">
        <div class="filter-row">
            <select name="relasi" class="form-control" style="max-width:420px;">
                <option value="">Semua Mata Kuliah & Kelas</option>
                <?php foreach (($filterOptions['relasi'] ?? []) as $value => $label): ?>
                    <?php [$matkulId, $kelasId] = explode('|', $value, 2); ?>
                    <option value="<?= htmlspecialchars($value) ?>" <?= (string) ($filterMatkul ?? '') === (string) $matkulId && (string) ($filterKelas ?? '') === (string) $kelasId ? 'selected' : '' ?>>
                        <?= htmlspecialchars($label) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="status" class="form-control" style="max-width:210px;">
                <option value="">Semua Status</option>
                <option value="dibuka" <?= ($filterStatus ?? '') === 'dibuka' ? 'selected' : '' ?>>Dibuka</option>
                <option value="sedang" <?= ($filterStatus ?? '') === 'sedang' ? 'selected' : '' ?>>Sedang Dikerjakan</option>
                <option value="terjadwal" <?= ($filterStatus ?? '') === 'terjadwal' ? 'selected' : '' ?>>Terjadwal</option>
                <option value="selesai" <?= ($filterStatus ?? '') === 'selesai' ? 'selected' : '' ?>>Selesai</option>
            </select>

            <button type="submit" class="btn btn-primary">Terapkan</button>
            <a href="index.php?page=kuis-tersedia" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <div class="quiz-grid" id="kuis-grid" data-feed-url="index.php?page=mahasiswa-kuis-feed&<?= htmlspecialchars(http_build_query([
        'status' => $filterStatus ?? '',
        'matkul_id' => $filterMatkul ?? '',
        'kelas_id' => $filterKelas ?? '',
    ])) ?>">

        <?php foreach($kuisList as $item): ?>

        <div class="quiz-card card">

            <div class="quiz-card-top quiz-<?= htmlspecialchars($item['warna'] ?? 'orange') ?>">

                <span class="badge badge-orange">
                    <?= htmlspecialchars($item['status_tampil'] ?? $item['status'] ?? 'Tersedia') ?>
                </span>

                <span class="quiz-count">
                    <?= $item['jumlah_soal'] ?? 20 ?> Soal
                </span>

            </div>

            <div class="quiz-card-body">

                <p class="quiz-matkul">
                    <?= htmlspecialchars(($item['kode_matkul'] ?? '') . ' - ' . ($item['nama_matkul'] ?? 'Pemrograman Web')) ?>
                </p>

                <h2>
                    <?= htmlspecialchars($item['nama_kuis'] ?? 'HTML Dasar') ?>
                </h2>

                <p class="text-muted">
                    <?= htmlspecialchars($item['nama_kelas'] ?? $item['kelas'] ?? 'SI-A') ?> / <?= htmlspecialchars($item['tahun_ajaran'] ?? '-') ?>
                </p>

                <p class="text-muted">
                    <?= (int) ($item['sks'] ?? 3) ?> SKS · Durasi <?= (int) ($item['durasi'] ?? 30) ?> menit
                </p>

                <a href="index.php?page=kerjakan&id=<?= urlencode($item['id'] ?? 1) ?>"
                   class="btn btn-primary btn-full">
                    Kerjakan
                </a>

            </div>

        </div>

        <?php endforeach; ?>

        <?php if (empty($kuisList)): ?>
            <div class="card empty-row" style="grid-column:1/-1;">Belum ada kuis yang tersedia.</div>
        <?php endif; ?>

    </div>

</section>

<script>
(() => {
    const grid = document.getElementById('kuis-grid');
    if (!grid) {
        return;
    }

    const feedUrl = grid.dataset.feedUrl;
    let lastSignature = '';

    const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    }[char]));

    const renderCard = (item) => {
        const status = item.status_tampil || item.status || 'Tersedia';
        const warna = item.warna || 'orange';
        const jumlahSoal = Number(item.jumlah_soal || 0);
        const durasi = Number(item.durasi || 30);
        const sks = Number(item.sks || 3);
        const canStart = ['dibuka', 'sedang'].includes(item.status_filter || '');
        const action = canStart
            ? `<a href="index.php?page=kerjakan&id=${encodeURIComponent(item.id || 1)}" class="btn btn-primary btn-full">Kerjakan</a>`
            : `<button type="button" class="btn btn-secondary btn-full" disabled>${escapeHtml(status)}</button>`;

        return `
            <div class="quiz-card card">
                <div class="quiz-card-top quiz-${escapeHtml(warna)}">
                    <span class="badge badge-orange">${escapeHtml(status)}</span>
                    <span class="quiz-count">${jumlahSoal} Soal</span>
                </div>
                <div class="quiz-card-body">
                    <p class="quiz-matkul">${escapeHtml((item.kode_matkul || '') + ' - ' + (item.nama_matkul || ''))}</p>
                    <h2>${escapeHtml(item.nama_kuis || 'Kuis')}</h2>
                    <p class="text-muted">${escapeHtml(item.nama_kelas || item.kelas || '-')} / ${escapeHtml(item.tahun_ajaran || '-')}</p>
                    <p class="text-muted">${sks} SKS / Durasi ${durasi} menit</p>
                    ${action}
                </div>
            </div>
        `;
    };

    const render = (items) => {
        if (!items.length) {
            grid.innerHTML = '<div class="card empty-row" style="grid-column:1/-1;">Belum ada kuis yang tersedia.</div>';
            return;
        }

        grid.innerHTML = items.map(renderCard).join('');
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
            // Biarkan data terakhir tetap tampil kalau koneksi polling gagal sesaat.
        }
    };

    lastSignature = JSON.stringify(<?= json_encode(array_values($kuisList ?? [])) ?>);
    window.setInterval(refresh, 5000);
    refresh();
})();
</script>
