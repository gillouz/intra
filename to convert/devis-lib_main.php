<?php

include(__DIR__."/nanofw/nano.php"); 

// ********* Standard building block for prescription

function ordo($ordo_name)
{
$lbl=$GLOBALS['lbl'];
$html="<!-- Ordonnance -->

<div class='row'>
  <div class='form-group col-md-3'>
    <label class='control-label'>&nbsp;</label>
    <button class='form-control btn btn-primary' type='button' onclick='ordo_find(document.ordo);'>".lbl("prescription_find")."</button>
  </div>
</div>
<br>

<form name='$ordo_name'>

<!-- right eye -->
<div class='row'>
<div class='form-group col-xs-2'>
<label class='control-label' >".lbl("sph")."</label>
<input name='SPH_OD'  class='form-control' type='text' pattern='(^[-+][0-9]{1,2}\.?$)|(^[-+][0-9]{1,2}\.(00|25|50|75)$)' value='' >
</div>

<div class='form-group col-xs-2'>
<label class='control-label' >".lbl("cyl")."</label>
<input name='CYL_OD'  class='form-control' type='text' pattern='(^[-][0-9]{1,2}\.?$)|(^[-+][0-9]{1,2}\.(00|25|50|75)$)' value='' >
</div>

<div class='form-group col-xs-2'>
<label class='control-label' >".lbl("axe")."</label>
<input name='AXE_OD'  class='form-control' type='text' pattern='(^[-+][0-9]{1,2}\.?$)|(^[-+][0-9]{1,2}\.(00|25|50|75)$)' value='' >
</div>

<div class='form-group col-xs-2'>
<label class='control-label' >".lbl("add")."</label>
<input name='ADD_OD'  class='form-control' type='text' pattern='(^[-+][0-9]{1,2}\.?$)|(^[-+][0-9]{1,2}\.(00|25|50|75)$)' value='' >
</div>

<div class='form-group col-xs-1'>
<label class='control-label' >".lbl("visus")."</label>
<input name='VISUS_OD'  class='form-control' type='text' pattern='(^[-+][0-9]{1,2}\.?$)|(^[-+][0-9]{1,2}\.(00|25|50|75)$)' value='' >
</div>

<div class='form-group col-xs-1'>
<label class='control-label' >".lbl("dp")."</label>
<input name='ECART_OD'  class='form-control' type='text' pattern='(^[-+][0-9]{1,2}\.?$)|(^[-+][0-9]{1,2}\.(00|25|50|75)$)' value='' >
</div>

<div class='form-group col-xs-2'>
<label class='control-label' >&nbsp;</label>
<button  type='button' class='form-control btn btn-primary' onclick='' >".lbl("copy_to_left")."</button>
</div>


</div>

<!-- left eye -->
<div class='row'>

<div class='form-group col-xs-2'>
<input name='SPH_OG'  class='form-control' type='text' value='' >
</div>

<div class='form-group col-xs-2'>
<input name='CYL_OG'  class='form-control' type='text' value='' >
</div>

<div class='form-group col-xs-2'>
<input name='AXE_OG'  class='form-control' type='text' value='' >
</div>

<div class='form-group col-xs-2'>
<input name='ADD_OG'  class='form-control' type='text' value='' >
</div>

<div class='form-group col-xs-1'>
<input name='VISUS_OG'  class='form-control' type='text' value='' >
</div>

<div class='form-group col-xs-1'>
<input name='ECART_OG'  class='form-control' type='text' value='' >
</div>

<div class='form-group col-xs-2'>
<button  type='button' class='form-control btn btn-primary' onclick='$(\"#prism\").slideToggle();' >".lbl("show_prism")."</button>
</div>


</div>

<div id='prism' style='display:none'>
<!-- right eye prism -->
<div class='row'>

<div class='form-group col-xs-2'>
<label class='control-label' >".lbl("prism")."</label>
<input name='PRISM_OD'  class='form-control' type='text' value='' >
</div>

<div class='form-group col-xs-2'>
<label class='control-label' >".lbl("prism_axe")."</label>
<input name='BASE_OD'  class='form-control' type='text' value='' >
</div>

</div>

<!-- left eye prism -->
<div class='row'>

<div class='form-group col-xs-2'>
<input name='PRISM_OG'  class='form-control' type='text' value='' >
</div>

<div class='form-group col-xs-2'>
<input name='BASE_OG'  class='form-control' type='text' value='' >
</div>

</div>
</div>

</form>";


return $html;
}

// ***** Standard building block for clients

// recherche , affichage et modification des clients
function client_find2()
{
  $schemas=$GLOBALS['schemas'];
  
  //recherche du client
  $html="<!-- client -->
  <div class='row'>
    <div id='client_find'>
      <div class='col-md-4'>
        <label for='LastUpdated' >".lbl("client_find")."</label>
        <div class='input-group'>
          <input type='text' class='form-control' name='comm_reference' placeholder='".lbl("fill_client_name")."' onchange='client_find(this.value); this.value=\"\"' >
          <span class='input-group-btn'>
            <button class='btn btn-primary' type='button' onclick=''>".lbl("find")."</button>
          </span>
        </div>
      </div>
    </div>
    <div id='client_choosed' style='padding:15px;'></div>
  </div>
  <br>";

  // create the form for client edit  
  $action=array(); 
  $modal_client_edit=json_to_form($schemas->client_edit["structure"],$action,"col-md-6","client_edit_form");
  $html.=modal("client_edit_popup",lbl("lbl_edit"),$modal_client_edit,"client_confirm_create(document.client_edit_form);");

  // client proposal
  $modal_client_find="<div id='client_list' ></div>";
  $html.=modal("client_popup",lbl("find_client"),$modal_client_find,"client_create");

  return $html;
}

?>
