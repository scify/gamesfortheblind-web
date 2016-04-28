var arrayType;
var data_ele;
jQuery(document).ready(function(){
    addEventPopup();
    jQuery('#pageconfig-submit').click(function(){
        jQuery.stbox.close();
    });
    arrayType=jQuery.parseJSON(decodeURIComponent(jQuery('#st-arrName').attr("data")));
    if(jQuery('#st-page>li').length>0){
        jQuery('#st-page>li').each(function(){
            if(jQuery(this).hasClass("st-column")){
                jQuery(this).removeClass("st-column").addClass("st-column-container");
                AddColumn(this);
                data = jQuery.parseJSON(decodeURIComponent(jQuery(this).attr("data")));
                ColumnDesign(this,data.content);         
            }else if(jQuery(this).hasClass("st-tabs")){
                jQuery(this).removeClass("st-tabs").addClass("st-tabs-container");
                AddTab(this);
                data = jQuery.parseJSON(decodeURIComponent(jQuery(this).attr("data")));
                TabsDesign(this,data.content);
            }
        });
        AddEvent();
    }
    jQuery('#st-page li').removeClass("btn");
    addsort();
    jQuery( "#draggable li" ).draggable({
        connectToSortable: ".sortable",
        appendTo: "body",
        placeholder: 'placeholder',
        helper: "clone",
        revert: "invalid"
    });
    // Remove action button toolbar default Joomla
    jQuery("#toolbar button").removeAttr("onclick");
    // Add Event Save
    buttonsave();
    // Append Input Name to Toolbar
    jQuery(".namepage .st-namepage").appendTo("#toolbar");
    jQuery('#toolbar-cancel').click(function(){
        window.location="index.php?option=com_onepage";
    });
    setTitleModule();
    setTitlePageitem();
});

function makeid()
{
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < 5; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    return text;
}
// Add Event sorttable
function addsort(){
    jQuery( ".sortable" ).sortable({
        connectToSortable: "#draggable",
        connectWith:".sortable",
        appendTo: "body",
        placeholder: 'placeholder',
        revert: true,
        receive: function(event, ui) {
              AddEvent(); // add Event 
        },
        start: function( event, ui ) {
            if(jQuery(ui.item).hasClass("st-column-container")){
                jQuery(ui.item).find('.st-columns').hide();
                jQuery(ui.item).find('.btn-group').hide();
                jQuery(ui.item).css('height','40');
            }else if(jQuery(ui.item).hasClass("st-tabs-container")){
                 jQuery(ui.item).find('.st-tabitems').hide();
                 jQuery(ui.item).css('height','40');
            }
        },
        stop: function( event, ui ) {
            if(ui.item.hasClass("st-column")){
                jQuery(ui.item).removeClass("st-column").addClass("st-column-container");
                //jQuery(ui.item).find('.st-edit').remove();
                AddColumn(ui.item);
            }
            else if(ui.item.hasClass("st-tabs")){
                jQuery(ui.item).removeClass("st-tabs").addClass("st-tabs-container");
                AddTab(ui.item);
            }else if(ui.item.hasClass("st-column-container")){
                jQuery(ui.item).find('.st-columns').show();
                jQuery(ui.item).find('.btn-group').show();
                jQuery(ui.item).css('height','auto');
            }else if(jQuery(ui.item).hasClass("st-tabs-container")){
                jQuery(ui.item).find('.st-tabitems').show();
                jQuery(ui.item).css('height','auto');
            }
        }
    });
}

function addsortSub(){
    jQuery( ".sortable" ).sortable({
        connectToSortable: "#draggable",
        connectWith:".sortable",
        appendTo: "body",
        placeholder: 'placeholder',
        revert: true,
        receive: function(event, ui) {
              AddEvent(); // add Event 
        },
        stop: function( event, ui ) {
            if(ui.item.hasClass("st-column")){
                jQuery(ui.item).remove();
                alert('no support');
            }
            ui.item.removeClass("btn");
        }
    });
}


