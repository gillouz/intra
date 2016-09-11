<?php

$schemas->concurrent=
[
  "name"=>"concurrent",
  "database"=>"nano",
  "table"=>"concurrent",
  "structure"=>
  [
    [
      "name"=>"name", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("concurrent"), 
      "display"=>["find","list", "form", "div","concat"] 
    ]
  ]
];


$schemas->offre_verre_info=
[
  "name"=>"offre_verre_info",
  "database"=>"bi",
  "table"=>"offre_verre_info",
  "structure"=>
  [
    [
      "name"=>"name", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("lens_name"), 
      "display"=>["find","list", "form", "div","concat"] 
    ],
    [ 
      "name"=>"info_fr",
      "label"=>nano\lbl("lens_informations_fr"), 
      "type"=>"text", 
      "regex"=>"^.*$", 
      "optional"=>true, 
      "col"=>"col-xs-12", 
      "clearall"=>true,
      "display"=>["list","form","find"]
    ],
    [ 
      "name"=>"info_de",
      "label"=>nano\lbl("lens_informations_de"), 
      "type"=>"text", 
      "regex"=>"^.*$", 
      "optional"=>true, 
      "col"=>"col-xs-12", 
      "clearall"=>true,
      "display"=>["list","form","find"]
    ]
  ]
];
    
$schemas->projet=
[
  "name"=>"projet",
  "database"=>"bi",
  "table"=>"d_projet",
  "structure"=>
  [
    [
      "name"=>"name", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("projet"), 
      "display"=>["find","list", "form", "div","concat"] 
    ]
  ]
]; 

$schemas->budget_projet=
[
  "name"=>"budget_projet",
  "database"=>"bi",
  "table"=>"f_budget_projet",
  "structure"=>
  [
    [
      "name"=>"budget", 
      "type"=>"key",
      "value"=>"!!DATA.budget.END!!",
      "schema"=>"budget",
      "regex"=>"^.*$", 
      "optional"=>true, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("facture"), 
      "display"=>["list", "form", "div"] 
    ],
    [
      "name"=>"projet", 
      "type"=>"key",
      "value"=>"",
      "schema"=>"projet",
      "regex"=>"^.*$", 
      "optional"=>true, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("projet"), 
      "display"=>["list", "form", "div"] 
    ],
    [
      "name"=>"montant", 
      "type"=>"float",
      "regex"=>"^.*$", 
      "value"=>"!!DATA.budget.montant_chf.END!!",
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("montant"),
      "display"=>["list", "form", "div","find"]
    ],
    [
      "name"=>"remarque", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "value"=>"",
      "optional"=>true, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("remarque"),
      "display"=>["list", "form", "div","find"]
    ],
  ]
]; 

$schemas->budget_ligne=
[
  "name"=>"budget_ligne",
  "database"=>"bi",
  "table"=>"f_budget_ligne",
  "structure"=>
  [
    [
      "name"=>"budget_id", 
      "type"=>"key",
      "value"=>"!!DATA.budget.END!!",
      "schema"=>"budget",
      "regex"=>"^.*$", 
      "optional"=>true, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("facture"), 
      "display"=>["list", "form", "div"] 
    ],
    [
      "name"=>"montant_chf", 
      "type"=>"float",
      "regex"=>"^.*$", 
      "value"=>"!!DATA.budget.montant_chf.END!!",
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("montant"),
      "display"=>["list", "form", "div","find"]
    ],
    [
      "name"=>"compte_1", 
      "type"=>"number",
      "regex"=>"^.*$", 
      "value"=>"",
      "optional"=>true, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("compte"),
      "display"=>["list", "form", "div","find"]
    ],
    [
      "name"=>"division", 
      "type"=>"number",
      "regex"=>"^.*$", 
      "value"=>"",
      "optional"=>true, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("division"),
      "display"=>["list", "form", "div","find"]
    ],
  ]
]; 


/*
  [
      "name"=>"projet", 
      "type"=>"key",
      "value"=>"",
      "schema"=>"projet",
      "regex"=>"^.*$", 
      "optional"=>true, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("projet"), 
      "display"=>["list", "form", "div"] 
    ],
*/


$schemas->budget=
[
  "name"=>"budget",
  "database"=>"bi",
  "table"=>"f_budget",
  "sgroup"=>"W",  // R W N
  "slevel"=>"W", // R W N
  "sother"=>"W", // R W N
  "structure"=>
  [  
    [
      "name"=>"fournisseur",
      "value"=>"",
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false,
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("fournisseur"), 
      "display"=>["list","find", "form", "div","concat"] 
    ],
    [
      "name"=>"nr_facture", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "value"=>"",
      "optional"=>false, 
      "col"=>"col-xs-2", 
      "clearall"=>false, 
      "label"=>nano\lbl("nr_facture_abacus"),
      "display"=>["list", "form", "div","find","concat"]
    ],
    [
      "name"=>"facture_ref", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "value"=>"",
      "optional"=>false, 
      "col"=>"col-xs-2", 
      "clearall"=>false, 
      "label"=>nano\lbl("nr_facture_fournisseur"),
      "display"=>["list", "form", "div","find"]
    ],
    [
      "name"=>"annee", 
      "type"=>"number",
      "regex"=>"^[0-9]{4}$", 
      "value"=>"",
      "optional"=>false, 
      "col"=>"col-xs-2", 
      "clearall"=>false, 
      "label"=>nano\lbl("annee"),
      "display"=>["list", "form", "div","find"]
    ],
    [
      "name"=>"mois", 
      "type"=>"number",
      "regex"=>"^[0-9]{4}$", 
      "value"=>"",
      "optional"=>false, 
      "col"=>"col-xs-2", 
      "clearall"=>false, 
      "label"=>nano\lbl("mois"),
      "display"=>["list", "form", "div","find"]
    ],
    [
      "name"=>"date", 
      "type"=>"date",
      "regex"=>"^.*$", 
      "value"=>"",
      "optional"=>false, 
      "col"=>"col-xs-2", 
      "clearall"=>false, 
      "label"=>nano\lbl("date_facture"),
      "display"=>["list", "form", "div","find"]
    ],
    [
      "name"=>"text",
      "value"=>"",
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false,
      "col"=>"col-xs-10", 
      "clearall"=>false, 
      "label"=>nano\lbl("text"), 
      "display"=>["list","find", "form", "div"] 
    ],
    [
      "name"=>"personne",
      "value"=>"",
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false,
      "col"=>"col-xs-2", 
      "clearall"=>false, 
      "label"=>nano\lbl("personne"), 
      "display"=>["list","find", "form", "div"] 
    ],
    [
      "name"=>"montant", 
      "type"=>"float",
      "regex"=>"^.*$", 
      "value"=>"",
      "optional"=>false, 
      "col"=>"col-xs-2", 
      "clearall"=>false, 
      "label"=>nano\lbl("montant"),
      "display"=>["list", "form", "div","find"]
    ],
    [
      "name"=>"montant_chf", 
      "type"=>"float",
      "regex"=>"^.*$", 
      "value"=>"",
      "optional"=>false, 
      "col"=>"col-xs-2", 
      "clearall"=>false, 
      "label"=>nano\lbl("montant_chf"),
      "display"=>["list", "form", "div","find"]
    ],
    [
      "name"=>"reparti",
      "label"=>nano\lbl("repartition_ok"),
      "type"=>"boolean",
      "regex"=>"^[0-1]$",
      "optional"=>false, 
      "col"=>"col-xs-2", 
      "clearall"=>false,
      "display"=>["form"]
    ],
    [
      "name"=>"projet",
      "value"=>"",
      "type"=>"relation",
      "key"=>"budget",
      "schema"=>"budget_projet",
      "regex"=>"^[0-9]*$", 
      "optional"=>false,
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("repartition projet"), 
      "display"=>["find", "list", "form", "div"] 
    ],
    [
      "name"=>"lignes",
      "value"=>"",
      "type"=>"relation",
      "key"=>"budget_id",
      "schema"=>"budget_ligne",
      "regex"=>"^[0-9]*$", 
      "optional"=>false,
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("repartition compta"), 
      "display"=>["find", "list", "form", "div"] 
    ],
   ],
   "default_query"=>[ "annee"=>2016,  "\$limit"=>100, "\$orderby"=>[ "reparti"=>["\$way"=>"asce"], "fournisseur"=>["\$way"=>"asce"], "date_facture"=>["\$way"=>"asce"] ]  ] ,
];


