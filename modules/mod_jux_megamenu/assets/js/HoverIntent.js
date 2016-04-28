/*
---

name: PictureSlider

description: Animated picture slider. This is a port of jQuery HoverIntent

license: MIT-style

authors:
- Jakob Holmelund

requires:
- core/1.4.3: [Class, Event]

provides: HoverIntent, Element.hoverIntent

...
*/
var HoverIntent = new Class({
	Implements:[Options, Events],
	options:{
			sensitivity: 7,
			interval: 100,
			timeout: 0,
			over:function(){},
			out:function(){}
	},
	initialize:function(element, options){
		this.setOptions(options);
		this.ob = element;
		var self = this;
		this.ob.addEvents({
			mouseenter:function(e){self.handleHover(e);},
			mouseleave:function(e){self.handleHover(e);}
		});
	},
	track:function(ev){
		this.cX = ev.page.x;
		this.cY = ev.page.y;
	},
	_compare:function(ev,ob){
		var self = this;
		ob.hoverIntent_t = clearTimeout(ob.hoverIntent_t);
		// compare mouse positions to see if they've crossed the threshold
		if ( ( Math.abs(this.pX-this.cX) + Math.abs(this.pY-this.cY) ) < this.options.sensitivity ) {
			ob.removeEvent("mousemove",self.track);
			// set hoverIntent state to true (so mouseOut can be called)
			ob.hoverIntent_s = 1;
			return this.options.over.apply(ob,[ev]);
		} else {
			// set previous coordinates for next time
			this.pX = this.cX; this.pY = this.cY;
			// use self-calling timeout, guarantees intervals are spaced out properly (avoids JavaScript timer bugs)
			ob.hoverIntent_t = (function(){self._compare(ev, ob);}).delay(this.options.interval);
		}
	},
	_delay:function(ev, ob){
		ob.hoverIntent_t = clearTimeout(ob.hoverIntent_t);
		ob.hoverIntent_s = 0;
//		console.log("delayed call");
		return this.options.out.apply(ob,[ev]);
	},
	handleHover:function(e){
		var ev = e;
		var self = this;
		// cancel hoverIntent timer if it exists
		if (this.ob.hoverIntent_t !== undefined) {
			this.ob.hoverIntent_t = clearTimeout(self.ob.hoverIntent_t);
		}
		// if e.type == "mouseenter"
		if (e.type == "mouseenter" || e.type == "mouseover") {
			// set "previous" X and Y position based on initial entry point
			this.pX = ev.page.x;
			this.pY = ev.page.y;
			// update "current" X and Y position based on mousemove
			this.ob.addEvent("mousemove",function(e){
				self.track(e);
			});
			// start polling interval (self-calling timeout) to compare mouse coordinates over time
			if (this.ob.hoverIntent_s != 1) {
				this.ob.hoverIntent_t = (function(){
					self._compare(ev,self.ob);
				}).delay(self.options.interval);
			}

		// else e.type == "mouseleave"
		} else if (e.type == "mouseleave" || e.type == "mouseout") {
			// unbind expensive mousemove event
			this.ob.removeEvent("mousemove",function(e){
				self.track(e);
			});
			// if hoverIntent state is true, then call the mouseOut function after the specified delay
			if (this.ob.hoverIntent_s == 1) {
				this.ob.hoverIntent_t = (function(){
					self._delay(ev, self.ob);
				}).delay(self.options.interval);
			}
		}
	}
});

Element.implement({
  hoverIntent : function(options) {
    new HoverIntent(this, options);
    return this;
  }
});