function addEventPopup(){
    jQuery('.stmodal').stbox({
        padding     :0,
        openEffect    : 'elastic',
        closeEffect    : 'elastic',
        helpers : {
            overlay:{
                closeClick :false,
                speedOut   : 200
            },
            title:null
        },
        closeBtn : false,
        autoSize : false,
        beforeLoad  :function(){
            var typepopup =jQuery(this.element).attr("data-type");
            switch(typepopup){
                case "columns_desi":
                    this.width=300;
                    this.height=300;
                    break;
                case "moduleid_desi":
                    this.width=260;
                    this.height=300;
                    break;
                case "pageitem_desi":
                    this.width=260;
                    this.height=300;
                    break;                    
                case "divider_desi":
                    this.width=300;
                    this.height=242;
                    break;
            }
        },
        afterLoad:function(){
            var typepopup =jQuery(this.element).attr("data-type");
            switch(typepopup){
                case "moduleid_desi":
                    jQuery('#st-element-modules').appendTo(".st-navigation .st-listmodule");
                    checkList('id','#jform_modules');
                    break;
                case "pageitem_desi":
                    jQuery('#st-element-pageitem').appendTo(".st-navigation .st-listpageitem");
                    checkList('id','#jform_pageitem');
                    break;                    

            }
            jQuery(this.content).find('.btn-cancel').click(function(){
                jQuery.stbox.close();
            });
            jQuery(this.content).find('.st-nav-title .icon-cancel-circle').click(function(){
                jQuery.stbox.close();
            });
        },
        beforeClose:function(){
            var typepopup =jQuery(this.element).attr("data-type");
            switch(typepopup){
                case "moduleid_desi":
                    jQuery(this.content).find('#st-element-modules').appendTo("#st-element");
                    break;
                case "pageitem_desi":
                    jQuery(this.content).find('#st-element-pageitem').appendTo("#st-element");
                    break;                    
            }
            jQuery('#st-navigation').html("");
        }
    });
}



// AddEvent 
function AddEvent(){
    // add event data
    jQuery('#st-page li .st-edit').bind('click',function(){
        jQuery('#st-page li').removeClass("st-focus");
        select = jQuery(this).closest('li');
        select.addClass("st-focus");
        data_ele = jQuery.parseJSON(decodeURIComponent(select.attr("data")));
        switch(data_ele.type){
            case "moduleid_desi":
                getModuleOption(data_ele);
                break;
            case "pageitem_desi":
                getPageitemOption(data_ele);
                break;                
            case "divider_desi":
                getDividerOption(data_ele);
                break;
            case "columns_desi":
                getColumDesignOption(data_ele);
                break;
            default:
                break;
        }
    });
    //add event close item
    jQuery('#st-page .st-close').bind('click',function(e){
        jQuery(this).closest('li').remove();
    });

    //add event close item
    jQuery('#st-page .st-hide').toggle(function(){
        var sele =jQuery(this).closest('li');
        sele.find('.st-columns').addClass('none');
        sele.find('.column-button-group').addClass("none");
        sele.find('.st-tabitems').addClass("none");
    },function(){
        var sele =jQuery(this).closest('li');
        sele.find('.st-columns').removeClass('none');
        sele.find('.column-button-group').removeClass("none");
        sele.find('.st-tabitems').removeClass("none");
    });
}


function checkList(id,sle){
    if(typeof(data_ele.attr[id])!='undefined'){
        jQuery('.st-navigation '+sle).val(data_ele.attr[id]);
        jQuery('.st-navigation  '+sle+' option[value='+data_ele.attr[id]+']').attr('selected','selected');
        var sele = jQuery('.st-navigation  '+sle+' option[value='+data_ele.attr[id]+']').text();
        jQuery('.st-navigation .chzn-container .chzn-single>span').text(sele);
        jQuery('.st-navigation .chzn-container .chzn-results li').removeClass("result-selected");
        jQuery('.st-navigation .chzn-container .chzn-results li').each(function(){
            if(jQuery(this).text()==sele){
                jQuery(this).addClass("result-selected ");
            }
        });
    }
}

// AddColumn
function AddColumn(item){        
    data = jQuery.parseJSON(decodeURIComponent(jQuery(item).attr("data")));
    content ='<div class="row-fluid st-columns">';
    for(i=0;i<data.content.length;i++){
        content+='<div class="span'+data.content[i].attr.col+'" index="'+i+'">';
        content+='<span class="st-changerow" href="#st-navigation" index="'+i+'"><i class="icon-cog-2"></i></span>';
        content+='<ul class="sortable sortable-column"></ul>';
        content+='</div>';
    }
    content+='</div>';    
    content+='<div class="btn-group column-button-group">';
        for(i=1;i<=6;i++)
            content+='<span class="btn '+((i==data.content.length)?'btn-primary':'')+'">'+i+'</span>';
    content+='</div>';
    jQuery(item).append(content);
    changeColumn();
    addsort();
    changeSpan();
}

