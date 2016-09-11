// STD function for client finding
var intra={};


// clients
intra.client={};

intra.client.id;
intra.client.detail;
intra.client.div;
intra.client.choose_callback=function(){};
intra.client.dismiss_callback=function(){};


intra.client.find=function()
{
  var field=intra.client.id;
  var callback=function (reply)
  {
    schemas.client.data=reply;
    if (schemas.client.data.length==0)
    {
      intra.client.new();
    }
    else if(schemas.client.data.length==1)
    {
      intra.client.choose(0);
    }
    else if(schemas.client.data.length>1)
    {
      var param=
      {
        'onclick':'intra.client.choose',
        'multiselect':false
      }
      $('#client_list_div').html(nano.table(schemas.client,schemas.client.data,param));
      $('#client_choose_popup').modal('show');
    }
    
    
  };
  
  if(field.value.length>0) nano.ajax("find="+field.value,'ajax_find_client.php',callback);
}
 
intra.client.edit=function()
{
  var field=intra.client.id;
  
  if(field.value=="") return false;
  
  var callback=function(reply)
  {
    var form=document.client_edit_form;
    schemas.client.data=reply;
    schemas.client.ix=0;
    form.attributes.nano_mode.value="edit";
    nano.form_load(schemas.client.data[0],form);  
    $('#client_edit_popup').modal('show');
  };
  
  if(field.value.length>0) nano.ajax("find="+field.value,'ajax_find_client.php',callback);
}

intra.client.choose=function(ix)
{
  $("#client_edit_popup").modal("hide");
  $("#client_choose_popup").modal("hide");
  
  schemas.client.ix=ix;
  
  if(intra.client.id) intra.client.id.value=schemas.client.data[ix].numclient;
  if(intra.client.detail) intra.client.detail.attributes.nano_value=schemas.client.data[ix];
  if(intra.client.detail) intra.client.detail.value=nano.concat_json(schemas.client,schemas.client.data[ix]);
  if(intra.client.div) $(intra.client.div).html(nano.div(schemas.client,schemas.client.data[ix],[],ix));
  
  intra.client.choose_callback();
}

intra.client.dismiss=function()
{
  schemas.client.data=[];
  schemas.client.ix=-1;
  
  if(intra.client.id) intra.client.id.value='';
  if(intra.client.detail) delete intra.client.detail.attributes.nano_value;
  if(intra.client.detail) intra.client.detail.value='';
  if(intra.client.div) $(intra.client.div).html('');
  
  intra.client.dismiss_callback();
}

intra.client.create=function()
{
  var json;
  var form=document.client_edit_form;
  var mode=form.attributes.nano_mode.value;
  
  var callback=function(reply)
  { 
    if(!schemas.client.data) schemas.client.data=[];
    schemas.client.data.push(reply[0]);
    schemas.client.ix=schemas.client.data.length-1;
    intra.client.choose(schemas.client.ix);
  };
  
  if(nano.form_validate(form))
    {
      json=nano.form_save(form);
      nano.ajax('data='+encodeURIComponent(JSON.stringify(json)),'ajax_save_client.php?',callback);  
    }
    else
    {
      return false;
    }

}

intra.client.new=function()
{
    var form=document.client_edit_form;
    
    $('#client_edit_popup').modal('show');
    form.reset();
    nano.form_prepare(form,'new',{});
    
    // set the defaut values
    form.mailing.checked=true;
    form.okmailingaudio.checked=true;
    form.okemailing.checked=true;
    form.acp.checked=true;
    
    
}


// STD functions for article finding
intra.article={};

intra.article.id;
intra.article.detail;
intra.article.brand;
intra.article.article_type;
intra.article.designation;
intra.article.nr_metas;
intra.article.div;
intra.article.pv;
intra.article.choose_callback=function(){};
intra.article.dismiss_callback=function(){};

intra.article.find_code=function()
{
  var value=intra.article.id.value;
  intra.article.find(value,"code","",0);
}


intra.article.find_marque=function()
{
  var value=intra.article.id.value;
  intra.article.find(value,"marque","",0);
}


intra.article.find_designation=function()
{
  var value=intra.article.id.value;
  intra.article.find(value,"designation","",0);
}

