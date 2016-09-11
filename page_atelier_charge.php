<?php //idx//Devis//
session_start();
include("nanofw/nano.php");
nano\init("page");

// header
echo nano\header();

//Navbar
echo nano\navbar(0);


echo "<div id='chart_div' class='col-xs-12 text-center'></div>";


echo "<script type='text/javascript'>


function charge_atelier_load()
{
  
  var callback=function(reply)
  {
    console.log(reply);
    
    var graph=nano.dataToGraph(reply,false);
    
    console.log(graph);
    
    graph.columns=[
      {'type':'string','name':'jour'},
      {'type':'number','name':'Livraison client'},
      {'type':'number','name':'Livraions fournisseur'}
      
    ]
    
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart()
    {
      var options = 
      {
      'title':'Charge atelier',
      'height':600,
       pointSize: 5,
      curveType: 'function',
      legend: 'none',
       vAxis: {
        minValue: 0,
        gridlines: {
            color: '#f3f3f3',
            count: 5
          }
        }
      }; 
      
      //legend: { position: 'bottom' }
      
      // Create the data table.
      var data = new google.visualization.DataTable();
      
      for(c in graph.columns)
      {
          data.addColumn(graph.columns[c].type, nano.lbl(graph.columns[c].name) );
      }
      
      data.addRows(graph.rows);

      //var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));   //LineChart           
      var chart = new google.visualization.SteppedAreaChart(document.getElementById('chart_div'));   //LineChart           
      chart.draw(data, options);
    }
    
    
  };
  
  nano.ajax('','ajax_find_atelier_charge.php?',callback);
  //nano.ajax('','ajax_find_biais_previsionnel.php?',callback);
  
}


charge_atelier_load();


</script>";



echo "</div>";



echo nano\footer();



?>
