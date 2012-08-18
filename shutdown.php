<?php
if(isset($_POST['submit'])) 
//if submit has been pressed
{
 exec("sudo /sbin/poweroff");
//NOTE change 'shutrouter' for the name of the script you are executing
}
?>
