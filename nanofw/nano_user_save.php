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
  die(return_error("Error in json","-1"));
}

//print_r($data);

$safe_data=(object) array();

$safe_data->_id=$data[0]->_id;
$safe_data->name=$data[0]->name;
$safe_data->adresse=$data[0]->adresse;
$safe_data->zip=$data[0]->zip;
$safe_data->city=$data[0]->city;
$safe_data->phone=$data[0]->phone;
$safe_data->fax=$data[0]->fax;
$safe_data->email=$data[0]->email;
$safe_data->password=$data[0]->password;

//print_r($safe_data);

$save=new nano\data(true);
$save->connect($schemas->{$schema});
$save->create($schemas->{$schema});

$return->fin=microtime();
$return->code="1";
$return->message="";
$return->status="SUCCESS";
$return->data=$save->upsert($schemas->{$schema},[$safe_data]); //dataset



echo JSON_encode($return, JSON_NUMERIC_CHECK);


?>
