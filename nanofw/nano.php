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
  global $centers;
  global $center;
 
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

  // ***** get language or use the french
  $center="";
  if (isset($_COOKIE["center"])) $center=$_COOKIE["center"];
  
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
        "admin"=>true,
        "col"=>"col-xs-4", 
        "clearall"=>false, 
        "label"=>lbl("user"), 
        "display"=>["list","form","find","div"] 
      ],
      [
        "name"=>"name", 
        "type"=>"string",
        "regex"=>"^.*$", 
        "optional"=>true, 
        "col"=>"col-xs-4", 
        "clearall"=>false, 
        "label"=>lbl("name"), 
        "display"=>["list","form","find","div"] 
      ],
      [
        "name"=>"adresse", 
        "type"=>"string",
        "regex"=>"^.*$", 
        "optional"=>true, 
        "col"=>"col-xs-4", 
        "clearall"=>false, 
        "label"=>lbl("adresse"), 
        "display"=>["list","form","find","div"] 
      ],
      [
        "name"=>"zip", 
        "type"=>"string",
        "regex"=>"^[0-9]*$", 
        "optional"=>true, 
        "col"=>"col-xs-4", 
        "clearall"=>false, 
        "label"=>lbl("zip"), 
        "display"=>["list","form","find","div"] 
      ],
      [
        "name"=>"city", 
        "type"=>"string",
        "regex"=>"^.*$", 
        "optional"=>true, 
        "col"=>"col-xs-4", 
        "clearall"=>false, 
        "label"=>lbl("city"), 
        "display"=>["list","form","find","div"] 
      ],
      [
        "name"=>"phone", 
        "type"=>"string",
        "regex"=>"^[+0-9]*$", 
        "optional"=>true, 
        "col"=>"col-xs-4", 
        "clearall"=>false, 
        "label"=>lbl("phone"), 
        "display"=>["list","form","find","div"] 
      ],
      [
        "name"=>"fax", 
        "type"=>"string",
        "regex"=>"^[+0-9]*$", 
        "optional"=>true, 
        "col"=>"col-xs-4", 
        "clearall"=>false, 
        "label"=>lbl("fax"), 
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
        "admin"=>true,
        "display"=>["list","form","find","div"]
      ],
      [
        "name"=>"is_admin",
        "type"=>"boolean",
        "regex"=>"^[0-1]$",
        "optional"=>false,
        "col"=>"col-xs-4",
        "clearall"=>false,
        "admin"=>true,
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
        "admin"=>true,
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
        "admin"=>true,
        "label"=>lbl("Groups"),
        "display"=>["list","form","find","div"]
      ],
      [
        "name"=>"centers",
        "type"=>"multiplekey",
        "schema"=>"_center",
        "regex"=>"^.*$",
        "optional"=>true,
        "col"=>"col-xs-12",
        "clearall"=>true,
        "admin"=>true,
        "label"=>lbl("centers"),
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
  
    $schemas->_center=
  [
    "name"=>"_center",
    "database"=>"nano",
    "table"=>"_center",
    "structure"=>
    [
      [
        "name"=>"name", 
        "type"=>"string",
        "regex"=>"^.*$", 
        "optional"=>false, 
        "col"=>"col-xs-4", 
        "clearall"=>false, 
        "label"=>lbl("center"), 
        "display"=>["find","list", "form", "div"] 
      ]
    ]
  ];
  
  // add the users library 
  if(file_exists (__DIR__."/../".$project.".php")) include(__DIR__."/../".$project.".php");
  // add the user defined tables
  if(file_exists (__DIR__."/../nano_schemas.php")) include(__DIR__."/../nano_schemas.php");
  

  // Connect to the datasource
  $users=new data(true);
  $users->connect($schemas->_users);
  
  // Create the tables
  $users->create($schemas->_users);
  $users->create($schemas->_group);
  $users->create($schemas->_center);

  // check if the user is logged in
  //$email="";
  $username="";
  $password="";
  
  //if (isset($_COOKIE["email"])) $email=$_COOKIE["email"];
  if (isset($_COOKIE["username"])) $username=$_COOKIE["username"];
  if (isset($_COOKIE["password"])) $password=$_COOKIE["password"];
  
  $query=["user"=>$username, "password"=>$password];
  $result=$users->select($query,$schemas->_users);
  
  //print_r($result);
  
  if(count($result)==0)
  {
    $query=[];
    $result=$users->select($query,$schemas->_users);
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
    
    // add centers
    $query=["_id"=>["\$in"=>$user["centers"]]];

    $load=new data(true);
    $load->connect($schemas->_center);
    $centers=$load->select($query,$schemas->_center);
    
    //print_r($centers);
    
    if($center=="" && count($center)>0 )
    {
        $center=$centers[0]["name"];
        setcookie("center", $center, time() + (365 * 24 * 60 * 60),"/" );
    }
    
  }

}

