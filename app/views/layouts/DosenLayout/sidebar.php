<?php
$currentPage = $_GET['page'] ?? 'dashboard';
?>

<div class="sidebar">

    <div class="logo">
        <h1>Quizify</h1>
        <p>Dosen Panel</p>
    </div>

    <ul class="menu">

        <li>
            <a href="?page=dashboard"
               class="<?= ($currentPage == 'dashboard') ? 'active' : '' ?>">
               Dashboard
            </a>
        </li>

        <li>
            <a href="?page=kuis"
               class="<?= ($currentPage == 'kuis') ? 'active' : '' ?>">
               Kelola Kuis
            </a>
        </li>

        <li>
            <a href="?page=soal"
               class="<?= ($currentPage == 'soal') ? 'active' : '' ?>">
               Kelola Soal
            </a>
        </li>

        <li>
            <a href="?page=mahasiswa"
               class="<?= ($currentPage == 'mahasiswa') ? 'active' : '' ?>">
               Monitoring Mahasiswa
            </a>
        </li>

        <li>
            <a href="?page=hasil"
               class="<?= ($currentPage == 'hasil') ? 'active' : '' ?>">
               Hasil Nilai
            </a>
        </li>

    </ul>

</div>