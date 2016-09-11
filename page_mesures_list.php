<?php 
session_start();
include("nanofw/nano.php");
nano\init("page");

// name your schema
$schema="mesures";

// Header
echo nano\header();

// Navbar
echo nano\navbar(0);

// open Bootstrap container
echo "<div class='container'>";

echo nano\quickList($schema,[]);


echo "<script>

// Clients
intra.client.id=document.mesures_edit_form.nr_client;
intra.client.detail=document.mesures_edit_form.client_detail;

// articles
intra.article.id=document.mesures_edit_form.article_code;
intra.article.detail=document.mesures_edit_form.designation;

</script>";

echo client_selector();
echo article_selector();

// Close container
echo "</div>";

// Footer
echo nano\footer();



?>
