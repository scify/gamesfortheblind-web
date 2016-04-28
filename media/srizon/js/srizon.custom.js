;
(function ($) {
    $.fn.srzSingleImageSlider = function (options) {
        var selector = $(this).selector;
        var settings = $.extend({
            images_json: [],
            prev_class: '.prev',
            next_class: '.next',
            current_index: 0,
            max_height: 600,
            fadeout_time: 300,
            fade_opacity: 0.3,
            fadein_time: 300,
            fixed_height: 'auto',
            hover_caption: 1,
            thumb_container: ''
        }, options);

        $(this).css('max-height', settings.max_height + 'px');
        var img = $('<img/>').attr('src', settings.images_json[settings.current_index].src).attr('alt', settings.images_json[settings.current_index].txt).css('height', settings.fixed_height);
        $(this).html(img);
        if (settings.hover_caption == 1) {
            $(this).srzAltToCaption();
        }
        $(this).parent().find(settings.next_class).each(function () {
            $(this).click(function () {
                settings.current_index = (settings.current_index + 1) % settings.images_json.length;
                change_image();
            });
        });

        $(this).on('swipeleft', function () {
            settings.current_index = (settings.current_index + 1) % settings.images_json.length;
            change_image();
        });

        $(this).on('swiperight', function () {
            settings.current_index = (settings.current_index + settings.images_json.length - 1) % settings.images_json.length;
            change_image();
        });

        $(this).parent().find(settings.prev_class).each(function () {
            $(this).click(function () {
                settings.current_index = (settings.current_index + settings.images_json.length - 1) % settings.images_json.length;
                change_image();
            });
        });

        if (settings.thumb_container != '') {
            $(settings.thumb_container).find('a').each(function () {
                $(this).click(function () {
                    settings.current_index = $(this).data('index');
                    change_image();
                });
            });
        }

        function change_image() {
            var img_new = $('<img/>').attr('src', settings.images_json[settings.current_index].src).attr('alt', settings.images_json[settings.current_index].txt).css('height', settings.fixed_height);
            $(selector).fadeTo(settings.fadeout_time, settings.fade_opacity, function () {
                img_new.one('loadc', function () {
                    $(selector).html(img_new).fadeTo(settings.fadein_time, 1);
                    if (settings.hover_caption == 1) {
                        $(selector).srzAltToCaption();
                    }
                });
                if (img_new[0].complete) img_new.trigger('loadc');
                else {
                    img_new.one('load', function () {
                        img_new.trigger('loadc');
                    })
                }
            });
        }

        return this;
    };
}(jQuery));

// jquery plugin for converting alt value to caption and show that on hover at the bottom
(function ($) {
    $.fn.srzAltToCaption = function () {
        this.find('img').each(function () {
            var $thisimg = $(this);
            $(this).on('loadc', function () {
                var caption_text = $(this).attr('alt');
                if (caption_text == '') return;

                var caption = $('<p>').addClass('current-caption').html(caption_text);
                caption.hide().insertAfter($(this));

                $(this).parent().hover(function () {
                    var caption_width = $thisimg.width() + 'px';
                    var caption_height = $thisimg.height() / 2 + 'px';
                    var caption_left = $thisimg.position().left + 'px';
                    caption.css({
                        'position': 'absolute',
                        'width': caption_width,
                        'max-height': caption_height,
                        'bottom': '0',
                        'left': caption_left
                    });
                    caption.fadeIn();
                }, function () {
                    caption.fadeOut();
                });
            });
            if ($(this)[0].complete) $(this).trigger('loadc');
            else {
                $(this).one('load', function () {
                    $(this).trigger('loadc');
                })
            }
        });
        return this;
    };
}(jQuery));

