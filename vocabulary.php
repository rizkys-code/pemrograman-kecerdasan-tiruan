<!DOCTYPE html>
<html>
<head>
	<title>Tahap Klasifikasi Data Dengan Algoritma Naive Bayes</title>
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
require_once "Koneksi.php";

$sql = "delete FROM Probabilitas_Kata";
$result0 = mysqli_query($conn,$sql);

$sql = "SELECT * FROM preprocessing where id_kategori is not null order by entry_id LIMIT 1";
$result1 = $conn->query($sql);
if ($result1->num_rows == 0) {
    echo "Data Tidak Ditemukan";
}else{	
			while($d = mysqli_fetch_array($result1)){
				$data = $d['data_bersih'];
				$id_kategori = $d['id_kategori'];

				$data_array=explode(" ",$data);
				$str_data=array();
				foreach($data_array as $value){
					$str_data[] = "".$value; 
					$kata=$value;               

					$sql = "SELECT * FROM kategori";
					$result2 = $conn->query($sql);
			    
					if ($result2 != false && $result2->num_rows == 0) {
						echo "Data Tidak Ditemukan";
					}else{	
						while($d = mysqli_fetch_array($result2)) {				
							$id = $d[0];
							$nm = $d[1];
							
							$sql = "SELECT * FROM Probabilitas_Kata where kata='$kata' and id_kategori='$id'";
							$result3 = $conn->query($sql);

							if ($result3 != false && $result3->num_rows == 0) {
								$q = "INSERT INTO Probabilitas_Kata(kata,id_kategori) 
										VALUES('$kata','$id')";
								
								$result3 = mysqli_query($conn,$q);
							}		
					}
				$q = "UPDATE Probabilitas_kata set jml_data=nvl (jml_data,0)+1 where kata='$kata' and id_kategori='id_kategori'";
				$result4 = mysqli_query($conn,$q);
				}        
			    
			}
		}         
}

$sql = "SELECT kata,nm_kategori FROM Probabilitas_Kata a,kategori b
where a.id_kategori=b.id_kategori";

$result4 = $conn->query($sql);
?>

<table class="table table-bordered table-striped table-hover">
<thead> 
	<br><br>   
	<tr></tr>   
<tr>
  <td colspan="5"><strong>Vocabulary Pada Setiap Dokumen Data Training</strong></td>
</tr>
<tr bgcolor="#CCCCCC">
<th>No.</th>
<th>Kata</th>
<th>Kategori</th>
</tr>
</thead>
    <?php

		$i=1;
        while($d = mysqli_fetch_array($result4))
        {
		?>
			<tr bgcolor="#FFFFFF">
				<td><?php echo $i; ?></td>
				<td><?php echo $d[0]; ?></td>
				<td><?php echo $d[1]; ?></td>
			</tr>
    <?php
    $i=$i+1;
        }
  ?>
</table>