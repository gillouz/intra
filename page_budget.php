<?php 
session_start();
include("nanofw/nano.php");
nano\init("page");

// Header
echo nano\header();

// Navbar
echo nano\navbar(0);

// open Bootstrap container
echo "<div class='container'>";

echo nano\quickEdit("budget",["mode"=>"form"]);

echo "<div class='col-xs-6'>Total: <span id='total_projet'></span> Diff: <span id='diff_projet'></span></div>";

// Close container
echo "</div>";

echo "<script>

budget_projet_save_callback=function()
{
    var n;
    var sum=0;

    lignes=schemas.budget_projet.data;

    for(n in lignes) if(lignes[n]._status<9) sum+=Math.round(parseFloat(lignes[n].montant)*100)/100;
    
    //sum=sum*1000)/1000;
    
    $('#total_projet').html(sum);
    $('#diff_projet').html(Math.round((schemas.budget.data[schemas.budget.ix].montant_chf-sum)*100)/100);
    
    if(sum==schemas.budget.data[schemas.budget.ix].montant_chf)
    {
        schemas.budget.data[schemas.budget.ix].reparti=1;
        document.budget_edit_form.reparti.checked=true;
    }
    else
    {
        schemas.budget.data[schemas.budget.ix].reparti=0;
        document.budget_edit_form.reparti.checked=false;
    }
    
    callback=function(){};
    
    nano.save('budget',[schemas.budget.data[schemas.budget.ix]],callback);  

}


</script>";

// Footer
echo nano\footer();



?>
