<?php
require_once __DIR__ . '/../layouts/DosenLayout/header.php';
require_once __DIR__ . '/../layouts/DosenLayout/sidebar.php';
require_once __DIR__ . '/../layouts/DosenLayout/navbar.php';

$formatDateTime = function ($value) {
    if (empty($value)) {
        return '';
    }

    $time = strtotime($value);

    return $time ? date('Y-m-d\TH:i', $time) : '';
};
?>

<section class="page-content">
    <div class="breadcrumb">
        <a href="index.php?page=dosen-kuis">Kelola Kuis</a>
        <span>&rsaquo;</span>
        <a href="index.php?page=dosen-soal&kuis_id=<?= urlencode($kuisId) ?>">Soal</a>
        <span>&rsaquo;</span>
        <span>Konfigurasi Kuis/Soal</span>
    </div>

    <div class="form-card">
        <h2>Konfigurasi Kuis/Soal</h2>
        <p class="text-muted" style="margin-bottom: 1.25rem;">
            <?= htmlspecialchars($kuis['judul']) ?> - <?= htmlspecialchars($kuis['kode_matkul'] . ' / ' . $kuis['kelas'] . ' / ' . ($kuis['tahun_ajaran'] ?? '-')) ?>
        </p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?page=dosen-konfigurasi-kuis&kuis_id=<?= urlencode($kuisId) ?>">
            <input type="hidden" name="kuis_id" value="<?= htmlspecialchars($kuisId) ?>">

            <div class="form-row">
                <div class="form-group">
                    <label>Timer Kuis (Menit)</label>
                    <input type="number" name="durasi" class="form-control" min="1" value="<?= (int) ($kuis['durasi'] ?? 30) ?>" required>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <?php foreach (['draft' => 'Draft', 'terjadwal' => 'Terjadwal', 'aktif' => 'Aktif', 'selesai' => 'Selesai'] as $value => $label): ?>
                            <option value="<?= $value ?>" <?= ($kuis['status'] ?? 'draft') === $value ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Mulai</label>
                    <input type="datetime-local" name="waktu_mulai" class="form-control" value="<?= htmlspecialchars($formatDateTime($kuis['waktu_mulai'] ?? '')) ?>">
                </div>

                <div class="form-group">
                    <label>Selesai</label>
                    <input type="datetime-local" name="waktu_selesai" class="form-control" value="<?= htmlspecialchars($formatDateTime($kuis['waktu_selesai'] ?? '')) ?>">
                </div>
            </div>

            <div class="form-actions">
                <a href="index.php?page=dosen-soal&kuis_id=<?= urlencode($kuisId) ?>" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan Konfigurasi</button>
            </div>
        </form>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/DosenLayout/footer.php'; ?>
