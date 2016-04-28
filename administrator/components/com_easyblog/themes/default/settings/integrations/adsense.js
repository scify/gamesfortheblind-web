
EasyBlog.ready(function($) {

	$('[data-adsense-responsive]').on('change', function(){
		var value = $(this).val();

		if (value == 1) {
			$('[data-responsive-form]').removeClass('hide');
			$('[data-code-form]').addClass('hide');

			return;
		}

		$('[data-responsive-form]').addClass('hide');
		$('[data-code-form]').removeClass('hide');
			
	});
});