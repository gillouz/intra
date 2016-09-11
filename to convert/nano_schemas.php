<?php
$schemas=(object) array();


$schemas->devis=
[
  "name"=>"devis",
  "database"=>$databases["bi"],
  "table"=>"devis",
  "structure"=>
  [
    ["name"=>"nr_client", "type"=>"key","regex"=>"^[0-9]{1,10}$", "optional"=>false, "col"=>"col-xs-6", "clearall"=>false, "label"=>lbl("nr_client"), "display"=>["list", "form", "div"] ],
    ["name"=>"nr_ordonnance","type"=>"key","regex"=>"^[0-9]{1,10}$", "optional"=>true, "col"=>"col-xs-6", "clearall"=>false, "label"=>lbl("nr_ordonnance"), "display"=>["list", "form", "div"] ],
    ["name"=>"nr_mesure","type"=>"key", "regex"=>"^[0-9]{1,10}$", "optional"=>true, "col"=>"col-xs-6", "clearall"=>false, "label"=>lbl("nr_mesure"), "display"=>["list", "form", "div"]],
    ["name"=>"lines","type"=>"relation", "table"=>"lines", "field"=>"nr_devis", "col"=>"col-xs-6", "clearall"=>false, "label"=>lbl("lines"), "display"=>false ],
    ["name"=>"date_cree","type"=>"date", "regex"=>"^[0-9]{4}-[0-9]{2}-[0-9]{2}$", "optional"=>false, "col"=>"col-xs-6", "clearall"=>false, "label"=>lbl("date_cree"), "display"=>["list", "form", "div"] ],
    ["name"=>"date_modif","type"=>"date", "regex"=>"^[0-9]{4}-[0-9]{2}-[0-9]{2}$", "optional"=>false, "col"=>"col-xs-6", "clearall"=>false, "label"=>lbl("date_modif"), "display"=>["list", "form", "div"]],
    ["name"=>"total","type"=>"double", "regex"=>"^[0-9]{1,10}\.[0-9]{1,2}|[0-9]{1,10}$", "optional"=>false, "col"=>"col-xs-6", "clearall"=>false, "label"=>lbl("total"), "display"=>["list", "form", "div"]],
    ["name"=>"nbr","type"=>"integer", "regex"=>"^[0-9]{1,10}$", "optional"=>false, "col"=>"col-xs-6", "clearall"=>false, "label"=>lbl("quantity"), "display"=>["list", "form", "div"]],
    ["name"=>"magasin","type"=>"json",  "optional"=>false, "col"=>"col-xs-6", "clearall"=>false, "label"=>lbl("magasin"), "display"=>false ],
    ["name"=>"client","type"=>"json",  "optional"=>false, "col"=>"col-xs-6", "clearall"=>false, "label"=>lbl("client"), "display"=>false ]
  ]
];


$schemas->lines=
[
  "name"=>"lines",
  "database"=>$databases["bi"],
  "table"=>"lines",
  "structure"=>
  [
    ["name"=>"nr_devis","type"=>"key","regex"=>"^[0-9]{1,10}$", "optional"=>false, "col"=>"col-md-2", "clearall"=>false, "label"=>"nr_devis", "display"=>false ],
    ["name"=>"code_article","type"=>"string", "regex"=>"^.*$", "optional"=>false, "col"=>"col-md-2", "clearall"=>false, "label"=>lbl("article_code"), "display"=>["list", "form", "div"] ],
    ["name"=>"type_article","type"=>"string", "regex"=>"^.*$", "optional"=>false, "col"=>"col-md-2", "clearall"=>false, "label"=>lbl("article_type"), "display"=>["list", "form", "div"] ],
    ["name"=>"marque","type"=>"string", "regex"=>"^.*$", "optional"=>false,"col"=>"col-md-2", "clearall"=>false, "label"=>lbl("brand"), "display"=>["list", "form", "div"]  ],
    ["name"=>"designation","type"=>"string", "regex"=>"^.*$", "optional"=>false,"col"=>"col-md-4", "clearall"=>false, "label"=>lbl("designation"), "display"=>["list", "form", "div"] ],
    ["name"=>"info","type"=>"string", "regex"=>"^.*$", "optional"=>false,"col"=>"col-md-4", "clearall"=>false, "label"=>"", "display"=>["list", "form", "div"] ],
    ["name"=>"quantite","type"=>"integer", "regex"=>"^[0-9]{1,10}$", "optional"=>false,"col"=>"col-md-2", "clearall"=>false, "label"=>lbl("quantity"), "display"=>["list", "form", "div"] ],
    ["name"=>"prix","type"=>"double", "regex"=>"[0-9]{1,10}\.[0-9]{1,2}|[0-9]{1,10}$", "optional"=>false,"col"=>"col-md-2", "clearall"=>false, "label"=>lbl("price"), "display"=>["list", "form", "div"] ]
  ]
];


