<?php //idx//Devis//
session_start();
include("nanofw/nano.php");
nano\init("page");

// header
echo nano\header();

//Navbar
echo nano\navbar(0);

echo "<div class='container'>";

echo "<div id='logo' class='col-xs-12 col-md-8 col-md-offset-2' ><br><br><img src='sonix_logo.png' class='img-responsive' alt='Responsive image'><br><br></div>";

echo "<div style='clear:both'></div>";

//page_commande.php
$col=1;

echo "<div class='row col-xs-12'>";

// tile start

echo "<button class='btn btn-primary col-xs-4 tile tile-violet-$col' onclick='jump(\"page_biais_encours.php\")' >".nano\lbl("biais_encours")."</button>";
$col++; if($col>=6) $col=1;

echo "<button class='btn btn-primary col-xs-4 tile tile-violet-$col' onclick='jump(\"https://c.eu2.visual.force.com/apex/gis_home\")' >".nano\lbl("back_office")."</button>";
$col++; if($col>=6) $col=1;

echo "<button class='btn btn-primary col-xs-4 tile tile-violet-$col' onclick='jump(\"index.php\")'>".nano\lbl("optique")."</button>";
$col++; if($col>=6) $col=1;

echo "<button class='btn btn-default col-xs-4 tile tile-violet-$col' onclick='jump(\"\")' >".nano\lbl("more_to_come")."</button>";
$col++; if($col>=6) $col=1;

//tile end

echo "</div>";
echo "</div>";
echo "<script>

  function jump(page)
  {
    window.open(page);
  }

</script>";


echo nano\footer();



?>
