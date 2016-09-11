<?php //idx//Devis//
session_start();
include("nanofw/nano.php");
nano\init("webservice");

//header('Content-type: text/html; charset=utf-8'); 

$max_sph_from=-10;
$max_sph_to=6;
$cyl_from=0;
$cyl_to=2;
$max_cyl_from=0;
$max_cyl_to=1;
$cyl="+";

// prendre les données dans les paramètres
if(isset($_GET['max_sph_from'])) $max_sph_from = htmlspecialchars($_GET['max_sph_from'],ENT_QUOTES,'ISO-8859-1');
if(isset($_GET['max_sph_to'])) $max_sph_to = htmlspecialchars($_GET['max_sph_to'],ENT_QUOTES,'ISO-8859-1');
if(isset($_GET['cyl_from'])) $cyl_from = htmlspecialchars($_GET['cyl_from'],ENT_QUOTES,'ISO-8859-1');
if(isset($_GET['cyl_to'])) $cyl_to = htmlspecialchars($_GET['cyl_to'],ENT_QUOTES,'ISO-8859-1');
if(isset($_GET['max_cyl_from'])) $max_cyl_from = htmlspecialchars($_GET['max_cyl_from'],ENT_QUOTES,'ISO-8859-1');
if(isset($_GET['max_cyl_to'])) $max_cyl_to = htmlspecialchars($_GET['max_cyl_to'],ENT_QUOTES,'ISO-8859-1');
//if(isset($_GET['cyl'])) $cyl = htmlspecialchars($_GET['cyl'],ENT_QUOTES,'ISO-8859-1');

if($max_cyl_to>0) $max_cyl_to=1;
if($max_cyl_from>0) $max_cyl_from=1;


if($cyl=="+")
{
    
    $cyl_from=$cyl_from*-1;
    $cyl_to=$cyl_to*-1;

    $max_cyl_to=1-$max_cyl_to;
    $max_cyl_from=1-$max_cyl_from;
    
}

/*
if($cyl=="-")
{
    
    $cyl_from=$cyl_from*-1;
    $cyl_to=$cyl_to*-1;

    if($max_cyl_to+$max_cyl_from==2)
    {
      $max_cyl_to=1-$max_cyl_to;
      $max_cyl_from=1-$max_cyl_from;
    }
}
*/

Header('Content-type: image/svg+xml; charset=utf-8');

$offset=5;
$width=200;
$cyl_nbr=(abs($cyl_to)-abs($cyl_from))*4;
$cyl_width=$width/$cyl_nbr;
$svg="";
$txto=$cyl_width/2-5;
$red="#6688C4";
$blue="#A96673";

$y=0;
$x=0;


$svg.=svg_text(0,$offset+$txto,"sph ".format($max_sph_to,"plan").format($cyl_from,""," cyl "),10);
$svg.=svg_text($width,$offset+$txto,"cyl ".format($cyl_to,0,""),10,"right");

$offset+=$cyl_width+5;


$col=$red;
switch(true)
{
case $max_sph_to==0:
    if($max_sph_from<0) $col=$blue; 
    break;
case $max_sph_to<0:
    $col=$blue;
    break;
}

for($n=0;$n<$cyl_nbr;$n++)
{
  
    if($max_cyl_to==1)
    {
    
	for($m=0;$m<($cyl_nbr);$m++) if($n>=($cyl_nbr-$m-1) ) $svg.=svg_square($n*$cyl_width,$m*$cyl_width+$offset,$cyl_width-2,$cyl_width-2,$col);
    
    }
    else
    {
        $svg.=svg_square($n*$cyl_width,$offset,$cyl_width-2,$cyl_width-2,$col);
    }
    
    
    
}




//$offset+=;

if($max_cyl_to==1) $offset+=($cyl_nbr-1)*$cyl_width;

$old_color=$col;

$col=$blue;

switch(true)
{
case $max_sph_from>0:
    $col=$red;
    break;
case $max_sph_from==0:
    if($max_sph_to>0)
    {
      $col=$red; 
    }
    break;
}

if($old_color!=$col)
{
  $offset+=10;
  $svg.=svg_text(0,$offset+$txto,"plan",10);
  $offset+=2;
}


$offset+=$cyl_width;

for($n=0;$n<$cyl_nbr;$n++)
{

    if($max_cyl_from==1)
    {
        for($m=0;$m<$cyl_nbr;$m++) if($n<=($cyl_nbr-$m-1) ) $svg.=svg_square($n*$cyl_width,$m*$cyl_width+$offset,$cyl_width-2,$cyl_width-2,$col);
    }
    else
    {
        $svg.=svg_square($n*$cyl_width,$offset,$cyl_width-2,$cyl_width-2,$col);
    }

}

$offset+=$cyl_width;
if($max_cyl_from==1) $offset+=($cyl_nbr-1)*$cyl_width;

$svg.=svg_text(0,$offset+$txto,"sph ".format($max_sph_from,"plan"),10);

$offset+=$cyl_width;


