/**
 * @version     $Id$
 * @author      JoomlaUX!
 * @package     Joomla.Site
 * @subpackage  mod_jux_megamenu
 * @copyright   Copyright (C) 20013 - 2015 by JoomlaUX. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl.html GNU/GPL version 3
*/
!function($){   
    $(document).ready(function(){
        if(window.innerWidth > 1024){
        //alert(window.innerWidth) ;
            // Cache the Window object
            $window = $(window);

             // Add class for mainmenu when scroller
            (function(){
                    
                var lastpos     = $(window).scrollTop(),
                    mainnav     = $('#js-mainnav'),
                    widthcontent= $('.container').outerWidth(false),
                    header      = $('header');

                if(mainnav.length){  

                    elmHeightMainnav = mainnav.outerHeight(true);
                    elmHeightHeader  = header.outerHeight(true);
                    elmHeight        = elmHeightHeader + elmHeightMainnav; 
                    $(window).scroll(function() {
                        //ignore when offcanvas open => leave unchanged
                       
                       

                        var scrolltop = $(window).scrollTop();
                        // if(scrolltop <= lastpos && lastpos > elmHeight){
                        if(lastpos > elmHeight){
                            if(!mainnav.hasClass('affix')) {
                                mainnav.addClass('affix');
                                $('#megamenucss .js-megamenu').css({"width":widthcontent+"px"});
                                $('.js-megamenu').addClass('container_menu');
                               
                                
                            }

                        } else if(scrolltop <= elmHeight) {
                            mainnav.removeClass('affix');
                            $('.js-megamenu').removeClass('container_menu');
                            $('#megamenucss .js-megamenu').css({"width":"auto"});
                            //$('#js-mainnav').removeClass('fadeInDown');
                            //mainnav.addClass('affix');
                        }
                        lastpos = scrolltop;

                    })
                }


                new Headroom(document.querySelector("#js-mainnav"), {
                    tolerance: 10,
                    offset : 205,
                    classes: {
                      initial: "animated",
                      // pinned: "fadeInDown",
                      // unpinned: "fadeOutUp"
                    }
                }).init();
                
            })();


         }

    });

}(jQuery);
