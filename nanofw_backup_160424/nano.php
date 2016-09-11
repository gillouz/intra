<?php
namespace nano;

/********************************************************************************** 

Low level functions that provide login, database connexion and error handeling

***********************************************************************************/

// setup the global variables and check the user access and load the config files
function init($use)
{
  global $lbl;
  global $schemas;
  global $languages;
  global $lang;
  global $databases;
  global $user;
  global $script_use;
  global $project;
 
  $languages=[];
  $databases=[];

  
  //setup user for first query
  $user=["_id"=>0, "user"=>"" , "level"=>0 ,"is_admin"=>false , "group"=>["_id"=>1] ];
  $user["groups"]=[0];
  
  $schemas=(object) array();
  $script_use=$use;
  
  // ***** get language or use the french
  $lang="fr";
  if (isset($_COOKIE["lang"])) $lang=$_COOKIE["lang"];

  // include the structure and the labels
  if(file_exists (__DIR__."/../nano_config.php")) 
  {
    include(__DIR__."/../nano_config.php");
  }
  else
  {
    die("There must be a nano config file in your project");
  }

  // Check if config file is ok
  if($project=="") die ("Give a name to your project in nano_config.php file!");
  if(!isset($databases["nano"])) die ("Please configure a nano database connection in nano_config.php!");

  // include languages translations
  if(file_exists (__DIR__."/../nano_label_$lang.php")) include(__DIR__."/../nano_label_$lang.php"); 
  
  // define the defaut tables
  $schemas->_users=
  [
    "name"=>"_users",
    "database"=>"nano",
    "table"=>"_users",
    "structure"=>
    [
      [
        "name"=>"user", 
        "type"=>"string",
        "regex"=>"^[0-9a-zA-Z]{1,10}$", 
        "optional"=>false, 
        "col"=>"col-xs-4", 
        "clearall"=>false, 
        "label"=>lbl("user"), 
        "display"=>["list","form","find","div"] 
      ],
      [
        "name"=>"email",
        "type"=>"string",
        "regex"=>"^[a-zA-Z0-9._-]*@[a-zA-Z0-9.-]*\.[a-zA-Z]{2,4}$",
        "optional"=>false,
        "col"=>"col-xs-4",
        "clearall"=>false,
        "label"=>lbl("email"),
        "display"=>["list","form","find","div"] 
      ],
      [
        "name"=>"password",
        "type"=>"password",
        "regex"=>"^.*$",
        "optional"=>false,
        "col"=>"col-xs-4",
        "clearall"=>false,
        "label"=>lbl("password"),
        "display"=>["list","form","find","div"] 
      ],
      [
        "name"=>"level",
        "type"=>"number",
        "regex"=>"^[0-9]*$",
        "optional"=>false,
        "col"=>"col-xs-4",
        "clearall"=>false,
        "label"=>lbl("level"),
        "display"=>["list","form","find","div"]
      ],
      [
        "name"=>"is_admin",
        "type"=>"boolean",
        "regex"=>"^[0-1]$",
        "optional"=>false,
        "col"=>"col-xs-4",
        "clearall"=>false,
        "label"=>lbl("Is admin"),
        "display"=>["list","form","find","div"]
      ],
      [
        "name"=>"group",
        "type"=>"key",
        "schema"=>"_group",
        "regex"=>"^.*$",
        "optional"=>false,
        "col"=>"col-xs-4",
        "clearall"=>false,
        "label"=>lbl("Main group"),
        "display"=>["list","form","find","div"]
      ],
      [
        "name"=>"groups",
        "type"=>"multiplekey",
        "schema"=>"_group",
        "regex"=>"^.*$",
        "optional"=>true,
        "col"=>"col-xs-12",
        "clearall"=>true,
        "label"=>lbl("Groups"),
        "display"=>["list","form","find","div"]
      ],
    ]
  ];

  $schemas->_group=
  [
    "name"=>"_group",
    "database"=>"nano",
    "table"=>"_group",
    "structure"=>
    [
      [
        "name"=>"name", 
        "type"=>"string",
        "regex"=>"^.*$", 
        "optional"=>false, 
        "col"=>"col-xs-4", 
        "clearall"=>false, 
        "label"=>lbl("genre"), 
        "display"=>["find","list", "form", "div"] 
      ]
    ]
  ];

  // add the user defined tables
  if(file_exists (__DIR__."/../nano_schemas.php")) include(__DIR__."/../nano_schemas.php");
  // add the users library 
  if(file_exists (__DIR__."/../".$project.".php")) include(__DIR__."/../".$project.".php");

  // Connect to the datasource
  $users=new data(true);
  $users->connect($schemas->_users);
  
  // Create the tables
  $users->create($schemas->_users);
  $users->create($schemas->_group);

  // check if the user is logged in
  $email="";
  $password="";
  if (isset($_COOKIE["email"])) $email=$_COOKIE["email"];
  if (isset($_COOKIE["password"])) $password=$_COOKIE["password"];
  
  $query=["email"=>$email, "password"=>$password];
  $result=$users->select($schemas->_users,$query);
  if(count($result)==0)
  {
    $query=[];
    $result=$users->select($schemas->_users,$query);
    if(count($result)==0)
    {
      // add the default admin group
      $data=(object) array("name"=>"admin");
      $users->upsert($schemas->_group,[$data]);
      
      // add the default admin user
      $data=(object) array("user"=>"admin","email"=>"admin@admin.com","password"=>"admin","level"=>"999","is_admin"=>1, "group"=> (object) array("_id"=>1, "name"=>"admin"), "groups"=>[] );
      $users->upsert($schemas->_users,[$data]);
    }
    echo header();
    echo loginForm();
    echo footer();
    die();
  }
  else
  {
    $user=$result[0];
    // add default group to groups
    $user["groups"][]=$user["group"]["_id"];
  }

}

// simple login form
function loginForm()
{
  
  $html="<!-- Login form -->
  <div class='container' style='margin-top:20px'>
    <form method='post' action='nanofw/nano_login.php' class='col-xs-4 col-xs-offset-4' >
      <h1>".lbl("Sign in")."</h1>
      <h1>&nbsp;</h1>
      <div class='form-group'>
        <label for='email'>".lbl("Email")."</label>
        <input id='email' type='email' name='email' class='form-control'  placeholder='".lbl("Email")."'>
      </div>
      <div class='form-group'>
        <label for='password'>Password</label>
        <input id='password' type='password' name='password' class='form-control'  placeholder='".lbl("password")."'>
      </div>
      <input type='submit' class='btn btn-primary' value='".lbl("Login")."'>
    </form>
  </div>";
      
  return $html;
  
}

// handle the errors messages
function error($message,$code)
{
  switch($GLOBALS["script_use"])
  {
    case "webservice":
      $return=[];
      $return["end"]=microtime();
      $return["code"]=$code;
      $return["message"]=$message;
      $return["status"]="ERROR";
      return JSON_encode($return);
      break;
    case "page":
    $html=header();  
    $html.="<!-- Error -->
    <div class='alert alert-danger' role='alert'>
    Error $code : $message
    </div>";
    $html.=footer();  
    return $html;
    break;
  }
  
}

// provide translated text
function lbl($txt)
{
  $lbl=$GLOBALS['lbl'];
  if(isset($lbl[$txt])) return $lbl[$txt];
  return $txt; //ucfirst(trim(str_replace("_"," ",$txt))); 
}

// provide database connection and management
class data
{

  // we need the schemas
  public function __construct($is_admin)
  {
    $this->schemas=$GLOBALS['schemas'];
    $this->databases=$GLOBALS['databases'];
    $this->languages=$GLOBALS['languages'];
    $this->lang=$GLOBALS['lang'];;
    $this->user=$GLOBALS['user'];
    $this->admin=$this->user['is_admin'];
    
    
    // of if we force it (to search for login)
    if($is_admin) $this->admin=true;
  
  }
  
  // connect to the database
  function connect($schema)
  {
    $dsn=$this->databases[$schema["database"]]["dsn"];
    $user=$this->databases[$schema["database"]]["user"];
    $password=$this->databases[$schema["database"]]["password"];
    $dbname=$this->databases[$schema["database"]]["dbname"];
    
    // connection to the server
    try 
    {
      $this->conn = new \PDO($dsn,$user,$password);
      $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
      $this->conn->exec("SET CHARACTER SET utf8");
      $this->conn->exec("SET lc_time_names =".$this->languages[$this->lang]["sql_format"]);      
    } 
    catch (Exception $e)
    {
      die(error($e->getMessage(),-1));
    }
    
    // use the database
    try
    {
      $query="create database if not exists $dbname;";
      $stmt = $this->conn->prepare($query); 
      $stmt->execute();
      $this->conn->exec("use $dbname");
    }
    catch (Exception $e)
    {
      die(error($e->getMessage(),-1));
    }
    
    return true;

  }
  
