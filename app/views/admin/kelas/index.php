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
            <div>
                <h2>Daftar Kelas</h2>
                <p class="text-muted">Buat kelas, kelola jurusan, dan assign dosen.</p>
            </div>
            <a href="index.php?page=kelas&action=tambah" class="btn btn-primary">&#43; Tambah Kelas</a>
        </div>

        <div class="card" style="margin-bottom:1rem;">
            <div class="section-header" style="margin-bottom:1rem;">
                <h2>Jurusan</h2>
            </div>
            <form method="POST" action="index.php?page=kelas&action=tambahJurusan" class="filter-row" style="margin-bottom:1rem;">
                <input type="text" name="nama_jurusan" class="form-control" placeholder="Nama jurusan baru" style="max-width:280px;" required>
                <button type="submit" class="btn btn-primary">&#43; Tambah Jurusan</button>
            </form>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:0.5rem;">
                <?php foreach ($jurusan as $j): ?>
                    <form method="POST" action="index.php?page=kelas&action=editJurusan" class="filter-row" style="gap:0.35rem;">
                        <input type="hidden" name="jurusan_id" value="<?= htmlspecialchars($j['id']) ?>">
                        <input type="text" name="nama_jurusan" class="form-control" value="<?= htmlspecialchars($j['nama']) ?>" required>
                        <button type="submit" class="btn btn-secondary btn-sm">Simpan</button>
                        <a href="index.php?page=kelas&action=hapusJurusan&id=<?= urlencode($j['id']) ?>" class="btn btn-danger btn-sm">Hapus</a>
                    </form>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Kelas</th>
                        <th>Jurusan</th>
                        <th>Kode Kelas</th>
                        <th>Kode Masuk</th>
                        <th>Tahun Ajaran</th>
                        <th>Dosen</th>
                        <th>Mahasiswa</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($kelas)): ?>
                        <?php $no = 1;
                        foreach ($kelas as $kls): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($kls['nama']) ?></td>
                                <td><?= htmlspecialchars($kls['jurusan'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($kls['kode_kelas'] ?? '-') ?></td>
                                <td><span class="badge badge-orange"><?= htmlspecialchars($kls['invite_code'] ?? '-') ?></span></td>
                                <td><?= htmlspecialchars($kls['tahun_ajaran'] ?? '-') ?></td>
                                <td><?= (int) ($kls['jumlah_dosen'] ?? 0) ?></td>
                                <td><?= (int) ($kls['jumlah_mahasiswa'] ?? 0) ?></td>
                                <td>
                                    <a href="index.php?page=kelas&action=edit&id=<?= $kls['id'] ?>"
                                        class="btn btn-secondary btn-sm">Edit</a>
                                    <a href="index.php?page=kelas&action=hapus&id=<?= $kls['id'] ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return konfirmasiHapus('<?= htmlspecialchars($kls['nama']) ?>')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted">Belum ada kelas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="table-info">Total <?= count($kelas) ?> kelas</div>
        </div>

    </section>

    <?php require 'app/views/layouts/footer.php'; ?>
