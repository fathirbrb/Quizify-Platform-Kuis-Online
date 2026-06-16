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
        <div style="display:flex; gap:0.5rem; align-items:center;">
            <a href="#modal-tambah-matkul" class="btn btn-secondary">&#43; Tambah Mata Kuliah</a>
            <a href="index.php?page=dosen-tambah-kuis&kelas_id=<?= urlencode($kelasId) ?>" class="btn btn-primary">&#43; Tambah Kuis</a>
        </div>
    </div>

    <div class="kelas-grid" style="margin-bottom:1.5rem;">
        <?php if (!empty($matkulDiampu)): ?>
            <?php $colors = ['blue', 'green', 'purple', 'orange']; ?>
            <?php foreach ($matkulDiampu as $index => $mk): ?>
                <?php $color = $colors[$index % count($colors)]; ?>
                <div class="kelas-card card">
                    <div class="kelas-card-header kelas-<?= $color ?>">
                        <div class="kelas-card-icon">
                            <svg viewBox="0 0 24 24" width="28" height="28" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                        </div>
                        <div class="kelas-card-stats">
                            <span class="kelas-badge"><?= (int) $mk['sks'] ?> SKS</span>
                        </div>
                    </div>
                    <div class="kelas-card-body">
                        <h3 class="kelas-nama"><?= htmlspecialchars($mk['nama_matkul']) ?></h3>
                        <p class="kelas-jurusan"><?= htmlspecialchars($mk['kode_matkul']) ?></p>
                        <div style="margin-top:auto; padding-top:0.75rem;">
                            <a href="index.php?page=dosen-tambah-kuis&kelas_id=<?= urlencode($kelasId) ?>&matkul_id=<?= urlencode($mk['matkul_id']) ?>" 
                               class="btn btn-secondary btn-sm" style="width:100%; text-align:center;">&#43; Kuis</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="card empty-row" style="grid-column: 1 / -1;">
                Belum ada mata kuliah di kelas ini.
            </div>
        <?php endif; ?>
    </div>

    <?php if (!empty($allKuis)): ?>
        <div class="timeline" style="margin-top:1rem; margin-bottom: 2rem;">
            <?php foreach ($allKuis as $kuis): ?>
                <?php
                    $status = strtolower($kuis['status'] ?? 'draft');
                    if ($status === 'aktif') {
                        $dotClass = 'timeline-dot-blue';
                        $badgeClass = 'badge-blue';
                        $statusTampil = 'Aktif / Sedang Berjalan';
                    } elseif ($status === 'terjadwal') {
                        $dotClass = 'timeline-dot-purple';
                        $badgeClass = 'badge-purple';
                        $statusTampil = 'Terjadwal';
                    } elseif ($status === 'selesai') {
                        $dotClass = 'timeline-dot-green';
                        $badgeClass = 'badge-green';
                        $statusTampil = 'Selesai';
                    } else {
                        $dotClass = 'timeline-dot-gray';
                        $badgeClass = 'badge-yellow';
                        $statusTampil = 'Draft';
                    }
                ?>
                <div class="timeline-item">
                    <div class="timeline-dot <?= $dotClass ?>"></div>
                    <div class="timeline-content card">
                        <div class="timeline-top">
                            <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($statusTampil) ?></span>
                            <span class="text-muted" style="font-size:0.75rem;">
                                <?= htmlspecialchars($kuis['kode_matkul'] ?? '') ?> · <?= (int) ($kuis['jumlah_soal'] ?? 0) ?> soal · <?= (int) ($kuis['durasi'] ?? 30) ?> menit
                            </span>
                        </div>

                        <h3 class="timeline-title"><?= htmlspecialchars($kuis['judul'] ?? '') ?></h3>
                        <p class="timeline-matkul"><?= htmlspecialchars($kuis['nama_matkul'] ?? '') ?></p>

                        <?php if (!empty($kuis['deskripsi'])): ?>
                            <p class="text-muted" style="margin-top:0.35rem;font-size:0.82rem;"><?= htmlspecialchars($kuis['deskripsi']) ?></p>
                        <?php endif; ?>

                        <?php if (!empty($kuis['waktu_mulai']) || !empty($kuis['waktu_selesai'])): ?>
                            <div class="timeline-schedule" style="margin-bottom:0.5rem;">
                                <?php if (!empty($kuis['waktu_mulai'])): ?>
                                    <span class="text-muted" style="font-size:0.78rem;">
                                        Mulai: <?= htmlspecialchars($kuis['waktu_mulai']) ?>
                                    </span>
                                <?php endif; ?>
                                <?php if (!empty($kuis['waktu_selesai'])): ?>
                                    <span class="text-muted" style="font-size:0.78rem;">
                                        Selesai: <?= htmlspecialchars($kuis['waktu_selesai']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <div style="margin-top:0.75rem; display:flex; gap:0.5rem;">
                            <a href="index.php?page=dosen-soal&kuis_id=<?= urlencode($kuis['id']) ?>" class="btn btn-secondary btn-sm">Kelola Soal</a>
                            <a href="index.php?page=dosen-konfigurasi-kuis&kuis_id=<?= urlencode($kuis['id']) ?>" class="btn btn-secondary btn-sm">Timer & Status</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="card empty-row" style="margin-bottom: 2rem;">
            Belum ada kuis di kelas ini.
        </div>
    <?php endif; ?>

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
    <!-- Modal Tambah Mata Kuliah (CSS Target Modal) -->
    <div id="modal-tambah-matkul" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-header">
                <h3 class="modal-title">Tambah Mata Kuliah Ke Kelas</h3>
                <a href="#" class="modal-close">&times;</a>
            </div>
            
            <form method="POST" action="index.php?page=dosen-konfigurasi-kelas&kelas_id=<?= urlencode($kelasId) ?>">
                <input type="hidden" name="action" value="tambah_matkul">
                
                <div class="form-group" style="margin-bottom:1rem;">
                    <label style="display:block; margin-bottom:0.5rem; font-weight:600;">Metode Penambahan</label>
                    <div style="display:flex; gap:1.5rem; align-items:center;">
                        <label class="checkbox-line" style="font-size:0.85rem; cursor:pointer;">
                            <input type="radio" name="pilihan_matkul" value="pilih" checked onclick="toggleMatkulInput('pilih')"> Pilih dari daftar
                        </label>
                        <label class="checkbox-line" style="font-size:0.85rem; cursor:pointer;">
                            <input type="radio" name="pilihan_matkul" value="baru" onclick="toggleMatkulInput('baru')"> Buat baru
                        </label>
                    </div>
                </div>

                <!-- Pilihan 1: Pilih dari daftar -->
                <div id="matkul-pilih-section" class="form-group" style="margin-bottom:1.25rem;">
                    <label style="display:block; margin-bottom:0.35rem; font-size:0.85rem; font-weight:500;">Pilih Mata Kuliah</label>
                    <select name="matkul_id" class="form-control">
                        <option value="">-- Pilih Mata Kuliah --</option>
                        <?php foreach (($allMatkulList ?? []) as $mk): ?>
                            <option value="<?= htmlspecialchars($mk['id']) ?>">
                                <?= htmlspecialchars($mk['kode'] . ' - ' . $mk['nama'] . ' (' . $mk['sks'] . ' SKS)') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Pilihan 2: Buat Baru -->
                <div id="matkul-baru-section" style="display:none; margin-bottom:1.25rem;">
                    <div class="form-group" style="margin-bottom:0.75rem;">
                        <label style="display:block; margin-bottom:0.35rem; font-size:0.85rem; font-weight:500;">Kode Mata Kuliah</label>
                        <input type="text" name="kode" class="form-control" placeholder="Contoh: IF102">
                    </div>
                    <div class="form-group" style="margin-bottom:0.75rem;">
                        <label style="display:block; margin-bottom:0.35rem; font-size:0.85rem; font-weight:500;">Nama Mata Kuliah</label>
                        <input type="text" name="nama_matkul" class="form-control" placeholder="Contoh: Algoritma & Pemrograman">
                    </div>
                    <div class="form-group" style="margin-bottom:0.75rem;">
                        <label style="display:block; margin-bottom:0.35rem; font-size:0.85rem; font-weight:500;">Jumlah SKS</label>
                        <select name="sks" class="form-control">
                            <option value="1">1 SKS</option>
                            <option value="2">2 SKS</option>
                            <option value="3" selected>3 SKS</option>
                        </select>
                    </div>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:0.5rem; margin-top:1.5rem;">
                    <a href="#" class="btn btn-secondary btn-sm">Batal</a>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal CSS Styles -->
    <style>
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.25s;
        z-index: 1000;
    }
    .modal-overlay:target {
        opacity: 1;
        pointer-events: auto;
    }
    .modal-card {
        background: white;
        border-radius: var(--radius);
        max-width: 500px;
        width: 90%;
        padding: 1.75rem;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
        transform: scale(0.9);
        transition: transform 0.25s;
    }
    .modal-overlay:target .modal-card {
        transform: scale(1);
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
    }
    .modal-title {
        margin: 0;
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--black);
    }
    .modal-close {
        text-decoration: none;
        color: var(--gray-400);
        font-size: 1.5rem;
        line-height: 1;
    }
    .modal-close:hover {
        color: var(--black);
    }
    </style>

    <!-- Modal Toggle Script -->
    <script>
    function toggleMatkulInput(type) {
        var pilihSec = document.getElementById('matkul-pilih-section');
        var baruSec = document.getElementById('matkul-baru-section');
        if (type === 'pilih') {
            pilihSec.style.display = 'block';
            baruSec.style.display = 'none';
        } else {
            pilihSec.style.display = 'none';
            baruSec.style.display = 'block';
        }
    }
    </script>

</section>

<?php require_once __DIR__ . '/../layouts/DosenLayout/footer.php'; ?>
