
<?php
require_once 'layout/header.php';
require_once 'layout/sidebar.php';
require_once 'layout/navbar.php';
?>

<div class="page-header">
    <h1>Kelola Soal</h1>

    <a href="?page=tambah-soal" class="btn">+ Tambah Soal</a>
</div>

<div class="table-box">

    <table>

        <tr>
            <th>Pertanyaan</th>
            <th>Jawaban Benar</th>
            <th>Aksi</th>
        </tr>

        <tr>
            <td>Apa fungsi HTML?</td>
            <td>B</td>
            <td>
                <button>Edit</button>
                <button>Hapus</button>
            </td>
        </tr>

        <tr>
            <td>Apa fungsi CSS?</td>
            <td>A</td>
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
