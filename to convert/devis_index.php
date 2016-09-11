<?php //idx//Devis//
session_start();
include("lib_1.php");
validate();
include("devis-lib_main.php");




// header
echo header_display();

//Navbar
echo navbar(0);

echo "<div class='container-fluid'>";

echo "<div id='logo' class='col-md-4 col-md-offset-4' ><br><br><br><img src='devis-img_logo.png' class='img-responsive' alt='Responsive image'><br><br><br></div>'";

//recherche du client
echo "<!-- formulaire client -->
<div class='row'>
  <div id='client_find_index_start'>
    <div class='col-md-4 col-md-offset-4'>
      <div class='input-group'>
        <input type='text' class='form-control' name='comm_reference' placeholder='".lbl("fill_client_name")."' onchange='client_find_index(this.value), this.value=\"\"' >
        <span class='input-group-btn'>
          <button class='btn btn-primary' type='button' onclick=''>".lbl("find")."</button>
        </span>
      </div>
    </div>
  </div>
  
  <div id='client_find_index_after' class='pull-left' style='display:none'>
    <div class='col-xs-1 center-block'>
      <img src='devis-img_logo_small.png' class='img-responsive vcenter' alt='Responsive image'>
    </div>
    <div class='col-xs-4'>
      <div class='input-group'>
        <input type='text' class='form-control' name='comm_reference' placeholder='".lbl("fill_client_name")."' onchange='client_find_index(this.value), this.value=\"\"' >
        <span class='input-group-btn'>
          <button class='btn btn-primary' type='button' onclick=''>".lbl("find")."</button>
        </span>
      </div>
    </div>
  </div>
</div>
<br>";


echo "<div id='client_list' ></div>";


echo footer_display();

echo "</div>";


?>
