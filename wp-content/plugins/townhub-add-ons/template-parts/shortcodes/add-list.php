<?php
/* add_ons_php */
if(is_user_logged_in()) : ?>
    <a href="<?php echo townhub_addons_add_listing_url();?>" class="add-list color-bg "><?php _e( 'Add Listing <span><i class="fal fa-layer-plus"></i></span>', 'townhub-add-ons' );?></a>
<?php else : 
	$logBtnAttrs = townhub_addons_get_login_button_attrs( 'addlist', 'current' );
	?>
    <a href="<?php echo esc_url( $logBtnAttrs['url'] );?>" class="add-list color-bg <?php echo esc_attr( $logBtnAttrs['class'] );?>" data-message="<?php esc_attr_e( 'You must be logged in to add listings.', 'townhub-add-ons' ); ?>"><?php _e( 'Add Listing <span><i class="fal fa-layer-plus"></i></span>', 'townhub-add-ons' );?></a>
<?php 
endif;