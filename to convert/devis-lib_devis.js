// base data
//var main=new Object(); //data de la page index
// variables globales
var lens_list;    // liste des verres
var lens_ix; 
var ordo_list;    // liste des ordonnances
var ordo_ix;
var client_list;  // liste des clients
var client_ix;
var frame_list;   // liste des montures
var frame_ix;
var article_list; // liste des articles
var article_ix;
var devis_list;   // liste des devis
var devis_ix;
var mesure_list; // list des mesures
var mesure_ix
// labels manquants
var missing_label=[] // liste des labels manquants
// devis
var devis=new Object(); // data de la page devis
devis.lines=[]; // lignes des devis
// mesure
var mesure=new Object(); // data de la page devis
//mesure.lines=[]; // lignes des devis


// ************* clients

function client_find(find)
{
  
  var callback=function (reply)
  {
    client_list=reply;
    if (client_list.data.length>1)
    {
      var param=
      {
        "onclick":"client_choose",
        "filter":[],
        "button":[],
        "noline":"<button class='btn btn-primary' onclick='client_new()'>"+lbl("client_create")+"</button>"
      }
      
      $("#client_list").html(json_to_table(schemas.client,client_list.data,param));
      $("#client_popup").modal("show");
    }
    else client_choose(0); // we do not need to ask the user to choose the client if only one match
  };
  
  ajax_call("?query=find_client&find="+find,'devis-ajax_find_client.php',callback);
}


function client_find_index(find)
{
  
  var callback=function(reply)
  {
    var param=
    {
      "onclick":"client_open",
      "buttons":[],
      "filter":[],
      "noline":"<button class='btn btn-primary' onclick='client_new()'>"+lbl("client_create")+"</button>",
      "display":"div"
    }
    
    client_list=reply;
    $("#client_list").html(json_to_div_table(schemas.client,client_list.data,param));
    $("#logo").hide(0);
    $("#client_find_index_start").hide(0);
    $("#client_find_index_after").show(0);
  };

  ajax_call("?query=find_client&find="+find,'devis-ajax_find_client.php',callback);  

}

function client_open(client)
{
  window.location="devis_client.php?nr_client="+client_list.data[client].NR_CLIENT;
}

function client_choose(client)
{
  var param=
  {
    "onclick":"client_change",
    "buttons":
    [
      { "col":"col-xs-2", "clearall":false,"display":true, "fn":"client_edit", "label":lbl("client_edit")  },
      { "col":"col-xs-2", "clearall":false,"display":true, "fn":"client_change", "label":lbl("client_change") }
    ],
    "filter":[],
    "noline":"",
    "display":"div"
  };
  
  client_ix=client;
  $("#client_choosed").html(json_to_div(schemas.client,client_list.data[client],param,client_ix));
  $('#client_find').hide();
  $("#client_popup").modal("hide");
  $("#client_choosed").show(0);
  $('#display_all').show(0);
  devis_find(client_list.data[client_ix].NR_CLIENT);
  ordo_find(client_list.data[client_ix].NR_CLIENT);
  mesure_find(client_list.data[client_ix].NR_CLIENT);
  
}

function client_edit(client)
{
  console.log(client_list.data[client]);
  form_load(client_list.data[client],document.client_edit_form)
  $("#client_edit_popup").modal("show");
}

function client_new(client)
{
  $("#client_popup").modal("hide");
  $("#client_edit_popup").modal("show");
}


function client_change(client)
{
  client_list={};
  $("#client_choosed").hide(0);
  $('#client_find').show(0);
}

function client_create()
{
  $("#client_quick_edit").modal("show");
}

function client_confirm_create(form)
{
  var json;
  var c;
  var data="?";
  
  var callback=function(reply)
  {
    console.log(reply);
  }

  if(form.checkValidity())
  {
    json=form_save(form);
    client_list.data[client_ix]=json;
    for(c in client_list.data[client_ix]) data+=c+"="+encodeURI(client_list.data[client_ix][c])+"&";
    ajax_call(data,'devis-ajax_save_client.php',callback);
  }
  else
  {
    alert("erreur");
    return false;
  }
  
  $("#client_edit_popup").modal("hide");
}



