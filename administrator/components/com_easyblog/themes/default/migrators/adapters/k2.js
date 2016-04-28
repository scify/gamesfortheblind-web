EasyBlog.ready(function($){

	$( "#migrate_k2_all" ).change(function() {
  		if ($( "#migrate_k2_all" ).val() == 1){
  			$('[data-category-dropdown]').hide();
  		} 
  		else{
  			$('[data-category-dropdown]').show();
  		}

	});


	$('[data-migrate-k2]').on('click', function() {

		// Disable the button from being clicked twice
		$(this).attr('disabled', "true");

		// Update the buttons message
		$(this).html('<i class="fa fa-cog fa-spin"></i> <?php echo JText::_('COM_EASYBLOG_UPDATING', true);?>');

		// Hide the no progress message
		$('[data-progress-empty]').addClass('hide');

		// Ensure that the progress is always reset to empty just in case the user runs it twice.
		$('[data-progress-status]').html('');

		// clear the stats.
		$('[data-progress-stat]').html('');

		//show the loading icon
		$('[data-progress-loading]').removeClass('hide');

		// //process the migration
		window.migrateArticle();

	});

	window.migrateArticle = function() {

		// Get the values from the form
		var categoryId = $('[data-migrate-k2-category]').val(),
			migrateComment = $( "#migrate_k2_comments" ).val(),
			migrateAll = $( "#migrate_k2_all" ).val();
			if(migrateAll == 1){
				categoryId = '';
			}

		EasyBlog.ajax('admin/views/migrators/migrateArticle',
		{
			"component"	: "com_k2",
			"migrateComment"	: migrateComment,
			"categoryId" : categoryId,
			"migrateAll" : migrateAll
		},
		{
			append: function(selector, message) {
				$(selector).append(message);
			}
		})
		.done(function(result)
		{
			// If there's still items to render, run a recursive loop until it doesn't have any more items;
			if (result == true) {
				window.migrateArticle();
				return;
			}

			//remove loading icon.
			$('[data-progress-loading]').addClass('hide');

			$('[data-migrate-k2]').removeAttr('disabled');
			$('[data-migrate-k2]').html('<i class="fa fa-check"></i> <?php echo JText::_('COM_EASYBLOG_COMPLETED', true);?>');

			if (result == 'noitem'){
				$('[data-migrate-k2]').removeAttr('disabled');
				$('[data-migrate-k2]').html('<?php echo JText::_('COM_EASYBLOG_MIGRATOR_RUN_NOW', true);?>');
				$('[data-progress-status]').html('<?php echo JText::_('COM_EASYBLOG_NO_ITEM', true);?>');
			}

		});
	}


});