  // Create the table dans column in the database using a schema
  function create($schema)
  {
    // alter table
    $alters=[];
    
    // the schema must have been defined
    if (!isset($schema["table"])) die(error("There must be a table name",-1));
    
    // the schema must have been defined
    if (!isset($this->databases[$schema["database"]]["create"])) die(error("Please setup the 'create' flag of your database",-1));
    if ($this->databases[$schema["database"]]["create"]==false) return  false; //die(error("Create is not allowed on this table",-1));
    
    
    // create the table if it does not exist
    //
    // status are the following
    // 0 visible
    // 7 locked 
    // 9 deleted
    //
    
    // do not allow use of the table is it is only for admin
    if (!$this->admin) if(substr($schema["table"],0,1)=="_") die(error("error trying to create table ".$schema["table"]." : tables starting with _ are for internal use only!",-11));
    
    //Créate the table
    $query="create table if not exists `".$schema["table"]."` (";
    $query.=" `_id` int(10) unsigned primary key auto_increment,";
    $query.=" `_user_id` int(10),";
    $query.=" `_group_id` int(10),";
    $query.=" `_level` int(10),";
    $query.=" `_ip` varchar(16),";
    $query.=" `_time` datetime,";
    $query.=" `_color` varchar(8),";
    $query.=" `_status` int(2) unsigned default 0";
    $query.=");";
    
    try
    {
      $stmt = $this->conn->prepare($query); 
      $stmt->execute();
    }
    catch (Exception $e)
    {
      die(error($e->getMessage()." : ".$query,-9));
    }
    
    // Get the columns in the table
    $query="show columns from `".$schema["table"]."`;";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $stmt->setFetchMode(\PDO::FETCH_ASSOC); 
    $db_schema = $stmt->fetchAll();
    
    // Create columns if it does not exists according to the schema
    foreach($schema["structure"] as $field)
    {
      $field_name=$field["name"];
      
      // do not allow creation of columns starting with _
      if(substr($field_name,0,1)=="_") die(error("error trying to create column ".$field_name." of ".$schema["table"]." : columns starting with _ are for internal use only!",-10));
      
      $tmp_languages=[""=>""];
      if($field["type"]=="translate") $tmp_languages=$this->languages;
      foreach($tmp_languages as $lkey=>$lvalue)
      {
        $field_name=$field["name"];
      
        if($field["type"]=="translate") $field_name="_".$lkey."_".$field_name;
        
        // check if column exist
        $exist=false;
        foreach($db_schema as $db_column) if(strtoupper($db_column["Field"])==strtoupper($field_name)) $exist=true;
        
        // field name starting with à "_" are reserved for internal use only
        if($exist==false)
        {
          $query_action="add `".$field_name."` ";
          switch($field["type"])
          {
          case "key":
            $alters[]=$query_action." int(10) unsigned";
            break;
          case "integer":
          case "number":
            $alters[]=$query_action." int(10)";
            break;
          case "double":
          case "float":
            $alters[]=$query_action." float(10,4)";
            break;
          case "translate":
          case "string":
          case "hidden":
            $alters[]=$query_action." varchar(255)";
            break;
          case "text":
            $alters[]=$query_action." text";
            break;
          case "boolean":
          case "checkbox":
            $alters[]=$query_action." tinyint(1) unsigned";
            break;
          case "date":
            $alters[]=$query_action." date";
            break;
          case "dateTime":
            $alters[]=$query_action." datetime";
            break;
          case "select":
          case "list":
            if (!is_array($field["enum"])) die(error("field ".$field_name." should have and enum array!",-13));
            $alters[]=$query_action." enum('".(implode("','",$field["enum"]))."')";
            break;
          case "password":
            $alters[]=$query_action." varchar(255)";
            break;
          case "multiplekey":
          case "json":
            // once Mysql 5.7 will be awalable we will change this to json type
            $alters[]=$query_action." TEXT";
            break;
          }
        }
      }
    }  
      
      
    if(count($alters)>0)
    {
      $query="alter table `".$schema["table"]."` ".implode(",",$alters).";";
      
      //echo $query;
      
      try
      {
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
      }
        catch (Exception $e) 
      {
        die(error($e->getMessage(),-1));
      }
    }
  
  
    return  true; 
  }

  // allow to select data from the table query is the same format as MongoDb querys
  function select($schema,$query)
  {
    unset($this->query);
    $this->query=$query;
    $table=$schema["table"];
    $columns=[];
    $joins=[];
    $where_rights=[];
    
    //check if right where set and setup default
    if(!isset($schema["sgroup"])) $schema["sgroup"]="W";
    if(!isset($schema["slevel"])) $schema["slevel"]="W";
    if(!isset($schema["sother"])) $schema["sother"]="W";
    
    if (!$this->admin) if(substr($table,0,1)=="_") die(error("error trying to read table ".$schema["table"]." : tables starting with _ are for internal use only!",-11));

    if(is_array($schema["default_query"])) $this->query=array_merge($this->query,$schema["default_query"]);
    
    // get the columns and joins
    $reply=$this->columns($schema,$table);
    $columns=array_merge($columns,$reply["columns"]);
    $joins=array_merge($joins,$reply["joins"]);
    
    $where_rights[]=" `$table`.`_user_id`='".$this->user["_id"]."' ";
    if($schema["sgroup"]!="N") $where_rights[]=" `$table`.`_group_id` in (".implode(",",$this->user["groups"]).") ";
    if($schema["slevel"]!="N") $where_rights[]=" `$table`.`_level`<'".$this->user["level"]."' ";
    if($schema["sother"]!="N") $where_rights[]=" true ";
  
    // add some restriction to the query depending of the right the user have on this table
    $this->query["_status"]=['$lt'=>9 ];
    
    // build the query
    $query="select ".implode(",",$columns)." from `".$schema["table"]."` ".implode(" ",$joins)." where (".implode(" or ",$where_rights).") and ".$this->where($this->query,$schema).$this->groupby($this->query,$schema).$this->orderby($this->query,$schema).";";
    
    //echo $query."<br><br>";
    
    try
    {
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $stmt->setFetchMode(\PDO::FETCH_ASSOC); 
    }
    catch(Exception $e) 
    {
      die(error($e->getMessage(),-3));
    }
    
    $dataset=[];
    
    // build the dataset
    foreach($stmt->fetchAll() as $line) $dataset[]=$this->dataset($schema,$line,$table);
    
    // return the dataset
    return $dataset;
   
  }
  
  protected function columns($schema,$prefix)
  {
    $table=$schema["table"];
    $columns=[];
    $joins=[];
    $if_rights=[];
    $where_rights=[];
    $or=[];
    $aggregation="";
    
    // allways add the internal filed
    $columns[]="`$table`.`_id` as `".$prefix."._id`";
    
    //check if right where set and setup default
    if(!isset($schema["sgroup"])) $schema["sgroup"]="W";
    if(!isset($schema["slevel"])) $schema["slevel"]="W";
    if(!isset($schema["sother"])) $schema["sother"]="W";
    
    $if_rights[]=" `$table`.`_user_id`='".$this->user["_id"]."'";
    if($schema["sgroup"]=="W") $if_rights[]="`$table`.`_group_id` in (".implode(",",$this->user["groups"]).")"; //$or[]=["_group_id"=>['$eq'=>"".$this->user["group"]["_id"]]];
    if($schema["slevel"]=="W") $if_rights[]="`$table`.`_level`<'".$this->user["level"]."'"; //$or[]=["_level"=>['$lt'=>"".$this->user["level"]]];
    if($schema["sother"]=="W") $if_rights[]=" true "; 
    
    $columns[]="if( ".implode(" or ",$if_rights)." ,`$table`.`_status`,'7') as `".$prefix."._status`";

    foreach($schema["structure"] as $field)
    {
      
      //add the aggregation function
      $aggregation="";
      if(isset($this->query["\$groupby"]["\$aggregate"][$field["name"]])) $aggregation=$this->query["\$groupby"]["\$aggregate"][$field["name"]];
      
      
      switch($field["type"])
      {
        case "relation":
          break;
        case "key";
          $subschema=$this->schemas->{$field["schema"]};
          $subtable=$subschema["table"];
          
          $this->create($subschema);
          $reply=$this->columns($subschema,$prefix.".".$field["schema"]);
          $columns=array_merge($columns,$reply["columns"]);
          $joins=array_merge($joins,$reply["joins"]);
          
          // Subtable right management
          //check if right where set and setup default
          if(!isset($subschema["sgroup"])) $subschema["sgroup"]="W";
          if(!isset($subschema["slevel"])) $subschema["slevel"]="W";
          if(!isset($subschema["sother"])) $subschema["sother"]="W";
          
          $where_rights[]=" `$subtable`.`_user_id`='".$this->user["_id"]."' ";
          if($subschema["sgroup"]!="N") $where_rights[]=" `$subtable`.`_group_id` in (".implode(",",$this->user["groups"]).")";
          if($subschema["slevel"]!="N") $where_rights[]=" `$subtable`.`_level`<'".$this->user["level"]."' ";
          if($subschema["sother"]!="N") $where_rights[]=" true ";
          
          array_unshift($joins," left join `$subtable` on `$subtable`.`_id`=`$table`.`".$field["name"]."` and (".implode(" or ",$where_rights).") and `$subtable`.`_status`<'9'  ");
          
          break;
        case "password";
          $columns[]="'!!NOCHANGE!!' as `".$prefix.".".$field["name"]."` ";
          break;
        case "translate";
          $columns[]="`$table`.`_".$this->lang."_".$field["name"]."` as `".$prefix.".".$field["name"]."` ";
          foreach($this->languages as $lkey=>$lvalue) $columns[]="`$table`.`_".$lkey."_".$field["name"]."` as `".$prefix."._".$lkey."_".$field["name"]."` ";
          break;
        case "section_start":
        case "section_end":
        case "html":
        case "button":
          break;
        default:
          //alter the column format according to the group by field format
          if(isset($this->query["\$groupby"]["\$fields"][$field["name"]]))
          {
            $columns[]=$this->sqlFunction($this->sqlFunction("`$table`.`".$field["name"]."`",$this->query["\$groupby"]["\$fields"][$field["name"]]),$aggregation)." as `".$prefix.".".$field["name"]."` ";
          }
          else
          {
            $columns[]=$this->sqlFunction("`$table`.`".$field["name"]."`",$aggregation)."  as `".$prefix.".".$field["name"]."` ";
          }
          break;
      }
  
    }
  
    return [ "columns"=>$columns, "joins"=>$joins];
  }
  
