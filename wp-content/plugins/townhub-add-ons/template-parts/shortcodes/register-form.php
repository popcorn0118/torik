<?php
/* add_ons_php */

?>
<div id="register-form" class="widecolumn cth-wide-form">
    <?php if ( $attributes['show_title'] ) : ?>
        <h3><?php _e( 'Register', 'townhub-add-ons' ); ?></h3>
    <?php endif; ?>

    <?php if ( count( $attributes['errors'] ) > 0 ) : ?>
        <?php foreach ( $attributes['errors'] as $error ) : ?>
            <p>
                <?php echo $error; ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>
 
    <form id="signupform" action="<?php echo wp_registration_url(); ?>" method="post">
        <?php 
        $login_pattern = townhub_addons_get_option('login_pattern');
        $login_pat_desc = townhub_addons_get_option('login_pat_desc');
        ?>
        <p class="form-row">
            <label for="username"><?php _e( 'Username', 'townhub-add-ons' ); ?> <strong>*</strong></label>
            <input type="text" name="username" id="username" required value=""
                
                <?php if( !empty($login_pattern) ) echo 'pattern="'.esc_attr( $login_pattern ).'"'; ?>
                <?php if( !empty($login_pat_desc) ) echo 'title="'.esc_attr( $login_pat_desc ).'"'; ?>
            >
            <?php if( !empty($login_pat_desc) ) echo '<span class="input-pattern-desc">'.$login_pat_desc.'</span>'; ?>
        </p>
        <?php 
        $email_pattern = townhub_addons_get_option('email_pattern');
        $email_pat_desc = townhub_addons_get_option('email_pat_desc'); ?>
        <p class="form-row">
            <label for="email"><?php _e( 'Email Address', 'townhub-add-ons' ); ?> <strong>*</strong></label>
            <input type="email" name="email" id="email" required value=""
                <?php if( !empty($email_pattern) ) echo 'pattern="'.esc_attr( $email_pattern ).'"'; ?>
                <?php if( !empty($email_pat_desc) ) echo 'title="'.esc_attr( $email_pat_desc ).'"'; ?>
            >
            <?php if( !empty($email_pat_desc) ) echo '<span class="input-pattern-desc">'.$email_pat_desc.'</span>'; ?>
        </p>
        <?php if(townhub_addons_get_option('reg_firstname') == 'yes'): ?>
        <p class="form-row">
            <label for="first_name"><?php _e( 'First name', 'townhub-add-ons' ); ?></label>
            <input type="text" name="first_name" id="first_name" required value="">
        </p>
        <?php endif; ?>
        <?php if(townhub_addons_get_option('reg_lastname') == 'yes'): ?>
        <p class="form-row">
            <label for="last_name"><?php _e( 'Last name', 'townhub-add-ons' ); ?></label>
            <input type="text" name="last_name" id="last_name" required value="">
        </p>
        <?php endif; ?>

        <?php if(townhub_addons_get_option('register_password') == 'yes'): 
            $pwd_pattern = townhub_addons_get_option('pwd_pattern');
            $pwd_pat_desc = townhub_addons_get_option('pwd_pat_desc');
        ?>
        <p class="form-row">
            <label for="reg-password"><?php _e( 'Password', 'townhub-add-ons' ); ?> <strong>*</strong></label>
            <input type="password" name="password" id="reg-password" onClick="this.select()" value="" required
                autocomplete="off" 
                <?php if( !empty($pwd_pattern) ) echo 'pattern="'.esc_attr( $pwd_pattern ).'"'; ?>
                <?php if( !empty($pwd_pat_desc) ) echo 'title="'.esc_attr( $pwd_pat_desc ).'"'; ?>
            >
            <?php if( !empty($pwd_pat_desc) ) echo '<span class="input-pattern-desc">'.$pwd_pat_desc.'</span>'; ?>
        </p>
        <?php else: ?>
        <p class="form-row">
            <?php _e( 'Note: Your password will be generated automatically and sent to your email address.', 'townhub-add-ons' ); ?>
        </p>
        <?php endif; ?>

        <?php if(townhub_addons_get_option('register_role') == 'yes'): ?>
            <div class="switchbtn text-center reg-as-lauthor">
                <input type="checkbox" id="reg_lauthor" name="reg_lauthor" value="1" class="switchbtn-checkbox">
                <label class="switchbtn-label" for="reg_lauthor">
                    <?php _e( '<i class="fal fa-user-tie"></i><span>Register as author</span>', 'townhub-add-ons' ); ?>
                </label>
            </div>
        <?php endif; ?>
                                        
        <div class="form-row terms_wrap">
            <?php if(townhub_addons_get_option('register_term_text') != ''): ?>
            <div class="filter-tags">
                <input id="accept_term" name="accept_term" value="1" type="checkbox" required="required">
                <label for="accept_term"><?php echo townhub_addons_get_option('register_term_text');?></label>
            </div>
            <?php endif; ?>
            <?php if(townhub_addons_get_option('register_consent_data_text') != ''): ?>
            <div class="filter-tags">
                <input id="consent_data" name="consent_data" value="1" type="checkbox" required="required">
                <label for="consent_data"><?php echo townhub_addons_get_option('register_consent_data_text');?></label>
            </div>
            <?php endif; ?>
        </div>

        <?php 
        if( townhub_addons_get_option('enable_g_recaptcah') == 'yes' && townhub_addons_get_option('g_recaptcha_site_key') != '' ){
            echo '<div class="cth-recaptcha"><div class="g-recaptcha" data-sitekey="'.esc_attr( townhub_addons_get_option('g_recaptcha_site_key') ).'"></div></div>';
        }
        ?>
 
        <p class="signup-submit">
            <input type="submit" name="submit" class="register-button button button-primary"
                   value="<?php _e( 'Register', 'townhub-add-ons' ); ?>"/>
        </p>

        <p><a class="have-an-account" href="<?php echo wp_login_url(); ?>">
            <?php _e( 'Have an account?', 'townhub-add-ons' ); ?>
        </a></p>
    </form>
</div>