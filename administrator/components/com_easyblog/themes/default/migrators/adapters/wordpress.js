EasyBlog.ready(function($) {

	$('[data-migrate-xmlwp]').on('click', function() {

		if( $('[data-xml-wordpress]').val() === null)
		{
			 EasyBlog.dialog(
                    {
                        content     : 'Please select xml file to proceed.',

                    });

		    return;
		}
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
		var xmlFile = $('[data-xml-wordpress]').val(),
			authorId = $('[data-author-id]').val()

		EasyBlog.ajax('admin/views/migrators/migrateArticle',
		{
			"authorId"	: authorId,
			"xmlFile" : xmlFile,
			"component"	: "xml_wordpress"
		},
		{
			append: function(selector, message) {
				$(selector).append(message);
			}
		})
		.done(function(result)
		{
			if (result == 'parseFailed') {
				$('[data-migrate-xmlwp]').removeAttr('disabled');
				$('[data-migrate-xmlwp]').html('<?php echo JText::_('COM_EASYBLOG_MIGRATOR_RUN_NOW', true);?>');
				$('[data-progress-status]').html('<?php echo JText::_('COM_EASYBLOG_PARSE_FAILED', true);?>');
			}

			if (result == 'fileNotExist') {
				$('[data-migrate-xmlwp]').removeAttr('disabled');
				$('[data-migrate-xmlwp]').html('<?php echo JText::_('COM_EASYBLOG_MIGRATOR_RUN_NOW', true);?>');
				$('[data-progress-status]').html('<?php echo JText::_('COM_EASYBLOG_FILE_DOES_NOT_EXIST', true);?>');
			}

			//remove loading icon.
			$('[data-progress-loading]').addClass('hide');
			
			if (result == 'success') {
				$('[data-migrate-xmlwp]').removeAttr('disabled');
				$('[data-migrate-xmlwp]').html('<i class="fa fa-check"></i> <?php echo JText::_('COM_EASYBLOG_COMPLETED', true);?>');
			}

			if (result == 'noitem'){
				$('[data-migrate-xmlwp]').removeAttr('disabled');
				$('[data-migrate-xmlwp]').html('<?php echo JText::_('COM_EASYBLOG_MIGRATOR_RUN_NOW', true);?>');
				$('[data-progress-status]').html('<?php echo JText::_('COM_EASYBLOG_NO_ITEM', true);?>');
			}
		});
	}

	window.divSrolltoBottomWordPressXML = function() {

		var objDiv = document.getElementById("progress-status6");
	    objDiv.scrollTop = objDiv.scrollHeight;

		var objDiv2 = document.getElementById("stat-status6");
	    objDiv2.scrollTop = objDiv2.scrollHeight;
	}

});