  // this function is used in the select function to create the where part of the query 
  protected function where($query,$schema)
  {
    $table=$schema["table"];
    $where_columns=[];
    $or_columns=[];
    
    foreach($query as $field=>$value)
    {
      
      foreach($schema["structure"] as $s) if($s["type"]=="translate" and $s["name"]==$field) $field="_".$this->lang."_".$field;
      
      switch(true)
      {
        case is_array($value):
          switch (true)
          {
            case $field=='$or':
              $or_columns=[];
              foreach($value as $subvalue) $or_column[]=$this->where($subvalue,$schema);
              $where_columns[]=" (".implode(" or ",$or_column).") ";
            break;
            case $field=='$and':
              $or_columns=[];
              foreach($value as $subvalue) $or_column[]=$this->where($subvalue,$schema);
              $where_columns[]=" (".implode(" and ",$or_column).") ";
            break;
          }
        case is_object($value):
          switch (true)
          {
            case isset($value['$eq']):
              $where_columns[]="`$table`.`$field`='".$value['$eq']."'";
              break;
            case isset($value['$gt']):
              $where_columns[]="`$table`.`$field`>'".$value['$gt']."'";
              break;
            case isset($value['$lt']):
              $where_columns[]="`$table`.`$field`<'".$value['$lt']."'";
              break;
            case isset($value['$gte']):
              $where_columns[]="`$table`.`$field`>='".$value['$gte']."'";
              break;
            case isset($value['$lte']):
              $where_columns[]="`$table`.`$field`<='".$value['$lte']."'";
              break;
            case isset($value['$ne']):
              $where_columns[]="`$table`.`$field`!='".$value['$ne']."'";
              break;
            case isset($value['$in']):
              $where_columns[]="`$table`.`$field` in ('".implode("','",$value['$in'])."')";
              break;
            case isset($value['$nin']):
              $where_columns[]="`$table`.`$field` not in ('".implode("','",$value['$nin'])."')";
              break;
            case isset($value['$lk']): // not in mongodb spec
              $where_columns[]="`$table`.`$field` like '%".$value['$lk']."%'";
              break;
          }
          break;
        default:
          $where_columns[]="`$table`.`$field`='$value'";
          break;
      }
    }
    
    return  implode(" and ",$where_columns);
    
  }
  
  
  // this function is used in the select function to create the where part of the query 
  protected function orderby($query,$schema)
  {
    $table=$schema["table"];
    $orderby_columns=[];
    
    if(!isset($query["\$orderby"])) return "";
    
    foreach($query["\$orderby"] as $field=>$value)
    {
      
      foreach($schema["structure"] as $s)
      {
        if($field==$s["name"])
        {
          if($s["type"]=="translate" and $s["name"]==$field) $field="_".$this->lang."_".$field;
        
          if($value==-1)
          {  
            $orderby_columns[]=" `$table`.`$field` desc ";
          }
          elseif($value==1)
          {
            $orderby_columns[]=" `$table`.`$field` ";
          }
        }
      }
      
    }
    
    return  " order by ".implode(",",$orderby_columns);
    
  }

  // group by 
  protected function groupby($query,$schema)
  {
    $table=$schema["table"];
    $groupby_columns=[];
    
    if(!isset($query["\$groupby"])) return "";
    
    foreach($query["\$groupby"]["\$fields"] as $field=>$value)
    {
      
      if(is_numeric($field)) $field=$value;
      
      foreach($schema["structure"] as $s)
      {
        if($field==$s["name"])
        {
          if($s["type"]=="translate" and $s["name"]==$field) $field="_".$this->lang."_".$field;
          $groupby_columns[]=$this->sqlFunction("`$table`.`$field`",$value);
        }
      }
    }

    return  " group by ".implode(",",$groupby_columns);
  }
  
  protected function sqlFunction($field,$fuction)
  {
        switch($function)
        {
          case "\$year":
              return " date_format($field,'%Y') ";
            break;
          case "\$month":
            return " date_format($field,'%m') ";
            break;
          case "\$monthname":
            return " date_format($field,'%M') ";
            break;
          case "\$week":
            return " date_format($field,'%u') ";
            break;
          case "\$weekday":
            return " date_format($field,'%w') ";
            break;
          case "\$sum":
            return " sum($field) ";
            break;
          case "\$count":
            return " count($field,) ";
            break;
          case "\$avg":
            return " avg($field) ";
            break;
          default:
            return " $field ";
            break;
          }
  
  }
  
  
  
  protected function dataset($schema,$line,$prefix,$relation=true)
  {
    $query="";
    $return="";
    $groupby=false;
    $grouptake=false;
    $aggregate="";
    
    if(isset($this->query["\$groupby"]["\$fields"])) $groupby=true;
    
    foreach($schema["structure"] as $field)
    {
      
      // when group by is used only return aggregated or group by columns in json
      $grouptake=false;
      if(isset($this->query["\$groupby"]["\$fields"][$field["name"]])) $grouptake=true; 
      if(isset($this->query["\$groupby"]["\$fields"])) if(in_array($field["name"],$this->query["\$groupby"]["\$fields"])) $grouptake=true;
      if(isset($this->query["\$groupby"]["\$aggregate"][$field["name"]])) $grouptake=true;
      
      
      //if($grouptake==true) $return[$aggregate."_".$field["name"]]=$line[$aggregate.".".$prefix.".".$field["name"]];
      
      if($grouptake==true or $groupby==false)
      //if($groupby==false)
      {
        switch($field["type"])
        {
          case "relation":
            if($relation)
            {
              $this->create($this->schemas->{$field["schema"]});
              unset($query);
              $query[$field["key"]]=$line[$prefix."._id"];
              $return[$field["name"]]=$this->select($this->schemas->{$field["schema"]},$query);
            }
            break;
          case "multiplekey":  
          case "json":
            $return[$field["name"]]=json_decode(stripslashes(html_entity_decode(preg_replace('/u([\da-fA-F]{4})/', '&#x\1;', $line[$prefix.".".$field["name"]]))));
            break;
          case "key":
            if($relation)
            {
              $return[$field["name"]]=$this->dataset($this->schemas->{$field["schema"]},$line,$prefix.".".$field["schema"],false);
            }
            break;
          case "translate":
            if($line[$prefix.".".$field["name"]])
            {
              $return[$field["name"]]=$line[$prefix.".".$field["name"]];
            }
            else
            {
              $translate="";
              foreach($this->languages as $lkey=>$lvalue) $translate.=$line[$prefix."._".$lkey."_".$field["name"]]." ";
              $return[$field["name"]]=trim($translate);
            }
            break;
          case "section_start":
          case "section_end":
          case "html":
          case "button":
            break;
          default:
            $return[$field["name"]]=$line[$prefix.".".$field["name"]];
            break;
        }
      }
    }
      
    // add the _id and status
    if(!$groupby) $return["_id"]=$line[$prefix."._id"];
    if(!$groupby) $return["_status"]=$line[$prefix."._status"];
    return $return;
  }
  
