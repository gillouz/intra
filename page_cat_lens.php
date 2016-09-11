<?php //idx//Devis//
session_start();
include("nanofw/nano.php");
nano\init("page");

// Header
echo nano\header();

// Navbar
echo nano\navbar(0);



$cle_type="PRO";
$cle_type_query="";
if(isset($_GET['cle_type'])) $cle_type = htmlspecialchars($_GET['cle_type'],ENT_QUOTES,'ISO-8859-1'); 
if($cle_type!="") $cle_type_query=" and v.cle_type='$cle_type' ";

$cle_teinte="BLC";
$cle_teinte_query="";
if(isset($_GET['cle_teinte'])) $cle_teinte = htmlspecialchars($_GET['cle_teinte'],ENT_QUOTES,'ISO-8859-1'); 
if($cle_teinte!="") $cle_teinte_query=" and v.cle_teinte='$cle_teinte' ";

$cle_material="ORG";
$cle_material_query="";
if(isset($_GET['cle_material'])) $cle_material = htmlspecialchars($_GET['cle_material'],ENT_QUOTES,'ISO-8859-1'); 
if($cle_material!="") $cle_material_query=" and v.cle_material='$cle_material' ";

$cle_index="";
$cle_index_query="";
if(isset($_GET['cle_index'])) $cle_index = htmlspecialchars($_GET['cle_index'],ENT_QUOTES,'ISO-8859-1'); 
if($cle_index!="") $cle_index_query=" and v.cle_index='$cle_index' ";

$manufacturer="OFFICIEL";
$manufacturer_query=" and est_officiel=1 ";
$est_officel_selected="selected";
if(isset($_GET['manufacturer'])) $manufacturer = htmlspecialchars($_GET['manufacturer'],ENT_QUOTES,'ISO-8859-1'); 
if( $manufacturer!="OFFICIEL" )
{
  $manufacturer_query=" and v.manufacturer like '%$manufacturer%'";
  $est_officel_selected="";
}


$designation="";
$designation_query="";
if(isset($_GET['designation'])) 
{
  $designation = htmlspecialchars($_GET['designation'],ENT_QUOTES,'ISO-8859-1');

  foreach(explode(" ",$designation) as $part)
  {
    if(substr($part,0,1)=="-")
    {
      $designation_query.=" and designation not like '%".substr($part,1)."%' ";
    }
    else
    {
      $designation_query.=" and designation like '%$part%' ";
    }
  }
  
  $cle_type="";
  $cle_teinte="";
  $cle_material="";
  $manufacturer="";
  
  $cle_type_query="";
  $cle_teinte_query="";
  $cle_material_query="";
  $manufacturer_query="";
  
}



if($cle_type=="PRO")
{

  $order_by="
  v.ordre_gamme desc,
  v.lens_index desc,
  v.ordre_traitement desc,
  pv,
  v.cle_type,
  v.cle_teinte,
  v.cle_material,
  v.manufacturer
  ";

}
else
{

  $order_by="
  v.lens_index desc,
  v.ordre_gamme desc,
  v.ordre_traitement desc,
  pv,
  v.cle_type,
  v.cle_teinte,
  v.cle_material,
  v.manufacturer
  ";

}



$query="
select 
v.cle_type as cle_type,
v.cle_material,
v.cle_gamme,
v.cle_index,
v.cle_teinte as cle_teinte,
v.cle_traitement,
v.manufacturer,
v.brief_designation,
v.designation,
v.lens_code,
v.coating_code,
v.lens_index,
min(v.pv_min + v.coating_pv) as pv,
max(v.pv_max + v.coating_pv) as pv_max
from offre_verre as v
where 1=1
$cle_type_query
$cle_teinte_query
$cle_material_query
$manufacturer_query
$cle_index_query
$designation_query
group by
v.designation
order by
$order_by
";

//echo $query;