$schemas->lens=
[
  "name"=>"lens",
  "database"=>"",
  "table"=>"",
  "structure"=>
  [
    [
      "name"=>"designation", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("designation"), 
      "display"=>["find","list", "form", "div"] 
    ],
    [
      "name"=>"lens_index", 
      "type"=>"float",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("lens_index"), 
      "display"=>["find","list", "form", "div"] 
    ],
    [
      "name"=>"diameter", 
      "type"=>"number",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("diameter"), 
      "display"=>["find","list", "form", "div"] 
    ],
    [
      "name"=>"add_from", 
      "type"=>"number",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("add_from"), 
      "display"=>["find","list", "form", "div"] 
    ],
    [
      "name"=>"add_to", 
      "type"=>"number",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("add_to"), 
      "display"=>["find","list", "form", "div"] 
    ],
    [
      "name"=>"max_sph_from", 
      "type"=>"flaot",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("max_sph_from"), 
      "display"=>["find","list", "form", "div"] 
    ],
       [
      "name"=>"max_sph_to", 
      "type"=>"float",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("max_sph_to"), 
      "display"=>["find","list", "form", "div"] 
    ],
       [
      "name"=>"cyl_to", 
      "type"=>"float",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("cyl_to"), 
      "display"=>["find","list", "form", "div"] 
    ],
    [
      "name"=>"price", 
      "type"=>"float",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("price"), 
      "display"=>["find","list", "form", "div"] 
    ]
  ]
];


$schemas->collaborateur=
[
  "name"=>"collaborateur",
  "database"=>"",
  "table"=>"",
  "structure"=>
  [
    [
      "name"=>"code", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("collaborateur_code"), 
      "display"=>["find","list", "form", "div"] 
    ],
    [
      "name"=>"libelle", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("collaborateur_nom"), 
      "display"=>["find","list", "form", "div"] 
    ],
    [
      "name"=>"type", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("collaborateur_type"), 
      "display"=>["find","list", "form", "div"] 
    ]
  ]
];


$schemas->orl=
[
  "name"=>"telsearch",
  "database"=>"",
  "table"=>"",
  "structure"=>
  [
    [
      "name"=>"name", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("name"), 
      "display"=>["find","list", "form", "div"] 
    ],
    [
      "name"=>"firstname", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("firstname"), 
      "display"=>["find","list", "form", "div"] 
    ],
    [
      "name"=>"street", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("street"), 
      "display"=>["find","list", "form", "div"] 
    ],
    [
      "name"=>"streetno", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("streetno"), 
      "display"=>["find","list", "form", "div"] 
    ],
    [
      "name"=>"zip", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("zip"), 
      "display"=>["find","list", "form", "div"] 
    ],
    [
      "name"=>"city", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("city"), 
      "display"=>["find","list", "form", "div"] 
    ],
    [
      "name"=>"canton", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("canton"), 
      "display"=>["find","list", "form", "div"] 
    ],
    [
      "name"=>"phone", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("phone"), 
      "display"=>["find","list", "form", "div"] 
    ]
  ]
];


$schemas->telsearch=
[
  "name"=>"telsearch",
  "database"=>"",
  "table"=>"",
  "structure"=>
  [
    [
      "name"=>"name", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("name"), 
      "display"=>["find","list", "form", "div"] 
    ],
    [
      "name"=>"firstname", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("firstname"), 
      "display"=>["find","list", "form", "div"] 
    ],
    [
      "name"=>"street", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("street"), 
      "display"=>["find","list", "form", "div"] 
    ],
    [
      "name"=>"streetno", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("streetno"), 
      "display"=>["find","list", "form", "div"] 
    ],
    [
      "name"=>"zip", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("zip"), 
      "display"=>["find","list", "form", "div"] 
    ],
    [
      "name"=>"city", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("city"), 
      "display"=>["find","list", "form", "div"] 
    ],
    [
      "name"=>"canton", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("canton"), 
      "display"=>["find","list", "form", "div"] 
    ],
    [
      "name"=>"phone", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("phone"), 
      "display"=>["find","list", "form", "div"] 
    ]
  ]
];



$schemas->article=
[
  "name"=>"article",
  "database"=>"", // no database
  "table"=>"",
  "structure"=>
  [
    [
      "name"=>"article_code", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("article_code"), 
      "display"=>["list", "form", "div"] 
    ],
    [
      "name"=>"article_type", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("article_type"), 
      "display"=>["list", "form", "div"] 
    ],
    [
      "name"=>"marque", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("marque"), 
      "display"=>["find","list", "form", "div"] 
    ],
    [
      "name"=>"designation", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("designation"), 
      "display"=>["find","list", "form", "div","concat"] 
    ],
    [
      "name"=>"nr_metas", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("nr_metas"), 
      "display"=>["find","list", "form", "div"] 
    ],
    [
      "name"=>"prix_vente", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("prix_vente"), 
      "display"=>["list", "form", "div"] 
    ],
    [
      "name"=>"qt_atelier", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("qt_atelier"), 
      "display"=>["list", "form", "div"] 
    ],
     [
      "name"=>"qt_sav", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("qt_sav"), 
      "display"=>["list", "form", "div"] 
    ],
  ]
];

/*
    [
      "name"=>"image",
      "type"=>"html",
      "label"=>nano\lbl("image"), 
      "html"=>"<img class='img-responsive center-block img-circle' onerror='this.src=\"lunette.svg\";' src='https://www.berdoz-optic.ch/product-img/index.php?berdoz_id=!!VALUE!!'>",
      "value"=>"!!DATA.article.article_code.END!!",
      "display"=>["list", "form", "div"] 
    ]
*/


