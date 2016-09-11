<?php //idx//Devis//
session_start();
include("lib_1.php");
validate("");
include("devis-lib_main.php");

// Header
echo header_display();

// Navbar
echo navbar(0);

echo nano_editor("activites");

// footer
echo footer_display();


?>