// ******************** Ordonnances


function ordo_usage(value)
{
  var tmp_ordo=jQuery.extend(true, {}, ordo_list.data[ordo_ix]);
  
  switch(value)
  {
    case "0": // ne rien faire
      break;
    case "1": // distance
      tmp_ordo.ADD_OD="";
      tmp_ordo.ADD_OG="";
      break;
    case "2": // lecture
      tmp_ordo.SPH_OD=parseFloat(tmp_ordo.SPH_OD)+parseFloat(tmp_ordo.ADD_OD);
      tmp_ordo.SPH_OG=parseFloat(tmp_ordo.SPH_OG)+parseFloat(tmp_ordo.ADD_OG);
      tmp_ordo.ADD_OD="";
      tmp_ordo.ADD_OG="";
      break;
    case "4": // office
      tmp_ordo.SPH_OD=parseFloat(tmp_ordo.SPH_OD)+parseFloat(tmp_ordo.ADD_OD);
      tmp_ordo.SPH_OG=parseFloat(tmp_ordo.SPH_OG)+parseFloat(tmp_ordo.ADD_OG);
      break;
  }
  form_load(tmp_ordo,document.ordo);
}

function ordo_find()
{
  
  var callback=function(reply)
  {
    var param=
    {
      "onclick":"ordo_choose",
      "buttons":[],
      "filter":[],
      "noline":"",
      "display":"div"
    }
    
    ordo_list=reply;
    $("#ordo_list_div").html(json_to_div_table(schemas.ordonnance,ordo_list.data,param));
    $("#ordo_popup").modal("show");
  };

  if (client_list.data[client_ix].NR_CLIENT)
  {
    ajax_call("?query=find_ordo&NR_CLIENT="+client_list.data[client_ix].NR_CLIENT,'devis-ajax_find_ordo.php',callback);
  }
  
}


function ordo_find_one(ordo)
{
  
  var callback=function(reply)
  {
    ordo_list=reply;
    ordo_ix=0;
    form_load(ordo_list.data[0],document.ordo);
  };
  
  if(ordo!="")
  {
    ajax_call("?query=find_ordo&NR_ORDONNANCE="+ordo,"devis-ajax_find_ordo.php",callback);
  }
}


function ordo_choose(ordo)
{
  ordo_ix=ordo;
  form_load(ordo_list.data[ordo_ix],document.ordo)
  $("#ordo_popup").modal("hide");
}


// ******************** article

function article_find_one(code)
{
  
  var callback=function(reply)
  {
    var line;
    article_list=reply;
    article_ix=0
      
    line={
      "_status":0,
      "code_article":article_list.data[0].code,
      "type_article":article_list.data[0].type_article,
      "marque":"", 
      "designation":article_list.data[0].designation,
      "info":"",
      "quantite":1,
      "prix":article_list.data[0].prix_vente
    }
    
    devis.lines.push(line);
    devis_line_display();
    document.devis.divers_code.value=""; 
  };

  ajax_call("?query=find_frame&code="+code+"&type=('D','R','K','E')",'devis-ajax_find_article.php',callback);  
}



// ******************** frame



function frame_find_one(code)
{
  var callback=function frame_find_one_reply(reply)
  {
    var line;
    frame_list=reply;
    frame_ix=0;
    
    line={
      "_status":0,
      "code_article":frame_list.data[0].code,
      "type_article":"Monture",
      "marque":frame_list.data[0].marque,
      "designation":frame_list.data[0].designation,
      "info":"Au lieu de "+frame_list.data[0].prix_vente+" jusqu'au "+frame_list.data[0].dt_fin,
      "quantite":1,
      "prix":frame_list.data[0].prix_special
    }
    
    devis.lines.push(line);
    devis_line_display();
    document.devis.frame_code.value="";
  };

  ajax_call("?query=find_frame&frame="+code,'devis-ajax_find_frame.php',callback);
}


