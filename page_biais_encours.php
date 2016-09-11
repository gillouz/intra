<?php //idx//Devis//
session_start();
include("nanofw/nano.php");
nano\init("page");

// header
echo nano\header();

//Navbar
echo nano\navbar(["index"=>"index_audio.php"]);

echo "<div class='container-fluid'>";

echo "<ul class='nav nav-tabs'>
  <li ><a data-toggle='tab' href='#devis_list_tab'>".nano\lbl("biais")."</a></li>
  <li><a data-toggle='tab' href='#mesure_list_tab'>".nano\lbl("biais_encours")."</a></li>
    
  <li class='active' ><a data-toggle='tab' href='#ordo_list_tab' >".nano\lbl("statistiques")."</a></li>
</ul>";

echo "<div class='tab-content'>";

echo "<!-- liste des biais --><div id='devis_list_tab' class='tab-pane fade '><br>";
echo nano\quickList("biais",[]);  
echo "</div>";

// list des en cours

echo "<!-- liste des encours --><div id='mesure_list_tab' class='tab-pane fade'><br>";
echo nano\quickList("biais_encours",[]);
echo "</div>";

// liste des ordonnances

echo "<!-- liste des ordo --><div id='ordo_list_tab' class='tab-pane fade in active'><br>";

echo "<div class='row'>
<div class='col-xs-3'>".nano\lbl("taux_bino_encours").":&nbsp;<b id='tx1'></b></div>
<div class='col-xs-3'>".nano\lbl("taux_bino_facture").":&nbsp;<b id='tx2' ></b></div>
<div>";

echo "<div id='chart_div' class='col-xs-12 text-center'></div>";
echo "</div>";

echo "</div>"; // tab-content

echo "<script type='text/javascript'>

function courrier_open(courrier)
{

    if (courrier.value!='')
    {    
	var form=courrier.form;
	var schema=form.attributes.nano_schema.value;
        var biais=schemas[schema].data[schemas[schema].ix]._id;
	var mode=form.attributes.nano_mode.value;
	var valide=false;

	//console.log(form);
	
	//if(form._status.value>=7) mode='lock';

	biais_encours_save_callback=function()
	{
	  window.location='ajax_save_courrier.php?biais='+biais+'&courrier='+courrier.value;
	  biais_encours_save_callback=function() {};
	}
	
	switch(mode)
	{
	  case 'new':
	  case 'edit':
	    var valide=biais_encours_save(form);
	    break;
	  case 'find':
	  case 'lock':
	    break;
	}
    }
}

function appareil_d_find(field)
{
  intra.article.id=document.biais_encours_edit_form.appareil_d_code;
  intra.article.brand=document.biais_encours_edit_form.appareil_d_marque;
  intra.article.designation=document.biais_encours_edit_form.appareil_d_designation;
  intra.article.nr_metas=document.biais_encours_edit_form.appareil_d_metas;
  intra.article.pv=document.biais_encours_edit_form.appareil_d_pv;

  if(field.name=='appareil_d_code')  intra.article.find(field.value,'code','AA',0);
    if(field.name=='appareil_d_designation')  intra.article.find(field.value,'designation','AA',0);

}

function appareil_g_find(field)
{
  intra.article.id=document.biais_encours_edit_form.appareil_g_code;
  intra.article.brand=document.biais_encours_edit_form.appareil_g_marque;
  intra.article.designation=document.biais_encours_edit_form.appareil_g_designation;
  intra.article.nr_metas=document.biais_encours_edit_form.appareil_g_metas;
  intra.article.pv=document.biais_encours_edit_form.appareil_g_pv;

    if(field.name=='appareil_g_code')  intra.article.find(field.value,'code','AA',0);
    if(field.name=='appareil_g_designation')  intra.article.find(field.value,'designation','AA',0);

}

function prestation_find(field)
{
  intra.article.id=document.biais_encours_edit_form.prestation_code;
  //intra.article.brand=document.biais_encours_edit_form.prestation_marque;
  intra.article.designation=document.biais_encours_edit_form.prestation_designation;
  intra.article.pv=document.biais_encours_edit_form.prestation_pv;

  if(field.name=='prestation_code')  intra.article.find(field.value,'code','SA',0);
  if(field.name=='prestation_designation')  intra.article.find(field.value,'designation','SA',0);

}


function autre_1_find(field)
{
  intra.article.id=document.biais_encours_edit_form.autre_1_code;
  //intra.article.brand=document.biais_encours_edit_form.autre_1_marque;
  intra.article.designation=document.biais_encours_edit_form.autre_1_designation;
  intra.article.pv=document.biais_encours_edit_form.autre_1_pv;

  if(field.name=='autre_1_code')  intra.article.find(field.value,'code','',0);
  if(field.name=='autre_1_designation')  intra.article.find(field.value,'designation','',0);
  
}

intra.article.choose_callback=function()
{
  biais_encours_total();
}


