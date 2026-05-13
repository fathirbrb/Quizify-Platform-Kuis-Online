<?php

require 'app/views/layouts/header.php';
require 'app/views/layouts/sidebar.php';
?>

    <main class="main">
        <?php require 'app/views/layouts/topbar.php'; ?>

        <section class="page-content">

            <?php if (isset($_GET['sukses']) && $_GET['sukses'] === 'ubah'): ?>
                <div class="alert alert-success">Role pengguna berhasil diperbarui.</div>
            <?php endif; ?>

            <div class="section-header">
                <h2>Role Management</h2>
            </div>

            <div class="info-box">
                <strong>&#9432; Informasi Role:</strong>
                <ul style="margin-top:0.5rem; padding-left:1rem;">
                    <li><strong>Admin</strong> — Akses penuh ke semua fitur sistem.</li>
                    <li><strong>Dosen</strong> — Dapat membuat dan mengelola kuis.</li>
                    <li><strong>Mahasiswa</strong> — Dapat mengakses dan mengerjakan kuis.</li>
                </ul>
            </div>

            <div class="card">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>NIM / NIP</th>
                            <th>Role Saat Ini</th>
                            <th>Ubah Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($akuns)): ?>
                            <?php $no = 1; foreach ($akuns as $akun): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($akun['nama']) ?></td>
                                    <td><?= htmlspecialchars($akun['email']) ?></td>
                                    <td><?= htmlspecialchars($akun['nim_nip'] ?? '-') ?></td>
                                    <td>
                                        <span class="badge badge-<?= $akun['role'] === 'admin' ? 'blue' : ($akun['role'] === 'dosen' ? 'green' : 'orange') ?>">
                                            <?= ucfirst($akun['role']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" action="index.php?page=role&action=ubahRole"
                                              style="display:flex; gap:0.5rem; align-items:center;">
                                            <input type="hidden" name="id" value="<?= $akun['id'] ?>" />
                                            <select name="role_baru" class="form-control" style="padding:0.3rem 0.5rem; font-size:0.8rem;">
                                                <option value="mahasiswa" <?= $akun['role'] === 'mahasiswa' ? 'selected' : '' ?>>Mahasiswa</option>
                                                <option value="dosen"     <?= $akun['role'] === 'dosen'     ? 'selected' : '' ?>>Dosen</option>
                                                <option value="admin"     <?= $akun['role'] === 'admin'     ? 'selected' : '' ?>>Admin</option>
                                            </select>
                                            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center text-muted">Belum ada pengguna.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </section>

        <?php require 'app/views/layouts/footer.php'; ?>
