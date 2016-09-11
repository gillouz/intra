<?php //idx//Devis//
session_start();
include("nanofw/nano.php");
nano\init("webservice");

header('Content-type: text/html; charset=utf-8'); 

$find="";
$what="code";


// prendre les données dans les paramètres
//if(isset($_POST['find'])) $find = htmlspecialchars($_POST['find'],ENT_QUOTES,'ISO-8859-1');
//if(isset($_POST['what'])) $what = htmlspecialchars($_POST['what'],ENT_QUOTES,'ISO-8859-1');


$return=(object) array();

$return->start=microtime();

//definition des variables


$magasin=$user["user"];
if($user["is_admin"]) $magasin="";

$type_article="'A','AA','SA','KA','RA','ACA','AD','RA','EA'";
$anneeActu=2016;
$anneePrec=$anneeActu-1;

//requette sur les ventes

$query="
select 
v.mois,
v1.ca as ca1,
v2.ca as ca2
from
(
    select
    date_format(dt_mois,'%M') as mois
    from f_vente_sum
    where magasin like '%$magasin%'
    and date_format(dt_mois,'%Y')>='$anneePrec'
    group by mois
    order by
    date_format(dt_mois,'%m')
    
) as v
left join 
(
    select
    date_format(dt_mois,'%M') as mois,
    floor(sum(ca_net)) ca
    from f_vente_sum
    where type_article in ($type_article)
    and magasin like '%$magasin%'
    and date_format(dt_mois,'%Y')='$anneePrec'
    group by mois
    order by
    date_format(dt_mois,'%m')
    
) as v1 on v.mois=v1.mois
left join 
(
    select
    date_format(dt_mois,'%M') as mois,
    floor(sum(ca_net)) ca
    from f_vente_sum
    where type_article in ($type_article)
    and magasin like '%$magasin%'
    and date_format(dt_mois,'%Y')='$anneeActu'
    group by mois
    order by
    date_format(dt_mois,'%m')
) as v2 on v.mois=v2.mois
";


try 
{
  
  $conn = new PDO($databases["bi"]["dsn"], $databases["bi"]["user"], $databases["bi"]["password"]);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $conn->exec("SET CHARACTER SET utf8");
  $conn->exec("SET lc_time_names =".$languages[$lang]["sql_format"]);      
  $conn->exec("use ".$databases["bi"]["dbname"]);
  
  $stmt = $conn->prepare($query); 
  $stmt->execute();

  // set the resulting array to associative
  $stmt->setFetchMode(PDO::FETCH_ASSOC); 
  $result1 = $stmt->fetchAll();
  
  unset($conn);
  
} 
catch (Exception $e) 
{
  die(nano\error($e->getMessage(),-2));
}

// requette sur le biais

$query="
select
date_format(date_facture_prevue,'%M') as mois,
floor(sum(montant_prevu)) ca
from biais
where magasin like '%$magasin%'
and date_format(date_facture_prevue,'%Y')='$anneeActu'
and status in ('ENC')
and _status<9
group by mois
";

try 
{
  
  $conn = new PDO($databases["nano"]["dsn"], $databases["nano"]["user"], $databases["nano"]["password"]);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $conn->exec("SET CHARACTER SET utf8");
  $conn->exec("SET lc_time_names =".$languages[$lang]["sql_format"]);      
  $conn->exec("use ".$databases["nano"]["dbname"]);
  
  $stmt = $conn->prepare($query); 
  $stmt->execute();

  // set the resulting array to associative
  $stmt->setFetchMode(PDO::FETCH_ASSOC); 
  $result2 = $stmt->fetchAll();
  
  unset($conn);
  
} 
catch (Exception $e) 
{
  die(nano\error($e->getMessage(),-2));
}

//setup result
$result=[];
$t1=0;
$t2=0;
$t3=0;


foreach($result1 as $r1)
{
    $r1["ca3"]=0;
    
    
    foreach($result2 as $r2)
    {
        if($r1["mois"]==$r2["mois"])
        {
            $r1["ca3"]=$r2["ca"];
        }
    }
    
    // calculer les totaux
    $t1+=$r1["ca1"];
    $t2+=$r1["ca2"];
    $t3+=$r1["ca3"];
    
    $result[]=$r1;
    
}

// ajouter le total
//$result[]=["mois"=>"Total", "ca1"=>$t1, "ca2"=>$t2, "ca3"=>$t3];

$return->fin=microtime();
$return->tmp=$return->fin-$return->debut;
$return->code="1";
$return->message="";
$return->status="SUCCESS"; //$query; //
$return->data=$result;
echo JSON_encode($return,JSON_NUMERIC_CHECK); //JSON_NUMERIC_CHECK




?>
