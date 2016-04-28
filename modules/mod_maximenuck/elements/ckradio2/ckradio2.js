window.addEvent('domready', function() {

    $$('.radioClass').each(function(el2){el2.setStyle('opacity','0'); });
    
    var monimage = $$('.boutonRadio');
    monimage.each(function(el) {
        el.addEvent('click',function(){
			var boutoncontainer = el.getParent(); 
            boutoncontainer.getElements('.boutonRadio').removeClass('coche');
            el.addClass('coche');
            var moninput = el.getFirst();
            
            // test
            var identifier = el.getProperty('identifier');
            if ($(identifier)) $(identifier).value = moninput.value;
            boutoncontainer.getElements('.radioClass').each(function(el2){
				el2.removeAttribute("checked","checked"); 
			});
                moninput.setAttribute("checked","checked");
            });
        });
    });