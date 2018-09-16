<?php 
//Koneksi database MySQL
include "dist/koneksi.php";
$tampilBarang	= mysql_query("SELECT * FROM tb_pegawai");

$arr = array();
while ($row = mysql_fetch_assoc($tampilBarang)) {
    $temp = array(
    "name" => $row["nama"],
    "isparent" => false);
   
    array_push($arr, $temp);
}
 
$data = json_encode($arr);
 
// echo "{
	   // \"name\":\"Airlinese\",
       // \"Parents\": [
        // {
            // \"name\": \"Aviation\",
            // \"isparent\": true
        // }
    // ],	   	   
	   // " . $data . "}";
	   

	   
$fp = fopen('flare.json', 'w');
fwrite($fp, "{
	   \"name\":\"Airlinese\",
       \"Parents\": [
        {
            \"name\": \"Aviation\",
            \"isparent\": true
        }
    ], \"children\":	   	   
" . json_encode($arr)."}");
fclose($fp);	   
	   
?>