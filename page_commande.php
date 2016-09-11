<?php //idx//Devis//
session_start();
include("nanofw/nano.php");
nano\init("page");

// header
echo nano\header();

$param=[ "index"=>"http://192.168.12.219/portail/index.php" ];

//Navbar
echo nano\navbar($param);

echo "<div class='container'>";

echo "<h1>".nano\lbl("commandes_atelier")."</h1>";

$quick_help="";

switch($lang)
{
case "fr": 
  echo "<div id='alert-code-art' class='alert alert-warning alert-dismissible' role='alert' ><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>";
  echo "<p class='form-control-static'>Cette page vous sert a faire des commandes à l'atelier central Attention! contrôlez bien la disponibilité des produits surtout pour le SAV</p>";
  echo "<p class='form-control-static'>Le suivi de vos commandes se fait ici:<a href=\"http://192.168.12.219/portail/tb-find_bin.php?q_id=149\"><strong> Suivi des commandes </strong></a></p>";
  echo "<p class='form-control-static'>La <strong>saisie rapide</strong> permet de commander les consomables les plus courrants dans les quantités standard du fourisseur.</p>";
  echo "</div>";
  break;
case "de":
  echo "<p></p>";
  $quick_help="";
  break;
}

$article_quick_list=
[
  "X70917"=>[ "type"=>"Divers", "marque"=>"Divers",  "designation"=>"Etui Berdoz mixte" , "quantite"=>"120"  ],
  "X50257"=>[ "type"=>"Divers", "marque"=>"Divers",  "designation"=>"Etui Berdoz sun black" , "quantite"=>"100"  ],
  "0040415"=>[ "type"=>"Divers", "marque"=>"Divers",  "designation"=>"Microfibres Berdoz" , "quantite"=>"100"  ],
  "0030399"=>[ "type"=>"Divers", "marque"=>"Divers",  "designation"=>"Etui souple microfibres Berdoz" , "quantite"=>"50"  ],
  "KNB"=>[ "type"=>"Divers", "marque"=>"Divers",  "designation"=>"Kit nettoyage Berdoz" , "quantite"=>"12"  ],
  "SACBP"=>[ "type"=>"Divers", "marque"=>"Divers",  "designation"=>"Sac Berdoz Sonix petits" , "quantite"=>"200"  ],
  "SACBG"=>[ "type"=>"Divers", "marque"=>"Divers",  "designation"=>"Sac Berdoz Sonix grands" , "quantite"=>"300"  ],
  "FANTI"=>[ "type"=>"Divers", "marque"=>"Divers",  "designation"=>"Feuille Anti-vol" , "quantite"=>"108"  ],
  "ANTIVB"=>[ "type"=>"Divers", "marque"=>"Divers",  "designation"=>"Anti-vol avec bride" , "quantite"=>""  ],
];

$article_quick_option="<option value=''>".nano\lbl("choose a value")."</option>";;
foreach($article_quick_list as $k=>$l)
{
  $article_quick_option.="<option value='".$k."' >".$l["designation"]."</option>";
}




echo "<!-- aide a la saisie -->
<div class='row'>
  <div class='col-xs-2'>
    <div class='form-group'>
      <label for='comm_type_article'>".nano\lbl("saisie_rapide")."</label>
      <select type='select' class='form-control' name='comm_article' id='comm_article' onchange='article_quick_choose(this);'>
      $article_quick_option
      </select>
    </div>
  </div>
</div>
<div class='row'>
</div>

";



echo nano\bulkInsert("nano_commande",[]);


echo "</div>";

echo "<script>

var article_quick_list=".JSON_encode($article_quick_list).";

intra.article.id=document.nano_commande_edit_form.code_article;
intra.article.article_type=document.nano_commande_edit_form.type_article;
intra.article.brand=document.nano_commande_edit_form.marque;
intra.article.detail=document.nano_commande_edit_form.designation;

function article_find_code(field)
{
  intra.article.find(field.value,'code','',1);
}

function article_find_designation(field)
{
  intra.article.find(field.value,'designation','',1);
}

function article_quick_choose(field)
{
  var a=article_quick_list[field.value];

  document.nano_commande_edit_form.type.value='stock';
  document.nano_commande_edit_form.code_article.value=field.value;
  document.nano_commande_edit_form.type_article.value=a.type;
  document.nano_commande_edit_form.marque.value=a.marque;
  document.nano_commande_edit_form.designation.value=a.designation;
  document.nano_commande_edit_form.quantite.value=a.quantite;
  
  nano_commande_add_line();
  
}


</script>";

echo article_selector();



echo nano\footer();



?>