  // This function is used to upsert data into the database
  function upsert($schema,$dataset,$parent=[])
  {
    
    if (!isset($this->databases[$schema["database"]]["upsert"])) die(error("Please setup the 'write' flag of your database",-1));
    if ($this->databases[$schema["database"]]["upsert"]==false) die(error("write to this database is not allowed",-1));
    
    
    //for insert;
    $insert_columns=[];
    $insert_values=[];
    $rights=[];
    
    //for update
    $update_values=[];
    
    $table=$schema["table"];
    
    
    if (!$this->admin) if(substr($table,0,1)=="_") die(error("error trying to write in table ".$schema["table"]." : tables starting with _ are for internal use only!",-11));
    
    // other
    $structure=$schema["structure"];
    $match=false;
    $_id=0;
    $_status=0;
    
    //dataset to return
    $return=[];

    // name of the columns to insert
    foreach($structure as $field)
    {
      switch($field["type"])
      {
        case "relation":
          break;
        case "translate":
          $insert_columns[]="`_".$this->lang."_".$field["name"]."`";
          break;
        case "section_start":
        case "section_end":
        case "html":
        case "button":
          break;
        default:
          $insert_columns[]="`".$field["name"]."`";
          break;
      }
    }
    
    // add the standards columns
    $insert_columns[]="`_ip`,`_time`,`_color`";
    $insert_columns[]="`_user_id`,`_group_id`,`_level`";
   
    // parse each line in the dataset
    foreach($dataset as $line)
    {
      //reset the query arrays
      $insert_values=[];
      $update_values=[];
      $_id=0;
      
      // if dataset have a parent relation add the id of the parent in the corresponding field
      //if(count($parent)>0) $line->{$parent->{"field"}}=$parent->{"_id"};
        
      // parse the structure
      foreach($structure as $field)
      {
        $match=false;
        
        if ( $field["type"]=="section_start" or $field["type"]=="section_end" or $field["type"]=="html" or $field["type"]=="button") $match=true;
        
        // Parse each column in the line
        foreach($line as $column=>$value)
        {
          // look for id to choose between insert or update
          if ($column=="_id") $_id=$value;
          if ($column=="_status") $_status=$value;
          
          // Column in the object must be referenced in the structure
          if($column==$field["name"])
          {
            $match=true;
            
            //echo $column."<br>";
            
            // Choose what to do depending on the column type
            switch($field["type"])
            {
              case "relation":
                if(!is_array($value))
                {
                  // only make a check. Recording this into the database will be done later
                  die(error($field["name"]." should be a array",-4));
                }
                break;
              case "boolean":
                if($value==1)
                {
                  $insert_values[]="1";
                  $update_values[]="`".$column."`=1";
                }
                else
                {
                  $insert_values[]="0";
                  $update_values[]="`".$column."`=0";
                }
                break;
              case "multiplekey":
              case "json":
                  // we store all the mess as a sting
                  $insert_values[]="'".addslashes(json_encode($value))."'";
                  $update_values[]="`".$column."`='".addslashes(json_encode($value))."'";
                break; 
              case "select":
              case "list":
                if(!isset($field["enum"])) die(error($field["name"]." need and enum array!",-5));
                if(!is_array($field["enum"])) die(error($field["name"]." enum element is not an array!",-5)); 
                if(in_array($value,$field["enum"]))
                {
                  $insert_values[]="'".addslashes($value)."'";
                  $update_values[]="`".$column."`='".addslashes($value)."'";
                }
                else
                {
                  die(error("$value in ".$field["name"]." not in the list",-5));
                }
                break;
              case "key":
                  $insert_values[]="'".addslashes($value->_id)."'";
                  $update_values[]="`".$column."`='".addslashes($value->_id)."'";
                break;
              case "translate":
                $column="_".$this->lang."_".$column;
              default:
                if(preg_match("#".$field["regex"]."#",$value) or $status=9) //allow not matching the regex if we delete the line
                {
                  $insert_values[]="'".addslashes($value)."'";
                  if ($value!="!!NOCHANGE!!" or is_numeric($value) ) $update_values[]="`".$column."`='".addslashes($value)."'";
                }
                else
                {
                  die(error("$value in ".$field["name"]." does not match ".$field["regex"],-5));
                }
                break;
            }
          }
        }
        
        // if we dont find the value then add empty or trigger an error if not optional
        
        if ($match==false)
        {
          if($field["optional"]==false)
          {
            die(error("No value for ".$field["name"]." was found",-6));
          }
          else
          {
            $insert_values[]="null";
          }
        }
      
      
      }
  
      
      //check if right where set and setup default
      if(!isset($schema["sgroup"])) $schema["sgroup"]="W";
      if(!isset($schema["slevel"])) $schema["slevel"]="W";
      if(!isset($schema["sother"])) $schema["sother"]="W";
      
      $rights[]=" `$table`.`_user_id`='".$this->user["_id"]."'";
      if($schema["sgroup"]=="W") $rights[]="`$table`.`_group_id` in (".implode(",",$this->user["groups"]).")";
      if($schema["slevel"]=="W") $rights[]="`$table`.`_level`<'".$this->user["level"]."'";
      if($schema["sother"]=="W") $rights[]=" true "; 
      
      // add default values
      //$insert_columns[]="`_ip`,`_time`,`_color`";
      $insert_values[]="'".$_SERVER["REMOTE_ADDR"]."','".date("Y-m-d H:i:s")."',''";
      $update_values[]="`_ip`='".$_SERVER["REMOTE_ADDR"]."',`_time`='".date("Y-m-d H:i:s")."',`_color`=''";
      
      
      
      // choose between update and insert
      switch(true)
      {
        case $_id==0 and $_status<9: // can not create deleted lines
          
          //$insert_columns[]="`_user_id`,`_group_id`,`_level`";
          $insert_values[]="'".$this->user["_id"]."','".$this->user["group"]["_id"]."','".$this->user["level"]."'";
      
          $query="insert into `$table` (".implode(",",$insert_columns).") values (".implode(",",$insert_values).");";
          break;
        case $_id!=0:
          $query="update `$table` set ".implode(",",$update_values).",`_status`='$_status' where `_id`='$_id' and (".implode(" or ",$rights).") and `_status`<7;"; //can not update locked or deleted lines
          break;
      }
      
      //echo $query;
      
      try
      {
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
      }
      catch(Exception $e) 
      {
          die(error($e->getMessage()." : ".$query,-3));
      }
      
      // add id to the dataset line
      if($_id==0) $line->_id=$this->conn->lastInsertId();
      
      //do the same recursively to each relation data
      foreach($line as $column=>$value)
      {
        foreach($structure as $field)
        {
          if($field["name"]==$column)
          {
            if($field["type"]=="relation")
            {
              //$description=$structure[$column];
              
              /* 
              $child=new bi();
              $child->connect();
              $child->create($schemas->{$description["table"]});
              $child->upsert($schemas->{$description["table"]},$value);
              */
              
              $relation=(object) array();
              $relation->field=$field["key"];
              $relation->_id=$line->_id;
              
              //print_r($value);
              
              // $child=new bi();
              // $child->connect();
              $this->create($this->schemas->{$field["schema"]});
              $child=$this->upsert($this->schemas->{$field["schema"]},$value,$relation);
              
              $line->{$column}=$child;
              
              
              
            }
          }
        }
      }
      
      // add the completed line to the return
      $return[]=$line;
      
    }
    
    return $return;
  }

}

function implode_match($glue,$array,$regex)
{
  $reply="";
  
  foreach($array as $element) if(preg_match("#".$regex."#",$element)) $reply.=$element.$glue; 
  rtrim($reply,$glue);
  
  return $reply;
}


/********************************************************************************** 

Mid level functions provide building blocs for html pages

***********************************************************************************/

// Html header and setup schemas for javascrip
// include javascript 
function header($background="#FFFFFF")
{
$lbl=$GLOBALS['lbl'];
$user=$GLOBALS['user'];
$schemas=$GLOBALS['schemas'];
$project=$GLOBALS['project'];
$lang=$GLOBALS['lang'];


/*
  <html xmlns='http://www.w3.org/1999/xhtml' xml:lang='$language'>
  <meta http-equiv='content-language' content='$language' />
  <meta name='language' content='$language' />
*/

if($project=="") error("Project name is not set please do it in nano_config.php",-8);
  

/*
<!-- bootstrap 4 -->
<link rel='stylesheet' href='https://cdn.rawgit.com/twbs/bootstrap/v4-dev/dist/css/bootstrap.css' integrity='sha384-XXXXXXXX' crossorigin='anonymous'>
<script src='https://cdn.rawgit.com/twbs/bootstrap/v4-dev/dist/js/bootstrap.js' integrity='sha384-XXXXXXXX' crossorigin='anonymous'></script>  
*/

//bootstrap 3
/*
<!-- bootstrap 3 -->
<link rel='stylesheet' href='./bootstrap/css/bootstrap.min.css'>
<link rel='stylesheet' type='text/css' media='print' href='./bootstrap/css/bootstrap.min.css'>
<script src='./bootstrap/js/bootstrap.min.js'></script>
*/

$html="<!DOCTYPE html>
<head>
<meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no' charset='utf-8' >

<!-- apple -->
<meta name='apple-mobile-web-app-capable' content='yes'>
<meta name='apple-mobile-web-app-status-bar-style' content='black'>
<link rel='apple-touch-icon' href='flat_berdoz.png'>

<!-- jquery -->
<script src='//code.jquery.com/jquery-1.11.0.min.js'></script>
<script src='//code.jquery.com/jquery-migrate-1.2.1.min.js'></script>
<script src='//code.jquery.com/ui/1.11.2/jquery-ui.min.js'></script>

<!-- jquery plugins -->
<script src='./jquery/jquery.tablesorter.min.js'></script>

<!-- google graph -->
<script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>

<!-- bootstrap 3 -->
<link rel='stylesheet' href='./bootstrap/css/bootstrap.min.css'>
<link rel='stylesheet' type='text/css' media='print' href='./bootstrap/css/bootstrap.min.css'>
<script src='./bootstrap/js/bootstrap.min.js'></script>

<!-- font awesome -->
<link href='./font-awesome-4.6.1/css/font-awesome.min.css' rel='stylesheet'>

<!-- nanofw -->
<link rel='stylesheet' href='nanofw/nano.css'>
<link rel='stylesheet' href='$project.css'>
<script src='nanofw/nano.js'></script>
<script src='".$project.".js'></script>";

$html.="<script>
var user='".$user["user"]."';
var label=new Object();
var lang='$lang';
var schemas=new Object();\n";

// URL params
$html.="url_data=".json_encode($_GET).";\n";

// Language translations
if($lbl) $html.="label=".json_encode($lbl).";\n";

//Schemas
foreach($schemas as $key=>$value) $html.="schemas.$key=".json_encode($value).";\n";

$html.="</script>
</head>
<html lang='fr'>
<body style='background:$background' >";


return $html;
}

