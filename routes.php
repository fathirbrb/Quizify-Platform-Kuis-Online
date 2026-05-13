
<?php

require_once 'app/controllers/DosenController.php';

$page = $_GET['page'] ?? 'dashboard';

$dosen = new DosenController();

switch($page){

    case 'dashboard':
        $dosen->dashboard();
        break;

    case 'kuis':
        $dosen->kuis();
        break;

    case 'tambah-kuis':
        $dosen->tambahKuis();
        break;

    case 'soal':
        $dosen->soal();
        break;

    case 'tambah-soal':
        $dosen->tambahSoal();
        break;

    case 'mahasiswa':
        $dosen->mahasiswa();
        break;

    case 'hasil':
        $dosen->hasil();
        break;

    default:
        echo "Halaman tidak ditemukan";
}
?>
