function alertNotification(){
	document.getElementById("pub-not-content").className = "open";
}

$(document).click(function(e){
	if ($(e.target).attr('id') != 'pub-dropdown-toggle'){
		document.getElementById("pub-not-content").className = "";
	}
})