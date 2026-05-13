<?php

$isEdit = isset($matkul) && $matkul;
$action = $isEdit ? 'edit&id=' . $matkul['id'] : 'tambah';

require 'app/views/layouts/header.php';
require 'app/views/layouts/sidebar.php';
?>

<main class="main">
    <?php require 'app/views/layouts/topbar.php'; ?>

    <section class="page-content">

        <div class="breadcrumb">
            <a href="index.php?page=matkul">Mata Kuliah</a>
            <span>&rsaquo;</span>
            <span><?= $isEdit ? 'Edit' : 'Tambah' ?></span>
        </div>

        <div class="form-card">
            <h2><?= $isEdit ? 'Edit Mata Kuliah' : 'Tambah Mata Kuliah Baru' ?></h2>
            <p class="text-muted" style="margin-bottom: 1.25rem;">Isi data mata kuliah dengan lengkap.</p>

            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($sukses): ?>
                <div class="alert alert-success"><?= htmlspecialchars($sukses) ?></div>
            <?php endif; ?>

            <form method="POST" action="index.php?page=matkul&action=<?= $action ?>">

                <div class="form-group">
                    <label for="kode">Kode Mata Kuliah <span class="req">*</span></label>
                    <input type="text" id="kode" name="kode" class="form-control" placeholder="Contoh: MK001"
                        value="<?= htmlspecialchars($matkul['kode'] ?? '') ?>" required />
                </div>

                <div class="form-group">
                    <label for="nama">Nama Mata Kuliah <span class="req">*</span></label>
                    <input type="text" id="nama" name="nama" class="form-control" placeholder="Contoh: Pemrograman Web"
                        value="<?= htmlspecialchars($matkul['nama'] ?? '') ?>" required />
                </div>

                <div class="form-group">
                    <label for="sks">Jumlah SKS</label>
                    <select id="sks" name="sks" class="form-control">
                        <?php for ($i = 1; $i <= 6; $i++): ?>
                            <option value="<?= $i ?>" <?= ($matkul['sks'] ?? 2) == $i ? 'selected' : '' ?>>
                                <?= $i ?> SKS
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-actions">
                    <a href="index.php?page=matkul" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Mata Kuliah' ?>
                    </button>
                </div>

            </form>
        </div>

    </section>

    <?php require 'app/views/layouts/footer.php'; ?>