(function ($) {
    $.fn.srzSingleImageCard = function (options) {
        var selector = $(this).selector;
        var settings = $.extend({
            images_json: [],
            next_class: '.next',
            current_index: 0,
            max_height: 600,
            animation_time: 500,
            hover_caption: 1,
            thumb_container: ''
        }, options);

        $(this).css('max-height', settings.max_height + 'px');

        if (settings.images_json.length < 3) {
            $(this).html('<h3>This layout requires at list 3 images</h3>');
        }
        else {
            var img1 = $('<img/>').attr('src', settings.images_json[settings.current_index].src).attr('alt', settings.images_json[settings.current_index].txt).addClass("withtransition card-first").appendTo(this);
            var img2 = $('<img/>').attr('src', settings.images_json[settings.current_index + 1].src).attr('alt', settings.images_json[settings.current_index + 1].txt).addClass("withtransition card-second").appendTo(this).hide();
            var img3 = $('<img/>').attr('src', settings.images_json[settings.current_index + 2].src).attr('alt', settings.images_json[settings.current_index + 2].txt).addClass("withtransition card-third").appendTo(this).hide();

            // first 3 image insertion complete - 2 and 3 are hidden before first one finishes loading in order to assign proper height

            img1.one('loadc', function () {
                if ($(this).attr('alt').length > 0 && settings.hover_caption == 1) {
                    var caption = $(this).parent().find('.current-caption').html($(this).attr('alt'));
                    $(this).hover(function () {
                        var caption_width = $(this).width() + 'px';
                        var caption_height = $(this).height() / 2 + 'px';
                        var caption_left = $(this).position().left + 'px';
                        caption.css({
                            'position': 'absolute',
                            'width': caption_width,
                            'max-height': caption_height,
                            'bottom': '0',
                            'left': caption_left
                        });
                        caption.fadeIn();
                    }, function () {
                        caption.fadeOut();
                    });
                }
                img2.css('max-height', (settings.max_height / 6) + 'px').show();
                img3.css('max-height', (settings.max_height / 12) + 'px').show();
                $(this).parent().find(settings.next_class).each(function () {
                    $(this).click(function () {
                        show_next_image();
                    });
                });
            });
            if (img1[0].complete) img1.trigger('loadc'); //force trigger load if already complete
            else {
                img1.one('load', function () {
                    img1.trigger('loadc');
                })
            }
        }

        function show_next_image() {
            settings.current_index = (settings.current_index + 1) % settings.images_json.length;
            var next_index = ( settings.current_index + 2 ) % settings.images_json.length;
            var img3 = $('<img/>').attr('src', settings.images_json[next_index].src).attr('alt', settings.images_json[next_index].txt).addClass("card-third").appendTo($(selector)).hide();
            // next image added

            srz_preserve_height_timer(selector, settings.animation_time - 100);
            var img0 = $('.card-first').removeClass('card-first').addClass('card-to-be-removed');
            var img1 = img0.next('img').removeClass('card-second').addClass('card-first').css('max-height', 'inherit');
            var img2 = img1.next('img').removeClass('card-third').addClass('card-second');
            setTimeout(function () {
                img0.remove();
            }, settings.animation_time + 500);

            img1.one('loadc', function () {
                if ($(this).attr('alt').length > 0 && settings.hover_caption == 1) {
                    var caption = $(this).parent().find('.current-caption').html($(this).attr('alt'));
                    $(this).hover(function () {
                        var caption_width = $(this).width() + 'px';
                        var caption_height = $(this).height() / 2 + 'px';
                        var caption_left = $(this).position().left + 'px';
                        caption.css({
                            'position': 'absolute',
                            'width': caption_width,
                            'max-height': caption_height,
                            'bottom': '0',
                            'left': caption_left
                        });
                        caption.fadeIn();
                    }, function () {
                        caption.fadeOut();
                    });
                }
                img2.css('max-height', (settings.max_height / 6) + 'px');
                img3.css('max-height', (settings.max_height / 12) + 'px').show();
                setTimeout(function () {
                    img3.addClass('withtransition');
                }, 500);
                $(this).click(function () {
                    show_next_image();
                });
            });
            if (img1[0].complete) img1.trigger('loadc'); //force trigger load if already complete
            else {
                img1.one('load', function () {
                    img1.trigger('loadc');
                })
            }
        }

        function srz_preserve_height_timer(target, time) {
            var jqel = jQuery(target);
            jqel.height(jqel.height());
            setTimeout(function () {
                jqel.height('auto');
            }, time);
        }

        return this;
    };
}(jQuery));

(function ($) {
    $.fn.matchImgHeight = function (options) {
        var settings = $.extend({
            height: 200
        }, options);
        this.find('img').each(function () {
            var aspect_ratio, new_width;
            if ($(this).attr('width')) {
                aspect_ratio = $(this).attr('width') / $(this).attr('height');
                new_width = aspect_ratio * settings.height;
                $(this).attr('width', new_width);
                $(this).width(new_width);
            }
            $(this).attr('height', settings.height);
            $(this).height(settings.height);
        });
        return this;
    };
}(jQuery));

