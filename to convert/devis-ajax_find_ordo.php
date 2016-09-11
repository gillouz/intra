<?php //idx//Devis//
session_start();
include("lib_1.php");
validate("agenda");
include("devis-lib_main.php");

if(isset($_GET['NR_CLIENT']))   $nr_client = htmlspecialchars($_GET['NR_CLIENT'],ENT_QUOTES,'ISO-8859-1');
if(isset($_GET['NR_ORDONNANCE'])) $nr_ordonnance = htmlspecialchars($_GET['NR_ORDONNANCE'],ENT_QUOTES,'ISO-8859-1'); 

// Objet de retour
$return=(object) array();

$return->start=microtime();

//$dsn = "4D:host=$opt_ip;port=$opt_port;charset=UTF-8";
//$dsn = '4D:host=192.168.12.222;charset=UTF-8';
//$user = 'administrateur';
//$pass = 'M6522!';

// tester le la chaine de recherche est vide
if (strlen($nr_client)==0 && strlen($nr_ordonnance)==0)
{
 die(return_error($return,"recherche vide","-1"));
}
else
{
    
$where="Nr_client=$nr_client ";    

if (strlen($nr_ordonnance)>0) $where="Nr_ordonnance=$nr_ordonnance " ;

$query_ordo="select
o.Nr_client,
o.Nr_ordonnance,
o.Emploi,
o.Refractionniste,
o.Vendeur,
DATE_TO_CHAR(o.Date_cree,'Dd.Mm.yyyy') AS DATE_ORDONNANCE,
o.Remarque,
o.Type_ordonnance,
o.Saisie_par,
o.Magasin
FROM Ordonnances AS o WHERE o.$where ORDER by o.Date_cree DESC";


$query_od="select
y.Nr_ordonnance,
y.Sphere AS SPH_OD,
y.Cylindre AS CYL_OD,
y.Axe AS AXE_OD,
y.Addition AS ADD_OD,
y.Prisme AS PRISM_OD,
y.Base AS BASE_OD,
y.Delta AS DELTA_OD,
y.Ecart AS ECART_OD,
y.Visus AS VISUS_OD
FROM Ordonnances_oeil AS y WHERE y.Oeil_D_G='D' and y.Nr_ordonnance IN (SELECT Nr_ordonnance FROM Ordonnances WHERE $where)";


$query_og="select
y.Nr_ordonnance,
y.Sphere AS SPH_OG,
y.Cylindre AS CYL_OG,
y.Axe AS AXE_OG,
y.Addition AS ADD_OG,
y.Prisme AS PRISM_OG,
y.Base AS BASE_OG,
y.Delta AS DELTA_OG,
y.Ecart AS ECART_OG,
y.Visus AS VISUS_OG
FROM Ordonnances_oeil AS y WHERE y.Oeil_D_G='G' and y.Nr_ordonnance IN (SELECT Nr_ordonnance FROM Ordonnances WHERE $where)";


  // Connexion au serveur 4D
  try 
  {
   $db = new PDO($databases["optisphere"]["dsn"], $databases["optisphere"]["user"], $databases["optisphere"]["password"]);
    
    // ordo
    $stmt = $db->prepare($query_ordo);
    $stmt->execute() or die(return_error($return,"Erreur sur la requette","-2"));
    $result_ordo=$stmt->fetchAll(PDO::FETCH_ASSOC);

    // od
    $stmt = $db->prepare($query_od);
    $stmt->execute() or die(return_error($return,"Erreur sur la requette","-2"));
    $result_od=$stmt->fetchAll(PDO::FETCH_ASSOC);

    // og
    $stmt = $db->prepare($query_og);
    $stmt->execute() or die(return_error($return,"Erreur sur la requette","-2"));
    $result_og=$stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // deconnexion
    unset($stmt);                                                                                                                                                                                                                    
    unset($db);
  } 
  catch (Exception $e) 
  {
    die(return_error($return,$e->getMessage(),"-2"));
  }
  
  // assembler les ordonnance et les yeux
  //$json1 = json_decode($json1String, true);
  //$json2 = json_decode($json2String, true);

  $result = array();
  
  foreach($result_ordo as $ordo)
  {
    $tmp = array();
  
    foreach($result_od as $od)
    {
      if($ordo['NR_ORDONNANCE'] == $od['NR_ORDONNANCE'])
      {
            $tmp = array_merge($ordo,$od);
            break;
      }
    }
    foreach($result_og as $og)
    {
      if($ordo['NR_ORDONNANCE'] == $og['NR_ORDONNANCE'])
      {
            $tmp = array_merge($tmp,$og);
            break;
      }
    }
    $result[]=$tmp;
  }
  
  $return->fin=microtime();
  $return->code="1";
  $return->message="";
  $return->status="SUCCESS";
  $return->data=$result;
  echo JSON_encode($return, JSON_NUMERIC_CHECK);

}
  


?>                