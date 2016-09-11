<?php 
session_start();
include("nanofw/nano.php");
nano\init("page");

$nr_client=0;
if(isset($_GET['nr_client'])) $nr_client = htmlspecialchars($_GET['nr_client'],ENT_QUOTES,'ISO-8859-1'); 

// Header
echo nano\header();

// Navbar
echo nano\navbar(0);

// open Bootstrap container
echo "<div class='container-fluid'>";






// Close container
echo "</div>";

// Footer
echo nano\footer();



?>
