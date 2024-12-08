<?php
// 2311501304 Muhammad Rifky Ramadhani 
require_once "Koneksi_2311501304.php";

// Fungsi menghitung probabilitas kata
function hitungProbabilitas_2311501304($kata_2311501304 = "", $kategori_id_2311501304, $conn)
{
    // Query untuk menghitung nilai NK (jumlah data kata dalam kategori tertentu)
    $queryNK_2311501304 = "SELECT SUM(jml_data) AS NK 
                FROM probabilitas_kata_2311501304
                WHERE kata = '$kata_2311501304' AND id_kategori = '$kategori_id_2311501304'";
    $resultNK_2311501304 = $conn->query($queryNK_2311501304);
    $rowNK_2311501304 = $resultNK_2311501304->fetch_assoc();
    $NK_2311501304 = $rowNK_2311501304['NK'] ?? 0;

    // Query untuk menghitung nilai N (jumlah total data dalam kategori tertentu)
    $queryN_2311501304 = "SELECT SUM(jml_data) AS N 
               FROM probabilitas_kata_2311501304
               WHERE id_kategori = '$kategori_id_2311501304'";
    $resultN_2311501304 = $conn->query($queryN_2311501304);
    $rowN = $resultN_2311501304->fetch_assoc();
    $N_2311501304 = $rowN['N'] ?? 0;

    // Query untuk menghitung jumlah kosakata (distinct kata)
    $queryKosakata_2311501304 = "SELECT COUNT(DISTINCT kata) AS KOSAKATA 
                      FROM probabilitas_kata_2311501304";
    $resultKosakata_2311501304 = $conn->query($queryKosakata_2311501304);
    $rowKosakata_2311501304 = $resultKosakata_2311501304->fetch_assoc();
    $kosakata_2311501304 = $rowKosakata_2311501304['KOSAKATA'] ?? 0;

    // Perhitungan probabilitas
    $probabilitas_2311501304 = ($NK_2311501304 + 1) / ($N_2311501304 + $kosakata_2311501304);
    return [
        "jumlah_data_kata_dalam_kategori_tertentu" => $NK_2311501304,
        "jumlah_total_data_dalam_kategori_tertentu" => $N_2311501304,
        "jumlah_kata_pada_dokument_test" => $kosakata_2311501304,
        "probabilitas" => $probabilitas_2311501304
    ];
}

$allData_2311501304 = [];
$q_2311501304 = "SELECT * FROM probabilitas_kata_2311501304";
$result_2311501304 = $conn->query($q_2311501304);

