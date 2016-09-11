<?php //idx//Devis//
session_start();
include("nanofw/nano.php");
nano\init("webservice");

// Objet de retour
$return=(object) array();
$return->start=microtime();

$mapping =
[ 
"magasin",
"date_mesure",
"heure_mesure",
"nr_client",
"client_detail",
"article_code",
"designation",
"FPDR",
"FPDL",
"FHR",
"FHL",
"A",
"B",
"DBL",
"EDR",
"EDL",
"PT",
"BVD",
"FWA",
"NPDR",
"NPDL",
"UF",
"RD",
"IR",
"IL"
];

if(isset($_POST["data"])) $data=$_POST["data"];

try
{
  $data=json_decode($data);
}
catch (Exception $e)
{
  die(nano\error("invalide json",-1));
}

$data->date_mesure=date("d.m.Y",strtotime("now"));
$data->heure_mesure=date("H:i:s",strtotime("now"));
$data->magasin="01VD";


$numclient=$data->numclient;

$url="http://192.168.12.222/REST/MESURE/AJOUTER/?";

foreach($mapping as $value)
{

  $url_part="";
  $url_value="";
  
  if(isset($data->{$value}))
  {
    if($value=="date_mesure")
    {
      $url_value=date("d.m.Y",strtotime($data->{$value}));
    }
    else
    {
      $url_value=$data->{$value};
    }
    
    $url_part = htmlspecialchars($url_value,ENT_QUOTES,'ISO-8859-1'); 

    if (strlen($url_part)>0) $url.="&$value=".urlencode($url_part);
  }
    
}

$result=JSON_decode(file_get_contents($url));

$file=fopen("log_mesure.html","a+");
fwrite($file,"\n<p>".date("Y-m-d H:i:s")."|".$url."</p>",2048);
fclose($file);

if($result->optishere[0]->systeme[0]->code!=1) die(nano\error($result->optishere[0]->systeme[0]->message,-1));

$return->fin=microtime();
$return->code="1";
$return->message="";
$return->status="SUCCESS";
$return->data=[];
echo JSON_encode($return);



?>                