<?php
/* add_ons_php */
class Esb_Class_User
{
	private static $_instance;

	private function __construct() {

		add_filter( 'body_class', array( $this, 'body_class' ) );

		if( townhub_addons_get_option('disable_custom_logreg') != 'yes' ){
			// login page
			add_action( 'login_form_login', array( $this, 'redirect_to_custom_login' ) );
			add_shortcode( 'cthlogin_page', array( $this, 'render_login_form' ) );
			add_filter( 'authenticate', array( $this, 'maybe_redirect_at_authenticate' ), 101, 3 );
			add_filter( 'wp_new_user_notification_email', array( $this, 'modify_wp_new_user_notification_email' ), 10, 3 );

			// login captcha
			add_filter( 'login_form_middle', array( $this, 'login_recaptcha' ), 100 );
			add_action( 'wp_print_footer_scripts', array( $this, 'add_captcha_js_to_footer' ) );
			// Show Correct Message at Logout
			add_action( 'wp_logout', array( $this, 'redirect_after_logout' ) );
			add_filter( 'login_redirect', array( $this, 'redirect_after_login' ), 10, 3 );

			// register page
			add_action( 'login_form_register', array( $this, 'redirect_to_custom_register' ) );
			add_shortcode( 'cthregister_page', array( $this, 'render_register_form' ) );
			add_action( 'login_form_register', array( $this, 'do_register_user' ) );

			// lost password
			add_action( 'login_form_lostpassword', array( $this, 'redirect_to_custom_lostpassword' ) );
			add_shortcode( 'cthforget_pwd_page', array( $this, 'render_password_lost_form' ) );
			add_action( 'login_form_lostpassword', array( $this, 'do_password_lost' ) );
			add_filter( 'lostpassword_url', array( $this, 'modify_lostpassword_url' ), 999 );
			add_filter( 'retrieve_password_message', array( $this, 'replace_retrieve_password_message' ), 10, 4 );
			// reset password
			add_action( 'login_form_rp', array( $this, 'redirect_to_custom_password_reset' ) );
			add_action( 'login_form_resetpass', array( $this, 'redirect_to_custom_password_reset' ) );
			add_shortcode( 'cthreset_pwd_page', array( $this, 'render_password_reset_form' ) );

			add_action( 'login_form_rp', array( $this, 'do_password_reset' ) );
			add_action( 'login_form_resetpass', array( $this, 'do_password_reset' ) );

		}
		add_action( 'wp_login', array( $this, 'after_login' ), 10, 2 );
		// social register
		add_action( 'mo_user_register', array( $this, 'miniOrange_and_fbl_social_register' ) );
		add_action( 'fbl/notify_new_registration', array( $this, 'miniOrange_and_fbl_social_register' ) );


    }

    public function after_login($login, $user){
    	Esb_Class_Dashboard::add_notification($user->ID, array('type'=>'logged_in') );
    }

