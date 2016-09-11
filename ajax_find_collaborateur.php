<?php //idx//Devis//
session_start();
include("nanofw/nano.php");
nano\init("webservice");

header('Content-type: text/html; charset=utf-8'); 

$find="";
$what="code";


// prendre les données dans les paramètres
if(isset($_POST['find'])) $find = htmlspecialchars($_POST['find'],ENT_QUOTES,'ISO-8859-1');
if(isset($_POST['what'])) $what = htmlspecialchars($_POST['what'],ENT_QUOTES,'ISO-8859-1');


$return=(object) array();

$return->start=microtime();

// tester le la chaine de recherche est vide
if (strlen($find)<2) die(nano\error("recherche vide ou trop courte",-1));

    
$query="SELECT 
code,
libelle,
type
from d_collaborateur ";

switch($what)
{
  case "collab":
    $query.=" WHERE libelle like '%$find%' and type!='ORL' and type!='OPHTALMO' ";
    break;
  case "ophta":
    $query.=" WHERE libelle like '%$find%' and type='OPHTA' ";
    break;
  case "orl":
    $query.=" WHERE libelle like '%$find%' and type='ORL'  ";
    break;
}


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
