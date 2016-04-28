$(document).ready(function() { 
    jQuery('#jform_menu_type').change(function () {
        var jform_menu_type=jQuery('#jform_menu_type').val();
        jQuery('#imgwaitting').css({display:'inline'});           
        jQuery('#menuitem').css({display:'none'});           
        jQuery.post(site_url+"index.php?option=com_onepage&task=getMenu&format=raw", { jform_menu_type: jform_menu_type },
       function(data) {   
           var ojb = jQuery.parseJSON(data); 
           if (ojb.status) {
               jQuery('#imgwaitting').css({display:'none'});
               jQuery('#menuitem').css({display:'block'}); 
               jQuery('#select-menu-item').html(ojb.select);  
           } 
           else {
               jQuery('#jform_menu_type').css({border:'solid 1px #ff0000'});
           }       
       });    
    });     
//var content = $("#contentmenu").val(); 
//$('#myIframe').contents().find('body').html(content);  
    jQuery('#customdom').hide();
    jQuery( "#custom" ).click(function() {
        jQuery( "#customdom" ).toggle( "slow", function() {
        });
    });        
});

function loadContentmenu() {
    jQuery('#jform_menu_id').change(function () {
        var jform_menu_id=jQuery('#jform_menu_id').val();
        jQuery('#imgwaitting1').css({display:'inline'});           
        jQuery('#menu-content').css({display:'none'});           
        jQuery.post(site_url+"index.php?option=com_onepage&task=getMenuitem&format=raw", { jform_menu_id: jform_menu_id },
       function(data) {   
           var ojb = jQuery.parseJSON(data); 
           if (ojb.status) {
               jQuery('#imgwaitting1').css({display:'none'});
               jQuery('#menu-content').css({display:'block'}); 
               jQuery('#content-area').html(ojb.value);  
               jQuery('#menulink').val(ojb.link);  
           } 
           else {
               jQuery('#jform_menu_id').css({border:'solid 1px #ff0000'});
           }       
       });    
    });         
}
 function inspectItem() {
          
          var iframe = jQuery('iframe'); // or some other selector to get the iframe     
          var head = iframe.contents().find("head");             
              head.append(jQuery("<link/>", { rel: "stylesheet", href: site_url+"components/com_onepage/assets/css/iframe-inspect.css", type: "text/css" }));
                      
          // Hight the selected elements -- Get from the database selector
            
            var content = jQuery("#contentmenu").val();      
            if(content) {
                var content_selector = iframe.contents().find(content);  
                content_selector.toggleClass('outline-element-clicked'); 
            }         
               
            jQuery('body', iframe.contents()).mouseover(function(event) {
              jQuery(event.target).addClass('outline-element');
               jQuery('a', event.target).click(false);
            })
            .mouseout(function(event) {
              jQuery(event.target).removeClass('outline-element');
            })
            .click(function(event) {                
                jQuery(event.target).removeClass('outline-element');     
                var elem=  jQuery(event.target).get(0);      
                var continueCss = 1,css = "", kclast = '';
                var i=0;
               // var adddottoclass = elem.className.split(' ').join(' .');           
               //console.log(elem);
                
                while (i<5) {                          
                    if (continueCss) {
                        if(elem.id) {
                            css = elem.nodeName + '#' + elem.id + " " + css;
                            continueCss = 0;
                        } else if(elem.className) {
                            var splitClass = elem.className.split(' ');   
                            if(splitClass) kclass = splitClass[0];
                            else kclass = elem.className;                               
                            css = elem.nodeName + '.' + kclass + " " + css;
                        } else {
                            css = elem.nodeName + " " + css;
                        }
                    }  
                       css = css.toLowerCase();
                       css = css.replace(/ $/, "");
                       elem = elem.parentNode; 
                    i++;
                      
                }   
     
                //$('#contentmenu').val(css); 

                
             jQuery(event.target).toggleClass('outline-element-clicked');  
             if(jQuery(event.target).hasClass('outline-element-clicked')){
              //  if(content) $('#contentmenu').val(content+', '+css); 
               // else $('#contentmenu').val(css);  
                var currentHtml= jQuery("#contentmenu").val();        
                var arr = currentHtml.split(',');   
                var removeItem  = css.replace('.outline-element-clicked', '');

                arr = jQuery.grep(arr, function(value) {
                    return value != removeItem;
                });
                module= arr.toString();
                if(module) jQuery("#contentmenu").val(module+','+css);               
                else jQuery("#contentmenu").val(css);   
              }            
              else {
                var currentHtml= jQuery("#contentmenu").val();        
                var arr = currentHtml.split(',');   
                var removeItem  = css.replace('.outline-element-clicked', '');
               // var removeItem  = css;
               //console.log(removeItem);
                arr = jQuery.grep(arr, function(value) {
                    return value != removeItem;
                });
                module= arr.toString(); 
                jQuery("#contentmenu").val(module);                   
              }

            });   
 }
 
 function preview(link,value) {
    jQuery('iframe').hide();  
    jQuery('#preview').load(link+' '+value).css({  
            "background-color": "#ffff99",
            "padding":"10px"
        });
    jQuery('#preview').show(); 
    jQuery('#preview').click(false);  
 }
 function original() {
    jQuery('iframe').show();
    jQuery('#preview').hide(); 
 }
  
 