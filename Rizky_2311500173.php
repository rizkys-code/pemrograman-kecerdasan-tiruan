<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tahap Klasifikasi Data Dengan Algoritma Naive Bayes</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <script src="bootstrap/js/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.js"></script>
</head>
<body>
    <table class="table table-bordered table-striped">
        <tr>
            <td colspan="3">
                <button onclick="goBack()">Kembali Ke Form Input Link</button>
                <script>
                function goBack() {
                    window.history.back();
                }
                </script>
            </td>
        </tr>
    </table>
    <br>

    <?php
    require_once "Koneksi.php";

    $sql = "delete FROM Probabilitas_kategori";
$result0 = mysqli_query($conn, $sql);

$sql = "SELECT * FROM kategori order by id_kategori";
$resultl = $conn->query($sql);
if ($resultl->num_rows == 0) {
    echo "Data Tidak Ditemukan";
} else {
    while ($d = mysqli_fetch_array($resultl)) {
        $id = $d['id_kategori'];
        $sql = "SELECT count(*) as jml FROM preprocessing where id_kategori='$id'";
        $result2 = $conn->query($sql);
        $d = mysqli_fetch_row($result2);
        $jmlkategori = $d[0];

        $sql = "SELECT count(*) jml FROM preprocessing";
        $result3 = $conn->query($sql);
        $d = mysqli_fetch_row($result3);
        $jmldokumen = $d[0];

        $nilai = $jmlkategori / $jmldokumen;

        $q = "INSERT INTO Probabilitas_Kategori (id_kategori,jml_data, nilai)
                 VALUES('$id', '$jmlkategori', '$nilai')";
        $result4 = mysqli_query($conn, $q);
        
    }
}
$sql = "SELECT nm_kategori , jml_data ,nilai FROM probabilitas_kategori a,kategori b where a.id_kategori=b.id_kategori order by 1";
$result4 = $conn->query($sql);      
?>
<table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <td colspan="5"><strong>Nilai Probabilitas Pada Setiap Kategori</strong></td>
            </tr>
            <tr bgcolor="#CCCCCC">
                <th>No.</th>
                <th>Kategori</th>
                <th>Frekuensi Dokumen Per Kategori</th>
                <th>Jumlah Seluruh Dokumen</th>
                <th>Probabilitas</th>
            </tr>
        </thead>
        <tbody>
<?php
$i=1;
while ($d = mysqli_fetch_array($result4)) {
?>
 <tr bgcolor="#FFFFFF">
            <td><?php echo $i; ?></td>
            <td><?php echo $d['nm_kategori']; ?></td>
            <td><?php echo $d['jml_data']; ?></td>
            <td><?php echo $jmldokumen; ?></td>
            <td><?php echo number_format($d['nilai'], 4); ?></td>
        </tr>
<?php
   $i++;
}
?>
 </tbody>
    </table>
</body>
</html> 