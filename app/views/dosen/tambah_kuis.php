
<?php
require_once __DIR__ . '/../layouts/DosenLayout/header.php';
require_once __DIR__ . '/../layouts/DosenLayout/sidebar.php';
require_once __DIR__ . '/../layouts/DosenLayout/navbar.php';
?>

<section class="page-content">
    <div class="breadcrumb">
        <a href="index.php?page=dosen-kuis">Kelola Kuis</a>
        <span>&rsaquo;</span>
        <span>Tambah Kuis</span>
    </div>

    <div class="form-card">
        <h2>Tambah Kuis Baru</h2>
        <p class="text-muted" style="margin-bottom: 1.25rem;">Isi detail kuis sebelum ditambahkan.</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?page=dosen-tambah-kuis">
        <div class="form-group">
            <label>Mata Kuliah & Kelas</label>
            <select name="relasi" class="form-control" required>
                <option value="">Pilih Mata Kuliah dan Kelas</option>
                <?php foreach ($matkulDiampu as $mk): ?>
                    <?php $selectedRelasi = (int) ($preselectKelas ?? 0) === (int) $mk['kelas_id'] && ((int) ($preselectMatkul ?? 0) === 0 || (int) ($preselectMatkul ?? 0) === (int) $mk['matkul_id']); ?>
                    <option value="<?= htmlspecialchars($mk['matkul_id'] . '|' . $mk['kelas_id']) ?>" <?= $selectedRelasi ? 'selected' : '' ?>>
                        <?= htmlspecialchars($mk['kode_matkul'] . ' - ' . $mk['nama_matkul'] . ' / ' . $mk['kelas'] . ' / ' . ($mk['tahun_ajaran'] ?? '-') . ' / ' . $mk['sks'] . ' SKS') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Judul Kuis</label>
            <input type="text" name="judul" class="form-control" placeholder="Judul Kuis" required>
        </div>

        <div class="form-group">
            <label>Deskripsi Kuis</label>
            <textarea name="deskripsi" class="form-control" placeholder="Deskripsi Kuis"></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Timer Kuis (Menit)</label>
                <input type="number" name="durasi" class="form-control" placeholder="Bebas isi durasi timer" min="1" value="30" required>
            </div>

            <div class="form-group">
                <label>Jumlah Soal</label>
                <select name="jumlah_soal" class="form-control" required>
                    <?php for ($i = 1; $i <= 50; $i++): ?>
                        <option value="<?= $i ?>" <?= $i === 5 ? 'selected' : '' ?>><?= $i ?> soal</option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="draft">Draft</option>
                <option value="terjadwal">Terjadwal</option>
                <option value="aktif">Aktif</option>
                <option value="selesai">Selesai</option>
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Mulai</label>
                <input type="datetime-local" name="waktu_mulai" class="form-control">
            </div>

            <div class="form-group">
                <label>Selesai</label>
                <input type="datetime-local" name="waktu_selesai" class="form-control">
            </div>
        </div>

        <div class="form-actions">
            <a href="index.php?page=dosen-kuis" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Kuis</button>
        </div>
        </form>
    </div>
</section>

<?php
require_once __DIR__ . '/../layouts/DosenLayout/footer.php';
?>
