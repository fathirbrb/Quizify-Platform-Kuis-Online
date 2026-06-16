<?php

$isEdit = isset($kls) && $kls;
$action = $isEdit ? 'edit&id=' . $kls['id'] : 'tambah';

require 'app/views/layouts/header.php';
require 'app/views/layouts/sidebar.php';
?>

    <main class="main">
        <?php require 'app/views/layouts/topbar.php'; ?>

        <section class="page-content">

            <div class="breadcrumb">
                <a href="index.php?page=kelas">Kelas</a>
                <span>&rsaquo;</span>
                <span><?= $isEdit ? 'Edit' : 'Tambah' ?></span>
            </div>

            <div class="form-card">
                <h2><?= $isEdit ? 'Edit Kelas' : 'Tambah Kelas Baru' ?></h2>
                <p class="text-muted" style="margin-bottom: 1.25rem;">Isi data kelas.</p>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <?php if ($sukses): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($sukses) ?></div>
                <?php endif; ?>

                <form method="POST" action="index.php?page=kelas&action=<?= $action ?>">

                    <div class="form-group">
                        <label for="nama">Nama Kelas <span class="req">*</span></label>
                        <input type="text" id="nama" name="nama" class="form-control"
                               placeholder="Contoh: SI-A 2024"
                               value="<?= htmlspecialchars($kls['nama'] ?? '') ?>" required />
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="jurusan">Jurusan <span class="req">*</span></label>
                            <select id="jurusan" name="jurusan_id" class="form-control" required>
                                <option value="">Pilih Jurusan</option>
                                <?php foreach ($jurusan as $j): ?>
                                    <option value="<?= htmlspecialchars($j['id']) ?>" <?= (string) ($kls['jurusan_id'] ?? '') === (string) $j['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($j['nama']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="kode_kelas">Kode Kelas</label>
                            <input type="text" id="kode_kelas" name="kode_kelas" class="form-control"
                                   placeholder="Contoh: SI-4A-2026"
                                   value="<?= htmlspecialchars($kls['kode_kelas'] ?? '') ?>" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="tahun_ajaran">Tahun Ajaran</label>
                        <input type="text" id="tahun_ajaran" name="tahun_ajaran" class="form-control"
                               placeholder="Contoh: 2024/2025"
                               value="<?= htmlspecialchars($kls['tahun_ajaran'] ?? '') ?>" />
                    </div>

                    <div class="form-group">
                        <label for="invite_code">Kode Masuk Kelas</label>
                        <input type="text" id="invite_code" name="invite_code" class="form-control"
                               placeholder="Kosongkan untuk generate otomatis"
                               value="<?= htmlspecialchars($kls['invite_code'] ?? '') ?>" />
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" class="form-control" placeholder="Catatan singkat kelas"><?= htmlspecialchars($kls['deskripsi'] ?? '') ?></textarea>
                    </div>

                    <div class="form-actions">
                        <a href="index.php?page=kelas" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Kelas' ?>
                        </button>
                    </div>

                </form>
            </div>

            <?php if ($isEdit): ?>
                <div class="form-card" style="margin-top:1rem;">
                    <h2>Assign Dosen</h2>
                    <p class="text-muted" style="margin-bottom:1rem;">Hubungkan dosen dan mata kuliah untuk kelas ini.</p>

                    <form method="POST" action="index.php?page=kelas&action=assignDosen" class="filter-row" style="margin-bottom:1rem;">
                        <input type="hidden" name="kelas_id" value="<?= htmlspecialchars($kls['id']) ?>">
                        <select name="dosen_id" class="form-control" required>
                            <option value="">Pilih Dosen</option>
                            <?php foreach ($dosenList as $dosen): ?>
                                <option value="<?= htmlspecialchars($dosen['id']) ?>"><?= htmlspecialchars($dosen['nama']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="matkul_id" class="form-control" required>
                            <option value="">Pilih Mata Kuliah</option>
                            <?php foreach ($matkulList as $mk): ?>
                                <option value="<?= htmlspecialchars($mk['id']) ?>"><?= htmlspecialchars($mk['kode'] . ' - ' . $mk['nama']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-primary">Assign</button>
                    </form>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Dosen</th>
                                <th>Mata Kuliah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($assignments)): ?>
                                <?php foreach ($assignments as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['nama_dosen']) ?></td>
                                        <td><?= htmlspecialchars($item['kode'] . ' - ' . $item['nama_matkul']) ?></td>
                                        <td>
                                            <a href="index.php?page=kelas&action=hapusAssignDosen&id=<?= urlencode($item['id']) ?>&kelas_id=<?= urlencode($kls['id']) ?>" class="btn btn-danger btn-sm">Hapus</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada dosen yang di-assign.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        </section>

        <?php require 'app/views/layouts/footer.php'; ?>
