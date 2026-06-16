<?php
require_once __DIR__ . '/../layouts/DosenLayout/header.php';
require_once __DIR__ . '/../layouts/DosenLayout/sidebar.php';
require_once __DIR__ . '/../layouts/DosenLayout/navbar.php';
?>

<section class="page-content">
    <div class="breadcrumb">
        <a href="index.php?page=dosen-soal&kuis_id=<?= urlencode($kuisId) ?>">Kelola Soal</a>
        <span>&rsaquo;</span>
        <span>Tambah Soal</span>
    </div>

    <div class="form-card">
        <h2>Tambah Soal Sekaligus</h2>
        <p class="text-muted" style="margin-bottom: 1.25rem;">
            <?= htmlspecialchars($kuis['judul'] ?? 'Kuis') ?> - pilih jumlah soal, isi semua, lalu simpan sekali.
        </p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="GET" action="index.php" style="margin-bottom:1rem;">
            <input type="hidden" name="page" value="dosen-tambah-soal">
            <input type="hidden" name="kuis_id" value="<?= htmlspecialchars($kuisId) ?>">
            <div class="form-group">
                <label>Jumlah Soal</label>
                <select name="jumlah_soal" class="form-control" onchange="this.form.submit()">
                    <?php for ($i = 1; $i <= 50; $i++): ?>
                        <option value="<?= $i ?>" <?= $jumlahSoal === $i ? 'selected' : '' ?>><?= $i ?> soal</option>
                    <?php endfor; ?>
                </select>
            </div>
        </form>

        <form method="POST" action="index.php?page=dosen-tambah-soal&kuis_id=<?= urlencode($kuisId) ?>">
            <input type="hidden" name="kuis_id" value="<?= htmlspecialchars($kuisId) ?>">
            <input type="hidden" name="jumlah_soal" value="<?= htmlspecialchars($jumlahSoal) ?>">

            <?php for ($i = 0; $i < $jumlahSoal; $i++): ?>
                <div class="card" style="margin-bottom:1rem;">
                    <h2 style="margin-bottom:1rem;">Soal <?= $i + 1 ?></h2>

                    <div class="form-group">
                        <label>Pertanyaan</label>
                        <textarea name="pertanyaan[]" class="form-control" placeholder="Masukkan Pertanyaan" required></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Pilihan A</label>
                            <input type="text" name="opsi_a[]" class="form-control" placeholder="Pilihan A" required>
                        </div>
                        <div class="form-group">
                            <label>Pilihan B</label>
                            <input type="text" name="opsi_b[]" class="form-control" placeholder="Pilihan B" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Pilihan C</label>
                            <input type="text" name="opsi_c[]" class="form-control" placeholder="Pilihan C">
                        </div>
                        <div class="form-group">
                            <label>Pilihan D</label>
                            <input type="text" name="opsi_d[]" class="form-control" placeholder="Pilihan D">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Jawaban Benar</label>
                        <select name="jawaban_benar[]" class="form-control" required>
                            <option value="">Pilih Jawaban Benar</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>
                </div>
            <?php endfor; ?>

            <div class="form-actions">
                <a href="index.php?page=dosen-soal&kuis_id=<?= urlencode($kuisId) ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Semua Soal</button>
            </div>
        </form>
    </div>
</section>

<?php
require_once __DIR__ . '/../layouts/DosenLayout/footer.php';
?>
