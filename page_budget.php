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

// Close container
echo "</div>";

echo "<script>

budget_ligne_save_callback=function()
{
    var n;
    var sum=0;

    console.log(schemas.budget.data[schemas.budget.ix]);

    lignes=schemas.budget_ligne.data;

    for(n in lignes) if(lignes[n]._status<9) sum+=lignes[n].montant;

    console.log(sum);
    
    if(sum==schemas.budget.data[schemas.budget.ix].montant)
    {
        schemas.budget.data[schemas.budget.ix].reparti=1;
        document.budget_edit_form.reparti.checked=true;
    }
    else
    {
        schemas.budget.data[schemas.budget.ix].reparti=0;
        document.budget_edit_form.reparti.checked=false;
    }
    
    nano.save('budget',[schemas.budget.data[schemas.budget.ix]],{});  

}


</script>";

// Footer
echo nano\footer();



?>
