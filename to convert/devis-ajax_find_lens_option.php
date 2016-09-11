<?php //idx//Devis//
session_start();
include("lib_1.php");
validate("agenda");
include("devis-lib_main.php");

header('Content-type: text/html; charset=utf-8'); 

// prendre les données dans les paramètres
if(isset($_GET['lens_d'])) $lens_d = htmlspecialchars($_GET['lens_d'],ENT_QUOTES,'ISO-8859-1'); 
if(isset($_GET['lens_g'])) $lens_g = htmlspecialchars($_GET['lens_g'],ENT_QUOTES,'ISO-8859-1'); 

if($lens_d=="") $lens_d=0;
if($lens_g=="") $lens_g=0;

// Objet de retour
$return=(object) array();
$return->start=microtime();

//Chercher les carracteristiques de ces verres
$query="
select coating_designation from offre_verre_option
where 
coating_code not in ( select coating_code from offre_verre where id in ($lens_d,$lens_g))
and lens_code in (select lens_code from offre_verre where id in ($lens_d,$lens_g))
;";

echo "<p>".$query."</p>";

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

echo "toto";

$return->fin=microtime();
$return->code="1";
$return->message="";
$return->status="SUCCESS";
$return->data=$result;
$return->side=$side;
echo JSON_encode($return, JSON_NUMERIC_CHECK);


?>
