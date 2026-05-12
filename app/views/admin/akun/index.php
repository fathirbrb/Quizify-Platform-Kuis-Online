<?php

require 'app/views/layouts/header.php';
require 'app/views/layouts/sidebar.php';
?>

    <main class="main">
        <?php require 'app/views/layouts/topbar.php'; ?>

        <section class="page-content">

            <?php if (isset($_GET['sukses']) && $_GET['sukses'] === 'hapus'): ?>
                <div class="alert alert-success">Akun berhasil dihapus.</div>
            <?php endif; ?>

            <div class="section-header">
                <h2>Daftar Akun Pengguna</h2>
                <a href="index.php?page=akun&action=tambah" class="btn btn-primary">
                    &#43; Tambah Akun
                </a>
            </div>

            <div class="card" style="margin-bottom: 1rem;">
                <form method="GET" action="index.php" class="filter-row">
                    <input type="hidden" name="page" value="akun" />

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Cari nama atau email..."
                        value="<?= htmlspecialchars($search) ?>"
                        style="max-width: 280px;"
                    />

                    <select name="role" class="form-control" style="max-width: 160px;">
                        <option value="">Semua Role</option>
                        <option value="admin"     <?= $filterRole === 'admin'      ? 'selected' : '' ?>>Admin</option>
                        <option value="dosen"     <?= $filterRole === 'dosen'      ? 'selected' : '' ?>>Dosen</option>
                        <option value="mahasiswa" <?= $filterRole === 'mahasiswa'  ? 'selected' : '' ?>>Mahasiswa</option>
                    </select>

                    <button type="submit" class="btn btn-secondary">Cari</button>

                    <?php if ($search || $filterRole): ?>
                        <a href="index.php?page=akun" class="btn btn-secondary">Reset</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="card">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>NIM / NIP</th>
                            <th>Role</th>
                            <th>Terdaftar</th>
                            <th>Aksi</th>
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
                                    <td class="text-muted"><?= date('d M Y', strtotime($akun['created_at'])) ?></td>
                                    <td>
                                        <a href="index.php?page=akun&action=edit&id=<?= $akun['id'] ?>"
                                           class="btn btn-secondary btn-sm">Edit</a>
                                        <a href="index.php?page=akun&action=hapus&id=<?= $akun['id'] ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return konfirmasiHapus('<?= htmlspecialchars($akun['nama']) ?>')">
                                           Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    <?= $search ? 'Tidak ada akun yang cocok dengan pencarian.' : 'Belum ada akun terdaftar.' ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <div class="table-info">
                    Menampilkan <?= count($akuns) ?> akun
                    <?= $search ? 'untuk pencarian "' . htmlspecialchars($search) . '"' : '' ?>
                </div>
            </div>

        </section>

        <?php require 'app/views/layouts/footer.php'; ?>
