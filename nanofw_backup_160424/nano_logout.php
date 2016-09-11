<?php
session_start();


setcookie("email", "", time() + (365 * 24 * 60 * 60),"/" );
setcookie("password", "", time() + (365 * 24 * 60 * 60),"/" );

$head=$_SERVER['HTTP_REFERER'];

header("Location: $head");
exit;



?>
