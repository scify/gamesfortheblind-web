<?php
/*------------------------------------------------------------------------
# com_onepage - XIPAT Onepage component
# version: 1.0
# ------------------------------------------------------------------------
# author    Nguyen Huy Kien - www.xipat.com
# copyright Copyright (C) 2013 www.xipat.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.xipat.com
# Technical Support:  http://www.xipat.com/support.html
-------------------------------------------------------------------------*/
    defined('_JEXEC') or die;    
    ?>
<a style="position: absolute; top: 0;" id="home" name="home-link"></a>
<link rel="stylesheet" href="<?php echo JURI::base(); ?>components/com_onepage/assets/style.css" type="text/css" media="screen,projection"  />
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>     

    <?php
    $menu = JFactory::getApplication()->getMenu();
    $item = $menu->getItem($menu->getActive()->id);
    $this->includeShortcode();      
    if($this->pageitem->code) {
        if($this->params->get('show_menu', 1)): 
            echo $this->loadTemplate('menu'); 
        endif; 
        echo $this->do_xpshortcode($this->pageitem->code);
    } 
    else echo 'No item';
?> 
<script type="text/javascript">  
     $( document ).ready(function() {
        // Scroll fixed menu
        $(function () {
            var msie6 = $.browser == 'msie' && $.browser.version < 7;
            if (!msie6) {
                var top = $('#onepage-nav').offset().top;
                $(window).scroll(function (event) {
                    //var y = jQuery(this).scrollTop() - 400;
                    var y = $(this).scrollTop();
                    if (y >= top) {
                        $('#onepage-nav').addClass('fixed');
                    } else {
                        $('#onepage-nav').removeClass('fixed');
                    }
                });
            }  
        });
       }); 

    // Active menu
    $(function(){
        var sidebar = $('#onepage-nav');  // cache sidebar to a variable for performance
        sidebar.delegate('li','click',function(){
            sidebar.find('.active').toggleClass('active');
            $(this).toggleClass('active');
        });
    });    

    function goToByScroll(id){
        jQuery('html,body').animate({scrollTop: jQuery("#"+id).offset().top},1000);
    } 

// Cache selectors
var lastId,
    topMenu = $("#onepage-nav"),
    topMenuHeight = topMenu.outerHeight()+15,
    // All list items
    menuItems = topMenu.find("a"),
    // Anchors corresponding to menu items
    scrollItems = menuItems.map(function(){
      var item = $($(this).attr("href"));
      if (item.length) { return item; }
    });

// Bind click handler to menu items
// so we can get a fancy scroll animation
menuItems.click(function(e){
  var href = $(this).attr("href"),
     // offsetTop = href === "#" ? 0 : $(href).offset().top-topMenuHeight+1;
      offsetTop = href === "#" ? 0 : $(href).offset().top+1;
  $('html, body').stop().animate({ 
      scrollTop: offsetTop
  }, 1000);
  e.preventDefault();
});

// Bind to scroll
$(window).scroll(function(){
   // Get container scroll position
   var fromTop = $(this).scrollTop()+topMenuHeight;
   
   // Get id of current scroll item
   var cur = scrollItems.map(function(){
     if ($(this).offset().top < fromTop)
       return this;
   });
   // Get the id of the current element
   cur = cur[cur.length-1];
   var id = cur && cur.length ? cur[0].id : "";
   
   if (lastId !== id) {
       lastId = id;
       // Set/remove active class
       menuItems
         .parent().removeClass("active")
         .end().filter("[href=#"+id+"]").parent().addClass("active");
   }                   
});

        $(function() {
            var pull         = $('#pull');
                menu         = $('nav ul');
                menuHeight    = menu.height();


            if($("#pull").is(":visible"))
            {
                $('nav ul a').click(function(){
                     menu.slideToggle();  
                });    
            }
            $(window).resize(function(){
                var w = $(window).width();
                if(w > 320 && menu.is(':hidden')) {
                    menu.removeAttr('style');
                }
            });
        });
        function mobilebutton() {   

                $('nav ul').slideToggle(); 
        }
</script> 