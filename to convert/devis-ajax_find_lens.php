<?php //idx//Devis//
session_start();
include("lib_1.php");
validate("agenda");
include("devis-lib_main.php");

header('Content-type: text/html; charset=utf-8'); 

// prendre les données dans les paramètres
if(isset($_GET['sph_d'])) $sph_d = htmlspecialchars($_GET['sph_d'],ENT_QUOTES,'ISO-8859-1'); 
if(isset($_GET['cyl_d'])) $cyl_d = htmlspecialchars($_GET['cyl_d'],ENT_QUOTES,'ISO-8859-1'); 
if(isset($_GET['add_d'])) $add_d = htmlspecialchars($_GET['add_d'],ENT_QUOTES,'ISO-8859-1'); 
if(isset($_GET['prism_d'])) $prism_d = htmlspecialchars($_GET['prism_d'],ENT_QUOTES,'ISO-8859-1');
if(isset($_GET['diam_d'])) $diam_d = htmlspecialchars($_GET['diam_d'],ENT_QUOTES,'ISO-8859-1'); 

if(isset($_GET['sph_g'])) $sph_g = htmlspecialchars($_GET['sph_g'],ENT_QUOTES,'ISO-8859-1'); 
if(isset($_GET['cyl_g'])) $cyl_g = htmlspecialchars($_GET['cyl_g'],ENT_QUOTES,'ISO-8859-1'); 
if(isset($_GET['add_g'])) $add_g = htmlspecialchars($_GET['add_g'],ENT_QUOTES,'ISO-8859-1'); 
if(isset($_GET['prism_g'])) $prism_g = htmlspecialchars($_GET['prism_g'],ENT_QUOTES,'ISO-8859-1');
if(isset($_GET['diam_g'])) $diam_g = htmlspecialchars($_GET['diam_g'],ENT_QUOTES,'ISO-8859-1'); 

if(isset($_GET['side'])) $side=htmlspecialchars($_GET['side'],ENT_QUOTES,'ISO-8859-1');

// Objet de retour
$return=(object) array();

$return->start=microtime();

// si pas de valeurs mettre les valeurs par defaut
if ($sph_d=="") $sph_d=0;
if ($cyl_d=="") $cyl_d=0;
if ($add_d=="") $add_d=0;
if ($prism_d=="") $prism_d=0;
if ($diam_d=="") $diam_d=0;

if ($sph_g=="") $sph_g=0;
if ($cyl_g=="") $cyl_g=0;
if ($add_g=="") $add_g=0;
if ($prism_g=="") $prism_g=0;
if ($diam_g=="") $diam_g=0;

if ($side=="") $side=3;

// ****** conversion de cylindre
if($cyl_d<0)
{
  $sph_d+=$cyl_d;
  $cyl_d=$cyl_d*-1;
}
if($cyl_g<0)
{
  $sph_g+=$cyl_g;
  $cyl_g=$cyl_g*-1;
}

// ****** colonnes communes à toutes les requettes
$column="
v.code_article,
v.designation,
v.manufacturer,
v.coating_code,
CONCAT(v.lens_code,'.',v.coating_code) as lens_code,
v.cle_gamme,
v.cle_teinte,
v.cle_traitement,
v.cle_index,
v.cle_material,
'$diam_d' as diam_d,
'$diam_g' as diam_g,
";

