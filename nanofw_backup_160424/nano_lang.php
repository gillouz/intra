<?php
session_start();
include(__DIR__."/nano.php");
nano\init("page");

$lang="fr";

if(isset($_GET['lang'])) $lang = htmlspecialchars($_GET['lang'],ENT_QUOTES,'ISO-8859-1'); 

setcookie("lang", $lang, time() + (365 * 24 * 60 * 60),"/" );

$head=$_SERVER['HTTP_REFERER'];

header("Location: $head");
exit;



?>
