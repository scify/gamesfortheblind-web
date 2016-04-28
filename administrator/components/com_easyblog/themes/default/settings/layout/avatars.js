
EasyBlog.ready(function($){

    $('[data-avatar-source]').on('change', function(){
        var source = $(this).val();

        if (source == 'phpbb') {
            $('[data-phpbb-path]').removeClass('hidden');

            return;
        }

        $('[data-phpbb-path]').addClass('hidden');
    });
});