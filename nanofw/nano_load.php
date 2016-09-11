<?php
session_start();
include(__DIR__."/nano.php");
nano\init("webservice");

header('Content-type: text/html; charset=utf-8'); 

$query="";
$schema="";

$return=(object) array();
$return->start=microtime();

// get datas
//if(isset($_GET['query'])) $query = $_GET['query']; 
//if(isset($_GET['schema'])) $schema = $_GET['schema']; 
if(isset($_POST['query'])) $query = $_POST['query']; 
if(isset($_POST['schema'])) $schema = $_POST['schema']; 


if(!isset($schemas->{$schema})) die(nano\error("The schema $schema does not exist",-1));

// connect to database
$load=new nano\data(false);
$load->connect($schemas->{$schema});
$load->create($schemas->{$schema});

// check is the query is ok
try
{
  $query=json_decode($query,TRUE);  
}
catch (Exception $e) 
{
   die(nano\error("Error in json","-1"));
}


$return->fin=microtime();
$return->code="1";
$return->message="";
$return->status="SUCCESS";
$return->data=$load->select($query,$schemas->{$schema});

//print_r($return);

echo JSON_encode($return, JSON_NUMERIC_CHECK);

?>