$schemas->client=
[
  "name"=>"client",
  "database"=>"",
  "table"=>"",
  "structure"=>
  [
    [ 
      "name"=>"numclient", 
      "label"=>nano\lbl("client number"), 
      "type"=>"hidden",
      "regex"=>"^.*$", 
      "optional"=>true, 
      "col"=>"col-xs-3", 
      "clearall"=>false,
      "display"=>["form"]
    ],
    [ 
      "name"=>"genre", 
      "label"=>nano\lbl("genre"), 
      "type"=>"list",
      "enum"=>["M","MA"],
      "regex"=>"^[a-za-z']*$", 
      "optional"=>false, 
      "col"=>"col-xs-6 col-md-3", 
      "clearall"=>false,
      "display"=>["list", "form", "div","concat"]
    ],
    [ 
      "name"=>"clientdepuis",
      "label"=>nano\lbl("clientdepuis"), 
      "type"=>"date",
      "disabled"=>true,
      "regex"=>"^.*$", 
      "optional"=>true, 
      "col"=>"col-xs-6 col-md-3 col-md-offset-6", 
      "clearall"=>false,
      "display"=>["form"]
    ],
    [ 
      "name"=>"nom", 
      "label"=>nano\lbl("nom"), 
      "type"=>"string", 
      "regex"=>"^[a-za-z']*$", 
      "optional"=>false, 
      "col"=>"col-xs-6 col-md-5", 
      "clearall"=>true,
      "display"=>["list", "form", "div","find","concat"]
    ],
    [ 
      "name"=>"prenom", 
      "label"=>nano\lbl("prenom"), 
      "type"=>"string",
      "regex"=>"^[a-za-z']*$", 
      "optional"=>false, 
      "col"=>"col-xs-6 col-md-4", 
      "clearall"=>false,
      "display"=>["list", "form", "div","find","concat"]
    ],   
    [ 
      "name"=>"datenaissance", 
      "label"=>nano\lbl("datenaissance"), 
      "type"=>"date",
      "regex"=>"^[0-9]{4}-[0-9]{2}-[0-9]{2}$", 
      "optional"=>false, 
      "col"=>"col-xs-12 col-md-3", 
      "clearall"=>false,
      "display"=>["list", "form", "div"]
    ],
    [ 
      "name"=>"adresse1",
      "label"=>nano\lbl("adresse1"), 
      "type"=>"string", 
      "regex"=>"^.*$", 
      "optional"=>true, 
      "col"=>"col-xs-8 col-md-5", 
      "clearall"=>false,
      "display"=>["form"]
    ],
    [ 
      "name"=>"numrue",
      "label"=>nano\lbl("numrue"), 
      "type"=>"string", 
      "regex"=>"^.*$", 
      "optional"=>true, 
      "col"=>"col-xs-4 col-md-2", 
      "clearall"=>false,
      "display"=>["form"]
    ],
    [ 
      "name"=>"adresse2",
      "label"=>nano\lbl("adresse2"), 
      "type"=>"string", 
      "regex"=>"^.*$", 
      "optional"=>true, 
      "col"=>"col-xs-12 col-md-5", 
      "clearall"=>false,
      "display"=>["form"]
    ],
    [ 
      "name"=>"npa",
      "label"=>nano\lbl("npa"), 
      "type"=>"string", 
      "regex"=>"^[0-9]*$", 
      "optional"=>true, 
      "col"=>"col-xs-3", 
      "clearall"=>false,
      "display"=>["list", "form"]
    ], 
    [ 
      "name"=>"localite",
      "label"=>nano\lbl("localite"), 
      "type"=>"string", 
      "regex"=>"^.*$", 
      "optional"=>true, 
      "col"=>"col-xs-6", 
      "clearall"=>false,
      "display"=>["form"]
    ],
    [ 
      "name"=>"telsearch", 
      "label"=>nano\lbl("annuaire"), 
      "type"=>"button",
      "btn"=>"btn-primary",
      "onclick"=>"intra.telsearch.find",
      "regex"=>"^[a-za-z']*$", 
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>false,
      "display"=>["form"]
    ],  
    [ 
      "name"=>"tel1",
      "label"=>nano\lbl("tel1"), 
      "type"=>"string", 
      "regex"=>"^\+[0-9]*$", 
      "optional"=>true, 
      "col"=>"col-md-3 col-xs-6", 
      "clearall"=>true,
      "display"=>["list", "form"]
    ],
    [ 
      "name"=>"tel2",
      "label"=>nano\lbl("tel2"), 
      "type"=>"string", 
      "regex"=>"^\+[0-9]*$", 
      "optional"=>true, 
      "col"=>"col-md-3 col-xs-6", 
      "clearall"=>false,
      "display"=>["form"]
    ],
    [ 
      "name"=>"mobile", 
      "label"=>nano\lbl("mobile"), 
      "type"=>"string" ,
      "regex"=>"^\+[0-9]*$",
      "optional"=>true, 
      "col"=>"col-md-3 col-xs-6", 
      "clearall"=>false,
      "display"=>["list", "form"]
    ],
    [ 
      "name"=>"email",
      "label"=>nano\lbl("email"),
      "type"=>"string",
      "regex"=>"^[a-za-z0-9.-_]*@[a-za-z0-9-_]*.[a-z0-9a-z]*$",
      "optional"=>true, 
      "col"=>"col-md-3 col-xs-6", 
      "clearall"=>false,
      "display"=>["list", "form"]
    ],
    [
      "name"=>"mailing",
      "label"=>nano\lbl("mailing"),
      "type"=>"boolean",
      "regex"=>"^[0-1]$",
      "optional"=>false, 
      "col"=>"col-md-3 col-xs-6", 
      "clearall"=>true,
      "display"=>["form"]
    ],
    [
      "name"=>"okmailingaudio",
      "label"=>nano\lbl("okmailingaudio"),
      "type"=>"boolean",
      "regex"=>"^[0-1]$",
      "optional"=>false, 
      "col"=>"col-md-3 col-xs-6", 
      "clearall"=>false,
      "display"=>["form"]
    ],
    [
      "name"=>"okemailing",
      "label"=>nano\lbl("okemailing"),
      "type"=>"boolean",
      "regex"=>"^[0-1]$",
      "optional"=>false, 
      "col"=>"col-md-3 col-xs-6", 
      "clearall"=>false,
      "display"=>["form"]
    ],
    [
      "name"=>"acp",
      "label"=>nano\lbl("acp"),
      "type"=>"boolean",
      "regex"=>"^[0-1]$",
      "optional"=>false, 
      "col"=>"col-md-3 col-xs-6", 
      "clearall"=>false,
      "display"=>["form"]
    ],
    [ 
      "name"=>"numavs",
      "label"=>nano\lbl("numavs"), 
      "type"=>"string", 
      "regex"=>"^.*$", 
      "optional"=>true, 
      "col"=>"col-md-3 col-xs-6", 
      "clearall"=>false,
      "display"=>["form"]
    ],
    [ 
      "name"=>"entreprise",
      "label"=>nano\lbl("entreprise"), 
      "type"=>"string", 
      "regex"=>"^.*$", 
      "optional"=>true, 
      "col"=>"col-md-3 col-xs-6", 
      "clearall"=>false,
      "display"=>["form"]
    ],
    [ 
      "name"=>"pays",
      "label"=>nano\lbl("pays"), 
      "type"=>"string", 
      "regex"=>"^.*$", 
      "optional"=>true, 
      "col"=>"col-md-3 col-xs-6", 
      "clearall"=>false,
      "display"=>["form"]
    ],
    [ 
      "name"=>"langue",
      "label"=>nano\lbl("langue"), 
      "type"=>"string", 
      "regex"=>"^.*$", 
      "optional"=>true, 
      "col"=>"col-md-3 col-xs-6", 
      "clearall"=>false,
      "display"=>["form"]
    ],
    [ 
      "name"=>"remarque",
      "label"=>nano\lbl("remarque"), 
      "type"=>"text", 
      "regex"=>"^.*$", 
      "optional"=>true, 
      "col"=>"col-xs-12", 
      "clearall"=>true,
      "display"=>["form"]
    ]
  ]
];



