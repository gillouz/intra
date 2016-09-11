<?php //idx//Devis//
session_start();
include("lib_1.php");
validate("");
include("devis-lib_main.php");


$nr_client="";

if(isset($_GET['nr_client'])) $nr_client = htmlspecialchars($_GET['nr_client'],ENT_QUOTES,'ISO-8859-1'); 

//client
$PictureDate = htmlspecialchars($_GET['PictureDate'],ENT_QUOTES,'ISO-8859-1');
$LastUpdated = htmlspecialchars($_GET['LastUpdated'],ENT_QUOTES,'ISO-8859-1');
$CustomerId = htmlspecialchars($_GET['CustomerId'],ENT_QUOTES,'ISO-8859-1');
$FirstName = htmlspecialchars($_GET['FirstName'],ENT_QUOTES,'ISO-8859-1');
$LastName = htmlspecialchars($_GET['LastName'],ENT_QUOTES,'ISO-8859-1');
$Email = htmlspecialchars($_GET['Email'],ENT_QUOTES,'ISO-8859-1');
//oeil
$FarPDR = htmlspecialchars($_GET['FarPDR'],ENT_QUOTES,'ISO-8859-1');
$FarPDL = htmlspecialchars($_GET['FarPDL'],ENT_QUOTES,'ISO-8859-1');
$NearPDR = htmlspecialchars($_GET['NearPDR'],ENT_QUOTES,'ISO-8859-1');
$NearPDL = htmlspecialchars($_GET['NearPDL'],ENT_QUOTES,'ISO-8859-1');
$HeightR = htmlspecialchars($_GET['HeightR'],ENT_QUOTES,'ISO-8859-1');
$HeightL = htmlspecialchars($_GET['HeightL'],ENT_QUOTES,'ISO-8859-1');
//monture sur le client
$Panto = htmlspecialchars($_GET['Panto'],ENT_QUOTES,'ISO-8859-1');
$Wrap = htmlspecialchars($_GET['Wrap'],ENT_QUOTES,'ISO-8859-1');
$RVD = htmlspecialchars($_GET['RVD'],ENT_QUOTES,'ISO-8859-1');
//monture seule
$FrameType = htmlspecialchars($_GET['FrameType'],ENT_QUOTES,'ISO-8859-1');
$AValue = htmlspecialchars($_GET['AValue'],ENT_QUOTES,'ISO-8859-1');
$BValue = htmlspecialchars($_GET['BValue'],ENT_QUOTES,'ISO-8859-1');
$EDValue = htmlspecialchars($_GET['EDValue'],ENT_QUOTES,'ISO-8859-1');
$DBLValue = htmlspecialchars($_GET['DBLValue'],ENT_QUOTES,'ISO-8859-1');
//Type de verre
$LensType = htmlspecialchars($_GET['LensType'],ENT_QUOTES,'ISO-8859-1');

$header=explode("^",$_SERVER['HTTP_OPTIKAM']);
$user=$header[0];
$password=$header[1];

// header
echo header_display("#000000");

//Navbar
echo navbar(0);

// sauvegarder la chaine d'appel
$file=fopen("query_log.txt","a+");
fwrite($file,"||".$_SERVER['QUERY_STRING']."||",2048);
fclose($file);

echo "<!-- container -->
<div class='container-fluid'>
<div class='panel panel-default' style='background:#FFFFFF'>
<div class='panel-body'>";

echo "<h1>".lbl("client_and_frame")."</h1>"; //titre

//recherche du client
echo client_find2();

// recherche de la monture
echo "<!-- client -->
<div class='row'>
  <div id='frame_find'>
    <div class='col-md-4'>
      <label for='LastUpdated' >".lbl("frame")."</label>
      <div class='input-group'>
        <input type='text' class='form-control' name='comm_reference' placeholder='".lbl("frame")."' onchange='frame_find_one_mesure(this.value); this.value=\"\"' >
        <span class='input-group-btn'>
          <button class='btn btn-primary' type='button' onclick=''>".lbl("find")."</button>
        </span>
      </div>
    </div>
  </div>
  <div id='frame_choosed' style='padding:15px;'></div>
</div>";

echo "<h1>".lbl("mesurement")."</h1>"; //titre


