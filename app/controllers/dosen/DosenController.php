<?php

class DosenController{

    public function dashboard(){
        require_once 'app/views/dosen/dashboard.php';
    }

    public function kuis(){
        require_once 'app/views/dosen/kuis.php';
    }

    public function tambahKuis(){
        require_once 'app/views/dosen/tambah_kuis.php';
    }

    public function soal(){
        require_once 'app/views/dosen/soal.php';
    }

    public function tambahSoal(){
        require_once 'app/views/dosen/tambah_soal.php';
    }

    public function mahasiswa(){
        require_once 'app/views/dosen/mahasiswa.php';
    }

    public function hasil(){
        require_once 'app/views/dosen/hasil.php';
    }
}
?>
