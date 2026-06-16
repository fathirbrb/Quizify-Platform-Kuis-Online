<?php
$nama = $_SESSION['nama'] ?? 'Mahasiswa';
$page = $_GET['page'] ?? 'mahasiswa-dashboard';
$titles = [
    'mahasiswa-dashboard' => ['Dashboard Mahasiswa', 'Ringkasan kuis, progres, dan aktivitas terbarumu.'],
    'kuis-tersedia' => ['Kuis Tersedia', 'Daftar kuis yang bisa kamu kerjakan sekarang.'],
    'kerjakan' => ['Kerjakan Kuis', 'Jawab soal dengan teliti sebelum mengumpulkan.'],
    'nilai' => ['Nilai Saya', 'Rekap nilai dari semua kuis yang sudah dikerjakan.'],
];
[$pageTitle, $pageSubtitle] = $titles[$page] ?? $titles['mahasiswa-dashboard'];

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
        <span class="role-chip">&#9670; Mahasiswa</span>
        <div class="avatar-wrap">
            <div class="avatar"><?= htmlspecialchars($initials) ?></div>
            <span class="avatar-name"><?= htmlspecialchars($nama) ?></span>
        </div>
        <a href="index.php?page=logout" class="btn-logout" title="Keluar"
           onclick="return confirm('Yakin ingin logout?')">&#x2192; Logout</a>
    </div>
</header>
