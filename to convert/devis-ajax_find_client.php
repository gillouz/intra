<?php //idx//Devis//
session_start();
include("lib_1.php");
validate("agenda");
include("devis-lib_main.php");

$nr_client="";
$find="";

if(isset($_GET['find'])) $find = htmlspecialchars($_GET['find'],ENT_QUOTES,'ISO-8859-1'); 
if(isset($_GET['nr_client'])) $nr_client = htmlspecialchars($_GET['nr_client'],ENT_QUOTES,'ISO-8859-1'); 

// Objet de retour
$return=(object) array();
$return->start=microtime();

$column="
Nr_client,
CONCAT('genre_',genre) as GENRE,
Nom,
Prenom,
DATE_TO_CHAR(Date_naissance,'Dd.Mm.yyyy') as DATE_NAISSANCE,
Telephone_1,
Telephone_portable,
adresse_email,
Adresse_1,
Nr_de_rue,
NPA,
Localite
";

switch(true)
{
case $nr_client!="";
  $query="select $column FROM Clients WHERE Nr_client=$nr_client;";
  break;
case $find!="";
  // construction de la requette
  $query="select $column FROM Clients";
  $wand=" where ";
  $query_close="";
  $first_number=true;
  $find_array=explode(" ",$find);
  $parts_used=0;

  foreach($find_array as $part)
  {
    if (strlen($part)>0)
    {
      if( preg_match("#(?=^[0-9.]*$)(?=\.)#",$part) )
      {
        if ($first_number==true)
        {
          $query.=$wand;
          $query.=" DATE_TO_CHAR(Date_naissance,'dd.mm.yyyy') like '%$part%'  ";
          $first_number=false;
          $parts_used++;
        }
      }
      else if( preg_match("#^[0-9]*$#",$part) )
      {
        if ($first_number==true)
        {
          $query.=$wand;
          $query.=" NR_CLIENT=".$part;
          $first_number=false;
          $parts_used++;
        }
      }
      else
      {
          $query.=$wand;
          $query.=" Nom_et_prenom like '%$part%'  ";
          $parts_used++;
      }
      
      $query_close.=")";
      $wand=" and ";
    }

  }
  $query.=" ORDER BY Nom;";

  if($parts_used==0)
  {
    die(return_error($return,"Tous les texte sont trop courts","-1"));
  }

  break;
default:
 die(return_error($return,"recherche vide","-1"));
  break;
}

// Connexion au serveur 4D
try 
{
  
  $db = new PDO($databases["optisphere"]["dsn"], $databases["optisphere"]["user"], $databases["optisphere"]["password"]);

  $stmt = $db->prepare($query);
  $stmt->execute() or die(return_error($return,"Erreur sur la requette","-2"));

  $result=$stmt->fetchAll(PDO::FETCH_ASSOC);

  // deconnexion
  unset($stmt);                                                                                                                                                                                                                    
  unset($db);
} 
catch (Exception $e) 
{
  die(return_error($return,$e->getMessage(),"-2"));
}

if(count($result)==0) 
{
  //die(return_error($return,"Pas de resultat","-3"));
}
if(count ($result)>100)
{
  die(return_error($return,"trop de resultats","-4"));
}

$return->fin=microtime();
$return->code="1";
$return->message="";
$return->status="SUCCESS";
$return->data=$result;
echo JSON_encode($return);

  


?>                