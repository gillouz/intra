<?php //idx//Devis//
session_start();
include("nanofw/nano.php");
nano\init("webservice");

//$nr_client="";
$who="";
$where="";

if(isset($_POST['who'])) $who = htmlspecialchars($_POST['who'],ENT_QUOTES,'ISO-8859-1'); 
if(isset($_POST['where'])) $where = htmlspecialchars($_POST['where'],ENT_QUOTES,'ISO-8859-1'); 

if(isset($_GET['who'])) $who = htmlspecialchars($_GET['who'],ENT_QUOTES,'ISO-8859-1'); 
if(isset($_GET['where'])) $where = htmlspecialchars($_GET['where'],ENT_QUOTES,'ISO-8859-1'); 


// Objet de retour
$return=(object) array();
$return->start=microtime();

$who="orl+".$who;

$url="http://tel.search.ch/api/?was=$who&wo=$where&key=fec570d6e42ce58ef6b1304e50648c90";

$peoples=simplexml_load_file($url);


$e=0;
$result=[];
while($entry=$peoples->entry[$e])
{
    $e++;
    $result[]=$entry->children('tel', true);
}


$return->fin=microtime();
$return->code="1";
$return->message="";
$return->status="SUCCESS";
$return->data=$result;

echo JSON_encode($return);

?>                