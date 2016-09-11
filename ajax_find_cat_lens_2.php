<?php //idx//Devis//
session_start();
include("nanofw/nano.php");
nano\init("webservice");

header('Content-type: text/html; charset=utf-8'); 

$param="";
$html="";

if(isset($_POST['param'])) $param = $_POST['param']; 



// check is the query is ok
try
{
  $param=json_decode($param,TRUE);  
}
catch (Exception $e) 
{
   die(nano\error("Error in json","-1"));
}

// Objet de retour
$return=(object) array();
$return->start=microtime();
$return->type="html";

//$html.=$param["brief_designation"];

if(strtoupper($param["brief_designation"])=="FLEGER" or strtoupper($param["brief_designation"])=="FORTIS")
{

  //$html.="grilles/$param[brief_designation].svg";

  $html.="<img class='img-responsive' src='grilles/".$param[brief_designation].".svg'>";


  $return->fin=microtime();
  $return->code="1";
  $return->message="";
  $return->status="SUCCESS";
  $return->data=$html;
  $return->side=$side;
  echo JSON_encode($return, JSON_NUMERIC_CHECK);

  die;

}



$grid_query="
select 
v.lens_code,
v.coating_code,
v.designation as designation,
v.lens_index as lens_index,
v.max_diameter as  diameter,
v.max_sph_from as max_sph_from,
v.max_sph_to as max_sph_to,
v.add_from as add_from,
v.add_to as add_to,
v.cyl_from cyl_from,
v.cyl_to cyl_to,
v.max_cyl_from as max_cyl_from,
v.max_cyl_to as max_cyl_to,
v.coating_pv as coating_price,
v.manufacturer
from offre_verre as v
where v.lens_code ='$param[lens_code]'
and v.coating_code='$param[coating_code]'
and v.manufacturer='$param[manufacturer]'
and cyl_to>0
order by 
v.designation,
diameter,
add_from

";

$price_query="
select 
p.lens_code,
max(p.diameter) as diameter,
max(p.sphere_group_to) as sphere_group_to,
max(p.cylinder_group_to) as cylinder_group_to,
p.price_2 as price
from offre_verre_prix as p
where p.lens_code ='$param[lens_code]'
and p.manufacturer='$param[manufacturer]'
group by
price_2
order by
price_2

";

$option_query="
select 
o.coating_designation,
o.coating_pv,
o.coating_code,
o.color
from offre_verre_option as o
where o.lens_code ='$param[lens_code]'
and o.manufacturer='$param[manufacturer]'
group by o.coating_code
order by color,coating_designation;
";




try 
{
  
  $conn = new PDO($databases["bi"]["dsn"], $databases["bi"]["user"], $databases["bi"]["password"]);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $conn->exec("SET CHARACTER SET utf8");
  $conn->exec("use ".$databases["bi"]["dbname"]);
  //$conn->exec("use dwh_dev");
  
  // grid
  $stmt = $conn->prepare($grid_query); 
  $stmt->execute();
  $stmt->setFetchMode(PDO::FETCH_ASSOC); 
  $grid = $stmt->fetchAll();
  
  //price
  $stmt = $conn->prepare($price_query); 
  $stmt->execute();
  $stmt->setFetchMode(PDO::FETCH_ASSOC); 
  $price = $stmt->fetchAll();
  
  //options
  $stmt = $conn->prepare($option_query); 
  $stmt->execute();
  $stmt->setFetchMode(PDO::FETCH_ASSOC); 
  $option = $stmt->fetchAll();
  
  unset($conn);

} 
catch (Exception $e) 
{
  die(nano\error($return,$e->getMessage(),"-2"));
}


$coating_price=$grid[0]["coating_price"];

$html.="<div class='container-fluid'>";

$html.="<h3>".$grid[0]["designation"]."</h3>";

$html.="<small>".$grid[0]["lens_code"].".".$grid[0]["coating_code"]."</small>";

//$html.="<div class='row' ><h4>".nano\lbl("price")."</h4>";

$html.="<br><table class='table table-striped'>";

$html.="<tr>
<th style='text-align:center' >".nano\lbl("price")."</th>
<th style='text-align:center' >".nano\lbl("dia_max")."</th>
<th style='text-align:center' >".nano\lbl("sph_max")."</th>
<th style='text-align:center' >".nano\lbl("cyl_max")."</th>
</tr>
";



foreach($price as $res)
{

  $html.="<tr>
  <td style='text-align:center' ><strong>".($res["price"]+$coating_price)."</strong> CHF</td>
  <td style='text-align:center' >$res[diameter] mm</td>
  <td style='text-align:center' >$res[sphere_group_to] dpt</td>
  <td style='text-align:center' >$res[cylinder_group_to] dpt</td>
  </tr>
  ";

}



$html.="</table>";


//$html.="</div>";

$html.="<div class='row'><h4>".nano\lbl("grid")."</h4>";


foreach($grid as $res)
{

  $html.="<div class='col-xs-6 col-md-4 col-lg-3' ><hr>";

  //$html.="<p>$res[lens_code]&nbsp;$res[coating_code]</p>";

  $html.="<p>".nano\lbl("diameter").": <strong>$res[diameter]</strong> mm</p>";

  if($res["add_to"]!=0) $html.="<p>".nano\lbl("addition").": ".nano\lbl("from")." <strong>$res[add_from]</strong> dpt ".nano\lbl("to")." <strong>$res[add_to]</strong> dpt</p>";

  $param="?max_sph_from=".$res["max_sph_from"];
  $param.="&max_sph_to=".$res["max_sph_to"];
  $param.="&cyl_from=".$res["cyl_from"];
  $param.="&cyl_to=".$res["cyl_to"];
  $param.="&max_cyl_from=".$res["max_cyl_from"];
  $param.="&max_cyl_to=".$res["max_cyl_to"];

  if($res["manufacturer"]=="SGL")
  {
    $param.="&cyl=-";
  }
  else
  {
    $param.="&cyl=+";
  }


  $html.="<img src='ajax_grille.php$param'  class='img-responsive' />";

  $html.="</div>";

}

$html.="</div>";

//option

$html.="<br>";

$html.="<div class='row'><h4>".nano\lbl("option")."</h4>";

$tcolor=0;
$color="";
foreach($option as $res)
{

  if($res["color"]!=$color) $html.="<div style='clear:both'><hr><h5>".nano\lbl("cle_option_color_".$res[color])."</h5></div>";
  $color=$res["color"];

  $html.="<div  class='tile-small tile-gray-$tcolor col-xs-6 col-md-4 col-lg-3' ><div class='tile-text'>
  $res[coating_designation] <small>$res[coating_code]</small><br><strong>$res[coating_pv]</strong> CHF</div>
  </div>
  ";

  $tcolor++;
  
  if($tcolor>=5) $tcolor=0;
 
}

$html.="</div>";




//$html.="</table>";





$html.="</div>";

//echo nano\footer();

$return->fin=microtime();
$return->code="1";
$return->message="";
$return->status="SUCCESS";
$return->data=$html;
$return->side=$side;
echo JSON_encode($return, JSON_NUMERIC_CHECK);



?>
