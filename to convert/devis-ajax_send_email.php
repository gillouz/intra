<?php //idx//Devis//
session_start();
include("lib_1.php");
validate("agenda");
include("devis-lib_main.php");
require_once('PHPMailer15/PHPMailerAutoload.php');


// Objet de retour
$return=(object) array();
$return->start=microtime();

$nr_devis="";
$client_email="pizzetta.gilles@gmail.com";
$client_genre="M";
$client_name="Pizzetta";
$client_surname="Gilles";

// prendre les données dans les paramètres
if(isset($_GET['nr_devis'])) $nr_devis = $_GET['nr_devis']; 
if(isset($_GET['client_email'])) $client_email = $_GET['client_email']; 
if(isset($_GET['client_genre'])) $client_genre = $_GET['client_genre']; 
if(isset($_GET['client_name'])) $client_name = $_GET['client_name']; 
if(isset($_GET['client_surname'])) $client_surname = $_GET['client_surname']; 


$email = new PHPMailer();

$email->IsSMTP(); // telling the class to use SMTP
$email->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)

// Connexion au SMTP
$email->SMTPAuth   = true;                  // enable SMTP authentication
$email->Host       = "smtp.gmail.com"; // sets the SMTP server
$email->Port       = 587;                    // set the SMTP port for the GMAIL server
$email->Username   = "pizzetta.gilles@gmail.com"; // SMTP account username
$email->Password   = "pzt3141592";  

// message
if ($crnt_email=="") $crnt_email="info@berdozoptic.ch";
$email->From      =  $crnt_email; 
$email->FromName  = "Berdozoptic";


switch ($lang)
{
  case "fr":
    $email->Subject   = "Berdozoptic - Votre devis";
    $email->Body      = "<p>Cher ".lbl($client_genre)." $client_name</p><p>Veuillez trouver ci-joint votre devis</p>";
    break;
  case "de":
    $email->Subject   = "Berdozoptic - Votre devis";
    $email->Body      = "<p>Cher ".lbl($client_genre)." $client_name</p><p>Veuillez trouver ci-joint votre devis</p>";
    break;
  default:
    $email->Subject   = "Berdozoptic - Votre devis";
    $email->Body      = "<p>Cher ".lbl($client_genre)." $client_name</p><p>Veuillez trouver ci-joint votre devis</p>";
    break;
}

$email->IsHTML(true);
$email->AddAddress( $client_email );

$file="http://localhost/portail/devis_pdf.php?nr_devis=$nr_devis";

$string=file_get_contents($file);

//echo $string;

$email->AddStringAttachment($string,'devis.pdf','base64', 'application/pdf');
$email->Send();

$return->fin=microtime();
$return->code="1";
$return->message="";
$return->status="SUCCESS";
$return->data=[];
echo JSON_encode($return);

//echo "{'start':'0','fin':'0','code':'1','message':'','status':'SUCCESS','data':[]}";




?>