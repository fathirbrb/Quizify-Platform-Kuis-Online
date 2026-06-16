<?php

require 'app/views/layouts/header.php';
require 'app/views/layouts/sidebar.php';
?>

<main class="main">
    <?php require 'app/views/layouts/topbar.php'; ?>

    <section class="page-content">

        <?php if (isset($_GET['sukses'])): ?>
            <?php $msg = [
                'hapus'  => 'Akun berhasil dihapus.',
                'simpan' => 'Akun berhasil disimpan.',
                'reset'  => 'Password berhasil direset ke "password123".',
                'status' => 'Status akun berhasil diubah.',
            ]; ?>
            <div class="alert alert-success"><?= $msg[$_GET['sukses']] ?? 'Berhasil.' ?></div>
        <?php endif; ?>

        <div class="section-header">
            <div>
                <h2>Kelola Akun Pengguna</h2>
                <p class="text-muted" style="margin-top:0.15rem;">Tambah, edit, nonaktifkan, atau hapus akun.</p>
            </div>
            <a href="index.php?page=akun&action=tambah" class="btn btn-primary">&#43; Tambah Akun</a>
        </div>

        <!-- Filter -->
        <div class="card" style="margin-bottom:1rem; padding:0.85rem 1.25rem;">
            <form method="GET" action="index.php" class="filter-row">
                <input type="hidden" name="page" value="akun" />
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau email..."
                    value="<?= htmlspecialchars($search) ?>" style="max-width:260px;" />
                <select name="role" class="form-control" style="max-width:150px;">
                    <option value="">Semua Role</option>
                    <option value="admin"     <?= $filterRole==='admin'     ?'selected':'' ?>>Admin</option>
                    <option value="dosen"     <?= $filterRole==='dosen'     ?'selected':'' ?>>Dosen</option>
                    <option value="mahasiswa" <?= $filterRole==='mahasiswa' ?'selected':'' ?>>Mahasiswa</option>
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
                        <th>Status</th>
                        <th>Terdaftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($akuns)): ?>
                        <?php $no = ($page - 1) * $perPage + 1;
                        foreach ($akuns as $akun): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($akun['nama']) ?></td>
                                <td class="text-muted"><?= htmlspecialchars($akun['email']) ?></td>
                                <td><?= htmlspecialchars($akun['nim_nip'] ?? '-') ?></td>
                                <td>
                                    <span class="badge badge-<?= $akun['role']==='admin' ? 'blue' : ($akun['role']==='dosen' ? 'green' : 'orange') ?>">
                                        <?= ucfirst($akun['role']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-<?= ($akun['status'] ?? 'aktif')==='aktif' ? 'active' : 'inactive' ?>">
                                        <?= ($akun['status'] ?? 'aktif')==='aktif' ? 'Aktif' : 'Nonaktif' ?>
                                    </span>
                                </td>
                                <td class="text-muted"><?= date('d M Y', strtotime($akun['created_at'])) ?></td>
                                <td>
                                    <div style="display:flex;gap:0.35rem;flex-wrap:wrap;">
                                        <a href="index.php?page=akun&action=edit&id=<?= $akun['id'] ?>"
                                            class="btn btn-secondary btn-sm">Edit</a>
                                        <a href="index.php?page=akun&action=resetPassword&id=<?= $akun['id'] ?>"
                                            class="btn btn-secondary btn-sm"
                                            onclick="return confirm('Reset password <?= htmlspecialchars($akun['nama']) ?> ke password123?')">Reset PW</a>
                                        <a href="index.php?page=akun&action=hapus&id=<?= $akun['id'] ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Hapus akun <?= htmlspecialchars($akun['nama']) ?>?')">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted" style="padding:2rem;">
                                <?= $search ? 'Tidak ada akun yang cocok.' : 'Belum ada akun terdaftar.' ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div style="display:flex; align-items:center; justify-content:space-between; margin-top:0.5rem;">
                <span class="text-muted">Menampilkan <?= count($akuns) ?> dari <?= $total ?> akun</span>
                <?php
                $baseUrl = 'index.php?page=akun' . ($search ? '&search='.urlencode($search) : '') . ($filterRole ? '&role='.$filterRole : '');
                require 'app/views/layouts/pagination.php';
                ?>
            </div>
        </div>

    </section>

    <?php require 'app/views/layouts/footer.php'; ?>
</main>
