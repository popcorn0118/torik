<?php
/* add_ons_php */
?>
<div class="ctb-modal-wrap ctb-modal" id="ctb-listing-claim-modal">
    <div class="ctb-modal-holder">
        <div class="ctb-modal-inner modal_main_dis">
            <div class="ctb-modal-close"><i class="fa fa-times"></i></div>
            <div class="ctb-modal-title"><?php echo sprintf( __( 'Claim listing: <span class="lauthor-msg-title">%s</span>', 'townhub-add-ons' ), get_the_title() );?></div>
            <div class="ctb-modal-content">
                <?php do_action( 'townhub-addons-claim-form-before' ); ?>
                <form class="listing-claim-form custom-form" action="#" method="POST">
                    <fieldset>
                        <?php do_action( 'townhub-addons-claim-form' ); ?>
                        <textarea name="claim_message" cols="40" rows="3" placeholder="<?php esc_attr_e( 'Additional information here.', 'townhub-add-ons' ); ?>" required="required"></textarea>
                    </fieldset>
                    <input type="hidden" name="listing_id" value="<?php echo get_the_ID(); ?>">
                    <button class="btn color-bg" type="submit" id="lclaim-submit"><?php _e( 'Submit', 'townhub-add-ons' ); ?></button>
                </form>
                <?php do_action( 'townhub-addons-claim-form-after' ); ?>
            </div>
            <!-- end modal-content -->
        </div>
    </div>
</div>
<!-- end modal --> 