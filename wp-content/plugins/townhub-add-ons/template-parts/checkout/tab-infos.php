<?php
/* add_ons_php */
$disabled = '';
if( is_user_logged_in() ) $disabled = ' disabled="disabled"';
?>
<div class="ck-tab-title fl-wrap">
    <h3><?php esc_html_e('Your personal Information', 'townhub-add-ons');?></h3>
</div>
<div class="row">
    <div class="col-sm-6">
        <label class="has-icon"><?php esc_html_e('First Name ', 'townhub-add-ons');?><i class="far fa-user"></i></label>
        <div class="ck-validate-field">

            <input type="text" placeholder="<?php esc_attr_e('Your Name', 'townhub-add-ons');?>" name="first_name" value="<?php echo $user_datas['first_name']; ?>" required="required" <?php echo $disabled; ?>/>
        </div>

    </div>
    <div class="col-sm-6">
        <label class="has-icon"><?php esc_html_e('Last Name ', 'townhub-add-ons');?><i class="far fa-user"></i></label>
        <div class="ck-validate-field">

            <input type="text" placeholder="<?php esc_attr_e('Your Last Name', 'townhub-add-ons');?>" name="last_name"value="<?php echo $user_datas['last_name']; ?>" required="required" <?php echo $disabled; ?>/>
        </div>

    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <label class="has-icon"><?php esc_html_e('Contact Email', 'townhub-add-ons');?><i class="far fa-envelope"></i>  </label>
        <div class="ck-validate-field">

            <input type="text" placeholder="<?php esc_attr_e('support@info.com', 'townhub-add-ons');?>" name="user_email" value="<?php echo $user_datas['email']; ?>" required="required" <?php echo $disabled; ?>/>
        </div>

    </div>
    <div class="col-sm-6">
        <label class="has-icon"><?php esc_html_e('Phone', 'townhub-add-ons');?><i class="far fa-phone"></i>  </label>
        <div class="ck-validate-field">

            <input type="text" placeholder="<?php esc_attr_e('+7(123)987654', 'townhub-add-ons');?>" name="phone" value="<?php echo $user_datas['phone']; ?>" <?php echo $disabled; ?>/>
        </div>

    </div>
</div>

<?php if( is_user_logged_in() ): ?>
    <a href="<?php echo Esb_Class_Dashboard::screen_url('profile'); ?>" class="btn-link go-edit-profile"><?php _e( 'Change profile infos', 'townhub-add-ons' ); ?></a>
<?php else: 
    $logBtnAttrs = townhub_addons_get_login_button_attrs( 'checkout', 'current' );
    ?>
    <div class="log-massage"><?php _e( 'Existing Customer? ', 'townhub-add-ons' ); ?><a href="<?php echo esc_url( $logBtnAttrs['url'] );?>" class="<?php echo esc_attr( $logBtnAttrs['class'] );?>"><?php _e( 'Click here to login', 'townhub-add-ons' ); ?></a></div>
    <?php
    $logreg_form_after = townhub_addons_get_option('logreg_form_after');
    if ( $logreg_form_after != '' ): 
        _e( '<div class="log-separator fl-wrap"><span>or</span></div>', 'townhub-add-ons' );
    ?>
    <div class="soc-log fl-wrap">
        <?php echo do_shortcode( $logreg_form_after ); ?>
    </div>
    <?php 
    endif; ?>
<?php endif; ?>

    
