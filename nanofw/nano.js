// ************* standard function

var nano={}; 


nano.setupMultiModal=function()
{
    // Opening mulptiple modal dialogs cause scrolling problems
    // This fuction is called in nano footer to fixe this issue.
  
    nano.openModals=0;
  
    $('.modal').on('hidden.bs.modal', function (e) {
        nano.openModals--;
        if(nano.openModals>0) $('body').addClass('modal-open');
    });

    $('.modal').on('shown.bs.modal', function (e) {
        nano.openModals++;
    });
  
}

//query sample

   /*var query=
    {
        'status':{'\$eq':'ENC'},
        'date_facture_prevue':{'\$year':'','\$gt':2015},
        '\$columns':
        {
            'biais.mono_bino':{'\$aggregate':'avg'}
        },
        '\$orderby':
        {
            'date_facture_prevue':{'\$way':'asce'}
        },
        '\$groupby':
        {
            'date_facture_prevue':{'\$monthname':''} 
        } 
    };

    */
    
nano.save=function(schema,json,callback)
{
  //json are data you want to save
  var data="schema="+schema+"&data="+encodeURIComponent(JSON.stringify(json));
  nano.ajax(data,'nanofw/nano_save.php?',callback); 
}

nano.load=function(schema,json,callback)
{
  // json is a query
  var data="schema="+schema+"&query="+encodeURIComponent(JSON.stringify(json));
  nano.ajax(data,'nanofw/nano_load.php?',callback); 
}

// this function displays text a nice way // not finiched
nano.txt=function(txt)
{
  //var re = new RegExp("_", "g");
  //if(txt)  txt=txt.replace(re," ");
  return txt;
}

// this function is used to call the ajax script  
nano.ajax=function(data,url,callback)
{
  
  console.log(callback);
  
  var xhr_object = null;
  var reply;
  
  if(window.XMLHttpRequest) var xhr_object = new XMLHttpRequest();
  else if(window.ActiveXObject) var xhr_object = new ActiveXObject('Microsoft.XMLHTTP');
  else
  {
      alert("Your browther does not implement XMLHTTPRequest");
      return;
  }
  
  $("#nano_wait").show();
  document.body.style.cursor="wait";
  
  //xhr_object.open('POST',url+data,true);
  xhr_object.open('POST',url,true);
  xhr_object.onreadystatechange = function()
  {
    $("#nano_wait").hide();
    document.body.style.cursor="";
    
    if(xhr_object.readyState == 4)
    {
      reply=nano.ajax_validate(xhr_object.responseText);
      if (reply)  callback(reply);
    }
  }
  xhr_object.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhr_object.send(data);
  return xhr_object;
}

// this function will be used to validate all json return for the ajax 
nano.ajax_validate=function (reply)
{
  var data=false;
  
  try
  {
    data=JSON.parse(reply);
  }
  catch(err)
  {
    alert("Bad reply from server:"+data);
    return false;
  }
  
  if(data.status=="ERROR")
  {
    alert("Error : "+data.message);
    return false;
  }
  
  return data.data;
}

nano.div_list=function(structure,json,param)
{
  
  var count=0;
  var display;
  var html="<div class='contener-fluid'>";
  
 
  
  for(c in json)
  {
    display=false;
    if (c._status)
    {
      if (c._status<9) display=true;
    }
    else display=true;
    
    if (display==true)
    {
      
      switch(param.display)
      {
        case "well":
          html+="<div class='col-md-4'>";
          html+="<div class='well' style='margin:5px;'>";
          html+=nano.div(structure,json[c],param,c);
          html+="</div></div>";
          count++;
          break;
        case "div":
        default:
          html+=nano.div(structure,json[c],param,c);
          html+="<hr>";
          count++;
          break;
      }
    }
  }
  
  if (count==0)
  {
    html+="<div class='text-center' ><p><span class='glyphicon glyphicon-tags'></span></p><p>"+nano.lbl("no_result")+"</p>";
    if (param.noline) html+=param.noline;
    html+="</div>";
  }
  
  html+="</div>";
    
  return html;
}

