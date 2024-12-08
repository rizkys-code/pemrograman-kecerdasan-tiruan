<!DOCTYPE html>
<html>
<h3><a href="FormLoadFile.php">Kembali Ke Form Input Link</a></h3>
<br>
</html>

<?php

include 'Koneksi.php';
include 'XML2Array.php';

$time_start = microtime(true); 

//sample script
for($i=0; $i<1000; $i++){
 //do anything
}

$link = $_GET['link'];
$xml=simplexml_load_file($link);
if( !$xml) //using simplexml_load_file function to load xml file
{
echo 'load XML failed ! ';
}
else
{
$array = XML2Array($xml);

$a=0;
    
	//save to tabel galert_data
	foreach( $array as $key => $value) {
		$id = $array['id'];
		$title = $array['title']; 
		$link = $array['link']; 
		$update = $array['updated']; 
	
		//select to database
		$sql = "SELECT * FROM galert_data where galert_id='$id'";
		$result = $conn->query($sql);
		
		if ($result->num_rows > 0) {
			echo "";
		}else{		
			//save to database
			$q = "INSERT INTO galert_data(galert_id,galert_title,galert_link,galert_update) 
			VALUES('$id','$title','$link','$update')";
			$result = mysqli_query($conn,$q);

			//save to tabel galert_entry
			foreach( $xml as $record ) 
			{
				$id2 = $record->id;
				$title = $record->title; 
				$link = $record->link; 
				$published = $record->published; 
				$update = $record->update; 
				$content = $record->content; 
				$author = $record->author; 

				//select to database
				$sql = "SELECT * FROM galert_entry where entry_id='$id2'";
				$result = $conn->query($sql);
				
				if ($result->num_rows > 0) {
					echo "";
				}else{	
					//save to database
					$q = "INSERT INTO galert_entry(entry_id,entry_title,entry_link,entry_published,
					entry_updated,entry_content,entry_author,feed_id) 
					VALUES('$id2','$title','$link','$published','$update','$content','$author','$id')";

					$result = mysqli_query($conn,$q);
				}		
			}
		}	
        $sql2 = "SELECT count(galert_title) jml FROM galert_data where galert_id='$id'";
		$result2 = $conn->query($sql2);
		
	}
}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
    body
        {
            font-family:Verdana, Geneva, sans-serif;    
        }
</style>
</head>
<body>
<table cellpadding="1" cellspacing="1" bgcolor="#999999">
    <tr bgcolor="#CCCCCC">
		<th>No</th>
        <th>ID</th>
        <th>Title</th>
        <th>Link</th>
        <th>Publisher</th>
        <th>Update</th>
        <th>Content</th>
        <th>Author</th>
    </tr>
    <?php

	$i=1;
        foreach($xml as $r)
            {
    ?>
    <tr bgcolor="#FFFFFF">
        <td><?php echo $i++;?></td>
        <td><?php echo $r->id;?></td>
        <td><?php echo $r->title;?></td>
        <td><?php echo $r->link;?></td>
        <td><?php echo $r->published;?></td>
        <td><?php echo $r->update;?></td>
        <td><?php echo $r->content;?></td>
        <td><?php echo $r->author;?></td>
    </tr>
    <?php
            }
  ?>
</table><br />
</body>

<?php

if ($result) {
	$d = mysqli_fetch_array($result2);
	$id = $d['jml'];
	echo '<h3>Penyimpanan '. $i-- .' Record Data Berhasil </h3>';
}else {
	echo '<h2>Gagal Melakukan Penyimpanan Data</h2>';
}

$time_end = microtime(true);

//dividing with 60 will give the execution time in minutes otherwise seconds
$execution_time = ($time_end - $time_start)/60;

//execution time of the script
echo '<h3><b>Total Execution Time:</b> '.$execution_time.' Mins';
// if you get weird results, use number_format((float) $execution_time, 10) 
?>
</html>