echo "<!-- mesures set 1 -->
<form role='form' name='mesure'>
  <div style='clear:both'></div>
  <div class='panel panel-default' style='background:#EFEFEF'>
    <div class='panel-body'>
      <div class='col-md-1'>
        <div class=' form-group'>
          <label for='OD' style='float:right' >&nbsp</label>
          <div id='OD' style='font-weight:bold;float:right' >OD</div>
        </div>
      </div>
      <div class='col-md-2'>
        <div class=' form-group'>
          <label for='FarPDR' >DP VL</label>
          <input class='form-control' name='FarPDR' id='FarPDR' type='text' value='".$FarPDR."'>
        </div>
      </div>
      <div class='col-md-2'>
        <div class=' form-group'>
          <label for='NearPDR' >DP VP</label>
          <input class='form-control' name='NearPDR' id='NearPDR' type='text' value='".$NearPDR."'>
        </div>
      </div>
      <div class='col-md-2'>
        <div class=' form-group'>
          <label for='HeightR' >Hauteur</label>
          <input class='form-control' name='HeightR' id='NeaPDR' type='text' value='".$HeightR."'>
        </div>
      </div>
      <div style='clear:both'></div>
      <div class='col-md-1'>
        <div class=' form-group'>
          <div id='OG' style='font-weight:bold;float:right' >OG</div>
        </div>
      </div>
      <div class='col-md-2'>
        <div class=' form-group'>
          <label for='FarPDL' hidden>DP VL</label>
          <input class='form-control' name='FarPDL' id='FarPDR' type='text' value='".$FarPDL."'>
        </div>
      </div>
      <div class='col-md-2'>
        <div class=' form-group'>
          <label for='NearPDL' hidden>DP VP</label>
          <input class='form-control' name='NearPDL' id='NearPDR' type='text' value='".$NearPDL."'>
        </div>
      </div>
      <div class='col-md-2'>
        <div class=' form-group'>
          <label for='HeightL' hidden>Hauteur</label>
          <input class='form-control' name='HeightL' id='HeightL' type='text' value='".$HeightR."'>
        </div>
      </div>
    </div>
  </div>
  <div style='clear:both'></div>
  <div class='col-md-4'>
    <div class=' form-group'>
      <label for='Panto' >Angle Pantoscopique</label>
      <input class='form-control' name='Panto' id='Panto' type='text' value='".$Panto."'>
    </div>
  </div>
  <div class='col-md-4'>
    <div class=' form-group'>
      <label for='Wrap' >Galbe</label>
      <input class='form-control' name='Wrap' id='Wrap' type='text' value='".$Wrap."'>
    </div>
  </div>
  <div class='col-md-4'>
    <div class=' form-group'>
      <label for='RVD' >Distance de lecture</label>
      <input class='form-control' name='RVD' id='RVD' type='text' value='".$RVD."'>
    </div>
  </div>
  <div style='clear:both'></div>
  <div class='col-md-4'>
    <div class=' form-group'>
      <label for='FrameType' >Type de monture</label>
      <input class='form-control' name='FrameType' id='FrameType' type='text' value='".$FrameType."'>
    </div>
  </div>
  <div class='col-md-2'>
    <div class=' form-group'>
      <label for='AValue' >A</label>
      <input class='form-control' name='AValue' id='AValue' type='text' value='".$AValue."'>
    </div>
  </div>
  <div class='col-md-2'>
    <div class=' form-group'>
      <label for='BValue' >B</label>
      <input class='form-control' name='BValue' id='BValue' type='text' value='".$BValue."'>
    </div>
  </div>
  <div class='col-md-2'>
    <div class=' form-group'>
      <label for='EDValue' >ED</label>
      <input class='form-control' name='EDValue' id='EDValue' type='text' value='".$EDValue."'>
    </div>
  </div>
  <div class='col-md-2'>
    <div class=' form-group'>
      <label for='DBLValue' >DBL</label>
      <input class='form-control' name='DBLValue' id='DBLValue' type='text' value='".$DBLValue."'>
    </div>
  </div>
  <div class='col-md-12' style='clear:both'>&nbsp</div>
  <div class='col-md-10'></div>
</form>
<input type='button' value='Enregistrer' class='btn btn-primary col-md-2' onclick='mesure_save(document.mesure)' >
";


echo "</div></div></div>"; // container

echo "<script>

var nr_client='$nr_client';
var client='$FirstName $LastName';

client=client.trim();

switch(true)
{
  case nr_client!='':
    client_find(nr_client);
    break;
  case client!='':
    client_find(client);
    break;  
}

</script>";


echo footer_display();


?>
