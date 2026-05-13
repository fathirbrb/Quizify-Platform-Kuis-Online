<?php

require 'app/views/layouts/header.php';
require 'app/views/layouts/sidebar.php';
?>

    <main class="main">
        <?php require 'app/views/layouts/topbar.php'; ?>

        <section class="page-content">

            <?php if (isset($_GET['sukses']) && $_GET['sukses'] === 'hapus'): ?>
                <div class="alert alert-success">Kelas berhasil dihapus.</div>
            <?php endif; ?>

            <div class="section-header">
                <h2>Daftar Kelas</h2>
                <a href="index.php?page=kelas&action=tambah" class="btn btn-primary">&#43; Tambah Kelas</a>
            </div>

            <div class="card">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Kelas</th>
                            <th>Tahun Ajaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($kelas)): ?>
                            <?php $no = 1; foreach ($kelas as $kls): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($kls['nama']) ?></td>
                                    <td><?= htmlspecialchars($kls['tahun_ajaran'] ?? '-') ?></td>
                                    <td>
                                        <a href="index.php?page=kelas&action=edit&id=<?= $kls['id'] ?>" class="btn btn-secondary btn-sm">Edit</a>
                                        <a href="index.php?page=kelas&action=hapus&id=<?= $kls['id'] ?>" class="btn btn-danger btn-sm"
                                           onclick="return konfirmasiHapus('<?= htmlspecialchars($kls['nama']) ?>')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center text-muted">Belum ada kelas.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <div class="table-info">Total <?= count($kelas) ?> kelas</div>
            </div>

        </section>

        <?php require 'app/views/layouts/footer.php'; ?>
