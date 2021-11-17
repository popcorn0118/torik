<?php
/* add_ons_php */

/* tweet feeds widget action */
add_action('wp_ajax_nopriv_townhub_get_tweets', 'townhub_add_ons_get_tweets_callback');
add_action('wp_ajax_townhub_get_tweets', 'townhub_add_ons_get_tweets_callback');

require_once( ESB_ABSPATH . "inc/twitter-api/twitteroauth/twitteroauth.php");
/**
 * Gets connection with user Twitter account
 * @param  String $cons_key     Consumer Key
 * @param  String $cons_secret  Consumer Secret Key
 * @param  String $oauth_token  Access Token
 * @param  String $oauth_secret Access Secrete Token
 * @return Object               Twitter Session
 */
function townhub_add_ons_getConnectionWithToken($cons_key, $cons_secret, $oauth_token, $oauth_secret)
{
    $connection = new CTH_TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_secret);
  
    return $connection;
}

function townhub_add_ons_get_tweets_callback(){

    
    // Cache Settings
    define('CACHE_ENABLED', false);
    define('CACHE_LIFETIME', 3600); // in seconds
    define('HASH_SALT', md5(ESB_ABSPATH."inc/twitter-api/"));

    $consumer_key = townhub_addons_get_option('consumer_key');
    $consumer_secret = townhub_addons_get_option('consumer_secret');
    $access_token = townhub_addons_get_option('access_token');
    $access_token_secret = townhub_addons_get_option('access_token_secret');

    // var_dump($consumer_key);
    // var_dump($consumer_secret);
    // var_dump($access_token);
    // var_dump($access_token_secret);

    // wp_send_json( array(
    //     'consumer_key' => $consumer_key,
    //     'consumer_secret' => $consumer_secret,
    //     'access_token' => $access_token,
    //     'access_token_secret' => $access_token_secret,
    // ) );

    // Check if keys are in place
    if ($consumer_key == '' || $consumer_secret == '' || $access_token == '' || $access_token_secret == '') {
        wp_send_json( esc_html__( 'You need a consumer key and secret keys. Get one from','townhub-add-ons' ).'<a href="'.esc_url('https://apps.twitter.com/' ).'" target="_blank">apps.twitter.com</a>' ) ;
    }

    // If count of tweets is not fall back to default setting
    $username = filter_input(INPUT_GET, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
    $number = filter_input(INPUT_GET, 'count', FILTER_SANITIZE_NUMBER_INT);
    $exclude_replies = filter_input(INPUT_GET, 'exclude_replies', FILTER_SANITIZE_SPECIAL_CHARS);
    $list_slug = filter_input(INPUT_GET, 'list', FILTER_SANITIZE_SPECIAL_CHARS);
    $hashtag = filter_input(INPUT_GET, 'hashtag', FILTER_SANITIZE_SPECIAL_CHARS);
    
    
    // Connect
    $connection = townhub_add_ons_getConnectionWithToken($consumer_key, $consumer_secret, $access_token, $access_token_secret);
    
    // Get Tweets
    if (!empty($list_slug)) {
        // https://developer.twitter.com/en/docs/twitter-api/v1/accounts-and-users/create-manage-lists/api-reference/get-lists-statuses
      $params = array(
          'owner_screen_name' => $username,
          'slug' => $list_slug,
          'list_id' => $list_slug,
          'per_page' => $number
      );

      $url = '/lists/statuses';
    } else if($hashtag) {
      $params = array(
          'count' => $number,
          'q' => '#'.$hashtag
      );

      $url = '/search/tweets';
    } else {
      $params = array(
          'count' => $number,
          'exclude_replies' => $exclude_replies,
          'screen_name' => $username
      );

      $url = '/statuses/user_timeline';
    }

    $tweets = $connection->get($url, $params);

    wp_send_json($tweets);

}
add_action('wp_ajax_nopriv_townhub_mailchimp', 'townhub_mailchimp_subscribe_callback');
add_action('wp_ajax_townhub_mailchimp', 'townhub_mailchimp_subscribe_callback');

/*
 *  @desc   Register user
*/
require_once ESB_ABSPATH .'inc/classes/Drewm/CTHMailChimp.php';
function townhub_mailchimp_subscribe_callback() {
    $output = array();
    $output['success'] = 'no';



    if ( ! isset( $_POST['_nonce'] ) || ! wp_verify_nonce( $_POST['_nonce'], 'townhub_mailchimp' ) ){
        $output['message'] = esc_html__('Sorry, your nonce did not verify.','townhub-add-ons' );
        wp_send_json( $output );
    }
    if(isset($_POST['_list_id'])&& $_POST['_list_id']){
        $list_id = $_POST['_list_id'];
    }else{
        $list_id = townhub_addons_get_option('mailchimp_list_id'); 
    }

    try {
        /*
         * ------------------------------------
         * Mailchimp Email Configuration
         * ------------------------------------
         */
        $MailChimp = new CTH_MailChimp( townhub_addons_get_option('mailchimp_api') );

        $result = $MailChimp->post("lists/$list_id/members", array(
            'email_address' => $_POST['email'],
            'status'        => 'subscribed'
        ) );

        if ($MailChimp->success()) {
            $output['success'] = 'yes';
            $output['message'] = esc_html__('Almost finished. Please check your email and verify.','townhub-add-ons' );
            $output['last_response'] = $MailChimp->getLastResponse();
        } else {
            $output['message'] = esc_html__('Oops. Something went wrong!','townhub-add-ons' );
            $output['last_response'] = $MailChimp->getLastResponse();
        }
    } catch (Exception $e) {
        $output['message'] = esc_html__('Oops. Something went wrong!','townhub-add-ons' );
        $output['last_response'] = $e->getMessage();
    }
        
    wp_send_json( $output );
}

add_action('wp_ajax_nopriv_townhub_get_vc_attach_images', 'townhub_get_vc_attach_images_callback');
add_action('wp_ajax_townhub_get_vc_attach_images', 'townhub_get_vc_attach_images_callback');

function townhub_get_vc_attach_images_callback() {
    $images = $_POST['images'];
    $html = $images;
    if($images != '') {
        $images = explode(",", $images);
        if(count($images)){
            $html = '';
            foreach ($images as $key => $img) {
                $html .= wp_get_attachment_image( $img, 'thumbnail', '', array('class'=>'townhub-ele-attach-thumb') );
            }
        }
    }
    wp_send_json($html );
}


/* Login Ajax Action */
add_action( 'wp_ajax_nopriv_townhub-login', 'townhub_addons_login_callback' );
// add_action( 'wp_ajax_townhub-login', 'townhub_addons_login_callback' );
/*
 *  @desc   Process theme login
 */
function townhub_addons_login_callback() {

    $json = array(
        'success' => false,
        'message' => '',
        'data' => array(
            '_POST'=>$_POST
        ),
        'debug'     => false,
    );

    // wp_send_json($json );

    // verify google reCAPTCHA
    if( townhub_addons_verify_recaptcha() === false ){
        $json['success'] = false;
        $json['message'] = esc_html__( 'reCAPTCHA failed, please try again.', 'townhub-add-ons' ) ;
        wp_send_json($json );
    }

    if(townhub_addons_get_option('log_reg_dis_nonce') != 'yes' ){
        $nonce = $_POST['_loginnonce'];
        
        if ( ! wp_verify_nonce( $nonce, 'townhub-login' ) ){
            $json['success'] = false;
            $json['message'] = esc_html__( 'Security checked!, Cheatn huh?', 'townhub-add-ons' ) ;
            wp_send_json($json );
        }
    }
        
    // https://codex.wordpress.org/Function_Reference/wp_signon
    // NOTE: If you don't provide $credentials, wp_signon uses the $_POST variable (the keys being "log", "pwd" and "rememberme").
    $redirection = isset($_POST['redirection']) ? $_POST['redirection'] : '';
    // set the WP login cookie
    $secure_cookie = is_ssl() ? true : false;
    $user = wp_signon( NULL, $secure_cookie );

    if ( is_wp_error($user) ) {
        $json['success'] = false;
        $json['message'] = $user->get_error_message();
    } else {
        $json['success'] = true;
        // $json['data']['townhub_addons_do_wp_mail'] =  townhub_addons_do_wp_mail( 'cththemes@gmail.com', 'User Login', 'There is an user login success');
        
        do_action( 'townhub_addons_user_login' );
        // townhub_addons_auto_login_new_user( $user->ID );

        $json['data']['userID'] = $user->ID;
        if( !empty($redirection) ) $json['redirection'] =  esc_url( $redirection ); 

        $json['message'] = __( 'Login success.', 'townhub-add-ons' );

        // Esb_Class_Dashboard::add_notification($user->ID, array('type'=>'logged_in') );
    }

    wp_send_json($json );
}

add_action( 'wp_ajax_nopriv_townhub-register', 'townhub_addons_registration_callback' );
add_action( 'wp_ajax_townhub-register', 'townhub_addons_registration_callback' );

/*
 *  @desc   Register user
 */
function townhub_addons_registration_callback() {
    $json = array(
        'success' => false,
        'data' => array(
            '_POST'=>$_POST
        )
    );

    if( get_option( 'users_can_register' ) != 1){
        $json['data']['reg_err'] = esc_html__( 'User registration feature is disabled.', 'townhub-add-ons' ) ;
        wp_send_json($json );
    }

    // wp_send_json($json );

    // verify google reCAPTCHA
    if( townhub_addons_verify_recaptcha() === false ){
        $json['success'] = false;
        $json['data']['reg_err'] = esc_html__( 'reCAPTCHA failed, please try again.', 'townhub-add-ons' ) ;
        wp_send_json($json );
    }

    if(townhub_addons_get_option('log_reg_dis_nonce') != 'yes' ){
        $nonce = $_POST['_regnonce'];
        
        if ( ! wp_verify_nonce( $nonce, 'townhub-register' ) ){
            $json['success'] = false;
            $json['data']['reg_err'] = esc_html__( 'Security checked!, Cheatn huh?', 'townhub-add-ons' ) ;
            wp_send_json($json );
        }
    }

        

    // check for corrent email
    if ( !is_email( $_POST['email'] ) ) {
        $json['success'] = false;
        $json['data']['reg_err'] = esc_html__( 'Invalid email address.', 'townhub-add-ons' ) ;
        wp_send_json($json );
    }
    $first_name = !empty($_POST['first_name']) ? $_POST['first_name'] : $_POST['username'];
    $last_name = !empty($_POST['last_name']) ? $_POST['last_name'] : '';
    
    $new_user_data = array(
        'user_login'    => $_POST['username'],
        'first_name'    => $first_name,
        'last_name'     => $last_name,
        'user_pass'     => wp_generate_password( 12, false ), // $_POST['password'], // // When creating an user, `user_pass` is expected.
        'user_email'    => $_POST['email'],
        // 'role'       => 'l_customer' //'subscriber'
    );
    $registered_as_author = false;
    if(townhub_addons_get_option('register_role') == 'yes' && isset($_POST['reg_lauthor']) && $_POST['reg_lauthor'] == 1 ) {
        $new_user_data['role'] = townhub_addons_get_option('author_role'); 
        $registered_as_author = true;
    }
    // default author
    if(townhub_addons_get_option('register_as_author') == 'yes' ) {
        $new_user_data['role'] = townhub_addons_get_option('author_role'); 
        $registered_as_author = true;
    }
    
    if(isset($_POST['password'])){
        $new_user_data['user_pass'] = $_POST['password'];
    }

    $user_id = wp_insert_user( $new_user_data );

    //On success
    if ( ! is_wp_error( $user_id ) ) {
        $json['success'] = true;
        // echo "User created : ". $user_id;
        // send login
        $notify_user = townhub_addons_get_option('new_user_email');
        // end admin email
        if( $notify_user == 'admin' || $notify_user == 'both' ){
            wp_new_user_notification( $user_id, null, 'admin' );
        }
        // if( ( $notify_user == 'user' || $notify_user == 'both' ) && townhub_addons_get_option('register_password') != 'yes' ){
        if(  $notify_user == 'user' || $notify_user == 'both'  ){
            wp_new_user_notification( $user_id, null, 'user' );
        } 

        $json['data']['user_id'] = $user_id;

        if(townhub_addons_get_option('register_auto_login') == 'yes') townhub_addons_auto_login_new_user( $user_id );
        
        do_action( 'townhub_addons_register_user', $user_id, false );

        // // Set the global user object
        // $current_user = get_user_by( 'id', $user_id );

        // // set the WP login cookie
        // $secure_cookie = is_ssl() ? true : false;

        // only active default author plan when registered user logged + has free_lplan value
        $inserted_free_membership = false;
        if( $registered_as_author && $free_lplan = townhub_addons_get_option('free_lplan') ){
            Esb_Class_Form_Handler::insert_free_subscription( $free_lplan, $user_id, false );
            $inserted_free_membership = true;
        }

        


        // wp_set_auth_cookie( $user_id, true, $secure_cookie ); // This function does not return a value.

        if( townhub_addons_get_option('register_no_redirect') != 'yes' && isset($_POST['redirection']) ) $json['data']['redirection'] =  esc_url($_POST['redirection']); 

        // redirect to free membership active page
        if( $registered_as_author && $inserted_free_membership ){
            if( townhub_addons_get_option('auto_active_free_sub') == 'yes' && townhub_addons_get_option('free_redirect_submit') == 'yes' ){
                $json['data']['redirection'] = get_permalink( esb_addons_get_wpml_option('submit_page') );
            }else{
                $checkout_page_id = esb_addons_get_wpml_option('checkout_success_page');
                if( $checkout_page_id != 'none' ){
                    $json['data']['redirection'] = esc_url( get_permalink($checkout_page_id) ); 
                }
            }
        }
        $json['data']['reg_msg'] = __( 'Successfully registered. Check your email address for the password.', 'townhub-add-ons' );

    }else{
        $json['success'] = false;
        $json['data']['reg_err'] = $user_id->get_error_message() ;
        $json['data']['new_user_data'] = $new_user_data ;
        // $json['data']['at_pos'] = strpos("@", $_POST['user_email']);
        // $json['data']['substr'] = substr($_POST['user_email'], 0, strpos($_POST['user_email'], "@") );

        

    }

    wp_send_json( $json );

}

// reset password callback
add_action('wp_ajax_nopriv_townhub_addons_reset_password', 'townhub_addons_reset_password_callback');
add_action('wp_ajax_townhub_addons_reset_password', 'townhub_addons_reset_password_callback');

function townhub_addons_reset_password_callback() {
    $json = array(
        'success' => false,
        'data' => array(
            'POST'=>$_POST,
        )
    );
    
    if(townhub_addons_get_option('log_reg_dis_nonce') != 'yes' ){
        $nonce = $_POST['_nonce'];
        
        if ( ! wp_verify_nonce( $nonce, 'townhub-add-ons' ) ){
            $json['data']['error'] = esc_html__( 'Security checked!, Cheatn huh?', 'townhub-add-ons' ) ;
            wp_send_json($json );
        }
    }

        

    if ( empty( $_POST['user_login'] ) || ! is_string( $_POST['user_login'] ) ) {
        $json['data']['error'] = esc_html__( 'Enter a username or email address.', 'townhub-add-ons' ) ;
        wp_send_json($json );
    } elseif ( strpos( $_POST['user_login'], '@' ) ) {
        $user_data = get_user_by( 'email', trim( wp_unslash( $_POST['user_login'] ) ) );
        if ( empty( $user_data ) ){
            $json['data']['error'] = esc_html__( 'There is no user registered with that email address.', 'townhub-add-ons' ) ;
            wp_send_json($json );
        }
    } else {
        $login = trim($_POST['user_login']);
        $user_data = get_user_by('login', $login);
    }

    if ( !$user_data ) {
        $json['data']['error'] = esc_html__( 'Invalid username or email.', 'townhub-add-ons' ) ;
        wp_send_json($json );
    }

    // Redefining user_login ensures we return the right case in the email.
    $key = get_password_reset_key( $user_data );
    if ( is_wp_error( $key ) ) {
        $json['data']['error'] = esc_html__( 'There is something wrong. Please try again.', 'townhub-add-ons' ) ;
        wp_send_json($json );
    }

    if ( ! townhub_addons_send_resetpwd_email($user_data,$key) ){
        $json['data']['error'] = esc_html__( 'The email could not be sent.', 'townhub-add-ons' ) ;
        wp_send_json($json );

    }

    $json['success'] = true;
    $json['data']['message'] = apply_filters( 'townhub_addons_reset_password_message', __( 'Your Password is reset. Check your email to complete the action.', 'townhub-add-ons' ) );



    // if(function_exists('retrieve_password')){
    //     $errors = retrieve_password();
    //     if ( !is_wp_error($errors) ) {
    //         $json['success'] = true;
    //         $json['data']['message'] = apply_filters( 'townhub_addons_reset_password_message', __( 'Please check your email to complete the action.', 'townhub-add-ons' ) );
    //     }
    // }else{
    //     $json['data']['error'] = esc_html__( 'The retrieve_password function doesn\'t exists', 'townhub-add-ons' ) ;
    // }

    wp_send_json($json );

}

function townhub_addons_send_resetpwd_email($user_data,$key){
    // Redefining user_login ensures we return the right case in the email.
    $user_login = $user_data->user_login;
    $user_email = $user_data->user_email;
    
    if ( is_multisite() ) {
        $site_name = get_network()->site_name;
    } else {
        /*
         * The blogname option is escaped with esc_html on the way into the database
         * in sanitize_option we want to reverse this for the plain text arena of emails.
         */
        $site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
    }
    $email_vars = array(
        'site_name'     => $site_name,
        'username'      => $user_login,
        'user_email'    => $user_email,
        'reset_url'     => network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ),
    );
    $email_template = townhub_addons_get_option('forget_pwd_email');

    $message      = Esb_Class_Emails::process_email_template($email_template, $email_vars);

    
    $message = apply_filters( 'esb_reset_password_email', $message, $user_data );
    /* translators: Password reset email subject. %s: Site name */
    $title = sprintf( __( '[%s] Password Reset' ,'townhub-add-ons'), $site_name );

    $headers = array();
    if (townhub_addons_get_option('emails_ctype') == 'html'){
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
    }
    if ( empty($message)  || !wp_mail( $user_email, wp_specialchars_decode( $title ), $message, $headers ) ){
        return false;
    }
    return true;
}