function frame_find_one_mesure(code)
{
  var callback=function(reply)
  {
    frame_list=reply;
    frame_ix=0;

    $("#frame_find").hide();
    $("#frame_choosed").html(json_to_div(schemas.frame,frame_list.data[frame_ix],frame_ix));
  }
  
  ajax_call("?query=find_frame&frame="+code,'devis-ajax_find_frame.php',callback);
}

function frame_change_mesure()
{
  frame_list=[];
  frame_ix=null;
  
  $("#frame_find").show(0);
  $("#frame_choosed").html("");
}

function frame_change(code)
{
  $("#frame_choosed").hide();
  $("#frame_find").show();
}

// ******************** lenses

function lens_find(ordo,side)
{
  var ordo=document[ordo];
  var devis=document["devis"];
  var data="?query=find_lens";

  var callback=function(reply)
  {
    lens_list=reply;
    lens_filter();
    $("#lens_popup").modal("show");
  };

  data+="&sph_d="+ordo.SPH_OD.value;
  data+="&cyl_d="+ordo.CYL_OD.value;
  data+="&add_d="+ordo.ADD_OD.value;
  data+="&prism_d="+ordo.PRISM_OD.value;
  data+="&diam_d="+devis.diam.value;

  data+="&sph_g="+ordo.SPH_OG.value;
  data+="&cyl_g="+ordo.CYL_OG.value;
  data+="&add_g="+ordo.ADD_OG.value;
  data+="&prism_g="+ordo.PRISM_OG.value;
  data+="&diam_g="+devis.diam.value;

  data+="&side="+side;

  ajax_call(data,'devis-ajax_find_lens.php',callback);
}


function lens_filter()
{
  var filter=[];
  
  //cle_gamme
  if(document.lens_filter_form.cle_gamme.value!="") filter.push({"name":"cle_gamme", "value":document.lens_filter_form.cle_gamme.value,"op":"$eq"});
  if(document.lens_filter_form.cle_index.value!="") filter.push({"name":"cle_index","value":document.lens_filter_form.cle_index.value,"op":"$eq"});
  if(document.lens_filter_form.cle_traitement.value!="") filter.push({"name":"cle_traitement","value":document.lens_filter_form.cle_traitement.value,"op":"$eq"});
  if(document.lens_filter_form.manufacturer.value!="") filter.push({"name":"manufacturer", "value":document.lens_filter_form.manufacturer.value,"op":"$eq"});
  if(document.lens_filter_form.cle_teinte.value!="") filter.push({"name":"cle_teinte", "value":document.lens_filter_form.cle_teinte.value,"op":"$eq"});
  if(document.lens_filter_form.designation.value!="") filter.push({"name":"designation", "value":document.lens_filter_form.designation.value,"op":"$lk"});
  if(document.lens_filter_form.lens_code.value!="") filter.push({"name":"lens_code", "value":document.lens_filter_form.lens_code.value,"op":"$eq"});
    
  var param=
  {
    "filter":filter,
    "onclick":"lens_choose"
  }
  
  $("#find_lens_list").html(json_to_table(schemas.lens,lens_list.data,param));
  
}

function lens_change()
{
  $("#lens_d_find").show();
  $("#lens_g_find").show();
  $("#lens_find").show();
  $("#lens_d_choosed").hide();
  $("#lens_g_choosed").hide();
}

