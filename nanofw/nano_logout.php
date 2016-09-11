<?php
session_start();

//unset nano 
setcookie("email", "", time() + (365 * 24 * 60 * 60),"/" );
setcookie("password", "", time() + (365 * 24 * 60 * 60),"/" );

// unset old portal 
setcookie("mag_log", "", time() + (365 * 24 * 60 * 60),"/portail" );
setcookie("mag_pass", "", time() + (365 * 24 * 60 * 60),"/portail" );

session_unset ();

$head=$_SERVER['HTTP_REFERER'];

header("Location: $head");
exit;



?>
