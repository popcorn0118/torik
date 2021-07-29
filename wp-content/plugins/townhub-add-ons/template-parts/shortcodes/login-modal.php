<?php
/* add_ons_php */

$login_redirect_page = esb_addons_get_wpml_option('login_redirect_page');
if($login_redirect_page != 'cth_current_page' && is_numeric($login_redirect_page) )
    $login_redirect_url = get_permalink( $login_redirect_page );
else 
    $login_redirect_url = townhub_addons_get_current_url();
$reg_redirect_page = esb_addons_get_wpml_option('reg_redirect_page');
if($reg_redirect_page != 'cth_current_page' && is_numeric($reg_redirect_page) )
    $reg_redirect_url = get_permalink( $reg_redirect_page );
else 
    $reg_redirect_url = townhub_addons_get_current_url();


?>
    <!--register form -->
    <div class="main-register-wrap ctb-modal-wrap ctb-modal" id="ctb-logreg-modal">
        <div class="reg-overlay"></div>
        <div class="main-register-holder ctb-modal-holder tabs-act">

            <div class="main-register ctb-modal-inner fl-wrap  modal_main tabs-wrapper">
                <div class="ctb-modal-title"><?php echo townhub_addons_get_option('logreg_form_title'); ?></div>
                <div class="close-reg ctb-modal-close"><i class="fal fa-times"></i></div>
                <div class="prelog-message"></div>
                <?php 
                $logreg_form_before = townhub_addons_get_option('logreg_form_before');
                if ( $logreg_form_before != '' ): ?>
                <div class="soc-log fl-wrap">
                    <?php echo do_shortcode( $logreg_form_before ); ?>
                </div>
                <?php 
                    _e( '<div class="log-separator fl-wrap"><span>or</span></div>', 'townhub-add-ons' );
                endif; ?>
                <ul class="tabs-menu fl-wrap no-list-style">
                    <li class="current"><a href="#tab-login"><?php _e( '<i class="fal fa-sign-in-alt"></i> Login', 'townhub-add-ons' ); ?></a></li>
                    <li><a href="#tab-register"><?php _e( '<i class="fal fa-user-plus"></i> Register', 'townhub-add-ons' ); ?></a></li>
                </ul>
                <!--tabs -->                       
                <div class="tabs-container">
                    <div class="tab">
                        <!--tab -->
                        <div id="tab-login" class="tab-content first-tab">
                            <div class="custom-form">
                                <form method="post" id="townhub-login">
                                    <div class="log-message"></div>
                                    <label for="user_login"><?php _e( 'Username or Email Address <span>*</span>', 'townhub-add-ons' );?></label>
                                    <input id="user_login" name="log" type="text" onClick="this.select()" value="" required>

                                    <label for="user_pass"><?php _e( 'Password <span>*</span>', 'townhub-add-ons' );?></label>
                                    <input id="user_pass" name="pwd" type="password" onClick="this.select()" value="" required>

                                    <?php townhub_addons_display_recaptcha('loginCaptcha'); ?>

                                    <button type="submit" id="log-submit" class="log-submit-btn btn color2-bg"><?php _e( 'Log In', 'townhub-add-ons' );?><i class="fas fa-caret-right"></i></button>

                                    <div class="clearfix"></div>
                                    <div class="filter-tags">
                                        <input name="rememberme" id="rememberme" value="true" type="checkbox">
                                        <label for="rememberme"><?php _e('Remember me','townhub-add-ons');?></label>
                                    </div>
                                    <?php
                                        // this prevent automated script for unwanted spam
                                        if ( function_exists( 'wp_nonce_field' ) ) 
                                            wp_nonce_field( 'townhub-login', '_loginnonce' );
                                    ?>
                                    
                                    <input type="hidden" name="redirection" value="<?php echo esc_url($login_redirect_url); ?>" />
                                </form>
                                <div class="lost_password">
                                    <a class="lost-password" href="<?php echo wp_lostpassword_url( townhub_addons_get_current_url() ); ?>"><?php _e('Lost Your Password?','townhub-add-ons');?></a>
                                </div>

                            </div>
                        </div>
                        <!--tab end -->
                        <!--tab -->
                        <div class="tab">
                            <div id="tab-register" class="tab-content">
                                <div class="custom-form">
                                    <form method="post" class="main-register-form" id="townhub-register">
                                        <div class="reg-message"></div>
        
                                        <?php if(townhub_addons_get_option('register_password') != 'yes'): ?>
                                            <p><?php esc_html_e( 'Account details will be confirmed via email.', 'townhub-add-ons' ); ?></p>
                                        <?php endif; ?>

                                        <?php 
                                        $login_pattern = townhub_addons_get_option('login_pattern');
                                        $login_pat_desc = townhub_addons_get_option('login_pat_desc');
                                        ?>

                                        <label for="reg_username"><?php _e( 'Username <span>*</span>', 'townhub-add-ons' );?></label>
                                        <input id="reg_username" name="username" type="text"  onClick="this.select()" value="" required 
                                            <?php if( !empty($login_pattern) ) echo 'pattern="'.esc_attr( $login_pattern ).'"'; ?>
                                            <?php if( !empty($login_pat_desc) ) echo 'title="'.esc_attr( $login_pat_desc ).'"'; ?>
                                        >
                                        <?php if( !empty($login_pat_desc) ) echo '<span class="input-pattern-desc">'.$login_pat_desc.'</span>'; ?>

                                        <?php if(townhub_addons_get_option('reg_firstname') == 'yes'): ?>
                                            <label for="reg_firstname"><?php _e( 'First Name <span>*</span>', 'townhub-add-ons' );?></label>
                                            <input id="reg_firstname" name="first_name" type="text"  onClick="this.select()" value="" required>
                                        <?php endif; ?>

                                        <?php if(townhub_addons_get_option('reg_lastname') == 'yes'): ?>
                                            <label for="reg_lastname"><?php _e( 'Last Name <span>*</span>', 'townhub-add-ons' );?></label>
                                            <input id="reg_lastname" name="last_name" type="text"  onClick="this.select()" value="" required>
                                        <?php endif; ?>
                                        <?php 
                                        $email_pattern = townhub_addons_get_option('email_pattern');
                                        $email_pat_desc = townhub_addons_get_option('email_pat_desc'); ?>
                                        <label for="reg_email"><?php _e( 'Email Address <span>*</span>', 'townhub-add-ons' );?></label>
                                        <input id="reg_email" name="email" type="email"  onClick="this.select()" value="" required 
                                            <?php if( !empty($email_pattern) ) echo 'pattern="'.esc_attr( $email_pattern ).'"'; ?>
                                            <?php if( !empty($email_pat_desc) ) echo 'title="'.esc_attr( $email_pat_desc ).'"'; ?>
                                        >
                                        <?php if( !empty($email_pat_desc) ) echo '<span class="input-pattern-desc">'.$email_pat_desc.'</span>'; ?>
                                        <?php if(townhub_addons_get_option('register_password') == 'yes'): 
                                            $pwd_pattern = townhub_addons_get_option('pwd_pattern');
                                            $pwd_pat_desc = townhub_addons_get_option('pwd_pat_desc');
                                        ?>
                                        <label for="reg_password"><?php _e( 'Password <span>*</span>', 'townhub-add-ons' );?></label>
                                        <input id="reg_password" name="password" type="password" onClick="this.select()" value="" required
                                            autocomplete="off" 
                                            <?php if( !empty($pwd_pattern) ) echo 'pattern="'.esc_attr( $pwd_pattern ).'"'; ?>
                                            <?php if( !empty($pwd_pat_desc) ) echo 'title="'.esc_attr( $pwd_pat_desc ).'"'; ?>
                                        >
                                        <?php if( !empty($pwd_pat_desc) ) echo '<span class="input-pattern-desc">'.$pwd_pat_desc.'</span>'; ?>
                                        <?php endif; ?>
                                        
                                        <?php if(townhub_addons_get_option('register_role') == 'yes'): ?>
                                            <div class="switchbtn text-center reg-as-lauthor">
                                                <input type="checkbox" id="reg_lauthor" name="reg_lauthor" value="1" class="switchbtn-checkbox">
                                                <label class="switchbtn-label" for="reg_lauthor">
                                                    <?php _e( '<i class="fal fa-user-tie"></i><span>Register as author</span>', 'townhub-add-ons' ); ?>
                                                </label>
                                            </div>
                                        <?php endif; ?>

                                        <div class="terms_wrap">
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
                                        <div class="clearfix"></div>

                                        <?php townhub_addons_display_recaptcha('regCaptcha'); ?>
                                        
                                        <button type="submit" id="reg-submit" class="log-submit-btn btn color2-bg"><?php _e( 'Register', 'townhub-add-ons' );?><i class="fas fa-caret-right"></i></button>

                                        <?php
                                            // this prevent automated script for unwanted spam
                                            if ( function_exists( 'wp_nonce_field' ) ) 
                                                wp_nonce_field( 'townhub-register', '_regnonce' );
                                        ?>

                                        <input type="hidden" name="redirection" value="<?php echo esc_url($reg_redirect_url); ?>" />

                                    </form>
        
                                </div>
                            </div>
                        </div>
                        <!--tab end -->
                    </div>
                    <!--tabs end -->
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
                    <div class="wave-bg">
                        <div class='wave -one'></div>
                        <div class='wave -two'></div>
                    </div>
                </div>
            </div><!-- main-register end -->

        </div>
    </div>
    <!--register form end -->


    <div class="ctb-modal-wrap ctb-modal" id="ctb-resetpsw-modal">
        <div class="ctb-modal-holder">
            <div class="ctb-modal-inner modal_main">
                <div class="ctb-modal-close"><i class="fal fa-times"></i></div>
                <div class="ctb-modal-title"><?php _e( 'Reset <span><strong>Password</strong></span>', 'townhub-add-ons' );?></div>
                <div class="ctb-modal-content">
                    
                    <form class="reset-password-form custom-form" action="#" method="post">
                        
                        <fieldset>
                            <label for="user_reset"><?php _e( 'Username or Email Address <span>*</span>', 'townhub-add-ons' );?></label>
                            <input id="user_reset" name="user_login" type="text"  value="" required>
                        </fieldset>
                        <button type="submit" class="btn color2-bg"><?php _e( 'Get New Password', 'townhub-add-ons' );?><i class="fas fa-caret-right"></i></button>

                        
                    </form>
                </div>
                <!-- end modal-content -->
            </div>
        </div>
    </div>
    <!-- end reset password modal --> 
