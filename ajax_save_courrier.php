<?php 
session_start();
include("nanofw/nano.php");
nano\init("page");

setlocale(LC_TIME, 'fr_FR.UTF8', 'fr.UTF8', 'fr_FR.UTF-8', 'fr.UTF-8');

$biais=0;
$courrier="";
$dt=date("ymd");

if(isset($_POST["biais"])) $biais=addslashes($_POST["biais"]);
if(isset($_GET["biais"])) $biais=addslashes($_GET["biais"]);

if(isset($_POST["courrier"])) $courrier=addslashes($_POST["courrier"]);
if(isset($_GET["courrier"])) $courrier=addslashes($_GET["courrier"]);

// get the template
if(file_exists ("template/".$courrier)) $content=file_get_contents("template/".$courrier);


// Build the data object
if($biais!=0) // connect to database
{
    $query=["_id"=>$biais];
    
    $load=new nano\data(false);
    $load->connect($schemas->biais);
 
    $data["biais"]=$load->select($query,$schemas->biais)[0];
    $data["encours"]=$load->select($query,$schemas->biais_encours)[0];
    
    // get the client data
    if(isset($data["biais"]["nr_client"])) $data["client"]=client_find($data["biais"]["nr_client"])->data[0];

    //get the user data
    $data["user"]=$user;
    
    // prepare the client name for filename
    $client = preg_replace('/[^a-zA-Z0-9_éèëöä-]/', '',$data["client"]["nom"]."_".$data["client"]["prenom"]);
    
}

//print_r($data);

// replace all {{tags}} in the template
if(count($data)>0)
{

    // get the things to replace in an array
    $match="#{{([a-zA-Z0-9\.\#_\$]*)}}#";
    preg_match_all($match,$content,$matches);
    
    foreach($matches[1] as $key=>$value)
    {
        $path=$data;

        $values=explode(".",$value);
        
        foreach($values as $v)
        {
        
            switch(true)
            {
            case $v=="\$translate":
                $path=nano\lbl($path);
                break;
            case $v=="\$today":
            	 $path=date("y-m-d");
            	 break;
            case $v=="\$date_m":
            	 $path=strftime("%d %h %Y",strtotime($path));
            	 break;
            case $v=="\$date_f":
		 $path=strftime("%d.%m.%Y",strtotime($path));
		 break;
            case $v=="\$int":
                $path=number_format($path,0);
                break;
            case $v=="\$pct":
		 $path=$path."%";
		 break;
	    case $v=="\$ucase":
		    $path=strtoupper($path);
		    break;
	    case $v=="\$dcase":
		    $path=strtolower($path);
		    break;
	    case $v=="\$nocode":
		  $path=nocode($path);
		  break;
            case substr($v,0,1)=="#":
                $path=substr($v,1).$path;
                break;
            default :
                $path=$path[$v];
                break;
            }
        }
        $content=str_replace($matches[0][$key],$path,$content);
    }
}


header('Content-Type: application/vnd.oasis.opendocument.text');
header("Content-disposition: attachment; filename=\"".$dt."_".$client."_".$courrier."\"");
echo $content;

//print_r(json_encode($data));


// Collection off special function to clean format strings
function nocode($txt) //get ride of the code in front of the collab name
{
  $arr=explode(" ",$txt);
  $rtrn="";
  
  for($n=1;$n<count($arr);$n++)
  {
    $rtrn.=$arr[$n]." ";
  }

  return trim($rtrn);
}



?>