try 
{
  
  $conn = new PDO($databases["bi"]["dsn"], $databases["bi"]["user"], $databases["bi"]["password"]);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $conn->exec("SET CHARACTER SET utf8");
  $conn->exec("use ".$databases["bi"]["dbname"]);
  //$conn->exec("use dwh_dev");
  
  
  $stmt = $conn->prepare($query); 
  $stmt->execute();

  // set the resulting array to associative
  $stmt->setFetchMode(PDO::FETCH_ASSOC); 
  $result = $stmt->fetchAll();
  

    // lens type // max(nom_berdoz) as name,
    $stmt = $conn->prepare("select code_berdoz as value,  max(propriete) as filter from d_propriete_verre where propriete in ('type','matiere','teinte','manufacturer','indice') group by code_berdoz"); 
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    $lens_types = $stmt->fetchAll();
    
    unset($conn);
  
} 
catch (Exception $e) 
{
  die(nano\error($return,$e->getMessage(),"-2"));
}

// open Bootstrap container
echo "<div class='container'>";


echo "
<div class='row hidden-print'>
    <div class='form-group col-lg-2 col-md-4 col-xs-6'>
        <label>".nano\lbl("type")."</label>
        <select class='form-control' name='type' onchange='cal_lens_change(this);' >".nano\selectOptions($lens_types,$cle_type,"lens_type_",true,"type")."
        </select>
    </div>
    <div class='form-group col-lg-2 col-md-4 col-xs-6'>
        <label>".nano\lbl("teinte")."</label>
        <select class='form-control' name='teinte' onchange='cal_lens_change(this);'>".nano\selectOptions($lens_types,$cle_teinte,"lens_tint_",true,"teinte")."
        </select>
    </div>
    <div class='form-group col-lg-2 col-md-4 col-xs-6'>
        <label>".nano\lbl("manufacturer")."</label>
        <select class='form-control' name='manufacturer' onchange='cal_lens_change(this);'>
        ".nano\selectOptions($lens_types,$manufacturer,"lens_manufacturer_",true,"manufacturer")."
        <option value='OFFICIEL' $est_officel_selected>".nano\lbl("lens_manufacturer_OFFICIEL")."</option>
        </select>
    </div>
    <div class='form-group col-lg-2 col-md-4 col-xs-6'>
        <label>".nano\lbl("material")."</label>
        <select class='form-control' name='material' onchange='cal_lens_change(this);'>".nano\selectOptions($lens_types,$cle_material,"lens_material_",true,"matiere")."
        </select>
    </div>
    <div class='form-group col-lg-2 col-md-4 col-xs-6'>
        <label>".nano\lbl("lens_index")."</label>
        <select class='form-control' name='index' onchange='cal_lens_change(this);'>".nano\selectOptions($lens_types,$cle_index,"lens_index_",true,"indice")."
        </select>
    </div>
    <div class='form-group col-lg-2 col-md-4 col-xs-6'>
        <label>&nbsp;</label>
        <button class='form-control' name='clear' onclick='cal_lens_change(this);'>".nano\lbl("clear")."</select>
    </div>
    <div class='form-group col-md-12 col-xs-12'>
        <label>".nano\lbl("designation")."</label>
        <div class='input-group'>
        <input class='form-control' name='designation' id='designation' onchange='cal_lens_change(this);' value='$designation'>
        <span class='input-group-btn'>
	  <button class='btn btn-default' onclick='document.getElementById(\"designation\").value=\"\"' >".nano\lbl("clear")."</button>
        </span>
        </div>
    </div>
    
<row>";

