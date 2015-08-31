<?php
if(isset($_POST['submit'])) 
//if submit has been pressed
{

 exec("NOW=$(date +\"%m-%d-%Y\");sudo /usr/bin/mysqldump -h localhost -u root -pmatias.31446757 phppostd > /compartido/$NOW.sql");
//NOTE change 'shutrouter' for the name of the script you are executing
}
?>
