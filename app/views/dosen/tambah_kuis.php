
<?php
require_once 'layout/header.php';
require_once 'layout/sidebar.php';
require_once 'layout/navbar.php';
?>

<h1 class="title">Tambah Kuis</h1>

<div class="form-box">

    <input type="text" placeholder="Judul Kuis">

    <textarea placeholder="Deskripsi Kuis"></textarea>

    <div class="grid-2">

        <input type="number" placeholder="Durasi (menit)">

        <input type="number" placeholder="Jumlah Soal">

    </div>

    <div class="grid-2">

        <input type="datetime-local">

        <input type="datetime-local">

    </div>

    <div class="checkbox">
        <input type="checkbox"> Random Soal
    </div>

    <button class="btn">Simpan Kuis</button>

</div>

<?php
require_once 'layout/footer.php';
?>