$schemas->client=
[
  "name"=>"client",
  "database"=>"4d",
  "table"=>"",
  "structure"=>
  [
    ["name"=>"NR_CLIENT", "col"=>"col-md-2", "clearall"=>false, "label"=>lbl("client_number"), "display"=>["list", "form", "div"] ],
    ["name"=>"NOM","col"=>"col-md-2", "clearall"=>false, "label"=>lbl("client_name"), "display"=>["list", "form", "div"] ],
    ["name"=>"PRENOM","col"=>"col-md-2", "clearall"=>false, "label"=>lbl("client_surname"), "display"=>["list", "form", "div"]] ,
    ["name"=>"DATE_NAISSANCE","col"=>"col-md-2", "clearall"=>false, "label"=>lbl("date_of_birth"), "display"=>["list", "form", "div"] ],
  ]
];


$schemas->client_edit=
[
  "name"=>"client_edit",
  "database"=>"4d",
  "table"=>"",
  "structure"=>
  [
    [ "name"=>"NR_CLIENT", "label"=>"Numero client", "type"=>"hidden" ],
    [ "name"=>"NOM", "label"=>"Nom", "type"=>"string", "regex"=>"^[a-zA-Z']*$", "required"=>true ],
    [ "name"=>"PRENOM", "label"=>"Prénom", "type"=>"string","regex"=>"^[a-zA-Z']*$", "required"=>true ],
    [ "name"=>"DATE_NAISSANCE", "label"=>"Date de naissance", "type"=>"string","regex"=>"^[0-9]{2}\.[0-9]{2}\.[0-9]{4}$", "required"=>true ],
    [ "name"=>"TELEPHONE_1", "label"=>"Numero de téléphone", "type"=>"string" ],
    [ "name"=>"TELEPHONE_PORTABLE", "label"=>"Numero de mobile", "type"=>"string" ],
    [ "name"=>"ADRESSE_EMAIL", "label"=>"Email", "type"=>"string" ],
    [ "name"=>"ACCEPT_EMAIL", "label"=>"Accept mailing", "type"=>"boolean" ]
  ]
];

// prescribing

$schemas->ordonnance=
[
  "name"=>"ordonnance",
  "database"=>"4d",
  "table"=>"",
  "structure"=>
  [
    ["name"=>"DATE_ORDONNANCE", "col"=>"col-xs-1", "clearall"=>false, "label"=>lbl("date"), "display"=>["list", "form", "div"] ],
    ["name"=>"MAGASIN", "col"=>"col-xs-1", "clearall"=>false, "label"=>lbl("shop"), "display"=>["list", "form", "div"] ],
    ["name"=>"EMPLOI", "col"=>"col-xs-2", "clearall"=>false, "label"=>lbl("usage"), "display"=>["list", "form", "div"] ],
    ["name"=>"REFRACTIONNISTE","col"=>"col-xs-2", "clearall"=>false, "label"=>lbl("prescriber"), "display"=>["list", "form", "div"] ],
    ["name"=>"REMARQUE","col"=>"col-md-6", "clearall"=>false, "label"=>lbl("remark"), "display"=>["list", "form", "div"] ],
    ["name"=>"SPH_OD","col"=>"col-xs-2", "clearall"=>true, "label"=>lbl("sphere"), "display"=>["list", "form", "div"] ],
    ["name"=>"CYL_OD","col"=>"col-xs-2", "clearall"=>false, "label"=>lbl("cylinder"), "display"=>["list", "form", "div"] ],
    ["name"=>"AXE_OD","col"=>"col-xs-2", "clearall"=>false, "label"=>lbl("axis"), "display"=>["list", "form", "div"] ],
    ["name"=>"ADD_OD","col"=>"col-xs-2", "clearall"=>false, "label"=>lbl("addition"), "display"=>["list", "form", "div"] ],
    ["name"=>"VISUS_OD","col"=>"col-xs-2", "clearall"=>false, "label"=>lbl("visus"), "display"=>["list", "form", "div"] ],
    ["name"=>"ECART_OD","col"=>"col-xs-2", "clearall"=>false, "label"=>lbl("pd"), "display"=>["list", "form", "div"] ],
    ["name"=>"SPH_OG","col"=>"col-xs-2", "clearall"=>true, "label"=>"", "display"=>["list", "form", "div"] ],
    ["name"=>"CYL_OG","col"=>"col-xs-2", "clearall"=>false, "label"=>"", "display"=>["list", "form", "div"] ],
    ["name"=>"AXE_OG","col"=>"col-xs-2", "clearall"=>false, "label"=>"", "display"=>["list", "form", "div"] ],
    ["name"=>"ADD_OG","col"=>"col-xs-2", "clearall"=>false, "label"=>"", "display"=>["list", "form", "div"] ],
    ["name"=>"VISUS_OG","col"=>"col-xs-2", "clearall"=>false, "label"=>"", "display"=>["list", "form", "div"] ],
    ["name"=>"ECART_OG","col"=>"col-xs-2", "clearall"=>false, "label"=>"", "display"=>["list", "form", "div"] ]
  ]
];