$schemas->devcon_lines=
[
  "name"=>"devcon_lines",
  "database"=>"nano",
  "table"=>"devcon_lines",
  "onload"=>"product_type_prepare",
  "sgroup"=>"R",  // R W N
  "slevel"=>"W", // R W N
  "sother"=>"R", // R W N
  "structure"=>
  [
    [
      "name"=>"dev_date", 
      "type"=>"date",
      "regex"=>"^[0-9]{4}-[0-9]{2}-[0-9]{2}$", 
      "value"=>"!!DATA.devcon_lines.dev_date.END!!",
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("devis date"),
      "display"=>["list", "form", "div"]
    ],
    [
      "name"=>"concurrent", 
      "type"=>"key",
      "value"=>"!!DATA.devcon_lines.concurrent.END!!",
      "schema"=>"concurrent",
      "regex"=>"^.*$", 
      "optional"=>true, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("concurrent"), 
      "display"=>["list", "form", "div"] 
    ],
    [
      "name"=>"npa",
      "value"=>"!!DATA.devcon_lines.npa.END!!",
      "type"=>"number",
      "regex"=>"^[0-9]{4,6}$", 
      "optional"=>false,
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("npa_concurrent"), 
      "display"=>["find", "form", "div"] 
    ],
    [
      "name"=>"reference",
      "value"=>"!!DATA.devcon_lines.reference.END!!",
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false,
      "col"=>"col-xs-3", 
      "clearall"=>true, 
      "label"=>nano\lbl("reference"), 
      "display"=>["find", "list", "form", "div"] 
    ],
    [
      "name"=>"email",
      "value"=>"!!DATA.devcon_lines.email.END!!",
      "type"=>"string",
      "regex"=>"^[a-zA-Z0-9.-_]*@[a-zA-Z0-9-_]*.[a-z0-9A-Z]*$", 
      "optional"=>true, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("contact by email"), 
      "display"=>["find","list", "form", "div"] 
    ],
    [
      "name"=>"product_type", 
      "type"=>"list",
      "enum"=>["frame","lens","other"],
      "optional"=>false, 
      "col"=>"col-xs-3",
      "onchange"=>"product_type_change",
      "clearall"=>false, 
      "label"=>nano\lbl("product_type"), 
      "display"=>["list", "form", "div"] 
    ],
    [
      "type"=>"section_start",
      "label"=>nano\lbl("frame or sunglass"),
      "col"=>"col-xs-12",
      "clearall"=>true,
      "id"=>"frame"
    ],
    [
      "name"=>"article_code", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false,
      "onchange"=>"intra.article.find_any",
      "onclick"=>"intra.article.find_any",
      "placeholder"=>nano\lbl("find by code..."),
      "col"=>"col-xs-6", 
      "clearall"=>true, 
      "label"=>nano\lbl("article_code"), 
      "display"=>["find", "list", "form", "div"] 
    ],
    [
      "name"=>"article_marque", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false,
      "col"=>"col-xs-6", 
      "clearall"=>false, 
      "label"=>nano\lbl("article_marque"), 
      "display"=>["find", "form", "div"] 
    ],
    [
      "type"=>"section_end",
    ],
    [
      "type"=>"section_start",
      "label"=>nano\lbl("Lenses"),
      "col"=>"col-xs-12",
      "clearall"=>true,
      "id"=>"lense"
    ],
    [
      "name"=>"lens_type", 
      "type"=>"list",
      "enum"=>["SVI","PRO","DEG","BIF","AUT"],
      "optional"=>false, 
      "col"=>"col-xs-2",
      "clearall"=>true, 
      "label"=>nano\lbl("lens_type"), 
      "display"=>[ "form", "div"] 
    ],
    [
      "name"=>"lens_index", 
      "type"=>"list",
      "enum"=>["STD","AMI","SFN","XFN","AUT"],
      "optional"=>false, 
      "col"=>"col-xs-2",
      "clearall"=>false, 
      "label"=>nano\lbl("lens_index"), 
      "display"=>[ "form", "div"] 
    ],
    [
      "name"=>"lens_material", 
      "type"=>"list",
      "enum"=>["MIN","ORG","PLY","TRX","AUT"],
      "optional"=>false, 
      "col"=>"col-xs-2",
      "clearall"=>false, 
      "label"=>nano\lbl("lens_material"), 
      "display"=>[ "form", "div"] 
    ],
    [
      "name"=>"lens_tint", 
      "type"=>"list",
      "enum"=>["BLC","TNT","POL","PHO","PPO","AUT"],
      "optional"=>false, 
      "col"=>"col-xs-2",
      "clearall"=>false, 
      "label"=>nano\lbl("lens_tint"), 
      "display"=>[ "form", "div"] 
    ],
    [
      "name"=>"lens_gamme", 
      "type"=>"list",
      "enum"=>["ECO","STK","FRX","SME","PRS","IND","AUT"],
      "optional"=>false, 
      "col"=>"col-xs-2",
      "clearall"=>false, 
      "label"=>nano\lbl("lens_gamme"), 
      "display"=>[ "form", "div"] 
    ],
    [
      "name"=>"lens_coating", 
      "type"=>"list",
      "enum"=>["STR","DUR","SAR","CLN","MCL","BLU","ARI","AUT"],
      "optional"=>false, 
      "col"=>"col-xs-2",
      "clearall"=>false, 
      "label"=>nano\lbl("lens_coating"), 
      "display"=>[ "form", "div"] 
    ],
    [
      "type"=>"section_end",
    ],
    [
      "name"=>"designation", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>false,
      "onchange"=>"article_search",
      "col"=>"col-xs-4", 
      "clearall"=>true, 
      "label"=>nano\lbl("designation"), 
      "display"=>["find", "list", "form", "div"] 
    ],
    [
      "name"=>"berdoz_price", 
      "type"=>"string",
      "regex"=>"^[0-9]*.[0-9]*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("berdoz_price"), 
      "display"=>["list", "form", "div"] 
    ],
    [
      "name"=>"con_price", 
      "type"=>"string",
      "regex"=>"^[0-9]*.[0-9]*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("con_price"), 
      "display"=>["list", "form", "div"] 
    ],
    
  ]
];


$schemas->biais=
[
  "name"=>"biais",
  "database"=>"nano",
  "table"=>"biais",
  //"onload"=>"",
  "sgroup"=>"N",  // R W N
  "slevel"=>"W", // R W N
  "sother"=>"N", // R W N
  "structure"=>
  [
    [
      "name"=>"magasin", 
      "value"=>"!!USERNAME!!",
      "type"=>"hidden",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("magasin"), 
      "display"=>["list", "form", "div"] 
    ],
    [
      "name"=>"nr_client", 
      "type"=>"string",
      "onchange"=>"biais_client_find",
      "onclick"=>"biais_client_find",
      "placeholder"=>nano\lbl("find by name code dob..."),
      "regex"=>"^[0-9]*$", 
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("nr_client"), 
      "display"=>["list","form","div"] 
    ],
    [
      "name"=>"client_detail",
      "id"=>"client_detail_div",
      "type"=>"string",
      "schema"=>"client",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-6", 
      "clearall"=>false, 
      "label"=>nano\lbl("client_detail"), 
      "display"=>["find","list", "form", "div"] 
    ],
    [
      "name"=>"edit",
      "type"=>"button",
      "btn"=>"btn-default",
      "col"=>"col-md-3", 
      "onclick"=>"biais_client_edit",
      "label"=>nano\lbl("edit"), 
      "display"=>["form"] 
    ],
    [
      "name"=>"biais_date", 
      "type"=>"date",
      "regex"=>"^[0-9]{4}-[0-9]{2}-[0-9]{2}$", 
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>true, 
      "label"=>nano\lbl("biais_date"),
      "display"=>["list", "form", "div","find"]
    ],
    [ 
      "name"=>"source", 
      "label"=>nano\lbl("source"), 
      "type"=>"list",
      "enum"=>["MAIL","VIT","REN","REC","ORL","JPO","TEL","OPT"],
      "regex"=>"^[a-za-z']*$", 
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>false,
      "display"=>["list", "form", "div","find"]
    ],
    [ 
      "name"=>"collaborateur",
      "label"=>nano\lbl("collaborateur"), 
      "onchange"=>"biais_collab_find",
      "onclick"=>"biais_collab_find",
      "type"=>"string", 
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>false,
      "display"=>["list","form","find"]
    ],
    [ 
      "name"=>"orl",
      "label"=>nano\lbl("orl"), 
      "type"=>"string",
      "onchange"=>"biais_orl_find",
      "onclick"=>"biais_orl_find",
      "regex"=>"^.*$", 
      "optional"=>true, 
      "col"=>"col-xs-3", 
      "clearall"=>false,
      "display"=>["list","form","find"]
    ],
    [ 
      "name"=>"orldata",
      "label"=>nano\lbl("orldata"), 
      "type"=>"json",
      "schema"=>"orl",
      "regex"=>"^.*$", 
      "optional"=>true, 
      "col"=>"col-xs-3", 
      "clearall"=>false,
      "display"=>[]
    ],
    [
      "name"=>"perte_droite", 
      "type"=>"float",
      "regex"=>"^[0-9]{1,3}$", 
      "optional"=>true, 
      "col"=>"col-xs-3", 
      "clearall"=>true, 
      "label"=>nano\lbl("perte_droite"), 
      "display"=>["list","form","div","find"] 
    ],
    [
      "name"=>"perte_gauche", 
      "type"=>"float",
      "regex"=>"^[0-9]{1,3}$", 
      "optional"=>true, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("perte_gauche"), 
      "display"=>["list","form","div","find"] 
    ],
    [ 
      "name"=>"status", 
      "label"=>nano\lbl("status"), 
      "type"=>"list",
      "enum"=>["NA","AA","APP","REC","DEM","ENC","FAC","ANN"],
      "regex"=>"^[a-za-z']*$", 
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>false,
      "display"=>["list","form","div","find"]
    ],
    [
      "name"=>"date_rappel", 
      "type"=>"date",
      "regex"=>"^[0-9]{4}-[0-9]{2}-[0-9]{2}$", 
      "optional"=>true, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("date_rappel"),
      "display"=>["list","form","div","find"]
    ],
    [ 
      "name"=>"remarque",
      "label"=>nano\lbl("remarque"), 
      "type"=>"text", 
      "regex"=>"^.*$", 
      "optional"=>true, 
      "col"=>"col-xs-6", 
      "clearall"=>true,
      "display"=>["list","form","find"]
    ],
    [ 
      "name"=>"opticien",
      "label"=>nano\lbl("opticien"), 
      "onchange"=>"biais_collab_find",
      "onclick"=>"biais_collab_find",
      "type"=>"string", 
      "regex"=>"^.*$", 
      "optional"=>true, 
      "col"=>"col-xs-3", 
      "clearall"=>false,
      "display"=>["form","find"]
    ],
    [
    "name"=>"courrier",
    "type"=>"html",
    "label"=>nano\lbl("courrier"), 
    "html"=>courrier_list("col-sx-3","Courrier","courrier"),
    "display"=>["form"]
    ]
  ]
];