// Html footer and setup the standard modal dialog for custom fields 
function footer()
{
  // add the nano standard popups
  $html=modal("nano_key_choose_popup",lbl("Choose"),"<div id='nano_key_choose_div'></div>","");
  
  $html.="</body></html>";
  return $html;
}

// Html Navebar provide a home button a ajax working indication and language switch 
function navbar($links)
{
$languages=$GLOBALS['languages'];
$user=$GLOBALS['user'];
$lang=$GLOBALS['lang'];


$lang_html="";
foreach($languages as $key=>$value)
{
  if($lang!=$key) { $lang_html.="<li class='active'><a href='nanofw/nano_lang.php?lang=$key'>".$value["name"]."</a></li>"; }
  else {  $lang_html.="<li><a>".$value["name"]."</a></li>"; }
}
  
$html="<!-- Navbare -->
<nav class='navbar navbar-inverse navbar-static-top' role='navigation'>
  <div class='container'>
  <div class='navbar-header'>
    <button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#navbar' aria-expanded='false' aria-controls='navbar'>
      <span class='sr-only'>Toggle navigation</span>
      <span class='icon-bar'></span>
      <span class='icon-bar'></span>
      <span class='icon-bar'></span>
    </button>
  </div>
  <div id='navbar' class='collapse navbar-collapse'>
    <ul class='nav navbar-nav'>
      <li class='active'><a href='index.php'>Home</a></li>
    </ul>
    <ul class='nav navbar-nav'>
      <a class='navbar-brand' href='#' style='width:50px'>
        <img id='nano_wait'  style='display:none; max-width:100px; margin-left:7px; margin-top: -7px;' src='nanofw/nano_wait.gif'>
      </a>
    </ul>
    <ul class='nav navbar-nav navbar-right'>
      $lang_html
      <li>&nbsp;</li>
      <li class='active'><a href='nanofw/nano_logout.php'>Logout</a></li>";
      if ($user["is_admin"]) $html.= "<li>&nbsp;</li><li class='active'><a href='nano_admin.php'>Admin</a></li>"; 
   $html.= " </ul>
    
  </div><!--/.nav-collapse -->
  </div>
</nav>";

return $html;
}

// Modal dialog from boostrap
function modal($modal_name,$modal_label,$modal_content,$modal_js)
{
$lbl=$GLOBALS['lbl'];
$html="<!-- common modal -->
<div class='modal fade' id='$modal_name'>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
          <span aria-hidden='true'>&times;</span>
        </button>
        <h4 class='modal-title'>$modal_label</h4>
      </div>
      <div class='modal-body'>
        $modal_content
      </div>";
        if ($modal_js!="")
        {
          $html.="<div class='modal-footer'>";
          $html.="<button type='button' class='btn btn-default' data-dismiss='modal'>".lbl("close")."</button>";
          $html.="<button type='button' class='btn btn-primary' onclick='$modal_js'>".lbl("save")."</button></div>";
        }
      $html.="
    </div>
  </div>
</div>";

return $html;
}

// Build a form according to the schema definition
function form($schema,$action,$default_values,$form)
{
  $schemas=$GLOBALS["schemas"];
  // col is the bootstrap column
  // action is an array of object for button [{"label":"edit","function":"client_edit"}]
  // structure describe the form components
  
  $disabled="disabled";
  $html="<form nano_mode='lock'  nano_schema='".$schema["name"]."' name='$form' onsubmit='return nano.submit();' ><div class='row'>";
  $nbr=0;
  $tabs="";
 
  $section=0;
  
  $structure=$schema["structure"];

  if(!is_array($structure)) die(error("Your structure is not an array",-1));
  
  foreach($structure as $s)
  {
    $nbr++;
    
    $collapse="in";
    $collapsed="";
    if(isset($s["collapse"])) if($s["collapse"]==true) 
    {
      $collapse="";
      $collapsed="collapsed";
    }
    
    $display=["list","form"];
    if(isset($s["display"])) $display=$s["display"];
    
    $id="";
    if(isset($s["id"])) $id="id='".$s["id"]."'";
    
    $type="text";
    if(isset($s["type"])) $type=$s["type"];
    
    $label="label_$nbr";
    if(isset($s["label"])) $label=$s["label"];
    
    $name="name_$nbr";
    if(isset($s["name"])) $name=$s["name"];
    
    $col="col-xs-3";
    if(isset($s["col"])) $col=$s["col"];
    
    $placeholder="";
    if(isset($s["placeholder"])) $placeholder=" placeholder='".$s["placeholder"]."'";
    
    $pattern="";
    if(isset($s["regex"])) $pattern=" pattern='".$s["regex"]."'";
    
    $onchange="";
    if(isset($s["onchange"])) $onchange=" onchange='".$s["onchange"]."(this)'";
    
    $required="";
    if(isset($s["required"])) if($s["required"]==true) $required=" required";
    
    $onclick="";
    if(isset($s["onclick"])) $onclick="onclick='".$s["onclick"]."(this)'";
    
    $btn="btn-primary";
    if(isset($s["btn"])) $btn=$s["btn"];
    

    //$nano_default="";
    //if(isset($s["value"])) $nano_default="nano_default='toto'"; //".$s["value"]."
    
    if(isset($s["clearall"])) if($s["clearall"]==true) $html.="<div style='clear:both'></div>";
    
    $default_value="";
    
    if(isset($s["value"])) $default_value=$s["value"]; //".$s["value"]."
    $hidden="";
    
    foreach($default_values as $dkey=>$dvalue)
    { 
      if($dkey==$s["name"])
      {
        $default_value=$dvalue;
        $hidden="hidden";
      }
    }
      
    if(in_array("form",$display)) 
    {
      switch($type)
      {
        case "translate":
        case "string":
        case "double":
        case "float":
          $html.="<div $id class='form-group $col'>"; 
          $html.="<label class='control-label' >$label</label>";
          if($onclick!="") $html.="<div class='input-group'>"; // form-control-static //form-control
          $html.="<input nano_type='$type' class='form-control' type='text' nano_default='$default_value' name='$name'  $placeholder $onchange $pattern $required $disabled $hidden>";
          if($onclick!="") $html.="<span class='input-group-btn'><button type='button' class='btn btn-primary' onclick='".$s["onclick"]."($form.$name)'>".lbl($s["onclick"])."</button></span></div>";
          $html.="</div>";
          break;
        case "text":
          $html.="<div $id class='form-group $col'>"; 
          $html.="<label class='control-label' >$label</label>";
          if($onclick!="") $html.="<div class='input-group'>"; // form-control-static //form-control
          $html.="<textarea nano_type='$type' class='form-control' nano_default='$default_value' name='$name'  $placeholder $onchange $pattern $required $disabled $hidden></textarea>";
          //if($onclick!="") $html.="<span class='input-group-btn'><button type='button' class='btn btn-primary' onclick='".$s["onclick"]."($form.$name)'>".lbl($s["onclick"])."</button></span></div>";
          $html.="</div>";
          break;
        case "html":
          $html.="<div $id class='form-group $col'>";
          $html.=$s["html"];
          $html.="</div>";
          break;
        case "button":
          $html.="<div $id class='form-group $col' >";
          $html.="<label class='control-label' >&nbsp;</label>";
          $html.="<input type='button' nano_type='button' class='form-control btn $btn' name='$name' value='$label' $onclick $disabled $hidden>";
          $html.="</div>";
          break;
        case "password":
          $html.="<div $id class='form-group $col'>";
          $html.="<label class='control-label' >$label</label>";
          $html.="<input nano_type='$type' class='form-control' type='password' nano_default='$default_value' name='$name' $onchange $pattern $required $disabled $hidden>";
          $html.="</div>";
          break;
        case "number":
        case "integer":
          $html.="<div $id class='form-group $col'>";
          $html.="<label class='control-label' >$label</label>";
          if($onclick!="") $html.="<div class='input-group'>";
          $html.="<input nano_type='$type' class='form-control' type='number' nano_default='$default_value' name='$name'  $placeholder $onchange $pattern $required $disabled $hidden>";
          if($onclick!="") $html.="<span class='input-group-btn'><button type='button' class='btn btn-primary' onclick='".$s["onclick"]."($form.$name)'>".lbl($s["onclick"])."</button></span></div>";
          $html.="</div>";
          break;
        case "date":
          $html.="<div $id class='form-group $col'>";
          $html.="<label class='control-label' >$label</label>";
          $html.="<input nano_type='$type' class='form-control' type='date' nano_default='$default_value' name='$name' placeholder='yyyy-mm-dd'  $onchange $pattern $required $disabled $hidden>";
          $html.="</div>";
          break;
        case "hidden":
        case "relation":
          $html.="<input $id nano_type='$type' type='text' nano_default='$default_value' name='$name' hidden>";
          break;
        case "json":
          $html.="<input $id nano_type='$type' type='text' nano_default='$default_value' name='$name' nano_value='' hidden>";
          $html.="<div id='".$name."_json_div' class='form-group $col' $onclick $hidden></div>";
          break;
        case "select":
        case "list":
          $html.="<div $id class='form-group $col'>";
          $html.="<label class='control-label' >$label</label>";
          $html.="<select nano_type='$type' class='form-control' nano_default='$default_value' name='$name' $onchange $disabled $hidden>";
          $html.="<option value=''>".lbl("choose a value")."</option>";
          if(isset($s["enum"])) foreach($s["enum"] as $e) $html.="<option value='$e'>".lbl($name."_".$e)."</option>";
          $html.="</select>";
          $html.="</div>";
          break;
        case "key":
          $html.="<div $id class='form-group $col'>";
          $html.="<label class='control-label' >$label</label>";
          $html.="&nbsp;<small>".lbl("type ? for the list")."</small>";
          
          $html.="<input nano_type='$type' class='form-control' type='text' nano_default='$default_value' $placeholder  name='$name' nano_schema='".$s["schema"]."' onchange='nano.key_find(this,\"onfind\");'  $pattern $required $disabled $hidden>";
          $html.="</div>";
          break;
        case "boolean":
        case "checkbox":
          $html.="<div $id class='form-group $col'>";
          $html.="<label class='control-label' >&nbsp;</label>";
          $html.="<div class='checkbox'><label>";
          $html.="<input nano_type='$type' type='checkbox' nano_default='$default_value' name='$name' $onchange   $required $disabled $hidden>&nbsp;$label";
          $html.="</div></label>";
          $html.="</div>";
          break;
        case "section_start":
          $html.="<div $id class='panel panel-default' >";
          $html.="<div class='panel-heading'>"; //class='btn btn-default btn-sm nano-btn $collapsed'
          $html.="<a class='btn btn-xs btn-default nano-btn $collapsed' type='button' data-toggle='collapse' aria-expanded='false' data-target='#".$s["id"]."_content'></a>";
          $html.="&nbsp;<span class='panel-title'>$label</span>";
          $html.="</div>";
          //$html.="<hr class='col-xs-12'>";
          $html.="<div class='collapse $collapse panel-body' id='".$s["id"]."_content' style='padding:0.1px'><br>";
          $section++;
          break;
        case "section_end":
          $html.="</div>";
          $html.="</div>";
          $section--;
          break;    
        case "multiplekey":
          $html.="<div $id class='form-group $col'>";
          $html.="<label class='control-label' >$label</label><br>";
          $html.="<small>".lbl("Hold ctrl for mulptiple selection.")."</small>";
          
          $keylist=new data(false);
          $keylist->connect($schemas->{$s["schema"]});
          $keylist->create($schemas->{$s["schema"]});
          
          $html.="<select nano_type='$type' size='10' class='form-control' nano_default='$default_value' name='$name' $onchange  $disabled $hidden multiple>";
          foreach($keylist->select($schemas->{$s["schema"]},[]) as $list) $html.="<option value='".$list["_id"]."'>".implode_match(' ',$list,"^[^0-9].*$")."</option>";
          $html.="</select>";
          $html.="</div>";
          break;
      }
    }  
  }
  
  for($i=0;$i<$section;$i++) $html.="</div></div>"; //if user forget to close section we do it for him
  
  $html.="<input nano_type='hidden' type='text' name='_id' value=0 hidden>";
  $html.="<input nano_type='hidden' type='text' name='_status' value=0 hidden>";

  
  foreach($action as $a)
  {
    $col="col-xs-6";
    $onclick='nano_submit';
    if(isset($a["col"])) $col=$a["col"];
    if(isset($a["onclick"])) $onclick=$a["onclick"];
    
    if(isset($a["clearall"])) if($a["clearall"]==true) $html.="<div style='clear:both'></div>";
    
    $btn="btn-primary";
    if(isset($a["btn"])) $btn=$a["btn"];
    
    switch($a["type"])
    {
        case "button":
          $html.="<div class='form-group $col' >";
          $html.="<label class='control-label' >&nbsp;</label>";
          $html.="<input type='button' nano_type='button' class='form-control btn $btn' name='".$a["name"]."' onclick='$onclick(this.form)' value='".$a["label"]."'>";
          $html.="</div>";
          break;
    }
    
    
  }
  
  
  $html.="</div></form>";  

  return $html;  
}

