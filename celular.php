<?php
include("mysql.php");
if (! defined(STDIN)) {
        define("STDIN", fopen("php://stdin", "r"));
}

$scanner=$_GET['cbar'];
        $query="select * from phppostd.phppos_items  WHERE  item_number='$scanner'";
        $q=mysql_query($query);
        if( mysql_num_rows($q) != 0 ){
                while($rowx=mysql_fetch_array($q)){
                        print "<H1>$ $rowx[unit_price] </H1><BR>";
                        print "$rowx[name]";
			
			#Agregado para agregar stock
                        $itemid= "$rowx[item_id]";
                        $elid = $rowx[item_id] ;
                        $stock = $rowx[quantity] ;


                }
			#Agregado paraagregar stock
			$conteo = $stock+1;
			mysql_query("UPDATE `phppostd`.`phppos_items` SET `deleted`='0', `quantity`='$conteo'  WHERE `item_number`='$scanner'");
			//Busco maximo id de la tabla inventario
	                $rs = mysql_query("SELECT max(trans_id) FROM `phppostd`.`phppos_inventory`");
        	        $maxid = mysql_result($rs,0);
                	$maxid2= $maxid+1 ;
	                // Genero Fecha MYSQL Compatible
        	        $mysqldate = date("Y-m-d H:i:s");
	                mysql_query("INSERT INTO `phppostd`.`phppos_inventory` (`trans_id`, `trans_items`, `trans_user`, `trans_date`, `trans_comment`, `trans_inventory`) VALUES ($maxid2, $itemid, 1, '$mysqldate', 'CELULAR:activa', 1)");

        }else{
                echo "ERROR: no se encontro el disco $username \n";
        }

//Desconectamos el Mysql
mysql_close();




?>

