<?php 
/* add_ons_php */

defined( 'ABSPATH' ) || exit; 
// $old_options = get_option( 'ea'.'sy'.'book-addons-options', false ); 
// if($old_options){
//     update_option( 'townhub-addons-options', $old_options );
//     delete_option( 'ea'.'sy'.'book-addons-options' );
// }
// set global options value
if(!isset($townhub_addons_options)) 
    $townhub_addons_options = get_option( 'townhub-addons-options', array() ); 

final class TownHub_Addons { 
    public $cthversion = '1.5.9';
    public $cart = null;
    public $geo = null;
    private static $_instance;

    public $options = null;
    private $plugin_url;
    private $plugin_path;
    public $payment_methods;

    private function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
    }

    private function init_hooks() {
        add_action('plugins_loaded', array( $this, 'load_plugin_textdomain' ));
        add_action('after_setup_theme', array( $this, 'after_setup_theme' ));



        register_activation_hook( ESB_PLUGIN_FILE, array( 'Esb_Class_Install', 'install') );
        register_deactivation_hook( ESB_PLUGIN_FILE, array( 'Esb_Class_Install', 'uninstall') );

        add_action( 'admin_init', array( 'Esb_Class_Install', 'update') );

        add_action( 'init', array( $this, 'init' ), 0 );
        // add_action( 'init', array( $this, 'init_after' ) ); // flush_rewrite_rules

        add_action( 'init', array( $this, 'init_scheduler' ) );
        add_action( 'townhub_expire_scheduler_action', array( $this, 'do_expire_scheduler' ) );
        add_action( 'townhub_ical_sync_scheduler_action', array( $this, 'do_ical_sync_scheduler' ) );

        add_action( 'widgets_init', array( $this, 'register_widgets' ), 11 );

        add_action( 'wp_loaded', array( $this, 'set_cookie_currency' ) );

        add_filter( 'ajax_query_attachments_args', array($this, 'filter_media_frontend') );
        add_filter('body_class', array($this,'townhub_addons_body_classes'));
        // add_action('admin_notices', array($this,'townhub_mobile_app_available'));

        // add_filter( 'run_wptexturize', '__return_false' );
        // http://prntscr.com/os0n76
        add_filter( 'wpml_single_edit_language_context', function($context, $post_type){
            if($post_type === 'listing_type') $context = 'normal';
            return $context;
        } , 10, 2);
    }

    public function load_plugin_textdomain(){
        load_plugin_textdomain( 'townhub-add-ons', false, plugin_basename(dirname(ESB_PLUGIN_FILE)) . '/languages' );
    }


    public function after_setup_theme(){
        if(!is_admin() && is_user_logged_in() && in_array( townhub_addons_get_user_role(), townhub_addons_get_option('admin_bar_hide_roles') ) ) {
            show_admin_bar( false );
        }
    }


    public function register_widgets() {
        register_sidebar( array(
            'name'          => esc_html__( 'Author Sidebar', 'townhub-add-ons' ),
            'id'            => 'author-sidebar',
            'description' => esc_html__('For listing author page', 'townhub-add-ons'), 
            'before_widget' => '<div id="%1$s" class="author-sidebar-widget box-widget-item fl-wrap block_box %2$s">', 
            'before_title' => '<div class="box-widget-item-header"><h3 class="widget-title">', 
            'after_title' => '</h3></div>',
            'after_widget' => '</div>',
        ) );

        register_sidebar( array(
            'name'          => esc_html__( 'Dashboard Feed', 'townhub-add-ons' ),
            'id'            => 'dashboard-feed',
            'description' => esc_html__('Appears in dashboard feed.', 'townhub-add-ons'), 
            'before_widget' => '<div id="%1$s" class="dashboard-feed-widget %2$s">', 
            'before_title' => '<h3 class="widget-title widget-title-hide">', 
            'after_title' => '</h3>',
            'after_widget' => '</div>',
        ) );

        register_widget( 'TownHub_About_Author' );
        register_widget( 'TownHub_Recent_Posts' );
        register_widget( 'TownHub_Instagram_Feed' );
        register_widget( 'TownHub_Banner' );
        // register_widget( 'TownHub_Banner_Video' );
        register_widget( 'TownHub_Twitter_Feed' );
    }

    public static function getInstance() {
        if ( ! ( self::$_instance instanceof self ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    private function __clone() {
    }

    private function __wakeup() {
    }

    private function define_constants() {
        $upload_dir = wp_upload_dir( null, false );

        $this->define( 'ESB_ABSPATH', plugin_dir_path( ESB_PLUGIN_FILE ) );
        $this->define( 'ESB_DIR_URL', plugin_dir_url( ESB_PLUGIN_FILE ) );
        $this->define( 'ESB_VERSION', $this->cthversion );
        $this->define( 'ESB_META_PREFIX', '_cth_' );
        $this->define( 'ESB_DEBUG', false );
        $this->define( 'ESB_LOG_FILE', $upload_dir['basedir'] .'/cthdev.log' );


        $this->plugin_url = plugin_dir_url(ESB_PLUGIN_FILE);
        $this->plugin_path = plugin_dir_path(ESB_PLUGIN_FILE);
    }

    private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

    public function is_request( $type ) {
        switch ( $type ) {
            case 'admin':
                return is_admin();
            case 'ajax':
                return defined( 'DOING_AJAX' );
            case 'frontend':
                return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
        }
    }

    private function includes() {
        require_once ESB_ABSPATH . 'includes/core-functions.php';
        require_once ESB_ABSPATH . 'includes/class-templates.php';
        
        require_once ESB_ABSPATH . 'inc/template_tags.php';
        // require_once ESB_ABSPATH . 'inc/vc_shortcodes.php';
        include_once ESB_ABSPATH . 'includes/class-install.php';
        include_once ESB_ABSPATH . 'includes/class-ctb-update.php';
        
        // require_once ESB_ABSPATH . 'includes/class-cookies.php';

        // for listing post type
        require_once ESB_ABSPATH .'includes/class-cpt.php';
        // process date time
        require_once ESB_ABSPATH .'includes/class-date.php';

        // azp
        require_once ESB_ABSPATH . 'shortcodes/azp.php';
        require_once ESB_ABSPATH . 'includes/azp.php';
        // require_once ESB_ABSPATH . 'shortcodes/azp.php';
        require_once ESB_ABSPATH . 'includes/azp_parser.php';
        


        if($this->is_request('admin')){

            // plugin option values
            require_once ESB_ABSPATH . 'includes/option_values.php';
            /* plugin options */
            require_once ESB_ABSPATH . 'includes/class-options.php';

            require_once ESB_ABSPATH . 'includes/class-admin-scripts.php';
            require_once ESB_ABSPATH . 'includes/azp_template.php';
            
            require_once ESB_ABSPATH . 'inc/cmb2/functions.php';

            require_once ESB_ABSPATH . 'includes/class-import.php';

            require_once ESB_ABSPATH . 'includes/class-auth-stats.php';
            
        }

        if($this->is_request('frontend')){
            require_once ESB_ABSPATH . 'includes/class-frontend-scripts.php';
            require_once ESB_ABSPATH . 'includes/class-form-handler.php';
            require_once ESB_ABSPATH . 'includes/class-ajax-handler.php';
            // cart
            require_once ESB_ABSPATH . 'includes/class-cart.php';
            require_once ESB_ABSPATH . 'includes/class-geolocation.php';
            if(townhub_addons_get_option('lazy_load') == 'yes')
                require_once ESB_ABSPATH . 'includes/class-lazy-load.php';
            
            require_once ESB_ABSPATH . 'shortcodes/listing.php';
            // iCal sync
            require_once ESB_ABSPATH . 'includes/class-ical.php';
            // azp
            
        }
        // ical sync
        require_once ESB_ABSPATH .'includes/class-ical-sync.php';
        // membership
        
        require_once ESB_ABSPATH .'includes/class-membership.php';
        require_once ESB_ABSPATH .'includes/class-booking.php';
        require_once ESB_ABSPATH .'includes/class-earning.php';
        require_once ESB_ABSPATH .'includes/class-withdrawals.php';
        //checkout
        require_once ESB_ABSPATH .'includes/class-checkout.php';
        require_once ESB_ABSPATH .'includes/class-checkout-listing.php'; 
         //payment
        require_once ESB_ABSPATH .'includes/class-payment.php';
        require_once ESB_ABSPATH .'includes/class-payment-paypal.php'; 
        if(townhub_addons_get_option('payments_stripe_enable') == 'yes'){
            require_once ESB_ABSPATH .'includes/class-payment-stripe.php';
        }
        require_once ESB_ABSPATH .'includes/class-payment-payfast.php'; 
        require_once ESB_ABSPATH .'includes/class-payment-skrill.php'; 
        if(townhub_addons_get_option('paystack_enable') == 'yes'){
            require_once ESB_ABSPATH .'includes/class-payment-paystack.php'; 
        } 
            

        // dashboard
        // require_once ESB_ABSPATH . 'includes/expire.php';
        require_once ESB_ABSPATH . 'includes/woo.php';
        require_once ESB_ABSPATH . 'includes/vendors/dokan.php';
        require_once ESB_ABSPATH . 'includes/vendors/wpml.php';

        // for chat
        require_once ESB_ABSPATH . 'posttypes/townhub-chat.php';
        require_once ESB_ABSPATH . 'includes/class-ads.php';

        // require_once ESB_ABSPATH . 'includes/dashboard/helpers.php';
        require_once ESB_ABSPATH . 'includes/class-dashboard.php';
        require_once ESB_ABSPATH . 'includes/dashboard/listings.php';

        require_once ESB_ABSPATH .'inc/rating.php';
        /**
         * Custom login/register page
         */
        // if( townhub_addons_get_option('disable_custom_logreg') != 'yes' ){
            require_once ESB_ABSPATH . 'includes/class-user.php';
        // }
        
        /**
         * Implement Post views
         *
         * @since TownHub 1.0
         */
        // require_once ESB_ABSPATH . 'inc/post_views.php';
        require_once ESB_ABSPATH . 'includes/class-lstats.php';
        /**
         * Implement Like Post
         *
         * @since TownHub 1.0
         */
        
        require_once ESB_ABSPATH . 'inc/post_like.php';
        require_once ESB_ABSPATH . 'inc/elementor.php';
        /**
         * Implement Ajax requests
         *
         * @since TownHub 1.0
         */
        require_once ESB_ABSPATH . 'inc/ajax.php';

        //widgets
        require_once ESB_ABSPATH .'widgets/shortcodes.php';
        require_once ESB_ABSPATH .'widgets/townhub_recent_posts.php';
        require_once ESB_ABSPATH .'widgets/townhub_about_author.php';
        require_once ESB_ABSPATH .'widgets/townhub_banner.php';
        // require_once ESB_ABSPATH .'widgets/townhub_banner_video.php';
        require_once ESB_ABSPATH .'widgets/townhub_instagram_feed.php';
        require_once ESB_ABSPATH .'widgets/townhub_twitter_feed.php';
        // require_once ESB_ABSPATH .'widgets/townhub_partners.php';
        //  require_once ESB_ABSPATH .'widgets/townhub_languages.php';
    }

    public function init() {

        do_action( 'cth_addons_init_before' );
        // if(!is_admin() && is_user_logged_in() &&  in_array( townhub_addons_get_user_role(), townhub_addons_get_option('admin_bar_hide_roles') ) ) {
        //     show_admin_bar( false );
        // }


        // $this->set_cookie_currency();

        // $this->options = new Esb_Class_Options();

        if ( $this->is_request( 'frontend' ) ) {
            foreach (townhub_addons_payment_names('', true) as $key => $title) {
                $pm_class = 'Esb_Class_Payment_'. ucfirst($key); // form -> Form
                if(class_exists($pm_class)){
                    $this->payment_methods[$key] = $pm_class::getInstance(); // new $pm_class;
                    // echo get_class( $this->payment_methods[$key] ) ."\n";
                    // error_log(date('[Y-m-d H:i e] '). get_class( $this->payment_methods[$key] ) . PHP_EOL, 3, ESB_LOG_FILE);
                }

                // $pm_class::getInstance(); // As of PHP 5.3.0
            }
            $this->cart = new Esb_Class_Cart();
            $this->geo = new Esb_Class_Geolocation();
        }

        do_action( 'cth_addons_init_after' );

    }

    public function init_after(){
        // https://codex.wordpress.org/Function_Reference/flush_rewrite_rules
        // do not use on live/production servers
        $ver = filemtime( __FILE__ ); // Get the file time for this file as the version number
        $defaults = array( 'version' => 0, 'time' => date_i18n('U') );
        $r = wp_parse_args( get_option( __CLASS__ . '_flush', array() ), $defaults );

        if ( $r['version'] != $ver || $r['time'] + 172800 < date_i18n('U') ) { // Flush if ver changes or if 48hrs has passed.
            flush_rewrite_rules();
            // trace( 'flushed' );
            $args = array( 'version' => $ver, 'time' => date_i18n('U') );
            if ( ! update_option( __CLASS__ . '_flush', $args ) ) add_option( __CLASS__ . '_flush', $args );
        }
    }

    public function init_scheduler(){
        if ( function_exists('as_next_scheduled_action') && function_exists('as_schedule_recurring_action')  ) {
            if( false === as_next_scheduled_action( 'townhub_expire_scheduler_action' ) ){
                if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). "init_scheduler --> as_schedule_recurring_action" . PHP_EOL, 3, ESB_LOG_FILE);
                as_schedule_recurring_action( strtotime( 'midnight tonight' ), DAY_IN_SECONDS, 'townhub_expire_scheduler_action' );
            }

            // error_log(as_next_scheduled_action( 'townhub_ical_sync_scheduler_action' ));
            
            if( townhub_addons_get_option('ical_sync_enable') == 'yes' && false === as_next_scheduled_action( 'townhub_ical_sync_scheduler_action' ) ){
                // error_log(date('[Y-m-d H:i e] '). "init_scheduler --> townhub_ical_sync_scheduler_action" . PHP_EOL);
                // as_schedule_single_action( strtotime('now +30 minutes'), 'townhub_ical_sync_scheduler_action' );
                // as_schedule_single_action( strtotime('now') + ( MINUTE_IN_SECONDS * townhub_addons_get_option('ical_sync_interval','1440') ), 'townhub_ical_sync_scheduler_action' );
                as_schedule_recurring_action( strtotime( 'now' ), MINUTE_IN_SECONDS * townhub_addons_get_option('ical_sync_interval','1440'), 'townhub_ical_sync_scheduler_action' );
            }
            if( townhub_addons_get_option('ical_sync_enable') != 'yes' && as_next_scheduled_action( 'townhub_ical_sync_scheduler_action' ) ){
                as_unschedule_action('townhub_ical_sync_scheduler_action');
            }
            // as_unschedule_action
        }


        
    }

    public function do_ical_sync_scheduler(){
        // error_log(date('[Y-m-d H:i e] '). "do_ical_sync_scheduler" . PHP_EOL);
        
        $query_args = array(
            'post_type'         => array('listing','lrooms'),
            'posts_per_page'    => -1,
            'post_status'       => 'publish',
            'fields'            => 'ids',
            'meta_key'          => ESB_META_PREFIX.'ical_url',
            'meta_value'        => '',
            'meta_compare'      => '!=',
            // 'meta_query' => array(
            //     // 'relation' => 'AND',
            //     array(
            //         'key'     => ESB_META_PREFIX.'ical_url',
            //         'value'   => '',
            //         'compare' => '!=',
            //         // 'type'    => 'CHAR'
            //     ),
            //     // array(
            //     //     'key'     => ESB_META_PREFIX.'end_date',
            //     //     'value'   => current_time( 'mysql' ),
            //     //     'compare' => '<',
            //     //     'type'    => 'DATETIME'
            //     // )
            // ),
            
        );
        $syncPosts = get_posts( $query_args );

        foreach ($syncPosts as $sID) {
            # code...
            // error_log($sID);

            $ical = new Esb_Class_iCal_Sync($sID);
            $ical->sync_import();
        }

        
    }

    public function do_expire_scheduler(){
        if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). "do_expire_scheduler" . PHP_EOL, 3, ESB_LOG_FILE);
        // will expire message based author subscription post
        $next_5_days = Esb_Class_Date::modify( 'now', 5, 'Y-m-d H:i:s' );

        $query_args = array(
            'post_type'         => 'lorder',
            'posts_per_page'    => -1,
            'post_status'       => 'publish',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key'     => ESB_META_PREFIX.'status',
                    'value'   => 'completed',
                    'compare' => '=',
                    // 'type'    => 'CHAR'
                ),
                array(
                    'key'     => ESB_META_PREFIX.'end_date',
                    'value'   => current_time( 'mysql' ),
                    'compare' => '>=',
                    'type'    => 'DATETIME'
                ),
                array(
                    'key'     => ESB_META_PREFIX.'end_date',
                    'value'   => $next_5_days,
                    'compare' => '<=',
                    'type'    => 'DATETIME'
                ),
                array(
                    'key'     => ESB_META_PREFIX.'end_date',
                    'value'   => Esb_Class_Date::modify( 'now', 4, 'Y-m-d H:i:s' ),
                    'compare' => '>',
                    'type'    => 'DATETIME'
                )
            ),
            'orderby' => 'date',
            'order' => 'DESC'
        );
        $expired_authors = array();
        $expired_posts = array();
        $expired_subs = get_posts( $query_args );
        if(!empty($expired_subs)){
            foreach ($expired_subs as $exsub) {
                $expired_authors[] = get_post_meta( $exsub->ID, ESB_META_PREFIX.'user_id', true ); // $exsub->post_author;
                $expired_posts[] = $exsub->ID;
            }
            // $expired_authors = array_unique($expired_authors);
        }

        if(!empty($expired_authors)){
            foreach ($expired_authors as $key => $auth) {
                do_action( 'esb_addons_subscription_will_expire', $auth, $expired_posts[$key] );
                Esb_Class_Dashboard::add_notification( $auth, array(
                    'type'          => 'membership_will_expired',
                    'entity_id'     => $expired_posts[$key]
                ) );
            }
        }

        


        // ad will expire
        $query_args = array(
            'post_type'         => 'cthads',
            'posts_per_page'    => -1,
            'post_status'       => 'publish',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key'     => ESB_META_PREFIX.'status',
                    'value'   => 'completed',
                    'compare' => '=',
                    // 'type'    => 'CHAR'
                ),
                array(
                    'key'     => ESB_META_PREFIX.'end_date',
                    'value'   => current_time( 'mysql' ),
                    'compare' => '>=',
                    'type'    => 'DATETIME'
                ),
                array(
                    'key'     => ESB_META_PREFIX.'end_date',
                    'value'   => $next_5_days,
                    'compare' => '<=',
                    'type'    => 'DATETIME'
                ),
                array(
                    'key'     => ESB_META_PREFIX.'end_date',
                    'value'   => Esb_Class_Date::modify( 'now', 4, 'Y-m-d H:i:s' ),
                    'compare' => '>',
                    'type'    => 'DATETIME'
                )
            ),
            'orderby' => 'date',
            'order' => 'DESC'
        );
        $expired_authors = array();
        $expired_posts = array();
        $expired_subs = get_posts( $query_args );
        if(!empty($expired_subs)){
            foreach ($expired_subs as $exsub) {
                $expired_authors[] = get_post_meta( $exsub->ID, ESB_META_PREFIX.'user_id', true ); // $exsub->post_author;
                $expired_posts[] = $exsub->ID;
            }
            // $expired_authors = array_unique($expired_authors);
        }

        if(!empty($expired_authors)){
            foreach ($expired_authors as $key => $auth) {
                do_action( 'esb_addons_ad_will_expire', $auth, $expired_posts[$key] );
                Esb_Class_Dashboard::add_notification( $auth, array(
                    'type'          => 'ad_will_expired',
                    'entity_id'     => $expired_posts[$key]
                ) );
            }
        }


        // expired subscription

        $query_args = array(
            'post_type'         => 'lorder',
            'posts_per_page'    => -1,
            'post_status'       => 'publish',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key'     => ESB_META_PREFIX.'status',
                    'value'   => 'completed',
                    'compare' => '=',
                    // 'type'    => 'CHAR'
                ),
                array(
                    'key'     => ESB_META_PREFIX.'end_date',
                    'value'   => current_time( 'mysql' ),
                    'compare' => '<',
                    'type'    => 'DATETIME'
                )
            ),
            'orderby' => 'date',
            'order' => 'DESC'
        );
        $expired_authors = array();
        $expired_posts = array();
        $expired_subs = get_posts( $query_args );
        if(!empty($expired_subs)){
            foreach ($expired_subs as $exsub) {
                $expired_authors[] = get_post_meta( $exsub->ID, ESB_META_PREFIX.'user_id', true ); // $exsub->post_author;
                $expired_posts[] = $exsub->ID;
            }
            // $expired_authors = array_unique($expired_authors);
        }

        if(!empty($expired_authors)){
            foreach ($expired_authors as $key => $auth) {
                $subs_post = $expired_posts[$key];
                // do not unsubscribe for admin
                if(townhub_addons_get_user_role($auth) != 'administrator'){
                    Esb_Class_Membership::deactive_membership($subs_post);
                }

                do_action( 'esb_addons_subscription_expired', $auth, $subs_post );
                Esb_Class_Dashboard::add_notification( $auth, array(
                    'type'          => 'membership_expired',
                    'entity_id'     => $subs_post
                ) );
            }
        }

        if( townhub_addons_get_option('membership_package_expired_hide') == 'yes' ){

            // update author listings status
            $expired_listings = get_posts( 
                array(
                    'post_type'         => 'listing',
                    'posts_per_page'    => -1,
                    'post_status'       => 'publish',
                    'fields'            => 'ids',
                    'meta_query' => array(
                        'relation' => 'AND',
                        array(
                            'key'     => ESB_META_PREFIX.'expire_date',
                            'compare' => '!=',
                            'value'   => '',
                        ),
                        array(
                            'key'     => ESB_META_PREFIX.'expire_date',
                            'compare' => '!=',
                            'value'   => 'NEVER',
                        ),
                        array(
                            'key'     => ESB_META_PREFIX.'expire_date',
                            'value'   => current_time( 'mysql' ),
                            'compare' => '<',
                            'type'    => 'DATETIME'
                        )
                    ),
                    'orderby' => 'date',
                    'order' => 'DESC'
                ) 
            );

            if(!empty($expired_listings)){
                
                foreach ($expired_listings as $exlist) {
                    wp_update_post( array('ID' => $exlist, 'post_status' => 'pending') );
                    do_action( 'esb_addons_listing_expired', $exlist );
                    // $expired_authors[] = get_post_meta( $exsub->ID, ESB_META_PREFIX.'user_id', true ); // $exsub->post_author;
                    // $expired_posts[] = $exsub->ID;
                }
                // $expired_authors = array_unique($expired_authors);
            }

        }

            


    }

    public function filter_media_frontend( $query ) {
        // admins get to see everything
        if ( ! current_user_can( 'manage_options' ) ) $query['author'] = get_current_user_id();
        return $query;
    }

    public function set_cookie_currency(){
        if(!isset($_REQUEST['currency']) || $_REQUEST['currency'] == '' || (isset($_COOKIE['esb_currency']) && $_COOKIE['esb_currency'] == $_REQUEST['currency'])) return;

        esb_setcookie( 'esb_currency', $_REQUEST['currency'], date_i18n('U') + MONTH_IN_SECONDS );

        $_COOKIE['esb_currency'] = $_REQUEST['currency'];
    }
    //notice mobile app is available
    public  function townhub_mobile_app_available(){
        echo '<div class="notice notice-error is-dismissible">
            <p>TownHub react-native <strong>Mobile Apps</strong> is now available. <a href="https://cththemes.com/" target="_blank">Get it</a></p> 
        </div>';
        if( version_compare(get_option( 'townhub-addons-version' ), '2.0.2', '<') ){
            echo '<div class="notice notice-error is-dismissible">
                <p>We\'ve released new version of TownHub Add-Ons plugin with new great features. But the new version introduces some radical changes to the plugin.
                <br />So please make sure backup your data first just in case anything happens and <a class="warning-link" href="https://docs.cththemes.com/docs/installation/changes-log/version-2-0-0/">read carefully update details instructions.</a>
                <br \><a class="cth-update-btn" href="'.add_query_arg( array(
        'ctb_update' => 1,
    ), admin_url() ).'">Update now</a></p> 
            </div>';
        }

    }
    public function townhub_addons_body_classes($classes) {
        $classes[] = 'townhub-has-addons';
        if(is_singular( 'listing' )) $classes[] = 'listing-type-id-'.get_post_meta( get_the_ID(), ESB_META_PREFIX.'listing_type_id', true );
        return $classes;
    }

}