    public static function getInstance() {
        if ( ! ( self::$_instance instanceof self ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    private function __clone() {}

    private function __wakeup() {}

    public function body_class($classes){
    	if( is_page(esb_addons_get_wpml_option('login_page')) ){
    		$classes[] = 'cth-login-page';
    	}
    	if( is_page(esb_addons_get_wpml_option('register_page')) ){
    		$classes[] = 'cth-register-page';
    	}
    	if( is_page(esb_addons_get_wpml_option('forget_pwd_page')) ){
    		$classes[] = 'cth-forget-pwd-page';
    	}
    	if( is_page(esb_addons_get_wpml_option('reset_pwd_page')) ){
    		$classes[] = 'cth-reset-pwd-page';
    	}
    	return $classes;
    }

    public static function custom_log_reg(){
    	return ( is_page(esb_addons_get_wpml_option('login_page')) || is_page(esb_addons_get_wpml_option('register_page')) || is_page(esb_addons_get_wpml_option('forget_pwd_page')) || is_page(esb_addons_get_wpml_option('reset_pwd_page')) );
    }
    

    // https://code.tutsplus.com/tutorials/build-a-custom-wordpress-user-flow-part-1-replace-the-login-page--cms-23627

    /**
	 * Finds and returns a matching error message for the given error code.
	 *
	 * @param string $error_code    The error code to look up.
	 *
	 * @return string               An error message.
	 */
	private function get_error_message( $error_code ) {
	    switch ( $error_code ) {
	        case 'empty_username':
	            return __( 'You do have an email address, right?', 'townhub-add-ons' );
	 
	        case 'empty_password':
	            return __( 'You need to enter a password to login.', 'townhub-add-ons' );
	 
	        case 'invalid_username':
	            return __(
	                "We don't have any users with that email address. Maybe you used a different one when signing up?",
	                'townhub-add-ons'
	            );
	 
	        case 'incorrect_password':
	            $err = __(
	                "The password you entered wasn't quite right. <a href='%s'>Did you forget your password</a>?",
	                'townhub-add-ons'
	            );
	            return sprintf( $err, wp_lostpassword_url() );
	        // Registration errors
	        case 'captcha':
    			return __( 'The Google reCAPTCHA check failed. Are you a robot?', 'townhub-add-ons' );
			case 'email':
			    return __( 'The email address you entered is not valid.', 'townhub-add-ons' );
			 
			case 'email_exists':
			    return __( 'An account exists with this email address.', 'townhub-add-ons' );

			case 'username_exists':
				return __( 'This username is already registered. Please choose another one.', 'townhub-add-ons' );
			 
			case 'closed':
			    return __( 'Registering new users is currently not allowed.', 'townhub-add-ons' );

			// Lost password
 
			case 'empty_username':
			    return __( 'You need to enter your email address to continue.', 'townhub-add-ons' );
			 
			case 'invalid_email':
			case 'invalidcombo':
			    return __( 'There are no users registered with this email address.', 'townhub-add-ons' );
			case 'retrieve_password_email_failure':
				return __( 'The email could not be sent. Your site may not be correctly configured to send emails.', 'townhub-add-ons' );

	        // Reset password
			case 'expiredkey':
			case 'invalidkey':
			    return __( 'The password reset link you used is not valid anymore.', 'townhub-add-ons' );
			 
			case 'password_reset_mismatch':
			    return __( "The two passwords you entered don't match.", 'townhub-add-ons' );
			     
			case 'password_reset_empty':
			    return __( "Sorry, we don't accept empty passwords.", 'townhub-add-ons' );
	 		// custom login message
			case 'follow':
				return __( "Logging in first to follow author.", 'townhub-add-ons' );
			case 'addlist':
				return __( "You must be logged in to add listings.", 'townhub-add-ons' );
			
			case 'savelist':
				return __( "Logging in first to save this listing.", 'townhub-add-ons' );
				
			case 'checkout':
				return __( "Logging in first to continue checkout.", 'townhub-add-ons' );
				
			case 'orderplan':
				return __( "You must be logged in to order a membership plan.", 'townhub-add-ons' );
				
			case 'claim':
				return __( "You must be logged in to claim listing.", 'townhub-add-ons' );
				
			case 'report':
				return __( "Logging in first to report this listing.", 'townhub-add-ons' );
				



				
	        default:
	            break;
	    }
	     
	    return __( 'An unknown error occurred. Please try again later.', 'townhub-add-ons' );
	}

	/**
	 * Renders the contents of the given template to a string and returns it.
	 *
	 * @param string $template_name The name of the template to render (without .php)
	 * @param array  $attributes    The PHP variables for the template
	 *
	 * @return string               The contents of the template.
	 */
	private function get_template_html( $template_name, $attributes = null ) {
	    if ( ! $attributes ) {
	        $attributes = array();
	    }
	 
	    ob_start();
	 
	    do_action( 'cth_login_before_' . $template_name );

	    townhub_addons_get_template_part( 'template-parts/shortcodes/'.$template_name, '', array( 'attributes' => $attributes ) );
	 
	    do_action( 'cth_login_after_' . $template_name );
	 
	    $html = ob_get_contents();
	    ob_end_clean();
	 
	    return $html;
	}

	/**
	 * Redirects the user to the correct page depending on whether he / she
	 * is an admin or not.
	 *
	 * @param string $redirect_to   An optional redirect_to URL for admin users
	 */
	private function redirect_logged_in_user( $redirect_to = null ) {

		$login_redirect_page = esb_addons_get_wpml_option('login_redirect_page');
		if($login_redirect_page != 'cth_current_page' && is_numeric($login_redirect_page) )
		    $login_redirect_url = get_permalink( $login_redirect_page );
		else 
		    $login_redirect_url = home_url(); // townhub_addons_get_current_url();

		wp_redirect( $login_redirect_url );

	    // $user = wp_get_current_user();
	    // if ( user_can( $user, 'manage_options' ) ) {
	    //     if ( $redirect_to ) {
	    //         wp_safe_redirect( $redirect_to );
	    //     } else {
	    //         wp_redirect( admin_url() );
	    //     }
	    // } else {
	    //     wp_redirect( home_url( 'member-account' ) );
	    // }


	}

	private function get_redirect_page($page_option = 'login_page'){
		$redirect_url = home_url();
        $redirect_page_id = esb_addons_get_wpml_option( $page_option, 'page', false );
    	if( !empty($redirect_page_id) ){
    		$redirect_url = get_permalink( $redirect_page_id );
    	}
    	return $redirect_url;
	}

	private function check_if_disabled(){
		$mode = townhub_addons_get_option('maintenance_mode');
	    return ($mode == 'maintenance' || $mode == 'coming_soon');
	}

    /**
	 * Redirect the user to the custom login page instead of wp-login.php.
	 */
	function redirect_to_custom_login() {
	    if ( $_SERVER['REQUEST_METHOD'] == 'GET' && false === $this->check_if_disabled() ) {
	        $redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : null;
	     
	        if ( is_user_logged_in() ) {
	        	// Esb_Class_Dashboard::add_notification(get_current_user_id(), array('type'=>'logged_in') );
	            $this->redirect_logged_in_user( $redirect_to );
	            exit;
	        }

        	$redirect_page_id = esb_addons_get_wpml_option( 'login_page', 'page', false );
	    	if( !empty($redirect_page_id) ){
	    		$redirect_url = get_permalink( $redirect_page_id );

	    		// The rest are redirected to the login page
		        // $redirect_url = $this->get_redirect_page( );
		        if ( ! empty( $redirect_to ) ) {
		            $redirect_url = add_query_arg( 'redirect_to', $redirect_to, $redirect_url );
		        }
		 
		        wp_redirect( $redirect_url );
		        exit;
	        
	    	}
		    	


		        
	 
	        // // The rest are redirected to the login page
	        // $redirect_url = $this->get_redirect_page( );
	        // if ( ! empty( $redirect_to ) ) {
	        //     $redirect_url = add_query_arg( 'redirect_to', $redirect_to, $redirect_url );
	        // }
	 
	        // wp_redirect( $redirect_url );
	        // exit;
	    }
	}
    /**
	 * A shortcode for rendering the login form.
	 *
	 * @param  array   $attributes  Shortcode attributes.
	 * @param  string  $content     The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function render_login_form( $attributes, $content = null ) {
	    // Parse shortcode attributes
	    $default_attributes = array( 'show_title' => false );
	    $attributes = shortcode_atts( $default_attributes, $attributes );
	    $show_title = $attributes['show_title'];
	 
	    if ( is_user_logged_in() ) {
	        return __( 'You are already signed in.', 'townhub-add-ons' );
	    }
	     
	    // Pass the redirect parameter to the WordPress login functionality: by default,
	    // don't specify a redirect, but if a valid redirect URL has been passed as
	    // request parameter, use it.
	    $attributes['redirect'] = '';
	    if ( isset( $_REQUEST['redirect_to'] ) ) {
	        $attributes['redirect'] = wp_validate_redirect( $_REQUEST['redirect_to'], $attributes['redirect'] );
	    }

	    // Error messages
		$errors = array();
		if ( isset( $_REQUEST['login'] ) ) {
		    $error_codes = explode( ',', $_REQUEST['login'] );
		 
		    foreach ( $error_codes as $code ) {
		        $errors []= $this->get_error_message( $code );
		    }
		}
		$attributes['errors'] = $errors;

		// Check if user just logged out
		$attributes['logged_out'] = isset( $_REQUEST['logged_out'] ) && $_REQUEST['logged_out'] == true;

		// Check if the user just registered
		$attributes['registered'] = isset( $_REQUEST['registered'] );

		// Check if the user just requested a new password 
		$attributes['lost_password_sent'] = isset( $_REQUEST['checkemail'] ) && $_REQUEST['checkemail'] == 'confirm';

	     
	    // Render the login form using an external template
	    return $this->get_template_html( 'login-form', $attributes );
	}

	/**
	 * Redirect the user after authentication if there were any errors.
	 *
	 * @param Wp_User|Wp_Error  $user       The signed in user, or the errors that have occurred during login.
	 * @param string            $username   The user name used to log in.
	 * @param string            $password   The password used to log in.
	 *
	 * @return Wp_User|Wp_Error The logged in user, or error information if there were errors.
	 */
	function maybe_redirect_at_authenticate( $user, $username, $password ) {
	    // Check if the earlier authenticate filter (most likely, 
	    // the default WordPress authentication) functions have found errors
	    if ( $_SERVER['REQUEST_METHOD'] === 'POST' && false === $this->check_if_disabled() ) {
	    	// if( !isset($_POST['action']) || $_POST['action'] != 'townhub-login' ){

	    	// }
	    	if( isset($_POST['cth-enable-recaptcha']) && $_POST['cth-enable-recaptcha'] == '1' ){
	    		if ( is_wp_error( $user ) ) {
		            $error_codes = join( ',', $user->get_error_codes() );

		            $redirect_url = $this->get_redirect_page( );
		            $redirect_url = add_query_arg( 'login', $error_codes, $redirect_url );
		 
		            wp_redirect( $redirect_url );
		            exit;
		        }elseif ( townhub_addons_verify_recaptcha() === false ) {
		        	$redirect_url = $this->get_redirect_page( );
				    // verify google reCAPTCHA
				    $redirect_url = add_query_arg( 'login', 'captcha', $redirect_url );
				    wp_redirect( $redirect_url );
		            exit;
		        }
	    	}
	    }
	 
	    return $user;
	}

	/**
	 * Redirect to custom login page after the user has been logged out.
	 */
	public function redirect_after_logout() {
		$redirect_url = $this->get_redirect_page( );
        $redirect_url = add_query_arg( 'logged_out', true, $redirect_url );
	    wp_safe_redirect( $redirect_url );
	    exit;
	}

	/**
	 * Returns the URL to which the user should be redirected after the (successful) login.
	 *
	 * @param string           $redirect_to           The redirect destination URL.
	 * @param string           $requested_redirect_to The requested redirect destination URL passed as a parameter.
	 * @param WP_User|WP_Error $user                  WP_User object if login was successful, WP_Error object otherwise.
	 *
	 * @return string Redirect URL
	 */
	public function redirect_after_login( $redirect_to, $requested_redirect_to, $user ) {
	    $redirect_url = home_url();
	 
	    if ( ! isset( $user->ID ) ) {
	        return $redirect_url;
	    }
	 
	    if ( user_can( $user, 'manage_options' ) ) {
	        // Use the redirect_to parameter if one is set, otherwise redirect to admin dashboard.
	        if ( $requested_redirect_to == '' ) {
	            $redirect_url = admin_url();
	        } else {
	            $redirect_url = $requested_redirect_to;
	        }
	    } else {
	        // Non-admin users always go to their account page after login
	        if( !empty($redirect_to) ){
	        	$redirect_url = $redirect_to;
	        }else{
	        	$login_redirect_page = esb_addons_get_wpml_option('login_redirect_page');
		        if( is_numeric($login_redirect_page) ){
		        	$redirect_url = get_permalink( $login_redirect_page );
		        }
	        }

	    }
	 
	    return wp_validate_redirect( $redirect_url, home_url() );
	}

	public function modify_wp_new_user_notification_email($wp_new_user_notification_email, $user, $blogname){
    	$email_template = townhub_addons_get_option('user_welcome_email');
	    if( !empty($email_template) ){
	        $key = get_password_reset_key( $user );
	        $email_vars = array(
	            'site_name'     => $blogname,
	            'username'      => $user->user_login,
	            'user_email'    => $user->user_email,
	            'set_pwd_url'     => network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user->user_login ), 'login' ),
	            'login_url'     => wp_login_url(),
	        );
	        
	        $message      = Esb_Class_Emails::process_email_template($email_template, $email_vars);

	        $headers = array();
	        if (townhub_addons_get_option('emails_ctype') == 'html'){
	            $headers[] = 'Content-Type: text/html; charset=UTF-8';
	        }

	        $wp_new_user_notification_email['message'] = $message;
	        $wp_new_user_notification_email['headers'] = $headers;

	    }
	    return $wp_new_user_notification_email;
    }

    private function get_recaptcha_html(){
    	if( townhub_addons_get_option('enable_g_recaptcah') == 'yes' && townhub_addons_get_option('g_recaptcha_site_key') != '' ){
    		return '<div class="cth-recaptcha"><div class="g-recaptcha" data-sitekey="'.esc_attr( townhub_addons_get_option('g_recaptcha_site_key') ).'"></div></div>';
    	}
    	return '';
    }

    public function login_recaptcha($html){
    	$html .= $this->get_recaptcha_html();
    	$html .= '<input type="hidden" name="cth-enable-recaptcha" value="1">';
    	return $html;
    }

    /**
	 * An action function used to include the reCAPTCHA JavaScript file
	 * at the end of the page.
	 */
	public function add_captcha_js_to_footer() {
	    if( self::custom_log_reg() ) echo "<script src='https://www.google.com/recaptcha/api.js'></script>";
	}

    // https://code.tutsplus.com/tutorials/build-a-custom-wordpress-user-flow-part-1-replace-the-login-page--cms-23627
    
	// https://code.tutsplus.com/tutorials/build-a-custom-wordpress-user-flow-part-2-new-user-registration--cms-23810
	/**
	 * A shortcode for rendering the new user registration form.
	 *
	 * @param  array   $attributes  Shortcode attributes.
	 * @param  string  $content     The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function render_register_form( $attributes, $content = null ) {
	    // Parse shortcode attributes
	    $default_attributes = array( 'show_title' => false );
	    $attributes = shortcode_atts( $default_attributes, $attributes );
	 
	    if ( is_user_logged_in() ) {
	        return __( 'You are already signed in.', 'townhub-add-ons' );
	    } elseif ( ! get_option( 'users_can_register' ) ) {
	        return __( 'Registering new users is currently not allowed.', 'townhub-add-ons' );
	    } else {
	    	// Retrieve possible errors from request parameters
			$attributes['errors'] = array();
			if ( isset( $_REQUEST['register-errors'] ) ) {
			    $error_codes = explode( ',', $_REQUEST['register-errors'] );
			 
			    foreach ( $error_codes as $error_code ) {
			        $attributes['errors'] []= $this->get_error_message( $error_code );
			    }
			}

	        return $this->get_template_html( 'register-form', $attributes );
	    }
	}
	/**
	 * Redirects the user to the custom registration page instead
	 * of wp-login.php?action=register.
	 */
	public function redirect_to_custom_register() {
	    if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
	        if ( is_user_logged_in() ) {
	            $this->redirect_logged_in_user();
	        } else {
		    	$redirect_url = $this->get_redirect_page( 'register_page' );
	            wp_redirect( $redirect_url );
	        }
	        exit;
	    }
	}

