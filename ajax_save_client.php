<?php //idx//Devis//
session_start();
include("nanofw/nano.php");
nano\init("webservice");

// Objet de retour
$return=(object) array();
$return->start=microtime();

$mapping =
[ 
"numclient",
"nom",
"prenom",
"datenaissance",
"genre",
"adresse1",
"adresse2",
"numrue",
"npa",
"localite",
"canton",
"pays",
"tel1",
"tel2",
"fax",
"mobile",
"langue",
"adressefact1",
"adressefact2",
"adressefact3",
"adressefact4",
"email",
"mailing",
"entreprise",
"magasin",
"numavs",
"profession",
"acp",
"remarque",
"okmailingaudio",
"okemailing",
"ddnestime",
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

$numclient=$data->numclient;

if( $numclient=="" or $numclient==0 )
{
  $url="http://".$databases["optisphere"]["ip"]."/REST/CLIENT/AJOUTER/?";
}
else
{
  $url="http://".$databases["optisphere"]["ip"]."/REST/CLIENT/MODIFIER/?";
}

foreach($mapping as $value)
{

  $url_part="";
  $url_value="";
  
  if(isset($data->{$value}))
  {
    if($value=="datenaissance")
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

//echo $url;

if($result->optishere[0]->systeme[0]->code!=1) die(nano\error($result->optishere[0]->systeme[0]->message,-1));

if (isset($result->optishere[1]->client[0]->numclient)) $data->numclient=$result->optishere[1]->client[0]->numclient;  
  
$return->fin=microtime();
$return->code="1";
$return->message="";
$return->status="SUCCESS";
$return->data=[$data];
echo JSON_encode($return);



?>                