nano.div=function(schema,json,param,index)
{
  // structure define the way the data display and the action performed [{"label":"edit","function":"client_edit"}]
  
  /*Structure sample
   * {  "structure":{"col1":{"col":"col-md-1", "clearall":true, "label":"label1", "display":true },....} 
   *    "onclick":"function to trigger if you click on the data"
   *    "buttons":{"button1":{"label":"labe1","fn":"function to trigger", "col":"col-md-3"  },.... } 
   * }
  */
  
  // json must be a flat table 
  if (!json) return "";
  
  var onclick=false;
  if(param.onclick) onclick=param.onclick;
 
  var nolabel=false;
  if(param.nolabel) nolabel=param.nolabel;
  
  var keys= Object.keys(json)
  var key_path;
  var html="";
  //var html="<div class='row'>";
  
  // variables
  var c;
  var value;
  var col;
  var clearall;
  var label;
  var display;
  var fn;
  var s;
  var p;
  var status;
  
  
  
  // if structure is empty create a standard one from the json
  // structure_create(json)
  // not implemented and probably never
  
  if(onclick) html+="<div onclick='"+onclick+"("+index+")'>";
  
  for(c in schema.structure)
  {
    name=schema.structure[c].name; 
    col=schema.structure[c].col; 
    clearall=schema.structure[c].clearall;
    label=schema.structure[c].label;
    
    display=false;
    if (schema.structure[c].display) display=schema.structure[c].display.indexOf("div")>=0;
    
    value=json[name];
    
    // go back to te next line
    if (clearall) html+="<div style='clear:both'></div>";
    
    if (display)
    {
      html+="<div class=' "+col+"'>"; //form-group
      if(label=="") label="&nbsp;";
      if(!nolabel) html+="<label class='control-label' >"+label+"</label>";
      html+="<div><p class='form-control-static'>";
      switch(schema.structure[c].type)
      {
        case "boolean":
        case "ckeckbox":
          if(value==1) html+="<span class='glyphicon glyphicon-ok'></span>";
          break;
        case "key":
          if(value) html+=nano.concat_json(schemas[schema.structure[c].schema],value);
          break;
        case "password":
          html+="******";
          break;
        case "relation":
          html+=value.length;
          break;
        case "section_start":
        case "section_end":
        case "html":
        case "button":
	  
          break;
        case "select":
        case "list":
          html+=nano.lbl(name+"_"+value);
          break;
        default:
          html+=nano.lbl(value);
          break;
      }
      html+="</p></div>";
      html+="</div>";
    }
  }  
  
  // close the onclick div
  if(onclick!="") html+="</div>";
  
  // add buttons
  for(c in param.buttons)
  {
      html+="<div class='form-group "+param.buttons[c].col+"'>";
      html+="<label class='control-label' >&nbsp;</label>";
      html+="<div><button class='form-control' onclick='"+param.buttons[c].fn+"("+index+")'>"+param.buttons[c].label+"</button></div>";
      html+="</div>";
  }
  
  
  //html+="</div>";  
  //html+="<div class='row'>";
  //html+="</div>";  

  return html;
}

nano.key_find=function(field,mode)
{
  var schema=schemas[field.attributes.nano_schema.value];
  var s;
  var f;
  //var v=field.value;
  var q={};
  var or={};
  
  switch(mode)
  {
    case "onfind":
      // search in all search fields
      if(field.value=="?") field.value="";
      for(s in schema["structure"])
      {
        if ( schema["structure"][s].display.indexOf("find")>=0)
        {
          f=schema["structure"][s].name;
          //q={};
          //q[f]={ "$lk":field.value};
          //or.push(q);
		  or[f]={ "$lk":field.value};
        }
      }
    case "onload":
      // always search in the _id
      //or.push({"_id":{"$eq":v}});
	  or["_id"]={"$eq":field.value};
      break;
  }
  
  
  
  var callback=function(reply)
  {
    var r;
    
    
    if(reply.length==1)
    {
      field.attributes.nano_value=reply[0];
      field.value=nano.concat_json(schemas[field.attributes.nano_schema.value],reply[0]);
    }
    else if (reply.length>1)
    {
      var param=
      {
        "onclick":"nano.key_choose",
        "multiselect":false
      }
    
      $("#nano_key_choose_div").html(nano.table(schema,reply,param));
      $("#nano_key_choose_popup").modal("show");
      
      // create the "callback fuction" sortof
      nano.key_choose=function(e)
      {
        field.attributes.nano_value=reply[e];
        field.value=nano.concat_json(schemas[field.attributes.nano_schema.value],reply[e]);
        $("#nano_key_choose_popup").modal("hide");
        delete nano.key_choose;
      }
      
    }
    else
    {
      field.value="";
    }
  };
  
  
  q["$or"]=or;
  console.log(q);
  
  nano.load(schema.name,q,callback);
  
}

