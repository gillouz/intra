<?php //idx//Devis//
session_start();
include("nanofw/nano.php");
nano\init("page");

$nr_client=0;
$presbyte=0;

if(isset($_GET['nr_client'])) $nr_client = htmlspecialchars($_GET['nr_client'],ENT_QUOTES,'ISO-8859-1');
if(isset($_GET['presbyte'])) $presbyte = htmlspecialchars($_GET['presbyte'],ENT_QUOTES,'ISO-8859-1');

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
    {'type':'string','name':'Proposal'},
    {'type':'number','name':'force'}
    ]
    
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart()
    {
      var options = 
      {
	'animation': {'startup': true},
	'animation.duration':10,
	'title':'Proposal',
	'height':600,
	'pointSize': 5,
	'curveType': 'function',
	'legend': 'none',
	'vAxis': {
	  'minValue': 0,
	  'gridlines': {
	  'color': '#f3f3f3',
	  'count': 5
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
      var chart = new google.visualization.BarChart(document.getElementById('chart_div'));   //LineChart           
      chart.draw(data, options);
    }
    
    
  };
  
  nano.ajax('nr_client=$nr_client&presbyte=$presbyte','ajax_find_activity_proposal.php?',callback);
}


charge_atelier_load();


</script>";



echo "</div>";



echo nano\footer();



?>
