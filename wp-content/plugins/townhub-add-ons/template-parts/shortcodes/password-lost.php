<?php
/* add_ons_php */

?>
<div id="password-lost-form" class="widecolumn cth-wide-form">
    <?php if ( $attributes['show_title'] ) : ?>
        <h3><?php _e( 'Forgot Your Password?', 'townhub-add-ons' ); ?></h3>
    <?php endif; ?>

    <?php if ( count( $attributes['errors'] ) > 0 ) : ?>
        <?php foreach ( $attributes['errors'] as $error ) : ?>
            <p>
                <?php echo $error; ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>
 
    <p>
        <?php
            _e(
                "Enter your email address and we'll send you a link you can use to pick a new password.",
                'townhub-add-ons'
            );
        ?>
    </p>
 
    <form id="lostpasswordform" action="<?php echo network_site_url( 'wp-login.php?action=lostpassword', 'login' ); // wp_lostpassword_url(); ?>" method="post">
        <p class="form-row">
            <label for="user_login"><?php _e( 'Username or Email Address', 'townhub-add-ons' ); ?></label>
            <input type="text" name="user_login" id="user_login">
        </p>

        <?php echo $attributes['recaptcha']; ?>
 
        <p class="lostpassword-submit">
            <input type="submit" name="submit" class="lostpassword-button button button-primary"
                   value="<?php _ex( 'Reset Password', 'Forget password', 'townhub-add-ons' ); ?>"/>
        </p>

        <p><a class="have-an-account" href="<?php echo wp_login_url(); ?>">
            <?php _e( 'Have an account?', 'townhub-add-ons' ); ?>
        </a></p>
    </form>
</div>

