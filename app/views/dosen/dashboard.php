
<?php
require_once 'layout/header.php';
require_once 'layout/sidebar.php';
require_once 'layout/navbar.php';
?>

<h1 class="title">Dashboard Dosen</h1>

<div class="card-container">

    <div class="card">
        <h2>12</h2>
        <p>Total Kuis</p>
    </div>

    <div class="card">
        <h2>120</h2>
        <p>Total Soal</p>
    </div>

    <div class="card">
        <h2>80</h2>
        <p>Total Mahasiswa</p>
    </div>

    <div class="card">
        <h2>6</h2>
        <p>Total Kelas</p>
    </div>

</div>

<div class="table-box">

    <h2>Aktivitas Terbaru</h2>

    <table>

        <tr>
            <th>Aktivitas</th>
            <th>Waktu</th>
        </tr>

        <tr>
            <td>Membuat kuis HTML</td>
            <td>10 menit lalu</td>
        </tr>

        <tr>
            <td>Menambahkan soal CSS</td>
            <td>30 menit lalu</td>
        </tr>

    </table>

</div>

<?php
require_once 'layout/footer.php';
?>
