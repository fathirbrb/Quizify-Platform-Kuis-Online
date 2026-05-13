
<?php
require_once 'layout/header.php';
require_once 'layout/sidebar.php';
require_once 'layout/navbar.php';
?>

<h1 class="title">Tambah Soal</h1>

<div class="form-box">

    <textarea placeholder="Masukkan Pertanyaan"></textarea>

    <input type="text" placeholder="Pilihan A">

    <input type="text" placeholder="Pilihan B">

    <input type="text" placeholder="Pilihan C">

    <input type="text" placeholder="Pilihan D">

    <select>
        <option>Pilih Jawaban Benar</option>
        <option>A</option>
        <option>B</option>
        <option>C</option>
        <option>D</option>
    </select>

    <button class="btn">Simpan Soal</button>

</div>

<?php
require_once 'layout/footer.php';
?>