$html="";
$tile_color=1;
$last_gamme="";
$last_index="";
$first_col="";
echo "<div>";
foreach ($result as $row) 
{
 
    

 
 
    if ($cle_type=="PRO")
    {
	// Big section
	if($row["cle_gamme"]!=$last_gamme) $html.="</div><hr class='col-xs-12'><div class='col-xs-12 col-md-3 tile tile-color-white page-break' ><h1>".nano\lbl("lens_gamme_".$row["cle_gamme"])."</h1></div><div class='col-xs-12 col-md-9' style='float:right'>";  
	$last_gamme=$row["cle_gamme"];  
   
	$first_col="<div class='col-xs-2'>".$row["lens_index"]."</div>";
    
	// Small section
	if($row["cle_index"]!=$last_index) $html.="<div class='row'><hr><h4>".nano\lbl("lens_index_".$row["cle_index"])."</h4></div>";  
	$last_index=$row["cle_index"];
    
    }
    else
    {
	  // Big section
	if($row["cle_index"]!=$last_index) $html.="</div><hr class='col-xs-12'><div class='col-xs-12 col-md-3 tile tile-color-white page-break' ><h1>".nano\lbl("lens_index_".$row["cle_index"])."</h1></div><div class='col-xs-12 col-md-9' style='float:right'>";  
	$last_index=$row["cle_index"];  
    
	$first_col="<div class='col-xs-2'>". nano\lbl("lens_gamme_".$row["cle_gamme"])."</div>";
        
    }
    
    
    $color="alert-success";
    
    switch(true)
    {
    case $row["cle_gamme"]=="SPR":
      $color="alert-orange";
      break;
    case $row["cle_teinte"]!="BLC":
      $color="alert-warning";
      break;
    case $row["cle_type"]=="PRO":
      $color="alert-info";
      break;
    
    }
    
    
    
    $html.="<div class='row $color line-row' onclick='cat_lens_detail(".JSON_encode($row).")'>";
    
    $html.=$first_col;
    
    $html.="<div class='col-xs-1'>".$row["cle_traitement"]."</div>";
    
    $html.="<div class='col-xs-5'>".$row["designation"]."</div>";
    
    $html.="<div class='col-xs-1'>".$row["manufacturer"]."</div>";
    
    if($row["pv"]==$row["pv_max"])
    {
        $html.="<div class='col-xs-3'>".number_format($row["pv"],0)." CHF</div>";
    }
    else
    {
      $html.="<div class='col-xs-3'>".nano\lbl("from")." ".number_format($row["pv"],0)." ".nano\lbl("to")." ".number_format($row["pv_max"],0)." CHF</div>";
    }
    
    $html.="</div>";
    
   
        
    
    $tile_color++;
    if($tile_color==6) $tile_color=1;
}
echo "</div>";

echo $html;


// edit form
echo nano\modal("lens_choose_popup",nano\lbl("lens_cat"),"<div id='lens_div'></div>","");

// Close container
echo "</div>";

echo "
<script>

var cle_type='$cle_type';
var cle_material='$cle_material';
var cle_teinte='$cle_teinte';
var cle_index='$cle_index';
var manufacturer='$manufacturer';

function cal_lens_change(field)
{
    switch(field.name)
    {
    case 'type':
        cle_type=field.value;
        window.location='page_cat_lens.php?cle_type='+cle_type+'&cle_material='+cle_material+'&cle_teinte='+cle_teinte+'&manufacturer='+manufacturer+'&cle_index='+cle_index;
        break;
    case 'material':
        cle_material=field.value;
        window.location='page_cat_lens.php?cle_type='+cle_type+'&cle_material='+cle_material+'&cle_teinte='+cle_teinte+'&manufacturer='+manufacturer+'&cle_index='+cle_index;
        break;
    case 'teinte':
        cle_teinte=field.value;
        window.location='page_cat_lens.php?cle_type='+cle_type+'&cle_material='+cle_material+'&cle_teinte='+cle_teinte+'&manufacturer='+manufacturer+'&cle_index='+cle_index;
        break;
    case 'manufacturer':
        manufacturer=field.value;
        window.location='page_cat_lens.php?cle_type='+cle_type+'&cle_material='+cle_material+'&cle_teinte='+cle_teinte+'&manufacturer='+manufacturer+'&cle_index='+cle_index;
        break;
    case 'index':
        cle_index=field.value;
        window.location='page_cat_lens.php?cle_type='+cle_type+'&cle_material='+cle_material+'&cle_teinte='+cle_teinte+'&manufacturer='+manufacturer+'&cle_index='+cle_index;
        break;
    case 'clear':
        window.location='page_cat_lens.php?';
        break;
    case 'designation':
	window.location='page_cat_lens.php?designation='+field.value;
	break;
    }

    

}

function cat_lens_detail(param)
{

    var callback=function (reply)
    {
        /*schemas.lens.data=reply;
        schemas.lens.ix=-1
    
        var param=
        {
            'onclick':'',
            'multiselect':false
        }
        
        $('#lens_div').html(nano.table(schemas.lens,schemas.lens.data,param));
        $('#lens_choose_popup').modal('show');*/
        
        
        $('#lens_div').html(reply);
        $('#lens_choose_popup').modal('show');
        
    };

    //console.log(param);

    var data='param='+encodeURIComponent(JSON.stringify(param));
    nano.ajax(data,'ajax_find_cat_lens.php?',callback); 

}


</script>";



// Footer
echo nano\footer();




?>
