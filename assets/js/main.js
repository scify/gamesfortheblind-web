
(function(){var e;var d=function(){};var b=["assert","clear","count","debug","dir","dirxml","error","exception","group","groupCollapsed","groupEnd","info","log","markTimeline","profile","profileEnd","table","time","timeEnd","timeStamp","trace","warn"];var c=b.length;var a=(window.console=window.console||{});while(c--){e=b[c];if(!a[e]){a[e]=d}}}());
;window.Modernizr=function(a,b,c){function u(a){i.cssText=a}function v(a,b){return u(prefixes.join(a+";")+(b||""))}function w(a,b){return typeof a===b}function x(a,b){return!!~(""+a).indexOf(b)}function y(a,b,d){for(var e in a){var f=b[a[e]];if(f!==c)return d===!1?a[e]:w(f,"function")?f.bind(d||b):f}return!1}var d="2.6.2",e={},f=b.documentElement,g="modernizr",h=b.createElement(g),i=h.style,j,k={}.toString,l={},m={},n={},o=[],p=o.slice,q,r=function(a,c,d,e){var h,i,j,k,l=b.createElement("div"),m=b.body,n=m||b.createElement("body");if(parseInt(d,10))while(d--)j=b.createElement("div"),j.id=e?e[d]:g+(d+1),l.appendChild(j);return h=["&#173;",'<style id="s',g,'">',a,"</style>"].join(""),l.id=g,(m?l:n).innerHTML+=h,n.appendChild(l),m||(n.style.background="",n.style.overflow="hidden",k=f.style.overflow,f.style.overflow="hidden",f.appendChild(n)),i=c(l,a),m?l.parentNode.removeChild(l):(n.parentNode.removeChild(n),f.style.overflow=k),!!i},s={}.hasOwnProperty,t;!w(s,"undefined")&&!w(s.call,"undefined")?t=function(a,b){return s.call(a,b)}:t=function(a,b){return b in a&&w(a.constructor.prototype[b],"undefined")},Function.prototype.bind||(Function.prototype.bind=function(b){var c=this;if(typeof c!="function")throw new TypeError;var d=p.call(arguments,1),e=function(){if(this instanceof e){var a=function(){};a.prototype=c.prototype;var f=new a,g=c.apply(f,d.concat(p.call(arguments)));return Object(g)===g?g:f}return c.apply(b,d.concat(p.call(arguments)))};return e}),l.fontface=function(){var a;return r('@font-face {font-family:"font";src:url("https://")}',function(c,d){var e=b.getElementById("smodernizr"),f=e.sheet||e.styleSheet,g=f?f.cssRules&&f.cssRules[0]?f.cssRules[0].cssText:f.cssText||"":"";a=/src/i.test(g)&&g.indexOf(d.split(" ")[0])===0}),a};for(var z in l)t(l,z)&&(q=z.toLowerCase(),e[q]=l[z](),o.push((e[q]?"":"no-")+q));return e.addTest=function(a,b){if(typeof a=="object")for(var d in a)t(a,d)&&e.addTest(d,a[d]);else{a=a.toLowerCase();if(e[a]!==c)return e;b=typeof b=="function"?b():b,typeof enableClasses!="undefined"&&enableClasses&&(f.className+=" "+(b?"":"no-")+a),e[a]=b}return e},u(""),h=j=null,e._version=d,e.testStyles=r,e}(this,this.document);
if(!!navigator.userAgent.toLowerCase().match(/(android (2.0|2.1))|(windows phone os 7)/)) {
Modernizr.fontface=false;
}
if(!Modernizr.fontface || document.getElementsByTagName('html')[0].className.match(/\bie7\b/)){
jQuery('body').removeClass('top-bar-buttons_icon-style').addClass('top-bar-buttons_text-style');
jQuery('body').removeClass('mobile-top-bar-buttons_icon-style').addClass('mobile-top-bar-buttons_image-style');
}
(function(){
'use strict';
jQuery.noConflict();
jQuery("html").addClass("js");
var currentFontSize; 
var fontSizeDifference = 10; 
var cookieDaysExpire = 30; 
var tooltipPositionX; 
var tooltipPositionY;
var graphicMode = "default"; 
var isPlanelClosing = false; 
var userCustomLayoutWidth = 'auto'; 
//jQuery repeated elements:
var $body = jQuery('body');
function activateBodyCSS3Transition(){
$body
.addClass('css3-transition')
.delay(400)
.queue(function(){
jQuery(this)
.removeClass('css3-transition')
.dequeue();
});
}
var layoutWidthRailElementStyle;
function setLayoutWidthMode(layoutwidthmode){
if(userCustomLayoutWidth!=='auto' && layoutwidthmode!=='custom'){
jQuery('.layout-width-rail').each(function(){
layoutWidthRailElementStyle = jQuery(this).attr('style');
layoutWidthRailElementStyle = layoutWidthRailElementStyle.replace(new RegExp('width:(.....|....|...|..|.)(px|em|%);?','i'),'');
jQuery(this).attr('style',layoutWidthRailElementStyle);
userCustomLayoutWidth='auto';
});
}
if($body.hasClass('default-layout')){
$body
.removeClass("full-layout-width")
.removeClass("fixed-layout-width")
.removeClass("liquid-layout-width")
.removeClass("custom-layout-width")
.addClass(layoutwidthmode+"-layout-width");
}
else{
$body
.removeClass("full-layout-width")
.removeClass("fixed-layout-width")
.removeClass("liquid-layout-width")
.removeClass("custom-layout-width")
.addClass("full-layout-width");
}
zhongFramework.defaultLayoutWidthMode=layoutwidthmode;
}
function hideAccessibilityPanel(){
if(isPlanelClosing){ return; }
isPlanelClosing = true;
jQuery('#show-accessibility-panel-button')
.attr("title", zhongFramework.accessPanelTitle_closed)
.find('.top-bar-tool-text')
.text(zhongFramework.accessPanelText_closed);
$body
.removeClass("accessibility-panel-visible")
.addClass("accessibility-panel-hidden");
jQuery('#accessibility-panel')
.slideUp("slow")
.queue(function(){
isPlanelClosing = false;
jQuery(this).addClass('removed');
jQuery(this).dequeue();
});
}
function toggleAccessibilityPanelVisibility(){
if(isPlanelClosing){ return; }
if($body.hasClass('accessibility-panel-hidden')){
isPlanelClosing = true;
jQuery('#show-accessibility-panel-button')
.attr("title", zhongFramework.accessPanelTitle_open)
.find('.top-bar-tool-text')
.text(zhongFramework.accessPanelText_open);
$body
.removeClass("accessibility-panel-hidden")
.addClass("accessibility-panel-visible");
jQuery('#accessibility-panel')
.removeClass('removed')
.slideDown("slow")
.queue(function(){
isPlanelClosing = false;
jQuery(this).dequeue();
});
}
else{ hideAccessibilityPanel(); }
}
function biggerFontSize(bigger){
if(bigger){
currentFontSize = currentFontSize + fontSizeDifference;
if(currentFontSize > 400){currentFontSize = 400;} 
}
else{
currentFontSize = currentFontSize - fontSizeDifference;
if(currentFontSize < 40){currentFontSize = 40;} 
}
setFontSize(currentFontSize);
}
function revertFontSize(){
currentFontSize = zhongFramework.defaultFontSize;
setFontSize(zhongFramework.defaultFontSize);
}
function setFontSize(newFontSize){ $body.css('font-size',newFontSize+'%');	}
function saveUserSettings(){
var date = new Date();
date.setTime(date.getTime()+(cookieDaysExpire*24*60*60*1000)); 
var expires = "; expires="+date.toGMTString();
var cookieData = currentFontSize+'|'+zhongFramework.defaultLayoutWidthMode+'|'+graphicMode+'|'+userCustomLayoutWidth;
document.cookie = "zhongFrameworkUserSettingsJS="+cookieData+expires+"; path=/"; 
}
function getUserSettings(){
var userDataRaw = readCookie("zhongFrameworkUserSettingsJS");
if(userDataRaw===null){
revertFontSize();
setLayoutWidthMode(zhongFramework.defaultLayoutWidthMode);
return;
}
if(userDataRaw!==null){
var userData = userDataRaw.split('|');
setUserSettings(userData[0],userData[1],userData[2],userData[3]);
}
}
function readCookie(cookie_name){
var ca = document.cookie.split(';');
var cookie_name_equals = cookie_name+"=";
var c;
for(var i=0;i < ca.length;i++) {
c = ca[i];
while (c.charAt(0)===' '){ c = c.substring(1,c.length); } 
if (c.indexOf(cookie_name_equals) === 0) return c.substring(cookie_name_equals.length,c.length);
}
return null;
}
function setUserSettings(fontSize_cookie,layoutWidthMode_cookie,graphicMode_cookie,customLayoutWidth_cookie){
if($body.hasClass('full-access') || $body.hasClass('high-contrast')){
setFontSize(zhongFramework.defaultFontSize);
return;
}
if(typeof fontSize_cookie == 'undefined') {fontSize_cookie=zhongFramework.defaultFontSize;}
if(typeof layoutWidthMode_cookie == 'undefined') {layoutWidthMode_cookie=zhongFramework.defaultLayoutWidthMode;}
if(typeof graphicMode_cookie == 'undefined') {graphicMode_cookie='default';}
if(typeof customLayoutWidth_cookie == 'undefined') {customLayoutWidth_cookie=zhongFramework.defaultLayoutWidthMode;}
currentFontSize=zhongFramework.defaultFontSize;
if(parseFloat(fontSize_cookie.replace(",", "."))){
currentFontSize=parseFloat(fontSize_cookie.replace(",", "."));
}
setFontSize(currentFontSize);
if(customLayoutWidth_cookie!=='auto' && $body.hasClass('default-layout') && parseFloat(customLayoutWidth_cookie.replace(",",".").replace('px',''))){
$body.addClass('custom-layout-width')
.removeClass('full-layout-width').removeClass('liquid-layout-width').removeClass('fixed-layout-width');
jQuery('.layout-width-rail').css('width',customLayoutWidth_cookie);
userCustomLayoutWidth=customLayoutWidth_cookie;		
layoutWidthMode_cookie='custom';
setLayoutWidthMode(layoutWidthMode_cookie);
}
else{
if(layoutWidthMode_cookie!=='full' && layoutWidthMode_cookie!=='fixed' && layoutWidthMode_cookie!=='fluid'){
layoutWidthMode_cookie=zhongFramework.defaultLayoutWidthMode;
}
setLayoutWidthMode(layoutWidthMode_cookie);
}
if(graphicMode_cookie==="night" && !$body.hasClass('best-legibility')){
graphicMode = "night";
$body
.removeClass('default-graphic-mode')
.addClass('night-mode');
jQuery('#night-mode-switcher')
.attr('title',zhongFramework.dayModeLinkTitle)
.find('.top-bar-tool-text').text(zhongFramework.dayModeLinkText)
.parent().find('.zhongframework-icon-night-mode').attr('class','zhongframework-icon zhongframework-icon-day-mode');
}
}
function nightModeTransitionHandler(){
jQuery('#night-mode-switcher').click(function(e){
e.preventDefault();
activateBodyCSS3Transition(); 
if($body.hasClass('night-mode')){
jQuery('#night-mode-switcher')
.attr('title',zhongFramework.nightModeLinkTitle)
.find('.top-bar-tool-text').text(zhongFramework.nightModeLinkText)
.parent().find('.zhongframework-icon-day-mode').attr('class','zhongframework-icon zhongframework-icon-night-mode');
$body
.addClass('default-graphic-mode')
.removeClass('night-mode');
graphicMode='default';
}
else{
jQuery('#night-mode-switcher')
.attr('title',zhongFramework.dayModeLinkTitle)
.find('.top-bar-tool-text').text(zhongFramework.dayModeLinkText)
.parent().find('.zhongframework-icon-night-mode').attr('class','zhongframework-icon zhongframework-icon-day-mode');
$body
.addClass('night-mode')
.removeClass('default-graphic-mode');
graphicMode='night';
}
$body.delay(400)
.queue(function(){
jQuery(this)
.removeClass('css3-transition')
.dequeue();
});
saveUserSettings();
});
}
//It doesn't effect any content, it's intention is purely graphic 
function defaultLayoutModifications(){
if($body.hasClass('parent-cms-Joomla3')){
jQuery('#search-module-outer button')
.removeClass('btn-primary').removeClass('btn').removeClass('button');
jQuery('#login-module button')
.removeClass('btn-primary').removeClass('btn').removeClass('button');
}
}
function scrollToTopAnimationHandler(){
jQuery('#goto-top-block #goto-top').click(function(e){
e.preventDefault();
jQuery('body,html').animate({scrollTop:1},600);
});	
}
function sameHeightCustomModulesHandler(){
if(zhongFramework.sameHeightCustomModules){
jQuery('.custom-modules-container').each(function(){
var customModulesInTheRow = jQuery(this).find('.custom-module-inner');
if(customModulesInTheRow.length===1){return;}
var maxHeightCustomModule = customModulesInTheRow.eq(0).outerHeight();
for(var i=1;i<customModulesInTheRow.length;i++){ 
if(maxHeightCustomModule < customModulesInTheRow.eq(i).outerHeight()){
maxHeightCustomModule = customModulesInTheRow.eq(i).outerHeight();
}
}
customModulesInTheRow.css('min-height',maxHeightCustomModule+'px');
customModulesInTheRow.find('[class*=custom-module-style_]').css('min-height',maxHeightCustomModule+'px');
customModulesInTheRow.find('[class*=custom-module-style_] [class*=custom-module-style_]').css('min-height','0');
});
}
}
function userLayoutWidthResizeHandler(){
if(jQuery('html').hasClass('ie7')){jQuery('#layout-width-resize-tool-container').remove();return;}
if(zhongFramework.isPortable){jQuery('#layout-width-resize-tool-container').remove();return;}
if(jQuery('html').hasClass('ie8')){
jQuery('.layout-width-resize-handle').css('opacity','0');
jQuery('.layout-width-resize-handle').hover(
function(){jQuery(this).css('opacity','0.7');},
function(){jQuery(this).css('opacity','0');});
}
var currentLayoutWidth, currentPointerPosition, bodyStyleAttribute, resizeHandleDirection, customLayoutWidthPx, layoutWidthRailElements;
var disableSelectionByCSS = '-moz-user-select:none;-webkit-user-select:none;-ms-user-select:none;user-select:none;';
jQuery('.layout-width-resize-trigger')
.mousedown(function(e){
layoutWidthRailElements = jQuery('.layout-width-rail');
currentPointerPosition=e.pageX;
currentLayoutWidth=layoutWidthRailElements.eq(0).width();
$body
.addClass('custom-layout-width')
.removeClass('full-layout-width').removeClass('liquid-layout-width').removeClass('fixed-layout-width');
layoutWidthRailElements.css('width',currentLayoutWidth+'px');
if(jQuery(this).is('#layout-width-resize-handle_left') || jQuery(this).is('#layout-width-resize-icon'))
{resizeHandleDirection=1;}
else
{resizeHandleDirection=-1;}
$body.attr('unselectable','on').attr('onselectstart','return false;').attr('onmousedown','return false;');
bodyStyleAttribute = document.getElementsByTagName('body')[0].getAttribute('style');
document.getElementsByTagName('body')[0].setAttribute('style',bodyStyleAttribute+disableSelectionByCSS);
$body
.bind("mousemove",function(e){
customLayoutWidthPx = currentLayoutWidth+((currentPointerPosition-e.pageX)*2*resizeHandleDirection);
layoutWidthRailElements.css('width',customLayoutWidthPx);
})
.mouseup(function(){
$body.unbind("mousemove").unbind("mouseup");
userCustomLayoutWidth=jQuery('.layout-width-rail').css('width');
bodyStyleAttribute = document.getElementsByTagName('body')[0].getAttribute('style');
bodyStyleAttribute = bodyStyleAttribute.replace(disableSelectionByCSS,'');
document.getElementsByTagName('body')[0].setAttribute('style',bodyStyleAttribute);
$body.removeAttr('unselectable').removeAttr('onselectstart').removeAttr('onmousedown');
});
});
}
function jSnippets(){
if(!zhongFramework.isPortable && zhongFramework.tooltipsEnabled===true){
var tooltipElement, tooltipFather;
jQuery('.show-tooltip').hover(function(){
tooltipFather = jQuery(this);
tooltipElement = jQuery('<span class="tooltip-title">'+tooltipFather.attr('title')+'</span>')
$body.append(tooltipElement);
jQuery(this).attr('title','');
tooltipElement.fadeIn(460); 
jQuery(this).mousemove(function(e){
tooltipPositionX=e.pageX+16 - jQuery(window).scrollLeft();
tooltipPositionY=e.pageY+10 - jQuery(window).scrollTop();
if( tooltipPositionX+tooltipElement.width() > jQuery(window).width()-16 )
{ tooltipPositionX = jQuery(window).width()-tooltipElement.width()-16; }
if( tooltipPositionY+tooltipElement.height() > jQuery(document).height()-10 )
{ tooltipPositionY = jQuery(document).height()-tooltipElement.height()-10; }
tooltipElement 
.css('left',tooltipPositionX)
.css('top',tooltipPositionY);
});
},function(){ 
if(!(jQuery(this).attr('title'))){ 
jQuery(this).attr('title',tooltipElement.text()); 
}
tooltipElement.remove(); 
});
}
}
jQuery(document).ready(function(){
getUserSettings();
setTimeout(function(){
//Do the scrolling only if portable
if(jQuery(window).scrollTop()===0 && zhongFramework.isPortable){ 
window.scrollTo(0,1);
}
},400);
if(zhongFramework.isPortable && $body.hasClass('fixed-top-bar')){
$body.removeClass('fixed-top-bar');
}
if( $body.hasClass("default-layout") ){
$body.addClass('accessibility-panel-hidden');
jQuery('#accessibility-panel').addClass('removed');
jSnippets();
defaultLayoutModifications();
jQuery('#show-accessibility-panel-button').click(function(){
toggleAccessibilityPanelVisibility();
});
jQuery('#liquid-width-button').click(function(){ activateBodyCSS3Transition(); setLayoutWidthMode('liquid'); });
jQuery('#fixed-width-button').click(function(){ activateBodyCSS3Transition(); setLayoutWidthMode('fixed'); });
jQuery('#full-width-button').click(function(){ activateBodyCSS3Transition(); setLayoutWidthMode('full'); });
jQuery('#larger-font-button').click(function(){ biggerFontSize(true); });
jQuery('#reset-font-button').click(function(){ revertFontSize(); });
jQuery('#smaller-font-button').click(function(){ biggerFontSize(false); });
jQuery(document).keydown(function(e){
if (e.keyCode === 27 && $body.hasClass("accessibility-panel-visible")){
toggleAccessibilityPanelVisibility(); 
}
});
jQuery(":not(#accessibility-panel *)").bind("focus",function(){
if($body.hasClass('accessibility-panel-hidden') || isPlanelClosing){return;}
else{ hideAccessibilityPanel(); }
});
var keyboardAccessibleMenu = function(menuID){
var floatingMenuLinks = jQuery(menuID).find(".menu").find("a");
for (var i=0; i<floatingMenuLinks.length; i++){
floatingMenuLinks[i].onfocus=function(){ 
// If the selected link
if(jQuery(this).parent().next("li").children("ul").is("*")){
jQuery(this).parent().next("li").children("ul").removeClass('visible-floating-menu');
}
if(jQuery(this).parent().prev("li").children("ul").is("*")){
jQuery(this).parent().prev("li").children("ul").removeClass('visible-floating-menu');
}
if(jQuery(this).parent().children("ul").is("*")){
jQuery(this).parent().children("ul").addClass('visible-floating-menu');
}
};
}
jQuery(":not("+menuID+" *)").bind("focus",function(){
if(!jQuery(menuID).find('.visible-floating-menu').size()){ return; };
jQuery(menuID).find('.visible-floating-menu').each(function(){jQuery(this).removeClass('visible-floating-menu')});
});
}; 
if(zhongFramework.topMenuFloating){ 
keyboardAccessibleMenu('#main-menu-container');
}
if(zhongFramework.sideMenuFloating){ 
keyboardAccessibleMenu('#main-body');
}
nightModeTransitionHandler();
scrollToTopAnimationHandler();
userLayoutWidthResizeHandler();
} 
if( $body.hasClass("full-access") ){
jQuery('a[target="_blank"]').append('<span class="opens-new-window-link" title="'+zhongFramework.newWindowLinkText+'"> ( '+zhongFramework.newWindowLinkText+' )</span>');
//(Select all <a> elements that have 'href' starting with 'http://' && 'href' NOT CONTAINING 'site base url')
jQuery('a[href^="http://"]:not(a[href*="'+zhongFramework.http_host+'"])')
.append('<span class="external-link" title="'+zhongFramework.externalLinkText+'"> ( '+zhongFramework.externalLinkText+' )</span>');
} 
if( $body.hasClass("mobile-layout") ){
jSnippets();
defaultLayoutModifications();
jQuery('#night-mode-switcher').attr('role','button');
jQuery('#larger-font-button').click(function(){ biggerFontSize(true); });
jQuery('#reset-font-button').click(function(){ revertFontSize(); });
jQuery('#smaller-font-button').click(function(){ biggerFontSize(false); });
if( $body.hasClass("mobile_menus-navigation-mode_expandable") ){
jQuery('.menu-container .parent>ul').slideUp(0);
jQuery('.menu-container .parent>a').click(function(e){e.preventDefault();});
jQuery('.menu-container .parent').one('click',function(){
jQuery(this).children('ul').slideDown(400);
jQuery(this).children('a').click(function(){window.location=jQuery(this).attr('href');});
});
} 
jQuery('#mobile-top-bar-tool_fontsize-button').click(
function(e){
e.preventDefault();
if(jQuery(this).hasClass('active')){ jQuery(this).removeClass('active'); }
else{ jQuery(this).addClass('active'); }
jQuery('#mobile-top-bar_module-container_fontsize').slideToggle('slow');
});
jQuery('#mobile-top-bar-tool_login-button').click(
function(e){
e.preventDefault();
if(jQuery(this).hasClass('active')){ jQuery(this).removeClass('active'); }
else{ jQuery(this).addClass('active'); }
jQuery('#mobile-top-bar_module-container_login').slideToggle('slow');
});
jQuery('#mobile-top-bar-tool_search-button').click(
function(e){
e.preventDefault();
if(jQuery(this).hasClass('active')){ jQuery(this).removeClass('active'); }
else{ jQuery(this).addClass('active'); }
jQuery('#mobile-top-bar_module-container_search').slideToggle('slow');
});
jQuery('#mobile-top-bar-tool_language-button').click(
function(e){
e.preventDefault();
if(jQuery(this).hasClass('active')){ jQuery(this).removeClass('active'); }
else{ jQuery(this).addClass('active'); }
jQuery('#mobile-top-bar_module-container_language').slideToggle('slow');
});
nightModeTransitionHandler();
scrollToTopAnimationHandler();
} 
if( $body.hasClass("default-layout") || $body.hasClass("mobile-layout") ){
jQuery('#night-mode-switcher').attr('role','button');
}//END "Both in default & mobile layout"
}); /*--------- END "document.ready" ---------*/
jQuery(window).load(function(){
if( $body.hasClass("default-layout") ){
sameHeightCustomModulesHandler();
jQuery('.accordion-block')
.css("display","block")
.css("overflow","hidden")
.css("position","relative")
.append('<span class="accordion-block-readMore icon-show-action" aria-hidden="true"></span>') 
.css({ 
height:function(){
return jQuery(this).height()/100*20+jQuery(this).find(".accordion-block-readMore").height()+6;
}
});
jQuery('.accordion-block-readMore').click(function(){
if(jQuery(this).hasClass('icon-show-action')){
jQuery(this)
.removeClass('icon-show-action').addClass('icon-hide-action')
.parent()
.css({ 
height:function(){
return (jQuery(this).height()-jQuery(this).find(".accordion-block-readMore").height()-6)*100/20+jQuery(this).find(".accordion-block-readMore").height()+6;
}}
);
}
else{
jQuery(this)
.removeClass('icon-hide-action').addClass('icon-show-action')
.parent()
.css({ 
height:function(){
return (jQuery(this).height()-jQuery(this).find(".accordion-block-readMore").height()-6)/100*20+jQuery(this).find(".accordion-block-readMore").height()+6;
}
});
}
});	
}
}); /*--------- END "window load" ---------*/
jQuery(window).unload(function(){ saveUserSettings(); });
})();