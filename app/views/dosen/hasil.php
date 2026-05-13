
<?php
require_once 'layout/header.php';
require_once 'layout/sidebar.php';
require_once 'layout/navbar.php';
?>

<h1 class="title">Hasil dan Analisis Nilai</h1>

<div class="card-container">

    <div class="card">
        <h2>98</h2>
        <p>Nilai Tertinggi</p>
    </div>

    <div class="card">
        <h2>65</h2>
        <p>Nilai Terendah</p>
    </div>

    <div class="card">
        <h2>84</h2>
        <p>Rata-rata Nilai</p>
    </div>

    <div class="card">
        <h2>92%</h2>
        <p>Persentase</p>
    </div>

</div>

<div class="table-box">

    <table>

        <tr>
            <th>Nama</th>
            <th>Nilai</th>
            <th>Ranking</th>
        </tr>

        <tr>
            <td>Dimas</td>
            <td>98</td>
            <td>1</td>
        </tr>

        <tr>
            <td>Fathir</td>
            <td>95</td>
            <td>2</td>
        </tr>

    </table>

</div>

<?php
require_once 'layout/footer.php';
?>