intra.article.find_any=function()
{
  var value=intra.article.id.value;
  intra.article.find(value,"any","",0);
}


intra.article.find=function(value,what,type,stock)
{
 
  var callback=function(reply)
  {
    schemas.article.data=reply;
    if (schemas.article.data.length==0)
    {
     intra.article.dismiss()
    }
    else //if(schemas.article.data.length==1)
    {
     /*intra.article.choose(0);
    }
    else if(schemas.article.data.length>1)
    {*/
      var param=
      {
        'onclick':'intra.article.choose',
        'multiselect':false
      }
      $('#article_list_div').html(nano.table(schemas.article,schemas.article.data,param));
      $('#article_choose_popup').modal('show');
    }
  };
  
  if(value.length>0) nano.ajax('find='+value+'&what='+what+'&type='+type+'&stock='+stock,'ajax_find_article.php?',callback);
}

intra.article.dismiss=function()
{
  schemas.article.data=[];
  schemas.article.ix=-1;
  
  if(intra.article.id) intra.article.id.value="";
  if(intra.article.detail) intra.article.detail.attributes.nano_value="";
  if(intra.article.detail) intra.article.detail.value="";
  if(intra.article.brand) intra.article.brand.value="";
  if(intra.article.article_type) intra.article.article_type.value="";
  if(intra.article.designation) intra.article.designation.value="";
  if(intra.article.nr_metas) intra.article.nr_metas.value="";
  if(intra.article.pv) intra.article.pv.value="";
  if(intra.article.div) $(intra.article.div).html("");
  
  intra.article.dismiss_callback();
  
}


intra.article.choose=function(ix)
{
  $("#article_edit_popup").modal("hide");
  $("#article_choose_popup").modal("hide");
  
  schemas.article.ix=ix;
  
  if(intra.article.id) intra.article.id.value=schemas.article.data[ix].article_code;
  if(intra.article.detail) intra.article.detail.attributes.nano_value=schemas.article.data[ix];
  if(intra.article.detail) intra.article.detail.value=nano.concat_json(schemas.article,schemas.article.data[ix]);
  if(intra.article.brand) intra.article.brand.value=schemas.article.data[ix].marque;
  if(intra.article.article_type) intra.article.article_type.value=schemas.article.data[ix].article_type;
  if(intra.article.designation) intra.article.designation.value=schemas.article.data[ix].designation;
  if(intra.article.nr_metas) intra.article.nr_metas.value=schemas.article.data[ix].nr_metas;
  if(intra.article.pv) intra.article.pv.value=schemas.article.data[ix].prix_vente;
  if(intra.article.div) $(intra.article.div).html(nano.div(schemas.article,schemas.article.data[ix],[],ix));
  
  intra.article.choose_callback();
}


// STD functions for collaborateur finding
intra.collaborateur={};

intra.collaborateur.id;
//intra.collaborateur.detail;
//intra.collaborateur.div;

intra.collaborateur.find_ophta=function(field)
{
  intra.collaborateur.id=field;
  intra.collaborateur.find("ophta");
}

intra.collaborateur.find_collab=function(field)
{
  intra.collaborateur.id=field;
  intra.collaborateur.find("collab");
}

intra.collaborateur.find_orl=function(field)
{
  intra.collaborateur.id=field;
  intra.collaborateur.find("orl");
}

intra.collaborateur.find=function(what)
{
  var field=intra.collaborateur.id;
  var callback=function(reply)
  {
    schemas.collaborateur.data=reply;
    if (schemas.collaborateur.data.length==0)
    {
     field.value="";
    }
    else if(schemas.collaborateur.data.length==1)
    {
     intra.collaborateur.choose(0);
    }
    else if(schemas.collaborateur.data.length>1)
    {
      var param=
      {
        'onclick':'intra.collaborateur.choose',
        'multiselect':false
      }
      $('#collaborateur_list_div').html(nano.table(schemas.collaborateur,schemas.collaborateur.data,param));
      $('#collaborateur_choose_popup').modal('show');
    }
  };
  
  if(field.value.length>0) nano.ajax('find='+field.value+'&what='+what,'ajax_find_collaborateur.php?',callback);
}

