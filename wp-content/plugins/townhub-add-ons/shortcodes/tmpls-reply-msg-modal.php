<?php
/* add_ons_php */

?>
<div class="ctb-modal-wrap ctb-modal" id="ctb-reply-msg-modal">
    <div class="ctb-modal-holder">
        <div class="ctb-modal-inner modal_main">
            <div class="ctb-modal-close"><i class="fa fa-times"></i></div>
            <div class="ctb-modal-title"><?php _e( 'Reply to ', 'townhub-add-ons' );?><span class="lauthor-msg-title"><?php esc_html_e( 'Message', 'townhub-add-ons' ); ?></span></div>
            <div class="ctb-modal-content">
                
                <form class="author-message-form author-message-dashboard custom-form" action="#" method="post">
                    <?php do_action( 'townhub_author_reply_form_before' ); ?>
                    <fieldset>
                        <textarea name="lmsg_message" cols="40" rows="3" placeholder="<?php esc_attr_e( 'Your message:', 'townhub-add-ons' ); ?>"></textarea>
                    </fieldset>
                    <?php do_action( 'townhub_author_reply_form_after' ); ?>
                    <div class="author-message-error"></div>
                    <button class="btn color2-bg author-msg-submit" type="submit"><?php _e( 'Send Message <i class="fal fa-paper-plane"></i>', 'townhub-add-ons' ); ?></button>
                    <input type="hidden" name="authid" value="">
                    <input type="hidden" name="first_msg" value="">
                    <input type="hidden" name="reply_to" value="">
                </form>
            </div>
            <!-- end modal-content -->
        </div>
    </div>
</div>
<!-- end modal --> 
