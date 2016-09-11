<?php //idx//Devis//
session_start();
include("nanofw/nano.php");
nano\init("webservice");

header('Content-type: text/html; charset=utf-8'); 

$find="";
$what="code";
$type="";
$stock=0;
$maxperiode=date("Ym");

// prendre les données dans les paramètres
if(isset($_POST['find'])) $find = htmlspecialchars($_POST['find'],ENT_QUOTES,'ISO-8859-1');
if(isset($_POST['what'])) $what = htmlspecialchars($_POST['what'],ENT_QUOTES,'ISO-8859-1');
if(isset($_POST['type'])) $type = htmlspecialchars($_POST['type'],ENT_QUOTES,'ISO-8859-1');
if(isset($_POST['stock'])) $stock = htmlspecialchars($_POST['stock'],ENT_QUOTES,'ISO-8859-1');


$return=(object) array();

$return->start=microtime();

// tester le la chaine de recherche est vide
if (strlen($find)<2) die(nano\error("recherche vide ou trop courte",-1));

$column="
a.code as article_code,
t.libelle_f as article_type,
a.libelle_f as designation,
m.libelle_f as marque,
a.nr_metas as nr_metas,
a.prix_vente
";


if($stock==1)
{

/*$query="SELECT 
$column,
s.qte_en_stock as qt_atelier,
s2.qte_en_stock as qt_sav
FROM d_article AS a  
LEFT JOIN d_marque as m on a.marque=m.code
LEFT JOIN d_type_article as t on a.type_article=t.code
LEFT JOIN (select id_article, qte_en_stock from f_stock where periode=$maxperiode and lieu_de_stock='ATELIER') as s on s.id_article=a.id
LEFT JOIN (select id_article, qte_en_stock from f_stock where periode=$maxperiode and lieu_de_stock='CASSE') as s2 on s2.id_article=a.id
";*/

$query="SELECT 
$column,
s.qte_en_stock as qt_atelier,
s2.qte_en_stock as qt_sav
FROM d_article AS a 
LEFT JOIN d_marque as m on a.marque=m.code 
LEFT JOIN d_type_article as t on a.type_article=t.code 
LEFT JOIN f_stock as s on s.periode=$maxperiode and s.lieu_de_stock='ATELIER' and s.id_article=a.id 
LEFT JOIN f_stock as s2 on s2.periode=$maxperiode and s2.lieu_de_stock='CASSE' and s2.id_article=a.id";

}
else
{

$query="SELECT 
$column
FROM d_article AS a  
LEFT JOIN d_marque as m on a.marque=m.code
LEFT JOIN d_type_article as t on a.type_article=t.code
";

}


switch($what)
{
  case "code":
    $query.=" WHERE a.code='$find' ";
    break;
  case "designation":
    $query.=" WHERE a.libelle_f like '%$find%' ";
    break;
  case "marque":
    $query.=" WHERE a.marque like '%$find%' ";
    break;
  case "any": //slow
    $query.=" WHERE ( a.code='$find' or a.libelle_f like '%$find%' or a.marque='$find' ) ";
    break;
}

if($type!="") $query.=" AND type_article in ('$type') ";


$query.=" and actif=1 ";

//and actif=1 and sans_reappro=0
//echo $query;

try 
{
  
  $conn = new PDO($databases["bi"]["dsn"], $databases["bi"]["user"], $databases["bi"]["password"]);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $conn->exec("SET CHARACTER SET utf8");
  $conn->exec("use ".$databases["bi"]["dbname"]);
  
  $stmt = $conn->prepare($query); 
  $stmt->execute();

  // set the resulting array to associative
  $stmt->setFetchMode(PDO::FETCH_ASSOC); 
  $result = $stmt->fetchAll();
  
  unset($conn);
  
} 
catch (Exception $e) 
{
  die(nano\error($e->getMessage(),-2));
}


$return->fin=microtime();
$return->code="1";
$return->message="";
$return->status=$query; //"SUCCESS";
$return->data=$result;
echo JSON_encode($return); //JSON_NUMERIC_CHECK




?>
