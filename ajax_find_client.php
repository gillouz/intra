<?php //idx//Devis//
session_start();
include("nanofw/nano.php");
nano\init("webservice");

$find="";

if(isset($_POST['find'])) $find = htmlspecialchars($_POST['find'],ENT_QUOTES,'ISO-8859-1'); 
if(isset($_GET['find'])) $find = htmlspecialchars($_GET['find'],ENT_QUOTES,'ISO-8859-1');  

$return=client_find($find);

echo JSON_encode($return);

  


?>                