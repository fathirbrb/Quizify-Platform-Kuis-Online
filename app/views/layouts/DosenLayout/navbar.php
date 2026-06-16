<?php
$nama = $_SESSION['nama'] ?? 'Dosen';
$page = $_GET['page'] ?? 'dosen-dashboard';
$titles = [
    'dosen-dashboard' => ['Dashboard Dosen', 'Ringkasan kelas, kuis, soal, dan aktivitas mahasiswa.'],
    'dosen-kuis' => ['Kelola Kuis', 'Buat, pantau, dan atur kuis untuk kelas.'],
    'dosen-tambah-kuis' => ['Tambah Kuis', 'Lengkapi detail kuis sebelum dibagikan ke mahasiswa.'],
    'dosen-soal' => ['Kelola Soal', 'Atur daftar soal dan jawaban benar untuk kuis.'],
    'dosen-tambah-soal' => ['Tambah Soal', 'Tambahkan pertanyaan dan pilihan jawaban.'],
    'dosen-mahasiswa' => ['Monitoring Mahasiswa', 'Pantau progres pengerjaan dan nilai mahasiswa.'],
    'dosen-hasil' => ['Laporan Nilai', 'Analisis hasil kuis dan capaian mahasiswa.'],
];
[$pageTitle, $pageSubtitle] = $titles[$page] ?? $titles['dosen-dashboard'];

$words = explode(' ', $nama);
$initials = strtoupper(substr($words[0], 0, 1));
if (isset($words[1])) {
    $initials .= strtoupper(substr($words[1], 0, 1));
}
?>

<main class="main">

<header class="topbar">
    <div class="topbar-left">
        <div class="topbar-title"><?= htmlspecialchars($pageTitle) ?></div>
        <p class="text-muted"><?= htmlspecialchars($pageSubtitle) ?></p>
    </div>

    <div class="topbar-right">
        <span class="role-chip">&#9670; Dosen</span>
        <div class="avatar-wrap">
            <div class="avatar"><?= htmlspecialchars($initials) ?></div>
            <span class="avatar-name"><?= htmlspecialchars($nama) ?></span>
        </div>
        <a href="#modal-logout" class="btn-logout" title="Keluar">&#x2192; Logout</a>
    </div>
</header>
