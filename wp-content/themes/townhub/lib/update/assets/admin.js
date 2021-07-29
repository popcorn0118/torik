(function ($) {
    //shorthand for ready event.
    $(
        function () {
            $( document ).on( 'click', '.cththemes-notice .notice-dismiss', function (e) {
                e.preventDefault()
                // Read the "data-notice" information to track which notice
                // is being dismissed and send it via AJAX
                var type = $( this ).closest( '.cththemes-notice' ).data( 'notice' );
                // Make an AJAX call
                // Since WP 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                // console.log(type)
                $.ajax( ajaxurl,
                    {
                        type: 'POST',
                        data: {
                            action: 'cththemes_dismiss_notice',
                            type: type,
                        }
                    });
            } );
        }
    )

}(jQuery));