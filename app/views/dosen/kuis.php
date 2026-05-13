
<?php
require_once 'layout/header.php';
require_once 'layout/sidebar.php';
require_once 'layout/navbar.php';
?>

<div class="page-header">
    <h1>Kelola Kuis</h1>

    <a href="?page=tambah-kuis" class="btn">+ Tambah Kuis</a>
</div>

<div class="table-box">

    <table>

        <tr>
            <th>Judul</th>
            <th>Kelas</th>
            <th>Durasi</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>

        <tr>
            <td>Kuis HTML Dasar</td>
            <td>SI-2A</td>
            <td>30 Menit</td>
            <td><span class="badge">Aktif</span></td>
            <td>
                <button>Edit</button>
                <button>Hapus</button>
            </td>
        </tr>

        <tr>
            <td>Kuis CSS</td>
            <td>SI-2B</td>
            <td>45 Menit</td>
            <td><span class="badge">Aktif</span></td>
            <td>
                <button>Edit</button>
                <button>Hapus</button>
            </td>
        </tr>

    </table>

</div>

<?php
require_once 'layout/footer.php';
?>