	/**
	 * Validates and then completes the new user signup process if all went well.
	 *
	 * @param string $email         The new user's email address
	 * @param string $first_name    The new user's first name
	 * @param string $last_name     The new user's last name
	 *
	 * @return int|WP_Error         The id of the user that was created, or error if failed.
	 */
	private function register_user( $email, $username, $first_name, $last_name, $as_author = false ) {
	    $errors = new WP_Error();
	 
	    // Email address is used as both username and email. It is also the only
	    // parameter we need to validate
	    if ( ! is_email( $email ) ) {
	        $errors->add( 'email', $this->get_error_message( 'email' ) );
	        return $errors;
	    }
	 
	    if ( username_exists( $username ) ) {
	        $errors->add( 'username_exists', $this->get_error_message( 'username_exists') );
	        return $errors;
	    }

	    if ( email_exists( $email ) ) {
	        $errors->add( 'email_exists', $this->get_error_message( 'email_exists') );
	        return $errors;
	    }

	    if( townhub_addons_get_option('register_password') == 'yes' && isset($_POST['password']) ){
	        $password = $_POST['password'];
	    }else{
	    	// Generate the password so that the subscriber will have to check email...
		    $password = wp_generate_password( 12, false );
	    }
	 	
	 	$user_role = get_option( 'default_role', 'subscriber' );
	 	if( $as_author ){
	 		$user_role = townhub_addons_get_option('author_role'); 
	 	}
		
	 
	    $user_data = array(
	        'user_login'    => $username,
	        'user_email'    => $email,
	        'user_pass'     => $password,
	        'first_name'    => $first_name,
	        'last_name'     => $last_name,
	        'nickname'      => $first_name,
	        'role'			=> $user_role,
	    );
	 
	    $user_id = wp_insert_user( $user_data );
	    // end admin email
	    $notify_user = townhub_addons_get_option('new_user_email');
        if( $notify_user == 'admin' || $notify_user == 'both' ){
            wp_new_user_notification( $user_id, null, 'admin' );
        }
        // if( ( $notify_user == 'user' || $notify_user == 'both' ) && townhub_addons_get_option('register_password') != 'yes' ){
        if(  $notify_user == 'user' || $notify_user == 'both'  ){
            wp_new_user_notification( $user_id, null, 'user' );
        } 
	 
	    return $user_id;
	}

	

