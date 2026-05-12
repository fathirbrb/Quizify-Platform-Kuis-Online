<?php


$isEdit  = isset($akun) && $akun;
$action  = $isEdit ? 'edit&id=' . $akun['id'] : 'tambah';

require 'app/views/layouts/header.php';
require 'app/views/layouts/sidebar.php';
?>

    <main class="main">
        <?php require 'app/views/layouts/topbar.php'; ?>

        <section class="page-content">

            <div class="breadcrumb">
                <a href="index.php?page=akun">Kelola Akun</a>
                <span>&rsaquo;</span>
                <span><?= $isEdit ? 'Edit Akun' : 'Tambah Akun' ?></span>
            </div>

            <div class="form-card">
                <h2><?= $isEdit ? 'Edit Akun' : 'Tambah Akun Baru' ?></h2>
                <p class="text-muted" style="margin-bottom: 1.25rem;">
                    <?= $isEdit ? 'Ubah data akun pengguna.' : 'Isi data berikut untuk membuat akun baru.' ?>
                </p>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <?php if ($sukses): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($sukses) ?></div>
                <?php endif; ?>

                <form method="POST" action="index.php?page=akun&action=<?= $action ?>">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="nama">Nama Lengkap <span class="req">*</span></label>
                            <input
                                type="text"
                                id="nama"
                                name="nama"
                                class="form-control"
                                placeholder="Masukkan nama lengkap"
                                value="<?= htmlspecialchars($akun['nama'] ?? '') ?>"
                                required
                            />
                        </div>

                        <div class="form-group">
                            <label for="email">Email <span class="req">*</span></label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control"
                                placeholder="contoh@email.com"
                                value="<?= htmlspecialchars($akun['email'] ?? '') ?>"
                                required
                            />
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="nim_nip">NIM / NIP</label>
                            <input
                                type="text"
                                id="nim_nip"
                                name="nim_nip"
                                class="form-control"
                                placeholder="Nomor Induk Mahasiswa/Pegawai"
                                value="<?= htmlspecialchars($akun['nim_nip'] ?? '') ?>"
                            />
                        </div>

                        <div class="form-group">
                            <label for="role">Role <span class="req">*</span></label>
                            <select id="role" name="role" class="form-control" required>
                                <option value="mahasiswa" <?= ($akun['role'] ?? 'mahasiswa') === 'mahasiswa' ? 'selected' : '' ?>>Mahasiswa</option>
                                <option value="dosen"     <?= ($akun['role'] ?? '')           === 'dosen'     ? 'selected' : '' ?>>Dosen</option>
                                <option value="admin"     <?= ($akun['role'] ?? '')           === 'admin'     ? 'selected' : '' ?>>Admin</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">
                            Password <?= $isEdit ? '<span class="text-muted">(kosongkan jika tidak ingin ubah)</span>' : '<span class="req">*</span>' ?>
                        </label>
                        <div class="input-password">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                placeholder="<?= $isEdit ? 'Biarkan kosong jika tidak diubah' : 'Minimal 6 karakter' ?>"
                                <?= $isEdit ? '' : 'required' ?>
                            />
                            <button type="button" class="btn-toggle-pass" onclick="togglePassword()">
                                &#128065;
                            </button>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="index.php?page=akun" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Akun' ?>
                        </button>
                    </div>

                </form>
            </div>

        </section>

        <?php require 'app/views/layouts/footer.php'; ?>
