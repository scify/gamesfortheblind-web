window.addEvent('load', function () {
    new JCaption('img.caption');
});
if (typeof RokBoxSettings == 'undefined') RokBoxSettings = {pc: '100'};
jQuery(document).ready(function ($) {
    $(document.body).append('<a href="#" class="scrollToTop">Go To Top</a>');
    //Check to see if the window is top if not then display button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.scrollToTop').fadeIn();
        } else {
            $('.scrollToTop').fadeOut();
        }
    });

    //Click event to scroll to top
    $('.scrollToTop').click(function () {
        $('html, body').animate({scrollTop: 0}, 1000);
        return false;
    });
    jQuery('.scrollTo').click(function (event) {
        var pageurl = window.location.href.split('#');
        var linkurl = $(this).attr('href').split('#');

        if ($(this).attr('href').indexOf('#') != 0
            && (($(this).attr('href').indexOf('http') == 0 && pageurl[0] != linkurl[0])
                || $(this).attr('href').indexOf('http') != 0 && pageurl[0] != 'https://gamesfortheblind.org/' + linkurl[0].replace('/', ''))
        ) {
            // here action is the natural redirection of the link to the page
        } else {
            event.preventDefault();
            $(this).scrolltock();
        }
    });

    $.fn.scrolltock = function () {
        var link = $(this);
        var page = jQuery(this).attr('href');
        var pattern = /#(.*)/;
        var targetEl = page.match(pattern);
        if (!targetEl.length) return;
        if (!jQuery(targetEl[0]).length) return;

        // close the menu hamburger
        if (link.parents('ul').length) {
            var menu = $(link.parents('ul')[0]);
            if (menu.parent().find('> .mobileckhambuger_toggler').length && menu.parent().find('> .mobileckhambuger_toggler').attr('checked') == 'checked') {
                menu.animate({'opacity': '0'}, function () {
                    menu.parent().find('> .mobileckhambuger_toggler').attr('checked', false);
                    menu.css('opacity', '1');
                });
            }
        }
        var speed = 1000;
        jQuery('html, body').animate({scrollTop: jQuery(targetEl[0]).offset().top + 0}, speed, scrolltock_setActiveItem());
        return false;
    }
    // Cache selectors
    var lastId,
        baseItems = jQuery('.scrollTo');
    // Anchors corresponding to menu items
    scrollItems = baseItems.map(function () {
        // if (! jQuery(jQuery(this).attr('href')).length) return;
        var pattern = /#(.*)/;
        var targetEl = jQuery(this).attr('href').match(pattern);

        if (targetEl == null) return;
        if (!targetEl[0]) return;
        if (!jQuery(targetEl[0]).length) return;
        var item = jQuery(targetEl[0]);
        if (item.length) {
            return item;
        }
    });
    // Bind to scroll
    jQuery(window).scroll(function () {
        scrolltock_setActiveItem();
    });

    function scrolltock_setActiveItem() {
        // Get container scroll position
        var fromTop = jQuery(this).scrollTop() - (0) + 2;

        // Get id of current scroll item
        var cur = scrollItems.map(function () {
            if (jQuery(this).offset().top < fromTop)
                return this;
        });
        // Get the id of the current element
        cur = cur[cur.length - 1];
        var id = cur && cur.length ? cur[0].id : '';

        if (lastId !== id) {
            lastId = id;
            // Set/remove active class
            baseItems.parent().parent().find('.active').removeClass('active');
            baseItems
                .parent().removeClass('active')
                .end().filter('[href$=#' + id + ']').parent().addClass('active');
        }
    }
}); // end of dom ready

jQuery(window).load(function () {
    // loop through the scrolling links to check if the scroll to anchor is needed on the page load
    jQuery('.scrollTo').each(function () {
        var pageurl = window.location.href;
        var linkurl = jQuery(this).attr('href');
        var pattern = /#(.*)/;
        var targetLink = linkurl.match(pattern);
        var targetPage = pageurl.match(pattern);

        if (targetLink == null) return;
        if (targetPage == null) return;
        if (!targetLink.length) return;
        if (!jQuery(targetLink[0]).length) return;

        if (jQuery(targetPage[0]).length && targetLink[0] == targetPage[0]) {
            jQuery(this).scrolltock();
        }
    });
});


