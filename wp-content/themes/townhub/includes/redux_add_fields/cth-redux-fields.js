
// handle media manager for backend edit listing
(function($) {
    "use strict";
    // When the DOM is ready.
    $(function() {
        // custom tax image select
        // Uploading files
        var media_frame;
        $(document).on('click', '.cth-redux-image', function(event) {
            event.preventDefault();
            var $this = $(this),
                $fieldWrap = $(this).closest('.media-field-wrap');
            
            let meta_key = $this.attr('class').match(/metakey-\S*/g)[0].substring(8);
            let field_key = $this.attr('class').match(/fieldkey-\S*/g)[0].substring(9);
            // console.log(meta_key);
            // console.log(field_key);
            let nkey = meta_key;
            if( field_key != '' ) nkey += "["+field_key+"]";
            if( meta_key == '' ) nkey = field_key;
            nkey = nkey.replace(/\[/g,'_').replace(/\]/g,'_')

            // console.log(nkey);
            // console.log($fieldWrap.find(`.${nkey}_id`).length);
            // Create the media frame.
            media_frame = wp.media.frames.media_frame = wp.media({
                title: $this.data('uploader_title'),
                button: {
                    text: $this.data('uploader_button_text'),
                },
                multiple: false // Set to true to allow multiple files to be selected
            });
            // When an image is selected, run a callback.
            media_frame.on('select', function() {
                // We set multiple to false so only get one image from the uploader
                let attachment = media_frame.state().get('selection').first().toJSON();
                // Do something with attachment.id and/or attachment.url here
                if( $fieldWrap.find(`.${nkey}_id`).length ) $fieldWrap.find(`.${nkey}_id`).val(attachment.id);
                if( $fieldWrap.find(`.${nkey}_url`).length ) $fieldWrap.find(`.${nkey}_url`).val(attachment.url);
                if( $fieldWrap.find(`.${nkey}_preview`).length ) $fieldWrap.find(`.${nkey}_preview`).attr('src',attachment.url).css('display','block');
                $this.next('.cth-redux-image-remove').show();
            });
            // Finally, open the modal
            media_frame.open();
        });
        $(document).on('click', '.cth-redux-image-remove', function(event) {
            event.preventDefault();
            var $this = $(this),
                $fieldWrap = $(this).closest('.media-field-wrap');
            
            let meta_key = $this.attr('class').match(/metakey-\S*/g)[0].substring(8);
            let field_key = $this.attr('class').match(/fieldkey-\S*/g)[0].substring(9);
            
            let nkey = meta_key;
            if( field_key != '' ) nkey += "["+field_key+"]";
            if( meta_key == '' ) nkey = field_key;
            nkey = nkey.replace(/\[/g,'_').replace(/\]/g,'_')
            if( $fieldWrap.find(`.${nkey}_id`).length ) $fieldWrap.find(`.${nkey}_id`).val('');
            if( $fieldWrap.find(`.${nkey}_url`).length ) $fieldWrap.find(`.${nkey}_url`).val('');
            if( $fieldWrap.find(`.${nkey}_preview`).length ) $fieldWrap.find(`.${nkey}_preview`).attr('src','').css('display','none');
            $this.hide();
        });


    });



})(jQuery);