function changeDiagram(){
	up = document.getElementById("ij-graf-up").checked;
	down = document.getElementById("ij-graf-down").checked;
	same = document.getElementById("ij-graf-same").checked;
	
	count = 0;
	if(up){
		count ++;	
	}
	
	if(down){
		count ++;	
	}
	
	if(same){
		count ++;
	}
	
	if(count == 1){
		if(up){
			document.getElementById("ij-graf-up").disabled = true;
		}
		
		if(down){
			document.getElementById("ij-graf-down").disabled = true;
		}
		
		if(same){
			document.getElementById("ij-graf-same").disabled = true;
		}
	}
	else{
		document.getElementById("ij-graf-up").disabled = false;
		document.getElementById("ij-graf-down").disabled = false;
		document.getElementById("ij-graf-same").disabled = false;
	}
	
	doPlot("right");
}

function jSelectArticle(id, title, object){
	document.getElementById('id_name').value = title;
	document.getElementById('id_id').value = id;
	window.parent.SqueezeBox.close();
}

function changeColor(id, spanId, color){
	document.getElementById(spanId+"_"+id).style.color = color;
}

function unColor(textareaName){
	var obj = document.getElementsByName(textareaName)[0];
	if(window.parentNode !== undefined){
		var par = obj.parentNode.parentNode.parentNode.parentNode;
		par.style.backgroundColor = 'transparent';
	}
}

function countKey(obj, no, i, type, maxNum){
	if(type == "Words"){
		var myArray = no.split(' ');
		
		for(var k = myArray.length - 1; k >= 0; k--) {
			if(myArray[k] === "") {
				myArray.splice(k, 1);
			}
		}
		
		var numar = maxNum - myArray.length;
	}
	else{
		var numar = maxNum - no.length;
	}
	document.getElementById("no_"+i).innerHTML = numar;
	if(numar < 0)
	changeColor(i, 'no', 'red')
	else changeColor(i, 'no', '#666666')
	var par = obj.parentNode.parentNode.parentNode.parentNode;
	if(numar != maxNum){
		par.style.backgroundColor='transparent';
	}
	else par.style.backgroundColor='#ffffcc';
}

function countDesc(obj, dtext, id, type, maxNum){
	maxNum = parseInt(maxNum);
	if(type == "Words"){
		var words = dtext.split(/[^\w\d-]+/g);
		var len = words.length;
		for(var i=0; i<len; i++){
			if(!words[i]){
				words.splice(i,1);
				len--; break;
			}
		}
		
		for(var k = words.length - 1; k >= 0; k--) {
			if(words[k] === "") {
				words.splice(k, 1);
			}
		}
		
		var numar = maxNum - words.length;
	}
	else{
		var numar = maxNum - dtext.length;
	}
	
	document.getElementById("do_"+id).innerHTML = numar;
	if(numar < 0)
		changeColor(id, 'do', 'red')
	else changeColor(id, 'do', '#666666')
	var par = obj.parentNode.parentNode.parentNode.parentNode;
	
	if(dtext.length >0){
		par.style.backgroundColor='transparent';
	}
	else{
		par.style.backgroundColor='#ffffcc';
	}
}

function countTitle(obj, no, i, type, maxNum){
	if(type == "Words"){
		var myArray = no.split(' ');
		
		for(var k = myArray.length - 1; k >= 0; k--) {
			if(myArray[k] === "") {
				myArray.splice(k, 1);
			}
		}
		
		var numar = maxNum - myArray.length;
	}
	else{
		var numar = maxNum - no.length;
	}
	
	document.getElementById("go_"+i).innerHTML = numar;
	if(numar < 0)
	changeColor(i, 'go', 'red')
	else changeColor(i, 'go', '#666666')
	var par = obj.parentNode.parentNode.parentNode.parentNode;
	if(numar != maxNum) {
		par.style.backgroundColor='transparent';
	}
	else par.style.backgroundColor='#ffffcc';
}

function showSpecificMenu(what_to_show, controller) {
	$('list_menus').css('display', 'none');
	$('list_mtree').css('display', 'none');
	$('list_zoo').css('display', 'none');
	$('list_ktwo').css('display', 'none');
	$('list_kunena').css('display', 'none');
	$('list_easyblog').css('display', 'none');
	
	if (what_to_show) {
		$(what_to_show).css('display', 'block');
	}
	
	document.adminForm.task.value = controller;
	document.adminForm.controller.value = controller;
	document.adminForm.submit();
}

