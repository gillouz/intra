<?php //idx//Devis//
session_start();
include("nanofw/nano.php");
nano\init("webservice");

header('Content-type: text/html; charset=utf-8'); 

//$find="";
//$what="code";
$nr_client=0;
$presbyte=0;

// prendre les données dans les paramètres
if(isset($_POST['nr_client'])) $nr_client = htmlspecialchars($_POST['nr_client'],ENT_QUOTES,'ISO-8859-1');
if(isset($_POST['presbyte'])) $presbyte = htmlspecialchars($_POST['presbyte'],ENT_QUOTES,'ISO-8859-1');
//if(isset($_POST['what'])) $what = htmlspecialchars($_POST['what'],ENT_QUOTES,'ISO-8859-1');


$return=(object) array();

$return->start=microtime();

$query="
select 
p._".$lang."_name, 
sum(c.frequency) occure 
from client_activity  as  c 
join activity as a on c.activity=a._id 
join proposal as p on a.proposal like concat('%',p._id,'%') 
where c.nr_client=$nr_client 
and p.presbyte<=$presbyte
group by p._".$lang."_name
order by sum(c.frequency) desc;";


$database=$databases["nano"];

try 
{
  
  $conn = new PDO($database["dsn"], $database["user"], $database["password"]);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $conn->exec("SET CHARACTER SET utf8");
  $conn->exec("SET lc_time_names =".$languages[$lang]["sql_format"]);      
  $conn->exec("use ".$database["dbname"]);
  
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
echo JSON_encode($return,JSON_NUMERIC_CHECK); //JSON_NUMERIC_CHECK




?>
