<?php //idx//Devis//
session_start();
include("lib_1.php");
validate();
include("devis-lib_main.php");

$nr_client="";

if(isset($_GET['nr_client'])) $nr_client = htmlspecialchars($_GET['nr_client'],ENT_QUOTES,'ISO-8859-1'); 

echo header_display();
echo navbar(0);
echo "<div class='container'>";
echo client_find2();
echo "<div id='display_all' style='display:none;'>"; // permet de masquer tout tant que le client n'est pas choisi

//echo "<hr>";
//echo ordo("ordo");

// tabs

echo "<ul class='nav nav-tabs'>
  <li  class='active'><a data-toggle='tab' href='#devis_list_tab'>Devis</a></li>
  <li><a data-toggle='tab' href='#mesure_list_tab'>Mesures</a></li>
  <li><a data-toggle='tab' href='#ordo_list_tab'>Ordonnance</a></li>
</ul>";

// liste des devis

/*echo '
<div class="container">
  <h2>Dynamic Tabs</h2>
  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#home">Home</a></li>
    <li><a data-toggle="tab" href="#menu1">Menu 1</a></li>
    <li><a data-toggle="tab" href="#menu2">Menu 2</a></li>
    <li><a data-toggle="tab" href="#menu3">Menu 3</a></li>
  </ul>

  <div class="tab-content">
    <div id="home" class="tab-pane fade in active">
      <h3>HOME</h3>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
    </div>
    <div id="menu1" class="tab-pane fade">
      <h3>Menu 1</h3>
      <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
    </div>
    <div id="menu2" class="tab-pane fade">
      <h3>Menu 2</h3>
      <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
    </div>
    <div id="menu3" class="tab-pane fade">
      <h3>Menu 3</h3>
      <p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
    </div>
  </div>
</div>';*/

echo "<div class='tab-content'>";

echo "<!-- liste des devis -->
<div id='devis_list_tab' class='tab-pane fade in active'>
  <div class='row'>
    <div class='form-group col-md-3'>
      <label class='control-label'>&nbsp;</label>
      <button class='form-control btn btn-primary' type='button' onclick='devis_new();'>".lbl("offer_new")."</button>
    </div>
  </div>
  <div id='devis_list_div'></div>
</div>
";

// list des mesures

echo "<!-- liste des mesures -->
<div id='mesure_list_tab' class='tab-pane fade'>
  <div class='row'>
    <div class='form-group col-md-3'>
      <label class='control-label'>&nbsp;</label>
      <button class='form-control btn btn-primary' type='button' onclick='mesure_new();'>".lbl("mesure_new")."</button>
    </div>
  </div>
  <div id='mesure_list_div'></div>
</div>
";
  
// liste des ordonnances

echo "<!-- liste des ordo -->
<div id='ordo_list_tab' class='tab-pane fade'>
  <div class='row'>
    <div class='form-group col-md-3'>
      <label class='control-label'>&nbsp;</label>
      <button class='form-control btn btn-primary' type='button' onclick='ordo_new();'>".lbl("ordo_new")."</button>
    </div>
  </div>
  <div id='ordo_list_div'></div>
</div>
";

echo "</div>"; // tab-content
  
echo "</div>"; //display all

// init
echo "
<script>

var nr_client='$nr_client';

// init tabs
$('#myTabs a').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
})


switch(true)
{
  case nr_client!='':
    client_find(nr_client);
    break;
}

</script>";

echo footer_display();




echo "</div>";


?>