// add_filter( 'esb_reset_password_email', function($message,$user_obj){
//     $user_login = $user_obj->user_login;
//     $user_email = $user_obj->user_email;
    
//     if ( is_multisite() ) {
//         $site_name = get_network()->site_name;
//     } else {
//         /*
//          * The blogname option is escaped with esc_html on the way into the database
//          * in sanitize_option we want to reverse this for the plain text arena of emails.
//          */
//         $site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
//     }
//     $message = __( 'Someone has requested a password reset for the following account:','townhub-add-ons' ) . "\r\n\r\n";
//     /* translators: %s: site name */
//     $message .= sprintf( __( 'Site Name: %s','townhub-add-ons'), $site_name ) . "\r\n\r\n";
//     /* translators: %s: user login */
//     $message .= sprintf( __( 'Username: %s','townhub-add-ons'), $user_login ) . "\r\n\r\n";
//     $message .= __( 'If this was a mistake, just ignore this email and nothing will happen.' ,'townhub-add-ons') . "\r\n\r\n";
//     $message .= __( 'To reset your password, visit the following address:' ,'townhub-add-ons') . "\r\n\r\n";
//     $message .= '<' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . ">\r\n";

//     return $message;
// }, 10, 2 );

function file_get_contents_stream($fn, $content_type = '') { 
    $opts = array( 
        'http' => array( 
            'method'=>"GET", 
            'header'=>"Content-Type: text/html;" 
        ) 
    ); 
    if($content_type != '') $opts['http']['header'] = "Content-Type: {$content_type};";

    $context = stream_context_create($opts); 
    $result = @file_get_contents($fn, false, $context); 
    return $result; 
}   
add_action('wp_ajax_nopriv_fetch_weather', 'townhub_addons_fetch_weather_callback');
add_action('wp_ajax_fetch_weather', 'townhub_addons_fetch_weather_callback');
function townhub_addons_fetch_weather_callback(){

        $json = array(
            'success' => false,
            'data' => array(
                'POST'=>$_POST,
            ),
            'debug'         => false,
        );

        

        $locale = get_locale();
        if($locale == '') $locale = 'en_US';
        $locale = strtolower($locale);
        if($locale != 'zh_cn' || $locale != 'zh_tw') $locale = preg_replace('/_.+$/m', '', trim($locale));
        
        $params = array(
            'appid'             => townhub_addons_get_option('weather_api'),
            // 'appid'                =>   'dba2268d1da306d9cd0933da05cfee06',
            // 'q'              => isset($_POST['location']) ? $_POST['location'] : '',
            // 'lat'               => '35',
            // 'lon'               => '139',
            // 'units'             => 'metric',
            'lang'              => $locale,
        );
        $wunit = townhub_addons_get_option('weather_unit', 'metric');
        if( $wunit != 'kelvin' ){
            $params['units'] = $wunit;
        }
        

        if(isset($_POST['lat']) && isset($_POST['lon'])){
            $params['lat'] = $_POST['lat'];
            $params['lon'] = $_POST['lon'];
        }else{
            $params['q'] = isset($_POST['location']) ? trim($_POST['location'], " ,") : '';
        }

        $params = http_build_query($params, null, '&', PHP_QUERY_RFC3986);

        // $weather_api = townhub_addons_get_option('weather_api');

        // $api_url = "https://api.openweathermap.org/data/2.5/weather?lat=35&lon=139&appid={$weather_api}"; // -> https://prntscr.com/m3z1hb

        $api_url = "https://api.openweathermap.org/data/2.5/forecast?{$params}"; // -> http://prntscr.com/m3z59g

        if(isset($_POST['view']) && $_POST['view'] == 'simple') $api_url = "https://api.openweathermap.org/data/2.5/weather?{$params}"; // -> https://prntscr.com/m3z1hb

        $json['url_db'] = $api_url;

        $response = wp_remote_get( $api_url );
 
        if ( is_array( $response ) && ! is_wp_error( $response ) ) {
            // $headers = $response['headers']; // array of http header lines
            // $body    = $response['body']; // use the content
            // error_log(json_encode($response));
            $json['success'] = true;
            $json['result'] = json_decode($response['body']);
        }else{
            // $json['debug'] = true;
            $json['error'] = __( 'Weather request error. Please make sure that your api is entered.', 'townhub-add-ons' );
        }

        // $result = file_get_contents_stream($api_url, 'application/json'); // JSON - Content-Type: application/json | JSONP = Content-Type: application/javascript
        
        // // if( ESB_DEBUG ) error_log(date('[Y-m-d H:i e] - '). "openweathermap - current: " . $result . PHP_EOL, 3, './openweathermap-current.log');
        // // if( ESB_DEBUG ) error_log(date('[Y-m-d H:i e] - '). "openweathermap - forecast: " . $result . PHP_EOL, 3, './openweathermap-forecast.log');

        // if($result === false){
        //     $json['error'] = __( 'Weather request error. Please make sure that your api is entered.', 'townhub-add-ons' );
        // }
        // else{
        //     $json['success'] = true;
        //     $json['result'] = json_decode($result);
        // }

        // $json['yahoo'] = self::featch_yahoo_weather(); // current return false
        // 'success' => false,
        wp_send_json( $json );


    }   