$schemas->biais_encours=
[
  "name"=>"biais_encours",
  "database"=>"nano",
  "table"=>"biais",
  "sgroup"=>"N",
  "slevel"=>"W",
  "sother"=>"N",
  "structure"=>
  [
    [
      "name"=>"magasin", 
      "value"=>"!!USERNAME!!",
      "type"=>"hidden",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("magasin"), 
      "display"=>["list","form", "div"] 
    ],
    [
      "name"=>"nr_client", 
      "type"=>"string",
      "onchange"=>"biais_encours_client_find",
      "onclick"=>"biais_encours_client_find",
      "placeholder"=>nano\lbl("find by name code dob..."),
      "regex"=>"^[0-9]*$", 
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("nr_client"), 
      "display"=>["list","form","div"] 
    ],
    [
      "name"=>"client_detail",
      "id"=>"client_detail_div",
      "type"=>"string",
      "schema"=>"client",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-6", 
      "clearall"=>false, 
      "label"=>nano\lbl("client_detail"), 
      "display"=>["find","list", "form", "div"] 
    ],
    [
      "name"=>"edit",
      "type"=>"button",
      "btn"=>"btn-default",
      "col"=>"col-md-3", 
      "onclick"=>"biais_encours_client_edit",
      "label"=>nano\lbl("edit"), 
      "display"=>["form"] 
    ],
    [ 
      "name"=>"collaborateur",
      "label"=>nano\lbl("collaborateur"), 
      "type"=>"string",
      "onchange"=>"biais_encours_collab_find",
      "onclick"=>"biais_encours_collab_find",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>false,
      "display"=>["list","form","find"]
    ],
    [
      "name"=>"cat_appareil", 
      "type"=>"list",
      "regex"=>"^.$",
      "enum"=>["1","2","3","4","5"],
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("cat_appareil"), 
      "display"=>["list","form","div","find"] 
    ],
    [
      "name"=>"mono_bino", 
      "type"=>"list",
      "enum"=>["1","2"],
      "regex"=>"^[0-9]{1,2,3}$", 
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("mono_bino"), 
      "display"=>["list","form","div","find"] 
    ],
    [
      "name"=>"date_facture_prevue", 
      "type"=>"date",
      "regex"=>"^[0-9]{4}-[0-9]{2}-[0-9]{2}$", 
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("date_facture_prevue"),
      "display"=>["list", "form", "div","find"]
    ],
    [ 
      "name"=>"status", 
      "label"=>nano\lbl("status"), 
      "type"=>"list",
      "enum"=>["NA","AA","APP","REC","DEM","ENC","FAC","ANN"],
      "regex"=>"^[a-za-z']*$", 
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>false,
      "display"=>[ "form", "div","find"]
    ],
    
    [
    "name"=>"courrier",
    "type"=>"html",
    "label"=>nano\lbl("courrier"), 
    "html"=>courrier_list("col-sx-3","Courrier","courrier"),
    "display"=>["form"]
    ],
    [
      "type"=>"section_start",
      "label"=>nano\lbl("detail"),
      "col"=>"col-xs-12",
      "clearall"=>true,
      "collapse"=>true,
      "id"=>"near"
    ],
    [
      "name"=>"appareil_d_code", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>true,
      "onchange"=>"appareil_d_find",
      "onclick"=>"appareil_d_find",
      "placeholder"=>nano\lbl("find by code..."),
      "col"=>"col-xs-2", 
      "clearall"=>true, 
      "label"=>nano\lbl("appareil_d_code"), 
      "display"=>["find", "form", "div"] 
    ],
    [
      "name"=>"appareil_d_marque", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>true,
      "col"=>"col-xs-2", 
      "clearall"=>false, 
      "label"=>nano\lbl("appareil_d_marque"), 
      "display"=>["find", "form", "div"] 
    ],
    [
      "name"=>"appareil_d_designation", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "onchange"=>"appareil_d_find",
      "onclick"=>"appareil_d_find",
      "placeholder"=>nano\lbl("find by designation..."),
      "optional"=>true,
      "col"=>"col-xs-2", 
      "clearall"=>false, 
      "label"=>nano\lbl("appareil_d_designation"), 
      "display"=>["list","find", "form", "div"] 
    ],
    [
      "name"=>"appareil_d_metas", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>true,
      "col"=>"col-xs-2", 
      "clearall"=>false, 
      "label"=>nano\lbl("appareil_d_metas"), 
      "display"=>["find", "form", "div"] 
    ],
    [
      "name"=>"appareil_d_serie", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>true,
      "col"=>"col-xs-2", 
      "clearall"=>false, 
      "label"=>nano\lbl("appareil_d_serie"), 
      "display"=>["find", "form", "div"] 
    ],
    [
      "name"=>"appareil_d_pv", 
      "type"=>"float",
      "regex"=>"^[\-0-9\.]*$", 
      "onchange"=>"biais_encours_total",
      "optional"=>true,
      "col"=>"col-xs-2", 
      "clearall"=>false, 
      "label"=>nano\lbl("appareil_d_pv"), 
      "display"=>["find", "form", "div"] 
    ],
    [
      "name"=>"appareil_g_code", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>true,
      "onchange"=>"appareil_g_find",
      "onclick"=>"appareil_g_find",
      "placeholder"=>nano\lbl("find by code..."),
      "col"=>"col-xs-2", 
      "clearall"=>true, 
      "label"=>nano\lbl("appareil_g_code"), 
      "display"=>["find", "form", "div"] 
    ],
    [
      "name"=>"appareil_g_marque", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>true,
      "col"=>"col-xs-2", 
      "clearall"=>false, 
      "label"=>nano\lbl("appareil_g_marque"), 
      "display"=>["find", "form", "div"] 
    ],
    [
      "name"=>"appareil_g_designation", 
      "type"=>"string",
      "onchange"=>"appareil_g_find",
      "onclick"=>"appareil_g_find",
      "placeholder"=>nano\lbl("find by designation..."),
      "regex"=>"^.*$", 
      "optional"=>true,
      "col"=>"col-xs-2", 
      "clearall"=>false, 
      "label"=>nano\lbl("appareil_g_designation"), 
      "display"=>["list","find", "form", "div"] 
    ],
    [
      "name"=>"appareil_g_metas", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>true,
      "col"=>"col-xs-2", 
      "clearall"=>false, 
      "label"=>nano\lbl("appareil_g_metas"), 
      "display"=>["find", "form", "div"] 
    ],
    [
      "name"=>"appareil_g_serie", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>true,
      "col"=>"col-xs-2", 
      "clearall"=>false, 
      "label"=>nano\lbl("appareil_g_serie"), 
      "display"=>["find", "form", "div"] 
    ],
    [
      "name"=>"appareil_g_pv", 
      "type"=>"float",
      "regex"=>"^[\-0-9\.]*$", 
      "onchange"=>"biais_encours_total",
      "optional"=>true,
      "col"=>"col-xs-2", 
      "clearall"=>false, 
      "label"=>nano\lbl("appareil_g_pv"), 
      "display"=>["find", "form", "div"] 
    ],
    [
      "name"=>"autre_1_code", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>true,
      "onchange"=>"autre_1_find",
      "onclick"=>"autre_1_find",
      "placeholder"=>nano\lbl("find by code..."),
      "col"=>"col-xs-2", 
      "clearall"=>true, 
      "label"=>nano\lbl("autre_1_code"), 
      "display"=>["find", "form", "div"] 
    ],
    
    [
      "name"=>"autre_1_designation", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "onchange"=>"autre_1_find",
      "onclick"=>"autre_1_find",
      "placeholder"=>nano\lbl("find by designation..."),
      "optional"=>true,
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("autre_1_designation"), 
      "display"=>["list","find", "form", "div"] 
    ],
    [
      "name"=>"autre_1_serie", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>true,
      "col"=>"col-xs-2 col-xs-offset-2", 
      "clearall"=>false, 
      "label"=>nano\lbl("autre_1_serie"), 
      "display"=>["find", "form", "div"] 
    ],
    [
      "name"=>"autre_1_pv", 
      "type"=>"float",
      "regex"=>"^[\-0-9\.]*$", 
      "onchange"=>"biais_encours_total",
      "optional"=>true,
      "col"=>"col-xs-2", 
      "clearall"=>false, 
      "label"=>nano\lbl("autre_1_pv"), 
      "display"=>["find", "form", "div"] 
    ],
    
    
    [
      "name"=>"prestation_code", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>true,
      "onchange"=>"prestation_find",
      "onclick"=>"prestation_find",
      "placeholder"=>nano\lbl("find by code..."),
      "col"=>"col-xs-2", 
      "clearall"=>true, 
      "label"=>nano\lbl("prestation_code"), 
      "display"=>["find", "form", "div"] 
    ],
    
    [
      "name"=>"prestation_designation", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "onchange"=>"prestation_find",
      "onclick"=>"prestation_find",
      "placeholder"=>nano\lbl("find by designation..."),
      "optional"=>true,
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("prestation_designation"), 
      "display"=>["list","find", "form", "div"] 
    ],
    [
      "name"=>"prestation_pv", 
      "type"=>"float",
      "regex"=>"^[\-0-9\.]*$", 
      "onchange"=>"biais_encours_total",
      "optional"=>true,
      "col"=>"col-xs-2 col-xs-offset-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("prestation_pv"), 
      "display"=>["find", "form", "div"] 
    ],
    [
      "name"=>"autre_2_designation", 
      "type"=>"string",
      "regex"=>"^.*$", 
      "optional"=>true,
      "col"=>"col-xs-6", 
      "clearall"=>true, 
      "label"=>nano\lbl("autre_2_designation"), 
      "display"=>["list","find", "form", "div"] 
    ],
    
    [
      "name"=>"autre_2_pv", 
      "type"=>"float",
      "regex"=>"^[\-0-9\.]*$", 
      "onchange"=>"biais_encours_total",
      "optional"=>true,
      "col"=>"col-xs-2 col-xs-offset-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("autre_2_pv"), 
      "display"=>["find", "form", "div"] 
    ],
    
    
    
    [
      "type"=>"section_end",
    ],
     [ 
      "name"=>"remarque",
      "label"=>nano\lbl("remarque"), 
      "type"=>"text", 
      "regex"=>"^.*$", 
      "optional"=>true, 
      "col"=>"col-xs-10", 
      "clearall"=>false,
      "display"=>[ "form","find"]
    ],
    [
      "name"=>"montant_prevu", 
      "type"=>"float",
      "regex"=>"^[0-9\.]*$", 
      "optional"=>false, 
      "col"=>"col-xs-2", 
      "clearall"=>false, 
      "label"=>nano\lbl("montant_prevu"), 
      "display"=>["list","form","div","find"] 
    ],
   
  ],
  "default_query"=>["\$or"=>["status"=>"ENC","status"=>"FAC"] ] ,
];