(function ($) {

    var event = jQuery.event,

    //helper that finds handlers by type and calls back a function, this is basically handle
    // events - the events object
    // types - an array of event types to look for
    // callback(type, handlerFunc, selector) - a callback
    // selector - an optional selector to filter with, if there, matches by selector
    //     if null, matches anything, otherwise, matches with no selector
        findHelper = function (events, types, callback, selector) {
            var t, type, typeHandlers, all, h, handle,
                namespaces, namespace,
                match;
            for (t = 0; t < types.length; t++) {
                type = types[t];
                all = type.indexOf(".") < 0;
                if (!all) {
                    namespaces = type.split(".");
                    type = namespaces.shift();
                    namespace = new RegExp("(^|\\.)" + namespaces.slice(0).sort().join("\\.(?:.*\\.)?") + "(\\.|$)");
                }
                typeHandlers = (events[type] || []).slice(0);

                for (h = 0; h < typeHandlers.length; h++) {
                    handle = typeHandlers[h];

                    match = (all || namespace.test(handle.namespace));

                    if (match) {
                        if (selector) {
                            if (handle.selector === selector) {
                                callback(type, handle.origHandler || handle.handler);
                            }
                        } else if (selector === null) {
                            callback(type, handle.origHandler || handle.handler, handle.selector);
                        }
                        else if (!handle.selector) {
                            callback(type, handle.origHandler || handle.handler);

                        }
                    }


                }
            }
        };

    /**
     * Finds event handlers of a given type on an element.
     * @param {HTMLElement} el
     * @param {Array} types an array of event names
     * @param {String} [selector] optional selector
     * @return {Array} an array of event handlers
     */
    event.find = function (el, types, selector) {
        var events = ( $._data(el) || {} ).events,
            handlers = [],
            t, liver, live;

        if (!events) {
            return handlers;
        }
        findHelper(events, types, function (type, handler) {
            handlers.push(handler);
        }, selector);
        return handlers;
    };
    /**
     * Finds all events.  Group by selector.
     * @param {HTMLElement} el the element
     * @param {Array} types event types
     */
    event.findBySelector = function (el, types) {
        var events = $._data(el).events,
            selectors = {},
        //adds a handler for a given selector and event
            add = function (selector, event, handler) {
                var select = selectors[selector] || (selectors[selector] = {}),
                    events = select[event] || (select[event] = []);
                events.push(handler);
            };

        if (!events) {
            return selectors;
        }
        //first check live:
        /*$.each(events.live || [], function( i, live ) {
         if ( $.inArray(live.origType, types) !== -1 ) {
         add(live.selector, live.origType, live.origHandler || live.handler);
         }
         });*/
        //then check straight binds
        findHelper(events, types, function (type, handler, selector) {
            add(selector || "", type, handler);
        }, null);

        return selectors;
    };
    event.supportTouch = "ontouchend" in document;

    $.fn.respondsTo = function (events) {
        if (!this.length) {
            return false;
        } else {
            //add default ?
            return event.find(this[0], $.isArray(events) ? events : [events]).length > 0;
        }
    };
    $.fn.triggerHandled = function (event, data) {
        event = (typeof event == "string" ? $.Event(event) : event);
        this.trigger(event, data);
        return event.handled;
    };
    /**
     * Only attaches one event handler for all types ...
     * @param {Array} types llist of types that will delegate here
     * @param {Object} startingEvent the first event to start listening to
     * @param {Object} onFirst a function to call
     */
    event.setupHelper = function (types, startingEvent, onFirst) {
        if (!onFirst) {
            onFirst = startingEvent;
            startingEvent = null;
        }
        var add = function (handleObj) {

                var bySelector, selector = handleObj.selector || "";
                if (selector) {
                    bySelector = event.find(this, types, selector);
                    if (!bySelector.length) {
                        $(this).delegate(selector, startingEvent, onFirst);
                    }
                }
                else {
                    //var bySelector = event.find(this, types, selector);
                    if (!event.find(this, types, selector).length) {
                        event.add(this, startingEvent, onFirst, {
                            selector: selector,
                            delegate: this
                        });
                    }

                }

            },
            remove = function (handleObj) {
                var bySelector, selector = handleObj.selector || "";
                if (selector) {
                    bySelector = event.find(this, types, selector);
                    if (!bySelector.length) {
                        $(this).undelegate(selector, startingEvent, onFirst);
                    }
                }
                else {
                    if (!event.find(this, types, selector).length) {
                        event.remove(this, startingEvent, onFirst, {
                            selector: selector,
                            delegate: this
                        });
                    }
                }
            };
        $.each(types, function () {
            event.special[this] = {
                add: add,
                remove: remove,
                setup: function () {
                },
                teardown: function () {
                }
            };
        });
    };
})(jQuery);

