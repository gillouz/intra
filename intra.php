<?php


function client_selector_form($param)
{

  $nr_client="";
  if( isset($param["nr_client"])) $nr_client=$param["nr_client"];
  
  $actions=[];
  if( isset($param["actions"])) $actions=$param["actions"];
  
  //<label class='control-label'>".nano\lbl("nr_client")."</label>
  
  $html.="
  <div id='client_choosed_div' onclick='client_selector.change()'></div>
  <div id='client_number_div' class='form-group col-xs-6 col-xs-offset-3'>
    <div id='logo' class='col-xs-8 col-xs-offset-2 col-md-6 col-md-offset-3' ><br><img src='img_logo.png' class='img-responsive' alt='Responsive image'><br></div>
    <input id='client_number_input' placeholder='".nano\lbl("find by name code dob...")."' class='form-control' name='nr_client' type='text' value='$nr_client' onchange='client_selector.find()' >
  </div>
  <div id='client_selector_actions'>
  <div id='client_choosed_edit_div' class='form-group col-xs-6 col-md-3'>
    <label class='control-label' >&nbsp;</label>
    <button class='btn btn-default col-xs-12'  onclick='client_selector.change()' >".nano\lbl("client_change")."</button>
  </div>
  <div id='client_choosed_edit_div' class='form-group col-xs-6 col-md-3'>
    <label class='control-label' >&nbsp;</label>
    <button class='btn btn-default col-xs-12'  onclick='intra.client.edit()' >".nano\lbl("client_edit")."</button>
  </div>
  ";
  
  // Display user buttons
  foreach($actions as $a)
  {
    $col="col-xs-6";
    if(isset($a["col"])) $col=$a["col"];
    
    $onclick='nano_submit';
    if(isset($a["onclick"])) $onclick=$a["onclick"];
    
    $btn="btn-primary";
    if(isset($a["btn"])) $btn=$a["btn"];
    
    $id="";
    if(isset($a["id"])) $id="id='".$a["id"]."'";
    
    if(isset($a["clearall"])) if($a["clearall"]==true) $html.="<div style='clear:both'></div>";
    
    switch($a["type"])
    {
        case "button":
          $html.="<div $id class='form-group $col' >";
          $html.="<label class='control-label' >&nbsp;</label>";
          $html.="<input type='button' nano_type='button' class='form-control btn $btn' name='".$a["name"]."' onclick='$onclick' value='".$a["label"]."'>";
          $html.="</div>";
          break;
    }
  }
  $html.="</div>";

  $html.="<script>
  // Clients
  intra.client.id=document.getElementById(\"client_number_input\");
  intra.client.div=document.getElementById(\"client_choosed_div\");
  
  var client_selector={};
  
  client_selector.find=function()
  {
    intra.client.find();
    $(\"#client_number_div\").hide();
    $(\"#client_choosed_div\").show();
    $(\"#client_selector_actions\").show();
    
  }
  
  client_selector.change=function()
  {
    intra.client.id.value=''; 
    $(\"#client_number_div\").show();
    $(\"#client_choosed_div\").hide();
    $(\"#client_selector_actions\").hide();
    
    intra.client.dismiss();
  }
  
  if(intra.client.id.value!='' && intra.client.id.value!=0 )
  { 
    client_selector.find();
  }
  else
  {
    client_selector.change();
  }
  
  </script>";

  $html.=client_selector();

  return $html;


}


function client_selector()
{
  $schemas=$GLOBALS["schemas"];
  
  $html="";
  
  // create the form for client edit  
  $action=
  [
    ["name"=>"ok", "label"=>"OK", "col"=>"col-xs-3","type"=>"button", "onclick"=>"intra.client.create();" ],
  ]; 
  $form=nano\form($schemas->client,$action,[],"client_edit_form");

  // edit form
  $html.=nano\modal("client_edit_popup",nano\lbl("client_edit"),$form,"");

  //client list
  $html.=nano\modal("client_choose_popup",nano\lbl("client_choose"),"<div id='client_list_div'></div>","");

  $html.="<script>
  
  intra.telsearch.name=document.client_edit_form.nom;
  intra.telsearch.firstname=document.client_edit_form.prenom;
  intra.telsearch.street=document.client_edit_form.adresse1;
  intra.telsearch.streetno=document.client_edit_form.numrue;
  intra.telsearch.zip=document.client_edit_form.npa;
  intra.telsearch.city=document.client_edit_form.localite;
  //intra.telsearch.canton=document.client_edit_form.canton;
  intra.telsearch.phone=document.client_edit_form.tel1;
  
  </script>";
  
  $html.=telsearch_selector();
  
  return $html;
  
}

function client_find($find)
{
    $nr_client="";
    $databases=$GLOBALS["databases"];

    // Objet de retour
    $return=(object) array();
    $return->start=microtime();

    $column="
    Nr_client as numclient,
    numavs as numavs,
    Nom as nom,
    Prenom as prenom,
    DATE_TO_CHAR(Client_depuis,'yyyy-Mm-Dd') as clientdepuis,
    genre as genre,
    Entreprise as entreprise,
    NPA as npa,
    Localite as localite,
    Adresse_1 as adresse1,
    Nr_de_rue as numrue,
    Adresse_2 as adresse2,
    Canton as canton,
    Pays as pays,
    Langue as langue,
    Profession as profession,
    DATE_TO_CHAR(Date_naissance,'yyyy-Mm-Dd') as datenaissance,
    CAST(Ddn_estime AS INT) as ddnestime,
    Telephone_1 as tel1,
    Telephone_2 as tel2,
    Telephone_portable as mobile,
    adresse_email as email,
    Remarque as remarque,
    CAST(Accepte_mailing AS INT) as mailing,
    CAST(OKMailingAudio AS INT) as okmailingaudio,
    CAST(OKEMailing AS INT) as okemailing,
    CAST(Accepte_ACP AS INT) as acp
    ";

    switch(true)
    {
    case $nr_client!="";
    $query="select $column FROM Clients WHERE Nr_client=$nr_client;";
    break;
    case $find!="";
    // construction de la requette
    $query="select $column FROM Clients";
    $wand=" where ";
    $query_close="";
    $first_number=true;
    $find_array=explode(" ",$find);
    $parts_used=0;

    foreach($find_array as $part)
    {
        if (strlen($part)>0)
        {
        if( preg_match("#(?=^[0-9.]*$)(?=\.)#",$part) )
        {
            if ($first_number==true)
            {
            $query.=$wand;
            $query.=" DATE_TO_CHAR(Date_naissance,'dd.mm.yyyy') like '%$part%'  ";
            $first_number=false;
            $parts_used++;
            }
        }
        else if( preg_match("#^[0-9]*$#",$part) )
        {
            if ($first_number==true)
            {
            $query.=$wand;
            $query.=" NR_CLIENT=".$part;
            $first_number=false;
            $parts_used++;
            }
        }
        else
        {
            $query.=$wand;
            $query.=" Nom_et_prenom like '%$part%'  ";
            $parts_used++;
        }
        
        $query_close.=")";
        $wand=" and ";
        }

    }
    $query.=" ORDER BY Nom;";

    if($parts_used==0)
    {
        die(nano\error($return,"Tous les texte sont trop courts","-1"));
    }

    break;
    default:
    die(nano\error("recherche vide",-1));
    break;
    }

    // Connexion au serveur 4D
    try 
    {
    
    $db = new PDO($databases["optisphere"]["dsn"], $databases["optisphere"]["user"], $databases["optisphere"]["password"]);

    $stmt = $db->prepare($query);
    $stmt->execute() or die(nano\error($e->message,-2));

    $result=$stmt->fetchAll(PDO::FETCH_ASSOC);

    //print_r($result);
    
    // deconnexion
    unset($stmt);                                                                                                                                                                                                                    
    unset($db);
    } 
    catch (Exception $e) 
    {
    die(nano\error($e->getMessage(),-2));
    }

    if(count($result)==0) 
    {
    //die(return_error($return,"Pas de resultat","-3"));
    }
    if(count ($result)>100)
    {
    die(nano\error("trop de resultats",-4));
    }

    $return->fin=microtime();
    $return->code="1";
    $return->message="";
    $return->status="SUCCESS";
    $return->data=$result;
    
    return $return;

}


function article_selector()
{
  $schemas=$GLOBALS["schemas"];
  
  $html="";

  //article list
  $html.=nano\modal("article_choose_popup",nano\lbl("article_choose"),"<div id='article_list_div'></div>","");

  
  return $html;
  
}

function collaborateur_selector()
{
  $schemas=$GLOBALS["schemas"];
  
  $html="";

  //article list
  $html.=nano\modal("collaborateur_choose_popup",nano\lbl("collaborateur_choose"),"<div id='collaborateur_list_div'></div>","");

  
  return $html;
  
}

function telsearch_selector()
{
  $schemas=$GLOBALS["schemas"];
  
  $html="";

  //article list
  $html.=nano\modal("telsearch_choose_popup",nano\lbl("telsearch_choose"),"<div id='telsearch_list_div'></div>","");

  
  return $html;
  
}


function orl_selector()
{
  $schemas=$GLOBALS["schemas"];
  
  $html="";

  //article list
  $html.=nano\modal("orl_choose_popup",nano\lbl("orl_choose"),"<div id='orl_list_div'></div>","");

  return $html;
  
}



function ordonnance_selector()
{
  $schemas=$GLOBALS["schemas"];
  
  $html="";

  //article list
  $html.=nano\modal("ordonnance_choose_popup",nano\lbl("ordonnance_choose"),"<div id='ordonnance_list_div'></div>","");

  
  return $html;
  
}


function ordonnance_form()
{
    $lbl=$GLOBALS['lbl'];
 
 
    $html="<!-- Ordonnance -->

    <div class='row'>
    <div class='form-group col-md-3'>
        <label class='control-label'>&nbsp;</label>
        <button class='form-control btn btn-primary' type='button' onclick='ordonnance_find();'>".nano\lbl("prescription_find")."</button>
    </div>
    </div>
    <br>

    <form name='ordonnance_form'>

    <!-- right eye -->
    <div class='row'>
    <div class='form-group col-xs-2'>
    <label class='control-label' >".nano\lbl("sph")."</label>
    <input name='sphere_d'  class='form-control' type='text' pattern='(^[-+][0-9]{1,2}\.?$)|(^[-+][0-9]{1,2}\.(00|25|50|75)$)' value='' >
    </div>

    <div class='form-group col-xs-2'>
    <label class='control-label' >".nano\lbl("cyl")."</label>
    <input name='cylindre_d'  class='form-control' type='text' pattern='(^[-][0-9]{1,2}\.?$)|(^[-+][0-9]{1,2}\.(00|25|50|75)$)' value='' >
    </div>

    <div class='form-group col-xs-2'>
    <label class='control-label' >".nano\lbl("axe")."</label>
    <input name='axe_d'  class='form-control' type='text' pattern='(^[-+][0-9]{1,2}\.?$)|(^[-+][0-9]{1,2}\.(00|25|50|75)$)' value='' >
    </div>

    <div class='form-group col-xs-2'>
    <label class='control-label' >".nano\lbl("add")."</label>
    <input name='add_d'  class='form-control' type='text' pattern='(^[-+][0-9]{1,2}\.?$)|(^[-+][0-9]{1,2}\.(00|25|50|75)$)' value='' >
    </div>

    <div class='form-group col-xs-1'>
    <label class='control-label' >".nano\lbl("visus")."</label>
    <input name='visus_d'  class='form-control' type='text' pattern='(^[-+][0-9]{1,2}\.?$)|(^[-+][0-9]{1,2}\.(00|25|50|75)$)' value='' >
    </div>

    <div class='form-group col-xs-1'>
    <label class='control-label' >".nano\lbl("dp")."</label>
    <input name='dp_d'  class='form-control' type='text' pattern='(^[-+][0-9]{1,2}\.?$)|(^[-+][0-9]{1,2}\.(00|25|50|75)$)' value='' >
    </div>

    <div class='form-group col-xs-2'>
    <label class='control-label' >&nbsp;</label>
    <button  type='button' class='form-control btn btn-primary' onclick='' >".nano\lbl("copy_to_left")."</button>
    </div>


    </div>

    <!-- left eye -->
    <div class='row'>

    <div class='form-group col-xs-2'>
    <input name='sphere_g'  class='form-control' type='text' value='' >
    </div>

    <div class='form-group col-xs-2'>
    <input name='cylindre_g'  class='form-control' type='text' value='' >
    </div>

    <div class='form-group col-xs-2'>
    <input name='axe_g'  class='form-control' type='text' value='' >
    </div>

    <div class='form-group col-xs-2'>
    <input name='add_g'  class='form-control' type='text' value='' >
    </div>

    <div class='form-group col-xs-1'>
    <input name='visus_g'  class='form-control' type='text' value='' >
    </div>

    <div class='form-group col-xs-1'>
    <input name='dp_g'  class='form-control' type='text' value='' >
    </div>

    <div class='form-group col-xs-2'>
    <button  type='button' class='form-control btn btn-primary' onclick='$(\"#prism\").slideToggle();' >".nano\lbl("show_prism")."</button>
    </div>


    </div>

    <div id='prism' style='display:none'>
    <!-- right eye prism -->
    <div class='row'>

    <div class='form-group col-xs-2'>
    <label class='control-label' >".nano\lbl("prism")."</label>
    <input name='prisme_d'  class='form-control' type='text' value='' >
    </div>

    <div class='form-group col-xs-2'>
    <label class='control-label' >".nano\lbl("prism_axe")."</label>
    <input name='base_d'  class='form-control' type='text' value='' >
    </div>

    </div>

    <!-- left eye prism -->
    <div class='row'>

    <div class='form-group col-xs-2'>
    <input name='prisme_g'  class='form-control' type='text' value='' >
    </div>

    <div class='form-group col-xs-2'>
    <input name='base_g'  class='form-control' type='text' value='' >
    </div>

    </div>
    </div>

    </form>";


    return $html;
}

function courrier_list($col,$label,$name)
{
    $html="<div class='form-group $col'>";
    $html.="<label class='control-label' >$label</label>";
    $html.="<select nano_type='select' class='form-control' name='$name' onchange='courrier_open(this)'>";
    $html.="<option value=''>".nano\lbl("choose a value")."</option>";
    
    foreach(scandir(__DIR__."/template")as $key=>$template)
    {
        if ($template != "." and $template != "..") $html.="<option value=\"$template\">$template</option>";
    }
    $html.="</select>";
    $html.="</div>";
    
    return $html;

}


?>