intra.collaborateur.choose=function(ix)
{
  $("#collaborateur_edit_popup").modal("hide");
  $("#collaborateur_choose_popup").modal("hide");
  
  schemas.collaborateur.ix=ix;
  
  if(intra.collaborateur.id) intra.collaborateur.id.value=schemas.collaborateur.data[ix].libelle;
  //if(intra.collaborateur.detail) intra.collaborateur.detail.attributes.nano_value=schemas.collaborateur.data[ix];
  //if(intra.collaborateur.detail) intra.collaborateur.detail.value=nano.concat_json(schemas.collaborateur,schemas.collaborateur.data[ix]);
  //if(intra.collaborateur.div) $(intra.collaborateur.div).html(nano.div(schemas.collaborateur,schemas.collaborateur.data[ix],[],ix));
}


// STD functions for telsearch finding
intra.telsearch={};

intra.telsearch.name;
intra.telsearch.firstname;
intra.telsearch.street;
intra.telsearch.streetno;
intra.telsearch.zip;
intra.telsearch.city;
intra.telsearch.canton;
intra.telsearch.phone;

intra.telsearch.find=function()
{
  var who=intra.telsearch.name.value+"+"+intra.telsearch.firstname.value;
  var where=intra.telsearch.zip.value+"+"+intra.telsearch.city.value+"+"+intra.telsearch.street.value;
  
  var callback=function(reply)
  {
    schemas.telsearch.data=reply;
    
    var param=
    {
      'onclick':'intra.telsearch.choose',
      'multiselect':false
    }
    $('#telsearch_list_div').html(nano.table(schemas.telsearch,schemas.telsearch.data,param));
    $('#telsearch_choose_popup').modal('show');
  
  };
  
  if(who.length>0) nano.ajax('who='+who+'&where='+where,'ajax_find_telsearch.php?',callback);
}

intra.telsearch.choose=function(ix)
{
  $("#telsearch_edit_popup").modal("hide");
  $("#telsearch_choose_popup").modal("hide");
  
  schemas.telsearch.ix=ix;
  
  if(intra.telsearch.name)  intra.telsearch.name.value=schemas.telsearch.data[ix].name ;
  if(intra.telsearch.firstname) intra.telsearch.firstname.value=schemas.telsearch.data[ix].firstname;
  if(intra.telsearch.street) intra.telsearch.street.value=schemas.telsearch.data[ix].street;
  if(intra.telsearch.streetno) intra.telsearch.streetno.value=schemas.telsearch.data[ix].streetno;
  if(intra.telsearch.zip) intra.telsearch.zip.value=schemas.telsearch.data[ix].zip;
  if(intra.telsearch.city) intra.telsearch.city.value=schemas.telsearch.data[ix].city;
  if(intra.telsearch.canton) intra.telsearch.canton.value=schemas.telsearch.data[ix].canton;
  if(intra.telsearch.phone) intra.telsearch.phone.value=schemas.telsearch.data[ix].phone;
  
}

// STD functions for orl finding
intra.orl={};

intra.orl.name;

intra.orl.find=function()
{
  var who=intra.orl.name.value; //+"+"+intra.orl.firstname.value;
  
  var callback=function(reply)
  {
    schemas.orl.data=reply;
  
    var param=
    {
      'onclick':'intra.orl.choose',
      'multiselect':false
    }
    
    $('#orl_list_div').html(nano.table(schemas.orl,schemas.orl.data,param));
    $('#orl_choose_popup').modal('show');
  
  };
  
  if(who.length>0) nano.ajax('who='+who+'&where=','ajax_find_orl.php?',callback);
}

intra.orl.choose=function(ix)
{
  $("#orl_edit_popup").modal("hide");
  $("#orl_choose_popup").modal("hide");
  
  schemas.orl.ix=ix;
 
  if(intra.orl.name) intra.orl.name.value=schemas.orl.data[ix].firstname+' '+schemas.orl.data[ix].name; //nano.concat_json(schemas.client,schemas.client.data[ix]);
  if(intra.orl.data) intra.orl.data.attributes.nano_value=schemas.orl.data[ix];
}




// STD ordonnance
intra.ordonnance={};

//intra.ordonnance.id;
//intra.ordonnance.numclient;
//intra.ordonnance.detail;
//intra.ordonnance.div;
//intra.ordonnance.choose_callback=function(){};
//intra.ordonnance.dismiss_callback=function(){};