function changeSpan(){
    jQuery('.st-changerow').stbox({
        padding     : 0,
        openEffect    : 'elastic',
        closeEffect    : 'elastic',
        helpers : {
            overlay:{
                  closeClick :false,
                  speedOut   : 200
            },
            title:null
        },
        closeBtn : false,
        autoSize : false,
        beforeLoad:function(){
            this.width=300;
            this.height=250;
            var sele = jQuery(this.element).closest(".st-column-container");
            var data = jQuery.parseJSON(decodeURIComponent(sele.attr("data")));
            var index = jQuery(this.element).attr("index");
            content ="<div class='st-nav-title'><i class='icon-columns'></i> Column<span class='st-close' title='Close'><i class='icon-cancel-circle'></i></span></div>";
            content+="<div class='st-nav-option'>";
                content+="<label>Column:</label>";
                content+="<div class='row-fluid'><select class='st-column span12'>";
                for(i=1;i<=12;i++){
                    content+='<option value="'+i+'">span'+i+'</option>';
                }
                content+="</select></div>";
                content+="<label>Class:</label>";
                content+="<div class='row-fluid'><input type='text' class='st-spanclass span12' value='"+((data.content[index].attr.class==null)?"":data.content[index].attr.class)+"'></div>";
                content+="<div class='st-button-group'>";
                    content+="<button class='btn btn-primary'>Save</button>";
                    content+="<button class='btn btn-cancel'>Cancel</button>";
                content+="</div>";
            content+="</div>";
            jQuery('.st-navigation').html(content);
            jQuery('.st-navigation .st-column option[value="'+data.content[index].attr.col+'"]').attr("selected","selected");
            jQuery('.st-navigation .btn-cancel').click(function(){
                jQuery.stbox.close();
            });
            jQuery('.st-navigation .icon-cancel-circle').click(function(){
                jQuery.stbox.close();
            });
            jQuery('.st-navigation .btn-primary').click(function(){
                var s = jQuery('.st-navigation .st-column').val();
                data.content[index].attr.col = s;
                data.content[index].attr.class=jQuery('.st-navigation .st-spanclass').val();
                sele.attr("data",encodeURIComponent(JSON.stringify(data)));
                sele.find('div[index="'+index+'"]').attr("class","").addClass("span"+s);
                jQuery.stbox.close();
            });
        }
    });
}

function changeColumn(){
    jQuery('.column-button-group span.btn').bind('click',function(){
        if(!jQuery(this).hasClass("btn-primary")){
            var sl = jQuery(this).closest('div');
            sl.find('span.btn').removeClass("btn-primary");
            jQuery(this).addClass("btn-primary");
            var col = jQuery(this).text();
            var data = jQuery.parseJSON(decodeURIComponent(jQuery(jQuery(this).closest("li")).attr("data")));
            
            var loop = true;
            if(col==5) classspan=2;
            else classspan=(12/col);
            sl.prev().find('[class^="span"]').attr("class","span"+classspan);
            var len = data.content.length;
            if(col>data.content.length){
                // Add column
                for(i=0;i<(col-len);i++){
                    var index = sl.prev().find('>[class^="span"]').length;
                    sl.prev().append("<div class='span"+classspan+"' index='"+index+"'><span class='st-changerow' href='#st-navigation' index='"+index+"'><i class='icon-cog-2'></i></span><ul class='sortable sortable-column'></ul></div>");
                    arr = {"type":"column_item_desi","content":"","attr":{"col":classspan}};
                    data.content.push(arr);
                }
            }else{
                //remove column
                for(i=0;i<(len-col);i++){
                    sl.prev().find('[class^="span"]:last-child').remove();
                    data.content.pop();
                }
            }
            for(i=0;i<data.content.length;i++){
                if(i==4 && data.content.length==5) data.content[i].attr.col=4;
                else data.content[i].attr.col=classspan;
            }
            if(col==5) sl.prev().find('>[index^="4"]').attr("class","span4");
            sl.parent().attr("data",encodeURIComponent(JSON.stringify(data)));
            addsort();
        }
    });
}

