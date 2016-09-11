<?php
$databases=[];

$databases["bi"]=
[
  "dsn"=>"mysql:host=192.168.12.199;dbname=dwh_dev",
  "user"=>"gpizzetta",
  "password"=>"314159",
  "ip"=>"192.168.12.199"
];

$databases["optisphere"]=
[
  "dsn"=>"4D:host=192.168.12.214;port=19819;charset=UTF-8",
  "user"=>"administrateur",
  "password"=>"M6522!",
  "ip"=>"192.168.12.214"
  
];

$databases["devis"]=
[
  "dsn"=>"mysql:host=192.168.12.219;dbname=devis",
  "user"=>"backup",
  "password"=>"backupdialog",
  "ip"=>"192.168.12.219"
];


$languages=[];

$languages["fr"]=
[
  "name"=>"Français"
];

$languages["de"]=
[
  "name"=>"German"
];

?>