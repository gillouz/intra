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


//print_r($user);

$return=(object) array();

$return->start=microtime();

/*$query="
select 
date_format(
	adddate(
		date_prevue,
		if(
			date_format(date_prevue,'%w')=0,
			1,
			if(
				date_format(date_prevue,'%w')=6,
				-1,
				0
			)
		)
	)
	,'%W %e %M'
)
as jour, 
count(*) as nombre 
from tmp_equipment 
where date_prevue>=curdate()
and date_verre_recu is null 
and code_flux in ( 503,603,712,800 ) 
group by jour;
";*/

$query="
select 
cal.jour as jour,
dc.nombre as client,
dl.nombre as fournisseur
from
( select date_format(date_prevue,'%W %e %M') as jour, date_prevue as dt from tmp_equipment where date_prevue>=curdate() group by jour) as cal
left join
(
select 
date_format(
	if(adddate(date_prevue,-1)<curdate(),
		curdate(),
		adddate(
			adddate(date_prevue,-1),
			if(
				date_format(adddate(date_prevue,-1),'%w')=0,
				-2,
				if(
					date_format(adddate(date_prevue,-1),'%w')=1,
					-3,
					0
				)
			)
		)
	)
	,'%W %e %M'
)
as jour, 
date_prevue as dt,
sum(qt_commande) as nombre 
from tmp_equipment 
where date_verre_recu is null 
and code_flux in ( 503,603,712,800 ) 
group by jour) as dc
on dc.jour=cal.jour
left join
(
select 
date_format(
	if(fournisseur_date_prevue<curdate(),
		curdate(),
		adddate(
			fournisseur_date_prevue,
			if(
				date_format(fournisseur_date_prevue,'%w')=0,
				1,
				if(
					date_format(fournisseur_date_prevue,'%w')=6,
					-1,
					0
				)
			)
		)
	)
	,'%W %e %M'
)
as jour, 
fournisseur_date_prevue as dt,
sum(qt_commande) as nombre 
from tmp_equipment 
where date_verre_recu is null 
and code_flux in ( 503,603,712,800 ) 
group by jour) as dl
on dl.jour=cal.jour order by cal.dt;";



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
$return->status="SUCCESS"; //$query; //
$return->data=$result;
echo JSON_encode($return,JSON_NUMERIC_CHECK); //JSON_NUMERIC_CHECK




?>