function getColumDesignOption(data){
    content ="<div class='st-nav-title'><i class='icon-columns'></i> Column<span class='st-close' title='Close'><i class='icon-cancel-circle'></i></span></div>";
    content+="<div class='st-nav-option st-column'>";
        content+="<label>ID:</label>";
        content+="<div class='row-fluid'>";
            content+="<input type='text' class='column-id' value='"+((data.attr.id==null)?"":data.attr.id)+"' />";
        content+="</div>";
        content+="<label>Class:</label>";
        content+="<div class='row-fluid'>";
            content+="<input type='text' class='column-class' value='"+((data.attr.class==null)?"":data.attr.class)+"' />";
        content+="</div>";
        content+="<label>Full Width:</label>";
        content+="<div class='row-fluid'><div class='st-fullwidth st-radio'>"; 
            content+='<label class="radio inline"><input type="radio" name="st-fullwidth" value="1" checked="checked">Yes</label>';
            content+='<label class="radio inline"><input type="radio" name="st-fullwidth" value="0">No</label>';
        content+="</div></div>";
        content+="<div class='st-button-group'>";
        content+="<button class='btn btn-primary'>Save</button>";
        content+="<button class='btn btn-cancel'>Cancel</button>";
        content+="</div>";
    content+="</div>";
    jQuery('.st-navigation').html(content);
    if(typeof data.attr.fullwidth !='undefined'){
        jQuery('#st-navigation .st-showtitle [value="'+data.attr.fullwidth+'"]').prop('checked', true);
    }
    jQuery('.st-nav-option button.btn-primary').click(function(){
        saveColumnDesign(data);
    });
}

function saveColumnDesign(data){
    data.attr.id = jQuery.trim(jQuery('#st-navigation .column-id').val());
    data.attr.class = jQuery.trim(jQuery('#st-navigation .column-class').val());
    data.attr.fullwidth = jQuery('#st-navigation .st-fullwidth input[type="radio"]:checked').val();
    var json = encodeURIComponent(JSON.stringify(data));
    jQuery('#st-page li.st-focus').attr("data",json);
    jQuery.stbox.close();
}