if($side==3)
{

  $query="(select 
  $column
  v.code_article as code_article_d,
  v.code_article as code_article_g,
  max(pd.price_2)+v.coating_pv as pv3_d,
  max(pg.price_2)+v.coating_pv as pv3_g
  from offre_verre as v
  left join offre_verre_prix as pd
  on pd.manufacturer=v.manufacturer 
  and pd.lens_code=v.lens_code
  and $diam_d<=pd.diameter
  and $sph_d<=pd.sphere_group_to
  and $cyl_d<=pd.cylinder_group_to
  left join offre_verre_prix as pg
  on pg.manufacturer=v.manufacturer 
  and pg.lens_code=v.lens_code
  and $diam_g<=pg.diameter
  and $sph_g<=pg.sphere_group_to
  and $cyl_g<=pg.cylinder_group_to
  where $diam_d<=v.max_diameter
  and $sph_d>=v.max_sph_from+($cyl_d)*v.max_cyl_from
  and $sph_d<=v.max_sph_to-($cyl_d)*v.max_cyl_to
  and $cyl_d>=v.cyl_from
  and $cyl_d<=v.cyl_to
  and $prism_d<=v.prism_to
  and $add_d>=v.add_from
  and $add_d<=v.add_to
  and $diam_g<=v.max_diameter
  and $sph_g>=v.max_sph_from+($cyl_g)*v.max_cyl_from
  and $sph_g<=v.max_sph_to-($cyl_g)*v.max_cyl_to
  and $cyl_g>=v.cyl_from
  and $cyl_g<=v.cyl_to
  and $prism_g<=v.prism_to
  and $add_g>=v.add_from
  and $add_g<=v.add_to
  and v.lens_code not in ('fleger','fortis') 
  group by lens_code,coating_code  ) 
  union 
  ( select 
  $column 
  v.code_article as code_article_d,
  v2.code_article as code_article_g,
  v.pv3 as pv3_d,
  v2.pv3 as pv3_g
  from offre_verre as v
  inner join offre_verre as v2 on v.lens_code=v2.lens_code
  and $diam_g<=v2.max_diameter
  and $sph_g>=v2.max_sph_from+($cyl_g)*v2.max_cyl_from
  and $sph_g<=v2.max_sph_to-($cyl_g)*v2.max_cyl_to
  and $cyl_g>=v2.cyl_from
  and $cyl_g<=v2.cyl_to
  and $prism_g<=v2.prism_to
  and $add_g>=v2.add_from
  and $add_g<=v2.add_to 
  and v2.lens_code in ('fleger','fortis')
  where $diam_d<=v.max_diameter
  and $sph_d>=v.max_sph_from+($cyl_d)*v.max_cyl_from
  and $sph_d<=v.max_sph_to-($cyl_d)*v.max_cyl_to
  and $cyl_d>=v.cyl_from
  and $cyl_d<=v.cyl_to
  and $prism_d<=v.prism_to
  and $add_d>=v.add_from
  and $add_d<=v.add_to
  and v.lens_code in ('fleger','fortis'))
  ";

}
elseif ($side==1)
{
  
  $query="(select 
  $column
  v.code_article as code_article_d,
  '' as code_article_g,
  max(pd.price_2)+v.coating_pv as pv3_d,
  '' as pv3_g
  from offre_verre as v 
  left join offre_verre_prix as pd
  on pd.manufacturer=v.manufacturer 
  and pd.lens_code=v.lens_code
  and $diam_d<=pd.diameter
  and $sph_d<=pd.sphere_group_to
  and $cyl_d<=pd.cylinder_group_to
  where $diam_d<=v.max_diameter
  and $sph_d>=v.max_sph_from+($cyl_d)*v.max_cyl_from
  and $sph_d<=v.max_sph_to-($cyl_d)*v.max_cyl_to
  and $cyl_d>=v.cyl_from
  and $cyl_d<=v.cyl_to
  and $prism_d<=v.prism_to
  and $add_d>=v.add_from
  and $add_d<=v.add_to
  and v.lens_code not in ('fleger','fortis')
  group by lens_code,coating_code order by designation )
  union 
  ( select 
  $column 
  v.code_article as code_article_d,
  '' as code_article_g,
  v.pv3 as pv3_d,
  '' as pv3_g
  from offre_verre as v
  where $diam_d<=v.max_diameter
  and $sph_d>=v.max_sph_from+($cyl_d)*v.max_cyl_from
  and $sph_d<=v.max_sph_to-($cyl_d)*v.max_cyl_to
  and $cyl_d>=v.cyl_from
  and $cyl_d<=v.cyl_to
  and $prism_d<=v.prism_to
  and $add_d>=v.add_from
  and $add_d<=v.add_to
  and v.lens_code in ('fleger','fortis'))
  
  ";

}
elseif ($side==2)
{

  $query="(select 
  $column
  '' as code_article_d,
  v.code_article as code_article_g,
  '' as pv3_d,
  max(pg.price_2)+v.coating_code as pv3_g
  from offre_verre as v
  left join offre_verre_prix as pg
  on pg.manufacturer=v.manufacturer 
  and pg.lens_code=v.lens_code
  and $diam_g<=pg.diameter
  and $sph_g<=pg.sphere_group_to
  and $cyl_g<=pg.cylinder_group_to
  where $diam_g<=v.max_diameter
  and $sph_g>=v.max_sph_from+($cyl_g)*v.max_cyl_from
  and $sph_g<=v.max_sph_to-($cyl_g)*v.max_cyl_to
  and $cyl_g>=v.cyl_from
  and $cyl_g<=v.cyl_to
  and $prism_g<=v.prism_to
  and $add_g>=v.add_from
  and $add_g<=v.add_to
  and v.lens_code not in ('fleger','fortis')
  group by lens_code,coating_code order by designation )
  union 
  ( select 
  $column 
  '' as code_article_d,
  v.code_article as code_article_g,
  '' as pv3_d,
  v.pv3 as pv3_g
  from offre_verre as v
  where $diam_d<=v.max_diameter
  and $sph_g>=v.max_sph_from+($cyl_g)*v.max_cyl_from
  and $sph_g<=v.max_sph_to-($cyl_g)*v.max_cyl_to
  and $cyl_g>=v.cyl_from
  and $cyl_g<=v.cyl_to
  and $prism_g<=v.prism_to
  and $add_g>=v.add_from
  and $add_g<=v.add_to
  and v.lens_code in ('fleger','fortis'))";
  
}
else die("erreur");

//echo "<p>".$query."</p>";

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
$return->side=$side;
echo JSON_encode($return, JSON_NUMERIC_CHECK);


?>