	/**
	 * Handles the registration of a new user.
	 *
	 * Used through the action hook "login_form_register" activated on wp-login.php
	 * when accessed through the registration action.
	 */
	public function do_register_user() {
	    if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
	        $redirect_url = $this->get_redirect_page( 'register_page' );

	        if ( ! get_option( 'users_can_register' ) ) {
	            // Registration closed, display error
	            $redirect_url = add_query_arg( 'register-errors', 'closed', $redirect_url );
	        } elseif ( townhub_addons_verify_recaptcha() === false ) {
			    // verify google reCAPTCHA
			    $redirect_url = add_query_arg( 'register-errors', 'captcha', $redirect_url );
	        } else {

	            $email = $_POST['email'];
	            $username = $_POST['username'];
	            $first_name = !empty($_POST['first_name']) ? $_POST['first_name'] : '';
	            $last_name = !empty($_POST['last_name']) ? $_POST['last_name'] : '';

	            $as_author = false;
			    if(townhub_addons_get_option('register_role') == 'yes' && isset($_POST['reg_lauthor']) && $_POST['reg_lauthor'] == 1 ) {
			        $as_author = true;
			    }
			    // default author
			    if(townhub_addons_get_option('register_as_author') == 'yes' ) {
			        $as_author = true;
			    }

	 
	            $result = $this->register_user( $email, $username, $first_name, $last_name, $as_author );
	 
	            if ( is_wp_error( $result ) ) {
	                // Parse errors into a string and append as parameter to redirect
	                $errors = join( ',', $result->get_error_codes() );
	                $redirect_url = add_query_arg( 'register-errors', $errors, $redirect_url );
	            } else {
	                // Success,
	                // $result -> user_id 
	            	do_action( 'townhub_addons_register_user', $result, false );

	            	// redirect to login page.
	                $redirect_url = $this->get_redirect_page( );
	                $redirect_url = add_query_arg( 'registered', $email, $redirect_url );

	            	// only active default author plan when registered user logged + has free_lplan value
			        $inserted_free_membership = false;
			        $free_lplan = townhub_addons_get_option('free_lplan');
			        if( $as_author && !empty($free_lplan) ){
			            Esb_Class_Form_Handler::insert_free_subscription( $free_lplan, $result, false );
			            $inserted_free_membership = true;
			        }

			        // redirect to free membership active page
			        if( $as_author && $inserted_free_membership ){
			        	if( townhub_addons_get_option('auto_active_free_sub') == 'yes' && townhub_addons_get_option('free_redirect_submit') == 'yes' ){
			                $redirect_url = get_permalink( esb_addons_get_wpml_option('submit_page') );
			            }else{
			                $checkout_page_id = esb_addons_get_wpml_option('checkout_success_page');
			                if( $checkout_page_id != 'none' ){
			                    $redirect_url = get_permalink($checkout_page_id); 
			                }
			            }
			        }

	                
	            }
	        }
	 
	        wp_redirect( $redirect_url );
	        exit;
	    }
	}

    // https://code.tutsplus.com/tutorials/build-a-custom-wordpress-user-flow-part-3-password-reset--cms-23811
    /**
	 * Redirects the user to the custom "Forgot your password?" page instead of
	 * wp-login.php?action=lostpassword.
	 */
	public function redirect_to_custom_lostpassword() {
	    if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
	        if ( is_user_logged_in() ) {
	            $this->redirect_logged_in_user();
	            exit;
	        }
	 		$redirect_url = $this->get_redirect_page( 'forget_pwd_page' );
	        wp_redirect( $redirect_url );
	        exit;
	    }
	}

	/**
	 * A shortcode for rendering the form used to initiate the password reset.
	 *
	 * @param  array   $attributes  Shortcode attributes.
	 * @param  string  $content     The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function render_password_lost_form( $attributes, $content = null ) {
	    // Parse shortcode attributes
	    $default_attributes = array( 'show_title' => false );
	    $attributes = shortcode_atts( $default_attributes, $attributes );
	 
	    if ( is_user_logged_in() ) {
	        return __( 'You are already signed in.', 'townhub-add-ons' );
	    } else {
	    	// Retrieve possible errors from request parameters
			$attributes['errors'] = array();
			if ( isset( $_REQUEST['errors'] ) ) {
			    $error_codes = explode( ',', $_REQUEST['errors'] );
			 
			    foreach ( $error_codes as $error_code ) {
			        $attributes['errors'] []= $this->get_error_message( $error_code );
			    }
			}

			$attributes['recaptcha'] = $this->get_recaptcha_html();

	        return $this->get_template_html( 'password-lost', $attributes );
	    }
	}

	/**
	 * Initiates password reset.
	 */
	public function do_password_lost() {
	    if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
	    	$redirect_url = $this->get_redirect_page( 'forget_pwd_page' );
	    	if( townhub_addons_verify_recaptcha() === false ){
	    		
	            $redirect_url = add_query_arg( 'errors', 'captcha', $redirect_url );
	    	}else{
	    		$errors = retrieve_password();
		        if ( is_wp_error( $errors ) ) {
		            // Errors found
		            $redirect_url = add_query_arg( 'errors', join( ',', $errors->get_error_codes() ), $redirect_url );
		        } else {
		            // Email sent
		            $redirect_url = $this->get_redirect_page( );
		            $redirect_url = add_query_arg( 'checkemail', 'confirm', $redirect_url );
		        }
	    	}
		        
	        wp_redirect( $redirect_url );
	        exit;
	    }
	}

	public function modify_lostpassword_url($url){
		return $this->get_redirect_page( 'forget_pwd_page' );
	}

	/**
	 * Returns the message body for the password reset mail.
	 * Called through the retrieve_password_message filter.
	 *
	 * @param string  $message    Default mail message.
	 * @param string  $key        The activation key.
	 * @param string  $user_login The username for the user.
	 * @param WP_User $user_data  WP_User object.
	 *
	 * @return string   The mail message to send.
	 */
	public function replace_retrieve_password_message( $message, $key, $user_login, $user_data ) {
		if ( is_multisite() ) {
	        $site_name = get_network()->site_name;
	    } else {
	        /*
	         * The blogname option is escaped with esc_html on the way into the database
	         * in sanitize_option we want to reverse this for the plain text arena of emails.
	         */
	        $site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
	    }
	    // Create new message
	    $email_vars = array(
	        'site_name'     => $site_name,
	        'username'      => $user_login,
	        'user_email'    => $user_data->user_email,
	        'reset_url'     => network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ),
	    );
	    $email_template = townhub_addons_get_option('forget_pwd_email');

	    $message      = Esb_Class_Emails::process_email_template($email_template, $email_vars);

	    
	    $message = apply_filters( 'esb_reset_password_email', $message, $user_data );

	    add_filter( 'wp_mail', function($attrs){
	    	$attrs['headers'] = array('Content-Type: text/html; charset=UTF-8') ;
	    	return $attrs;
	    } );

    	return $message;
	}

    /**
	 * Redirects to the custom password reset page, or the login page
	 * if there are errors.
	 */
	public function redirect_to_custom_password_reset() {
	    if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
	    	
	        // Verify key / login combo
	        $user = check_password_reset_key( $_REQUEST['key'], $_REQUEST['login'] );
	        if ( ! $user || is_wp_error( $user ) ) {
	        	$redirect_url = $this->get_redirect_page( );
	            if ( $user && $user->get_error_code() === 'expired_key' ) {
	            	$redirect_url = add_query_arg( 'login', 'expiredkey', $redirect_url );
	            } else {
	            	$redirect_url = add_query_arg( 'login', 'invalidkey', $redirect_url );
	            }
	            wp_redirect( $redirect_url );
	            exit;
	        }
	        $redirect_url = $this->get_redirect_page( 'reset_pwd_page' );

	        $redirect_url = add_query_arg( 'login', esc_attr( $_REQUEST['login'] ), $redirect_url );
	        $redirect_url = add_query_arg( 'key', esc_attr( $_REQUEST['key'] ), $redirect_url );
	 
	        wp_redirect( $redirect_url );
	        exit;
	    }
	}
	/**
	 * A shortcode for rendering the form used to reset a user's password.
	 *
	 * @param  array   $attributes  Shortcode attributes.
	 * @param  string  $content     The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function render_password_reset_form( $attributes, $content = null ) {
	    // Parse shortcode attributes
	    $default_attributes = array( 'show_title' => false );
	    $attributes = shortcode_atts( $default_attributes, $attributes );
	 
	    if ( is_user_logged_in() ) {
	        return __( 'You are already signed in.', 'townhub-add-ons' );
	    } else {
	        if ( isset( $_REQUEST['login'] ) && isset( $_REQUEST['key'] ) ) {
	            $attributes['login'] = $_REQUEST['login'];
	            $attributes['key'] = $_REQUEST['key'];
	 
	            // Error messages
	            $errors = array();
	            if ( isset( $_REQUEST['error'] ) ) {
	                $error_codes = explode( ',', $_REQUEST['error'] );
	 
	                foreach ( $error_codes as $code ) {
	                    $errors []= $this->get_error_message( $code );
	                }
	            }
	            $attributes['errors'] = $errors;
	 
	            return $this->get_template_html( 'password-reset', $attributes );
	        } else {
	            return __( 'Invalid password reset link.', 'townhub-add-ons' );
	        }
	    }
	}

	/**
	 * Resets the user's password if the password reset form was submitted.
	 */
	public function do_password_reset() {
	    if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
	        $rp_key = $_REQUEST['rp_key'];
	        $rp_login = $_REQUEST['rp_login'];
	 		
	 		$redirect_url = $this->get_redirect_page( );

	        $user = check_password_reset_key( $rp_key, $rp_login );
	        if ( ! $user || is_wp_error( $user ) ) {
        	
	            if ( $user && $user->get_error_code() === 'expired_key' ) {
	            	$redirect_url = add_query_arg( 'login', 'expiredkey', $redirect_url );
	            } else {
	            	$redirect_url = add_query_arg( 'login', 'invalidkey', $redirect_url );
	            }
	            wp_redirect( $redirect_url );
	            exit;
	        }
	 
	        if ( isset( $_POST['pass1'] ) ) {
	        	$redirect_url = $this->get_redirect_page( 'reset_pwd_page' );

	            if ( $_POST['pass1'] != $_POST['pass2'] ) {
	                // Passwords don't match
	                $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
	                $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
	                $redirect_url = add_query_arg( 'error', 'password_reset_mismatch', $redirect_url );
	 
	                wp_redirect( $redirect_url );
	                exit;
	            }
	 
	            if ( empty( $_POST['pass1'] ) ) {
	                // Password is empty
	                $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
	                $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
	                $redirect_url = add_query_arg( 'error', 'password_reset_empty', $redirect_url );
	 
	                wp_redirect( $redirect_url );
	                exit;
	            }
	 			// if( !empty($login_page_id) ){
	    //     		$redirect_url = get_permalink( $login_page_id );
	    //     	}
	            $redirect_url = $this->get_redirect_page( );
	            // Parameter checks OK, reset password
	            reset_password( $user, $_POST['pass1'] );
	            wp_redirect( add_query_arg( 'password', 'changed', $redirect_url ) );
	        } else {
	            echo __( 'Invalid request.', 'townhub-add-ons' );
	        }
	 
	        exit;
	    }
	}

	public function miniOrange_and_fbl_social_register($user_id){
	    // default author
	    if(townhub_addons_get_option('register_as_author') == 'yes' ) {

	    	$user = get_user_by('ID', $user_id);
            $user->set_role( townhub_addons_get_option('author_role') );

	        // only active default author plan when registered user logged + has free_lplan value
	        $inserted_free_membership = false;
	        $free_lplan = townhub_addons_get_option('free_lplan');
	        if( !empty($free_lplan) ){
	            Esb_Class_Form_Handler::insert_free_subscription( $free_lplan, $user_id, false );
	            $inserted_free_membership = true;
	        }


	        // // redirect to free membership active page
	        // if( $inserted_free_membership ){
	        //     $checkout_page_id = esb_addons_get_wpml_option('checkout_success_page');
	        //     if($checkout_page_id != 'none'){
	        //         $redirect_url =  get_permalink($checkout_page_id); 
	        //     }
	        // }
	    }

	}

	public static function billingDetails($user_id = 0){
		if( empty($user_id) ) return;
		$fields = array(
			'billing_first_name',
	        'billing_last_name',
	        'billing_phone',
	        'billing_email' ,
	        // 'billing_company',
	        
	        'billing_address_1',
	        'billing_address_2',
	        'billing_postcode',
	        
	        
	        'billing_city',
	        'billing_state',
	        'billing_country',
		);

		$details = array();
		foreach ($fields as $meta_key) {
			$details[$meta_key] = get_user_meta( $user_id, $meta_key, true );
        }
        $infos = array();
        if( !empty($details['billing_first_name']) || !empty($details['billing_last_name']) ){
        	$infos['name'] = '<div class="ubilling-field ubilling-name"><div class="ubilling-label">'.esc_html_x('Name','User billing','townhub-add-ons').'</div><div class="ubilling-content">'.sprintf( esc_html_x('%1$s %2$s','User billing','townhub-add-ons'), $details['billing_first_name'], $details['billing_last_name'] ).'</div></div>';
        }
        if( !empty($details['billing_phone']) ){
        	$infos['phone'] = '<div class="ubilling-field ubilling-phone"><div class="ubilling-label">'.esc_html_x('Phone','User billing','townhub-add-ons').'</div><div class="ubilling-content">'.esc_html($details['billing_phone']).'</div></div>';
        }
        if( !empty($details['billing_email']) ){
        	$infos['email'] = '<div class="ubilling-field ubilling-email"><div class="ubilling-label">'.esc_html_x('Email','User billing','townhub-add-ons').'</div><div class="ubilling-content">'.esc_html($details['billing_email']).'</div></div>';
        }
        $addresses = array();
        if( !empty($details['billing_address_1']) ){
        	$addresses[] = esc_html($details['billing_address_1']);
        }
        if( !empty($details['billing_address_2']) ){
        	$addresses[] = esc_html($details['billing_address_2']);
        }
        if( !empty($details['billing_postcode']) ){
        	$addresses[] = esc_html($details['billing_postcode']);
        }
        if( !empty($details['billing_city']) ){
        	$addresses[] = esc_html($details['billing_city']);
        }
        if( !empty($details['billing_state']) ){
        	$addresses[] = esc_html($details['billing_state']);
        }
        if( !empty($details['billing_country']) ){
        	$addresses[] = esc_html($details['billing_country']);
        }
        if( !empty($addresses) ){
        	$infos['address'] = '<div class="ubilling-field ubilling-address"><div class="ubilling-label">'.esc_html_x('Address','User billing','townhub-add-ons').'</div><div class="ubilling-content"><span>'.implode("<br />", $addresses).'</span></div></div>';
        }

        if( !empty($infos) ){
        	return '<div class="ubilling-fields">'.implode("\n", $infos).'</div>';
        }

        return '';
	}
}

Esb_Class_User::getInstance();