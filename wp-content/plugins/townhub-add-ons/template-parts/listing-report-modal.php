<?php
/* add_ons_php */
?>
<div class="ctb-modal-wrap ctb-modal" id="ctb-listing-report-modal">
    <div class="ctb-modal-holder">
        <div class="ctb-modal-inner modal_main_dis">
            <div class="ctb-modal-close"><i class="fa fa-times"></i></div>
            <div class="ctb-modal-title"><?php echo sprintf( __( 'Report listing: <span class="lauthor-msg-title">%s</span>', 'townhub-add-ons' ), get_the_title() );?></div>
            <div class="ctb-modal-content">
                <?php do_action( 'townhub-addons-report-form-before' ); ?>
                <form class="listing-report-form custom-form" action="#" method="POST">
                    <fieldset>
                        <?php 
                        if( townhub_addons_get_option('report_must_login') != 'yes' && !is_user_logged_in() ): ?>
                        <input type="text" name="uname" value="" placeholder="<?php echo esc_attr_x( 'Your name', 'Report listing', 'townhub-add-ons' );?>" required="required">
                        <input type="text" name="uemail" value="" placeholder="<?php echo esc_attr_x( 'Your email', 'Report listing', 'townhub-add-ons' );?>" required="required">
                        <?php endif; ?>
                        <?php do_action( 'townhub-addons-report-form' ); ?>
                        <textarea name="report_message" cols="40" rows="3" placeholder="<?php esc_attr_e( 'Additional information here.', 'townhub-add-ons' ); ?>" required="required"></textarea>
                    </fieldset>
                    <input type="hidden" name="listing_id" value="<?php echo get_the_ID(); ?>">
                    <button class="btn color-bg" type="submit" id="lreport-submit"><?php _e( 'Submit', 'townhub-add-ons' ); ?></button>
                </form>
                <?php do_action( 'townhub-addons-report-form-after' ); ?>
            </div>
            <!-- end modal-content -->
        </div>
    </div>
</div>
<!-- end modal --> 