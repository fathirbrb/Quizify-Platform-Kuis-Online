<?php
require_once __DIR__ . '/../layouts/DosenLayout/header.php';
require_once __DIR__ . '/../layouts/DosenLayout/sidebar.php';
require_once __DIR__ . '/../layouts/DosenLayout/navbar.php';
?>

<section class="page-content">
    <div class="breadcrumb">
        <a href="index.php?page=dosen-kelas">Kelola Kelas</a>
        <span>&rsaquo;</span>
        <span>Konfigurasi Isi Kelas</span>
    </div>

    <div class="filter-row" style="margin-bottom:1rem;">
        <a href="#info-kelas" class="btn btn-secondary">Informasi Kelas</a>
        <a href="#kelola-kuis" class="btn btn-secondary">Kelola Kuis</a>
        <a href="#daftar-mahasiswa" class="btn btn-secondary">Daftar Mahasiswa</a>
    </div>

    <div id="info-kelas" class="form-card" style="margin-bottom:1rem;">
        <h2>Konfigurasi Kelas</h2>
        <p class="text-muted" style="margin-bottom:1.25rem;">Atur identitas kelas sebelum mengelola kuis.</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?page=dosen-konfigurasi-kelas&kelas_id=<?= urlencode($kelasId) ?>">
            <input type="hidden" name="kelas_id" value="<?= htmlspecialchars($kelasId) ?>">

            <div class="form-row">
                <div class="form-group">
                    <label>Nama Kelas</label>
                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($kelas['kelas'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label>Jurusan</label>
                    <select name="jurusan" class="form-control">
                        <option value="">Pilih Jurusan</option>
                        <?php foreach (($jurusanList ?? []) as $jurusan): ?>
                            <option value="<?= htmlspecialchars($jurusan['nama']) ?>" <?= ($kelas['jurusan'] ?? '') === $jurusan['nama'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($jurusan['nama']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Kode Kelas</label>
                    <input type="text" name="kode_kelas" class="form-control" value="<?= htmlspecialchars($kelas['kode_kelas'] ?? '') ?>" placeholder="Contoh: SI-4A-2026">
                </div>

                <div class="form-group">
                    <label>Tahun Ajaran</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($kelas['tahun_ajaran'] ?? '-') ?>" disabled>
                </div>
            </div>

            <div class="form-group">
                <label>Kode Masuk Kelas</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($kelas['invite_code'] ?? '-') ?>" disabled>
            </div>

            <div class="form-actions">
                <a href="index.php?page=dosen-kelas" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan Kelas</button>
            </div>
        </form>
    </div>

    <div id="kelola-kuis" class="section-header">
        <div>
            <h2>Isi Kelas</h2>
            <p class="text-muted">Mata kuliah dan kuis untuk <?= htmlspecialchars($kelas['kelas'] ?? '-') ?>.</p>
        </div>
        <a href="index.php?page=dosen-tambah-kuis&kelas_id=<?= urlencode($kelasId) ?>" class="btn btn-primary">&#43; Tambah Kuis</a>
    </div>

    <div class="card" style="margin-bottom:1rem;">
        <table class="table">
            <thead>
                <tr>
                    <th>Mata Kuliah</th>
                    <th>SKS</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($matkulDiampu)): ?>
                    <?php foreach ($matkulDiampu as $mk): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($mk['kode_matkul']) ?></strong>
                                <div class="text-muted"><?= htmlspecialchars($mk['nama_matkul']) ?></div>
                            </td>
                            <td><span class="badge badge-blue"><?= (int) $mk['sks'] ?> SKS</span></td>
                            <td>
                                <a href="index.php?page=dosen-tambah-kuis&kelas_id=<?= urlencode($kelasId) ?>&matkul_id=<?= urlencode($mk['matkul_id']) ?>" class="btn btn-secondary btn-sm">&#43; Kuis</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="empty-row">Belum ada mata kuliah di kelas ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>Judul Kuis</th>
                    <th>Mata Kuliah</th>
                    <th>Durasi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($allKuis)): ?>
                    <?php foreach ($allKuis as $kuis): ?>
                        <tr>
                            <td><?= htmlspecialchars($kuis['judul']) ?></td>
                            <td><?= htmlspecialchars($kuis['kode_matkul'] . ' - ' . $kuis['nama_matkul']) ?></td>
                            <td><?= (int) $kuis['durasi'] ?> Menit</td>
                            <td><span class="badge badge-blue"><?= ucfirst(htmlspecialchars($kuis['status'] ?? 'draft')) ?></span></td>
                            <td>
                                <div style="display:flex;gap:0.35rem;flex-wrap:wrap;">
                                    <a href="index.php?page=dosen-soal&kuis_id=<?= urlencode($kuis['id']) ?>" class="btn btn-secondary btn-sm">Soal</a>
                                    <a href="index.php?page=dosen-konfigurasi-kuis&kuis_id=<?= urlencode($kuis['id']) ?>" class="btn btn-secondary btn-sm">Timer</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="empty-row">Belum ada kuis di kelas ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div id="daftar-mahasiswa" class="section-header" style="margin-top:1rem;">
        <div>
            <h2>Daftar Mahasiswa</h2>
            <p class="text-muted">Mahasiswa yang tergabung di kelas ini.</p>
        </div>
    </div>

    <form method="GET" action="index.php" class="card" style="margin-bottom:1rem;">
        <input type="hidden" name="page" value="dosen-konfigurasi-kelas">
        <input type="hidden" name="kelas_id" value="<?= htmlspecialchars($kelasId) ?>">
        <div class="filter-row">
            <input type="text" name="search_mahasiswa" class="form-control" placeholder="Cari nama, NIM, atau email..." value="<?= htmlspecialchars($keywordMahasiswa ?? '') ?>" style="max-width:360px;">
            <button type="submit" class="btn btn-primary">Cari</button>
            <a href="index.php?page=dosen-konfigurasi-kelas&kelas_id=<?= urlencode($kelasId) ?>#daftar-mahasiswa" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Email</th>
                    <th>Tanggal Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($mahasiswaKelas)): ?>
                    <?php foreach ($mahasiswaKelas as $mhs): ?>
                        <tr>
                            <td><?= htmlspecialchars($mhs['nama']) ?></td>
                            <td><?= htmlspecialchars($mhs['nim_nip'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($mhs['email'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($mhs['joined_at'] ?? '-') ?></td>
                            <td>
                                <a href="index.php?page=dosen-hapus-mahasiswa-kelas&kelas_id=<?= urlencode($kelasId) ?>&mahasiswa_id=<?= urlencode($mhs['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus mahasiswa dari kelas ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="empty-row">Belum ada mahasiswa di kelas ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/DosenLayout/footer.php'; ?>
