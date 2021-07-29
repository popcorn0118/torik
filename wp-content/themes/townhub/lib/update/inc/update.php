<?php
/* banner-php */
namespace CTHthemesAutoUpdate;

/**
 * 
 */
class Update
{
    private static $_instance = null;

    private $data;

    private $slug;

    private $option_name;

    private $version;

    private $plugin_url;

    private $plugin_path;

    private $page_url;

    private $item_id = 25019571 ; // '21694727'

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    function __construct(){
        $this->init_globals();
        $this->init_includes();
        $this->init_actions();
    }

    /**
     * You cannot clone this class.
     *
     * @codeCoverageIgnore
     */
    public function __clone() {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'townhub' ), '1.0.0' );
    }

    /**
     * You cannot unserialize instances of this class.
     *
     * @codeCoverageIgnore
     */
    public function __wakeup() {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'townhub' ), '1.0.0' );
    }

    private function init_globals() {
        $this->data        = new \stdClass();
        $this->version     = '1.0.0';
        $this->slug        = 'cththemes-auto-update';
        $this->option_name = self::sanitize_key( $this->slug );
        $this->plugin_url  = trailingslashit( get_parent_theme_file_uri('lib') );
        $this->plugin_path = CTHTHEMES_AUTO_UPDATE_PATH;
        $this->page_url    = admin_url( 'admin.php?page=' . $this->slug );
        $this->data->admin = true;

    }

    private function init_includes() {
        require $this->plugin_path . 'inc/admin/admin.php';
        require $this->plugin_path . 'inc/api.php';
        require $this->plugin_path . 'inc/items.php';
    }

    private function init_actions() {
        // Load OAuth.
        add_action( 'init', array( $this, 'admin' ) );

        // Load Upgrader.
        add_action( 'init', array( $this, 'items' ) );
        add_action( 'init', array( $this, 'api' ) );
    }

    public function get_plugin_path(){
        return $this->plugin_path;
    }

    public function get_plugin_url() {
        return $this->plugin_url;
    }

    public function get_option_name() {
        return $this->option_name;
    }

    public function get_envato_purchase_code_option_name(){
        return "envato_purchase_code_$this->item_id";
    }
    public function get_envato_purchase_code_option_value(){
        return get_option( $this->get_envato_purchase_code_option_name() );
    }

    public function get_version() {
        return $this->version;
    }
    public function get_slug() {
        return $this->slug;
    }
    public function get_item_id() {
        return $this->item_id;
    }

    /**
     * Return the plugin page URL.
     *
     *
     * @return string
     */
    public function get_page_url() {
        return $this->page_url;
    }

    public function admin() {
        return Admin::instance();
    }

    public function set_option( $name, $option ) {
        $options          = self::get_options();
        $name             = self::sanitize_key( $name );
        $options[ $name ] = esc_html( $option );
        $this->set_options( $options );
    }

    public function set_options( $options ) {
        update_option( $this->option_name, $options );
    }

    public function get_options() {
        return get_option( $this->option_name, array() );
    }

    public function get_option( $name, $default = '' ) {
        $options = self::get_options();
        $name    = self::sanitize_key( $name );
        return isset( $options[ $name ] ) ? $options[ $name ] : $default;
    }

    public function items(){
        return Items::instance();
    }

    public function api(){
        return Api::instance();
    }

    private function sanitize_key( $key ) {
        return preg_replace( '/[^A-Za-z0-9\_]/i', '', str_replace( array( '-', ':' ), '_', $key ) );
    }


}