<?php
/* banner-php */
namespace CTHthemesAutoUpdate;

/**
 *
 */
class Api
{
    private static $_instance = null;

    private $api_url ;

    public $token;

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    private function __construct()
    {
        $this->init_globals();
    }
    public function __clone()
    {
        _doing_it_wrong(__FUNCTION__, esc_html__('Cheatin&#8217; huh?', 'townhub'), '1.0.0');
    }
    public function __wakeup()
    {
        _doing_it_wrong(__FUNCTION__, esc_html__('Cheatin&#8217; huh?', 'townhub'), '1.0.0');
    }

    private function init_globals()
    {
        $this->api_url = 'http://update.cththemes.net/wp-json/cthupdate/v1';
        // Envato API token.
        $this->token = cththemes_auto_update()->get_envato_purchase_code_option_value();
    }

    public function request($url, $args = array())
    {
        $defaults = array(
            // Bearer or Basic
            'headers' => array(
                'Authorization' => 'Basic ' . $this->token,
                'User-Agent'    => 'CTHthemes - WP Update ' . cththemes_auto_update()->get_version(),
            ),
            'timeout' => 14,
        );
        $args = wp_parse_args($args, $defaults);

        $token = trim(str_replace('Basic', '', $args['headers']['Authorization']));
        if (empty($token)) {
            return new \WP_Error('api_token_error', __('An API token is required.', 'townhub'));
        }

        // Make an API request.
        $response = wp_remote_get(esc_url_raw($url), $args);
        // error example
        // string(130) "{"headers":{"Authorization":"Basic purchase-code","User-Agent":"CTHthemes - WP Update 1.0.0"},"timeout":14}" string(115) "{"errors":{"http_request_failed":["cURL error 28: Connection timed out after 10000 milliseconds"]},"error_data":[]}" string(130) "{"headers":{"Authorization":"Basic purchase-code","User-Agent":"CTHthemes - WP Update 1.0.0"},"timeout":14}" string(115) "{"errors":{"http_request_failed":["cURL error 28: Connection timed out after 10000 milliseconds"]},"error_data":[]}" array(4) { ["purchased"]=> array(0) { } ["active"]=> array(0) { } ["installed"]=> array(0) { } ["install"]=> array(0) { } } array(0) { } string(130) "{"headers":{"Authorization":"Basic purchase-code","User-Agent":"CTHthemes - WP Update 1.0.0"},"timeout":14}" string(115) "{"errors":{"http_request_failed":["cURL error 28: Connection timed out after 10000 milliseconds"]},"error_data":[]}" array(4) { ["purchased"]=> array(0) { } ["active"]=> array(0) { } ["installed"]=> array(0) { } ["install"]=> array(0) { } } array(0) { } string(130) "{"headers":{"Authorization":"Basic purchase-code","User-Agent":"CTHthemes - WP Update 1.0.0"},"timeout":14}" string(115) "{"errors":{"http_request_failed":["cURL error 28: Connection timed out after 10000 milliseconds"]},"error_data":[]}" array(4) { ["purchased"]=> array(0) { } ["active"]=> array(0) { } ["installed"]=> array(0) { } ["install"]=> array(0) { } } array(0) { } string(130) "{"headers":{"Authorization":"Basic purchase-code","User-Agent":"CTHthemes - WP Update 1.0.0"},"timeout":14}" string(115) "{"errors":{"http_request_failed":["cURL error 28: Connection timed out after 10001 milliseconds"]},"error_data":[]}" 

        // Check the response code.
        $response_code    = wp_remote_retrieve_response_code($response);
        $response_message = wp_remote_retrieve_response_message($response);

        if (!empty($response->errors) && isset($response->errors['http_request_failed'])) {
            // API connectivity issue, inject notice into transient with more details.
            $option = cththemes_auto_update()->get_options();
            if (empty($option['notices'])) {
                $option['notices'] = [];
            }
            $option['notices']['http_error'] = current($response->errors['http_request_failed']);
            cththemes_auto_update()->set_options($option);
            return new \WP_Error('http_error', esc_html(current($response->errors['http_request_failed'])));
        }

        if (200 !== $response_code && !empty($response_message)) {
            return new \WP_Error($response_code, $response_message);
        } elseif (200 !== $response_code) {
            return new \WP_Error($response_code, __('An unknown API error occurred.', 'townhub'));
        } else {
            $return = json_decode(wp_remote_retrieve_body($response), true);
            if (null === $return) {
                return new \WP_Error('api_error', __('An unknown API error occurred.', 'townhub'));
            }
            return $return;
        }
    }

