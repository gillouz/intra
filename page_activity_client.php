<?php 
session_start();
include("nanofw/nano.php");
nano\init("page");

$nr_client=0;
if(isset($_GET['nr_client'])) $nr_client = htmlspecialchars($_GET['nr_client'],ENT_QUOTES,'ISO-8859-1'); 

// Header
echo nano\header();

// Navbar
echo nano\navbar(0);

// open Bootstrap container
echo "<div class='container-fluid'>";

echo "<div class='row' >";

$param=
[
"nr_client"=>$nr_client,
"callback"=>"on_client_choose",
];

//"actions"=>[[ "type"=>"button", "col"=>"col-xs-3", "onclick"=>"client_activity_save()", "name"=>"save", "label"=>nano\lbl("save") ]],

echo client_selector_form($param);

echo "<hr class='col-xs-12'>

</div>";

echo "<div class='row'></i><div id='activity_list_div'></div></div>";

//echo client_selector_script($nr_client);

echo "<script>
//
//var nr_client=$nr_client;
schemas.activity.data=[];
schemas.activity.ix=-1;
schemas.client_activity.data=[];
schemas.client_activity.ix=-1;

intra.client.choose_callback=function()
{
  activity_load();
}

intra.client.dismiss_callback=function()
{
  $('#activity_list_div').html('');
}

function activity_load()
{
  var query={};
  nano.load('activity',query,activity_reply);
}

function client_activity_load()
{
  var nr_client=schemas.client.data[schemas.client.ix].numclient;
  
  //console.log(schemas.client.data[schemas.client.ix].numclient);

  var query={\"nr_client\":{\"\$eq\":nr_client}};
  nano.load('client_activity',query,client_activity_reply);
}

function activity_reply(reply)
{
    schemas.activity.data=reply;
    client_activity_load(intra.client.id.value);
}

function client_activity_reply(reply)
{
    schemas.client_activity.data=reply;
    activity_display();
}

function activity_click(e)
{
  var i;
  var k;
  var activity_id=schemas.activity.data[e]._id;
  var activity=schemas.activity.data[e];
  var date=nano.today();
  var frequency;
  var html='';
  
  if (!schemas.client_activity.data) schemas.client_activity.data=[];

  var found=false;
  for(i in schemas.client_activity.data)
  {
    if(schemas.client_activity.data[i].activity._id==activity_id) 
    {
      schemas.client_activity.data[i].frequency++;
      if(schemas.client_activity.data[i].frequency>=4)  schemas.client_activity.data[i].frequency=0;
      frequency=schemas.client_activity.data[i].frequency;
      found=true
    }
    
  }
  
  if (!found)
  {
    frequency=1;
    
    var cli_act={
    'nr_client':intra.client.id.value,
    'date':date,
    'activity':activity,
    'frequency':1
    };
    
    schemas.client_activity.data.push(cli_act);
  }
  
  
  //html='<br>';
  for(k=0;k<frequency;k++)  html+='<span class=\"glyphicon glyphicon-star\" aria-hidden=\"true\" ></span>';
  
  $('#activity_id_'+e).html(html);
  
}

function activity_display()
{
  var i;
  var j;
  var k;
  var frequency;
  var activity_id;
  var html='';
  var tc=1;
  
  html+='<button class=\"btn btn-primary btn-lg col-xs-6 col-md-3 tile tile-color-6\" onclick=\"client_activity_save()\" >'+nano.lbl('save');
  html+='</button>';
  
  for(j in schemas.activity.data)
  {
    frequency=0;
    for(i in schemas.client_activity.data) if(schemas.client_activity.data[i].activity._id==schemas.activity.data[j]._id) frequency=schemas.client_activity.data[i].frequency;  
    
    html+='<button class=\"btn btn-primary btn-lg col-xs-6 col-md-3 tile tile-color-'+tc+'\" onclick=\"activity_click('+j+')\" >'+schemas.activity.data[j].name+'&nbsp;';
    html+='<span id=\"activity_id_'+j+'\">';
    for(k=0;k<frequency;k++)  html+='<span class=\"glyphicon glyphicon-star\" aria-hidden=\"true\" ></span>';
    html+='</span>';
    html+='</button>';
    
    tc++; if(tc==6) tc=1;
  }
  
  html+='<button class=\"btn btn-primary btn-lg col-xs-6 col-md-3 tile tile-color-6\" onclick=\"client_activity_save()\" >'+nano.lbl('save');
  html+='</button>';

  $('#activity_list_div').html(html);
}

function client_activity_save()
{
  nano.save('client_activity',schemas.client_activity.data,client_activity_aftersave);
}


function client_activity_aftersave(reply)
{
  //console.log(schemas.client.data[schemas.client.ix]);
  var nr_client=schemas.client.data[schemas.client.ix].numclient;
  
  if(schemas.client.data[schemas.client.ix].datenaissance)
  {
    var birthday=new Date(schemas.client.data[schemas.client.ix].datenaissance) ;
    var ageDifMs = Date.now() - birthday.getTime();
    var ageDate = new Date(ageDifMs);
    var age=Math.abs(ageDate.getUTCFullYear() - 1970);
    var presbyte=0;
    
    if (age>45)
    { 
      presbyte=1; 
    }
    else
    { 
      presbyte=0; 
    }
  
  }
  
  window.location='page_activity_proposal.php?nr_client='+nr_client+'&presbyte='+presbyte;
  
}

</script>";

//glyphicon glyphicon-star


// Close container
echo "</div>";

// Footer
echo nano\footer();



?>
