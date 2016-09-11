<?php //idx//Devis//
session_start();
include("nanofw/nano.php");
nano\init("webservice");

if(isset($_GET['NR_CLIENT']))   $nr_client = htmlspecialchars($_GET['NR_CLIENT'],ENT_QUOTES,'ISO-8859-1');
if(isset($_GET['NR_ORDONNANCE'])) $nr_ordonnance = htmlspecialchars($_GET['NR_ORDONNANCE'],ENT_QUOTES,'ISO-8859-1'); 

// Objet de retour
$return=(object) array();
$return->start=microtime();

// tester le la chaine de recherche est vide
if (strlen($nr_client)==0 && strlen($nr_ordonnance)==0) die(nano\error($return,"recherche vide","-1"));

    
$where="Nr_client=$nr_client ";    

if (strlen($nr_ordonnance)>0) $where="Nr_ordonnance=$nr_ordonnance " ;

//o.Type_ordonnance as ,

$query_ordo="select
o.Nr_client as numclient,
o.Nr_ordonnance as numordonnance,
o.Emploi as emploi,
o.Refractionniste as refractionniste,
o.Vendeur as vendeur,
DATE_TO_CHAR(o.Date_cree,'Dd.Mm.yyyy') as date_ordonnance,
o.Remarque as remarque,
o.Saisie_par as saisie_par,
o.Magasin as magasin
FROM Ordonnances AS o WHERE o.$where ORDER by o.Date_cree DESC";


$query_od="select
y.Nr_ordonnance as numordonnance,
y.Sphere AS sphere_d,
y.Cylindre AS cylindre_d,
y.Axe AS axe_d,
y.Addition AS add_d,
y.Prisme AS prisme_d,
y.Base AS base_d,
y.Delta AS delta_d,
y.Ecart AS dp_d,
y.Visus AS visus_d
FROM Ordonnances_oeil AS y WHERE y.Oeil_D_G='D' and y.Nr_ordonnance IN (SELECT Nr_ordonnance FROM Ordonnances WHERE $where)";


$query_og="select
y.Nr_ordonnance as numordonnance,
y.Sphere AS sphere_g,
y.Cylindre AS cylindre_g,
y.Axe AS axe_g,
y.Addition AS add_g,
y.Prisme AS prisme_g,
y.Base AS base_g,
y.Delta AS delta_g,
y.Ecart AS dp_g,
y.Visus AS visus_g
FROM Ordonnances_oeil AS y WHERE y.Oeil_D_G='G' and y.Nr_ordonnance IN (SELECT Nr_ordonnance FROM Ordonnances WHERE $where)";


// Connexion au serveur 4D
try 
{
    $db = new PDO($databases["optisphere"]["dsn"], $databases["optisphere"]["user"], $databases["optisphere"]["password"]);

    // ordo
    $stmt = $db->prepare($query_ordo);
    $stmt->execute() or die(nano\error($return,"Erreur sur la requette","-2"));
    $result_ordo=$stmt->fetchAll(PDO::FETCH_ASSOC);

    // od
    $stmt = $db->prepare($query_od);
    $stmt->execute() or die(nano\error($return,"Erreur sur la requette","-2"));
    $result_od=$stmt->fetchAll(PDO::FETCH_ASSOC);

    // og
    $stmt = $db->prepare($query_og);
    $stmt->execute() or die(nano\error($return,"Erreur sur la requette","-2"));
    $result_og=$stmt->fetchAll(PDO::FETCH_ASSOC);

    // deconnexion
    unset($stmt);                                                                                                                                                                                                                    
    unset($db);
} 
catch (Exception $e) 
{
    die(return_error($return,$e->getMessage(),"-2"));
}

$result = array();

foreach($result_ordo as $ordo)
{
    $tmp = array();

    foreach($result_od as $od)
    {
        if($ordo['numordonnance'] == $od['numordonnance'])
        {
            $tmp = array_merge($ordo,$od);
            break;
        }
    }
    foreach($result_og as $og)
    {
        if($ordo['numordonnance'] == $og['numordonnance'])
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

  


?>                