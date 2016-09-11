<?php //idx//Devis//
session_start();
include("lib_1.php");
//validate("");
include("devis-lib_main.php");

$schema="commandes_mag";

// Header
echo header_display();

// Navbar
echo navbar(0);

echo "<div class='container-fluid'>";

/*


<div class='row'>
  <div class='btn-group col-xs-12' role='group' aria-label=''>
    <button class='btn btn-primary col-xs-2' type='button' onclick='easy_find({\"ok_monture\":{\"\$ne\":0}})'>".lbl("Récéption atelier")."</button>
    <button class='btn btn-primary col-xs-2' type='button' onclick='easy_find({\"ok_verre\":{\"\$ne\":0}})'>".lbl("Récéption verres")."</button>
    <button class='btn btn-primary col-xs-2' type='button' onclick='easy_find({\"ok_montage\":{\"\$ne\":0}})'>".lbl("Montage")."</button>
    <button class='btn btn-primary col-xs-2' type='button' onclick='easy_find({\"ok_controle\":{\"\$ne\":0}})'>".lbl("Contrôle")."</button>
    <button class='btn btn-primary col-xs-2' type='button' onclick='easy_find({\"ok_expedition\":{\"\$ne\":0}})'>".lbl("Expedition")."</button>
    <button class='btn btn-primary col-xs-2' type='button' onclick='easy_find({\"ok_magasin\":{\"\$ne\":0},\"magasin\":{\"\$eq\":\"".$user."\"}})'>".lbl("Récéption magasin")."</button>
  </div>
</div>
<div class='row'>

*/

echo "  
<br>
<form class='form-inline'>
  <div class='form-group'>
    <label for='exampleInputName2'>&nbsp;&nbsp;&nbsp;&nbsp;</label>
    <input type='text' class='form-control' placeholder='equipement' onchange='easy_find({\"nr_equipement\":{\"\$eq\":this.value}}); this.value=\"\"'>
  </div>
  <div class='form-group'>
    <label for='exampleInputEmail2'>&nbsp;</label>
    <input type='email' class='form-control' placeholder='commande' onchange='easy_find({\"nr_commande\":{\"\$eq\":this.value}}); this.value=\"\"'>
  </div>
  <div class='form-group'>
    <label for='exampleInputEmail2'>&nbsp;</label>
    <input type='email' class='form-control' placeholder='peniche' onchange='easy_find({\"nr_peniche\":{\"\$eq\":this.value}}); this.value=\"\"'>
  </div>
   <div class='form-group'>
    <label for='exampleInputEmail2'>&nbsp;</label>
    <input type='email' class='form-control' placeholder='client' onchange='easy_find({\"nr_client\":{\"\$eq\":this.value}}); this.value=\"\"'>
  </div>
</form>
</div>
";


echo "
<script>

function easy_find(query)
{
  nano_load('$schema',query,".$schema."_display)
  $('#".$schema."_find_popup').modal('hide');
}

</script>
";

//$user="01VD";

$param=
[
"default_query"=>"{\"ok_fin\":{\"\$eq\":0},\"magasin\":{\"\$eq\":\"".$user."\"},\"code_article_d\":{\"\$nin\":[\"AV\"]}}",
"show_buttons"=>false,
"show_filters"=>false
];

//".$user."

echo "<br>";

echo nano_editor($schema,$param);

echo "</div>"; // container

// footer
echo footer_display();


?>