nano.form_next=function(form)
{
  var schema=schemas[form.attributes.nano_schema.value];
  var mode=form.attributes.nano_mode.value;
  
  if(schema.ix < schema.data.length-1 && (mode=="edit" || mode=="lock"))
  {
    schema.ix++;
    nano.form_load(schema.data[schema.ix],form);
  }
}

nano.form_prev=function(form)
{
  var schema=schemas[form.attributes.nano_schema.value];
  var mode=form.attributes.nano_mode.value;
  
  if(schema.ix > 0  && (mode=="edit" || mode=="lock"))
  {
    schema.ix--;
    nano.form_load(schema.data[schema.ix],form);
  }
}

nano.form_reset=function(form)
{
  var s;
  var schema=schemas[form.attributes.nano_schema.value];
  
  form.reset();
  
  for(s in schema.structure) 
  {
    if(schema.structure[s].display)
    {
      //if(schema.structure[s].display.indexOf("form")>=0)
      if(form[schema.structure[s].name])
      {
        switch(schema.structure[s].type)
        {
          case "relation":
          case "section_start":
          case "section_end":
          case "html":
          case "button":
            break;
          default:
            form[schema.structure[s].name].attributes.nano_value=[];
            break;
        }
      }
    }
  }
  
}

nano.quickValue=function(value)
{
  
  if(typeof value=="string") // I only use this on string for the moment
  {
    var param=value.split(".");
    switch(true)
    {
      case param[0]=="!!DATA":
        
        if(!param[1]) return "";  
          
        if(!schemas[param[1]]) return "";
        
        if(!schemas[param[1]].data) return "";
          
        if(!schemas[param[1]].data[schemas[param[1]].ix]) return "";
        
        if(!param[2]) return "";  
        
        if(param[2]=="END!!") return schemas[param[1]].data[schemas[param[1]].ix];
        
        if(!schemas[param[1]].data[schemas[param[1]].ix][param[2]]) return "";
        
        return schemas[param[1]].data[schemas[param[1]].ix][param[2]];
        
        break;
      case param[0]=="!!USERNAME!!":
        return user;
        break;
      case param[0]=="!!CENTER!!":
        return center;
        break;
      case param[0]=="!!TODAY!!":
        return nano.today() ;
        break;
      case param[0]=="!!VALUE!!":
        return "";
        break;
      //case value=="!!TOMORROW!!":
        //return date("Y-m-d",strtotime("now + 1 day"));
      //case value=="!!NEXTWEEK!!":
        //return date("Y-m-d",strtotime("now + 1 week"));
      //case value=="!!NEXTMONTH!!":
        //return date("Y-m-d",strtotime("now + 1 month"));*/
      default:
        return value;
        break;
    }
  }
  return value;
}

nano.field_endisable=function(field,stat)
{
    field.disabled=stat;
    if(stat) field.style.background="#dddddd";
    else field.style.background="#ffffff";
}