(function ($) {
    var isPhantom = /Phantom/.test(navigator.userAgent),
        supportTouch = !isPhantom && "ontouchend" in document,
        scrollEvent = "touchmove scroll",
    // Use touch events or map it to mouse events
        touchStartEvent = supportTouch ? "touchstart" : "mousedown",
        touchStopEvent = supportTouch ? "touchend" : "mouseup",
        touchMoveEvent = supportTouch ? "touchmove" : "mousemove",
        data = function (event) {
            var d = event.originalEvent.touches ?
                event.originalEvent.touches[0] :
                event;
            return {
                time: (new Date).getTime(),
                coords: [d.pageX, d.pageY],
                origin: $(event.target)
            };
        };

    /**
     * @add jQuery.event.swipe
     */
    var swipe = $.event.swipe = {
        /**
         * @attribute delay
         * Delay is the upper limit of time the swipe motion can take in milliseconds.  This defaults to 500.
         *
         * A user must perform the swipe motion in this much time.
         */
        delay: 500,
        /**
         * @attribute max
         * The maximum distance the pointer must travel in pixels.  The default is 75 pixels.
         */
        max: 75,
        /**
         * @attribute min
         * The minimum distance the pointer must travel in pixels.  The default is 30 pixels.
         */
        min: 30
    };

    $.event.setupHelper([

    /**
     * @hide
     * @attribute swipe
     */
        "swipe",
    /**
     * @hide
     * @attribute swipeleft
     */
        'swipeleft',
    /**
     * @hide
     * @attribute swiperight
     */
        'swiperight',
    /**
     * @hide
     * @attribute swipeup
     */
        'swipeup',
    /**
     * @hide
     * @attribute swipedown
     */
        'swipedown'], touchStartEvent, function (ev) {
        var
        // update with data when the event was started
            start = data(ev),
            stop,
            delegate = ev.delegateTarget || ev.currentTarget,
            selector = ev.handleObj.selector,
            entered = this;

        function moveHandler(event) {
            if (!start) {
                return;
            }
            // update stop with the data from the current event
            stop = data(event);

            // prevent scrolling
            if (Math.abs(start.coords[0] - stop.coords[0]) > 10) {
                event.preventDefault();
            }
        };

        // Attach to the touch move events
        $(document.documentElement).bind(touchMoveEvent, moveHandler)
            .one(touchStopEvent, function (event) {
                $(this).unbind(touchMoveEvent, moveHandler);
                // if start and stop contain data figure out if we have a swipe event
                if (start && stop) {
                    // calculate the distance between start and stop data
                    var deltaX = Math.abs(start.coords[0] - stop.coords[0]),
                        deltaY = Math.abs(start.coords[1] - stop.coords[1]),
                        distance = Math.sqrt(deltaX * deltaX + deltaY * deltaY);

                    // check if the delay and distance are matched
                    if (stop.time - start.time < swipe.delay && distance >= swipe.min) {
                        var events = ['swipe'];
                        // check if we moved horizontally
                        if (deltaX >= swipe.min && deltaY < swipe.min) {
                            // based on the x coordinate check if we moved left or right
                            events.push(start.coords[0] > stop.coords[0] ? "swipeleft" : "swiperight");
                        } else
                        // check if we moved vertically
                        if (deltaY >= swipe.min && deltaX < swipe.min) {
                            // based on the y coordinate check if we moved up or down
                            events.push(start.coords[1] < stop.coords[1] ? "swipedown" : "swipeup");
                        }

                        // trigger swipe events on this guy
                        $.each($.event.find(delegate, events, selector), function () {
                            this.call(entered, ev, {start: start, end: stop})
                        })

                    }
                }
                // reset start and stop
                start = stop = undefined;
            })
    });

})(jQuery);

(function (a) {
    a.fn.removeWhitespace = function () {
        this.contents().filter(function () {
            return this.nodeType == 3 && !/\S/.test(this.nodeValue)
        }).remove();
        return this
    }
})(jQuery);

