<?php session_start(); 
#### Roshan's very simple code to export data to excel   
#### Copyright reserved to Roshan Bhattarai - nepaliboy007@yahoo.com
#### if you have any problem contact me at http://roshanbh.com.np
#### fell free to visit my blog http://php-ajax-guru.blogspot.com

	//first of all unset these variables

	unset($_SESSION['report_header']);
	unset($_SESSION['report_values']);
	//note that the header contain the three columns and its a array
	$_SESSION['report_header']=array("CodigodeBarra","Artista-Disco","Cia", "Soporte", "Costo"); 
   // now the excel data field should be two dimentational array with three column
$count = 0;
foreach($items as $item)
{
        $barcode = $item['item_number'];
        $text = $item['name'];
	$compania = $item['description'];
	$soporte = $item['category'];
	$costo = $item['cost_price'];
   		echo $_SESSION['report_values'][$count][0]="$barcode"." ";
		echo $_SESSION['report_values'][$count][1]="$text"." ";
		echo $_SESSION['report_values'][$count][2]="$compania"." ";
		echo $_SESSION['report_values'][$count][3]="$soporte"." ";
		echo $_SESSION['report_values'][$count][4]="$costo"." ";
		echo "<br>";
        $count++;
}

  

  ?>
  


<a href="/exportar.php?fn=pedido">GenerarExcel</a>

<!--the export_report.php takes one variable called fn as GET parameter which is name of the file to be generated, if you pass member_report as a value, then the name of the generated file would be member_report.php

 -->