$schemas->frame=
[
  "name"=>"frame",
  "database"=>"4d",
  "table"=>"",
  "structure"=>
  [
    ["name"=>"code", "col"=>"col-xs-2", "clearall"=>false, "label"=>lbl("code_article"), "display"=>["list", "form", "div"] ],
    ["name"=>"marque", "col"=>"col-xs-2", "clearall"=>false, "label"=>lbl("brand"), "display"=>["list", "form", "div"] ],
    ["name"=>"designation", "col"=>"col-xs-2", "clearall"=>false, "label"=>lbl("designation"), "display"=>["list", "form", "div"] ],
    ["name"=>"prix_special","col"=>"col-xs-2", "clearall"=>false, "label"=>lbl("price"), "display"=>["list", "form", "div"] ],
  ]
];
  

$schemas->lens=
[
  "name"=>"lens",
  "database"=>"4d",
  "table"=>"",
  "structure"=>
  [
    ["name"=>"lens_code", "col"=>"col-md-2", "clearall"=>false, "label"=>lbl("article_code"), "display"=>["list", "form", "div"] ],
    ["name"=>"designation","col"=>"col-md-2", "clearall"=>false, "label"=>lbl("designation"), "display"=>["list", "form", "div"] ],
    ["name"=>"cle_gamme","col"=>"col-md-4", "clearall"=>false, "label"=>lbl("product_line"), "display"=>["list", "form", "div"] ],
    ["name"=>"pv3_d","col"=>"col-md-2", "clearall"=>false, "label"=>lbl("price"), "display"=>["list", "form", "div"] ],
    
  ]
];


$schemas->mesures=
[  
  "name"=>"mesures",
  "database"=>$databases["bi"],
  "table"=>"mesures",
  "structure"=>
  [
    ["name"=>"nr_frame", "type"=>"key","regex"=>"^[0-9]{1,10}$", "optional"=>false, "col"=>"col-xs-12", "clearall"=>false, "label"=>lbl("frame"), "display"=>["list", "form", "div"] ],
    ["name"=>"nr_client", "type"=>"key","regex"=>"^[0-9]{1,10}$", "optional"=>false, "col"=>"col-xs-12", "clearall"=>false, "label"=>lbl("nr_client"), "display"=>["list", "form", "div"] ],
    ["name"=>"frame","type"=>"json", "optional"=>false, "col"=>"col-xs-12", "clearall"=>false, "label"=>lbl("brand"), "display"=>["list", "form", "div"] ],
    ["name"=>"FarPDR","type"=>"double","regex"=>"^[0-9]{1,10}\.[0-9]{1,2}|[0-9]{1,10}$", "optional"=>false, "col"=>"col-xs-6", "clearall"=>false, "label"=>lbl("mesure_dpd"), "display"=>["list", "form", "div"] ],
    ["name"=>"FarPDL","type"=>"double","regex"=>"^[0-9]{1,10}\.[0-9]{1,2}|[0-9]{1,10}$", "optional"=>false, "col"=>"col-xs-6", "clearall"=>false, "label"=>lbl("mesure_dpg"), "display"=>["list", "form", "div"] ]
  ]
];

