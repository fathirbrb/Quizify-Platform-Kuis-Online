
<?php
require_once 'layout/header.php';
require_once 'layout/sidebar.php';
require_once 'layout/navbar.php';
?>

<h1 class="title">Monitoring Mahasiswa</h1>

<div class="table-box">

    <table>

        <tr>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Status</th>
            <th>Nilai</th>
        </tr>

        <tr>
            <td>Dimas</td>
            <td>SI-2A</td>
            <td><span class="badge">Selesai</span></td>
            <td>90</td>
        </tr>

        <tr>
            <td>Fathir</td>
            <td>SI-2A</td>
            <td><span class="badge pending">Belum</span></td>
            <td>-</td>
        </tr>

    </table>

</div>

<?php
require_once 'layout/footer.php';
?>
