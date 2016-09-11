<?php //idx//Devis//
session_start();
include("lib_1.php");
validate("agenda");
include("devis-lib_main.php");


header('Content-type: text/html; charset=utf-8'); 

$frame="";

// prendre les données dans les paramètres
if(isset($_GET['code'])) $code = htmlspecialchars($_GET['code'],ENT_QUOTES,'ISO-8859-1'); 
if(isset($_GET['type'])) $type = htmlspecialchars($_GET['type'],ENT_QUOTES,'ISO-8859-1'); 

$return=(object) array();

$return->start=microtime();

// tester le la chaine de recherche est vide
if (strlen($code)==0)
{
 die(return_error($return,"recherche vide","-1"));
}
else
{
    
$query="SELECT 
a.code,
t.libelle_f as type_article,
a.libelle_f as designation,
m.libelle_f as marque,
a.prix_vente
FROM d_article AS a  
INNER JOIN d_type_article AS t
on a.type_article=t.code 
LEFT JOIN d_marque as m on a.marque=m.code
WHERE a.code='$code'
";

//echo $query;

  try 
  {
    
    $conn = new PDO($databases["bi"]["dsn"], $databases["bi"]["user"], $databases["bi"]["password"]);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $conn->prepare($query); 
    $stmt->execute();

    // set the resulting array to associative
    $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    $result = $stmt->fetchAll();
    
    unset($conn);
    
  } 
  catch (Exception $e) 
  {
    die(return_error($return,$e->getMessage(),"-2"));
  }
  
  
  $return->fin=microtime();
  $return->code="1";
  $return->message="";
  $return->status="SUCCESS";
  $return->data=$result;
  echo JSON_encode($return, JSON_NUMERIC_CHECK);

}
  


?>
