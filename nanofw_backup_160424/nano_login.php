<?php
session_start();


$email="";
$password="";
if(isset($_POST['email'])) $email = htmlspecialchars($_POST['email'],ENT_QUOTES,'ISO-8859-1'); 
if(isset($_POST['password'])) $password = htmlspecialchars($_POST['password'],ENT_QUOTES,'ISO-8859-1'); 

setcookie("email", $email, time() + (365 * 24 * 60 * 60),"/" );
setcookie("password", $password, time() + (365 * 24 * 60 * 60),"/" );

$head=$_SERVER['HTTP_REFERER'];

header("Location: $head");
exit;



?>