while ($data_2311501304 = mysqli_fetch_assoc($result_2311501304)) {
    $kategori_id_2311501304 = $data_2311501304['id_kategori'];
    $kata_2311501304 = $data_2311501304['kata'];
    $data_probabilitas_2311501304 = hitungProbabilitas_2311501304($kata_2311501304, $kategori_id_2311501304, $conn);

    // Cek apakah nilai_probabilitas sudah ada supaya tidak update terus menerus
    $queryCekProbabilitas_2311501304 = "SELECT nilai_probabilitas FROM probabilitas_kata_2311501304 WHERE id_kategori = '$kategori_id_2311501304' AND kata = '$kata_2311501304'";
    $resultCek_2311501304 = $conn->query($queryCekProbabilitas_2311501304);
    $cek_probabilitas_2311501304 = $resultCek_2311501304->fetch_assoc()['nilai_probabilitas'];


    if ($cek_probabilitas_2311501304 == 0) {
        $probabilitas_2311501304 = $data_probabilitas_2311501304['probabilitas'];

        $sql_update_probabilitas_kata_2311501304 = "UPDATE probabilitas_kata_2311501304
                                        SET nilai_probabilitas = $probabilitas_2311501304
                                        WHERE id_kategori = '$kategori_id_2311501304' AND kata = '$kata_2311501304'";
        mysqli_query($conn, $sql_update_probabilitas_kata_2311501304);
        // echo "update nich";
    } else {
        $probabilitas_2311501304 = $cek_probabilitas_2311501304;
        // echo "aman";
    }

    // Ambil nama kategori
    $q_kategori_2311501304 = "SELECT nm_kategori FROM kategori_2311501304 WHERE id_kategori = $kategori_id_2311501304";
    $result_kategori_2311501304 = $conn->query($q_kategori_2311501304);
    $nama_kategori_2311501304 = mysqli_fetch_assoc($result_kategori_2311501304)['nm_kategori'];

    // Simpan data untuk ditampilkan di tabel
    $allData_2311501304[] = [
        "kata" => $data_2311501304['kata'],
        "kategori" => $nama_kategori_2311501304,
        "frekuensi_kata_per_kategori" => $data_probabilitas_2311501304["jumlah_data_kata_dalam_kategori_tertentu"],
        "total_kata_per_kategori" => $data_probabilitas_2311501304['jumlah_total_data_dalam_kategori_tertentu'],
        "total_kata_per_dokumen" => $data_probabilitas_2311501304['jumlah_kata_pada_dokument_test'],
        "probalitas" => $probabilitas_2311501304
    ];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perhitungan Probabilitas Kata</title>
</head>

<body>

    <head>
        <title>Tahap Perhitungan Probabilitas Kata</title>
        <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
        <script type="text/javascript" src="bootstrap/js/jquery-js"></script>
        <script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
    </head>

    <body>
        <br>
        <button onclick="goBack()">Kembali Ke Form Input Link</button>
        <script>
            function goBack() {
                window.history.back();
            }
        </script>
        <br><br><br>

        <h3>Daftar Kategori</h3>
        <table class="table table-bordered">
            <thead class="table-secondary">
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Kategori</th>
                    <th scope="col">Jumlah Kata Pada Dokumen Test Per Kategori</th>
                </tr>
            </thead>
            <tbody>
                <?php $i_2311501304 = 1;
                $total_2311501304 = 0;
                $q_kategori_2311501304 = "SELECT * FROM kategori_2311501304";
                $result_kategori_2311501304 = $conn->query($q_kategori_2311501304);
                ?>
                <?php while ($data_2311501304 = mysqli_fetch_assoc($result_kategori_2311501304)) : ?>
                    <tr bgcolor="#FFFFFF">
                        <td><?= $i_2311501304 ?></td>
                        <td><?= $data_2311501304['nm_kategori'] ?></td>
                        <td>
                            <?php
                            $total_per_kategori_2311501304 = hitungProbabilitas_2311501304("", $data_2311501304['id_kategori'], $conn)['jumlah_kata_pada_dokument_test'];
                            $total_2311501304 += $total_per_kategori_2311501304;
                            echo $total_per_kategori_2311501304;
                            ?>
                        </td>
                    </tr>
                    <?php $i_2311501304++ ?>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr class="table-info">
                    <td colspan="2" class="text-end"><strong>Total Jumlah Kata Per Kategori</strong></td>
                    <td><strong><?= $total_2311501304 ?></strong></td>
                </tr>
            </tfoot>
        </table>
        <br><br><br>

        <div class="alert alert-success" role="alert">
            Probabilitas Data Kata Berhasil
        </div>
        <h3>Daftar Probabilitas Kata</h3>
        <table class="table table-bordered table-hover">
            <thead class="table-secondary">
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Kata</th>
                    <th scope="col">Kategori</th>
                    <th scope="col">Frekuensi Kata Per Kategori (NK)</th>
                    <th scope="col">Jumlah Kata Per Kategori (N)</th>
                    <th scope="col">Jumlah Kata Pada Dokumen Test (KOSAKATA)</th>
                    <th scope="col">Probalitas Kata</th>
                </tr>
            </thead>
            <tbody>
                <?php $i_2311501304 = 1; ?>
                <?php foreach ($allData_2311501304 as $item_2311501304) : ?>
                    <tr bgcolor="#FFFFFF">
                        <td><?= $i_2311501304 ?></td>
                        <td><?= $item_2311501304['kata'] ?></td>
                        <td><?= $item_2311501304['kategori'] ?></td>
                        <td><?= $item_2311501304['frekuensi_kata_per_kategori'] ?></td>
                        <td><?= $item_2311501304['total_kata_per_kategori'] ?></td>
                        <td><?= $item_2311501304['total_kata_per_dokumen'] ?></td>
                        <td><?= $item_2311501304['probalitas'] ?></td>
                    </tr>
                    <?php $i_2311501304++ ?>
                <?php endforeach; ?>
            </tbody>
        </table>

    </body>

</html>