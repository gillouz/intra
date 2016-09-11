<?php //idx//Devis//
session_start();
include("lib_1.php");
validate("agenda");
include("devis-lib_main.php");

//ini_set('display_errors','on');
//error_reporting(E_ALL);
header('Content-type: text/html; charset=utf-8'); 

//$handle = fopen("lens_list.json", "r");
//echo fread($handle,9999999);
//fclose($handle);
//die();

$frame="";

// prendre les données dans les paramètres
if(isset($_GET['frame'])) $frame = htmlspecialchars($_GET['frame'],ENT_QUOTES,'ISO-8859-1'); 

$return=(object) array();

$return->start=microtime();

// tester le la chaine de recherche est vide
if (strlen($frame)==0)
{
 die(return_error($return,"recherche vide","-1"));
}
else
{
    

$query_soldes="SELECT 
a.code,
a.type_article,
a.libelle_f as designation,
m.libelle_f as marque,
a.prix_vente,
p.prix_offre as prix_special,
date_format(p.dt_fin,'%d.%m.%Y') as dt_fin,
p.offre as offre
FROM d_article as a  
left join offre_produit as p 
on a.code=p.code_article 
and p.dt_debut<=date(now()) 
and p.dt_fin>=date(now())
inner join d_marque as m on a.marque=m.code
where a.code='$frame'";

$query_marque="SELECT 
a.code,
a.type_article,
a.libelle_f as designation,
m.libelle_f as marque,
a.prix_vente,
(1-p.prix_rabais)*a.prix_vente as prix_special,
date_format(p.dt_fin,'%d.%m.%Y') as dt_fin,
p.html as offre
FROM d_article as a  
left join offre_marque as p 
on a.marque=p.code_marque 
and a.type_article=p.type_article
and p.dt_debut<=date(now()) 
and p.dt_fin>=date(now()) 
and floor(datediff(curdate(),a.date_premier_achat)/30)>=p.age
and type_equipement='L'
inner join d_marque as m on a.marque=m.code
where a.code='$frame'";

$query_type="SELECT 
a.code,
a.type_article,
a.libelle_f as designation,
m.libelle_f as marque,
a.prix_vente,
(1-p.prix_rabais)*a.prix_vente as prix_special,
date_format(p.dt_fin,'%d.%m.%Y') as dt_fin,
p.html as offre
FROM d_article as a  
left join offre_marque as p 
on p.code_marque='*' 
and a.type_article=p.type_article
and p.dt_debut<=date(now()) 
and p.dt_fin>=date(now()) 
and floor(datediff(curdate(),a.date_premier_achat)/30)>=p.age
and type_equipement='L'
inner join d_marque as m on a.marque=m.code
where a.code='$frame'
and concat(a.marque,'-',a.type_article) not in (select concat(code_marque,'-',type_article) from offre_marque where marque!='*')
";


$query_no_offer="SELECT 
a.code,
a.type_article,
a.libelle_f as designation,
m.libelle_f as marque,
a.prix_vente,
a.prix_vente as prix_special,
'' as dt_fin,
'' as offre
FROM d_article as a  
inner join d_marque as m on a.marque=m.code
where a.code='$frame'";



$query="select * from (($query_soldes) union ($query_marque) union ($query_type) union ($query_no_offer)) as t where prix_special is not null order by prix_special";


//where prix_special is not null

//echo "<p>".$query."</p>";

  try 
  {
    // Open DWH data base
    //$dwh = mysqli_connect("192.168.12.199","gpizzetta","314159","dwh_dev",3306) or die("Error connecting to dwh_prod");
    
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
