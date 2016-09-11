<?php //idx//Devis//
session_start();
include("lib_1.php");
validate("agenda");
include("devis-lib_main.php");

// Objet de retour
$return=(object) array();
$return->start=microtime();

$mapping = array( "NR_CLIENT"=>"numclient",
"NOM"=>"nom",
"PRENOM"=>"prenom",
"DATE_NAISSANCE"=>"datenaissance",
"GENRE"=>"genre",
"ADRESSE_1"=>"adresse1",
"ADRESSE_2"=>"adresse2",
"NR_DE_RUE"=>"numrue",
"NPA"=>"npa",
"LOCALITE"=>"localite",
"CANTON"=>"canton",
"PAYS"=>"pays",
"TELEPHONE_1"=>"tel1",
"TELEPHONE_2"=>"tel2",
"FAX"=>"fax",
"TELEPHONE_PORTABLE"=>"mobile",
"LANGUE"=>"langue",
"ADR_FACT_1"=>"adressefact1",
"ADR_FACT_2"=>"adressefact2",
"ADR_FACT_3"=>"adressefact3",
"ADR_FACT_4"=>"adressefact4",
"ADRESSE_EMAIL"=>"email",
"ACCEPTE_MAILING"=>"mailing",
"ENTREPRISE"=>"entreprise",
"MAGASIN"=>"magasin",
"NUMAVS"=>"numavs",
"PROFESSION"=>"profession",
"ACCEPTE_ACP"=>"acp",
"REMARQUE"=>"remarque");

if($_GET["NR_CLIENT"]=="")
{
  $url="http://".$databases["optisphere"]["ip"]."/REST/CLIENT/AJOUTER/?";
}
else
{
  $url="http://".$databases["optisphere"]["ip"]."/REST/CLIENT/MODIFIER/?";
}


foreach($mapping as $key=>$value)
{

  $url_part="";

  if(isset($_GET[$key])) $url_part = htmlspecialchars($_GET[$key],ENT_QUOTES,'ISO-8859-1'); 

  if (strlen($url_part)>0) $url.="&$value=".urlencode($url_part);
    
}

$result=JSON_decode(file_get_contents($url));

$return->fin=microtime();
$return->code="1";
$return->message="";
$return->status="SUCCESS";
$return->data=[];
echo JSON_encode($return);







?>                