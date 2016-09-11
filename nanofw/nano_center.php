<?php
session_start();
include(__DIR__."/nano.php");
nano\init("page");

$center="";

if(isset($_GET['center'])) $center = htmlspecialchars($_GET['center'],ENT_QUOTES,'ISO-8859-1'); 

setcookie("center", $center, time() + (365 * 24 * 60 * 60),"/" );

$head=$_SERVER['HTTP_REFERER'];

header("Location: $head");
exit;



?>
