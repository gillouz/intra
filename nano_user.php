<?php 
session_start();
include("nanofw/nano.php");
nano\init("page");

//print_r($schemas);

// Header
echo nano\header();

// Navbar
echo nano\navbar(0);

// open Bootstrap container
echo "<div class='container-fluid'>";

echo "<h1>".nano\lbl("user_config")."</h1>";

echo nano\form($schemas->{"_users"},[],[],"_users_edit_form");

echo "<hr>";

echo "<!-- save -->
<div class='form-group col-xs-6 col-md-2'>
  <label class='control-label'>&nbsp;</label>
  <button class='form-control btn btn-primary' type='button' onclick='save_user();'>".nano\lbl("save")."</button>
</div>";

//print_r($user);

// Close container
echo "</div>";

echo "<script>
schemas._users.data=[".json_encode($user)."];
schemas._users.ix=0;

_users_edit_mode(0); 


function save_user()
{
  var form=document._users_edit_form;
  var json;
  var c;
  var data='?';
  
  var callback=function(reply)
  {
    schemas._users.data=reply;
  }

  if(nano.form_validate(form))
  {
    json=nano.form_save(form);
    var dataset=[];
    dataset.push(json);
   
    nano.ajax('schema=_users&data='+encodeURIComponent(JSON.stringify(dataset)),'nanofw/nano_user_save.php?',callback); 
    
  }
  else
  {
    return false;
  }
  
  return true;
}


";

echo nano\quickListFunctions("_users");


echo "</script>";



// Footer
echo nano\footer();


?>
