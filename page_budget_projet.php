<?php 
session_start();
include("nanofw/nano.php");
nano\init("page");

// Header
echo nano\header();

// Navbar
echo nano\navbar(0);

// open Bootstrap container
echo "<div class='container'>";

echo nano\quickList("projet",[]);

// Close container
echo "</div>";

// Footer
echo nano\footer();



?>