// simple login form
function loginForm()
{
  
  /*
  <div class='form-group'>
        <label for='email'>".lbl("Email")."</label>
        <input id='email' type='email' name='email' class='form-control'  placeholder='".lbl("Email")."'>
      </div>
      
  */
  
  $html="<!-- Login form -->
  <div class='container' style='margin-top:20px'>
    <form method='post' action='nanofw/nano_login.php' class='col-xs-4 col-xs-offset-4' >
      <h1>".lbl("Sign in")."</h1>
      <h1>&nbsp;</h1>
      <div class='form-group'>
        <label for='username'>".lbl("username")."</label>
        <input id='username' type='text' name='username' class='form-control'  placeholder='".lbl("username")."'>
      </div>
      <div class='form-group'>
        <label for='password'>".lbl("password")."</label>
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
  
  protected function isHtmlType($type)
  {
    if ( $type=="section_start" or $type=="section_end" or $type=="html" or $type=="button" or $type=="info" ) return true;
    return false;
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
        
        if ( $this->isHtmlType($field["type"])==false )
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
                        //$alters[]=$query_action." enum('".(implode("','",$field["enum"]))."')";
                        $alters[]=$query_action." varchar(255)";
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
  function select($query,$schema)
  {
    $this->query=$query;
    $table=$schema["table"];
    $columns=[];
    $joins=[];
    $where_rights=[];
    $auto_columns=false;
    
    // Find if columns have been spécified
    if(!isset($this->query["\$columns"]))
    {
      $this->query["\$columns"]=[];
      $auto_columns=true;
    }
    
    //check if right where set and setup default
    if(!isset($schema["sgroup"])) $schema["sgroup"]="W";
    if(!isset($schema["slevel"])) $schema["slevel"]="W";
    if(!isset($schema["sother"])) $schema["sother"]="W";
    
    //print_r($schema);
    
    if (!$this->admin) if(substr($table,0,1)=="_") die(error("error trying to read table ".$schema["table"]." : tables starting with _ are for internal use only!",-11));

    if(isset($schema["default_query"])) if(is_array($schema["default_query"])) $this->query=array_merge($this->query,$schema["default_query"]);
    
    // get the columns and joins
    $reply=$this->columns($schema,$table,$auto_columns);
    $columns=array_merge($columns,$reply["columns"]);
    $joins=array_merge($joins,$reply["joins"]);
    
    $where_rights[]=" `$table`.`_user_id`='".$this->user["_id"]."' ";
    if($schema["sgroup"]!="N") $where_rights[]=" `$table`.`_group_id` in (".implode(",",$this->user["groups"]).") ";
    if($schema["slevel"]!="N") $where_rights[]=" `$table`.`_level`<'".$this->user["level"]."' ";
    if($schema["sother"]!="N") $where_rights[]=" true ";
  
    // add some restriction to the query depending of the right the user have on this table
    $this->query["_status"]=['$lt'=>9 ];
    
    //print_r($this->query);
    
    // build the query
    $sqlquery="select ".implode(",",$columns)." from `".$schema["table"]."` ".implode(" ",$joins)." where (".implode(" or ",$where_rights).") and ".$this->where($this->query,$schema).$this->groupby($schema).$this->orderby($schema).$this->limit($schema).";";
    
    //if($table!="_users" and $table!="_center" and $table!="_groups" and $table=="f_budget") echo $sqlquery."\n";
    
    try
    {
      $stmt = $this->conn->prepare($sqlquery);
      $stmt->execute();
      $stmt->setFetchMode(\PDO::FETCH_ASSOC); 
    }
    catch(Exception $e) 
    {
      die(error($e->getMessage(),-3));
    }
    
    $dataset=[];
    
    // build the dataset
    foreach($stmt->fetchAll() as $line) $dataset[]=$this->dataset($schema,$line,$table,$auto_columns);
    
    // return the dataset
    return $dataset;
   
  }
  
  protected function columns($schema,$prefix,$auto_columns)
  {
    $table=$schema["table"];
    $columns=[];
    $joins=[];
    $if_rights=[];
    $where_rights=[];
    $or=[];
    
    // allways add the internal fields
    if($auto_columns)
    {
      $columns[]="`$table`.`_id` as `".$prefix."._id`";
      $columns[]="`$table`.`_color` as `".$prefix."._color`";
    }
    
    //check if right where set and setup default
    if(!isset($schema["sgroup"])) $schema["sgroup"]="W";
    if(!isset($schema["slevel"])) $schema["slevel"]="W";
    if(!isset($schema["sother"])) $schema["sother"]="W";
    
    $if_rights[]=" `$table`.`_user_id`='".$this->user["_id"]."'";
    if($schema["sgroup"]=="W") $if_rights[]="`$table`.`_group_id` in (".implode(",",$this->user["groups"]).")"; 
    if($schema["slevel"]=="W") $if_rights[]="`$table`.`_level`<'".$this->user["level"]."'"; 
    if($schema["sother"]=="W") $if_rights[]=" true "; 
    
    if($auto_columns) $columns[]="if( ".implode(" or ",$if_rights)." ,`$table`.`_status`,'7') as `".$prefix."._status`";
    
    foreach($schema["structure"] as $field)
    {
        
        // it this field is of HTML type then only 
        if ($this->isHtmlType($field["type"])) continue;
    
        // if no columns have been specified we take all
        if($auto_columns) $this->query["\$columns"][$prefix.".".$field["name"]]=["\$as"=>$prefix.".".$field["name"]];
        
        // only add columns specified in the columns array
        if(isset($this->query["\$columns"][$prefix.".".$field["name"]]))
        {    
            // if the user forgot to set a name for his column we set it for him
            if(!isset($this->query["\$columns"][$prefix.".".$field["name"]]["\$as"])) $this->query["\$columns"][$prefix.".".$field["name"]]["\$as"]=$prefix.".".$field["name"];
            
            switch($field["type"])
            {
            case "relation":
                break;
            case "key";
                $where_rights=[];
                $subschema=$this->schemas->{$field["schema"]};
                $subtable=$subschema["table"];
                
                $this->create($subschema);
                $reply=$this->columns($subschema,$prefix.".".$field["schema"],$auto_columns);
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
                // newer send password field value
                $fld="'!!NOCHANGE!!'";
                foreach($this->query["\$columns"][$prefix.".".$field["name"]] as $function=>$param) $fld=$this->sqlFunction($fld,$function,$param);
                $columns[]=$fld;
                break;
            case "translate";
                $fld="`$table`.`_".$this->lang."_".$field["name"]."`";
                foreach($this->query["\$columns"][$prefix.".".$field["name"]] as $function=>$param) $fld=$this->sqlFunction($fld,$function,$param);
                $columns[]=$fld;
                // always add all others languages
                foreach($this->languages as $lkey=>$lvalue) $columns[]="`$table`.`_".$lkey."_".$field["name"]."` as `".$prefix."._".$lkey."_".$field["name"]."` ";
                break;
            case "section_start":
            case "section_end":
            case "html":
            case "info":
            case "button":
                break;
            default:
                $fld="`$table`.`".$field["name"]."`";
                foreach($this->query["\$columns"][$prefix.".".$field["name"]] as $function=>$param) $fld=$this->sqlFunction($fld,$function,$param);
                $columns[]=$fld;
                break;
            }
        }
    
    }
    
    return [ "columns"=>$columns, "joins"=>$joins];
  }
  

  // this function is used in the select function to create the where part of the query 
  protected function where($query,$schema,$op="and")
  {
    $table=$schema["table"];
    $where_columns=[];
    //$or_columns=[];
    $f=[];
    
    foreach($query as $field=>$value)
    {
 
      foreach($schema["structure"] as $s) if($s["name"]==$field) $f=$s;
      
        if(strtoupper($f["type"])=="TRANSLATE") $field="_".$this->lang."_".$field;
	  
	//if($s["type"]=="translate" and $s["name"]==$field) $field="_".$this->lang."_".$field;
	//if($s["type"]=="relation" and $s["name"]==$field) $skip=true;
    //if($skip) continue;
      
      $fld="`$table`.`$field`";
      
	
		  
		  switch (true)
		  {
			case $field=='$or':
			  $where_columns[]=" (".($this->where($value,$schema,"or")).") ";
			  break;
			case $field=='$and':
			  $where_columns[]=" (".($this->where($value,$schema,"and")).") ";
			  break;
			case $field=='$groupby':
			case $field=='$orderby':
			case $field=='$columns':
			case $field=='$limit':
			case strtoupper($f["type"])=='RELATION':
			  break;
			default:
			  switch(true)
			  {
				case is_array($value):
				  foreach($value as $function=>$param) $fld=$this->sqlFunction($fld,$function,$param);
				  $where_columns[]=$fld;
				  break;
                                case $value==0:
                                  $where_columns[]=$fld."=0 ";
                                  break;
				case !$value:
				  $where_columns[]=$fld." is null ";
				  break;
				default:
				  $where_columns[]="$fld='$value'";
				  break;
			  }
		  }
		
    }
	
    return  implode(" $op ",$where_columns);
    
  }
  
  // group by 
  protected function orderby($schema)
  {
    $table=$schema["table"];
    $orderby_columns=[];
    
    // if no orderby end here
    if(!isset($this->query["\$orderby"])) return "";
  
    foreach($this->query["\$orderby"] as $field=>$value)
    {
      $fld="`$table`.`$field`";
      foreach($schema["structure"] as $s)
      {
        if($field==$s["name"])
        {
          if($s["type"]=="translate" and $s["name"]==$field) $field="_".$this->lang."_".$field;
          $fld="`$table`.`$field`";
          foreach($value as $function=>$param) $fld=$this->sqlFunction($fld,$function,$param);
          $orderby_columns[]=$fld;
        }
      }
    }
    return  " order by ".implode(",",$orderby_columns);
  }
  
  
  protected function limit($schema)
  {
    $table=$schema["table"];
 
    // if no orderby end here
    if(!isset($this->query["\$limit"])) return "";
  
    if(!is_numeric($this->query["\$limit"])) return "";
    
    return  " limit ".$this->query["\$limit"];
  }

  // group by 
  protected function groupby($schema)
  {
    $table=$schema["table"];
    $groupby_columns=[];

    // if no groupby end here
    if(!isset($this->query["\$groupby"])) return "";
  
    foreach($this->query["\$groupby"] as $field=>$value)
    {
      // this allow the user to use an array []
      if(is_numeric($field))
      {
        $field=$value;
        $value=false;
      }
      
      $fld="`$table`.`$field`";
      
      foreach($schema["structure"] as $s)
      {
        if($field==$s["name"])
        {
          if($s["type"]=="translate" and $s["name"]==$field) $field="_".$this->lang."_".$field;
          
          $fld="`$table`.`$field`";
          
          switch(true)
          {
            case is_array($value):
              foreach($value as $function=>$param) $fld=$this->sqlFunction($fld,$function,$param);
              $groupby_columns[]=$fld;
              break;
            default:
              $groupby_columns[]=$fld;
              break;
          }
        }
      }
    }

    return  " group by ".implode(",",$groupby_columns);
  }
  
  protected function sqlFunction($field,$function,$param=false)
  {
    switch(true)
    {
      case $function=="\$way" and $param=="desc":
        return " $field desc ";
        break;
      case $function=="\$way" and $param=="asce":
        return " $field ";
        break;
      case $function=="\$year":
        return " date_format($field,'%Y') ";
        break;
      case $function=="\$month":
        return " date_format($field,'%m') ";
        break;
      case $function=="\$monthname":
        return " date_format($field,'%M') ";
        break;
      case $function=="\$week":
        return " date_format($field,'%u') ";
        break;
      case $function=="\$weekday":
        return " date_format($field,'%w') ";
        break;
      case $function=="\$dateformat":
        return " date_format($field,'$param') ";
        break;
      case $function=="\$sum":
        return " sum($field) ";
        break;
      case $function=="\$count":
        return " count($field) ";
        break;
      case $function=="\$avg":
        return " avg($field) ";
        break;
      case $function=="\$aggregate":
        return " $param($field) ";
        break;
      case $function=="\$case" and $param=="up":
        return " ucase($field) "; 
        break;
      case $function=="\$case" and $param=="down":
        return " downcase($field) "; 
        break;
      case $function=="\$eq":
        return "$field='$param'";
        break;
      case $function=="\$gt":
        return "$field>$param";
        break;
      case $function=="\$lt":
        return "$field<$param";
        break;
      case $function=="\$gte":
        return "$field>=$param";
        break;
      case $function=="\$lte":
        return "$field<=$param";
        break;
      case $function=="\$ne":
        return "$field!='$param'";
        break;
      case $function=="\$in":
        return "$field in ('".implode("','",$param)."')";
        break;
      case $function=="\$nin":
        return "$field not in ('".implode("','",$param)."')";
        break;
      case $function=="\$lk":
        return "$field like '%$param%'";
        break;
      case $function=="\$plus":
        return "($field+$param)";
        break;
      case $function=="\$minus":
        return "($field-$param)";
        break;
      case $function=="\$mutiply":
        return "($field*$param)";
        break;
      case $function=="\$as":
        return "$field as `$param`";
        break;
      default:
        return " $field ";
        break;
      }
  }
  
  protected function dataset($schema,$line,$prefix,$auto_columns,$relation=true)
  {
    $return="";
    
    foreach($schema["structure"] as $field)
    {
        
        if ($this->isHtmlType($field["type"])) continue;
      
    
        if(isset($this->query["\$columns"][$prefix.".".$field["name"]]))
        {
          $as=$this->query["\$columns"][$prefix.".".$field["name"]]["\$as"];
          
          switch($field["type"])
          {
            case "relation":
              if($relation)
              {
		if(!isset($field["key"])) die(error("add a key to your relation",-1));
		if(!isset($field["schema"])) die(error("add a schema to your relation",-1));
              
                $rel=new data(false);
                $rel->connect($this->schemas->{$field["schema"]});
                $rel->create($this->schemas->{$field["schema"]});

                $relquery=[];
                $relquery[$field["key"]]=$line[$prefix."._id"];
                $return[$field["name"]]=$rel->select($relquery,$this->schemas->{$field["schema"]});
              }
              break;
            case "multiplekey":  
            case "json":
              $return[$field["name"]]=json_decode(stripslashes(html_entity_decode(preg_replace('/u([\da-fA-F]{4})/', '&#x\1;', $line[$as]))),true);
              break;
            case "key":
              if($relation)
              {
                $return[$field["name"]]=$this->dataset($this->schemas->{$field["schema"]},$line,$prefix.".".$field["schema"],$auto_columns,false);
              }
              break;
            case "translate":
              if($line[$as])
              {
                $return[$field["name"]]=$line[$as];
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
            case "info":
            case "button":
              break;
            default:
              $return[$field["name"]]=$line[$as];
              break;
          }
        }
    }
      
    if($auto_columns) $return["_id"]=$line[$prefix."._id"];
    if($auto_columns) $return["_color"]=$line[$prefix."._color"];
    if($auto_columns) $return["_status"]=$line[$prefix."._status"];
  
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
        case "info":
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
      $prepare_values=[];
      $_id=0;
      
      // if dataset have a parent relation add the id of the parent in the corresponding field
      //if(count($parent)>0) $line->{$parent->{"field"}}=$parent->{"_id"};
        
      // parse the structure
      foreach($structure as $field)
      {
        $match=false;
        
        // Skip the upsert for HTML only fields
        if ($this->isHtmlType($field["type"])) $match=true;

        // Parse each column in the line
        foreach($line as $column=>$value)
        {
          // look for id to choose between insert or update
          if ($column=="_id") $_id=$value;
          if ($column=="_status") $_status=$value;
          
          // Column in the object must be referenced in the structure
	  if(!$match)
	  {
            if($column==$field["name"])
            {
                $match=true;
                
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
                    $prepare_values[]=1;
                    }
                    else
                    {
                    $insert_values[]="0";
                    $update_values[]="`".$column."`=0";
                    $prepare_values[]=0;
                    }
                    break;
                case "multiplekey":
                case "json":
                    // we store all the mess as a sting
                    $insert_values[]="'".addslashes(json_encode($value))."'";
                    $update_values[]="`".$column."`='".addslashes(json_encode($value))."'";
                    $prepare_values[]=json_encode($value);
                    break; 
                case "select":
                case "list":
                    if(!isset($field["enum"])) die(error($field["name"]." need and enum array!",-5));
                    if(!is_array($field["enum"])) die(error($field["name"]." enum element is not an array!",-5)); 
                    if(in_array($value,$field["enum"]))
                    {
                        $insert_values[]="'".addslashes($value)."'";
                        $update_values[]="`".$column."`='".addslashes($value)."'";
                        $prepare_values[]=$value;
                    }
                    else
                    {
                        if($_status<9) die(error("$value in ".$field["name"]." not in the list",-5));
                    }
                    break;
                case "key":
                    if(isset($value->_id))
                    {
                        $insert_values[]=addslashes($value->_id);
                        $update_values[]="`".$column."`=".addslashes($value->_id);
                        $prepare_values[]=$value->_id;
                    }
                    else
                    {
                        $insert_values[]="null";
                    }
                    break;
                case "number":
                case "integer":
                case "float":
                case "double": 
                    if(preg_match("#".$field["regex"]."#",$value) or $status=9) //allow not matching the regex if we delete the line
                    {
                        // replace empty string by null for numbers
                        if ($value=="") $value="null";
                        $insert_values[]=addslashes($value);
                        if ($value!="!!NOCHANGE!!" or is_numeric($value) ) $update_values[]="`".$column."`=".addslashes($value);
                        ///wolf here
                        $prepare_values[]=$value;
                    }
                    else
                    {
                        die(error("$value in ".$field["name"]." does not match ".$field["regex"],-5));
                    }
                    break;
                    break;
                case "translate":
                    $column="_".$this->lang."_".$column;
                default:
                    if(preg_match("#".$field["regex"]."#",$value) or $status=9) //allow not matching the regex if we delete the line
                    {
                        $insert_values[]="'".addslashes($value)."'";
                        if ($value!="!!NOCHANGE!!" or is_numeric($value) ) $update_values[]="`".$column."`='".addslashes($value)."'";
                        ///wolf here
                        $prepare_values[]=$value;
                    }
                    else
                    {
                        die(error("$value in ".$field["name"]." does not match ".$field["regex"],-5));
                    }
                    break;
                }
            }
	  }
        }
        
        // if we dont find the value then add empty or trigger an error if not optional
        
        if ($match==false)
        {
          if($field["optional"]==false)
          {
	    switch(true)
	    {
	    case $_id==0: //on insert values can not be missing
	      die(error("No value for ".$field["name"]." was found",-6));
	      break;
	    case $_id!=0: // on update we can agree that some value may be missing
	      break;
	    }
          }
          else
          {
            $insert_values[]="null";
            $prepare_values[]=NULL;
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
      
      //$prepare_values[]=$_SERVER["REMOTE_ADDR"];
      //$prepare_values[]=date("Y-m-d H:i:s");
      //$prepare_values[]="";
      
      
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
            
            if($this->isHtmlType($field["type"])) continue;
        
          if($field["name"]==$column)
          {
            if($field["type"]=="relation")
            {
              
              $relation=(object) array();
              $relation->field=$field["key"];
              $relation->_id=$line->_id;
              
              /*
              $this->create($this->schemas->{$field["schema"]});
              $child=$this->upsert($this->schemas->{$field["schema"]},$value,$relation);
              
              $line->{$column}=$child;
              */
              
              $child=new data(false);
              $child->connect($this->schemas->{$field["schema"]});
              $child->create($this->schemas->{$field["schema"]});
              $line->{$column}=$child->upsert($this->schemas->{$field["schema"]},$value,$relation);
              
              
              
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

function selectOptions($optionsArray,$defaultValue,$namePrefix="",$hasEmptyOption=false,$optionFilter="")
{

    $html="";
    
    if($hasEmptyOption==true) $html.="<option value=''>".lbl("choose a value")."</option>";

    foreach($optionsArray as $option)
    {
        if(isset($option["filter"])) if(strtoupper($option["filter"])!=strtoupper($optionFilter)) continue;
    
        if(isset($option["value"]))
        {
            $selected="";
            if(strtoupper($option["value"])==strtoupper($defaultValue)) $selected="selected";
        
            if(isset($option["name"]))  
            {
                $html.="<option value='$option[value]' $selected>".$name=$option["name"]."</option>";
            }
            else
            {
                $html.="<option value='$option[value]' $selected>".lbl($namePrefix.$option["value"])."</option>";
        
            }
        }
    }

    return $html;

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
$center=$GLOBALS['center'];


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

/*
<!-- apple -->
<meta name='apple-mobile-web-app-capable' content='yes'>
<meta name='apple-mobile-web-app-status-bar-style' content='black'>
<link rel='apple-touch-icon' href='flat_berdoz.png'>
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

<!-- TinyMCE -->
<script src='//cdn.tinymce.com/4/tinymce.min.js'></script>


<!-- jquery plugins -->
<script src='./jquery/jquery.tablesorter.min.js'></script>

<!-- google graph -->
<script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>


<!-- bootstrap 3 -->
<link rel='stylesheet' href='./bootstrap/css/bootstrap.min.css' media='all'>

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
var center='$center';
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

  // fixe problem in boostrap multiple modal when closing the top one
  $html.="<script>

    nano.setupMultiModal();
    
    </script>";
 
  
  
  $html.="</body></html>";
  return $html;
}

// Html Navebar provide a home button a ajax working indication and language switch 
function navbar($param=[])
{
$languages=$GLOBALS['languages'];
$user=$GLOBALS['user'];
$lang=$GLOBALS['lang'];
$center=$GLOBALS['center'];
$centers=$GLOBALS['centers'];

$lang_html="";
foreach($languages as $key=>$value)
{
    if($lang!=$key) 
    { 
        $lang_html.="<li class='active'><a href='nanofw/nano_lang.php?lang=$key'>".$value["name"]."</a></li>"; 
    }
    else 
    {  
        $lang_html.="<li><a>".$value["name"]."</a></li>";
    }
}

$center_list="";
if (count($centers)>0)
{
    $center_list="<li class='dropdown'><a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>$center<span class='caret'></span></a><ul class='dropdown-menu'>";
    foreach($centers as $c) $center_list.="<li><a href='nanofw/nano_center.php?center=".$c["name"]."'>".$c["name"]."</a></li>";  
    $center_list.="</ul></li>";
}

$index="index.php";
if(isset($param["index"])) $index=$param["index"];

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
      <li class='active'><a href='$index'>Home</a></li>
      $center_list
    </ul>
    <ul class='nav navbar-nav'>
      <a class='navbar-brand' href='#' style='width:50px'>
        <img id='nano_wait'  style='display:none; max-width:100px; margin-left:7px; margin-top: -7px;' src='nanofw/nano_wait.gif'>
      </a>
    </ul>
    <ul class='nav navbar-nav navbar-right'>
      $lang_html
      <li>&nbsp;</li>
      <li><a href='nano_user.php'>".$user["user"]."</a></li>";
      if ($user["is_admin"]) $html.= "<li>&nbsp;</li><li><a href='nano_admin.php'>Admin</a></li>"; 
   $html.= "<li class='active'><a href='nanofw/nano_logout.php'>Logout</a></li>
      </ul>
    
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
    // todo : supprimer tout a propos des valeurs par defaut je ne garde leur definition que dans la structure!

  $schemas=$GLOBALS["schemas"];
  $user=$GLOBALS["user"];
  $html="";
  // col is the bootstrap column
  // action is an array of object for button [{"label":"edit","function":"client_edit"}]
  // structure describe the form components
  
  $disabled="disabled";
  $html.="<div class='row'>"; // class='row'>";
  $html.="<form nano_mode='lock'  nano_schema='".$schema["name"]."' name='$form' onsubmit='return nano.submit();' >";
  $nbr=0;
  $tabs="";
 
  $section=0;
  
  $structure=$schema["structure"];
 
  //print_r($schema);

  if(!is_array($structure)) die(error("Your structure is not an array",-1));
  
  foreach($structure as $s)
  {
    $nbr++;
    $show=true;
    
    //print_r($user);
    
    if(isset($s["admin"])) if($s["admin"]==true and $user["is_admin"]==false) $show=false;
    
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
    
    if(isset($s["clearall"])) if($s["clearall"]==true) $html.="<div style='clear:both'></div>";
    
    $default_value="";
    
    // get default value from structure
    //if(isset($s["value"])) $default_value=$s["value"]; 
    
    // get default value from parameters
    /*$hidden="";
    foreach($default_values as $dkey=>$dvalue)
    { 
      if($dkey==$s["name"])
      {
        $default_value=$dvalue;
        $hidden="hidden";
      }
    }*/
      
    if(in_array("form",$display)) 
    {
      if($show)
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
	  case "info":
	    $html.="<div $id class='form-group $col'>"; 
	    $html.="<label class='control-label' >$label</label>";
	    if($onclick!="") $html.="<div class='input-group'>"; // form-control-static //form-control
	    $html.="<input nano_type='$type' class='form-control intra-static' type='text' nano_default='$default_value' name='$name' disabled>";
	    //if($onclick!="") $html.="<span class='input-group-btn'><button type='button' class='btn btn-primary' onclick='".$s["onclick"]."($form.$name)'>".lbl($s["onclick"])."</button></span></div>";
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
	    $html.="<input $id nano_type='$type' type='text' nano_default='$default_value' name='$name' nano_schema='".$s["schema"]."' nano_value='' hidden>";
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
	    foreach($keylist->select([],$schemas->{$s["schema"]}) as $list) $html.="<option value='".$list["_id"]."'>".implode_match(' ',$list,"(?!^\d+$)^.+$")."</option>";
	    $html.="</select>";
	    $html.="</div>";
	    break;
	}
      }
    }
    else
    {
      $html.="<input $id nano_type='$type' type='text' nano_default='$default_value' nano_schema='".$s["schema"]."' name='$name' hidden>";
    }
  }
  
  //  // ^[^0-9].*$
  
  for($i=0;$i<$section;$i++) $html.="<!-- section end --></div></div>"; //if user forget to close section we do it for him
  
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
  
  
  $html.="</form>"; //form 
  $html.="</div>"; //row

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
  
  $autoload=false;
  if (isset($param["autoload"])) $autoload=$param["autoload"]; 
  
  // gip deactivate default values
  /*$default_values=[];  
  if (isset($param["default_values"]))
  {
    unset($default_values);
    $default_values=$param["default_values"]; 
  }*/
  
  
  $action=
  [
    ["name"=>"prev", "label"=>"<", "col"=>"col-xs-3", "type"=>"button", "clearall"=>true, "onclick"=>"nano.form_prev(this.form);" ],
    ["name"=>"next", "label"=>">",  "col"=>"col-xs-3","type"=>"button", "onclick"=>"nano.form_next(this.form);" ],
    ["name"=>"ok", "label"=>"OK", "col"=>"col-xs-3 col-xs-offset-3","type"=>"button", "onclick"=>$schema."_ok();" ],
  ]; 
  
  //$form=form($schemas->{$schema},$action,$default_values,$schema."_edit_form"); 
  $form=form($schemas->{$schema},$action,[],$schema."_edit_form"); 
  $html.=modal($schema."_edit_popup",lbl($schema."_edit_popup"),$form,""); //$schema."_save(document.".$schema."_edit_form);"

  // prepare the find popup  
  $html.=modal($schema."_find_popup",lbl($schema."_find_popup"),"<div id='".$schema."_find_div'></div>",$schema."_find_validate(document.".$schema."_find_form);");
  
  if($show_buttons)
  {
    $html.="<!-- Std nano actions -->
    <h4>".lbl($schema)."</h4>
    <div class='row'>
      <div class='form-group col-xs-6 col-md-2  col-md-offset-1'>
        <label class='control-label'>&nbsp;</label>
        <button class='form-control btn btn-primary' type='button' onclick='".$schema."_new_mode({});'>".lbl("new")."</button>
      </div>
      <div class='form-group col-xs-6 col-md-2'>
        <label class='control-label'>&nbsp;</label>
        <button class='form-control btn btn-default' type='button' onclick='".$schema."_sub_selection()'>".lbl("sub selection")."</button>
      </div>
      <div class='form-group col-xs-4 col-md-2'>
        <label class='control-label'>&nbsp;</label>
        <button class='form-control btn btn-default' type='button' onclick='".$schema."_mass_delete()'>".lbl("delete")."</button>
      </div>
      <div class='form-group col-xs-4 col-md-2'>
        <label class='control-label'>&nbsp;</label>
        <button class='form-control btn btn-default' type='button' onclick='".$schema."_find_mode()'>".lbl("find")."</button>
      </div>
      <div class='form-group col-xs-4 col-md-2'>
        <label class='control-label'>&nbsp;</label>
        <button class='form-control btn btn-default' type='button' onclick='".$schema."_find_all()'>".lbl("show_all")."</button>
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
  
  $html.=quickListFunctions($schema,$param);
  
  $html.="</script>";
  
  return $html;
  
}

function quickListFunctions($schema,$param=[])
{
  $html="";

  //print_r($param);

  $listclick=$schema."_edit_mode";
  if(isset($param["listclick"])) $listclick=$param["listclick"];

  //echo $listclick;
  
  // std nano user callback
  $html.="var ".$schema."_save_callback=function() {};";
  $html.="var ".$schema."_display_callback=function() {};";
  
  // reserved
  //$html.="var ".$schema."_relation_child_callback=function() {};";
  
  // Std nano open in edit mode
  $html.="
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
    //nano.form_prepare(form,'new',default_values);
    nano.form_prepare(form,'new',{});
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
      'onclick':'$listclick',
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
    if(!schemas.$schema.database) return false;
    if( schemas.$schema.database=='') return false;

   
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
      
      nano.save('$schema',tmp_array,".$schema."_save_callback); 
      ".$schema."_display(schemas.$schema.data);
    }
  }
  ";
  
   // std nano delete function
  $html.="
  function ".$schema."_delete()
  {
    var tmp_array=[];

    if(!schemas.$schema.data) return false;
    if(schemas.$schema.data.length==0) return false;
    if(!schemas.$schema.database) return false;
    if( schemas.$schema.database=='') return false;

    
    if(window.confirm(nano.lbl('Do you really want to delete')))
    {
      schemas.$schema.data[schemas.$schema.ix]._status=9;
      tmp_array.push(schemas.$schema.data[schemas.$schema.ix]);
      schemas.$schema.data.splice(schemas.$schema.ix,1)
      
      
      nano.save('$schema',tmp_array,".$schema."_save_callback); 
      
      
      ".$schema."_display(schemas.$schema.data);
    }
  }
  ";
  
 

  //std nano find 
  $html.="
  function ".$schema."_find(form)
  {
    var query=nano.form_query(form);
    
    if(!schemas.$schema.database) return false;
    if( schemas.$schema.database=='') return false;
    
    nano.load('$schema',query,".$schema."_display)
    return true;
  }
  ";

  //std nano find all 
  $html.="
  function ".$schema."_find_all()
  {
    var query=[];
    nano.load('$schema',query,".$schema."_display)
    return true;
  }
  ";
  
   // std nano display
  $html.="
  function ".$schema."_display(reply)
  {
    schemas.$schema.data=reply;
    if(!schemas.$schema.ix) if(schemas.$schema.data.length>0) schemas.$schema.ix=0;
    if(schemas.$schema.ix==-1) if(schemas.$schema.data.length>0) schemas.$schema.ix=0;
    if(schemas.$schema.ix>schemas.$schema.data.length) schemas.$schema.ix=0;
    
    
    
    var param=
    {
      'onclick':'$listclick',
      'multiselect':true
    }
    
    $('#".$schema."_list_div').html(nano.table(schemas.$schema,schemas.$schema.data,param));
    //$('#".$schema."_list_div').html(nano.div_list(schemas.$schema,schemas.$schema.data,param));
    
    if(typeof ".$schema."_relation_parent_callback == 'function') ".$schema."_relation_parent_callback(); 
    
    ".$schema."_display_callback();
    
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
    
    if(!schemas.$schema.database) return false;
    if( schemas.$schema.database=='') return false;

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
      
      if(typeof ".$schema."_relation_child_callback == 'function') ".$schema."_relation_child_callback();
      
      ".$schema."_display(schemas.$schema.data);
      
      ".$schema."_save_callback();
      
      
      
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
  
  $autoload=false;
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
    
    <div style='clear:both'>
      <div id='".$schema."_buttons_row' class='row'>
	<div class='form-group col-xs-4 col-md-2'>
	  <label class='control-label'>&nbsp;</label>
	  <button class='form-control btn btn-primary' type='button' onclick='nano.form_prev(document.".$schema."_edit_form); ".$schema."_relation_parent_callback();'><</button>
	</div>
	<div class='form-group col-xs-4 col-md-2'>
	  <label class='control-label'>&nbsp;</label>
	  <button class='form-control btn btn-primary' type='button' onclick='nano.form_next(document.".$schema."_edit_form); ".$schema."_relation_parent_callback();'>></button>
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
      
      // define the relation callback
      $html.="
      ".$s["schema"]."_relation_child_callback=function() 
      {
        schemas.$schema.data[schemas.$schema.ix]['$s[name]']=JSON.parse(JSON.stringify(schemas.$s[schema].data));
      }
      ";
      
      $html.="</script>";
      
      
      //$form=form($schemas->{$s["schema"]},$action,[$s["key"]=>0],$s["schema"]."_edit_form");
      $form=form($schemas->{$s["schema"]},$action,[],$s["schema"]."_edit_form");
      
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
  
  ".$schema."_relation_parent_callback=function()
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
    if(schemas.$schema.ix==-1) return false;
    
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
        
        //schemas[field.schema].data=schemas.$schema.data[schemas.$schema.ix][field.name];
        
        schemas[field.schema].data=JSON.parse(JSON.stringify(schemas.$schema.data[schemas.$schema.ix][field.name]));
        
        
        //document[field.schema+'_edit_form'][field.key].attributes.nano_default=schemas.$schema.data[schemas.$schema.ix];
        
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
  
  if(isset($param["title"])) $html.="<h4>".lbl($param["title"])."</h4>";
  
  $action=
  [
    ["name"=>"add", "label"=>lbl("add"), "clearall"=>false,"col"=>"col-xs-6 col-xs-6","type"=>"button", "onclick"=>$schema."_add_line();" ],
    ["name"=>"del", "label"=>lbl("del"), "btn"=>"btn-default", "clearall"=>false,"col"=>"col-xs-6 col-xs-6","type"=>"button", "onclick"=>$schema."_del_line();" ],
  ]; 
  
  $html.=form($schemas->{$schema},$action,[],$schema."_edit_form"); 
    
  $html.="<div id='".$schema."_list_div' ></div>";
  
  $html.="
  <div class='row'>
    <div class='form-group col-xs-6 col-md-6'>
      <label class='control-label'>&nbsp;</label>
      <button class='form-control btn btn-primary' type='button' onclick='".$schema."_bulk_save()'>".lbl("save")."</button>
    </div>
    <div class='form-group col-xs-6 col-md-6'>
      <label class='control-label'>&nbsp;</label>
      <button class='form-control btn btn-default' type='button' onclick=''>".lbl("clear")."</button>
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
      schemas.$schema.ix=schemas.$schema.data.length-1;      
      
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
      schemas.$schema.ix=schemas.$schema.data.length-1;      
      
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
	".$schema."_save_callback();
      
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