nano.form_prepare=function(form,nano_mode,default_values)
{
  var s;
  var i;
  var schema=schemas[form.attributes.nano_schema.value];
  var value;
  var el;
  var disabled;
  
  form.attributes.nano_mode.value=nano_mode;
  
  if(parseInt(form._status.value)>=7) nano_mode="lock";
  
  for(s in schema.structure) 
  {
    if(schema.structure[s].display)
    {
      //if(schema.structure[s].display.indexOf("form")>=0)
      if(form[schema.structure[s].name])
      
      {
	value="";
        
        disabled=false;
        if(schema.structure[s].disabled) disabled=schema.structure[s].disabled; 
        
	switch(schema.structure[s].type)
	{
	  case "relation":
	  case "section_start":
	  case "section_end":
	  case "html":
	  case "button":
	    break;
          case "info":
              nano.field_endisable(form[schema.structure[s].name],true);
              break;
	  //case "key":
	  //case "json":
            // take default array value from the form
            //if(form[schema.structure[s].name].attributes.nano_default) value=form[schema.structure[s].name].attributes.nano_default;
	  default:
      
	    // take default value from the form
	    //if(form[schema.structure[s].name].attributes.nano_default.value) value=form[schema.structure[s].name].attributes.nano_default.value;
      
            // take the default from structure  
            if(schema.structure[s].value) value=schema.structure[s].value;  
              
              
	    // take defaut value from the parameter
	    if(default_values[schema.structure[s].name]) value=default_values[schema.structure[s].name];
	    
	    value=nano.quickValue(value);
	    
	    //console.log("value:");
      
	    //console.log(value);
      
	    
	    switch(nano_mode)
	    {
	      case "find":
                nano.field_endisable(form[schema.structure[s].name],true);
                if( schema.structure[s].display.indexOf("find")>=0 ) 
                {
                    nano.field_endisable(form[schema.structure[s].name],disabled);
                }
                break;
              case "lock":
                nano.field_endisable(form[schema.structure[s].name],true);
                break;
              case "new":
                if(value!="") nano.load_field(form[schema.structure[s].name],value);
                nano.field_endisable(form[schema.structure[s].name],disabled);
                break;
              case "edit":
                nano.field_endisable(form[schema.structure[s].name],disabled);
                break;
	      }
	    
	      if (schema.structure[s].focus) form[schema.structure[s].name].focus();
	      break;
	}
      }
    }
  }
  
  for (i = 0; i < form.elements.length; i++) 
  {
    el = form.elements[i];
    switch(el.type)
    {
      case "button":
        switch(nano_mode)
        {
          case "lock":
            el.disabled=true;
            break
          default:
            el.disabled=false;
            break;
        }
        break;
      default:
        break;
    }
  }
}

nano.round=function (num,round)
{
  return Math.round(num*(1/round))/(1/round);
}

nano.form_load=function(json,form)
{
  var schema=schemas[form.attributes.nano_schema.value];
  var c;
  var s;
  var mode=form.attributes.nano_mode.value;
  var field;
  var value;
  nano.form_reset(form);
  
  if(json)
  {
    for(s in schema.structure) 
    {
      if(form[schema.structure[s].name])
      {
        el=form[schema.structure[s].name];
        value=json[schema.structure[s].name];
	
	if(schema.structure[s].round) value=nano.round(value,schema.structure[s].round);
	
	nano.load_field(el,value);
      }
    }
    
    form._id.value=json["_id"];
    form._status.value=json["_status"];
    
  }
  
  nano.form_prepare(form,mode,{});
  
  if(schema.onload) window[schema.onload]();

}

nano.load_field=function(el,value)
{
  //var form=el.form;
  //var schema=schemas[form.attributes.nano_schema.value];
  
  if(!value) value="";

  switch (el.attributes.nano_type.value)
  {
    case "boolean":
    case "ckeckbox":
      value=parseInt(value);
      el.checked=value ;
      break;
    case "multiplekey":
      for(s in el.options) if(value) if(value.indexOf(parseInt(el.options[s].value))>=0) el.options[s].selected=true;
      break;
    case "key":
      if(value)
      {
        el.attributes.nano_value=value;
        el.value=nano.concat_json(schemas[el.attributes.nano_schema.value],value);
      }
      break;
    case "json":
      if(value)
      {
        el.attributes.nano_value=value;
	
	//console.log(form.attributes);
	
        $("#"+el.name+"_json_div").html(nano.div(schemas[el.attributes.nano_schema.value],value,[],0)); //nano.div(schemas.client,schemas.client.data[ix],[],ix)
      }
      break;
    case "relation":
      if(value=="") value=[];
      el.attributes.nano_value=value;
      break;
    case "section_start":
    case "section_end":
    case "html":
    case "button":
      break;
    default:
      el.value=value;
      break;
  }

}


nano.concat_json=function(schema,json)
{
  var i;
  var txt="";

  for(i in schema.structure) if(schema.structure[i].display.indexOf("concat")>=0) txt+=json[schema.structure[i].name]+" ";
  
  if(txt=="") txt="Add a concat field!";
  
  return txt.trim();  
}

