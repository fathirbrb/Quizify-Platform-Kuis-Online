<?php

require 'app/views/layouts/header.php';
require 'app/views/layouts/sidebar.php';
?>

<main class="main">
    <?php require 'app/views/layouts/topbar.php'; ?>

    <section class="page-content">

        <?php if (isset($_GET['sukses']) && $_GET['sukses'] === 'hapus'): ?>
            <div class="alert alert-success">Mata kuliah berhasil dihapus.</div>
        <?php endif; ?>

        <div class="section-header">
            <h2>Daftar Mata Kuliah</h2>
            <a href="index.php?page=matkul&action=tambah" class="btn btn-primary">&#43; Tambah Mata Kuliah</a>
        </div>

        <div class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Nama Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($matkuls)): ?>
                        <?php $no = 1;
                        foreach ($matkuls as $mk): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><span class="badge badge-blue"><?= htmlspecialchars($mk['kode']) ?></span></td>
                                <td><?= htmlspecialchars($mk['nama']) ?></td>
                                <td><?= $mk['sks'] ?> SKS</td>
                                <td>
                                    <a href="index.php?page=matkul&action=edit&id=<?= $mk['id'] ?>"
                                        class="btn btn-secondary btn-sm">Edit</a>
                                    <a href="index.php?page=matkul&action=hapus&id=<?= $mk['id'] ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return konfirmasiHapus('<?= htmlspecialchars($mk['nama']) ?>')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada mata kuliah.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="table-info">Total <?= count($matkuls) ?> mata kuliah</div>
        </div>

    </section>

    <?php require 'app/views/layouts/footer.php'; ?>