//"default_query"=>["status"=>["\$eq"=>"ENC"], "\$groupby"=>["\$fields"=>["date_facture_prevue"=>"\$monthname"], "\$aggregate"=>["montant_prevu"=>"\$sum"]]] ,
  //"default_query"=>["status"=>["\$eq"=>"ENC"]] ,


$schemas->mesures=
[
  "name"=>"mesures",
  "database"=>"nano",
  "table"=>"mesures",
  //"onload"=>"",
  "sgroup"=>"R",  // R W N
  "slevel"=>"W", // R W N
  "sother"=>"R", // R W N
  "structure"=>
  [
    [
      "name"=>"magasin", 
      "value"=>"!!USERNAME!!",
      "type"=>"hidden",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("magasin"), 
      "display"=>["list", "form", "div"] 
    ],
    [
      "name"=>"dt_mesure", 
      "type"=>"hidden",
      "value"=>"!!TODAY!!",
      "regex"=>"^[0-9]{4}-[0-9]{2}-[0-9]{2}$", 
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("dt_mesure"),
      "display"=>["list", "form", "div"]
    ],
    [
      "name"=>"nr_client", 
      "type"=>"string",
      "onchange"=>"intra.client.find",
      "onclick"=>"intra.client.find",
      "placeholder"=>nano\lbl("find by name code dob..."),
      "regex"=>"^[0-9]*$", 
      "optional"=>false, 
      "col"=>"col-md-6", 
      "clearall"=>false, 
      "label"=>nano\lbl("nr_client"), 
      "display"=>["list","form","div"] 
    ],
    [
      "name"=>"client_detail",
      "type"=>"string",
      "placeholder"=>nano\lbl("find by name code dob..."),
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-md-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("client_detail"), 
      "display"=>["list","form","div","find"] 
    ],
    [
      "name"=>"edit",
      "type"=>"button",
      "btn"=>"btn-default",
      "col"=>"col-md-2", 
      "onclick"=>"intra.client.edit",
      "label"=>nano\lbl("edit"), 
      "display"=>["form"] 
    ],
    [
      "name"=>"article_code", 
      "type"=>"string",
      "onchange"=>"intra.article.find_code",
      "onclick"=>"intra.article.find_code",
      "placeholder"=>nano\lbl("find by code..."),
      "regex"=>"^[A-Za-z0-9]*$", 
      "optional"=>false, 
      "col"=>"col-md-6", 
      "clearall"=>true, 
      "label"=>nano\lbl("article_code"), 
      "display"=>["list","form","div"] 
    ],
    [
      "name"=>"designation", 
      "type"=>"string",
      "placeholder"=>nano\lbl("find by code..."),
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-md-6", 
      "clearall"=>false, 
      "label"=>nano\lbl("designation"), 
      "display"=>["list","form","div","find"] 
    ],
    [
      "type"=>"section_start",
      "label"=>nano\lbl("distance_vision"),
      "col"=>"col-xs-12",
      "clearall"=>true,
      "id"=>"distance"
    ],
    [
      "name"=>"FPDR", 
      "type"=>"float",
      "round"=>0.5,
      "regex"=>"^[-+]?[0-9]*\.?[0-9]*$", 
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>true, 
      "label"=>nano\lbl("dp_right"), 
      "display"=>["list","form","div"] 
    ],
    [
      "name"=>"FPDL", 
      "type"=>"float",
      "round"=>0.5,
      "regex"=>"^[-+]?[0-9]*\.?[0-9]*$",
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("dp_left"), 
      "display"=>["list","form","div"] 
    ],
    [
      "name"=>"FHR", 
      "type"=>"float",
      "round"=>0.5,
      "regex"=>"^[-+]?[0-9]*\.?[0-9]*$",
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("height_right"), 
      "display"=>["list","form","div"] 
    ],
    [
      "name"=>"FHL", 
      "type"=>"float",
      "round"=>0.5,
      "regex"=>"^[-+]?[0-9]*\.?[0-9]*$", 
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("height_left"), 
      "display"=>["list","form","div"] 
    ],
    ["type"=>"section_end"],
    [
      "type"=>"section_start",
      "label"=>nano\lbl("frame"),
      "col"=>"col-xs-12",
      "clearall"=>true,
      "id"=>"frame"
    ],
    [
      "name"=>"A", 
      "type"=>"float",
      "round"=>0.5,
      "regex"=>"^[-+]?[0-9]*\.?[0-9]*$",
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>true, 
      "label"=>nano\lbl("a_length"), 
      "display"=>["form","div"] 
    ],
    [
      "name"=>"B", 
      "type"=>"float",
      "round"=>0.5,
      "regex"=>"^[-+]?[0-9]*\.?[0-9]*$",
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("b_length"), 
      "display"=>["form","div"] 
    ],
    [
      "name"=>"DBL", 
      "type"=>"float",
      "round"=>0.5,
      "regex"=>"^[-+]?[0-9]*\.?[0-9]*$",
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("c_length"), 
      "display"=>["form","div"] 
    ],
    [
      "name"=>"EDR", 
      "type"=>"float",
      "round"=>0.5,
      "regex"=>"^[-+]?[0-9]*\.?[0-9]*$",
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>true, 
      "label"=>nano\lbl("diam_right"), 
      "display"=>["form","div"] 
    ],
    [
      "name"=>"EDL", 
      "type"=>"float",
      "round"=>0.5,
      "regex"=>"^[-+]?[0-9]*\.?[0-9]*$",
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("diam_left"), 
      "display"=>["form","div"] 
    ],
    [
      "type"=>"section_end",
    ],
    [
      "type"=>"section_start",
      "label"=>nano\lbl("personal"),
      "col"=>"col-xs-12",
      "clearall"=>true,
      "collapse"=>true,
      "id"=>"personal"
    ],
    [
      "name"=>"PT", 
      "type"=>"float",
      "round"=>0.5,
      "regex"=>"^[-+]?[0-9]*\.?[0-9]*$",
      "optional"=>true, 
      "col"=>"col-xs-3", 
      "clearall"=>true, 
      "label"=>nano\lbl("tilt"), 
      "display"=>["form","div"] 
    ],
    [
      "name"=>"BVD", 
      "type"=>"float",
      "round"=>0.5,
      "regex"=>"^[-+]?[0-9]*\.?[0-9]*$",
      "optional"=>true, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("vertex"), 
      "display"=>["form","div"] 
    ],
    [
      "name"=>"FWA", 
      "type"=>"float",
      "round"=>0.5,
      "regex"=>"^[-+]?[0-9]*\.?[0-9]*$",
      "optional"=>true, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("wrap_angle"), 
      "display"=>["form","div"] 
    ],
    [
      "type"=>"section_end",
    ],
    [
      "type"=>"section_start",
      "label"=>nano\lbl("near_vision"),
      "col"=>"col-xs-12",
      "clearall"=>true,
      "collapse"=>true,
      "id"=>"near"
    ],
    [
      "name"=>"NPDR", 
      "type"=>"float",
      "round"=>0.5,
      "regex"=>"^[-+]?[0-9]*\.?[0-9]*$",
      "optional"=>true, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("near_epd"), 
      "display"=>["form","div"] 
    ],[
      "name"=>"NPDL", 
      "type"=>"float",
      "round"=>0.5,
      "regex"=>"^[-+]?[0-9]*\.?[0-9]*$",
      "optional"=>true, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("near_epg"), 
      "display"=>["form","div"] 
    ],[
      "name"=>"UF", 
      "type"=>"float",
      "round"=>0.5,
      "regex"=>"^[-+]?[0-9]*\.?[0-9]*$",
      "optional"=>true, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("upfit"), 
      "display"=>["form","div"] 
    ],[
      "name"=>"RD", 
      "type"=>"float",
      "round"=>0.5,
      "regex"=>"^[-+]?[0-9]*\.?[0-9]*$",
      "optional"=>true, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("rd"), 
      "display"=>["form","div"] 
    ],[
      "name"=>"IR", 
      "type"=>"float",
      "round"=>0.5,
      "regex"=>"^[-+]?[0-9]*\.?[0-9]*$",
      "optional"=>true, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("inset_right"), 
      "display"=>["form","div"] 
    ],[
      "name"=>"IL", 
      "type"=>"float",
      "round"=>0.5,
      "regex"=>"^[-+]?[0-9]*\.?[0-9]*$",
      "optional"=>true, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("inset_left"), 
      "display"=>["form","div"] 
    ],
    [
      "type"=>"section_end",
    ],
    
   ]
];

