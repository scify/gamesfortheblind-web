function getStats(selmenu, selsubmenu){
	switch(selmenu){
		case 'article':
			$('menu_types').setStyle('display', 'none');			
			break;
		case 'menuitems':
			$('menu_types').setStyle('display', 'block');
			$('mtree').setStyle('display', 'none');
			$('ktwo').setStyle('display', 'none');
			$('zoo').setStyle('display', 'none');
			$('kunena').setStyle('display', 'none');
			$('easyblog').setStyle('display', 'none');
			break;
		case 'mtree':
			$('menu_types').setStyle('display', 'none');
			$('mtree').setStyle('display', 'block');
			$('ktwo').setStyle('display', 'none');
			$('zoo').setStyle('display', 'none');
			$('easyblog').setStyle('display', 'none');
			$('kunena').setStyle('display', 'none');
			break;
		case 'zoo':
			$('menu_types').setStyle('display', 'none');
			$('mtree').setStyle('display', 'none');
			$('ktwo').setStyle('display', 'none');
			$('zoo').setStyle('display', 'block');
			$('easyblog').setStyle('display', 'none');
			$('kunena').setStyle('display', 'none');
			break;	
		case 'ktwo':
			$('menu_types').setStyle('display', 'none');
			$('mtree').setStyle('display', 'none');
			$('zoo').setStyle('display', 'none');
			$('easyblog').setStyle('display', 'none');
			$('ktwo').setStyle('display', 'block');
			$('kunena').setStyle('display', 'none');
			break;	
		case 'kunena':
			$('menu_types').setStyle('display', 'none');
			$('mtree').setStyle('display', 'none');
			$('zoo').setStyle('display', 'none');
			$('ktwo').setStyle('display', 'none');
			$('kunena').setStyle('display', 'block');
			$('easyblog').setStyle('display', 'none');
			break;	
		case 'easyblog':
			$('menu_types').setStyle('display', 'none');
			$('mtree').setStyle('display', 'none');
			$('zoo').setStyle('display', 'none');
			$('easyblog').setStyle('display', 'block');
			$('ktwo').setStyle('display', 'none');
			$('kunena').setStyle('display', 'none');
			break;	
	}
	
	for(var i=1; i<=7; i++){
		var req = new Request.HTML({
			method: 'get',
			url: 'components/com_ijoomla_seo/javascript/ajax.php?tasks=stats&stats='+i+'&selmenu='+selmenu+'&selsubmenu='+selsubmenu,
			data: { 'do' : '1' },
			//onRequest: function() { alert('Request made. Please wait...'); },
			update: $('stat'+i),
			onComplete: function(response){		
			}
		}).send();
	}
}