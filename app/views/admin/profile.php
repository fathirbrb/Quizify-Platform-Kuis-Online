<?php

require 'app/views/layouts/header.php';
require 'app/views/layouts/sidebar.php';

$nama  = $_SESSION['nama']  ?? 'Administrator';
$email = $_SESSION['email'] ?? 'admin@quizify.com';
$words = explode(' ', $nama);
$initials = strtoupper(substr($words[0],0,1)) . (isset($words[1]) ? strtoupper(substr($words[1],0,1)) : '');
?>

<main class="main">
    <?php require 'app/views/layouts/topbar.php'; ?>

    <section class="page-content">

        <?php if (isset($_GET['sukses'])): ?>
            <div class="alert alert-success">Profil berhasil disimpan.</div>
        <?php endif; ?>
        <?php if (isset($error) && $error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="section-header">
            <div>
                <h2>Profile Settings</h2>
                <p class="text-muted" style="margin-top:0.15rem;">Ubah informasi akun dan password.</p>
            </div>
        </div>

        <div class="profile-wrap">

            <!-- Info profile -->
            <div class="card" style="margin-bottom:1rem;">
                <div class="profile-avatar-row">
                    <div class="profile-avatar-big"><?= $initials ?></div>
                    <div>
                        <div style="font-weight:700; font-size:1rem;"><?= htmlspecialchars($nama) ?></div>
                        <div class="profile-avatar-info"><?= htmlspecialchars($email) ?></div>
                        <span class="badge badge-blue" style="margin-top:0.35rem;">Admin</span>
                    </div>
                </div>

                <form method="POST" action="index.php?page=profile&action=simpan">
                    <div class="form-group">
                        <label>Nama Lengkap <span class="req">*</span></label>
                        <input type="text" name="nama" class="form-control"
                            value="<?= htmlspecialchars($nama) ?>" required />
                    </div>
                    <div class="form-group">
                        <label>Email <span class="req">*</span></label>
                        <input type="email" name="email" class="form-control"
                            value="<?= htmlspecialchars($email) ?>" required />
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>

            <!-- Ubah password -->
            <div class="card">
                <h4 style="margin-bottom:1rem;">Ubah Password</h4>
                <form method="POST" action="index.php?page=profile&action=ubahPassword">
                    <div class="form-group">
                        <label>Password Saat Ini <span class="req">*</span></label>
                        <div class="input-password">
                            <input type="password" name="password_lama" class="form-control"
                                placeholder="••••••••" required />
                            <button type="button" class="btn-toggle-pass" onclick="togglePw(this)">&#128065;</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Password Baru <span class="req">*</span></label>
                        <div class="input-password">
                            <input type="password" name="password_baru" class="form-control"
                                placeholder="Minimal 6 karakter" required minlength="6" />
                            <button type="button" class="btn-toggle-pass" onclick="togglePw(this)">&#128065;</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password Baru <span class="req">*</span></label>
                        <div class="input-password">
                            <input type="password" name="password_konfirmasi" class="form-control"
                                placeholder="Ulangi password baru" required />
                            <button type="button" class="btn-toggle-pass" onclick="togglePw(this)">&#128065;</button>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Ubah Password</button>
                    </div>
                </form>
            </div>

        </div>

    </section>

    <?php require 'app/views/layouts/footer.php'; ?>
</main>

<script>
function togglePw(btn) {
    var input = btn.previousElementSibling;
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