$schemas->activites=
[  
  "name"=>"activites",
  "database"=>$databases["bi"],
  "table"=>"activites",
  "structure"=>
  [
    ["name"=>"name", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-12", "clearall"=>false, "label"=>lbl("act_name"), "display"=>["list", "form", "div"] ],
    ["name"=>"for_work", "type"=>"boolean","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-12", "clearall"=>false, "label"=>lbl("for_work"), "display"=>["list", "form", "div"] ],
    ["name"=>"for_home", "type"=>"boolean","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-12", "clearall"=>false, "label"=>lbl("for_home"), "display"=>["list", "form", "div"] ],
    ["name"=>"use_prog", "type"=>"boolean","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-12", "clearall"=>false, "label"=>lbl("use_prog"), "display"=>["list", "form", "div"] ],
    ["name"=>"use_photo", "type"=>"boolean","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-12", "clearall"=>false, "label"=>lbl("use_photo"), "display"=>["list", "form", "div"] ],
    ["name"=>"use_pola", "type"=>"boolean","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-12", "clearall"=>false, "label"=>lbl("use_pola"), "display"=>["list", "form", "div"] ],
    ["name"=>"use_glass", "type"=>"boolean","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-12", "clearall"=>false, "label"=>lbl("use_glass"), "display"=>["list", "form", "div"] ]
  ]
];

$schemas->commandes=
[  
  "name"=>"commandes",
  "database"=>$databases["devis"],
  "table"=>"commandes",
  "structure"=>
  [
    ["name"=>"magasin", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-12", "clearall"=>false, "label"=>lbl("magasin"), "display"=>["list", "form", "div"] ],
    ["name"=>"nr_equipement", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-12", "clearall"=>false, "label"=>lbl("equipement"), "display"=>["list", "form", "div"] ],
    ["name"=>"nr_commande", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-12", "clearall"=>false, "label"=>lbl("commande"), "display"=>["list", "form", "div"] ],
    ["name"=>"nr_peniche", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-12", "clearall"=>false, "label"=>lbl("peniche"), "display"=>["list", "form", "div"] ],
    ["name"=>"date_cree", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-12", "clearall"=>false, "label"=>lbl("date_cree"), "display"=>["list", "form", "div"] ],
    ["name"=>"date_verre_recu", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-12", "clearall"=>false, "label"=>lbl("date_verres_recus"), "display"=>["list", "form", "div"] ],
    ["name"=>"nom_flux", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-12", "clearall"=>false, "label"=>lbl("flux"), "display"=>["list", "form", "div"] ],
    ["name"=>"designation_d", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-12", "clearall"=>false, "label"=>lbl("verre"), "display"=>["list", "form", "div"] ],
    ["name"=>"nom_etat", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-12", "clearall"=>false, "label"=>lbl("etat"), "display"=>["list", "form", "div"] ],
    ["name"=>"remarque", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-12", "clearall"=>false, "label"=>lbl("Remarque"), "display"=>["list", "form", "div"] ],
    ["name"=>"fournisseur_statut", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-12", "clearall"=>false, "label"=>lbl("etat_fournisseur"), "display"=>["list", "form", "div"] ],
  ]
];


$schemas->commandes_mag=
[  
  "name"=>"commandes_mag",
  "database"=>$databases["devis"],
  "table"=>"commandes",
  "structure"=>
  [
    ["name"=>"nr_equipement", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-3", "clearall"=>false, "label"=>lbl("Nr equip"), "display"=>["list", "form", "div"] ],
    ["name"=>"nr_commande", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-3", "clearall"=>false, "label"=>lbl("Nr Cmnd"), "display"=>["list", "form", "div"] ],
    ["name"=>"nr_peniche", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-3", "clearall"=>false, "label"=>lbl("Nr peniche"), "display"=>[ "form", "div"] ],
    ["name"=>"nr_facture", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-3", "clearall"=>false, "label"=>lbl("Nr facture"), "display"=>[ "form", "div"] ],
    ["name"=>"nr_client", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-3", "clearall"=>false, "label"=>lbl("Nr client"), "display"=>[ "form", "div"] ],
    
    
    ["name"=>"vendeur", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-3", "clearall"=>false, "label"=>lbl("Vendeur"), "display"=>["list","form", "div"] ],
    ["name"=>"montant_en_cours", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-3", "clearall"=>false, "label"=>lbl("Montant en cours"), "display"=>["form", "div"] ],
    
    ["name"=>"date_cree", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-3", "clearall"=>true, "label"=>lbl("Date cree"), "display"=>["list", "form", "div"] ],
    ["name"=>"date_commande", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-3", "clearall"=>false, "label"=>lbl("Date commande"), "display"=>["form", "div"] ],
    ["name"=>"date_prevue", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-3", "clearall"=>false, "label"=>lbl("Date prevue"), "display"=>["list", "form", "div"] ],
    
    ["name"=>"fournisseur", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-3", "clearall"=>false, "label"=>lbl("Fourn."), "display"=>["list","form", "div"] ], 
    ["name"=>"fournisseur_statut", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-3", "clearall"=>false, "label"=>lbl("etat_fournisseur"), "display"=>["list","form", "div"] ],
    ["name"=>"fournisseur_date", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-3", "clearall"=>false, "label"=>lbl("date_fournisseur"), "display"=>["form", "div"] ],
    ["name"=>"date_verre_recu", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-3", "clearall"=>false, "label"=>lbl("date_verres_recus"), "display"=>["list", "form", "div"] ],
    
    ["name"=>"emploi", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-2", "clearall"=>true, "label"=>lbl("Emploi"), "display"=>[ "form", "div"] ],
    ["name"=>"remarque", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-10", "clearall"=>false, "label"=>lbl("Remarque"), "display"=>["list", "form", "div"] ],
    
    ["name"=>"designation_d", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-5", "clearall"=>true, "label"=>lbl("verre droit"), "display"=>["list", "form", "div"] ],
    ["name"=>"designation_g", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-5", "clearall"=>false, "label"=>lbl("verre gauche"), "display"=>[ "form", "div"] ],
    ["name"=>"couloir_progression", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-2", "clearall"=>false, "label"=>lbl("corridor"), "display"=>[ "form", "div"] ],
    
    ["name"=>"sphere_d", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-1 col-xs-offset-1", "clearall"=>true, "label"=>lbl("sph D"), "display"=>[ "form", "div"] ],
    ["name"=>"cylindre_d", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-1", "clearall"=>false, "label"=>lbl("cyl D"), "display"=>[ "form", "div"] ],
    ["name"=>"axe_d", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-1", "clearall"=>false, "label"=>lbl("axe D"), "display"=>[ "form", "div"] ],
    ["name"=>"addition_d", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-1", "clearall"=>false, "label"=>lbl("add D"), "display"=>[ "form", "div"] ],
    ["name"=>"diametre_d", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-1", "clearall"=>false, "label"=>lbl("diam D"), "display"=>[ "form", "div"] ],
    ["name"=>"prisme_d", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-1", "clearall"=>false, "label"=>lbl("prism D"), "display"=>[ "form", "div"] ],
    ["name"=>"base_d", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-1", "clearall"=>false, "label"=>lbl("base D"), "display"=>[ "form", "div"] ],
    ["name"=>"ecart_d", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-1", "clearall"=>false, "label"=>lbl("ecart D"), "display"=>[ "form", "div"] ],
    ["name"=>"hauteur_d", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-1", "clearall"=>false, "label"=>lbl("hauteur D"), "display"=>[ "form", "div"] ],
    ["name"=>"etat_d", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-1", "clearall"=>false, "label"=>lbl("etat D"), "display"=>["list", "form", "div"] ],
    
    
    ["name"=>"sphere_g", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-1 col-xs-offset-1", "clearall"=>true, "label"=>lbl("sph G"), "display"=>[ "form", "div"] ],
    ["name"=>"cylindre_g", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-1", "clearall"=>false, "label"=>lbl("cyl G"), "display"=>[ "form", "div"] ],
    ["name"=>"axe_g", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-1", "clearall"=>false, "label"=>lbl("axe G"), "display"=>[ "form", "div"] ],
    ["name"=>"addition_g", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-1", "clearall"=>false, "label"=>lbl("add G"), "display"=>[ "form", "div"] ],
    ["name"=>"diametre_g", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-1", "clearall"=>false, "label"=>lbl("diam G"), "display"=>[ "form", "div"] ],
    ["name"=>"prisme_g", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-1", "clearall"=>false, "label"=>lbl("prism G"), "display"=>[ "form", "div"] ],
    ["name"=>"base_g", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-1", "clearall"=>false, "label"=>lbl("base G"), "display"=>[ "form", "div"] ],
    ["name"=>"ecart_g", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-1", "clearall"=>false, "label"=>lbl("ecart G"), "display"=>[ "form", "div"] ],
    ["name"=>"hauteur_g", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-1", "clearall"=>false, "label"=>lbl("hauteur G"), "display"=>[ "form", "div"] ],
    ["name"=>"etat_g", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-1", "clearall"=>false, "label"=>lbl("etat G"), "display"=>[ "form", "div"] ],
    
    
    ["name"=>"nom_flux", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-3", "clearall"=>false, "label"=>lbl("flux"), "display"=>["list", "form", "div"] ],
    ["name"=>"nom_etat", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-3", "clearall"=>false, "label"=>lbl("etat"), "display"=>["list", "form", "div"] ],
    ["name"=>"code_casse", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-3", "clearall"=>false, "label"=>lbl("code casse"), "display"=>["form", "div"] ],
    ["name"=>"code_monteur", "type"=>"string","regex"=>"^.*$", "optional"=>false, "col"=>"col-xs-3", "clearall"=>false, "label"=>lbl("monteur"), "display"=>["form", "div"] ],
   
    
   ]
];

 


?>