function ColumnDesign(sele,data){
    jQuery(sele).find('.sortable-column').each(function(index){
        for(i=0;i<data[index].content.length;i++){
            var html="";
            if(arrayType[data[index].content[i].type].type=="tabs_desi"){
                arrayType[data[index].content[i].type].class="st-tabs-container";
            }   
            html+="<li data-type='"+arrayType[data[index].content[i].type].type+"' class='ui-state-highlight btn ui-draggable "+arrayType[data[index].content[i].type].class+"' style='display:block;' data=\""+encodeURIComponent(JSON.stringify(data[index].content[i]))+"\">";
            html+="<div class='st-header-title'>";   
            html+="<span class='st-name'>"+arrayType[data[index].content[i].type].name+"</span> ";
            html+="<span href='#' class='st-close' title='Close'><i class='icon-cancel-circle'></i></span> ";
            html+="<span href='#st-navigation' data-type='"+arrayType[data[index].content[i].type].type+"' title='Edit' class='st-edit stmodal'><i class='icon-cog-2'></i></span>";
            if(arrayType[data[index].content[i].type].type=="tabs_desi"){
            html+='<span class="st-hide"><i class="icon-new-window"></i></span>';
            }
            html+="</div>";
            if(data[index].content[i].type=='tabs_desi'){
                var id = makeid();
                html+='<div class="st-tabitems">';
                    html+='<ul class="nav nav-tabs" id="myTab-'+id+'">';
                    for(j=0;j<data[index].content[i].content.length;j++){
                        html+='<li '+((j==0)?'class="active"':'')+'><a class="st-tab" href="#'+id+'-'+j+'" index="'+j+'" >'+data[index].content[i].content[j].attr.title;
                            html+='<input index="'+j+'" class="input-edittab" type="text" id="myTab-input-'+id+'-'+j+'" value="'+data[index].content[i].content[j].attr.title+'" />'
                            html+='<i class="icon-cancel-circle" title="close" index="#'+id+'-'+j+'"></i>';
                        html+='</a></li>';
                    }
                        html+='<li><a class="newtab" href="#"><i class=" icon-plus"></i></a></li>';
                    html+='</ul>';
                    html+='<div class="tab-content">';
                    for(j=0;j<data[index].content[i].content.length;j++){
                        html+='<div class="tab-pane '+((j==0)?'active':'')+'" id="'+id+'-'+j+'"><ul class="sortable sortable-tabitem">';
                        for(var k=0;k<data[index].content[i].content[j].content.length;k++){
                            html+="<li data-type='"+data[index].content[i].content[j].content[k].type+"' class='ui-state-highlight btn ui-draggable "+arrayType[data[index].content[i].content[j].content[k].type].class+"' style='display:block;' data='"+encodeURIComponent(JSON.stringify(data[index].content[i].content[j].content[k]))+"'>";
                                html+="<div class='st-header-title'>";
                                    html+="<span class='st-name'>"+arrayType[data[index].content[i].content[j].content[k].type].name+"</span> ";
                                    html+="<span href='#' class='st-close' title='Close'><i class='icon-cancel-circle'></i></span> ";
                                    html+="<span href='#st-navigation' data-type='"+data[index].content[i].content[j].content[k].type+"' title='Edit' class='st-edit stmodal'><i class='icon-cog-2'></i></span>";
                                html+="</div>";
                            html+="</li>";
                        }
                        html+='</ul></div>';
                    }
                    html+='</div>';
                html+='</div>';
            }
            html+="</li>";
            jQuery(this).append(html);
            jQuery('[id*="myTab"] a.newtab').click(function (e) {
                var array = jQuery(this).parent().prev().find('.input-edittab').attr('id').split("-");
                array[3]=(array[3]+1);
                jQuery(this).parent().before('<li class=""><a class="st-tab" href="#'+array[2]+'-'+array[3]+'"><span class="tabname">New Tab</span><input class="input-edittab" type="text" id="myTab-input-'+array[2]+'-'+array[3]+'" value="New Tab" /><i class="icon-cancel-circle" title="close" index="#'+array[2]+'-'+array[3]+'"></a></li>');
                jQuery(this).parent().parent().parent().find('.tab-content').append('<div class="tab-pane " id="'+array[2]+'-'+array[3]+'"><ul class="sortable sortable-tabitem ui-sortable"></ul></div>');
                addEventTab(array[2]);
                jQuery('#st-page #myTab-'+array[2]+' .input-edittab').hide();
                return false;
            });
            jQuery('[id*="myTab"] a.st-tab .icon-cancel-circle').click(function(){
                var sele = jQuery(this).attr('index');
                var count = jQuery(this).parent().parent().parent().find('>li').length;
                console.log(count);
                if(count==2){
                    alert('Keep minimum 1 tab');
                    return false;
                }else{
                    jQuery(sele).remove();
                    jQuery(this).parent().parent().remove();
                    return false;
                }
            });
            jQuery('[id*="myTab"] a.st-tab').click(function (e) {
                e.preventDefault();
                jQuery(this).tab('show');
            });
            jQuery('[id*="myTab"] [id*="myTab-input"]').hide();
        }
    });
}

//////////////////////////////// Button ////////////////////////////////////
function appendDataColumn(sele){
    if(!sele.hasClass("st-column-container")){
        arr = [];
        sele.each(function(index){
            arr.push(jQuery.parseJSON(decodeURIComponent(jQuery(this).attr("data"))));
        });
        return arr;
    }else{
        data = jQuery.parseJSON(decodeURIComponent(jQuery(this).attr("data")));
        jQuery(this).find('.sortable-column').each(function(){
            var a = appendDataColumn(jQuery(this).find('>li'));
            data.content[index].content.push(a);
        });
        return data;
    }
}

