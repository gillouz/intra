<?php 
session_start();
include("nanofw/nano.php");
nano\init("page");

// name your schema
$schema="devcon_lines";

// Header
echo nano\header();

// Navbar
echo nano\navbar(0);

// open Bootstrap container
echo "<div class='container-fluid'>";

//echo nano\quickEdit($schema,["mode"=>"form","onload"=>"new"]);

echo nano\quickList($schema,["mode"=>"form","onload"=>"new"]);


// Close container
echo "</div>";

// Spécifique scripts
echo "<script>

intra.article.id=document.devcon_lines_edit_form.article_code;
intra.article.brand=document.devcon_lines_edit_form.article_marque;
intra.article.detail=document.devcon_lines_edit_form.designation;
intra.article.pv=document.devcon_lines_edit_form.berdoz_price;


function product_type_prepare()
{
  product_type_change(document.devcon_lines_edit_form.product_type);
}


function product_type_change(field)
{
  var form=document.devcon_lines_edit_form;
  
  console.log('etat:'+field.value);
  
  switch(field.value)
  {
  case 'other':
  case 'frame':
    $('#lense').hide();
    $('#frame').show();
    
    if(form.lens_type.value=='') form.lens_type.value='AUT';
    if(form.lens_index.value=='') form.lens_index.value='AUT';
    if(form.lens_material.value=='') form.lens_material.value='AUT';
    if(form.lens_tint.value=='') form.lens_tint.value='AUT';
    if(form.lens_gamme.value=='') form.lens_gamme.value='AUT';
    if(form.lens_coating.value=='') form.lens_coating.value='AUT';
    
    break;
  case 'lens':
    $('#frame').hide();
    $('#lense').show();
    
    if(form.article_code.value=='') form.article_code.value='V';
    if(form.article_marque.value=='') form.article_marque.value='verre';
    
    break;
  default:
    
    $('#frame').hide();
    $('#lense').hide();
    
    break;
  }
}



</script>";


echo article_selector();



// Footer
echo nano\footer();



?>
