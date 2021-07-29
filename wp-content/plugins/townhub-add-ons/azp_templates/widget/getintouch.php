<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $hide_widget_on = $hide_email = $hide_map = $title = '';

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'getintouch',
    'azp-element-' . $azp_mID,  
    $el_class,
);
// $animation_data = self::buildAnimation($azp_attrs);
// $classes[] = $animation_data['trigger'];
// $classes[] = self::buildTypography($azp_attrs);//will return custom class for the element without dot
// $azplgallerystyle = self::buildStyle($azp_attrs);

$classes = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $classes ) ) ); 

if($el_id!=''){
    $el_id = 'id="'.$el_id.'"';
}
// $listing_author_id = get_the_author_meta('ID');
if(( $hide_widget_on_check = townhub_addons_is_hide_on_plans($hide_widget_on) ) !== 'true') :

    $lauthor_id = get_post_field( 'post_author', get_the_ID() );

    $current_user = wp_get_current_user();
    
    $loggedName = '';
    $loggedEmail = '';
    $loggedPhone = '';
    if( is_user_logged_in() ){
        $loggedName = $current_user->display_name;
        $loggedEmail = get_user_meta($current_user->ID,  ESB_META_PREFIX.'email', true );
        if( empty($loggedEmail) ) $loggedEmail = $current_user->user_email;
        $loggedPhone = get_user_meta($current_user->ID,  ESB_META_PREFIX.'phone', true );
    }
?>
<div class="<?php echo $classes; ?> authplan-hide-<?php echo $hide_widget_on_check;?>" <?php echo $el_id;?>>
    <div class="for-hide-on-author"></div>
    <!--box-widget-item -->
    <div class="box-widget-item fl-wrap block_box">
        <?php if($title != ''): ?>
        <div class="box-widget-item-header">
            <h3><?php echo $title; ?></h3>
        </div>
        <?php endif; ?>
        <div class="box-widget">
            
            <div class="box-widget-content">
               
                <form class="author-message-form custom-form" action="#" method="post">
                    <?php do_action( 'townhub_author_contact_form_before', $lauthor_id ); ?>
                    <fieldset>
                    
                        <label><i class="fal fa-user"></i></label>
                        <input name="lmsg_name" class="has-icon" type="text" placeholder="<?php esc_attr_e( 'Your Name*', 'townhub-add-ons' ); ?>" value="<?php echo esc_attr( $loggedName ); ?>" required="required">
                        <div class="clearfix"></div>
                        <label><i class="fal fa-envelope"></i></label>
                        <input name="lmsg_email" class="has-icon" type="text" placeholder="<?php esc_attr_e( 'Email Address*', 'townhub-add-ons' ); ?>" value="<?php echo esc_attr( $loggedEmail ); ?>" required="required">
                        <label><i class="fal fa-phone"></i></label>
                        <input name="lmsg_phone" class="has-icon" type="text" placeholder="<?php esc_attr_e( 'Phone', 'townhub-add-ons' ); ?>" value="<?php echo esc_attr( $loggedPhone ); ?>">

                        <textarea name="lmsg_message" cols="40" rows="3" placeholder="<?php esc_attr_e( 'Additional Information:', 'townhub-add-ons' ); ?>"></textarea>
                    </fieldset>
                    
                    <?php do_action( 'townhub_author_contact_form_after', $lauthor_id ); ?>
                    <div class="author-message-error"></div>
                    <button class="btn color2-bg author-msg-submit" type="submit"><?php _e( 'Send Message <i class="fal fa-paper-plane"></i>', 'townhub-add-ons' ); ?></button>
                    <input type="hidden" name="authid" value="<?php echo $lauthor_id; ?>">
                    <input type="hidden" name="listing_id" value="<?php the_ID(); ?>">
                </form>

            </div>
        </div>
    </div>
</div>

<?php endif; 