add_action('wp_ajax_get_curr_rate', 'townhub_addons_get_curr_rate_callback');
function townhub_addons_get_curr_rate_callback(){

    $json = array(
        'success' => false,
        'data' => array(
            // 'POST'=>$_POST,
        ),
        'debug'         => false,
    );
    $params = array(
        'compact'             => 'ultra',
        'apiKey'              => apply_filters( 'cth_currconv_api', townhub_addons_get_option('curr_convert_api', '39dd0de7891d0b93c9d0') ), 
        //; _x( '39dd0de7891d0b93c9d0', 'Change with your currencyconverterapi.com API key', 'default' ),
        
    );
    if( isset($_POST['base']) &&  isset($_POST['curr']) ){
        $params['q'] = $_POST['base'].'_'.$_POST['curr'];
    }else{
        $params['q'] = 'EUR_USD';
    }
    $params_str = http_build_query($params, null, '&', PHP_QUERY_RFC3986);
    $api_url = townhub_addons_get_option('curr_convert_free', 'yes') == 'yes' ? "https://free.currconv.com/api/v7/convert?{$params_str}" : "https://api.currconv.com/api/v7/convert?{$params_str}";
    $response = wp_remote_get( esc_url_raw( $api_url ) );
    if ( is_wp_error( $response ) ) {
        $json['error'] = __( 'currencyconverterapi.com request error!', 'townhub-add-ons' );
    }else{
        /* Will result in $api_response being an array of data,
        parsed from the JSON response of the API listed above */
        $api_response = json_decode( wp_remote_retrieve_body( $response ), true );

        $json['success'] = true;

        $json['rate'] = $api_response[$params['q']];
    }
    wp_send_json( $json );
}