function buttonsave(){
    jQuery("#toolbar-apply").click(function(){
        jQuery('#system-message-container').html("");   
        name=jQuery("#jform_title").val();
        desc=jQuery("#jform_description").val();
        if(name==""){
            alert("Name Page is not empty !");
            jQuery("#jform_title").focus();
            return false;
        }
        arr = [];
        jQuery('#st-page>li').each(function(){
            if(jQuery(this).hasClass("st-column-container")){
                data = jQuery.parseJSON(decodeURIComponent(jQuery(this).attr("data")));
                jQuery(this).find('.sortable-column').each(function(index){
                    var a = [];
                    jQuery(this).find('>li').each(function(){
                        if(jQuery(this).attr("data-type")=='tabs_desi'){
                            jQuery(this).attr("data",saveTabOption(jQuery(this),jQuery.parseJSON(decodeURIComponent(jQuery(this).attr("data")))));
                        }
                        a.push(jQuery.parseJSON(decodeURIComponent(jQuery(this).attr("data"))));
                    });
                    data.content[index].content = a;
                });
                jQuery(this).attr("data",encodeURIComponent(JSON.stringify(data)));
            }else if(jQuery(this).hasClass("st-tabs-container")){
                data = jQuery.parseJSON(decodeURIComponent(jQuery(this).attr("data")));
                jQuery(this).attr("data",saveTabOption(jQuery(this),data));
            }
            arr.push(jQuery(this).attr('data'));
        });
        json = JSON.stringify(arr);  

        jQuery.ajax({
            type:"POST",
            url:"index.php?option=com_onepage&task=page.xpSave&tmpl=component",
            data:{data:json,id:jQuery('#stid').val(),name:name,desc:desc},
            dataType : 'html',
            beforeSend :function(){
                ajaxbeforesend();
            },
            success:function(response){
                ajaxsuccess(response);
            }
        });
    });
jQuery("#toolbar-save").click(function(){
        jQuery('#system-message-container').html("");   
        name=jQuery("#jform_title").val();
        desc=jQuery("#jform_description").val();
        if(name==""){
            alert("Page title empty!");
            jQuery("#jform_title").focus();
            return false;
        }
        arr = [];
        jQuery('#st-page>li').each(function(){
            if(jQuery(this).hasClass("st-column-container")){
                data = jQuery.parseJSON(decodeURIComponent(jQuery(this).attr("data")));
                jQuery(this).find('.sortable-column').each(function(index){
                    var a = [];
                    jQuery(this).find('>li').each(function(){
                        if(jQuery(this).attr("data-type")=='tabs_desi'){
                            jQuery(this).attr("data",saveTabOption(jQuery(this),jQuery.parseJSON(decodeURIComponent(jQuery(this).attr("data")))));
                        }
                        a.push(jQuery.parseJSON(decodeURIComponent(jQuery(this).attr("data"))));
                    });
                    data.content[index].content = a;
                });
                jQuery(this).attr("data",encodeURIComponent(JSON.stringify(data)));
            }else if(jQuery(this).hasClass("st-tabs-container")){
                data = jQuery.parseJSON(decodeURIComponent(jQuery(this).attr("data")));
                jQuery(this).attr("data",saveTabOption(jQuery(this),data));
            }
            arr.push(jQuery(this).attr('data'));
        });
        json = JSON.stringify(arr);  

        jQuery.ajax({
            type:"POST",
            url:"index.php?option=com_onepage&task=page.xpSave&tmpl=component",
            data:{data:json,id:jQuery('#stid').val(),name:name,desc:desc},
            dataType : 'html',
            beforeSend :function(){
                ajaxbeforesend();
            },
            success:function(response){
                ajaxsuccess(response);   
            }
        });
        setTimeout(function() {
          window.location="index.php?option=com_onepage"; 
        }, 2000);        
    });        
}
function ajaxbeforesend(){
    var inputs = jQuery("#toolbar").find("input, select, button, textarea");
    inputs.prop("disabled", true);
    jQuery('#st-save-eff .icon-spinner').show();
    jQuery('#st-save-eff .message').hide();
    jQuery('#st-save-eff').show();
    jQuery('.st-design .st-items,.st-design .st-desi').css({opacity:0.6});
}

function ajaxsuccess(response){
    var inputs = jQuery("#toolbar").find("input, select, button, textarea");
    inputs.prop("disabled", false);
    jQuery('#st-save-eff .icon-spinner').hide();
    jQuery('#st-save-eff .message').fadeIn(200);
    var wait = window.setTimeout( function(){
            jQuery('#st-save-eff').fadeOut(400);
            jQuery('.st-design .st-items,.st-design .st-desi').css({opacity:1});
        },1000
    );
}

