<?php //idx//Devis//
session_start();
include("lib_1.php");
validate();
include("devis-lib_main.php");

$nr_client="";
$nr_ordonnance="";
$nr_devis="";

if(isset($_GET['NR_CLIENT'])) $nr_client = htmlspecialchars($_GET['NR_CLIENT'],ENT_QUOTES,'ISO-8859-1'); 
if(isset($_GET['NR_ORDONNANCE'])) $nr_ordonnance = htmlspecialchars($_GET['NR_ORDONNANCE'],ENT_QUOTES,'ISO-8859-1'); 
if(isset($_GET['NR_DEVIS'])) $nr_devis = htmlspecialchars($_GET['NR_DEVIS'],ENT_QUOTES,'ISO-8859-1'); 

// header
echo header_display();


echo "<div id='print_show_1' style='height:200px;display:none'></div>";


// print hide
//echo "<div id='print_hide_1'>";

//Navbar
echo navbar(0);

// alert
echo alertmsg();

echo "<div class='container'>";


//recherche du client
echo client_find2();

// Choose usage and display ordo 
echo "
<div class='row'>
  <div class='form-group col-md-4'>
    <label class='control-label' >".lbl("usage")."</label>
    <select class='form-control' onchange='ordo_usage(this.value)'>
      <option value='1'>".lbl("far")."</option>
      <option value='2'>".lbl("near")."</option>
      <option value='0' selected='selected'>".lbl("both")."</option>
      <option value='4'>".lbl("deg")."</option>
    </select>
  </div>
  <div class='form-group col-md-6'></div>
  <div class='form-group col-md-2 '>
    <label class='control-label' >&nbsp;</label>
    <button  type='button' class='form-control btn btn-default' onclick='$(\"#ordo\").slideToggle();' >".lbl("show_ordo")."</button>
  </div>
</div>
";

//Afficher l'ordonnance si nescessaire
echo "<div id='ordo' style='display:none'>";
echo "<hr>";
echo ordo("ordo");
echo "</div>";

// ordo proposal list
$modal_ordo_find="<div id='ordo_list_div' ></div>";
echo modal("ordo_popup",lbl("find_ordo"),$modal_ordo_find,"ordo_create");

echo "<hr>";

echo "
<div class='text-center'>
<form name='devis' onsubmit='return on_submit();'  >

<div class='form-group col-md-2' >
<label class='control-label' >".lbl("frame")."</label>
<input name='frame_code'  class='form-control' type='text' value='' onchange='frame_find_one(this.value)' >
</div>

<div class='form-group col-md-2'>
<label class='control-label' >".lbl("diameter")."</label>
<input name='diam'  class='form-control' type='text' value='80' onchange=''>
</div>

<div class='form-group col-md-2'>
<label class='control-label' >&nbsp;</label>
<button  type='button' class='form-control btn btn-primary' onclick=' lens_find(\"ordo\",1); ' >".lbl("lens_right")."</button>
</div>

<div class='form-group col-md-2'>
<label class='control-label' >&nbsp;</label>
<button  type='button' class='form-control btn btn-primary' onclick=' lens_find(\"ordo\",2); ' >".lbl("lens_left")."</button>
</div>

<div class='form-group col-md-2'>
<label class='control-label' >&nbsp;</label>
<button  type='button' class='form-control btn btn-primary' onclick=' lens_option_find(); ' >".lbl("lens_option")."</button>
</div>

<div class='form-group col-md-2' >
<label class='control-label' >".lbl("divers")."</label>
<input name='divers_code'  class='form-control' type='text' value='' onchange='article_find_one(this.value)' >
</div>

</form>
</div>


";

/*
<div class='form-group col-md-1'>
<label class='control-label' >&nbsp;</label>
<button  type='button' class='form-control btn btn-primary' onclick=' lens_find(\"ordo\",3); ' >".lbl("lens_both")."</button>
</div>*/

echo "<div style='clear:both'></div>";
echo "</div>"; //container 
//echo "</div>"; //print_hide_1 

// listes du devis
echo "<div id='devis_line_div' class='container'></div>";


echo "<!-- Save Buttons -->
<div class='container'>
  <div class='form-group col-md-2'>
    <label class='control-label' >&nbsp;</label>
    <button  type='button' class='form-control btn btn-primary' onclick='devis_save(\"email\"); ' >".lbl("devis_save_email")."</button>
  </div>
  <div class='form-group col-md-2'>
    <label class='control-label' >&nbsp;</label>
    <button  type='button' class='form-control btn btn-primary' onclick='devis_save(\"print\"); ' >".lbl("devis_save_print")."</button>
  </div>
</div>"; 

// fin du formulaire devis
//echo "</form>";

// Dialog modal de recherche des verres
$modal_find_lens="

<form name='lens_filter_form' onsubmit='return on_submit();'>
<div class='row'>

