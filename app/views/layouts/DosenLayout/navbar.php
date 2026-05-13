<?php
session_start();

$_SESSION['nama'] = "Dimas";
$nama = $_SESSION['nama'] ?? 'Dosen';
?>

<div class="main-content">

<div class="navbar">

    <div>
        <h2>Halo, <?= $nama ?></h2>
    </div>

    <div class="profile">
        <span>Dosen</span>
    </div>

</div>