// Get Option Module
function getModuleOption(data){
    content ="<div class='st-nav-title'><i class='icon-qrcode-2'></i> Module<span class='st-close' title='Close'><i class='icon-cancel-circle'></i></span></div>";
    content+="<div class='st-nav-option st-module'>";
        content+="<div class='row-fluid'>";
            content+="<div class='span12'>";
                content+="<label>Choose:</label>";
                content+="<div class='st-listmodule'></div>";
            content+="</div>";
        content+="</div>";
        content+="<label>Show title:</label>";
        content+="<div class='row-fluid'><div class='st-showtitle st-radio'>"; 
            content+='<label class="radio inline"><input type="radio" name="st-title" value="0" checked="checked">No</label>';
            content+='<label class="radio inline"><input type="radio" name="st-title" value="1">Yes</label>';
        content+="</div></div>";
        content+="<label>Module Class Suffix:</label>";
        content+="<div class='row-fluid'><input type='text' class='st-moduleclass span12' value='"+((data.attr.moduleclass==null)?"":data.attr.moduleclass)+"'></div>";
        content+="<div class='st-button-group'>";
        content+="<button class='btn btn-primary'>Save</button>";
        content+="<button class='btn btn-cancel'>Cancel</button>";
        content+="</div>";
    content+="</div>";
    jQuery('.st-navigation').html(content);
    if(typeof data.attr.showtitle !='undefined'){
        jQuery('#st-navigation .st-showtitle [value="'+data.attr.showtitle+'"]').prop('checked', true);
    }
    jQuery('.st-nav-option button.btn-primary').click(function(){
        saveModule();
    });
}

function saveModule(){
    var title = jQuery('#st-navigation #jform_modules_chzn>a>span').text();
    jQuery('#st-page .st-focus .st-header-title .st-name').find('.st-modulename').remove();
    jQuery('#st-page .st-focus .st-header-title .st-name').append(' <span class="st-modulename">'+title+'</span>');
    // validate
    if(jQuery('#st-navigation #jform_modules').val()==0){
        alert('Please select a module !');
        return false;
    }
    if(testspecialclass(jQuery('#st-navigation .st-moduleclass').val())){
        data = {"type":"moduleid_desi","content":"","attr":{"id":jQuery('.st-nav-option #jform_modules').val(),"title":title}};
        data.attr.showtitle=jQuery('#st-navigation .st-showtitle [type="radio"]:checked').val();
        data.attr.moduleclass = jQuery('#st-navigation .st-moduleclass').val();
        var json = encodeURIComponent(JSON.stringify(data));
        jQuery('#st-page li.st-focus').attr("data",json);
        jQuery.stbox.close();
    }else{
        alert('No special characters allowed.');
        return false;
    }
}

function setTitleModule(){
    jQuery('#st-page li').each(function(){
        if(jQuery(this).hasClass('st-module')){
            var title = jQuery.parseJSON(decodeURIComponent(jQuery(this).attr('data'))).attr.title;
            jQuery(this).find('.st-header-title .st-name').append(' <span class="st-modulename">'+title+'</span>');
        }
    });
}
// End Option Module   
// Get Option page item
function getPageitemOption(data){
    content ="<div class='st-nav-title'><i class='icon-qrcode-2'></i> Item<span class='st-close' title='Close'><i class='icon-cancel-circle'></i></span></div>";
    content+="<div class='st-nav-option st-pageitem'>";
        content+="<div class='row-fluid'>";
            content+="<div class='span12'>";
                content+="<label>Choose:</label>";
                content+="<div class='st-listpageitem'></div>";
            content+="</div>";
        content+="</div>";
        content+="<label>Show title:</label>";
        content+="<div class='row-fluid'><div class='st-showtitle st-radio'>"; 
            content+='<label class="radio inline"><input type="radio" name="st-title" value="0" checked="checked">No</label>';
            content+='<label class="radio inline"><input type="radio" name="st-title" value="1">Yes</label>';
        content+="</div></div>";
        content+="<div class='st-button-group'>";
        content+="<button class='btn btn-primary'>Save</button>";
        content+="<button class='btn btn-cancel'>Cancel</button>";
        content+="</div>";
    content+="</div>";
    jQuery('.st-navigation').html(content);
    if(typeof data.attr.showtitle !='undefined'){
        jQuery('#st-navigation .st-showtitle [value="'+data.attr.showtitle+'"]').prop('checked', true);
    }
    jQuery('.st-nav-option button.btn-primary').click(function(){
        savePageitem();
    });
}