/********************************************************************************** 

High level fuction use building blocs to provide out of the box CRUD

***********************************************************************************/

// full CRUD on a schema
function quickList($schema,$param=[])
{
  $html="";
  $schemas=$GLOBALS['schemas'];
  
  $default_query="{}";  
  if (isset($param["default_query"])) $default_query=$param["default_query"]; 
  
  $show_buttons=true;  
  if (isset($param["show_buttons"])) $show_buttons=$param["show_buttons"]; 
  
  $autoload;
  if (isset($param["autoload"])) $autoload=$param["autoload"]; 
  
  $default_values=[];  
  if (isset($param["default_values"]))
  {
    unset($default_values);
    $default_values=$param["default_values"]; 
    //print_r($default_values);
  }
  
  // create the form for client edit  
  /*$action=
  [
  ["name"=>"prev", "label"=>"<", "col"=>"col-xs-3", "type"=>"button", "clearall"=>true, "onclick"=>"nano.form_prev(this.form);" ],
  ["name"=>"next", "label"=>">",  "col"=>"col-xs-3","type"=>"button", "onclick"=>"nano.form_next(this.form);" ],
  ["name"=>"edit", "label"=>"Edit", "col"=>"col-xs-3","type"=>"button", "onclick"=>$schema."_edit_mode(this.form);" ],
  ["name"=>"search", "label"=>"Search", "col"=>"col-xs-3","type"=>"button", "onclick"=>$schema."_find_mode(this.form);" ],
  ["name"=>"delete", "label"=>"Delete", "col"=>"col-xs-3","type"=>"button", "onclick"=>"form_delete(this.form);" ],
  ["name"=>"save", "label"=>"Save", "col"=>"col-xs-3","type"=>"button", "onclick"=>$schema."_save(this.form);" ],
  ];*/ 
  
  $action=
  [
    ["name"=>"prev", "label"=>"<", "col"=>"col-xs-3", "type"=>"button", "clearall"=>true, "onclick"=>"nano.form_prev(this.form);" ],
    ["name"=>"next", "label"=>">",  "col"=>"col-xs-3","type"=>"button", "onclick"=>"nano.form_next(this.form);" ],
    ["name"=>"ok", "label"=>"OK", "col"=>"col-xs-3 col-xs-offset-3","type"=>"button", "onclick"=>$schema."_ok();" ],
  ]; 
  
  $form=form($schemas->{$schema},$action,$default_values,$schema."_edit_form"); 
  $html.=modal($schema."_edit_popup",lbl($schema."_edit_popup"),$form,""); //$schema."_save(document.".$schema."_edit_form);"

  // prepare the find popup  
  $html.=modal($schema."_find_popup",lbl($schema."_find_popup"),"<div id='".$schema."_find_div'></div>",$schema."_find_validate(document.".$schema."_find_form);");
  
  if($show_buttons)
  {
    $html.="<!-- Std nano actions -->
    <h4>".lbl($schema)."</h4>
    <div class='row'>
      <div class='form-group col-xs-6 col-md-3'>
        <label class='control-label'>&nbsp;</label>
        <button class='form-control btn btn-primary' type='button' onclick='".$schema."_new_mode({});'>".lbl("new")."</button>
      </div>
      <div class='form-group col-xs-6 col-md-3'>
        <label class='control-label'>&nbsp;</label>
        <button class='form-control btn btn-default' type='button' onclick='".$schema."_sub_selection()'>".lbl("sub selection")."</button>
      </div>
      <div class='form-group col-xs-6 col-md-3'>
        <label class='control-label'>&nbsp;</label>
        <button class='form-control btn btn-default' type='button' onclick='".$schema."_mass_delete()'>".lbl("delete")."</button>
      </div>
      <div class='form-group col-xs-6 col-md-3'>
        <label class='control-label'>&nbsp;</label>
        <button class='form-control btn btn-default' type='button' onclick='".$schema."_find_mode()'>".lbl("find")."</button>
      </div>
    </div>
    ";
  }
    
  $html.="<div id='".$schema."_list_div' ></div>";
  
  $html.="<script>";
  
  // std nano var
  $html.="
  schemas.$schema.data=[];
  schemas.$schema.ix=-1;
  default_query=[];
  
  
  nano.load('$schema',default_query,".$schema."_display);
  
  ";
  
  if($autoload)
  {
    $html.="nano.form_load(url_data,document.".$schema."_edit_form);";
  }
  
  // load data on page open
  
  $html.=quickListFunctions($schema);
  
  $html.="</script>";
  
  return $html;
  
}

