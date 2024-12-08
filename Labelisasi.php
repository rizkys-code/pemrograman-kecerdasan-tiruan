<!DOCTYPE html>
<html>
<head>
	<title>Tahap Labelisasi Data</title>
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
	<script type="text/javascript" src="bootstrap/js/jquery.js"></script>
	<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
</head>
<body>
<table class="table table-bordered table-striped">
      <td colspan="3">
      <button onclick="goBack()">Kembali Ke Form Input Link</button>
        <script>
            function goBack() {
                window.history.back();
            }
        </script>
        </td>
</table>    
<br>

<?php
include 'Koneksi.php';

	$sql = "SELECT * FROM kategori";
	$result1 = $conn->query($sql);

	if ($result1->num_rows == 0) {
	$sql = "insert into kategori (nm_kategori) select substr(galert_title,16) from galert_data";	
	$result1 = mysqli_query($conn,$sql);
	}

	$sql = "update galert_data g,kategori k,galert_entry e,preprocessing p
	set p.id_kategori=k.id_kategori
	WHERE feed_id = galert_id
	and substr(galert_title,16)=nm_kategori
	and p.entry_id=e.entry_id";	
	$result = mysqli_query($conn,$sql);

    $i=1;
    $sql = "SELECT * FROM galert_data, kategori where substr(galert_title,16)=nm_kategori";
    $result = $conn->query($sql);
    if ($result->num_rows == 0) {
        echo "Data Tidak Ditemukan";
    }else{	
        ?>	
        <table class="table table-bordered table-striped table-hover">
        <thead>    
        <tr>
          <td colspan="3"><strong>Daftar Labelisasi Data</strong></td>
        </tr>
        <tr bgcolor="#CCCCCC">
        <th>No.</th>
        <th>Title</th>
        <th>Nama Kategori</th>
        </tr>
        </thead>
        <?php 
        while($d = mysqli_fetch_array($result)){
            $title = $d['galert_title'];
            $kategori = $d['nm_kategori'];
        ?>       
            <tr bgcolor="#FFFFFF">
                <td><?php echo $i; ?></td>
                <td><?php echo $title; ?></td>
                <td><?php echo $kategori; ?></td>
            </tr>
     
        <?php 
        $i=$i+1;            
        }
}
?>

<br>
<br>
<?php
    $i=1;
    $sql = "SELECT * FROM preprocessing where length(entry_id)!=0";
    $result = $conn->query($sql);
    if ($result->num_rows == 0) {
        echo "Data Tidak Ditemukan";
    }else{	
        ?>	
        <table class="table table-bordered table-striped table-hover">
        <thead>    
        <tr>
          <td colspan="3"><strong>Daftar Labelisasi Data</strong></td>
        </tr>
        <tr bgcolor="#CCCCCC">
        <th>No.</th>
        <th>Data Bersih</th>
        <th>Nama Kategori</th>
        </tr>
        </thead>
        <?php 
        while($d = mysqli_fetch_array($result)){
            $data_bersih = $d['data_bersih'];
            $id_kategori = $d['id_kategori'];

            $sql = "SELECT nm_kategori FROM kategori where id_kategori='$id_kategori'";
            $result2 = mysqli_query($conn, "SELECT * FROM kategori WHERE 1");

if ($result2 && mysqli_num_rows($result2) > 0) {
    $d = mysqli_fetch_row($result2);
    $nm_kategori = $d[0];
} else {
    echo "Tidak ada data ditemukan atau query gagal.";
}


            
    
        ?>       
            <tr bgcolor="#FFFFFF">
                <td><?php echo $i; ?></td>
                <td><?php echo $data_bersih; ?></td>
                <td><?php echo $nm_kategori; ?></td>
            </tr>
     
        <?php 
        $i=$i+1;            
        }
}
?>
<br>
<br>
</body>
</html>
