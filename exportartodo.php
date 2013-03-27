<?php
if(isset($_POST['submit'])) 
{
exec('sudo mysqldump -uroot -pmatias.31446757 --fields-terminated-by=, --tab=/tmp/ phppostd phppos_items');
}
?>