function quickListFunctions($schema)
{
  
  // Std nano open in edit mode
  $html="
  function ".$schema."_edit_mode(ix) 
  {
      var form=document.".$schema."_edit_form;
      if(ix) schemas.$schema.ix=ix;
      nano.form_prepare(form,'edit',{});
      nano.form_load(schemas.$schema.data[schemas.$schema.ix],form);
      $('#".$schema."_edit_popup').modal('show');
  }
  ";
  
  // std nano open in find mode
  $html.="
  function ".$schema."_find_mode()
  {
      var form=document.".$schema."_edit_form;
      nano.form_reset(form);
      nano.form_prepare(form,'find',{});
      $('#".$schema."_edit_popup').modal('show');
  }
  ";
  
  // Std nano open in new mode
  $html.="
  function ".$schema."_new_mode(default_values) 
  {
    var form=document.".$schema."_edit_form;
    nano.form_reset(form);
    nano.form_prepare(form,'new',default_values);
    if(schemas.$schema.onload) window[schemas.$schema.onload]();
    $('#".$schema."_edit_popup').modal('show');
  }
  ";
  
  // Std nano ok  
  $html.="
  function ".$schema."_ok() 
  {
    var form=document.".$schema."_edit_form;
    var mode=form.attributes.nano_mode.value;
    var valide=false;
    
    if(form._status.value>=7) mode='lock';
    
    switch(mode)
    {
      case 'new':
      case 'edit':
        valide=".$schema."_save(form);
        break;
      case 'find':
        valide=".$schema."_find(form);
        break;
      case 'lock':
        break;
    }
    
    if(valide)
    {
      $('#".$schema."_edit_popup').modal('hide');
      nano.form_prepare(document.".$schema."_edit_form,'lock',{});
    }
  }
  ";
  
  
  // std nano sub select function
  $html.="
  function ".$schema."_sub_selection()
  {
    var i;
    var tmp_array=[];
    
    for(i in document.getElementsByName('".$schema."_checkbox'))
    {
      if(document.getElementsByName('".$schema."_checkbox')[i].checked==true) tmp_array.push(schemas.$schema.data[document.getElementsByName('".$schema."_checkbox')[i].value]);
    }
    
    schemas.$schema.data=tmp_array;
    
    var param=
    {
      'onclick':'".$schema."_edit_mode',
      'multiselect':true
    }
    
    $('#".$schema."_list_div').html(nano.table(schemas.$schema,schemas.$schema.data,param));
  }
  ";

  // std nano delete function
  $html.="
  function ".$schema."_mass_delete()
  {
    var i;
    var tmp_array=[];
    var checkboxes=document.getElementsByName('".$schema."_checkbox');
    
    if(!schemas.$schema.data) return false;
    
    if(schemas.$schema.data.length==0) return false;
    
    if(window.confirm(nano.lbl('Do you really want to delete')))
    {
      for(i in checkboxes)
      {
        if(checkboxes[i].checked==true && checkboxes[i].type=='checkbox' )
        {
          schemas.$schema.data[checkboxes[i].value]._status=9;
          tmp_array.push(schemas.$schema.data[checkboxes[i].value]);
        }
      }
      
      nano.save('$schema',tmp_array,function(){}); 
      ".$schema."_display(schemas.$schema.data);
    }
  }
  ";
  
   // std nano delete function
  $html.="
  function ".$schema."_delete()
  {
    var tmp_array=[];
    
    if(schemas.$schema.data.length==0) return false;
    
    if(window.confirm(nano.lbl('Do you really want to delete')))
    {
      schemas.$schema.data[schemas.$schema.ix]._status=9;
      tmp_array.push(schemas.$schema.data[schemas.$schema.ix]);
      schemas.$schema.data.splice(schemas.$schema.ix,1)
      
      
      nano.save('$schema',tmp_array,function(){}); 
      
      
      ".$schema."_display(schemas.$schema.data);
    }
  }
  ";
  
 

  //std nano find 
  $html.="
  function ".$schema."_find(form)
  {
    var query=nano.form_query(form);
    nano.load('$schema',query,".$schema."_display)
    return true;
  }
  ";

   // std nano display
  $html.="
  function ".$schema."_display(reply)
  {
    schemas.$schema.data=reply;
    
    var param=
    {
      'onclick':'".$schema."_edit_mode',
      'multiselect':true
    }
    
    $('#".$schema."_list_div').html(nano.table(schemas.$schema,schemas.$schema.data,param));
    //$('#".$schema."_list_div').html(nano.div_list(schemas.$schema,schemas.$schema.data,param));
    
    if(typeof ".$schema."_callback == 'function') ".$schema."_callback(); 
    
  }
  ";

  // Std nano save function
  $html.="
  function ".$schema."_save(form)
  {
    var json;
    var c;
    var data='?';
    var _id=0;
    if(form._id) _id=form._id.value;
    
    var callback=function(reply)
    {
      
      if(_id==0) 
      {
        schemas.$schema.data.push(reply[0]);
        schemas.$schema.ix=schemas.$schema.data.length-1;
      }
      else
      {  
        schemas.$schema.data[schemas.$schema.ix]=reply[0];
      }
      
      ".$schema."_display(schemas.$schema.data);
      
    }

    if(nano.form_validate(form))
    {
      json=nano.form_save(form);
      var dataset=[];
      dataset.push(json);
      nano.save('$schema',dataset,callback);
    }
    else
    {
      return false;
    }
    
    return true;
  }

  ";
  
  

  
  return $html;
  
}

