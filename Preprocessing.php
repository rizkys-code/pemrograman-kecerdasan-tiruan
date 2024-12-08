<!DOCTYPE html>
<html>
<h3><a href="FormLoadFile.php">Kembali Ke Form Awal</a></h3>
<br>
</html>

<?php
include 'Koneksi.php';
include "stopword.php";

$time_start = microtime(true); 

//sample script
for($i=0; $i<1000; $i++){
 //do anything
}

require_once __DIR__ . '/sastrawi/vendor/autoload.php';

$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
$stemmer  = $stemmerFactory->createStemmer();

echo "<br>";

$sql = "SELECT kata_tbaku,concat(kata_baku,' ') kata_baku FROM slangword";
$stmt = $conn->prepare($sql);
$stmt->execute();
$resultSet = $stmt->get_result();
$result = $resultSet->fetch_all();
//$arr_slang=array();


foreach($result as $k=>$v) {
	$arr_slang[$v[0]]=$v[1];	
}

$sql = "SELECT * FROM galert_entry where length(entry_id)!=0";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    echo "Data Tidak Ditemukan";
}else{	
    ?>	
    <table border="1" cellpadding="1" cellspacing="1" bgcolor="#999999">
    <tr bgcolor="#CCCCCC">
    <th>ID</th>
    <th>Content</th>
    <th>Case Folding</th>
    <th>Hapus Simbol</th>
    <th>Filter Slang Word</th>
    <th>Filter Stop Word</th>
    <th>Stimming</th>
    <th>Tokenisasi</th>
    </tr>
    <?php 
    while($d = mysqli_fetch_array($result)){
        $id = $d['entry_id'];
		$content = $d['entry_content'];

        //1 Case Folding
            //echo strtoupper($content);
            //echo strtolower($content);
            $cf = strtolower($content);


        //2 Penghapusan Simbol-Simbol (Symbol Removal)
            $simbol = preg_replace("/[^a-zA-Z\\s]/", "", $cf);

            //Penghapusan tag html
			$tags = preg_replace("/<.*?>/", " ", $simbol);
			
			//Penghapusan URL
			$regex = "@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@";
			$url=preg_replace($regex, ' ', $tags);
	
			//Penghapusan angka dan tanda baca
			$simbol = preg_replace('/[^a-z\9\6 ]+/i', ' ', $url);

            $simbol =str_replace("nbsp",' ',$simbol); //changes &nbsp to space
            

        //3 Konversi Slangword
			$rem_slang=explode(" ",$simbol);
			$slangword=str_replace(array_keys($arr_slang), $arr_slang, $simbol);
			
			
//str_replace adalah fungsi php yang digunakan untuk mengubah isi teks, karakter atau string.
//X adalah kata atau karakter yang akan diganti.
//Y adalah kata atau karakter penggantinya.
//string adalah sumber string yang akan diproses.

		
//echo "<pre>";
//print_r(array_keys($arr_slang));
//print_r($arr_slang);
//print_r($simbol);


        //4 Stopword Removal
			$rem_stopword=explode(" ",$slangword);
			$str_data=array();
			$i=1;
			foreach($rem_stopword as $value){
				if(!in_array($value, $stopwords)){
					$str_data[] = "".$value;
					//echo "<pre>";
					//print_r($str_data);   
					//print_r($i);   
					             
				}            
					$i=$i+1;
			}        
//exit;
			$stopword = implode(" ", $str_data);
			
			//in_array(search, array, type)
			//Search : merupakan sebuah keyword atau kata kunci yang akan kita cari dan nilai tersebut bernilai wajib
			//Array : merupakan nilai yang akan kita bandingkan dengan nilai yang kita cari, yaitu nilai search. dan nilai tersebut bernilai wajib
			//Type : merupakan fungsi pencarian untuk string yang di cari dan jenis tertentu dalam array dan nilai tersebut bernilai pilihan, boleh kita gunakan ataupun tidak.

		//5 Stemming
			$query1 = implode(' ', (array)$stopword);
			$query2 = $stopword;
            //$stemming   = $stemmer->stem($query1);
            
			//print_r($stemming);
			//echo "<br>";
            $stemming   = $stemmer->stem($query2);
			//print_r($stemming);
			//exit;

        //6 Tokenisasi
            //$tokenisasi = preg_split("/[\s,.:]+/", $stemming);
            $tokenisasi = explode(" ", $stemming);
            $tokenisasi=implode(", ",$tokenisasi);
   ?>
   
        <tr bgcolor="#FFFFFF">
            <td><?php echo $id; ?></td>
            <td><?php echo $content; ?></td>
            <td><?php echo $cf; ?></td>
            <td><?php echo $simbol; ?></td>
            <td><?php echo $slangword; ?></td>
            <td><?php echo $stopword; ?></td>
            <td><?php echo $stemming; ?></td>
            <td><?php echo $tokenisasi; ?></td>
        </tr>
 
        <?php 
    
		$sql = "SELECT * FROM preprocessing where entry_id='$id'";
		$result1 = $conn->query($sql);

		if ($result1->num_rows == 0) {
			//save to database
			$q = "INSERT INTO preprocessing(entry_id,p_cf,p_simbol,p_tokenisasi,p_sword,p_stopword,p_stemming,data_bersih) 
			        VALUES('$id','$cf','$simbol','$tokenisasi','$slangword','$stopword','$stemming','$stemming')";
			
			$result1 = mysqli_query($conn,$q);
		}else {

                        $q = "UPDATE preprocessing set p_cf='$cf', p_simbol='$simbol', p_tokenisasi='$tokenisasi', p_sword='$slangword', 
                                    p_stopword='$stopword', p_stemming='$stemming', data_bersih='$stemming'
			                        where entry_id='$id'";
			
			$result1 = mysqli_query($conn,$q);
		}	
        
        $sql2 = "SELECT count(*) jml FROM galert_entry where length(entry_id)!=0";
		$result2 = $conn->query($sql2);

    }
?>
    </table>
    
<?php
	if ($result1){
        $d = mysqli_fetch_array($result2);
        $id = $d['jml'];
		echo '<h1>Preprocessing '. $id .' Record Data Berhasil</h1>';
	}else{
		echo '<h2>Gagal Melakukan Preprocessing Data</h2>';
	}
}


$time_end = microtime(true);

//dividing with 60 will give the execution time in minutes otherwise seconds
$execution_time = ($time_end - $time_start)/60;

//execution time of the script
echo '<h1><b>Total Execution Time:</b> '.$execution_time.' Mins';
// if you get weird results, use number_format((float) $execution_time, 10) 

?>
