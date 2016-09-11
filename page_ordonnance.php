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
echo "<div class='container-fluid'>";

echo "<div class='row' >";

$param=
[
"nr_client"=>$nr_client,
"callback"=>"on_client_choose",
];

//"actions"=>[[ "type"=>"button", "col"=>"col-xs-3", "onclick"=>"client_activity_save()", "name"=>"save", "label"=>nano\lbl("save") ]],

echo client_selector_form($param);

echo ordonnance_selector();

echo "<hr class='col-xs-12'>

</div>";

echo ordonnance_form();

echo "<script>

function ordonnance_find()
{

  
  intra.ordonnance.find(10,'');



}




</script>";


// Close container
echo "</div>";

// Footer
echo nano\footer();



?>