function showMenu(selected){
	if (selected != "articles"){
		document.getElementById("articles-light").style.display = "";
	}
}

function f_refresh(){
	var mtitle = document.metatags.mtitle.value;
	var metakey = document.metatags.metakey.value;
	var metadesc = document.metatags.metadesc.value;
	var id = document.metatags.id.value;
	window.parent.document.location = 'index.php?option=com_ijoomla_seo&controller=pages&task=savepage&mtitle='+mtitle+'&metakey='+metakey+'&metadesc='+metadesc+'&id='+id;
	window.parent.SqueezeBox.close();	
}

function f_refresh2(){
	var mtitle = document.metatags.mtitle.value;
	var metakey = document.metatags.metakey.value;
	var metadesc = document.metatags.metadesc.value;
	var id = document.metatags.id.value;
	window.parent.document.location = 'index.php?option=com_ijoomla_seo&controller=keysarticles&task=savepage&mtitle='+mtitle+'&metakey='+metakey+'&metadesc='+metadesc+'&id='+id;
	window.parent.SqueezeBox.close();
}

function changeMenu() {
	var type = document.getElementById('type').value;
	if(type == "1"){
		document.getElementById('t_article').style.display = "block";
		document.getElementById('t_menu').style.display = "none";
		document.getElementById('t_menu_2').style.display = "none";
		document.getElementById('t_url').style.display = "none";	
	}
	else if(type == "2"){
		document.getElementById('t_article').style.display = "none";
		document.getElementById('t_menu').style.display = "block";
		document.getElementById('t_menu_2').style.display = "block";
		document.getElementById('t_url').style.display = "none";
	}
	else if(type == "3"){
		document.getElementById('t_article').style.display = "none";
		document.getElementById('t_menu').style.display = "none";
		document.getElementById('t_menu_2').style.display = "none";
		document.getElementById('t_url').style.display = "block";	
	}
    else if(type == "4") {
		document.getElementById('t_article').style.display = "none";
		document.getElementById('t_menu').style.display = "none";
		document.getElementById('t_menu_2').style.display = "none";
		document.getElementById('t_url').style.display = "none";
    }    
 }
 
function fieldSort(field, sort, num){
	var form =  document.adminForm;
	form.col.value = field; 
	form.colnum.value = num;
	updown = sort.value;
	if(updown == '' || updown == 'desc'){ 
		sort.value = 'asc'; 
	}	
	else{ 
		sort.value = 'desc';
	}	
}

function getMenuItems(value){
	var req = new Request.HTML({
		method: 'get',
		url: 'index.php?option=com_ijoomla_seo&controller=newilinks&task=changeMenuItems&menu_type='+value,
		data: { 'do' : '1' },
		onComplete: function(response){
			document.getElementById("t_menu_2").empty().adopt(response);
		}
	}).send();
}

function changeSticky(image, path){
	alt = image.alt;
	var num = (image.id).substring(6);
	var id = parseInt(document.getElementById("cb"+num).value);
	var onoff = null;
					
	if(alt == 'sticky_off') {		
		onoff = 1;
	}
	else {
		onoff = 0;
	}

	url = path+'index.php?option=com_ijoomla_seo&controller=keys&task=change_sticky&sid='+id+'&onoff='+onoff+'&tmpl=component';

	var req = new Request.HTML({
		method: 'get',
		url: url,
		async:false,
		data: { 'do' : '1' },
		onComplete: function(response){
			if(alt == 'sticky_off') {
				image.alt = 'sticky_on';
				image.src = path+'components/com_ijoomla_seo/images/sticky_on.gif';
			}
			else {
				image.alt = 'sticky_off';
				image.src = path+'components/com_ijoomla_seo/images/sticky_off.gif';
			}
		}
	}).send();
}

function getRank(key, i, path, search_count){
	key = key.replace("&", "*and*");
	var grank = 'rank'+i;
	var gchange = 'change'+i;
	document.getElementById(gchange).innerHTML = '';
	oldrank = ($(grank).innerHTML == "-")? 0: parseInt($(grank).innerHTML);
	
	url = 'index.php?option=com_ijoomla_seo&controller=keys&key='+key+'&task=get_Grank&oldrank='+oldrank;
	
	var req = new Request.HTML({
		method: 'get',
		url: url,
		data: { 'do' : '1' },
		onComplete: function(response){
			document.getElementById(gchange).empty().adopt(response);
			if(document.getElementById(gchange).innerHTML == "0"){
				alert("Your keyword doesn't appear in the first "+search_count+" search results of Google!");
			}
			if($(gchange).innerHTML != 0 && $(grank).innerHTML == "-"){										
				$(grank).innerHTML = $(gchange).innerHTML;										
			}
			changeImg(i, key, path);
		}
	}).send();	
}

