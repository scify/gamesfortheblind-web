var labelparents = [];
if (typeof(contentmooaccordion) == 'undefined')
    var contentmooaccordion = {};
var mooaccordioncontainers = [];
var mooAccordion = [];
window.addEvent('domready', function() {
    var labels = $$('.mooaccordion');
    var lastcontainer = (labels.length > 0) ? labels[0].getParent() : null;
    var containerindex = 0;
    labels.each(function(label) {
        if (lastcontainer != label.getParent()) {
            containerindex++;
            lastcontainer = label.getParent();
            labelparents[containerindex] = [];
        }
        if (labelparents.length == 0 || !labelparents[containerindex].contains(label)) {
            labelparents[containerindex] = label.getParent().getChildren('[class~="mooaccordion"]');
            mooaccordioncontainers[containerindex] = new Element('div', {'id': 'mooaccordion' + containerindex, 'class': 'mooaccordioncontainer mooaccordioncontainer' + containerindex});
        }
    });
    labelparents.each(function(labelparent, index) {
        if (labelparent[0].getChildren().length == 0 || !labelparent[0].getChildren()[0].hasClass('mooaccordionicon')) {
            mooaccordioncontainers[index].inject(labelparent[0], 'before');
            labelparent.each(function(label) {
                var target = label.getNext();
                label.addClass('mooaccordion' + index);
                mooaccordioncontainers[index].adopt(label);
                mooaccordioncontainers[index].adopt(target);
                var icon = new Element('div', {'class': 'mooaccordionicon'}).inject(label, 'top');
            });
        }
    });
    mooaccordioncontainers.each(function(mooaccordioncontainer, index) {
        if (mooaccordioncontainer.getParent()) {
            var containerid = mooaccordioncontainer.getParent().get('id');
        } else {
            var containerid = null;
        }
        var containeroptions = {};
        if (
                containerid !== null &&
                contentmooaccordion.length !== 0 &&
                typeof(contentmooaccordion) !== 'undefined' &&
                typeof(contentmooaccordion[containerid]) !== 'undefined'
                ) {
            // allow all hidden
            if (contentmooaccordion[containerid].hasOwnProperty('allowallclosed')) {
                containeroptions['alwaysHide'] = true;
            }
            if (contentmooaccordion[containerid].hasOwnProperty('display')) {
                // display specific item
                containeroptions['display'] = contentmooaccordion[containerid]['display'];
            } else {
                // hide all
                if (contentmooaccordion[containerid].hasOwnProperty('allowallclosed'))
                    containeroptions['display'] = -1;
            }
            if (contentmooaccordion[containerid].hasOwnProperty('template')) {
                // you can't have both - if a template is selected, open and closed classes are overridden
                contentmooaccordion[containerid]['openedclass'] = 'template' + contentmooaccordion[containerid]['template'] + 'opened';
                contentmooaccordion[containerid]['closedclass'] = 'template' + contentmooaccordion[containerid]['template'] + 'closed';
            }
            if (
                    contentmooaccordion[containerid].hasOwnProperty('openedclass') ||
                    contentmooaccordion[containerid].hasOwnProperty('closedclass')
            ) {
                containeroptions.onActive = function(toggler) {
                    toggler.addClass('mooaccordionopen');
                    if (contentmooaccordion[containerid].hasOwnProperty('openedclass')) {
                        toggler.addClass(contentmooaccordion[containerid]['openedclass']);
                    }
                    if (contentmooaccordion[containerid].hasOwnProperty('closedclass')) {
                        toggler.removeClass(contentmooaccordion[containerid]['closedclass']);
                    }
                };
                containeroptions.onBackground = function(toggler) {
                    toggler.removeClass('mooaccordionopen');
                    if (contentmooaccordion[containerid].hasOwnProperty('openedclass')) {
                        toggler.removeClass(contentmooaccordion[containerid]['openedclass']);
                    }
                    if (contentmooaccordion[containerid].hasOwnProperty('closedclass')) {
                        toggler.addClass(contentmooaccordion[containerid]['closedclass']);
                    }
                };
            }
            if (contentmooaccordion[containerid].hasOwnProperty('hover')) {
                $$('.mooaccordion' + index).each(function(el) {
                    el.addEvent('mouseenter', function() {
                        if(!el.hasClass('mooaccordionopen')) el.fireEvent('click');
                    });
                });
            }            
        }
        if (Object.keys(containeroptions).length > 0) {
            mooAccordion[index] = new Fx.Accordion('#mooaccordion' + index + ' .mooaccordion' + index, '*[class~=mooaccordion' + index + '] + *', containeroptions);
        } else {
            $$('.mooaccordion' + index).each(function(el) {
                el.addClass('mooaccordiondefaulttoggle');
            });
            containeroptions.onActive = function(toggler) {
                toggler.addClass('mooaccordionopen');
            };
            containeroptions.onBackground = function(toggler) {
                toggler.removeClass('mooaccordionopen');
            };
            mooAccordion[index] = new Fx.Accordion('#mooaccordion' + index + ' .mooaccordion' + index, '*[class~=mooaccordion' + index + '] + *', containeroptions);
        }
    });
});