(function () {
    /*----------------------------------------------------------------
-  Slideshow initialization
---------------------------------------------------------------- */
    var manualSlideChanged = false;
    var animationInProgress = false; //Prevent multiple animations at the same time
    var activeSlideIndex = 0;
    var nextSlideIndex;
    var $activeSlide;
    var $nextSlide;
    var heightTemp; //Dynamically calculate the height of the slideshow
    var slidesNumber = 1;
    var animationSpeed = 9999999999999999;
    var animationInterval = 9999999999999999;
    var enableDots = false;
    var enableArrows = false;
    var enableLinkedTitles = false;
    var textOverImages = true;
    var $accessibleSlideshow = jQuery('#accessible-slideshow-ID_261830873');

    //Slides initialization:
    jQuery('.accessible-slideshow .slide').css({
        'opacity': '0',
        'display': 'none'
    }).addClass('hidden-slide');
    jQuery('.accessible-slideshow .slide:first-child').css({
        'opacity': '1',
        'display': 'block'
    }).removeClass('hidden-slide').addClass('active-slide');

    //Style initialization
    jQuery('.default-layout .accessible-slideshow_arrow').css('display', 'block').css('opacity', '0');
    jQuery('.accessible-slideshow_dot-wrapper:first-child .accessible-slideshow_dot').addClass('activeDot');
    jQuery('.default-layout .text-over-images_true .slide-text').css('position', 'absolute').css('bottom', '0').css('left', '0');

    //Initialize dots style

    //Initialize arrow style

    /*----------------------------------------------------------------
-  Slideshow handler (slide change)
---------------------------------------------------------------- */
    //"requestedSlideIndex" values: -1 (show next slide) ; -2 (show previous slide) ; >0 (requested slide index)
    var nextSlideAnimation = function (requestedSlideIndex) {

        // If animation is still in progress -or- the requested slide is the same as the one showed, then do nothing.
        if (animationInProgress || requestedSlideIndex === activeSlideIndex) {
            return;
        }

        //Else, start the animation
        animationInProgress = true;

        /**
         * Get the next active slide index
         **/
        if (requestedSlideIndex == -2) { // "-2" = show previous slide
            nextSlideIndex = activeSlideIndex - 1;
            if (nextSlideIndex == -1) {
                nextSlideIndex = slidesNumber - 1;
            } // if beginning is reached, go to the last slide
        } else {
            if (requestedSlideIndex == -1) { // "-1" = show next slide
                nextSlideIndex = activeSlideIndex + 1;
                if (nextSlideIndex == slidesNumber) {
                    nextSlideIndex = 0;
                } // if end is reached, go to the first slide
            } else { // this case is active when a dot is clicked
                nextSlideIndex = requestedSlideIndex;
            }
        }

        /**
         * Get the current/next slides reverences
         **/
        $nextSlide = $accessibleSlideshow.find('.slide').eq(nextSlideIndex);
        $activeSlide = $accessibleSlideshow.find('.slide').eq(activeSlideIndex);

        /**
         * Update the "live-aria" attributes (only if the slideshow was changed manually)
         **/
        if (manualSlideChanged) {
            $accessibleSlideshow.find('.accessible-slideshow').removeAttr('aria-live');
            $activeSlide.removeAttr('aria-live');
            $nextSlide.attr('aria-live', 'rude');
        }

        /**
         * Toggle active/hidden slides
         **/
        $activeSlide
            .removeClass('active-slide').addClass('hidden-slide');
        $nextSlide
            .removeClass('hidden-slide').addClass('active-slide').css('display', 'block');

        /**
         * Animate the process:
         **/
        //Active slide fade-in
        $activeSlide
            .animate({'opacity': '0'}, {
                queue: true,
                duration: animationSpeed
            })
            .queue(function () {
                //At animation finished, hide the slide
                jQuery(this).css('display', 'none');
                jQuery(this).dequeue();
            });
        //Previous slide fade-out
        $nextSlide
            .animate({'opacity': '1'}, {
                queue: true,
                duration: animationSpeed
            })
            .queue(function () {
                //When the new slide is showed, "unbind" the animation
                animationInProgress = false;
                jQuery(this).dequeue();
            });

        /**
         * Update Dots
         **/
        jQuery('.accessible-slideshow_dot.activeDot').removeClass('activeDot');
        jQuery('.accessible-slideshow_dot').eq(nextSlideIndex).addClass('activeDot');


        activeSlideIndex = nextSlideIndex;

    }

    /*----------------------------------------------------------------
-  Repeat the animation every N sec. (ONLY in default layout)
---------------------------------------------------------------- */
    var accessSlideshowInterval;
    if (jQuery("body").hasClass('default-layout')) {
        accessSlideshowInterval = setInterval(function () {
            nextSlideAnimation(-1);
        }, animationInterval);
    }

    /*----------------------------------------------------------------
-  Slideshow stop/restart handler (ONLY in default layout)
---------------------------------------------------------------- */
    jQuery('.default-layout .accessible-slideshow_outer').hover( // When the slideshow is hovered with the pointer, stop it
        function () {
            if (!manualSlideChanged) {
                clearInterval(accessSlideshowInterval);
            }
        },
        function () {
            // if any of the "navigation arrow" hasn't been clicked, restart the animation
            if (!manualSlideChanged) {
                accessSlideshowInterval = setInterval(function () {
                    nextSlideAnimation(-1);
                }, animationInterval);
            }
        }
    );

    /*----------------------------------------------------------------
-  Slideshow dots/arrows animations (hover)
---------------------------------------------------------------- */
    // Dots container hovered
    jQuery('.default-layout .accessible-slideshow_outer').hover(
        function () {
            jQuery('.default-layout .accessible-slideshow_arrow').animate({'opacity': '0.4'}, {
                queue: false,
                duration: 300
            });
            jQuery('.default-layout .accessible-slideshow_dots-container').animate({'opacity': '0.4'}, {
                queue: false,
                duration: 300
            });
        },
        function () {
            jQuery('.default-layout .accessible-slideshow_arrow').animate({'opacity': '0'}, {
                queue: false,
                duration: 300
            });
            jQuery('.default-layout .accessible-slideshow_dots-container').animate({'opacity': '0'}, {
                queue: false,
                duration: 300
            });
        }
    );
    // Opacity effect for the hovered arrows
    jQuery('.default-layout .accessible-slideshow_arrow').hover(
        function () {
            jQuery(this).animate({'opacity': '0.8'}, {
                queue: false,
                duration: 300
            });
        },
        function () {
            jQuery(this).animate({'opacity': '0.4'}, {
                queue: false,
                duration: 300
            });
        }
    );
    // Opacity effect for the hovered dots
    jQuery('.default-layout .accessible-slideshow_dots-container').hover(
        function () {
            jQuery(this).animate({'opacity': '0.8'}, {
                queue: false,
                duration: 300
            });
        },
        function () {
            jQuery(this).animate({'opacity': '0.4'}, {
                queue: false,
                duration: 300
            });
        }
    );

    /*----------------------------------------------------------------
-  Arrows click handler
---------------------------------------------------------------- */
    jQuery('.accessible-slideshow_arrow').click(function (e) {

        if (animationInProgress) {
            return;
        }

        //this stops the animation circle
        manualSlideChanged = true;

        if (jQuery(this).hasClass('accessible-slideshow_arrow-right')) {
            nextSlideAnimation(-1); // -2 = show next slide
        }
        if (jQuery(this).hasClass('accessible-slideshow_arrow-left')) {
            nextSlideAnimation(-2); // -2 = show previous slide
        }
    });

    /*----------------------------------------------------------------
-  Dots click handler
---------------------------------------------------------------- */
    jQuery('.accessible-slideshow_dot').click(function (e) {

        if (animationInProgress) {
            return false;
        }

        //this stops the animation circle
        manualSlideChanged = true;

        nextSlideAnimation(jQuery(this).parent().index());
    });

    /*----------------------------------------------------------------
-  Focus handler for dot navigation
---------------------------------------------------------------- */
    jQuery(".accessible-slideshow_dot").bind("focus", function () {

        // When focused, stop animation
        clearInterval(accessSlideshowInterval);

        //Show the dots
        jQuery('.accessible-slideshow_dots-container').css('opacity', '0.9');

        //Add the class for the "active style", plus, a special class to specify the keyboard focus
        jQuery(this).addClass('focusedAnchor');

    });
    jQuery(".accessible-slideshow_dot").bind("focusout", function () {

        //Remove the "active style" and also the special class for the keyboard focus
        jQuery(this).removeClass('focusedAnchor');

    });

    /*----------------------------------------------------------------
-  Focus handler for arrow navigation
---------------------------------------------------------------- */
}()); //END accessible slideshow