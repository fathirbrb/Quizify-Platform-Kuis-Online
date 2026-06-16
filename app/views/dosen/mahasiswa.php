<?php
require_once __DIR__ . '/../layouts/DosenLayout/header.php';
require_once __DIR__ . '/../layouts/DosenLayout/sidebar.php';
require_once __DIR__ . '/../layouts/DosenLayout/navbar.php';

$perPage = 5;
$page = max(1, (int) ($_GET['p'] ?? 1));
$total = count($allMhs);
$totalPages = max(1, (int) ceil($total / $perPage));
$page = min($page, $totalPages);
$mhs = array_slice($allMhs, ($page - 1) * $perPage, $perPage);

$baseUrl = 'index.php?page=dosen-mahasiswa'
    . ($selectedKelasId !== null ? '&kelas_id=' . urlencode((string)$selectedKelasId) : '')
    . ($selectedMatkulId !== null ? '&matkul_id=' . urlencode((string)$selectedMatkulId) : '')
    . ($filterStatus !== '' ? '&status=' . urlencode($filterStatus) : '');
?>

<section class="page-content">
    <div class="section-header" style="margin-bottom:0.75rem;">
        <div>
            <h2>Monitoring Mahasiswa</h2>
            <p class="text-muted">Pantau progres pengerjaan kuis dan nilai mahasiswa berdasarkan kelas dan mata kuliah.</p>
        </div>
    </div>

    <!-- Breadcrumbs Navigasi -->
    <nav class="breadcrumb" style="margin-bottom:1.5rem;">
        <a href="index.php?page=dosen-mahasiswa" style="text-decoration:none; display:inline-flex; align-items:center; gap:4px;">
            <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
            Monitoring Mahasiswa
        </a>
        <?php if ($selectedKelasId !== null): ?>
            <span style="color: var(--gray-400); margin: 0 0.25rem;">/</span>
            <?php if ($selectedMatkulId !== null): ?>
                <a href="index.php?page=dosen-mahasiswa&kelas_id=<?= urlencode((string)$selectedKelasId) ?>" style="text-decoration:none;">
                    Kelas: <?= htmlspecialchars($selectedKelas['kelas'] ?? '') ?>
                </a>
                <span style="color: var(--gray-400); margin: 0 0.25rem;">/</span>
                <span class="text-muted" style="font-weight:600; color:var(--gray-600);">
                    Matkul: <?= htmlspecialchars($selectedMatkul['nama_matkul'] ?? '') ?>
                </span>
            <?php else: ?>
                <span class="text-muted" style="font-weight:600; color:var(--gray-600);">
                    Kelas: <?= htmlspecialchars($selectedKelas['kelas'] ?? '') ?>
                </span>
            <?php endif; ?>
        <?php endif; ?>
    </nav>

    <?php if ($selectedKelasId === null): ?>
        <!-- LANGKAH 1: PILIH KELAS -->
        <div class="card" style="padding:1.5rem; margin-bottom:1.5rem; border-left: 4px solid var(--accent);">
            <h3 style="margin-top:0; font-size:1.05rem; font-weight:700;">Langkah 1: Pilih Kelas</h3>
            <p class="text-muted" style="margin: 0.25rem 0 0; font-size:0.85rem;">Pilih kelas yang ingin Anda monitor dari daftar kelas di bawah ini.</p>
        </div>

        <?php if (!empty($kelasDiampu)): ?>
            <div class="kelas-grid">
                <?php $colors = ['blue', 'green', 'purple', 'orange']; ?>
                <?php foreach ($kelasDiampu as $index => $kelas): ?>
                    <?php $color = $colors[$index % count($colors)]; ?>
                    <a href="index.php?page=dosen-mahasiswa&kelas_id=<?= urlencode((string)$kelas['kelas_id']) ?>" class="kelas-card card" style="transition: transform 0.2s, box-shadow 0.2s;">
                        <div class="kelas-card-header kelas-<?= $color ?>">
                            <div class="kelas-card-icon">
                                <svg viewBox="0 0 24 24" width="28" height="28" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"></path></svg>
                            </div>
                            <div class="kelas-card-stats">
                                <span class="kelas-badge"><?= (int) ($kelas['jumlah_mahasiswa'] ?? 0) ?> Mahasiswa</span>
                            </div>
                        </div>
                        <div class="kelas-card-body">
                            <h3 class="kelas-nama"><?= htmlspecialchars($kelas['kelas'] ?? '') ?></h3>
                            <p class="kelas-jurusan"><?= htmlspecialchars($kelas['jurusan'] ?: '-') ?></p>

                            <div class="kelas-meta">
                                <span class="kelas-meta-item">
                                    <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                    TA: <?= htmlspecialchars($kelas['tahun_ajaran'] ?? '-') ?>
                                </span>
                                <span class="kelas-meta-item">
                                    <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                                    <?= (int) ($kelas['jumlah_matkul'] ?? 0) ?> Mata Kuliah
                                </span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="card empty-row">
                <p>Belum ada kelas yang diampu.</p>
                <p class="text-muted" style="margin-top:0.5rem;">Hubungi admin untuk mendaftarkan kelas Anda.</p>
            </div>
        <?php endif; ?>

    <?php elseif ($selectedMatkulId === null): ?>
        <!-- LANGKAH 2: PILIH MATA KULIAH -->
        <div class="card" style="padding:1.5rem; margin-bottom:1.5rem; border-left: 4px solid var(--accent); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
            <div>
                <h3 style="margin-top:0; font-size:1.05rem; font-weight:700;">Langkah 2: Pilih Mata Kuliah</h3>
                <p class="text-muted" style="margin: 0.25rem 0 0; font-size:0.85rem;">Pilih mata kuliah di kelas <strong><?= htmlspecialchars($selectedKelas['kelas'] ?? '') ?></strong> yang ingin dimonitor.</p>
            </div>
            <a href="index.php?page=dosen-mahasiswa" class="btn btn-secondary btn-sm" style="display:inline-flex; align-items:center; gap:4px;">
                <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none"><polyline points="15 18 9 12 15 6"></polyline></svg>
                Kembali
            </a>
        </div>

        <?php 
        $filteredMatkul = [];
        foreach ($matkulDiampu as $mk) {
            if ((int)$mk['kelas_id'] === (int)$selectedKelasId) {
                $filteredMatkul[] = $mk;
            }
        }
        ?>

        <?php if (!empty($filteredMatkul)): ?>
            <div class="kelas-grid">
                <?php $colors = ['blue', 'green', 'purple', 'orange']; ?>
                <?php foreach ($filteredMatkul as $index => $mk): ?>
                    <?php $color = $colors[$index % count($colors)]; ?>
                    <a href="index.php?page=dosen-mahasiswa&kelas_id=<?= urlencode((string)$selectedKelasId) ?>&matkul_id=<?= urlencode((string)$mk['matkul_id']) ?>" class="kelas-card card" style="transition: transform 0.2s, box-shadow 0.2s;">
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
                            
                            <div class="kelas-meta">
                                <span class="kelas-meta-item">
                                    <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                    TA: <?= htmlspecialchars($mk['tahun_ajaran'] ?? '-') ?>
                                </span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="card empty-row">
                <p>Tidak ada mata kuliah yang diampu untuk kelas ini.</p>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <!-- LANGKAH 3: TABEL NILAI MAHASISWA -->
        <div class="card" style="padding:1.5rem; margin-bottom:1.5rem; border-left: 4px solid var(--accent); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
            <div>
                <h3 style="margin-top:0; font-size:1.05rem; font-weight:700;">Langkah 3: Nilai & Progress Mahasiswa</h3>
                <p class="text-muted" style="margin: 0.25rem 0 0; font-size:0.85rem;">
                    Monitoring nilai mahasiswa pada mata kuliah <strong><?= htmlspecialchars($selectedMatkul['nama_matkul'] ?? '') ?></strong> &bull; Kelas <strong><?= htmlspecialchars($selectedKelas['kelas'] ?? '') ?></strong>.
                </p>
            </div>
            <a href="index.php?page=dosen-mahasiswa&kelas_id=<?= urlencode((string)$selectedKelasId) ?>" class="btn btn-secondary btn-sm" style="display:inline-flex; align-items:center; gap:4px;">
                <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none"><polyline points="15 18 9 12 15 6"></polyline></svg>
                Kembali ke Mata Kuliah
            </a>
        </div>

        <!-- Filter Status Pengerjaan -->
        <form method="GET" action="index.php" class="card" style="margin-bottom:1rem; padding: 1rem;">
            <input type="hidden" name="page" value="dosen-mahasiswa" />
            <input type="hidden" name="kelas_id" value="<?= htmlspecialchars((string)$selectedKelasId) ?>" />
            <input type="hidden" name="matkul_id" value="<?= htmlspecialchars((string)$selectedMatkulId) ?>" />
            <div class="filter-row" style="display:flex; align-items:center; gap: 0.75rem;">
                <span style="font-size:0.85rem; font-weight:600; color:var(--gray-600);">Status:</span>
                <select name="status" class="form-control" style="max-width:190px;">
                    <option value="">Semua Status</option>
                    <option value="belum" <?= $filterStatus === 'belum' ? 'selected' : '' ?>>Belum</option>
                    <option value="sedang" <?= $filterStatus === 'sedang' ? 'selected' : '' ?>>Sedang</option>
                    <option value="selesai" <?= $filterStatus === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                </select>

                <button type="submit" class="btn btn-primary">Terapkan</button>
                <a href="index.php?page=dosen-mahasiswa&kelas_id=<?= urlencode((string)$selectedKelasId) ?>&matkul_id=<?= urlencode((string)$selectedMatkulId) ?>" class="btn btn-secondary">Reset</a>
            </div>
        </form>

        <!-- Tabel Data -->
        <div class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Mata Kuliah</th>
                        <th>Kelas</th>
                        <th>Tahun Ajaran</th>
                        <th>Kuis</th>
                        <th>Status</th>
                        <th>Progress</th>
                        <th>Nilai</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($mhs)): ?>
                        <?php $no = ($page - 1) * $perPage + 1; foreach ($mhs as $m): ?>
                        <?php
                            $status = $m['status'] ?? 'belum';
                            $badge = $status === 'selesai' ? 'green' : ($status === 'sedang' ? 'yellow' : 'red');
                            $statusLabel = ['belum' => 'Belum', 'sedang' => 'Sedang', 'selesai' => 'Selesai'][$status] ?? ucfirst($status);
                            $kuisId = $m['kuis_id'] ?? null;
                            $mahasiswaId = $m['mahasiswa_id'] ?? null;
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($m['nama_mahasiswa']) ?></td>
                            <td>
                                <strong><?= htmlspecialchars($m['kode_matkul']) ?></strong>
                                <div class="text-muted"><?= htmlspecialchars($m['nama_matkul']) ?></div>
                            </td>
                            <td><?= htmlspecialchars($m['kelas']) ?></td>
                            <td><?= htmlspecialchars($m['tahun_ajaran'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($m['judul_kuis'] ?? '-') ?></td>
                            <td><span class="badge badge-<?= $badge ?>"><?= htmlspecialchars($statusLabel) ?></span></td>
                            <td><?= (int) ($m['progress'] ?? 0) ?>%</td>
                            <td><?= $m['nilai'] !== null ? htmlspecialchars($m['nilai']) : '-' ?></td>
                            <td>
                                <?php if (($m['status'] ?? '') === 'selesai' && $kuisId !== null && $mahasiswaId !== null): ?>
                                    <a href="index.php?page=dosen-detail-mahasiswa&kuis_id=<?= urlencode((string) $kuisId) ?>&mahasiswa_id=<?= urlencode((string) $mahasiswaId) ?>"
                                       class="btn btn-secondary btn-sm">Detail</a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="empty-row">Belum ada data monitoring untuk filter ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div style="display:flex;align-items:center;justify-content:space-between;margin-top:0.75rem; padding: 1rem;">
                <span class="text-muted">Menampilkan <?= count($mhs) ?> dari <?= $total ?> mahasiswa</span>
                <?php require 'app/views/layouts/pagination.php'; ?>
            </div>
        </div>

    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/../layouts/DosenLayout/footer.php'; ?>
