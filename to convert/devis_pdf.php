<?php //idx//Devis//
session_start();
include("lib_1.php");
validate("agenda");
include("devis-lib_main.php");
require('fpdf/fpdf.php');
setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
//setlocale (LC_TIME, 'fr_FR.iso885915@euro','fra'); 



//header('Content-Type: application/pdf');

//echo "debug";

$nr_devis="";
$logo=1;

// prendre les données dans les parametres
if(isset($_GET['nr_devis'])) $nr_devis = $_GET['nr_devis']; 
if(isset($_GET['logo'])) $logo = $_GET['logo']; 

/*
$return=(object) array();
$return->start=microtime();
$result=array();
*/

class PDF extends FPDF
{

  //funtion header
  function devis_logo()
  {
    $this->Image('logo.png',65,10,80);
  }
  
  //devis adresse
  function devis_adresse($data)
  {
    $this->Ln(40);
    $this->Cell(100);
    $this->Cell(100,7,ulbl($data->GENRE),0,1);
    $this->Cell(100);
    $this->Cell(100,7,ucvt($data->NOM)." ".ucvt($data->PRENOM),0,1);
    $this->Cell(100);
    $this->Cell(100,7,ucvt($data->ADRESSE_1)." ".ucvt($data->NR_DE_RUE),0,1);
    $this->Cell(100);
    $this->Cell(100,7,ucvt($data->NPA)." ".ucvt($data->LOCALITE),0,1);
    $this->Ln(7);
  }
  
  function devis_ref($data)
  {
    $this->Cell(100);
    $this->Cell(100,7,$data['date'],0,1);
    $this->Ln(20);
    $this->Cell(100,7,"Nr Devis: ".$data['nr_devis'],0,1);
    $this->Cell(100,7,"Nr Client: ".$data['nr_client'],0,1);
    $this->Cell(100,7,ulbl("shop").": ".$data['shop'],0,1);
    $this->Ln(7);
  }
  
    
  // Tableau simple
  function devis_lines($structure, $data)
  {
    $this->SetDrawColor(100,100,100);
    // Entete des colonnes
    $this->SetFont('Arial','B',10);
    foreach($structure as $s)
    {
      $this->Cell($s['width'],7,$s['label'],'B',0,$s['align']);
    }
    $this->Ln();
    // data
    $this->SetFont('Arial','',10);
    foreach($data as $row)
    {
      foreach($structure as $s)
      {
        if($s['translate']==true)
        {
        $this->Cell($s['width'],7,ulbl($row[$s['name']]),0,0,$s['align']);
        }
        else
        {
        $this->Cell($s['width'],7,ucvt($row[$s['name']]),0,0,$s['align']);
        }
      }
      $this->Ln();
    }
    $this->SetFont('Arial','B',10);
    foreach($structure as $s)
    {
      $this->Cell($s['width'],7,$s['total'],0,0,$s['align']);
    }
    $this->Ln(10);
  }

  function devis_info($data)
  {
    $this->SetFont('Arial','B',10);
    $this->MultiCell(0,7,$data['benefices'],0,'J');
    $this->SetFont('Arial','',10);
    $this->MultiCell(0,7,$data['validity'],0,'J');
    $this->MultiCell(0,7,$data['condition'],0,'J');
  }
  
  function devis_accord()
  {
      $this->SetY(-80);
      $this->Cell(100);
      $this->Cell(50,30,ulbl("devis_agreed"),'B',1);
  }
  
  function devis_footer()
  {
      $this->SetFont('Arial','',8);
      $this->Image('footer.jpg',0,280,210);
  }
}


if($nr_devis!="")
{
  
  // create return object
  $return=(object) array();
  $return->start=microtime();
  $result=array();

  $devis=new nano_data();
  $devis->connect($schemas->devis);
  $devis->create($schemas->devis);

  $query=[ "_id"=>$nr_devis ];
  $data=$devis->select($schemas->devis,$query);

  //print_r($data);
  
  $return->fin=microtime();
  $return->code="1";
  $return->message="";
  $return->status="SUCCESS";
  $return->data=$data["data"];
  
  //print_r($data["data"]);
  
  // structure du tableau de donnee
  $structure=array
  (
    ["name"=>"code_article","label"=>ulbl("article_code"),"width"=>20, "align"=>"L","translate"=>false, "total"=>"" ],
    ["name"=>"type_article","label"=>ulbl("article_code"),"width"=>30, "align"=>"L","translate"=>false, "total"=>"" ],
    ["name"=>"marque","label"=>ulbl("brand"),"width"=>30, "align"=>"L","translate"=>false, "total"=>"" ],
    ["name"=>"designation","label"=>ulbl("designation"),"width"=>70, "align"=>"L","translate"=>false, "total"=>"Total CHF"],
    ["name"=>"quantite","label"=>ulbl("quantity"),"width"=>20, "align"=>"C","translate"=>false, "total"=>$return->data[0]['nbr']  ],
    ["name"=>"prix","label"=>ulbl("price"),"width"=>20, "align"=>"C","translate"=>false, "total"=>$return->data[0]['total'] ]
  );
  
  $reference=array
  (
    "date"=>utf8_decode (strftime("%A %d %B %Y.",strtotime($return->data[0]['date_cree']))),
    "nr_devis"=>$nr_devis,
    "nr_client"=>$return->data[0]['nr_client'],
    "shop"=>$return->data[0]['magasin']->{'nom'}." ".$return->data[0]['magasin']->{'tel'}
  );
  
  $info=array
  (
      "validity"=>"", //lbl("validity")." ".date("dd.mm.yyyy",strtotime(devis['date_cree']." + 30 day")),
      "condition"=>"",
      "benefices"=>ulbl("frame").": ".$return->data[0]['frame'].", ".ulbl("lens").": ".$return->data[0]['lens']
  );
  
  $pdf = new PDF();
  $pdf->SetFont('Arial','',10);
  $pdf->AddPage();
  if($logo==1) $pdf->devis_logo();
  $pdf->devis_adresse($return->data[0]['client']);
  $pdf->devis_ref($reference);
  $pdf->devis_lines($structure,$return->data[0]['lines']);
  $pdf->devis_info($info);
  $pdf->devis_accord();
  if($logo==1) $pdf->devis_footer();
  $pdf->Output();
  

}
else
{
  echo "nothing to print";
}

function ulbl($txt)
{
  $lbl=$GLOBALS['lbl'];
  if(isset($lbl[$txt])) return iconv("UTF-8", "ISO-8859-1", $lbl[$txt]); 
  return $txt;
}

function ucvt($txt)
{
  return iconv("UTF-8", "ISO-8859-1", $txt); 
}



?>
