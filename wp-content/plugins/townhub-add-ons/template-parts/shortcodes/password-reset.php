<?php
/* add_ons_php */

?>
<div id="password-reset-form" class="widecolumn cth-wide-form">
    <?php if ( $attributes['show_title'] ) : ?>
        <h3><?php _e( 'Pick a New Password', 'townhub-add-ons' ); ?></h3>
    <?php endif; ?>
 
    <form name="resetpassform" id="resetpassform" action="<?php echo site_url( 'wp-login.php?action=resetpass' ); ?>" method="post" autocomplete="off">
        <input type="hidden" id="user_login" name="rp_login" value="<?php echo esc_attr( $attributes['login'] ); ?>" autocomplete="off" />
        <input type="hidden" name="rp_key" value="<?php echo esc_attr( $attributes['key'] ); ?>" />
         
        <?php if ( count( $attributes['errors'] ) > 0 ) : ?>
            <?php foreach ( $attributes['errors'] as $error ) : ?>
                <p>
                    <?php echo $error; ?>
                </p>
            <?php endforeach; ?>
        <?php endif; ?>
 
        <p class="form-row">
            <label for="pass1"><?php _e( 'New password', 'townhub-add-ons' ) ?></label>
            <input type="password" name="pass1" id="pass1" class="input" size="20" value="" autocomplete="off" />
        </p>
        <p class="form-row">
            <label for="pass2"><?php _e( 'Repeat new password', 'townhub-add-ons' ) ?></label>
            <input type="password" name="pass2" id="pass2" class="input" size="20" value="" autocomplete="off" />
        </p>
         
        <p class="description"><?php echo wp_get_password_hint(); ?></p>
         
        <p class="resetpass-submit">
            <input type="submit" name="submit" id="resetpass-button"
                   class="button button-primary" value="<?php _ex( 'Reset Password', 'Reset password', 'townhub-add-ons' ); ?>" />
        </p>
    </form>
</div>