nano.form_save=function(form)
{
  var schema=schemas[form.attributes.nano_schema.value];
  var i;
  var s;
  var json=new Object();
  var arr = form.elements;
  var el;
  
  for(s in schema.structure) 
  {
    if(form[schema.structure[s].name])
    {
      el = form[schema.structure[s].name];
      switch(el.attributes.nano_type.value)
      {
        case "boolean":
        case "ckeckbox":
            if(el.checked==true) json[el.name]=1;
            else json[el.name]=0;
            break;
        case "multiplekey":
            json[el.name]=[];
            for(s in el.selectedOptions) if(el.selectedOptions[s].value) json[el.name].push(el.selectedOptions[s].value);
          break;
        case "key":
            json[el.name]=el.attributes.nano_value;
            break
        case "json":
            json[el.name]=el.attributes.nano_value;
            break
        case "relation":
            if(el.attributes.nano_value)
            {
                json[el.name]=el.attributes.nano_value;//=value;
            }
            else
            {
                json[el.name]=[];
            }
            break;
        case "section_start":
        case "section_end":
        case "info":
        case "html":
        case "button":
            break;
        default:
            json[el.name]=el.value;
            break;
      }
    }
  }  
  
  json["_id"]=form._id.value;
  json["_status"]=form._status.value;
  
  return json;
}

nano.form_query=function(form)
{
  var schema=schemas[form.attributes.nano_schema.value];
  var s;
  var q;
  var v;
  var query={};

  // search in all search fields
  for(s in schema["structure"])
  {
    v="";

    if(form[schema.structure[s].name])
    {
        if ( schema["structure"][s].display.indexOf("find")>=0)
        {
        
            switch(schema["structure"][s].type)
            {
            case "section_start":
            case "section_end":
            case "info":
            case "html":
            case "button":
                break;
            case "boolean":
            case "ckeckbox":
                v="0";
                if(form[schema["structure"][s].name].checked==true) v="1";
                break;
            default:
                v=form[schema["structure"][s].name].value;
                break;
            }
            
            if(v!="") query[schema["structure"][s].name]={ "$lk":v};
        }
    }
  }
  
  return query;
}

nano.form_validate=function(form)
{
  var schema=schemas[form.attributes.nano_schema.value];
  var i;
  
  for(s in schema["structure"])
  {
    if(schema.structure[s].display)
    {
      //if(schema.structure[s].display.indexOf("form")>=0)
      if(form[schema.structure[s].name])
      
      {
      
        switch(schema["structure"][s].type)
        {
          case "relation":
            break;
          case "key":
            if (form[schema["structure"][s].name].value==0)
            {
              form[schema["structure"][s].name].style.background="#ffe5e5";
              form[schema["structure"][s].name].focus();
              return false;
            }
          case "section_start":
          case "section_end":
          case "html":
          case "json":
          case "info":
          case "button":
            break;
          default:
            if(!form[schema["structure"][s].name].checkValidity())
            {
              form[schema["structure"][s].name].style.background="#ffe5e5";
              return false;
            }
            
            if(!schema["structure"][s].optional && form[schema["structure"][s].name].value=="")
            {
              form[schema["structure"][s].name].style.background="#ffe5e5";
              form[schema["structure"][s].name].focus();
              return false;
            }
        
            form[schema["structure"][s].name].style.background="transparent";
            break;
        }
      }
    }
  }
  
    
  return true;
}

nano.submit=function()
{
  return false;
}

