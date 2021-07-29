<?php
/* add_ons_php */
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
<script type="text/template" id="tmpl-room-inquiry-fields">
    <div class="fl-wrap">
        
        <label class="lbl-hasIcon"><i class="fal fa-user"></i></label>
        <input name="lb_name" class="has-icon" type="text" placeholder="<?php echo esc_attr_x( 'Your Name*','Rooms Inquiry', 'townhub-add-ons' ); ?>" value="<?php echo esc_attr( $loggedName ); ?>" required="required">
    </div>
    <div class="fl-wrap">
        <label class="lbl-hasIcon"><i class="fal fa-envelope"></i></label>
        <input name="lb_email" class="has-icon" type="email" placeholder="<?php  echo esc_attr_x( 'Email Address*','Rooms Inquiry', 'townhub-add-ons' ); ?>" value="<?php echo esc_attr( $loggedEmail ); ?>" required="required">
    </div>
    <div class="fl-wrap">
        <label class="lbl-hasIcon"><i class="fal fa-phone"></i></label>
        <input name="lb_phone" class="has-icon" type="text" placeholder="<?php  echo esc_attr_x( 'Phone','Rooms Inquiry', 'townhub-add-ons' ); ?>" value="<?php echo esc_attr( $loggedPhone ); ?>" required="required">
    </div>
</script>