function savePageitem(){
    var title = jQuery('#st-navigation #jform_pageitem_chzn>a>span').text();
    jQuery('#st-page .st-focus .st-header-title .st-name').find('.st-pageitemtitle').remove();
    jQuery('#st-page .st-focus .st-header-title .st-name').append(' <span class="st-pageitemtitle">'+title+'</span>');
    // validate
    if(jQuery('#st-navigation #jform_pageitem').val()==0){
        alert('Please select a item!');
        return false;
    }
    if(testspecialclass(jQuery('#st-navigation .st-moduleclass').val())){
        data = {"type":"pageitem_desi","content":"","attr":{"id":jQuery('.st-nav-option #jform_pageitem').val(),"title":title}};
        data.attr.showtitle=jQuery('#st-navigation .st-showtitle [type="radio"]:checked').val();
        data.attr.moduleclass = jQuery('#st-navigation .st-pageitem').val();
        var json = encodeURIComponent(JSON.stringify(data));
        jQuery('#st-page li.st-focus').attr("data",json);
        jQuery.stbox.close();
    }else{
        alert('No special characters allowed.');
        return false;
    }
}


function setTitlePageitem(){
    jQuery('#st-page li').each(function(){
        if(jQuery(this).hasClass('st-pageitem')){
            var title = jQuery.parseJSON(decodeURIComponent(jQuery(this).attr('data'))).attr.title;
            jQuery(this).find('.st-header-title .st-name').append(' <span class="st-pageitemtitle">'+title+'</span>');
        }
    });
}

// End Option page item

//Get Option Divider
function getDividerOption(data){
    content ="<div class='st-nav-title'><i class='icon-minus-2'></i> Divider<span class='st-close' title='Close'><i class='icon-cancel-circle'></i></span></div>";
    content+="<div class='st-nav-option'>";
        content+="<label>Style:</label>";
        content+="<div class='row-fluid'><select class='st-style span12'>"; 
            content+='<option value="none">None</option>';
            content+='<option value="border">Border</option>';
        content+="</select></div>";
        content+="<label>Margin:</label>";
        content+="<div class='row-fluid'><input type='text' class='st-margin span12' value='"+data.attr.margin+"' placeholder='Margin'><div>";
        content+="<div class='st-button-group'>";
        content+="<button class='btn btn-primary'>Save</button>";
        content+="<button class='btn btn-cancel'>Cancel</button>";
        content+="</div>";
    content+="</div>";
    jQuery('.st-navigation').html(content);
    jQuery('.st-navigation .st-style option[value="'+data.attr.style+'"]').attr("selected","selected");
    jQuery('.st-navigation .st-margin').val(data.attr.margin);
    jQuery('.st-nav-option button.btn-primary').click(function(){
        saveDivider();
    });
}

function saveDivider(){
    var style = jQuery('.st-navigation .st-style').val();
    var margin = jQuery('.st-navigation .st-margin').val();
    if(testNumber(margin)){
        data = {"type":"divider_desi","content":" ","attr":{"style":style,"margin":margin}};
        var json = encodeURIComponent(JSON.stringify(data));
        jQuery('#st-page li.st-focus').attr("data",json);
        jQuery.stbox.close();
    }else{
        alert('Margin must be number format.');
        return false;
    }
}

//End Divider

function convertHTML(str)
{
    str = str.replace(/&/g, "&amp;");
    str = str.replace(/>/g, "&gt;");
    str = str.replace(/</g, "&lt;");
    str = str.replace(/"/g, "&quot;");
    str = str.replace(/'/g, "&#039;");
    return str;
}
function testNumber(text){
    var reg = /^\d+$/;
    return reg.test(text);
}
function testEmpty(sele){
    if(jQuery.trim(sele.val())==""){
        return true;
    }else{
        return false;
    }
}
function testspecialclass(text){
    var reg = /^\s*[a-zA-Z0-9\_\-\s]*\s*$/;
    return reg.test(text);
}
function testspecial(text){
    var reg = /^\s*[a-zA-Z0-9,\s]*\s*$/;
    return reg.test(text);
}
function testpath(text){
    var reg = /^[a-zA-Z0-9\/]*$/;
    return reg.test(text);
}