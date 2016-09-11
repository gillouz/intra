<?php
session_start();
include(__DIR__."/nano.php");
nano\init("webservice");

header('Content-type: text/html; charset=utf-8'); 

//print_r($schemas->devcon_lines);

echo "<form action='nano_load.php' method='post'>
schema:<br><input name='schema' value='devcon_lines'><br>
query:<br><textarea name='query' style='width:100%;height:80%'></textarea>
<input type='submit' name='submit'>
</form>";


?>
