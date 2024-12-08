<!DOCTYPE html> 
<html>
<body>
    <link rel="stylesheet" type="text/css" href="stylee.css">
    <table cellpadding="10" cellspacing="3" align="center">
    <tr>
	    <td>
        <form action="LoadFile.php" method="get">	
            <h1 style="font-family: arial">Rizky Saputra | 2311500173 </h1>
            <br>	
            <h1 style="font-family: arial">Tahap Pengumpulan Data</h1>
            <h2 style="font-family: arial">Input Link </h2>
            <input type="text" size="80" name="link"/>            
            <br><br>
            <input type="submit" value="Simpan Data">
            <input type="reset" size="100"/>
        </form>
        </td>
 	    <td>
        <form action="vocabulary.php" method="get">		
            <h1 style="font-family: arial">Tahap Klasifikasi</h1>
            <h2 style="font-family: arial">Learning (Pembelajaran)</h2>
            <h3 style="font-family: arial">Bentuk Vocabulary </h3>
            <input type="submit" value="Proses">
            <br>
        </form>
        <form action="Rizky_2311500173.php" method="get">
            <h1 style="font-family: arial">Hitung Probabilitas Kategori</h1>
            <h2 style="font-family: arial">Probabilitas Kategori</h2>
            <input type="submit" value="Proses">
        </form>
        </td>   
        </tr>

    <tr>
    <td>
        <form action="Preprocessing.php" method="get">		
            <h1 style="font-family: arial">Tahap PreProcessing Data</h1>
            <input type="submit" value="Proses Pembersihan Data">
	    </form>
    </td>
    </tr>

    <tr>
    <td>
        <form action="Labelisasi.php" method="get">		
            <h1 style="font-family: arial">Tahap Labelisasi</h1>
            <input type="submit" value="Proses Labelisasi">
	    </form>                        
    </td>
    </tr>


</body>
</html>