(function (e) {
    e.fn.collageCaption = function (t) {
        var n = {
            images: e(this).children(),
            background: "black",
            opacity: "0.5",
            speed: 0,
            cssClass: "Caption",
            behaviour_c: 0
        };
        var r = e.extend({}, n, t);
        return this.each(function () {
            var t = 0, n = [];
            r.images.each(function (t) {
                var n = e(this);
                var ih = n.find('img').height() / 2;
                if (typeof n.data("caption") == "undefined") {
                    return
                }
                if (n.data("caption").length == 0) return;
                var i = '<div class="' + r.cssClass + '" style="position:relative;"><div class="Caption_Background" style="background-color:' + r.background + ";opacity:" + r.opacity + ';position:relative;top:0;"></div><div class="Caption_Content" style="position:relative;">' + n.data("caption") + "</div></div>";
                n.append(i);
                var s = n.find("." + r.cssClass), o = n.find(".Caption_Background"), u = n.find(".Caption_Content");
                var a = Math.min(s.height(), ih);
                o.height(a);
                u.css("top", "-" + a + "px");
                s.css('top', -1 * a);
                if (r.behaviour_c == 0) {
                    s.hide();
                    n.bind({
                        mouseenter: function (e) {
                            if (s.find('.Caption_Content').html().length > 0) {
                                s.fadeIn();
                            }
                        }, mouseleave: function (e) {
                            if (s.find('.Caption_Content').html().length > 0) {
                                s.fadeOut()
                            }
                        }
                    });
                }
                if (r.behaviour_c == 1) {
                    n.bind({
                        mouseenter: function (e) {
                            if (s.find('.Caption_Content').html().length > 0) {
                                s.fadeOut();
                            }
                        }, mouseleave: function (e) {
                            if (s.find('.Caption_Content').html().length > 0) {
                                s.fadeIn()
                            }
                        }
                    });
                }
            });
            return this
        })
    }
})(jQuery);

jQuery(document).ready(function () {
    if (jQuery().magnificPopup) {
        jQuery('.jfbalbum').each(function () {
            jQuery(this).magnificPopup({
                delegate: 'a.aimg',
                type: 'image',
                gallery: {enabled: true},
                zoom: {
                    enabled: true,
                    duration: 300,
                    easing: 'ease-in-out',
                    opener: function (openerElement) {
                        return openerElement.is('img') ? openerElement : openerElement.find('img');
                    }
                }
            });
        });
        jQuery('.jtubegallery').each(function () {
            jQuery(this).magnificPopup({
                delegate: '.magpopif',
                type: 'iframe'
            });
        });
    }
    jQuery('.jfbalbum').one('mouseenter',function () {
        jQuery('.Caption_Content').each(function () {
            jQuery(this).click(function (e) {
                //if (this !== e.target) return;
                var eee = jQuery(this).parent().prev();
                if(eee.data('gallery') == 'gallery'){
                    window.location = eee.attr('href');
                }
                else{
                    eee.click();
                }
            });
        });
    });
    jQuery('a.aimg').click(function () {
        setTimeout(function () {
            jQuery('.mfp-figure').on('swipeleft', function () {
                jQuery('.mfp-arrow-right').click();
            }).on('swiperight', function () {
                jQuery('.mfp-arrow-left').click();
            });
        }, 100);
    });
});

function load_juser_video(scrollerid, videoid) {
    var vidloaderbox, embedcode, autotext;
    autotext = 'autoplay=0';
    vidloaderbox = '#vid' + scrollerid;
    embedcode = '<div>' +
        '<table class="juser-vid-table">' +
        '<tr>' +
        '<td>' +
        '<div class="juser-vid-container"><iframe class="youtube-player" type="text/html" width="960" height="600" src="//www.youtube.com/embed/' + videoid + '?fs=1&rel=0&wmode=transparent' + autotext + '" frameborder="0" allowfullscreen></iframe></div>' +
        '</td>' +
        '</tr>' +
        '</table>' +
        '</div>';
    jQuery(vidloaderbox).html(embedcode);
}

(function ($) {
    $.fn.autoscrollElastislide = function (params) {
        var defaults = {
            interval: 0,
            direction: 0
        };
        var $this = $(this);
        var options = $.extend({}, defaults, params);
        setTimeout(function () {
            if(options.interval > 0){
                setInterval(function () {
                    if(options.direction == 0){
                        //change direction if the button is hidden
                        if($this.parent().next().find('.elastislide-next:hidden').length){
                            options.direction = 1;
                        }
                        else{
                            $this.parent().next().find('.elastislide-next').trigger('mousedown');
                        }
                    }
                    else{
                        //change direction if the button is hidden
                        if($this.parent().next().find('.elastislide-prev:hidden').length){
                            options.direction = 0;
                        }
                        else{
                            $this.parent().next().find('.elastislide-prev').trigger('mousedown');
                        }

                    }

                },options.interval*1000);

            }
        },100);
        return this;
    }
})(jQuery);
