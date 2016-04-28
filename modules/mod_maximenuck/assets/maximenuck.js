/**
 * @copyright	Copyright (C) 2011 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * Module Maximenu CK
 * @license		GNU/GPL
 * */

if (typeof(MooTools) != 'undefined') {

	var DropdownMaxiMenu = new Class({
		Implements: Options,
		options: {    //options par defaut si aucune option utilisateur n'est renseignee

			mooTransition: 'Quad',
			mooEase: 'easeOut',
			mooDuree: 500,
			mooDureeout: 500,
			useOpacity: '0',
			menuID: 'maximenuck',
			testoverflow: '1',
			orientation: 'horizontal',
			style: 'moomenu',
			opentype: 'open',
			direction: 'direction',
			directionoffset1: '30',
			directionoffset2: '30',
			dureeIn: 0,
			dureeOut: 500,
			ismobile: false,
			showactivesubitems: '0',
			langdirection: 'ltr',
			menuposition: '0',
			effecttype: 'dropdown'
		},
		initialize: function(element, options) {
			if (!element)
				return false;

			this.setOptions(options); //enregistre les options utilisateur

			var maduree = this.options.mooDuree;
			var madureeout = this.options.mooDureeout;
			var matransition = this.options.mooTransition;
			var monease = this.options.mooEase;

			// transform the transition option for mootools
			// var transitions = ['Quad', 'Cubic', 'Quart', 'Sine', 'Expo', 'Quint', 'Circ', 'Elastic', 'Back', 'Bounce'];
			var eases = ['easeInOut', 'easeIn', 'easeOut'];
			for (i=0;i<eases.length;i++) {
				if ( matransition.match(eases[i]) ) {
					matransition = matransition.replace(eases[i], '');
					monease = eases[i];
				}
			}

			var useopacity = this.options.useOpacity;
			var dureeout = this.options.dureeOut;
			var dureein = this.options.dureeIn;
			var menuID = this.options.menuID;
			var testoverflow = this.options.testoverflow;
			var orientation = ( this.options.orientation === 'vertical' ) ? '1' : '0';
			var opentype = this.options.opentype;
			var style = this.options.style;
			var direction = this.options.direction;
			var directionoffset1 = this.options.directionoffset1;
			var directionoffset2 = this.options.directionoffset2;
			var showactivesubitems = this.options.showactivesubitems;
			var ismobile = this.options.ismobile;
			var langdirection = this.options.langdirection;
			var effecttype = this.options.effecttype;

			if (this.options.menuposition == 'topfixed') {
				var menuy = element.getElement('ul').getPosition().y;
				element.menuHeight = element.offsetHeight;
				this.startListeners(element, menuy);
			} else if (this.options.menuposition == 'bottomfixed') {
				element.addClass('maximenufixed').getElement('ul.maximenuck').setStyle('position', 'static');
			}
			if (effecttype == 'dropdown') {
				var els = element.getElements('li.maximenuck.parent');
			} else {
				var els = element.getElements('li.level1.maximenuck.parent');
			}

			//els.each(function(el) {
			for (var i = 0; i < els.length; i++) {
				el = els[i];

				
				if (effecttype == 'dropdown') {
					// test if dropdown is required
					if (el.hasClass('nodropdown')) {
						continue;
					}
					
					el.conteneur = el.getElement('div.floatck');
					el.slideconteneur = el.getElement('div.maxidrop-main');
					el.conteneurul = el.getElements('div.floatck ul');
					el.conteneurul.setStyle('position', 'static');
					el.conteneur.setStyles({
						position: 'absolute'
					});
				} else {
					element.getElements('ul').setStyles({
					'position':'static',
					'top': 0,
					'left':0
					});
					el.conteneur = element.getElements('.maxipushdownck > .floatck')[i];
					el.conteneur.setStyles({
						//position: 'relative',
						overflow: 'hidden',
						width: 'inherit'
						// margin: '0',
						// padding: '0'
					});
					el.slideconteneur = element.getElements('.maxipushdownck > .floatck')[i].getElement('div.maxidrop-main'); // pb ici
					el.conteneurul = el.getElement('div.floatck ul');
					el.getElements('div.floatck ul').setStyle('position', 'static');
				}

//				if (el.getElement('div.floatck') != null) {
					

					if (direction == 'inverse') {
						if (orientation == '0') {
							if (el.hasClass('level1')) {
								el.conteneur.setStyle('bottom', directionoffset1 + 'px');
							} else {
								el.conteneur.setStyle('bottom', directionoffset2 + 'px');
							}
						} else {
							if (el.hasClass('level1')) {
								el.conteneur.setStyle('right', directionoffset1 + 'px');
							} else {
								el.conteneur.setStyle('right', directionoffset2 + 'px');
							}
						}
					}

					el.conteneur.mh = el.conteneur.clientHeight;
					el.conteneur.mw = el.conteneur.clientWidth;
					el.duree = maduree;
					el.madureeout = madureeout;
					el.transition = matransition;
					el.ease = monease;
					el.useopacity = useopacity;
					el.orientation = orientation;
					el.opentype = opentype;
					el.direction = direction;
					el.effecttype = effecttype;
					el.showactivesubitems = showactivesubitems;
					el.zindex = el.getStyle('z-index');
					el.submenudirection = (langdirection == 'rtl') ? 'right' : 'left';
					el.submenupositionvalue = (el.hasClass('fullwidth')) ? '0px' : 'auto';
					el.createFxMaxiCK();

					if (style == 'clickclose') {
						el.addEvent('mouseenter', function() {

							if (testoverflow == '1' && effecttype == 'dropdown')
								this.testOverflowMaxiCK(menuID);
							if (el.hasClass('level1') && el.hasClass('parent') && el.status != 'show') {
								els.each(function(el2) {
									if (el2.status == 'show') {
										//el2.getElement('div.floatck').setStyle('height','0');
										element.getElements('div.floatck').setStyle('display', 'none');
										el2.status = 'hide';
										el2.setStyle('z-index', 12001);
										el2.removeClass('sfhover');
									}
								});

							}
							this.setStyle('z-index', 15000);
							this.conteneur.setStyle('z-index', 15000);
							if (this.status != 'show' && effecttype == 'pushdown') this.hideAllMaxiCK(dureeout, element);
							this.showMaxiCK();

						});
						if (effecttype == 'dropdown') {
							el.getElement('.maxiclose').addEvent('click', function() {
								this.setStyle('z-index', 12001);
								this.conteneur.setStyle('z-index', 12001);
								el.hideMaxiCK(dureeout);
							});
						} else {
							el.conteneur.getElement('.maxiclose').addEvent('click', function() {
								this.setStyle('z-index', 12001);
								this.conteneur.setStyle('z-index', 12001);
								this.hideAllMaxiCK(dureeout, element);
							});
						}

					} else if (style == 'click') {

						var levels = ["level1", "level1", "level2", "level3", "level4"];

						if (el.hasClass('parent') && el.getFirst('a.maximenuck')) {
							el.redirection = el.getFirst('a.maximenuck').getProperty('href');
							el.getFirst('a.maximenuck').setProperty('href', 'javascript:void(0)');
							el.hasBeenClicked = false;
						}

						// hide when clicked outside
//                        if (ismobile) {
//                            document.body.addEvent('click',function(e) {
//                                if(element && !e.target || !$(e.target).getParents().contains(element)) {
//                                    el.hasBeenClicked = false;
//                                    el.hideMaxiCK();
//                                }
//                            });
//                        }

						clicktarget = el.getFirst('a.maximenuck') || el.getFirst('span.separator') || el.getFirst().getFirst('a.maximenuck') || el.getFirst().getFirst('span.separator');
						clicktarget.addEvent('click', function() {
							el = this.getParent();
							// set the redirection again for mobile
//                            if (el.hasBeenClicked == true && ismobile) {
//                                el.getFirst('a.maximenuck').setProperty('href',el.redirection);
//                            }

							el.hasBeenClicked = true;
							if (testoverflow == '1' && effecttype == 'dropdown')
								el.testOverflowMaxiCK(menuID);
							if (el.status == 'show') {
								// el.setStyle('z-index',12001);
								if (effecttype == 'dropdown') {
									el.hideMaxiCK();
								} else {
									el.hideAllMaxiCK(dureeout, element);
								}
							} else {
								levels.each(function(level) {

									if (el.hasClass(level) && el.hasClass('parent') && el.status != 'show') {

										els.each(function(el2) {
											if (el2.status == 'show' && el2.hasClass(level)) {
												//el2.getElement('div.floatck').setStyle('height','0');
												element.getElements('li.' + level + ' div.floatck').setStyle('display', 'none');
												el2.status = 'hide';
												el2.setStyle('z-index', 12001);
												el2.removeClass('sfhover');
											}
										});

									}
								}); // fin de boucle level.each
								el.setStyle('z-index', 15000);
								el.conteneur.setStyle('z-index', 15000);
								if (el.status != 'show' && effecttype == 'pushdown') el.hideAllMaxiCK(dureeout, element);
								el.showMaxiCK(dureein);
							}

						});

					} else {
						el.addEvent('mouseover', function() {
							if (testoverflow == '1' && effecttype == 'dropdown')
								this.testOverflowMaxiCK(menuID);
							if (this.status != 'show' && effecttype == 'pushdown') this.hideAllMaxiCK(dureeout, element);
							this.setStyle('z-index', 15000);
							this.conteneur.setStyle('z-index', 15000);
							this.showMaxiCK(dureein);

						});
						if (effecttype == 'dropdown') {
							el.addEvent('mouseleave', function() {
								this.setStyle('z-index', 12001);
								this.conteneur.setStyle('z-index', 12001);
								this.hideMaxiCK(dureeout);
							});
						} else {
							element.addEvent('mouseleave', function() {
								this.hideAllMaxiCK(dureeout, element);
							});
						}
					}
//				}
				//});
			}
			// needed for IE<11 to get the elements size
			element.getElements('div.floatck').setStyles({
				'display': 'none',
				'opacity': '1'
			});
		},
		startListeners: function(menu, menuy) {
			document.body.setProperty('data-margintop', document.body.getStyle('margin-top'));
			
			var togglefixedclass = function() {
				menu.removeClass('maximenufixed');
			}
			var action = function() {
				if (document.body.getScroll().y > menuy) {
					if (! menu.hasClass('maximenufixed') ) {
						menu.addClass('maximenufixed');
						document.body.setStyle('margin-top', parseInt(menu.menuHeight));
					}
				} else {
					document.body.setStyle('margin-top', document.body.getProperty('data-margintop'));
					togglefixedclass();
				}
			}
			window.addEvent('scroll', action);
			window.addEvent('load', action);
		}
	});

	if (MooTools.version > '1.12')
		Element.extend = Element.implement;

	Element.extend({
		testOverflowMaxiCK: function(menuID) {
			var limite = document.getElement('#' + menuID).offsetWidth + document.getElement('#' + menuID).getLeft();


			if (this.hasClass('parent')) {
				var largeur = this.conteneur.mw + 180;
				if (this.hasClass('level1'))
					largeur = this.conteneur.mw;

				var positionx = this.getLeft() + largeur;

				if (positionx > limite) {
					this.getElement('div.floatck').addClass('fixRight');
					this.setStyle('z-index', '15000');
					this.conteneur.setStyle('z-index', 15000);
				}

			}

		},
		createFxMaxiCK: function() {
			// set the dimensions of the submenu
			this.conteneur.setStyles({
				'display': 'block',
				'opacity': '0'
				});
				
			var size = this.conteneur.getComputedSize();
			this.conteneur.mh = size.height;
			this.conteneur.mw = size.width;
			// this.conteneur.mh = size.height - size.computedTop - size.computedBottom;
			// this.conteneur.mw = size.width - size.computedLeft - size.computedRight;
			
			// var myTransition = new Fx.Transition(Fx.Transitions[this.transition][this.ease]);
			var myTransition = new Fx.Transition(Fx.Transitions[this.transition][this.ease]); // easeInQuad

			if (this.hasClass('level1') && this.orientation != '1')
			{
				if ((this.opentype == 'slide' && this.direction == 'normal') || (this.opentype == 'open' && this.direction == 'inverse')) {
					this.maxiFxCK2 = new Fx.Tween(this.slideconteneur, {
						property: 'margin-top',
						duration: this.duree,
						transition: myTransition
					});
					this.maxiFxCK2.set(-this.conteneur.mh);
				}
				this.maxiFxCK = new Fx.Tween(this.conteneur, {
					property: 'height',
					duration: this.duree,
					transition: myTransition
				});

			} else {
				if ((this.opentype == 'slide' && this.direction == 'normal') || (this.opentype == 'open' && this.direction == 'inverse')) {
					this.maxiFxCK2 = new Fx.Tween(this.slideconteneur, {
						property: 'margin-' + el.submenudirection,
						duration: this.duree,
						transition: myTransition
					});

					this.maxiFxCK2.set(this.conteneur.mw);
				}

				this.maxiFxCK = new Fx.Tween(this.conteneur, {
					property: 'width',
					duration: this.duree,
					transition: myTransition
				});
				// this.maxiFxCK.set(0);
			}

			if (this.useopacity == '1') {
				this.maxiOpacityCK = new Fx.Tween(this.conteneur, {
					property: 'opacity',
					duration: this.duree
				});
				// this.maxiOpacityCK.set(0);
			}

			
			

			// to show the active subitems
			if (this.showactivesubitems == '1' && this.conteneur.getElement('.active')) {
				this.conteneur.setStyle('display', 'block');
				this.conteneur.setStyle(this.submenudirection, this.submenupositionvalue);
				this.conteneur.setStyle('opacity', '1');
				this.status = 'show';
				if (this.opentype == 'slide')
					this.maxiFxCK2.set(0);
			} else {
				this.maxiFxCK.set(0);
				if (this.useopacity == '1')
					this.maxiOpacityCK.set(0);
				// this.conteneur.setStyle(this.submenudirection, '-999em');
				// this.conteneur.setStyle('display', 'none');
				this.status = 'hide';
			}



			animComp = function() {
				if (this.status == 'hide')
				{
					// this.conteneur.setStyle(el.submenudirection, '-999em');
					this.conteneur.setStyle('display', 'none');
					this.conteneur.setStyle('position', 'absolute');
					this.hidding = 0;
					this.setStyle('z-index', this.zindex);
					if (this.opentype == 'slide' && this.hasClass('level1') && this.orientation != '1')
						this.slideconteneur.setStyle('margin-top', '0');
					if (this.opentype == 'slide' && (!this.hasClass('level1') || this.orientation != '1')) {
						this.slideconteneur.setStyle('margin-' + el.submenudirection, '0');
					}
				}
				this.showing = 0;
				if (this.effecttype == 'dropdown') this.conteneur.setStyle('overflow', '');

			}
			this.maxiFxCK.addEvent('onComplete', animComp.bind(this));
			if ((this.opentype == 'slide' && this.direction == 'normal') || (this.opentype == 'open' && this.direction == 'inverse'))
				this.maxiFxCK2.addEvent('onComplete', animComp.bind(this));

		},
		showMaxiCK: function(timeout) {
			clearTimeout(this.timeout);
			this.addClass('sfhover');
			this.status = 'show';
			clearTimeout(this.timeout);
			if (timeout)
			{
				this.timeout = setTimeout(this.animMaxiCK.bind(this), timeout);
			} else {
				this.animMaxiCK();
			}
		},
		hideMaxiCK: function(timeout) {
			this.status = 'hide';
			this.removeClass('sfhover');
			clearTimeout(this.timeout);
			if (timeout)
			{
				this.timeout = setTimeout(this.animMaxiCK.bind(this), timeout);
			} else {
				this.animMaxiCK();
			}
		},
		hideAllMaxiCK: function(timeout, menu) {
			
			items = menu.getElements('li.level1.maximenuck.parent');

			items.each(function(item) {
				item.status = 'hide';
				// item.removeClass('sfhover');
				item.hidding = 1;
				item.showing = 0;
				item.maxiFxCK.cancel();
				
				if ((item.opentype == 'slide' && item.direction == 'normal') || (item.opentype == 'open' && item.direction == 'inverse'))
					item.maxiFxCK2.cancel();

				item.maxiFxCK.start(item.conteneur.offsetHeight, 0);
				
				if ((item.opentype == 'slide' && item.direction == 'normal') || (item.opentype == 'open' && item.direction == 'inverse')) {
					item.maxiFxCK2.start(0, -item.conteneur.offsetHeight);
				}

				if (item.useopacity == '1') {
					item.maxiOpacityCK.cancel();
					item.maxiOpacityCK.start(1, 0);
				}
			});
		},
		animMaxiCK: function() {

			if ( ((this.status == 'hide' && this.conteneur.style.display != 'block') || (this.status == 'show' && this.conteneur.style.display == 'block' && !this.hidding))
					// || this.submenudirection == 'right' && ((this.status == 'hide' && this.conteneur.style.right != this.submenupositionvalue) || (this.status == 'show' && this.conteneur.style.right == this.submenupositionvalue && !this.hidding))
					)
				return;

			this.conteneur.setStyle('overflow', 'hidden');
			if (this.status == 'show') {
				this.hidding = 0;
			}
			if (this.status == 'hide')
			{
				this.hidding = 1;
				this.showing = 0;
				this.maxiFxCK.cancel();
				if ((this.opentype == 'slide' && this.direction == 'normal') || (this.opentype == 'open' && this.direction == 'inverse'))
					this.maxiFxCK2.cancel();

				if (this.hasClass('level1') && this.orientation != '1') {
					this.maxiFxCK.start(this.conteneur.getComputedSize().height, 0);
					if ((this.opentype == 'slide' && this.direction == 'normal') || (this.opentype == 'open' && this.direction == 'inverse')) {
						this.maxiFxCK2.start(0, -this.conteneur.getComputedSize().height);
					}
				} else {
					this.maxiFxCK.start(this.conteneur.getComputedSize().width, 0);
					if ((this.opentype == 'slide' && this.direction == 'normal') || (this.opentype == 'open' && this.direction == 'inverse')) {
						this.maxiFxCK2.start(0, -this.conteneur.getComputedSize().width);
					}
				}
				if (this.useopacity == '1') {
					this.maxiOpacityCK.cancel();
					this.maxiOpacityCK.start(1, 0);
				}
			} else {
				this.showing = 1;
				if (this.effecttype == 'pushdown') this.conteneur.setStyle('position', 'relative');
				// this.conteneur.setStyle(this.submenudirection, this.submenupositionvalue);
				this.conteneur.setStyle('display', 'block');

				this.maxiFxCK.cancel();
				if ((this.opentype == 'slide' && this.direction == 'normal') || (this.opentype == 'open' && this.direction == 'inverse'))
					this.maxiFxCK2.cancel();
				if (this.hasClass('level1') && this.orientation != '1') {
					this.maxiFxCK.start(this.conteneur.getComputedSize().height, this.conteneur.mh);
					if ((this.opentype == 'slide' && this.direction == 'normal') || (this.opentype == 'open' && this.direction == 'inverse')) {
						this.maxiFxCK2.start(-this.conteneur.mh, 0);
					}
				} else {
					this.maxiFxCK.start(this.conteneur.getComputedSize().width, this.conteneur.mw);
					if ((this.opentype == 'slide' && this.direction == 'normal') || (this.opentype == 'open' && this.direction == 'inverse')) {
						this.maxiFxCK2.start(-this.conteneur.mw, 0);
					}
				}
				if (this.useopacity == '1') {
					this.maxiOpacityCK.cancel();
					this.maxiOpacityCK.start(0, 1);
				}

			}


		}
	});

	DropdownMaxiMenu.implement(new Options); //ajoute les options utilisateur � la class


	/*Window.onDomReady(function() {new DropdownMenu($E('ul.maximenuck'),{
	 //mooTransition : 'Quad',
	 //mooTransition : 'Cubic',
	 //mooTransition : 'Quart',
	 //mooTransition : 'Quint',
	 //mooTransition : 'Pow',
	 //mooTransition : 'Expo',
	 //mooTransition : 'Circ',
	 mooTransition : 'Sine',
	 //mooTransition : 'Back',
	 //mooTransition : 'Bounce',
	 //mooTransition : 'Elastic',
	 
	 mooEase : 'easeIn',
	 //mooEase : 'easeOut',
	 //mooEase : 'easeInOut',
	 
	 mooDuree : 500
	 })
	 });*/

}