nano.table=function(schema,json,param)
{
  var multiselect=false;
  if(param.multiselect) if(param.multiselect==true) multiselect=true;
  
  var onclick=false;
  if(param.onclick) onclick=param.onclick;
  
  var html="<table id='mytable' class='tablesorter table table-striped'><tbody>";
  var keys;
  var value;
  var display;
  var reg;
  var limit=300;
  var count=0;
  var value=0;
  var nbr=0;
  var footer;
  var status;
  var old_ix=schema.ix;
  //
  var c;
  var f;
  var k;
  var h;
  var e;
  var p;
  var v;
  //
  var footer_op;
  var footer_columns=[];
  
  // if this is the first iteration we dispay the column labels
  html+="<thead><tr>";
  if (multiselect) html+="<th><input type='checkbox' onclick='nano.table_invert_selection(\""+schema.name+"\");'></value></th>"; 
  for(h in schema.structure)
  {
    switch(schema.structure[h].type)
    {
      case "section_start":
      case "section_end":
      case "button":
      //case "html":
        break;
      default:
        if(schema.structure[h].display.indexOf("list")>=0 )  html+="<th>"+schema.structure[h].label+"</th>";
        break;
    }
  }
  html+="</tr></thead>";

  
  for(e in json)
  {
    if (json[e])
    {
      schema.ix=e;
    
      // set display to true for each iteration before testing the filters
      display=true;
      for(f in param.filter)
      {
        for(h in schema.structure)
        {
          if (param.filter[f].name==schema.structure[h].name)
          {
            switch (param.filter[f].op)
            {
              case "$eq":
                if(param.filter[f].value!=json[e][schema.structure[h].name]) display=false;
                break;
              case "$lk":
                reg=new RegExp(param.filter[f].value,"i");
                if (!json[e][schema.structure[h].name].match(reg)) display=false;
              break;
            }
          }
        }
      }
      
      // the status of the line if exist can also affect the if the line is displayed
      if(json[e]._status)
      {
        if (json[e]._status>=9) display=false;
      }
      
      // if filter did not hide the line
      if(display==true)
      {
        
        html+="<tr ";
        if (onclick) html+="onclick='"+onclick+"(\""+e+"\")'";
        html+=">";
        
        if (multiselect)
        {
          if(json[e]._status<7)
          html+="<td ><input type='checkbox' name='"+schema.name+"_checkbox' value='"+e+"' onclick='event.stopPropagation();' ></td>";
          else html+="<td></td>";
        }
        
        for(h in schema.structure)
        {
        
          if (schema.structure[h].name) value=json[e][schema.structure[h].name];
          
          if(!value) value='';
          
          if(schema.structure[h].display)
          {
            if(schema.structure[h].display.indexOf("list")>=0) 
            {
              switch(schema.structure[h].type)
              {
                case "html":
                  html+="<td>";
                  html+=schema.structure[h].html.replace("!!VALUE!!",nano.quickValue(schema.structure[h].value));
                  html+="</td>";
                  break;
                case "key":
                  html+="<td>";
                  if(value) html+=nano.concat_json(schemas[schema.structure[h].schema],value);
                  html+="</td>";
                  break;
                case "json":
                  html+="<td>";
                  if(value) html+=nano.concat_json(schemas[schema.structure[h].schema],value);
                  html+="</td>";
                  break;
                case "relation":
                  html+="<td>"+value.length+"</td>";
                  break;
                case "password":
                  html+="<td>******</td>";
                  break;
                case "boolean":
                case "ckeckbox":
                  if(value==1) html+="<td><span class='glyphicon glyphicon-ok'></span></td>";
                  else html+="<td></td>";
                  break;
                case "select":
                case "list":
                  html+="<td>"+nano.lbl(schema.structure[h].name+"_"+value)+"</td>";
                  break;
                default:
                  html+="<td>"+nano.lbl(value)+"</td>";
                  break;
              }
              // garder dans un tableau les nom des colonnes pour plus le footer
              footer_columns.push(schema.structure[h].name);
            }
          }
        }
        
        html+="</tr>";
        count++;  
      }
      
      if(count==limit) break;
    }
  }

  schema.ix=old_ix;
  
  /*
  if(param.footer && json.length>0)
  {
    footer=nano.table_total(param.footer,json);
    html+="<tfoot><tr>";
    if (multiselect) html+="<th></th>"; 
    for(h in schema.structure)
    {
      for(k in keys)
      {
        if ( schema.structure[h].name==keys[k] ) 
        {
          if(schema.structure[h].display.indexOf("list")>=0)
          {
            if ( footer[schema.structure[h].name].value)
            {
              html+="<th>"+footer[schema.structure[h].name].value+"</th>";
            }
            else
            {
              html+="<th></th>";
            }
          }
        }
      }
    }
    html+="</tr></tfoot>";
  }*/
  
  
  html+="</tbody></table>";
  
  html+="<script>$(function(){$('#mytable').tablesorter();});</script>";
  
  if(count==0)
  {
    html+="<div class='text-center' ><p><span class='glyphicon glyphicon-tags'></span></p>";
    if (schema.noline) html+=schema.noline;
    html+="</div>";
  }
  
  return html;
  
}

  
  
  
nano.table_invert_selection=function(name)
{
  var i;
  var checkboxes=document.getElementsByName(name+'_checkbox');
  
  for(i in checkboxes)
  {
    if(checkboxes[i].checked==true) checkboxes[i].checked=false;
    else checkboxes[i].checked=true;
  }
}