// function called after the ajax responses to the request
function changeImg(i, title, path){
	grank = 'rank'+i;
	gchange = 'change'+i;
	
	// old G rank
	if($(grank).innerHTML == '-')	{				
		vrank = 0; 						
	}									
	else{
		vrank = parseInt($(grank).innerHTML);					
	}
	
	// new G rank
	if($(gchange).innerHTML == '-'){
		vchange = 0;	
		out = '-';					
	}
	else{					
		vchange = parseInt($(gchange).innerHTML);
		if(vrank){ 
			out = vchange;
		}
		else{
			out = '-';
		}
	}
	
	$(grank).innerHTML = out;
	
	var change = 0;	
	if(vchange && vchange != vrank){
		change = Math.abs(vchange - vrank);	
	}
		
	if(change){
		val = change;
	}
	else{
		val = '-';
	}
	
	var mode = -1;			
	if((vchange > vrank || vchange == 0) && vrank > 0 && change>0){						
		$(gchange).innerHTML = '<span style="color:red">'+val+'</span>'+'&nbsp;&nbsp;<img src="'+path+'images/down.gif" border="0" alt="down" align="absmiddle"/>';
			mode = 0;
	}
	
	else if((vchange < vrank || vrank == 0) && vchange > 0 && change>0){
		$(gchange).innerHTML = '<span style="color:green">'+val+'</span>'+'&nbsp;&nbsp;<img src="'+path+'images/up.gif" border="0" alt="up" align="absmiddle"/>';
			mode = 1;
	}
	else{
		$(gchange).innerHTML = '-';
	}
	
	var req = new Request.HTML({
		method: 'get',
		url: 'index.php?option=com_ijoomla_seo&controller=keys&task=change&val='+change+'&key='+title+'&mode='+mode,
		data: { 'do' : '1' },
		onComplete: function(resp){ 
		}
	}).send();
}

function insideArticles(action){
	if(action == "yes"){
		document.getElementById("inside_articles").style.display = "block";
	}
	else if(action == "no"){
		document.getElementById("inside_articles").style.display = "none";
	}
}

function changeSource(value){
	if(value == 0){
		document.getElementById("menu-items").style.display = "none";
		document.getElementById("jomsocial").style.display = "none";
		document.getElementById("zoo").style.display = "none";
		document.getElementById("k2").style.display = "none";
		document.getElementById("easy-blog").style.display = "none";
		
		document.getElementById("missing-title").innerHTML = "0";
		document.getElementById("missing-keys").innerHTML = "0";
		document.getElementById("missing-desc").innerHTML = "0";
	}
	else if(value == 1){ // Articles
		document.getElementById("menu-items").style.display = "none";
		document.getElementById("jomsocial").style.display = "none";
		document.getElementById("zoo").style.display = "none";
		document.getElementById("k2").style.display = "none";
		document.getElementById("easy-blog").style.display = "none";
		
		var req = new Request.HTML({
			method: 'get',
			url: 'index.php?option=com_ijoomla_seo&controller=about&task=stats&source=articles&value=title',
			data: { 'do' : '1' },
			update: 'missing-title',
			onComplete: function(response){
			}
		}).send();
		
		var req = new Request.HTML({
			method: 'get',
			url: 'index.php?option=com_ijoomla_seo&controller=about&task=stats&source=articles&value=keys',
			data: { 'do' : '1' },
			update: 'missing-keys',
			onComplete: function(response){
			}
		}).send();
		
		var req = new Request.HTML({
			method: 'get',
			url: 'index.php?option=com_ijoomla_seo&controller=about&task=stats&source=articles&value=desc',
			data: { 'do' : '1' },
			update: 'missing-desc',
			onComplete: function(response){
			}
		}).send();
	}
	else{
		document.getElementById("statistics-light").style.display = "";
	}
}

function closePopUp(modal_id){
	document.getElementById(modal_id).style.display = "none";
}