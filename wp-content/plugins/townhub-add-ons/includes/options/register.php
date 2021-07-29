<?php 
/* add_ons_php */

function townhub_addons_options_get_register(){
    return array(
            array(
                "type" => "section",
                'id' => 'register_general_sec',
                "title" => __( 'User Registration', 'townhub-add-ons' ),
                
            ),

            // array(
            //     "type" => "info",
            //     'id' => 'register_note_info',
            //     "title" => __( 'Info note', 'townhub-add-ons' ),
            //     'desc'  => 'Please make sure that user registration is enabled: https://prnt.sc/s7vo4j'
            // ),

            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'new_user_email',
                "title" => __('Send new user registration email to', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> 'both',
                    'options'=> array(
                        'user' => __( 'User only', 'townhub-add-ons' ),
                        'admin' => __( 'Admin only', 'townhub-add-ons' ),
                        'both' => __( 'Admin and user', 'townhub-add-ons' ),
                        'none' => __( 'None', 'townhub-add-ons' ),
                        
                    ),
                ),
                'desc'  => 'Please make sure that user registration is enabled on Settings -> General screen: <a href="https://prnt.sc/s7vo4j" target="_blank">https://prnt.sc/s7vo4j</a>'
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'reg_firstname',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => _x('Show First Name field', 'TownHub Add-Ons', 'townhub-add-ons'),
                'desc'  => '',
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'reg_lastname',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => _x('Show Last Name field', 'TownHub Add-Ons', 'townhub-add-ons'),
                'desc'  => '',
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'register_password',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Show Password field', 'townhub-add-ons'),
                'desc'  => '',
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'register_password',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Show Password field', 'townhub-add-ons'),
                'desc'  => '',
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'register_auto_login',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Login user after registered?', 'townhub-add-ons'),
                'desc'  => '',
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'register_no_redirect',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => __('Disable redirect after registered?', 'townhub-add-ons'),
                'desc'  => '',
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'register_role',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Allow register as author?', 'townhub-add-ons'),
                'desc'  => '',
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'register_as_author',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Register as author (NEW)', 'townhub-add-ons'),
                'desc'  => __('Check this option if you want registered users is author by default', 'townhub-add-ons'),
            ),
            
            array(
                "type" => "field",
                "field_type" => "textarea",
                'id' => 'logreg_form_title',
                "title" => __('Log/Reg modal title', 'townhub-add-ons'),
                'desc'  => '',
                'args' => array(
                    'default' => 'Welcome to <span><strong>Town</strong>Hub<strong>.</strong></span>',
                )
            ),
            array(
                "type" => "field",
                "field_type" => "textarea",
                'id' => 'logreg_form_before',
                "title" => __('Log/Reg Top Content', 'townhub-add-ons'),
                'desc'  => __( 'Content showing up above user login - register form. You can add shortcode for social login.', 'townhub-add-ons' ),
                'args' => array(
                    'default' => '',
                )
            ),

            array(
                "type" => "field",
                "field_type" => "textarea",
                'id' => 'logreg_form_after',
                "title" => __('Log/Reg Bottom Content', 'townhub-add-ons'),
                'desc'  => __( 'Content showing up above user login - register form. You can add shortcode for social login.', 'townhub-add-ons' ),
                'args' => array(
                    'default' => '<p>For faster login or register use your social account.</p>
[fbl_login_button redirect="" hide_if_logged="" size="large" type="continue_with" show_face="true"]',
                )
            ),

            

            array(
                "type" => "field",
                "field_type" => "textarea",
                'id' => 'register_term_text',
                "title" => __('Terms Text', 'townhub-add-ons'),
                'desc'  => __( 'Accept terms text on user register form.', 'townhub-add-ons' ),
                'args' => array(
                    'default' => 'By using the website, you accept the terms and conditions',
                )
            ),

            array(
                "type" => "field",
                "field_type" => "textarea",
                'id' => 'register_consent_data_text',
                "title" => __('Consent Personal Data Text', 'townhub-add-ons'),
                'desc'  => '',
                'args' => array(
                    'default' => 'Consent to processing of personal data',
                )
            ),

            // array(
            //     "type" => "field",
            //     "field_type" => "checkbox",
            //     'id' => 'admin_bar_front',
            //     'args'=> array(
            //         'default' => 'no',
            //         'value' => 'yes',
            //     ),
            //     "title" => __('Show Front-end Admin Bar', 'townhub-add-ons'),
            //     'desc'  => '',
            // ),

            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'admin_bar_hide_roles',
                "title" => __('Hide Admin Bar for', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> array('l_customer','listing_author','subscriber','contributor','author'),
                    'options'=> townhub_addons_get_author_roles(),
                    'multiple' => true,
                    'use-select2' => true
                ),
                // 'desc' => esc_html__("The page redirect to after submit/edit listing", 'townhub-add-ons'), 
            ),


            array(
                "type" => "section",
                'id' => 'register_login_sec',
                "title" => __( 'User Login', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "page_select",
                'id' => 'login_redirect_page',
                "title" => __('After Login Redirect', 'townhub-add-ons'),
                'desc'  => __('The page user redirect to after login.', 'townhub-add-ons') . 'DO NOT select Login Page here',
                'args' => array(
                    'default'   => 'cth_current_page',
                    // 'default_title' => "Pricing Tables",
                    'options' => array(
                        array(
                            'cth_current_page',
                            __( 'Current Page', 'townhub-add-ons' ),
                        ),
                    )
                )
            ),

            array(
                "type" => "field",
                "field_type" => "page_select",
                'id' => 'reg_redirect_page',
                "title" => _x('After Register Redirect', 'TownHub Add-Ons', 'townhub-add-ons'),
                'desc'  => _x('The page user will redirect to after register.', 'TownHub Add-Ons', 'townhub-add-ons') . 'DO NOT select Register Page here',
                'args' => array(
                    'default'   => 'cth_current_page',
                    // 'default_title' => "Pricing Tables",
                    'options' => array(
                        array(
                            'cth_current_page',
                            _x( 'Current Page', 'TownHub Add-Ons', 'townhub-add-ons' ),
                        ),
                    )
                )
            ),

            array(
                "type" => "field",
                "field_type" => "number",
                'id' => 'login_delay',
                "title" => __('Login Redirect Timeout', 'townhub-add-ons'),
                'args' => array(
                    'default'  => '5000',
                    'min'  => '0',
                    'max'  => '500000',
                    'step'  => '1000',
                ),
                'desc'  => __('The number of milliseconds to wait before logged in redirect', 'townhub-add-ons') . __( '<br>And larger than <strong>300000</strong> for disabled.', 'townhub-add-ons' ),
            ),


            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'log_reg_dis_nonce',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Disable verify nonce?', 'townhub-add-ons'),
                'desc'  => __( 'Use this option if you receive "Security checked!, Cheatn huh?" error when using cache plugin.', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'off_avatar',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => __('Disable Gravatar', 'townhub-add-ons'),
                'desc'  => '',
            ),

            // array(
            //     "type" => "field",
            //     "field_type" => "image",
            //     'id' => 'df_avatar',
            //     "title" => __('Default Avatar', 'townhub-add-ons'),
            //     'desc' => '',
            // ),
            

            array(
                "type" => "field",
                "field_type" => "checkbox", 
                'id' => 'delete_user',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Allow delete user?', 'townhub-add-ons'),  
                'desc'  => __( 'Allow user delete account. All realated data will be deleted too.', 'townhub-add-ons' ),
            ),

            array(
                "type" => "section",
                'id' => 'custom_logreg_sec',
                "title" => __( 'Custom Login/Register pages', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox", 
                'id' => 'disable_custom_logreg',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Disable custom login/register pages?', 'townhub-add-ons'),  
                'desc'  => '',
            ),

            array(
                "type" => "field",
                "field_type" => "page_select",
                'id' => 'login_page',
                "title" => __('Login Page', 'townhub-add-ons'),
                'desc'  => 'The page will be used for user login page. The page content should contain <b>[cthlogin_page]</b> shortcode',
                'args' => array(
                    'default_title' => "Login Page",
                )
            ),

            array(
                "type" => "field",
                "field_type" => "page_select",
                'id' => 'register_page',
                "title" => __('Register Page', 'townhub-add-ons'),
                'desc'  => 'The page will be used for user registration page. The page content should contain <b>[cthregister_page]</b> shortcode',
                'args' => array(
                    'default_title' => "Register Page",
                )
            ),

            array(
                "type" => "field",
                "field_type" => "page_select",
                'id' => 'forget_pwd_page',
                "title" => __('Forget Password Page', 'townhub-add-ons'),
                'desc'  => 'The page content should contain <b>[cthforget_pwd_page]</b> shortcode', 
                'args' => array(
                    'default_title' => "Forget Password Page",
                )
            ),

            array(
                "type" => "field",
                "field_type" => "page_select",
                'id' => 'reset_pwd_page',
                "title" => __('Reset Password Page', 'townhub-add-ons'),
                'desc'  => 'The page content should contain <b>[cthreset_pwd_page]</b> shortcode',
                'args' => array(
                    'default_title' => "Reset Password Page",
                )
            ),
            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'login_pattern',
                'args' => array(
                    'default'  => '^[A-Za-z\d\.]{6,}$',
                ),
                "title" => __('Username field pattern', 'townhub-add-ons'),
                'desc'  => '<a href="https://www.w3schools.com/tags/att_input_pattern.asp" target="_blank">HTML &lt;input&gt; pattern Attribute</a>',
            ),
            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'login_pat_desc',
                'args' => array(
                    'class' => 'large-text',
                    'default'  => 'You can use letters, numbers and periods and at least 6 characters or more',
                ),
                "title" => __('Username field pattern description', 'townhub-add-ons'),
                'desc'  => '',
            ),
            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'email_pattern',
                'args' => array(
                    'default'  => '^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$',
                ),
                "title" => _x('Email field pattern','TownHub Add-Ons', 'townhub-add-ons'),
                'desc'  => '<a href="https://www.w3schools.com/tags/att_input_pattern.asp" target="_blank">HTML &lt;input&gt; pattern Attribute</a>',
            ),
            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'email_pat_desc',
                'args' => array(
                    'class' => 'large-text',
                    'default'  => 'Make sure to enter all lowercase letters for your email address',
                ),
                "title" => _x('Email field pattern description','TownHub Add-Ons', 'townhub-add-ons'),
                'desc'  => '',
            ),
            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'pwd_pattern',
                'args' => array(
                    'default'  => '^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d$@$!%*?&]{8,}$',
                ),
                "title" => __('Password field pattern', 'townhub-add-ons'),
                'desc'  => '<a href="https://www.w3schools.com/tags/att_input_pattern.asp" target="_blank">HTML &lt;input&gt; pattern Attribute</a>',
            ),

            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'pwd_pat_desc',
                'args' => array(
                    'class' => 'large-text',
                    'default'  => 'Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters',
                ),
                "title" => __('Password field pattern description', 'townhub-add-ons'),
                'desc'  => '',
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'dis_log_reg_modal',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Disable Login/Register Popup?', 'townhub-add-ons'),
                'desc'  => '',
            ),


            array(
                "type" => "field",
                "field_type" => "editor",
                'id' => 'user_welcome_email',
                "title" => __('User Registration Email', 'townhub-add-ons'),
                'desc'  => '',
                'args' => array(
                    'default' => '<p style="text-align: left;">You have registered new account on our website with details bellow:</p>
<p style="text-align: left;">Site Name: <strong>{site_name}</strong></p>
<p style="text-align: left;">Site Username: <strong>{username}</strong></p>
<p style="text-align: left;">To set your password, visit the following address: <a href="{set_pwd_url}" target="_blank" rel="noopener">Set password</a></p>
<p style="text-align: left;">Or <a href="{login_url}" target="_blank" rel="noopener">login with your account</a></p>
<p style="text-align: left;">Thank you</p>',
                )
            ),

            array(
                "type" => "field",
                "field_type" => "editor",
                'id' => 'forget_pwd_email',
                "title" => __('Forget Password Email', 'townhub-add-ons'),
                'desc'  => '',
                'args' => array(
                    'default' => '<p style="text-align: left;">Someone has requested a password reset for the following account:</p>
<p style="text-align: left;">Site Name: <strong>{site_name}</strong></p>
<p style="text-align: left;">Site Username: <strong>{username}</strong></p>
<p style="text-align: left;">If this was a mistake, just ignore this email and nothing will happen.</p>
<p style="text-align: left;">To reset your password, visit the following address: <a href="{reset_url}" target="_blank" rel="noopener">Reset password</a></p>',
                )
            ),

    );
}
