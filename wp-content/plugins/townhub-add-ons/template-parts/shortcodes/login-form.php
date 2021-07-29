<?php
/* add_ons_php */

?>
<div class="login-form-container cth-wide-form">
    <?php if ( $attributes['show_title'] ) : ?>
        <h2><?php _e( 'Sign In', 'townhub-add-ons' ); ?></h2>
    <?php endif; ?>

    <!-- Show errors if there are any -->
    <?php if ( !empty($attributes['errors']) && count( $attributes['errors'] ) > 0 ) : ?>
        <?php foreach ( $attributes['errors'] as $error ) : ?>
            <p class="login-error">
                <?php echo $error; ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Show logged out message if user just logged out -->
    <?php if ( $attributes['logged_out'] ) : ?>
        <p class="login-info">
            <?php _e( 'You have signed out. Would you like to sign in again?', 'townhub-add-ons' ); ?>
        </p>
    <?php endif; ?>

    <?php if ( $attributes['registered'] ) : ?>
        <p class="login-info">
            <?php
                printf(
                    __( 'You have successfully registered to <strong>%s</strong>. We have emailed your password to the email address you entered.', 'townhub-add-ons' ),
                    get_bloginfo( 'name' )
                );
            ?>
        </p>
    <?php endif; ?>

    <?php if ( $attributes['lost_password_sent'] ) : ?>
        <p class="login-info">
            <?php _e( 'Check your email for a link to reset your password.', 'townhub-add-ons' ); ?>
        </p>
    <?php endif; ?>
     
    <?php
        wp_login_form(
            array(
                'label_username' => __( 'Username', 'townhub-add-ons' ),
                'label_log_in' => __( 'Sign In', 'townhub-add-ons' ),
                'redirect' => $attributes['redirect'],
            )
        );
    ?>
     
    <p><a class="forgot-password" href="<?php echo wp_lostpassword_url(); ?>">
        <?php _e( 'Forgot your password?', 'townhub-add-ons' ); ?>
    </a> | <a class="forgot-password" href="<?php echo wp_registration_url(); ?>">
        <?php _e( 'Register new account', 'townhub-add-ons' ); ?>
    </a></p>

    <?php 
    $logreg_form_after = townhub_addons_get_option('logreg_form_after');
    if ( $logreg_form_after != '' ): 
        // _e( '<div class="log-separator fl-wrap"><span>or</span></div>', 'townhub-add-ons' );
    ?>
    <div class="dfsoc-log fl-wrap">
        <?php echo do_shortcode( $logreg_form_after ); ?>
    </div>
    <?php 
    endif; ?>
    
</div>