function biais_encours_total()
{
  var form=document.biais_encours_edit_form;
  
  var pvd=parseFloat(form.appareil_d_pv.value);
  var pvg=parseFloat(form.appareil_g_pv.value);
  var pvp=parseFloat(form.prestation_pv.value);
  var pv1=parseFloat(form.autre_1_pv.value);
  var pv2=parseFloat(form.autre_2_pv.value);

  if(isNaN(pvd)) pvd=0;
  if(isNaN(pvg)) pvg=0;
  if(isNaN(pvp)) pvp=0;
  if(isNaN(pv1)) pv1=0;
  if(isNaN(pv2)) pv2=0;
  
  form.montant_prevu.value=pvd+pvg+pvp+pv1+pv2;

}


function taux_bino_load()
{
    var d=new Date()
    var y=d.getFullYear();
    var query;
    var callback;
    
    query=
    {
        'status':{'\$eq':'ENC'},
        'date_facture_prevue':{'\$year':'','\$gte':y},
        '\$columns':
        {
            'biais.mono_bino':{'\$aggregate':'avg'}
        },
        '\$groupby':
        {
            'date_facture_prevue':{'\$year':''} 
        } 
    };

    callback=function(reply)
    { 
        var taux;
        
        console.log(reply); 
        if(reply[0])
        {
            taux=(reply[0].mono_bino-1)*100;
        
            $('#tx1').html(taux.toFixed(0)+'%');
        }
        else
        {
            $('#tx1').html('0');
        }
    };
    
    nano.load('biais_encours',query,callback);
    
    query=
    {
        'status':{'\$eq':'FAC'},
        'date_facture_prevue':{'\$year':'','\$gte':y},
        '\$columns':
        {
            'biais.mono_bino':{'\$aggregate':'avg'}
        },
        '\$groupby':
        {
            'date_facture_prevue':{'\$year':''} 
        } 
    };

    callback=function(reply)
    { 
        var taux;
        
        console.log(reply); 
        if(reply[0])
        {
            taux=(reply[0].mono_bino-1)*100;
        
            $('#tx2').html(taux.toFixed(0)+'%');
        }
        else
        {
            $('#tx2').html('0%');
        }
        
    };
    
    nano.load('biais_encours',query,callback);
    
    
}

function previsionnel_load()
{
  
  var callback=function(reply)
  {
    var graph=nano.dataToGraph(reply,false);
    var c;
    
    console.log(graph);
    
    graph.columns=
    [
        {'type':'string','name':'Mois'},
        {'type':'number','name':'CA n-1'},
        {'type':'number','name':'CA n'},
        {'type':'number','name':'CA prevu'}
    ]
    
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart()
    {
      var options = 
      {
        'title':'Ventes et previsionnel',
        'height':600,
        pointSize: 5,
        legend: 'none',
        vAxis: 
        { 
	  minValue: 0,
	  gridlines: 
	  {
	      color: '#f3f3f3',
	      count: 5
	  }
        }
        
      }; 
      
      // Create the data table.
      var data = new google.visualization.DataTable();
      
      for(c in graph.columns)
      {
          data.addColumn(graph.columns[c].type, nano.lbl(graph.columns[c].name) );
      }
      
      data.addRows(graph.rows);

      var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));   //LineChart   //ColumnChart      
      chart.draw(data, options);
      
    }
    
    
  };
  
  nano.ajax('','ajax_find_biais_previsionnel.php?',callback);
  
}


//wrapping biais functions

// Clients
function biais_client_find(field)
{
  intra.client.id=document.biais_edit_form.nr_client;
  intra.client.detail=document.biais_edit_form.client_detail;
  intra.client.find(field)
}

function biais_client_edit()
{
  intra.client.id=document.biais_edit_form.nr_client;
  intra.client.detail=document.biais_edit_form.client_detail;
  intra.client.edit()
}

// Collaborateur
function biais_collab_find(field)
{
  intra.collaborateur.find_collab(field)
}

//orl
function biais_orl_find(field)
{
  intra.orl.name=field;
  intra.orl.data=document.biais_edit_form.orldata;
  //intra.collaborateur.find_orl(field)
  intra.orl.find();
}

//wrapping biais_encours functions

// Clients
function biais_encours_client_find(field)
{
  intra.client.id=document.biais_encours_edit_form.nr_client;
  intra.client.detail=document.biais_encours_edit_form.client_detail;
  intra.client.find(field)
}

function biais_encours_client_edit()
{
  intra.client.id=document.biais_encours_edit_form.nr_client;
  intra.client.detail=document.biais_encours_edit_form.client_detail;
  intra.client.edit()
}


// Collaborateur
function biais_encours_collab_find(field)
{
  intra.collaborateur.find_collab(field)
}

//orl
function biais_encours_orl_find(field)
{
  intra.collaborateur.find_orl(field)
}

previsionnel_load();
taux_bino_load();

</script>";

echo "</div>";


echo client_selector();

echo orl_selector();

echo collaborateur_selector();

echo article_selector();

echo nano\footer();

?>