<div class='form-group col-md-3'>
<label class='control-label' >".lbl("gamme")."</label>
<select class='form-control' onchange='lens_filter()' name='cle_gamme'>
  <option value=''>".lbl("all")."</option>
  <option value='STK'>".lbl("STK")."</option>
  <option value='ECO'>".lbl("ECO")."</option>
  <option value='SME'>".lbl("SME")."</option>
  <option value='PRS'>".lbl("PRS")."</option>
  <option value='IND'>".lbl("IND")."</option>
  <option value='GNC'>".lbl("GNC")."</option>
</select>
</div>

<div class='form-group col-md-3'>
<label class='control-label' >".lbl("index")."</label>
<select class='form-control' onchange='lens_filter()' name='cle_index'>
  <option value=''>".lbl("all")."</option>
  <option value='STD'>".lbl("STD")."</option>
  <option value='AMI'>".lbl("AMI")."</option>
  <option value='SFN'>".lbl("SFN")."</option>
  <option value='XFN'>".lbl("XFN")."</option>
  <option value='INC'>".lbl("INC")."</option>
</select>
</div>


<div class='form-group col-md-3'>
<label class='control-label' >".lbl("traitement")."</label>
<select class='form-control' onchange='lens_filter()' name='cle_traitement'>
  <option value=''>".lbl("all")."</option>
  <option value='STR'>".lbl("STR")."</option>
  <option value='DUR'>".lbl("DUR")."</option>
  <option value='SAR'>".lbl("SAR")."</option>
  <option value='CLN'>".lbl("CLN")."</option>
  <option value='MCL'>".lbl("MCL")."</option>
  <option value='BLU'>".lbl("BLU")."</option>
  <option value='ARI'>".lbl("ARI")."</option>
</select>
</div>

<div class='form-group col-md-3'>
<label class='control-label' >".lbl("teinte")."</label>
<select class='form-control' onchange='lens_filter()' name='cle_teinte'>
  <option value=''>".lbl("all")."</option>
  <option value='BLC'>".lbl("BLC")."</option>
  <option value='TNT'>".lbl("TNT")."</option>
  <option value='PHO'>".lbl("PHO")."</option>
  <option value='POL'>".lbl("POL")."</option>
  <option value='PPO'>".lbl("PPO")."</option>
</select>
</div>

<div class='form-group col-md-3'>
<label class='control-label' >".lbl("manufacturer")."</label>
<select class='form-control' onchange='lens_filter()' name='manufacturer'>
  <option value=''>".lbl("all")."</option>
  <option value='ESS'>Essilor</option>
  <option value='NOV'>Novacel</option>
  <option value='SWC' selected='selected'>SwissLens</option>
  <option value='BRD'>Berdoz</option>
</select>
</div>

<div class='form-group col-md-6'>
<label class='control-label' >".lbl("designation")."</label>
<input name='designation'  class='form-control' type='text' value='' onchange='lens_filter()'>
</div>

<div class='form-group col-md-3'>
<label class='control-label' >&nbsp;</label>
<button  type='button' class='form-control btn btn-default' onclick=' document.lens_filter_form.reset(); lens_filter()' >".lbl("clear_filter")."</button>
</div>

<input name='lens_code'  class='form-control' type='text' value='' style='display:none;'>

</div>
</form>


<div id='find_lens_list' ></div>";

echo modal("lens_popup",lbl("find_lens"),$modal_find_lens,"");

/*
// client popup    
$structure=array(
[ "name"=>"NR_CLIENT", "label"=>"Numero client", "type"=>"hidden" ],
[ "name"=>"NOM", "label"=>"Nom", "type"=>"text", "pattern"=>"^[a-zA-Z']*$", "required"=>true ],
[ "name"=>"PRENOM", "label"=>"Prénom", "type"=>"text","pattern"=>"^[a-zA-Z']*$", "required"=>true ],
[ "name"=>"DATE_NAISSANCE", "label"=>"Date de naissance", "type"=>"text","pattern"=>"^[0-9]{2}\.[0-9]{2}\.[0-9]{4}$", "required"=>true ],
[ "name"=>"TELEPHONE_1", "label"=>"Numero de téléphone", "type"=>"text" ],
[ "name"=>"TELEPHONE_PORTABLE", "label"=>"Numero de mobile", "type"=>"text" ],
[ "name"=>"ADRESSE_EMAIL", "label"=>"Email", "type"=>"text" ],
[ "name"=>"ACCEPT_EMAIL", "label"=>"Accept mailing", "type"=>"checkbox" ]
);

$action=array(); //[  "label"=>"valider", "function"=>""  ] );
$modal_client_edit=json_to_form($structure,$action,"col-md-6","client_edit_form");
echo modal("client_edit_popup","$lbl->lbl_edit",$modal_client_edit,"client_confirm_create(document.client_edit_form);");

*/


// init
echo "
<script>

var nr_ordonnance='$nr_ordonnance';
var nr_client='$nr_client';
var nr_devis='$nr_devis';

//devis_init(nr_devis);

switch(true)
{
  case nr_client!='':
    client_find(nr_client);
  case nr_ordonnance!='':
    ordo_find_one(nr_ordonnance);
    break;
  case nr_devis!='':
    devis_find_one(nr_devis);
}

</script>";

echo footer_display();

echo "</div>";

?>
