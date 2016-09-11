<?php
session_start();
include(__DIR__."/nano.php");
nano\init("webservice");

header('Content-type: text/html; charset=utf-8'); 

$data="";
$schema="";

$return=(object) array();
$return->start=microtime();

// prendre les données dans les paramètres
//if(isset($_GET['data'])) $data= $_GET['data']; 
//if(isset($_GET['schema'])) $schema= $_GET['schema']; 
if(isset($_POST['data'])) $data= $_POST['data']; 
if(isset($_POST['schema'])) $schema= $_POST['schema']; 

try
{
  $data=JSON_decode($data);
}
catch(Expression $e)
{
  die(nano\error("Error in json",-1));
}

$save=new nano\data(false);
$save->connect($schemas->{$schema});
$save->create($schemas->{$schema});

$return->fin=microtime();
$return->code="1";
$return->message="";
$return->status="SUCCESS";
$return->data=$save->upsert($schemas->{$schema},$data); //dataset

echo JSON_encode($return, JSON_NUMERIC_CHECK);


?>
