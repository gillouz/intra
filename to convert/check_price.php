<?php //idx//Devis//Verifier un prix
session_start();
include("lib_1.php");
validate();
include("devis-lib_main.php");


// header
echo header_display("#EEEEEE");

echo "<div class='container-fluid'>";

echo "<!-- rechercher des montures -->
<div class='text-center'>
  <br>
  <form name='devis' onsubmit='return on_submit();'  >
    <div class='col-md-12 col'>
      <div class='input-group'>
        <input type='text' class='form-control' name='frame_code' placeholder='Entrez le code ici...pour avoir le prix' onchange='frame_find_one_check_price(this.value); this.value=\"\"'  >
        <span class='input-group-btn'>
          <button class='btn btn-primary' type='button' onclick=''>".lbl("find")."</button>
        </span>
      </div><!-- /input-group -->
    </div><!-- /.col-lg-6 -->
  </form>
</div>
<br>
<br>
";
echo "</div>"; //container 


echo footer_display();


?>
