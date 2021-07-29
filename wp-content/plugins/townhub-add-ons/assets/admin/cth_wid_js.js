// handle media manager for backend edit listing
(function($) {
    "use strict";
    // When the DOM is ready.
    $(function() {
        // custom tax image select
        // Uploading files
        var media_frame;
        // var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
        // var set_to_post_id = 10; // Set this
        $(document).on('click', '.upload_image_button', function(event) {
            event.preventDefault();
            var $this = $(this),
                $fieldWrap = $(this).closest('.media-field-wrap');
            let meta_key = $this.attr('class').match(/metakey-\S*/g)[0].substring(8);
            // console.log(meta_key);
            let field_key = $this.attr('class').match(/fieldkey-\S+/g)[0].substring(9);
            // console.log(field_key);
            let nkey = meta_key;
            if (field_key != '') nkey += "[" + field_key + "]";
            if (meta_key == '') nkey = field_key;
            nkey = nkey.replace(/\[/g,'_').replace(/\]/g,'_')
            // console.log(nkey);
            // If the media frame already exists, reopen it.
            // if (media_frame) {
            //     // Set the post ID to what we want
            //     // media_frame.uploader.uploader.param('post_id', set_to_post_id);
            //     // Open frame
            //     media_frame.open();
            //     return;
            // } else {
            //     // Set the wp.media post id so the uploader grabs the ID we want when initialised
            //     // wp.media.model.settings.post.id = set_to_post_id;
            // }
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
                // console.log(attachment);
                // console.log($fieldWrap.find(`.${nkey}_id`));
                if ($fieldWrap.find(`.${nkey}_id`).length) $fieldWrap.find(`.${nkey}_id`).val(attachment.id);
                if ($fieldWrap.find(`.${nkey}_url`).length) $fieldWrap.find(`.${nkey}_url`).val(attachment.url);
                if ($fieldWrap.find(`.${nkey}_preview`).length) $fieldWrap.find(`.${nkey}_preview`).attr('src', attachment.url).css('display', 'block');
                // jQuery("input[id='"+meta_key+"["+field_key+"][id]"+"']").val(attachment.id);
                // jQuery("input[id='"+meta_key+"["+field_key+"][url]"+"']").val(attachment.url);
                // jQuery("img[id='"+meta_key+"["+field_key+"][preview]"+"']").attr('src',attachment.url).css('display','block');
                $this.next('.remove_image_button').show();
                // Restore the main post ID
                // wp.media.model.settings.post.id = wp_media_post_id;
            });
            // Finally, open the modal
            media_frame.open();
        });
        $(document).on('click', '.remove_image_button', function(event) {
            event.preventDefault();
            var $this = $(this),
                $fieldWrap = $(this).closest('.media-field-wrap');
            let meta_key = $this.attr('class').match(/metakey-\S*/g)[0].substring(8); //console.log(meta_key);
            let field_key = $this.attr('class').match(/fieldkey-\S+/g)[0].substring(9); //console.log(field_key);
            let nkey = meta_key;
            if (field_key != '') nkey += "[" + field_key + "]";
            if (meta_key == '') nkey = field_key;
            nkey = nkey.replace(/\[/g,'_').replace(/\]/g,'_')
            if ($fieldWrap.find(`.${nkey}_id`).length) $fieldWrap.find(`.${nkey}_id`).val('');
            if ($fieldWrap.find(`.${nkey}_url`).length) $fieldWrap.find(`.${nkey}_url`).val('');
            if ($fieldWrap.find(`.${nkey}_preview`).length) $fieldWrap.find(`.${nkey}_preview`).attr('src', '').css('display', 'none');
            // jQuery("input[id='"+meta_key+"["+field_key+"][id]"+"']").val('');
            // jQuery("input[id='"+meta_key+"["+field_key+"][url]"+"']").val('');
            // jQuery("img[id='"+meta_key+"["+field_key+"][preview]"+"']").attr('src','').css('display','none');
            $this.hide();
            //$this.removeClass('remove_image_button button-secondary').addClass('upload_image_button button-primary').text('Upload Image');
        });
    });
})(jQuery);