function quickEdit($schema,$param=[])
{
  $html="";
  $schemas=$GLOBALS['schemas'];
  $tabs;
  $tabs_panes;
  
  $action=[];
  
  $mode="div";
  if(isset($param["mode"])) $mode=$param["mode"];
  
  $onload="load";
  if(isset($param["onload"])) $onload=$param["onload"];
  
  $default_query="{}";  
  if (isset($param["default_query"])) $default_query=$param["default_query"]; 
  
  $show_buttons=true;  
  if (isset($param["show_buttons"])) $show_buttons=$param["show_buttons"]; 
  
  $autoload;
  if (isset($param["autoload"])) $autoload=$param["autoload"]; 
  
  
  // add form actions button  
  if($mode=="form")
  {
    $action[]=["name"=>"cancel", "btn"=>"btn-default", "label"=>"Cancel", "col"=>"col-xs-2 col-xs-offset-8","type"=>"button", "onclick"=>$schema."_cancel();" ];
    $action[]=["name"=>"ok", "btn"=>"btn-primary","label"=>"OK", "col"=>"col-xs-2","type"=>"button", "onclick"=>$schema."_ok();" ]; 
  }
  elseif($mode=="div")
  {
    $action[]=["name"=>"ok", "btn"=>"btn-primary","label"=>"OK", "col"=>"col-xs-2 col-xs-offset-10","type"=>"button", "onclick"=>$schema."_ok();" ]; 
  }
  
  $html.="<h1>".lbl($schema)."</h1>";
  
  
  if($mode=="div")
  {
    $html.="<div id='".$schema."_div'></div>";
  }
  elseif($mode=="form")
  {
    $html.=form($schemas->{$schema},$action,[],$schema."_edit_form"); 
  }
  
  if($show_buttons)
  {
    $html.="<!-- Std nano actions -->
    
    <div id='".$schema."_buttons_row' class='row'>
       <div class='form-group col-xs-4 col-md-2'>
        <label class='control-label'>&nbsp;</label>
        <button class='form-control btn btn-primary' type='button' onclick='nano.form_prev(document.".$schema."_edit_form); ".$schema."_callback();'><</button>
      </div>
      <div class='form-group col-xs-4 col-md-2'>
        <label class='control-label'>&nbsp;</label>
        <button class='form-control btn btn-primary' type='button' onclick='nano.form_next(document.".$schema."_edit_form); ".$schema."_callback();'>></button>
      </div>
      <div class='form-group col-xs-4 col-md-2'>
        <label class='control-label'>&nbsp;</label>
        <button class='form-control btn btn-primary' type='button' onclick='".$schema."_new_mode({}); ".$schema."_hide_buttons(); '>".lbl("new")."</button>
      </div>
      <div class='form-group col-xs-4 col-md-2'>
        <label class='control-label'>&nbsp;</label>
        <button class='form-control btn btn-default' type='button' onclick='".$schema."_edit_mode(null); ".$schema."_hide_buttons();'>".lbl("edit")."</button>
      </div>
      <div class='form-group col-xs-4 col-md-2'>
        <label class='control-label'>&nbsp;</label>
        <button class='form-control btn btn-default' type='button' onclick='".$schema."_delete()'>".lbl("delete")."</button>
      </div>
      <div class='form-group col-xs-4 col-md-2'>
        <label class='control-label'>&nbsp;</label>
        <button class='form-control btn btn-default' type='button' onclick='".$schema."_find_mode(); ".$schema."_hide_buttons();'>".lbl("find")."</button>
      </div>
    </div>
    ";
  }
  
  
  if($mode=="div")
  {
    $form=form($schemas->{$schema},$action,[],$schema."_edit_form");   
    $html.=modal($schema."_edit_popup",lbl($schema."_edit_popup"),$form,""); //$schema."_save(document.".$schema."_edit_form);"
  }
  
  $tabs="<ul class='nav nav-tabs' role='tablist'>";
  $tabs_panes="<div class='tab-content'>";
  $active="active";
  foreach($schemas->{$schema}["structure"] as $s)
  {
    if($s["type"]=="relation")
    {
      $action=
      [
        ["name"=>"prev", "label"=>"<",  "col"=>"col-xs-3", "type"=>"button", "clearall"=>true, "onclick"=>"nano.form_prev(this.form); ".$s["schema"]."_relation_reload(); " ],
        ["name"=>"next", "label"=>">",  "col"=>"col-xs-3","type"=>"button", "onclick"=>"nano.form_next(this.form); ".$s["schema"]."_relation_reload();" ],
        ["name"=>"ok", "label"=>"OK", "col"=>"col-xs-3 ","type"=>"button", "onclick"=>$s["schema"]."_ok();" ],
      ]; 
      
      $tabs.="<li role='presentation' class='$active'><a href='#".$s["schema"]."_tab_div' aria-controls='home' role='tab' data-toggle='tab'>".$s["label"]."</a></li>";
      $tabs_panes.="<div role='tabpanel' class='tab-pane $active' id='".$s["schema"]."_tab_div'>";
      $active="";
      //$tabs_panes.="<div >";
      $tabs_panes.="<!-- Std nano actions -->
      <div class='row'>
        <div class='form-group col-xs-6 col-md-2'>
          <label class='control-label'>&nbsp;</label>
          <button class='form-control btn btn-primary' type='button' onclick='".$s["schema"]."_new_mode({});'>".lbl("new")."</button>
        </div>
        <div class='form-group col-xs-6 col-md-2'>
          <label class='control-label'>&nbsp;</label>
          <button class='form-control btn btn-default' type='button' onclick='".$s["schema"]."_sub_selection()'>".lbl("sub selection")."</button>
        </div>
        <div class='form-group col-xs-6 col-md-2'>
          <label class='control-label'>&nbsp;</label>
          <button class='form-control btn btn-default' type='button' onclick='".$s["schema"]."_mass_delete()'>".lbl("delete")."</button>
        </div>
      </div>
      <div id='devis_list_div'></div>
      ";
      $tabs_panes.="<div id='".$s["schema"]."_list_div'></div>";
      $tabs_panes.="</div>";
      
      $html.="<script>";
      $html.=quickListFunctions($s["schema"]);
      $html.="</script>";
      $form=form($schemas->{$s["schema"]},$action,[$s["key"]=>0],$s["schema"]."_edit_form");
      $html.=modal($s["schema"]."_edit_popup",lbl($s["schema"]."_edit_popup"),$form,""); 
    }
  }
  $tabs.="</ul>";
  $tabs_panes.="</div>";
  
  $html.=$tabs;
  $html.=$tabs_panes;
  
  //$html.="<div id='".$schema."_list_div' ></div>";
  
  $html.="<script>";
  
  // std nano var
  $html.="
  schemas.$schema.data=[];
  schemas.$schema.ix=-1;
  
  function ".$schema."_hide_buttons()
  {
    $('#".$schema."_buttons_row :button').attr('disabled','disabled'); 
    $('.tab-content :button').attr('disabled','disabled');
  }
  
  ".$schema."_callback=function()
  {
    nano.form_load(schemas.$schema.data[schemas.$schema.ix],document.".$schema."_edit_form);
    $('#".$schema."_div').html(nano.div(schemas.$schema,schemas.$schema.data[schemas.$schema.ix],{'onclick':'".$schema."_edit_mode'},schemas.$schema.ix));
    ".$schema."_relation_reload();
    $('#".$schema."_buttons_row :button').removeAttr('disabled');
    $('.tab-content :button').removeAttr('disabled');
  }; 
  
  function ".$schema."_cancel()
  {
    $('#".$schema."_edit_popup').modal('hide');
    nano.form_prepare(document.".$schema."_edit_form,'lock',{});
    $('#".$schema."_buttons_row :button').removeAttr('disabled');
    $('.tab-content :button').removeAttr('disabled');
  };
  
  $('#".$schema."_edit_popup').on('hidden.bs.modal', function (e) {
  ".$schema."_cancel();
  })
  
  
  ";

  switch($onload)
  {
  case "load":
    $html.="nano.load('$schema',$default_query,".$schema."_display);";
    break;
  case "new":
    $html.=$schema."_new_mode({}); ".$schema."_hide_buttons();";
    break;
  }
  
  if($autoload)
  {
    $html.="nano.form_load(url_data,document.".$schema."_edit_form);";
  }
  
  
  $html.=quickListFunctions($schema);
 
  // std nano relation reload
  $html.="
  function ".$schema."_relation_reload()
  {
    var s;
    var field;
    
    if(schemas.$schema.data.length==0) return false;
    
    
    for(s in schemas.$schema.structure)
    {
      field=schemas.$schema.structure[s];
      if(field.type=='relation')
      {
        var param=
        {
          'onclick':field.schema+'_edit_mode',
          'multiselect':true
        }
        
        
        schemas[field.schema].data=schemas.$schema.data[schemas.$schema.ix][field.name];
        document[field.schema+'_edit_form'][field.key].attributes.nano_default.value=schemas.$schema.data[schemas.$schema.ix]._id;
        document[field.schema+'_edit_form'][field.key].attributes.nano_value=schemas.$schema.data[schemas.$schema.ix]
        
        document[field.schema+'_edit_form'][field.key].attributes.nano_value=schemas.$schema.data[schemas.$schema.ix]
        
        $('#'+field.schema+'_list_div').html(nano.table(schemas[field.schema],schemas.$schema.data[schemas.$schema.ix][field.name],param));
      }
     }
    
  
  }
  ";
  
  // std nano relation hide
  $html.="
  function ".$schema."_relation_clear()
  {
    var s;
    var field;
  
    for(s in schemas.$schema.structure)
    {
      field=schemas.$schema.structure[s];
      if(field.type=='relation') $('#'+field.schema+'_list_div').html('');
    }
  
  }
  ";
  
  
  $html.="</script>";
    
  
  return $html;
  
  
}

// full CRUD on a schema
function bulkInsert($schema,$param=[])
{
  $html="";
  $schemas=$GLOBALS['schemas'];
  
  $default_query="{}";  
  if (isset($param["default_query"])) $default_query=$param["default_query"]; 
  
  $show_buttons=true;  
  if (isset($param["show_buttons"])) $show_buttons=$param["show_buttons"]; 
  
  
  $html.="<h4>".lbl($schema)."</h4>";
  
  $action=
  [
    ["name"=>"add", "label"=>"add", "clearall"=>false,"col"=>"col-xs-6 col-xs-6","type"=>"button", "onclick"=>$schema."_add_line();" ],
    ["name"=>"del", "label"=>"del", "btn"=>"btn-default", "clearall"=>false,"col"=>"col-xs-6 col-xs-6","type"=>"button", "onclick"=>$schema."_del_line();" ],
  ]; 
  
  $html.=form($schemas->{$schema},$action,[],$schema."_edit_form"); 
    
  $html.="<div id='".$schema."_list_div' ></div>";
  
  $html.="
  <div class='row'>
    <div class='form-group col-xs-6 col-md-6'>
      <label class='control-label'>&nbsp;</label>
      <button class='form-control btn btn-primary' type='button' onclick='".$schema."_bulk_save()'>".lbl("Save")."</button>
    </div>
    <div class='form-group col-xs-6 col-md-6'>
      <label class='control-label'>&nbsp;</label>
      <button class='form-control btn btn-default' type='button' onclick=''>".lbl("Clear")."</button>
    </div>
  </div>
  ";
  
  
  $html.="<script>";
  
  // std nano var
  $html.="
  schemas.$schema.data=[];
  schemas.$schema.ix=-1;
  default_query=[];
  
  
  //nano.load('$schema',default_query,".$schema."_display);
  
  default_values={};
  
  ".$schema."_new_mode(default_values) 
  
  ";
  
  // load data on page open
  
  $html.=quickListFunctions($schema);
  
  
  
  $html.="
  function ".$schema."_add_line() 
  {
    var form=document.".$schema."_edit_form;
    
    var valide=false;
    if(nano.form_validate(form))
    {
      schemas.$schema.data.push(nano.form_save(form));
      
      ".$schema."_display(schemas.$schema.data);
      ".$schema."_new_mode(default_values) 
    }
    else
    {
      return false;
    }
  }
  ";
  
  $html.="
  function ".$schema."_del_line()
  {
    var i;
    var tmp_array=[];
    var checkboxes=document.getElementsByName('".$schema."_checkbox');
    
    if(!schemas.$schema.data) return false;
    
    if(schemas.$schema.data.length==0) return false;
    
    if(window.confirm(nano.lbl('Do you really want to delete')))
    {
      for(i in checkboxes)
      {
        if(checkboxes[i].checked==true && checkboxes[i].type!='checkbox' )
        {
          tmp_array.push(schemas.$schema.data[checkboxes[i].value]);
        }
      }
      
      schemas.$schema.data=tmp_array
      ".$schema."_display(schemas.$schema.data);
    }
  }
  ";
  
  $html.="
  function ".$schema."_bulk_save()
  {
      callback=function()
      {
        location.reload();
      };
  
      nano.save('$schema',schemas.$schema.data,callback);
      schemas.$schema.data=[];
  }
  ";
  
  
  
  
  $html.="</script>";
  
  return $html;
  
}


?>