nano.query_form=function(name)
{
  var html="<form name='"+name+"_find_form' onsubmit='return nano.query_from_query_add(this);' >";
  
  html+="<div class='row'>";
  
  html+="<!-- select field -->";
  html+="<div class='form-group col-xs-4'>";
  html+="<select class='form-control' name='field'>";
  
  for(i in schemas[name].structure)
  {
    html+="<option value='"+schemas[name].structure[i].name+"' >"+schemas[name].structure[i].label+"</option>";
  }
  
  html+="</select>";
  html+="</div>";
  
  html+="<!-- operation -->";
  html+="<div class='form-group col-xs-2'>";
  html+="<select class='form-control' name='operation'>";
  html+="<option value='$eq'>=</option>";
  html+="<option value='$gt'>></option>";
  html+="<option value='$lt'><</option>";
  html+="<option value='$gte'>>=</option>";
  html+="<option value='$lte'><=</option>";
  html+="<option value='$ne'>!=</option>";
  html+="<option value='$lk'>like</option>";
  html+="</select>";
  html+="</div>";
  
  
  html+="<!-- value -->";
  html+="<div class='form-group col-xs-6'>";
  html+="<input class='form-control' type='text' name='value' >";
  html+="</div>";
  
  html+="</div>"; //row
  
  
  html+="<!-- or -->";
  html+="<div class='form-group col-xs-2'>";
  html+="<button type='button' class='form-control btn btn-primary' data-toggle='button' aria-pressed='false' onclick='nano.query_from_operator_add(this.form,\"$or\")' >Or</button>"; //
  html+="</div>";
  
  
  html+="<!-- and -->";
  html+="<div class='form-group col-xs-2'>";
  html+="<button type='button' class='form-control btn btn-primary' data-toggle='button' aria-pressed='false' onclick='nano.query_from_operator_add(this.form,\"$and\")' >And</button>"; //
  html+="</div>";
  
  html+="<div class='form-group col-xs-4'>&nbsp;</div>";
  
  html+="<div class='row'>";
  html+="<!-- button -->";
  html+="<div class='form-group col-xs-4'>";
  html+="<button type='sumbit' class='form-control btn btn-primary' >Ajouter</button>"; //
  html+="</div>";
  
  html+="</div>"; //row
  
  html+="<div class='row'>";
  html+="<!-- query -->";
  html+="<div class='form-group col-xs-12'>";
  html+="<input type='text' class='form-control' name='query' >";
  //html+="<div id='query_list_div' ></div>";
  
  html+="</div>";
  html+="</div>"; // row
  
  
  html+="</form>";  // container
  
  return html;
}

nano.query_from_operator_add=function(form,type)
{
  var query;
  
  if (form.or_on===undefined) form.or_on=false;
  if (form.and_on===undefined) form.and_on=false;
  
  switch(type)
  {
    case "$or":
      if(form.or_on)
      {
        query="]";
        form.or_on=false;
      }
      else 
      {
        query="\"$or\":[";
        form.or_on=true;
      }
      break;
    case "$and":
      if(form.and_on)
      {
        query="]";
        form.and_on=false;
      }
      else 
      {
        query="\"$and\":[";
        form.and_on=true;
      }
      break;
  }
  
  form.query.value+=query;
  
}

nano.query_from_query_add=function(form)
{
  if (form.or_on===undefined) form.or_on=false;
  if (form.and_on===undefined) form.and_on=false;
 
  if (form.value.value!="")
  {
    switch(form.query.value.slice(-1))
    {
      case "\"":
      case "}":
        form.query.value+=",";
        break;
    }
    
    var query="\""+form.field.value+"\":{\""+form.operation.value+"\":\""+form.value.value+"\"}";
    if (form.or_on || form.and_on) query="{"+query+"}";
    form.query.value+=query;
  }
  else
  {
    alert(nano.lbl("please_add_value"));
  }
  
  return false;

}