intra.ordonnance.find=function(numclient,numordonnance)
{
  
  var callback=function (reply)
  {
    schemas.ordonnance.data=reply;
    if (schemas.ordonnance.data.length==0)
    {
      intra.ordonnance.new();
    }
    else if(schemas.ordonnance.data.length==1)
    {
      intra.ordonnance.choose(0);
    }
    else if(schemas.ordonnance.data.length>1)
    {
      var param=
      {
        'onclick':'intra.ordonnance.choose',
        'multiselect':false
      }
      $('#ordonnance_list_div').html(nano.table(schemas.ordonnance,schemas.ordonnance.data,param));
      $('#ordonnance_choose_popup').modal('show');
    }
    
    
  };
  
  //if(numordonnance.length>0 || numclient.lenght>0) 
    nano.ajax("numclient="+numclient+"&numordonnance="+numordonnance,"ajax_find_ordonnance.php",callback);
}
 
intra.ordonnance.edit=function()
{
  var field=intra.ordonnance.id;
  
  if(field.value=="") return false;
  
  var callback=function(reply)
  {
    var form=document.ordonnance_edit_form;
    schemas.ordonnance.data=reply;
    schemas.ordonnance.ix=0;
    form.attributes.nano_mode.value="edit";
    nano.form_load(schemas.ordonnance.data[0],form);  
    $('#ordonnance_edit_popup').modal('show');
    
    // help user not to forget to ask birth date
    //if(schemas.ordonnance.data[0].ddnestime==true) 
    //{
        form.datenaissance.background="red";
    //}
    
  };
  
  if(field.value.length>0) nano.ajax("find="+field.value,'ajax_find_ordonnance.php',callback);
}

intra.ordonnance.choose=function(ix)
{
  $("#ordonnance_edit_popup").modal("hide");
  $("#ordonnance_choose_popup").modal("hide");
  
  schemas.ordonnance.ix=ix;
  
  if(intra.ordonnance.id) intra.ordonnance.id.value=schemas.ordonnance.data[ix].numordonnance;
  if(intra.ordonnance.detail) intra.ordonnance.detail.attributes.nano_value=schemas.ordonnance.data[ix];
  if(intra.ordonnance.detail) intra.ordonnance.detail.value=nano.concat_json(schemas.ordonnance,schemas.ordonnance.data[ix]);
  if(intra.ordonnance.div) $(intra.ordonnance.div).html(nano.div(schemas.ordonnance,schemas.ordonnance.data[ix],[],ix));
  
  intra.ordonnance.choose_callback();
}

intra.ordonnance.dismiss=function()
{
  schemas.ordonnance.data=[];
  schemas.ordonnance.ix=-1;
  
  if(intra.ordonnance.id) intra.ordonnance.id.value='';
  if(intra.ordonnance.detail) delete intra.ordonnance.detail.attributes.nano_value;
  if(intra.ordonnance.detail) intra.ordonnance.detail.value='';
  if(intra.ordonnance.div) $(intra.ordonnance.div).html('');
  
  intra.ordonnance.dismiss_callback();
}

intra.ordonnance.create=function()
{
  var json;
  var form=document.ordonnance_edit_form;
  var mode=form.attributes.nano_mode.value;
  
  var callback=function(reply)
  { 
    if(!schemas.ordonnance.data) schemas.ordonnance.data=[];
    schemas.ordonnance.data.push(reply[0]);
    schemas.ordonnance.ix=schemas.ordonnance.data.length-1;
    intra.ordonnance.choose(schemas.ordonnance.ix);
  };
  
  if(nano.form_validate(form))
    {
      json=nano.form_save(form);
      nano.ajax('data='+encodeURIComponent(JSON.stringify(json)),'ajax_save_ordonnance.php?',callback);  
    }
    else
    {
      return false;
    }

}

intra.ordonnance.new=function()
{
    var form=document.ordonnance_edit_form;
    
    $('#ordonnance_edit_popup').modal('show');
    form.reset();
    nano.form_prepare(form,'new',{});
    
    // set the defaut values
    form.mailing.checked=true;
    form.okmailingaudio.checked=true;
    form.okemailing.checked=true;
    form.acp.checked=true;
    
    
}