function lens_choose(lens)
{
  var line;
  lens_ix=lens;
  
  $("#lens_popup").modal("hide");
  
  // setup a filter for the other lens (so he user just have to make one click)
  document.lens_filter_form.reset(); 
  document.lens_filter_form.lens_code.value=lens_list.data[lens_ix].lens_code; //main.lens_code; 
  document.lens_filter_form.manufacturer.value="";
      
  switch(lens_list.side)
  {
    case 1:
      
      line={
        "_status":0,
        "code_article":lens_list.data[lens].code_article_d,
        "type_article":"Verre droit",
        "marque":"Verre",
        "designation":lens_list.data[lens].designation,
        "info":lbl(lens_list.data[lens].cle_gamme)+", "+lbl(lens_list.data[lens].cle_index)+", "+lbl(lens_list.data[lens].cle_teinte)+", "+lbl(lens_list.data[lens].cle_traitement),
        "quantite":1,
        "prix":lens_list.data[lens].pv3_d,
        "diametre":lens_list.data[lens].diam_d
      }
      devis.lines.push(line);
      break;
    case 2:
      
      line={
        "_status":0,
        "code_article":lens_list.data[lens].code_article_g,
        "type_article":"Verre gauche",
        "marque":"Verre",
        "designation":lens_list.data[lens].designation,
        "info":lbl(lens_list.data[lens].cle_gamme)+", "+lbl(lens_list.data[lens].cle_index)+", "+lbl(lens_list.data[lens].cle_teinte)+", "+lbl(lens_list.data[lens].cle_traitement),
        "quantite":1,
        "prix":lens_list.data[lens].pv3_g,
        "diametre":lens_list.data[lens].diam_g
      }
      devis.lines.push(line);
      break;
    case 3:
      
      line={
        "_status":0,
        "code_article":lens_list.data[lens].code_article_d,
        "marque":"Verre",
        "designation":lens_list.data[lens].designation,
        "info":lbl(lens_list.data[lens].cle_gamme)+", "+lbl(lens_list.data[lens].cle_index)+", "+lbl(lens_list.data[lens].cle_teinte)+", "+lbl(lens_list.data[lens].cle_traitement),
        "quantite":1,
        "prix":lens_list.data[lens].pv3_d,
        "diametre":lens_list.data[lens].diam_d
      };
      
      devis.lines.push(line);
      
      line={
        "_status":0,
        "code_article":lens_list.data[lens].code_article_g,
        "marque":"Verre",
        "designation":lens_list.data[lens].designation,
        "info":lbl(lens_list.data[lens].cle_gamme)+", "+lbl(lens_list.data[lens].cle_index)+", "+lbl(lens_list.data[lens].cle_teinte)+", "+lbl(lens_list.data[lens].cle_traitement),
        "quantite":1,
        "prix":lens_list.data[lens].pv3_g,
        "diametre":lens_list.data[lens].diam_g
      };
      
      devis.lines.push(line);
      break;
  }

 
  devis_line_display();
  
}

// ************* Devis


function devis_new()
{
  window.location="devis_devis.php?NR_CLIENT="+client_list.data[client_ix].NR_CLIENT; //+"&NR_ORDONNANCE="+ordo_list.data[ordo_ix].NR_ORDONNANCE;
}

function devis_edit(devis)
{
  window.location="devis_devis.php?NR_DEVIS="+devis_list.data[devis]._id;
}

function devis_init()
{
  // initialisation de la page en fonction de données disponibles
  if (nr_devis!="")
  {
    devis_find_one(nr_devis);
  }
  else
  {
    ordo_find_one(nr_ordonnance);
    client_find_one(nr_client);
    devis_line_display();
  }
}

function devis_line_display()
{
  var param=
  {
    "onclick":"devis_line_delete",
    "noline":lbl("devis_noline"),
    "buttons":[],
    "footer":
    [
      {"name":"prix","op":"sum"},
      {"name":"quantite","op":"sum"},
    ]
  }
  
  $("#devis_line_div").html(json_to_table(schemas.lines,devis.lines,param));
}

function devis_line_delete(line)
{
  devis.lines[line]._status=9;
  devis_line_display()
}