nano.graph=function(div,schema,query,options,graphType)
{
  var callback=function(reply)
  {
    var graph=nano.dataToGraph(reply,schema);
    
    console.log(graph);
  
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart()
    {

      // Create the data table.
      var data = new google.visualization.DataTable();
      
      for(c in graph.columns)
      {
          data.addColumn(graph.columns[c].type, nano.lbl(graph.columns[c].name) );
      }
      
      data.addRows(graph.rows);

      //var chart = new google.visualization.AreaChart(document.getElementById(div));   //LineChart           
      var chart = new google.visualization[graphType](document.getElementById(div));   //LineChart           
      chart.draw(data, options);
    }
  }
  
  
  nano.load('biais_encours',query,callback);
  
  
  return true;
  
  
}

nano.dataToGraph=function(json,schema)
{
  var keys=Object.keys(json[0]); 
  var e;
  var k;
  var v;
  var s;
  var row=[];
  var graph={};
  var gtype;
  var gcolumn;
  graph.columns=[];
  graph.rows=[];
  
  /*  type - A string with the data type of the values of the column. The type can be one of the following: 'string', 'number', 'boolean', 'date', 'datetime', and 'timeofday'. */
  
  // map nanofw columns type with google graph
  if(schema)
  {
    for(s in schema.structure)
    {
      if(keys.indexOf(schema.structure[s].name)>-1) 
      {
	switch(schema.structure[s].type)
	{
	    case "integer":
	    case "number":
	    case "double":
	    case "float":
	      gtype="number";
	      break;
	    case "select":
	    case "list":
	    case "password":
	    case "translate":
	    case "string":
	    case "hidden":
	    case "text":
	      gtype="string";
	      break;
	    case "boolean":
	    case "checkbox":
	      gtype="boolean";
	      break;
	    case "date":
	      gtype="string";
	      break;
	    case "dateTime":
	      gtype="datetime";
	      break;
	    case "key":
	    case "multiplekey":
	    case "json":
	      gtype="string";
	      break;
	}
	
	graph.columns.push({"type":gtype,"name":schema.structure[s].name});
	
      }
    }
  }
  
  for(e in json)
  {
    row=[];
    for(k in keys)
    {
      row.push(json[e][keys[k]]);
    }
    graph.rows.push(row);
  }
  
  return graph;
  
}

nano.table_total=function(operation,json)
{
 var result=new Object(); 
  
 if(json.length>0)
 {  
  var keys=Object.keys(json[0]); 
  var e;
  var k;
  var o;
  
    for(e in json)
    {
      for (o in operation)
      {
        for(k in keys)
        {
          if (json[e]._status<8)
          {            
            if ( operation[o].name==keys[k] )
            {
              if(!result[keys[k]]) result[keys[k]]=new Object();
              if(!result[keys[k]].count) result[keys[k]].count=0;
              if(!result[keys[k]].sum) result[keys[k]].sum=0;
              
              result[keys[k]].count++;
              result[keys[k]].sum+=parseFloat(json[e][keys[k]]);
            }
          }
        }
      }
    }

    for(k in keys)
    {
      for (o in operation)
      {
        if ( operation[o].name==keys[k] )
        {
          switch (operation[o].op)
          {
            case "sum":
              result[keys[k]].value=result[keys[k]].sum;
              break;
            case "avg":
              result[keys[k]].value=result[keys[k]].sum/result[keys[k]].count;
              break;
            case "count":
              result[keys[k]].value=result[keys[k]].count;
              break;
          } 
        }
      }
    }
  }
  
  return result;
 
}

nano.today=function()
{
  var today = new Date();
  var dd = today.getDate();
  var mm = today.getMonth()+1; //January is 0!
  var yyyy = today.getFullYear();

  if(dd<10) dd='0'+dd;
  if(mm<10) mm='0'+mm;

  return yyyy+"-"+mm+"-"+dd;
}

nano.lbl=function(txt)
{
  if(label[txt])
  {
    return label[txt];
  }
  else
  {
    return txt;
  }
}