/*

switch (true)
{
case $max_cyl_to==1: 
    $svg.=svg_text($width,2,format($max_sph_to+$max_cyl_to*$cyl_to*-1,"plan").format($cyl_to,""," / "),20,"right");
    $offset+=20;
    $path1=[[0,$width],[$width,0],[$width,$width]];
    $svg.=svg_path(0,$offset,$path1,$col); 
    $offset+=$width;
    $svg.=svg_text(0,$offset+2,format($max_sph_to,"plan").format($cyl_from,""," / "),20);
    break;
case $max_cyl_to==0:
    $svg.=svg_text(0,2,format($max_sph_to,"plan").format($cyl_from,""," / "),20);
    $offset+=20;
    $svg.=svg_square(0,$offset,$width,20,$col);
    $offset+=20;
    $svg.=svg_text($width,$offset+2,format($max_sph_to+$max_cyl_to*$cyl_to*-1,"plan").format($cyl_to,""," / "),20,"right");
    
    break;
}


$offset+=20;
$col="#6688C4";
    


switch (true)
{
case $max_cyl_from==1 : 
    
    $svg.=svg_text($width,$offset,format($max_sph_from+$max_cyl_from*$cyl_to*-1,"plan").format($cyl_to,""," / "),20,"right");

    $offset+=20;
    $path1=[[0,0],[0,$width],[$width,0]];
    $svg.=svg_path(0,$offset,$path1,$col); 
    $offset+=$width;

    $svg.=svg_text(0,$offset,format($max_sph_from,"plan").format($cyl_from,""," / "),20);

    
    break;
case $max_cyl_from==0:
    $svg.=svg_text(0,$offset,format($max_sph_from,"plan").format($cyl_from,""," / "),20);

    $offset+=20;
    $svg.=svg_square(0,$offset,$width,20,$col);

    $offset+=20;

    $svg.=svg_text($width,$offset+2,format($max_sph_from+$max_cyl_from*$cyl_to*-1,"plan").format($cyl_to,""," / "),20,"right");
    break;
}
*/

echo svg_start($width,$offset+20);
echo $svg;
echo svg_end();



function format($nbr,$zero=0,$pre="",$post="")
{
    switch (true)
    {
    case $nbr>0:
    return $pre."+".$nbr.$post;
    break;
    
    case $nbr<0:
    return $pre.$nbr.$post;
    break;

    case $nbr==0:
    return $zero;
    break;

    }
}

function svg_start($w,$h)
{

  return "<?xml version='1.0' encoding='UTF-8' standalone='no'?>
  <svg xmlns:dc='http://purl.org/dc/elements/1.1/'
  xmlns:cc='http://creativecommons.org/ns#'
  xmlns:rdf='http://www.w3.org/1999/02/22-rdf-syntax-ns#'
  xmlns:svg='http://www.w3.org/2000/svg'
  xmlns='http://www.w3.org/2000/svg'
  xmlns:sodipodi='http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd'
  xmlns:inkscape='http://www.inkscape.org/namespaces/inkscape'
  width='".$w."mm'
  height='".$h."mm'
  id='svg2'
  version='1.1'>
  <g>";

}


function svg_path($x,$y,$path,$col="#000000")
{
    
    $lastx=0;
    $lasty=0;
    $minx=99999;
    $miny=99999;
    $maxx=0;
    $maxy=0;
    $d="";
    foreach($path as $dot)
    {
        $d.=" ".($dot[0]-$lastx).",".($dot[1]-$lasty)." ";
        //$lastx=$dot[0];
        //$lasty=$dot[1];
        if($dot[0]<$minx) $minx=$dot[0];        
        if($dot[1]<$miny) $miny=$dot[1];
        if($dot[0]>$maxx) $maxx=$dot[0];        
        if($dot[1]>$maxy) $maxy=$dot[1];
    }

    //$svg="<svg x='$minx' y='$miny' width='".$maxx."mm' height='".$maxy."mm' viewBox='$minx $miny $maxx$maxy'>"; //viewBox='0 0 100 100'
    $svg="<svg x='".$x."mm' y='".$y."mm' width='".$maxx."mm' height='".$maxy."mm' viewBox='0 0 $maxx $maxy'>"; 
    $svg.="<path style='opacity:1;fill:$col;fill-opacity:1;fill-rule:evenodd' d='M $d Z' id='rect4134' />";
    $svg.="</svg>";
        
    return $svg;

}

function svg_square($x,$y,$w,$h,$color="#000000",$opacity=1)
{

  $svg="<rect 
  id='".rand(5,15)."' 
  style='fill:$color;fill-opacity:$opacity'
  width='".$w."mm'
  height='".$h."mm'
  x='".$x."mm'
  y='".$y."mm'/>
  ";

  return $svg ;
}

function svg_text($x,$y,$text,$size,$align="left")
{

  

  $align_style="text-anchor:start;dominant-baseline: hanging;";
  if($align=="right") $align_style="text-align:end;writing-mode:lr;text-anchor:end;dominant-baseline: hanging;";

  //line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans;-inkscape-font-specification:Sans
  // xml:space='preserve'
  //sodipodi:linespacing='125%'
  
  $svg= "<text
  style='$align_style font-size:".$size."mm;line-height:100%; font-family:Arial; font-style:normal; font-weight:normal; '
  x='".$x."mm'
  y='".$y."mm'
  id='text2998'
  >$text</text>";

  
  /*
  <tspan
  sodipodi:role='line'
  id='tspan3000'
  x='$x'
  y='$y'>$text</tspan>
  */
  
  return $svg;

}

function svg_end()
{
  return '</g></svg>';
}

/*
echo svg_start();
echo svg_square(10,10,20,20);
echo svg_text(30,30,"toto",10);
echo svg_end();
*/
?>
