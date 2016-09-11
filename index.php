<?php //idx//Devis//
session_start();
include("nanofw/nano.php");
nano\init("page");

// header
echo nano\header();

//Navbar
echo nano\navbar(0);

echo "<div class='container'>";

echo "<div id='logo' class='col-xs-8 col-xs-offset-2 col-md-6 col-md-offset-3' ><br><br><br><img src='img_logo.png' class='img-responsive' alt='Responsive image'><br><br><br></div>";

echo "<div style='clear:both'></div>";

//page_commande.php
$col=1;

echo "<div class='row col-xs-12'>";

// tile start

echo "<button class='btn btn-primary col-xs-4 tile tile-color-$col' onclick='jump(\"page_cat_lens.php\")'>".nano\lbl("tarif_verres")."</button>";
$col++; if($col>=6) $col=1;

echo "<a class='btn btn-primary col-xs-4 tile tile-center tile-color-$col ' href='doc/1607-Catalogue_Lentilles_berdoz_V2_$lang.pdf' >".nano\lbl("tarif_lentilles")."</a>";
$col++; if($col>=6) $col=1;

echo "<a class='btn btn-primary col-xs-4 tile tile-center tile-color-$col ' href='page_byjuno.php' >".nano\lbl("byjuno")."</a>";
$col++; if($col>=6) $col=1;

echo "<button class='btn btn-primary col-xs-4 tile tile-color-$col' onclick='jump(\"page_commande.php\")'>".nano\lbl("commandes_atelier")."</button>";
$col++; if($col>=6) $col=1;

echo "<button class='btn btn-primary col-xs-4 tile tile-color-$col' onclick='jump(\"page_activity_client.php\")'>".nano\lbl("activity_client")."</button>";
$col++; if($col>=6) $col=1;

echo "<button class='btn btn-primary col-xs-4 tile tile-color-$col' onclick='jump(\"https://c.eu2.visual.force.com/apex/gis_home\")' >".nano\lbl("back_office")."</button>";
$col++; if($col>=6) $col=1;

echo "<button class='btn btn-primary col-xs-4 tile tile-color-$col' onclick='jump(\"index_audio.php\")' >".nano\lbl("audition")."</button>";
$col++; if($col>=6) $col=1;

echo "<button class='btn btn-default col-xs-4 tile tile-color-$col' onclick='jump(\"\")' >".nano\lbl("more_to_come")."</button>";
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