function devis_save(next)
{
  var reg=new RegExp("@");
 
  devis.client=client_list.data[client_ix]; //main.client;
  devis.nr_client=client_list.data[client_ix].NR_CLIENT;
  devis.ordo=ordo_list.data[ordo_ix];
  devis.nr_ordonnance=ordo_list.data[ordo_ix].NR_ORDONNANCE;
  devis.nr_mesure=0;
  devis.magasin={};
  
  if(devis.lines.length>0)
  {  
    var reply_function;
    var callback;
    
    switch(next)
    {
      case "email":
        
        // if there is no email edit the client 
        if(!devis.client.ADRESSE_EMAIL.match(reg))
        {
          client_edit(0);
          return;
        }
        
        callback=function(reply)
        {
            devis=reply.data[0];
            devis_email();
        };
        
        break;
      case "print":
      default:
        
        callback=function(reply)
        {
          devis=reply.data[0];
          window.open('devis_pdf.php?logo=0&nr_devis='+devis._id);
        }
        
        break;
    }
    
    // ajouter le total du devis
    var operation=
    [
      {"name":"prix","op":"sum"},
      {"name":"quantite","op":"sum"},
    ];
    
    var totaux=json_total(operation,devis.lines);
    devis.total=totaux.prix.value;
    devis.nbr=totaux.quantite.value;
    
    // date du devis
    if(!devis.date_cree) devis.date_cree=today();
    devis.date_modif=today();
    
    var dataset=[];
    dataset.push(devis);
    nano_save('devis',dataset,callback);
    
  }
  else
  {
    alert(lbl("no_lines"));
  }
}




function devis_email()
{
  var data="?nr_devis="+devis._id; 
  data+="&client_email="+devis.client.ADRESSE_EMAIL;
  data+="&client_name="+devis.client.NOM;
  data+="&client_surname="+devis.client.PRENOM;
  data+="&client_genre="+devis.client.GENRE;

  var callback=function(reply)
  {
    alertmsg("success","alert_success")
    window.location="devis_client.php?nr_client="+client_list.data[client_ix].NR_CLIENT;
  };

  
  ajax_call(data,'devis-ajax_send_email.php',callback);
}


function devis_find_one(nr_devis)
{

  var callback=function(reply)
  {
    devis=jQuery.extend(true, {}, reply.data[0]);
    devis_line_display();
    ordo_find_one(devis.nr_ordonnance);
    client_find(devis.nr_client);
  };

  nano_load('devis',{ "_id":nr_devis },callback)
}


function devis_find(nr_client)
{
  var callback=function(reply)
  {
    var param=
    {
      "onclick":"devis_edit",
      "buttons":[],
      "filter":[],
      "noline":"",
      "display":"well"
    }
    
    devis_list=jQuery.extend(true, {}, reply);
    $("#devis_list_div").html(json_to_div_table(schemas.devis,devis_list.data,param));
  };
  nano_load('devis',{ "nr_client":nr_client },callback);
}

// ******************* mesures

function mesure_new()
{
  window.location="devis_mesure.php?nr_client="+client_list.data[client_ix].NR_CLIENT;
}

function mesure_save(form)
{
  var json;
  var c;
  var data="?";

  var callback=function(reply)
  {
    mesure_list=jQuery.extend(true, {}, reply);
    mesure_ix=0;
    window.location="devis_client.php?nr_client="+client_list.data[client_ix].NR_CLIENT;
  }

  if(typeof client_list != 'undefined' && typeof frame_list != 'undefined')
  {
    if(form.checkValidity())
    {
      json=form_save(form);
      json.client=client_list.data[client_ix];
      json.frame=frame_list.data[frame_ix];
      json.nr_client=client_list.data[client_ix].NR_CLIENT;
      json.nr_frame=frame_list.data[frame_ix].code;
      
      mesure=jQuery.extend(true, {}, json);
      
      // add the date
      if(!mesure.date_cree) mesure.date_cree=today();
      mesure.date_modif=today();
      
      var dataset=[];
      dataset.push(mesure);
      nano_save('mesures',mesure,callback);
      
    }
    else
    {
      alert("erreur");
      return false;
    }
  }
  //$("#client_edit_popup").modal("hide");
}


function mesure_find(nr_client)
{
  
  var callback=function(reply)
  {
    var param=
    {
      "onclick":"mesure_choose",
      "buttons":[],
      "filter":[],
      "noline":"",
      "display":"well"
    }
    
    mesure_list=jQuery.extend(true, {}, reply);
    $("#mesure_list_div").html(json_to_div_table(schemas.mesures,mesure_list.data,param));
  }

  nano_load('mesures',{ "nr_client":nr_client },callback)
}