$schemas->nano_commande=
[
  "name"=>"nano_commande",
  "database"=>"portail",
  "table"=>"nano_commande",
  "sgroup"=>"R",  // R W N
  "slevel"=>"W", // R W N
  "sother"=>"N", // R W N
  "structure"=>
  [
    [
      "name"=>"magasin", 
      "value"=>"!!USERNAME!!",
      "type"=>"hidden",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("magasin"), 
      "display"=>["list", "form", "div"] 
    ],
    [
      "name"=>"dt_comm_magasin", 
      "type"=>"hidden",
      "value"=>"!!TODAY!!",
      "regex"=>"^[0-9]{4}-[0-9]{2}-[0-9]{2}$", 
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("dt_comm_magasin"),
      "display"=>["list", "form", "div"]
    ],
    [
      "name"=>"reference", 
      "type"=>"string",
      "regex"=>"^.*$",
      "value"=>"!!DATA.nano_commande.reference.END!!",
      "optional"=>true, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("reference"), 
      "display"=>["list", "form", "div","find"] 
    ],
    [
      "name"=>"remarque", 
      "type"=>"string",
      "value"=>"!!DATA.nano_commande.remarque.END!!",
      "regex"=>"^.*$", 
      "optional"=>true, 
      "col"=>"col-xs-8", 
      "clearall"=>false, 
      "label"=>nano\lbl("remarque"), 
      "display"=>["list", "form", "div"] 
    ],
   
    [
      "name"=>"type", 
      "type"=>"list",
      "regex"=>"^.$",
      "enum"=>["stock","client","sav"],
      "optional"=>false,
      "focus"=>true,
      "col"=>"col-xs-2", 
      "clearall"=>false, 
      "label"=>nano\lbl("Pour"), 
      "display"=>["list","form","div","find"] 
    ],
    [
      "name"=>"code_article", 
      "type"=>"string",
      "onchange"=>"article_find_code",
        "onclick"=>"article_find_code",
        "schema"=>"code_article",
      "regex"=>"^.*$",
      "optional"=>false, 
      "col"=>"col-xs-2", 
      "clearall"=>false, 
      "label"=>nano\lbl("code_article"), 
      "display"=>["list", "form", "div"] 
    ],
    [
      "name"=>"type_article", 
      "type"=>"hidden",
      "regex"=>"^.*$", 
      "optional"=>true, 
      "col"=>"col-xs-2", 
      "clearall"=>false, 
      "label"=>nano\lbl("type_article"), 
      "display"=>["list", "form", "div"] 
    ],
    [
      "name"=>"designation", 
      "type"=>"string",
      "onchange"=>"article_find_designation",
      "onclick"=>"article_find_designation",
      "schema"=>"code_article",
      "regex"=>"^.*$", 
      "optional"=>true, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("designation"), 
      "display"=>["list", "form", "div"] 
    ],
    [
      "name"=>"marque", 
      "type"=>"string",
      //"onchange"=>"article_find_marque",
      "schema"=>"code_article",
      "regex"=>"^.*$", 
      "optional"=>true, 
      "col"=>"col-xs-2", 
      "clearall"=>false, 
      "label"=>nano\lbl("marque"), 
      "display"=>["list", "form", "div"] 
    ],
 
    [
      "name"=>"quantite", 
      "type"=>"number",
      "schema"=>"quantite",
      "regex"=>"^[0-9]*$", 
      "optional"=>false, 
      "col"=>"col-xs-2", 
      "clearall"=>false, 
      "label"=>nano\lbl("quantite"), 
      "display"=>["list", "form", "div"] 
    ]
  ]
];