    /**
     * Deferred item download URL.
     *
     *
     * @param int $id The item ID.
     * @return string.
     */
    public function deferred_download( $id, $type = 'update_theme' ) {
        if ( empty( $id ) ) {
            return '';
        }

        $args = array(
            'cth_defer_download' => true,
            'item_id'           => $id,
            'type'              => $type
        );
        return add_query_arg( $args, esc_url( cththemes_auto_update()->get_page_url() ) );
    }

    /**
     * Get the item download.
     *
     *
     * @param  int   $id The item ID.
     * @param  int   $type Download type. Must be "update_theme" "update_plugin" "install_plugin", "demo_data_xml"
     * @param  array $args The arguments passed to `wp_remote_get`.
     * @return bool|array The HTTP response.
     */
    public function download( $id, $type = 'update_theme', $args = array() ) {
        if ( empty( $id ) ) {
            return false;
        }

        $url      = $this->api_url . '/download/' . $this->add_oauth() .'/' . $id . '/' . $type;
        $response = $this->request( $url, $args );

        // @todo Find out which errors could be returned & handle them in the UI.
        if ( is_wp_error( $response ) || empty( $response ) || ! empty( $response['error'] ) ) {
            return false;
        }

        if ( ! empty( $response['download_link'] ) ) {
            
            return $response['download_link'];
        }

        // Missing a WordPress theme and plugin, report an error.
        $option = cththemes_auto_update()->get_options();
        if ( ! isset( $option['notices'] ) ) {
            $option['notices'] = [];
        }
        $option['notices']['missing-package-zip'] = true;
        cththemes_auto_update()->set_options( $option );

        return false;
    }


    /**
     * Get the list of available themes.
     *
     *
     * @param  array $args The arguments passed to `wp_remote_get`.
     * @return array The HTTP response.
     */
    public function themes( $args = array() ) {
        $themes = array();

        $url      = $this->api_url . '/themes/' . $this->add_oauth() ;
        $response = $this->request( $url, $args );

        if ( is_wp_error( $response ) || empty( $response ) || empty( $response['results'] ) ) {
            return $themes;
        }

        foreach ( $response['results'] as $theme ) {
            $themes[] = $this->normalize_theme( $theme );
        }

        return $themes;
    }

    /**
     * Normalize a theme.
     *
     *
     * @param  array $theme An array of API request values.
     * @return array A normalized array of values.
     */
    public function normalize_theme( $theme ) {
        $normalized_theme = array(
            'id'            => $theme['id'],
            'name'          => ( ! empty( $theme['name'] ) ? $theme['name'] : '' ),
            'author'        => ( ! empty( $theme['author'] ) ? $theme['author'] : '' ),
            'version'       => ( ! empty( $theme['version'] ) ? $theme['version'] : '' ),
            'description'   => self::remove_non_unicode( strip_tags( $theme['description'] ) ),
            'url'           => ( ! empty( $theme['url'] ) ? $theme['url'] : '' ),
            'author_url'    => ( ! empty( $theme['author_url'] ) ? $theme['author_url'] : '' ),

            'number_of_downloads'          => ( ! empty( $plugin['number_of_downloads'] ) ? $plugin['number_of_downloads'] : 0 ),
            'landscape_url'          => ( ! empty( $plugin['landscape_url'] ) ? $plugin['landscape_url'] : '' ),
            'thumbnail_url'          => ( ! empty( $plugin['thumbnail_url'] ) ? $plugin['thumbnail_url'] : '' ),
            
        );

        return $normalized_theme;
    }

