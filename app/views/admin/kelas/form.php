<?php

$isEdit = isset($kls) && $kls;
$action = $isEdit ? 'edit&id=' . $kls['id'] : 'tambah';

require 'app/views/layouts/header.php';
require 'app/views/layouts/sidebar.php';
?>

    <main class="main">
        <?php require 'app/views/layouts/topbar.php'; ?>

        <section class="page-content">

            <div class="breadcrumb">
                <a href="index.php?page=kelas">Kelas</a>
                <span>&rsaquo;</span>
                <span><?= $isEdit ? 'Edit' : 'Tambah' ?></span>
            </div>

            <div class="form-card">
                <h2><?= $isEdit ? 'Edit Kelas' : 'Tambah Kelas Baru' ?></h2>
                <p class="text-muted" style="margin-bottom: 1.25rem;">Isi data kelas.</p>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <?php if ($sukses): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($sukses) ?></div>
                <?php endif; ?>

                <form method="POST" action="index.php?page=kelas&action=<?= $action ?>">

                    <div class="form-group">
                        <label for="nama">Nama Kelas <span class="req">*</span></label>
                        <input type="text" id="nama" name="nama" class="form-control"
                               placeholder="Contoh: SI-A 2024"
                               value="<?= htmlspecialchars($kls['nama'] ?? '') ?>" required />
                    </div>

                    <div class="form-group">
                        <label for="tahun_ajaran">Tahun Ajaran</label>
                        <input type="text" id="tahun_ajaran" name="tahun_ajaran" class="form-control"
                               placeholder="Contoh: 2024/2025"
                               value="<?= htmlspecialchars($kls['tahun_ajaran'] ?? '') ?>" />
                    </div>

                    <div class="form-actions">
                        <a href="index.php?page=kelas" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Kelas' ?>
                        </button>
                    </div>

                </form>
            </div>

        </section>

        <?php require 'app/views/layouts/footer.php'; ?>
