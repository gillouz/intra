<?php 
session_start();
include("nanofw/nano.php");
nano\init("page");

// name your schema
$schema="client";

// Header
echo nano\header();

// Navbar
echo nano\navbar(0);

// open Bootstrap container
echo "<div class='container-fluid'>";

echo "<h1>".nano\lbl("admin")."</h1>";

// Display the nano standard editor
echo nano\quickList("_users");

// Display the nano standard editor
echo nano\quickList("_group");

// Display the nano standard editor
echo nano\quickList("_center");


// Close container
echo "</div>";

// Footer
echo nano\footer();


?>