    /**
     * Get the list of available plugins.
     *
     *
     * @param  array $args The arguments passed to `wp_remote_get`.
     * @return array The HTTP response.
     */
    public function plugins( $args = array() ) {
        $plugins = array();

        $url      = $this->api_url . '/plugins/' . $this->add_oauth() ;
        $response = $this->request( $url, $args );

        if ( is_wp_error( $response ) || empty( $response ) || empty( $response['results'] ) ) {
            return $plugins;
        }

        foreach ( $response['results'] as $plugin ) {
            $plugins[] = $this->normalize_plugin( $plugin );
        }

        return $plugins;
    }


    /**
     * Normalize a plugin.
     *
     *
     * @param  array $plugin An array of API request values.
     * @return array A normalized array of values.
     */
    public function normalize_plugin( $plugin ) {

        $plugin_normalized = array(
            'id'              => $plugin['id'],
            'name'            => ( ! empty( $plugin['name'] ) ? $plugin['name'] : '' ),
            'author'          => ( ! empty( $plugin['author'] ) ? $plugin['author'] : '' ),
            'version'         => ( ! empty( $plugin['version'] ) ? $plugin['version'] : '' ),
            // 'description'     => self::remove_non_unicode( strip_tags( $plugin['description'] ) ),
            'description'     => self::remove_non_unicode( $plugin['description'] ),
            'url'             => ( ! empty( $plugin['url'] ) ? $plugin['url'] : '' ),
            'author_url'      => ( ! empty( $plugin['author_url'] ) ? $plugin['author_url'] : '' ),
            'requires'        => ( ! empty( $plugin['requires'] ) ? $plugin['requires'] : '' ),
            'tested'          => ( ! empty( $plugin['tested'] ) ? $plugin['tested'] : '' ),
            
            'last_updated'      => ( ! empty( $plugin['last_updated'] ) ? $plugin['last_updated'] : '' ),
            'number_of_downloads'          => ( ! empty( $plugin['number_of_downloads'] ) ? $plugin['number_of_downloads'] : 0 ),
            'landscape_url'          => ( ! empty( $plugin['landscape_url'] ) ? $plugin['landscape_url'] : '' ),
            'thumbnail_url'          => ( ! empty( $plugin['thumbnail_url'] ) ? $plugin['thumbnail_url'] : '' ),
            'rating'          => ( ! empty( $plugin['rating'] ) ? $plugin['rating'] : '' ),
            'changelogs'          => ( ! empty( $plugin['changelogs'] ) ? $plugin['changelogs'] : '' ),


            'plugin_class_check'          => ( ! empty( $plugin['plugin_class_check'] ) ? $plugin['plugin_class_check'] : '' ),
            'plugin_func_check'          => ( ! empty( $plugin['plugin_func_check'] ) ? $plugin['plugin_func_check'] : '' ),
        );


        
        return $plugin_normalized;
    }


    private function client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    private function client_domain() {
        $domain = '';
        if (getenv('HTTP_HOST'))
            $domain = getenv('HTTP_HOST');
        else if(getenv('SERVER_NAME'))
            $domain = getenv('SERVER_NAME');
        else
            $domain = 'UNKNOWN';
        return $domain;
    }

    private function add_oauth(){
        $parts = array(
            $this->client_ip(),
            $this->client_domain(),
            cththemes_auto_update()->get_item_id()
        );

        return rawurldecode(implode("||", $parts));
    }

    /**
     * Remove all non unicode characters in a string
     *
     *
     * @param string $retval The string to fix.
     * @return string
     */
    static private function remove_non_unicode( $retval ) {
        return preg_replace( '/[\x00-\x1F\x80-\xFF]/', '', $retval );
    }


}
