<section class="page-content">

    <div class="greeting">
        <h1>Halo, <?= $_SESSION['nama'] ?? 'Mahasiswa' ?></h1>
        <p class="text-muted">Selamat datang kembali! Berikut ringkasan aktivitas kuismu hari ini.</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card orange">
            <div class="stat-label">Kuis Tersedia</div>
            <div class="stat-value">
                <?= $stats['kuis_tersedia'] ?>
            </div>
            <div class="stat-desc">Kuis yang bisa dikerjakan</div>
        </div>
        <div class="stat-card yellow">
            <div class="stat-label">Belum Dikerjakan</div>
            <div class="stat-value">
                <?= $stats['belum_dikerjakan'] ?>
            </div>
            <div class="stat-desc">Menunggu dikerjakan</div>
        </div>
        <div class="stat-card green">
            <div class="stat-label">Selesai</div>
            <div class="stat-value">
                <?= $stats['selesai'] ?>
            </div>
            <div class="stat-desc">Kuis sudah dikumpulkan</div>
        </div>
        <div class="stat-card blue">
            <div class="stat-label">Nilai Terakhir</div>
            <div class="stat-value">
                <?= $stats['nilai_terakhir'] ?>
            </div>
            <div class="stat-desc">Skor kuis terbaru</div>
        </div>
    </div>

    <div class="section-header">
        <div>
            <h2>Search Kelas</h2>
            <p class="text-muted">Cari kelas lalu masuk memakai kode dari dosen.</p>
        </div>
    </div>

    <?php if (!empty($joinMessage['text'])): ?>
        <div class="alert <?= !empty($joinMessage['ok']) ? 'alert-success' : 'alert-error' ?>">
            <?= htmlspecialchars($joinMessage['text']) ?>
        </div>
    <?php endif; ?>

    <div class="card" style="margin-bottom:1.25rem;">
        <form method="GET" action="index.php" class="filter-row" style="margin-bottom:1rem;">
            <input type="hidden" name="page" value="mahasiswa-dashboard">
            <input type="text" name="search_kelas" class="form-control" placeholder="Cari nama, jurusan, atau kode kelas..." value="<?= htmlspecialchars($searchKelas ?? '') ?>" style="max-width:420px;">
            <button type="submit" class="btn btn-primary">Cari Kelas</button>
            <?php if (!empty($searchKelas)): ?>
                <a href="index.php?page=mahasiswa-dashboard" class="btn btn-secondary">Reset</a>
            <?php endif; ?>
        </form>

        <?php if (!empty($searchKelas)): ?>
            <table class="table" style="margin-top:1rem;">
                <thead>
                    <tr>
                        <th>Nama Kelas</th>
                        <th>Jurusan</th>
                        <th>Kode Kelas</th>
                        <th>Tahun Ajaran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($kelasResults)): ?>
                        <?php foreach ($kelasResults as $kelas): ?>
                            <tr>
                                <td><?= htmlspecialchars($kelas['nama']) ?></td>
                                <td><?= htmlspecialchars($kelas['jurusan'] ?? '-') ?></td>
                                <td><span class="badge badge-orange"><?= htmlspecialchars($kelas['kode_kelas'] ?? '-') ?></span></td>
                                <td><?= htmlspecialchars($kelas['tahun_ajaran'] ?? '-') ?></td>
                                <td>
                                    <?php if (!empty($kelas['sudah_gabung'])): ?>
                                        <span class="badge badge-green">Sudah Gabung</span>
                                    <?php else: ?>
                                        <span class="badge badge-yellow">Butuh Kode</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (empty($kelas['sudah_gabung'])): ?>
                                        <button type="button" class="btn btn-primary btn-sm js-open-join-modal"
                                            data-kelas-id="<?= htmlspecialchars($kelas['id']) ?>"
                                            data-kelas-nama="<?= htmlspecialchars($kelas['nama']) ?>">
                                            Gabung
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">Kelas tidak ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <div id="join-class-modal" style="display:none;position:fixed;inset:0;background:rgba(17,24,39,0.42);z-index:50;align-items:center;justify-content:center;padding:1rem;">
        <div class="card" style="max-width:420px;width:100%;">
            <div class="section-header" style="margin-bottom:1rem;">
                <div>
                    <h2>Gabung Kelas</h2>
                    <p class="text-muted" id="join-class-name"></p>
                </div>
            </div>
            <form method="POST" action="index.php?page=mahasiswa-gabung-kelas">
                <input type="hidden" name="kelas_id" id="join-class-id">
                <div class="form-group">
                    <label>Kode Kelas</label>
                    <input type="text" name="kode_kelas" class="form-control" placeholder="Masukkan kode kelas atau kode masuk dari dosen" required>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" id="join-class-close">Batal</button>
                    <button type="submit" class="btn btn-primary">Gabung</button>
                </div>
            </form>
        </div>
    </div>

    <div class="section-header">
        <div>
            <h2>Aktivitas Terbaru</h2>
            <p class="text-muted">Kuis terakhir yang tersedia atau sudah dikerjakan.</p>
        </div>
    </div>

    <?php if (empty($aktivitas)): ?>
        <p class="text-muted">Belum ada aktivitas.</p>
    <?php else: ?>
        <div class="quiz-grid">
            <?php foreach ($aktivitas as $item): ?>
                <div class="quiz-card card">
                    <div class="quiz-card-top quiz-<?= htmlspecialchars($item['warna'] ?? 'blue') ?>">
                        <span class="badge badge-blue">
                            <?= htmlspecialchars($item['status'] ?? 'Tersedia') ?>
                        </span>
                        <span class="quiz-count">
                            <?= (int) ($item['jumlah_soal'] ?? 0) ?> Soal
                        </span>
                    </div>
                    <div class="quiz-card-body">
                        <h2>
                            <?= htmlspecialchars($item['nama_kuis'] ?? '') ?>
                        </h2>
                        <p class="text-muted">
                            <?= htmlspecialchars($item['nama_matkul'] ?? '') ?> ·
                            <?= (int) ($item['durasi'] ?? 0) ?> menit
                        </p>
                        <?php if (!empty($item['nilai'])): ?>
                            <span class="badge badge-green">
                                <?= (int) $item['nilai'] ?>% akurasi
                            </span>
                        <?php else: ?>
                            <span class="badge badge-yellow">Belum dikerjakan</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</section>

<script>
(() => {
    const modal = document.getElementById('join-class-modal');
    const idInput = document.getElementById('join-class-id');
    const nameEl = document.getElementById('join-class-name');
    const closeBtn = document.getElementById('join-class-close');
    if (!modal || !idInput || !nameEl || !closeBtn) return;

    document.querySelectorAll('.js-open-join-modal').forEach((button) => {
        button.addEventListener('click', () => {
            idInput.value = button.dataset.kelasId || '';
            nameEl.textContent = button.dataset.kelasNama || '';
            modal.style.display = 'flex';
        });
    });

    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });
})();
</script>
