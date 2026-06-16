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

        <?php if (!empty($kelas)): ?>
            <?php $colors = ['blue', 'green', 'purple', 'orange']; ?>
            <div class="kelas-grid" style="margin-bottom:1rem;">
                <?php foreach ($kelas as $index => $kls): ?>
                    <?php $color = $colors[$index % count($colors)]; ?>
                    <div class="kelas-card card">
                        <div class="kelas-card-header kelas-<?= $color ?>">
                            <div class="kelas-card-icon">
                                <svg viewBox="0 0 24 24" width="28" height="28" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"></path></svg>
                            </div>
                            <div class="kelas-card-stats">
                                <span class="kelas-badge"><?= (int) ($kls['jumlah_dosen'] ?? 0) ?> Dosen · <?= (int) ($kls['jumlah_mahasiswa'] ?? 0) ?> Mhs</span>
                            </div>
                        </div>
                        <div class="kelas-card-body">
                            <h3 class="kelas-nama"><?= htmlspecialchars($kls['nama']) ?></h3>
                            <p class="kelas-jurusan"><?= htmlspecialchars($kls['jurusan'] ?? '-') ?></p>
                            <div class="kelas-meta">
                                <span class="kelas-meta-item">
                                    <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                                    Kode: <?= htmlspecialchars($kls['invite_code'] ?? '-') ?>
                                </span>
                                <span class="kelas-meta-item">
                                    <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                    <?= htmlspecialchars($kls['tahun_ajaran'] ?? '-') ?>
                                </span>
                            </div>
                            <div style="margin-top:0.75rem;display:flex;gap:0.5rem;">
                                <a href="index.php?page=kelas&action=edit&id=<?= $kls['id'] ?>" class="btn btn-secondary btn-sm">Edit</a>
                                <a href="index.php?page=kelas&action=hapus&id=<?= $kls['id'] ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return konfirmasiHapus('<?= htmlspecialchars($kls['nama']) ?>')">Hapus</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($kelas)): ?>
            <div class="card empty-row">
                <p>Belum ada kelas.</p>
                <p class="text-muted" style="margin-top:0.5rem;">Klik tombol "Tambah Kelas" di atas untuk membuat kelas baru.</p>
            </div>
        <?php endif; ?>

    </section>

    <?php require 'app/views/layouts/footer.php'; ?>
