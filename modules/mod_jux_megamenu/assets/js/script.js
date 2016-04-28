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
        function jux_sticky () {
            if(window.innerWidth > 1024){
                 $window = $(window);      
                    var lastpos     = $(window).scrollTop(),
                        mainnav     = $('#js-mainnav'),
                        header      = $('header');
                    if(mainnav.length){  
                        elmHeightMainnav = mainnav.outerHeight(true);
                        elmHeightHeader  = header.outerHeight(true);
                        elmHeight        = elmHeightHeader + elmHeightMainnav; 
                        $(window).scroll(function() {   
                            if(window.innerWidth > 1024){
                                var scrolltop = $(window).scrollTop();
                                if(lastpos > elmHeight){
                                    if(!mainnav.hasClass('affix')) {
                                        mainnav.addClass('affix');    
                                    }
                                } else if(scrolltop <= elmHeight) {
                                    mainnav.removeClass('affix');
                                }
                                lastpos = scrolltop;
                           }else{
                                $('#js-mainnav').removeClass('affix');
                           }
                        });  
                }
                new Headroom(document.querySelector("#js-mainnav"), {
                    tolerance: 10,
                    offset : 205,
                    classes: {
                      initial: "animated"
                    }
                }).init();
            }else{
                $('#js-mainnav').removeClass('affix');
            }
        }
        jux_sticky();
        $(window).resize( function(){
            jux_sticky();
        });
    });

}(jQuery);
