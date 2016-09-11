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

//col-xs-offset-2

// open Bootstrap container
echo "<div class='container'>";

echo "<div id='logo' class='col-xs-12' style='padding:20px'>
<img src='img_logo.png' class='img-responsive' style='margin:0 auto' alt='Responsive image'>
</div>";

echo "<div style='clear:both'></div>";

echo "<h1>".nano\lbl("byjuno_procedure")."</h1>";

echo "<div class='row'>

<h3>".nano\lbl("download_bill")."</h3>
<br>
<div class'' >
  <label class='control-label'>".nano\lbl("bill_number")."</label>
  <div class='input-group'><input class='form-control' name='nrfacture' id='nrfacture' placeholder='".nano\lbl("bill_number")."' type='number' >
  <span class='input-group-btn'>
    <button type='button' class='btn btn-primary' onclick='window.open(\"http://192.168.12.222/REST/FACTURE/GENERER_PDF/?numfacture=\"+getElementById(\"nrfacture\").value)'>".nano\lbl("find")."</button>
  </span>
  </div>
</div>
<br>
<br>
<br>
</div>";

echo "<br><h3><a href='doc/byjuno_$lang.pdf' >".nano\lbl("link_to_procedure")."</a></h3>";

echo "<br><h3><a href='doc/PROC-MAG-Byjuno v1_$lang.pdf' >".nano\lbl("link_to_procedure_berdoz")."</a></h3>";



// Close container
echo "</div>";

// Footer
echo nano\footer();



?>
