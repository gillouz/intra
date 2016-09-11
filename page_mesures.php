<?php 
session_start();
include("nanofw/nano.php");
nano\init("page");

// name your schema
$schema="mesures";

// Header
echo nano\header();

// Navbar
echo nano\navbar(0);

// open Bootstrap container
echo "<div class='container'>";

//echo nano\quickEdit($schema,["mode"=>"form","onload"=>"new"]);

echo nano\quickEdit($schema,["mode"=>"form","onload"=>"new", "autoload"=>true, "show_buttons"=>false]);

$file=fopen("log_mesure.html","a+");
fwrite($file,"\n<p>".date("Y-m-d H:i:s")."|".$_SERVER['QUERY_STRING']."</p>",2048);
fclose($file);

echo "<script>

// Clients
intra.client.id=document.mesures_edit_form.nr_client;
intra.client.detail=document.mesures_edit_form.client_detail;

// articles
intra.article.id=document.mesures_edit_form.article_code;
intra.article.detail=document.mesures_edit_form.designation;

".$schema."_save_callback=function() 
{
  var json;
  var form=document.mesures_edit_form;
  
  var callback=function(reply)
  { 
   window.location='index.php';
  };
  
  if(nano.form_validate(form))
  {
    json=nano.form_save(form);
    nano.ajax('data='+encodeURIComponent(JSON.stringify(json)),'ajax_save_mesures.php?',callback);  
  }
  else
  {
    return false;
  }

};

</script>";

echo client_selector();
echo article_selector();

// Close container
echo "</div>";

// Footer
echo nano\footer();



?>