$schemas->activity=
[
  "name"=>"activity",
  "database"=>"nano",
  "table"=>"activity",
  "sgroup"=>"W",  // R W N
  "slevel"=>"w", // R W N
  "sother"=>"W", // R W N
  "structure"=>
  [
    [
      "name"=>"name", 
      "type"=>"translate",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("activity"), 
      "display"=>["find","list", "form", "div"] 
    ],
    [
    "name"=>"proposal",
    "type"=>"multiplekey",
    "schema"=>"proposal",
    "regex"=>"^.*$",
    "optional"=>true,
    "col"=>"col-xs-12",
    "clearall"=>true,
    "label"=>nano\lbl("proposal"),
    "display"=>["list","form","find","div"]
    ]
  ]
];

$schemas->proposal=
[
  "name"=>"proposal",
  "database"=>"nano",
  "table"=>"proposal",
  "sgroup"=>"W",  // R W N
  "slevel"=>"w", // R W N
  "sother"=>"W", // R W N
  "structure"=>
  [
    [
      "name"=>"name", 
      "type"=>"translate",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-4", 
      "clearall"=>false, 
      "label"=>nano\lbl("proposal"), 
      "display"=>["find","list", "form", "div"] 
    ],
    [
      "name"=>"presbyte",
      "label"=>nano\lbl("presbyte"),
      "type"=>"boolean",
      "regex"=>"^[0-1]$",
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>false,
      "display"=>["list","form"]
    ]
   ]
];



$schemas->client_activity=
[
  "name"=>"client_activity",
  "database"=>"nano",
  "table"=>"client_activity",
  //"onload"=>"",
  "sgroup"=>"W",  // R W N
  "slevel"=>"w", // R W N
  "sother"=>"W", // R W N
  "structure"=>
  [
    [
      "name"=>"nr_client", 
      "type"=>"string",
      "onchange"=>"intra.client.find",
      "onclick"=>"intra.client.find",
      "placeholder"=>nano\lbl("find by name code dob..."),
      "regex"=>"^[0-9]*$", 
      "optional"=>false, 
      "col"=>"col-xs-6", 
      "clearall"=>false, 
      "label"=>nano\lbl("nr_client"), 
      "display"=>["list","form","div"] 
    ],
    [
      "name"=>"date", 
      "type"=>"date",
      "regex"=>"^[0-9]{4}-[0-9]{2}-[0-9]{2}$", 
      "value"=>"!!TODAY!!",
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>true, 
      "label"=>nano\lbl("biais date"),
      "display"=>["list", "form", "div"]
    ],
    [
      "name"=>"activity", 
      "type"=>"key",
      "schema"=>"activity",
      "regex"=>"^.*$", 
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("activity"), 
      "display"=>["list", "form", "div"] 
    ],
    [
      "name"=>"frequency", 
      "type"=>"string",
      "regex"=>"^[0-3]{1}$", 
      "optional"=>false, 
      "col"=>"col-xs-3", 
      "clearall"=>false, 
      "label"=>nano\lbl("frequency"), 
      "display"=>["list", "form", "div"] 
    ]
  ]
];

$schemas->ordonnance=
[
  "name"=>"ordonnance",
  "database"=>"",
  "table"=>"",
  "structure"=>
  [
    [
    "name"=>"numclient",
    "col"=>"col-xs-1", 
    "clearall"=>false, 
    "label"=>nano\lbl("client"), 
    "display"=>["list", "form", "div"] 
    ],
    [
    "name"=>"numordonnance",
    "col"=>"col-xs-1", 
    "clearall"=>false, 
    "label"=>nano\lbl("ordonnance"), 
    "display"=>["list", "form", "div"] 
    ],
    [
    "name"=>"date_ordonnance",
    "col"=>"col-xs-1", 
    "clearall"=>false, 
    "label"=>nano\lbl("date"), 
    "display"=>["list", "form", "div"] 
    ],
    [
    "name"=>"magasin",
    "col"=>"col-xs-1",
    "clearall"=>false, 
    "label"=>nano\lbl("shop"), 
    "display"=>["list", "form", "div"]
    ],
    [
    "name"=>"emploi", 
    "col"=>"col-xs-2", 
    "clearall"=>false, 
    "label"=>nano\lbl("usage"), 
    "display"=>["list", "form", "div"] 
    ],
    [
    "name"=>"refractionniste",
    "col"=>"col-xs-2", 
    "clearall"=>false, 
    "label"=>nano\lbl("prescriber"), 
    "display"=>["list", "form", "div"] 
    ],
    [
    "name"=>"remarque",
    "col"=>"col-md-6", 
    "clearall"=>false, 
    "label"=>nano\lbl("remark"), 
    "display"=>["list", "form", "div"] 
    ],
    [
    "name"=>"sphere_d",
    "col"=>"col-xs-2", 
    "clearall"=>true, 
    "label"=>nano\lbl("sphere"), 
    "display"=>["list", "form", "div"] 
    ],
    [
    "name"=>"cylindre_d",
    "col"=>"col-xs-2", 
    "clearall"=>false, 
    "label"=>nano\lbl("cylinder"), 
    "display"=>["list", "form", "div"] 
    ],
    [
    "name"=>"axe_d",
    "col"=>"col-xs-2", 
    "clearall"=>false, 
    "label"=>nano\lbl("axis"), 
    "display"=>["list", "form", "div"] 
    ],
    [
    "name"=>"add_d",
    "col"=>"col-xs-2", 
    "clearall"=>false, 
    "label"=>nano\lbl("addition"), 
    "display"=>["list", "form", "div"] 
    ],
    ["name"=>"visus_d",
    "col"=>"col-xs-2", 
    "clearall"=>false, 
    "label"=>nano\lbl("visus"), 
    "display"=>["list", "form", "div"] 
    ],
    [
    "name"=>"dp_d",
    "col"=>"col-xs-2", 
    "clearall"=>false, 
    "label"=>nano\lbl("pd"), 
    "display"=>["list", "form", "div"] 
    ],
    [
    "name"=>"sphere_g",
    "col"=>"col-xs-2", 
    "clearall"=>true, 
    "label"=>"", 
    "display"=>["list", "form", "div"] 
    ],
    [
    "name"=>"cylindre_g",
    "col"=>"col-xs-2", 
    "clearall"=>false, 
    "label"=>"", 
    "display"=>["list", "form", "div"]
    ],
    [
    "name"=>"axe_g",
    "col"=>"col-xs-2", 
    "clearall"=>false, 
    "label"=>"", 
    "display"=>["list", "form", "div"] 
    ],
    [
    "name"=>"add_g",
    "col"=>"col-xs-2", 
    "clearall"=>false, 
    "label"=>"", 
    "display"=>["list", "form", "div"]
    ],
    [
    "name"=>"visus_g",
    "col"=>"col-xs-2", 
    "clearall"=>false, 
    "label"=>"", 
    "display"=>["list", "form", "div"] 
    ],
    [
    "name"=>"dp_g",
    "col"=>"col-xs-2", 
    "clearall"=>false, 
    "label"=>"", 
    "display"=>["list", "form", "div"] 
    ]
  ]
];



?>