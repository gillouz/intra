<?php
session_start();


//$email="";
$username="";
$password="";

//if(isset($_POST['email'])) $email = htmlspecialchars($_POST['email'],ENT_QUOTES,'ISO-8859-1'); 
if(isset($_POST['username'])) $username = htmlspecialchars($_POST['username'],ENT_QUOTES,'ISO-8859-1'); 
if(isset($_POST['password'])) $password = htmlspecialchars($_POST['password'],ENT_QUOTES,'ISO-8859-1'); 

//setcookie("email", $email, time() + (365 * 24 * 60 * 60),"/" );
setcookie("username", $username, time() + (365 * 24 * 60 * 60),"/" );
setcookie("password", $password, time() + (365 * 24 * 60 * 60),"/" );

// set cookie for old portal
setcookie("mag_log", $username, time() + (365 * 24 * 60 * 60),"/portail" );
setcookie("mag_pass", $password, time() + (365 * 24 * 60 * 60),"/portail" );

$head=$_SERVER['HTTP_REFERER'];

header("Location: $head");
exit;



?>
