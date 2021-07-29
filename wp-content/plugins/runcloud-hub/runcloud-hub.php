<?php
/**
 * @wordpress-plugin
 * Plugin Name:     RunCloud Hub
 * Description:     RunCloud server-side caching for WordPress, Nginx FastCGI/Proxy Cache and Redis Object Cache
 * Author:          RunCloud
 * Author URI:      https://runcloud.io/
 * Version:         1.1.2
 * License:         GPL-2.0 or later
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:     runcloud-hub
 * Domain Path:     /languages
 */

/*
Copyright 2020 RunCloud.io
All rights reserved.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version
2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
with this program. If not, visit: https://www.gnu.org/licenses/
 */

/* 
More informations:

RunCloud Hub
https://blog.runcloud.io/runcloud-hub/

RunCache - Nginx FastCGI/Proxy Cache
https://blog.runcloud.io/nginx-fastcgi-cache/

Redis Object Cache
https://blog.runcloud.io/redis-object-cache/


*/

if (!defined('WPINC') || defined('RUNCLOUD_HUB_HOOK')) {
    exit;
}

/**
 * constant
 */
define('RUNCLOUD_HUB_FILE', __FILE__);
define('RUNCLOUD_HUB_HOOK', plugin_basename(RUNCLOUD_HUB_FILE));
define('RUNCLOUD_HUB_PATH', realpath(plugin_dir_path(RUNCLOUD_HUB_FILE)) . '/');
define('RUNCLOUD_HUB_PATH_LANG', RUNCLOUD_HUB_PATH . 'languages/');
define('RUNCLOUD_HUB_PATH_VIEW', RUNCLOUD_HUB_PATH . 'partials/');
define('RUNCLOUD_HUB_PATH_RSC', RUNCLOUD_HUB_PATH . 'assets/');
define('RUNCLOUD_HUB_PATH_LIB', RUNCLOUD_HUB_PATH . 'library/');

final class RunCloud_Hub
{
    // reference
    private static $name       = 'RunCloud Hub';
    private static $slug       = 'runcloud-hub';
    private static $islug      = 'runcloud';

    private static $db_version = 'runcloud_hub_version';
    private static $db_setting = 'runcloud_hub_config';
    private static $db_stats   = 'runcloud_hub_stats';
    private static $db_type    = 'runcloud_hub_type';
    private static $db_notice  = 'runcloud_hub_notice';
    private static $db_preload = 'runcloud_hub_preload';
    private static $db_network = 'runcloud_hub_network';

    // version
    private static $version      = '1.1.2';
    private static $version_prev = '1.1.1';

    // later
    private static $hook       = '';
    private static $cache_path = '';
    private static $ordfirst;
    private static $ordlast;

    // url
    private static $plugin_url        = '';
    private static $plugin_url_assets = '';

    // view
    private static $checked = [];
    private static $value   = [];

    // is cond
    private static $is_purge_cache_home    = false;
    private static $is_purge_cache_content = false;
    private static $is_purge_cache_archive = false;
    private static $is_purge_cache_urlpath = false;
    private static $is_run_preload         = false;
    private static $is_redis_debug         = false;
    private static $is_html_footprint      = false;

    // purge prefix
    private static $purge_prefix_var = 'runcache-purge';
    private static $purge_prefix_all = 'runcache-purgeall';

    // done purge 
    private static $done_purgeall     = false;
    private static $done_purgesiteall = false;

    // misc
    private static $dropin_file   = 'object-cache.php';
    private static $dropin_source = 'wp-object-cache.php';
    private static $dropin_config = 'rcwp-redis-config.php';
    private static $transientk    = 'runcloudhub';
    private static $magictoken    = '__rcmagiclink';
    private static $optpage       = false;
    private static $onfirehooks   = false;

    // api
    private static $endpoint = 'https://manage.runcloud.io/api/runcloud-hub';

    // update
    private static $trunk = 'https://manage.runcloud.io/resources/runcloud-hub/latest_version';

    /**
     * is_wp_ssl.
     */
    private static function is_wp_ssl()
    {
        $scheme = parse_url(get_site_url(), PHP_URL_SCHEME);
        return ('https' === $scheme ? true : false);
    }

    /**
     * is_wp_cli.
     */
    private static function is_wp_cli()
    {
        return (defined('WP_CLI') && WP_CLI);
    }

    /**
     * is_defined_halt.
     */
    private static function is_defined_halt()
    {
        return defined('RUNCLOUD_HUB_HALT');
    }

    /**
     * define_halt.
     */
    private static function define_halt()
    {
        if (!self::is_defined_halt() && !self::is_wp_cli()) {
            define('RUNCLOUD_HUB_HALT', true);
        }
    }

    /**
     * is_apache.
     */
    private static function is_apache()
    {
        if (false !== strpos($_SERVER['SERVER_SOFTWARE'], 'Apache')) {
            return true;
        }

        if ('apache2handler' === php_sapi_name()) {
            return true;
        }

        return false;
    }

    /**
     * is_nginx.
     */
    private static function is_nginx()
    {
        if (isset($GLOBALS['is_nginx']) && (bool) $GLOBALS['is_nginx']) {
            return true;
        }

        if (false !== strpos($_SERVER['SERVER_SOFTWARE'], 'nginx')) {
            return true;
        }

        return false;
    }

    /**
     * is_srcache.
     */
    private static function is_srcache()
    {
        if (is_multisite()) {
            $blog_id = self::get_main_site_id();
            switch_to_blog($blog_id);
        }

        $type = get_option(self::$db_type);

        if (is_multisite()) {
            restore_current_blog();
        }

        return ( $type === 'sr' || $type === 'srcache' ? true : false );
    }

    /**
     * switch_cache_type.
     */
    private static function switch_cache_type( $status = false )
    {
        if ( self::is_srcache() ) {
            $updated = self::update_cache_type('fastcgi');
            $debug_message = sprintf( esc_html__('Switching cache purger type from %s to %s', 'runcloud-hub'), 'sr', 'fastcgi' );
        }
        else {
            $updated = self::update_cache_type('sr');
            $debug_message = sprintf( esc_html__('Switching cache purger type from %s to %s', 'runcloud-hub'), 'fastcgi', 'sr' );
        }
        self::debug(__METHOD__, $debug_message);

        if ( $updated ) {
            $req_status = [
                'code' => 200,
                'status' => esc_html__('Switching cache purger type was successful.', 'runcloud-hub'),
            ];
        }
        else {
            $req_status = [
                'code' => 0,
                'status' => esc_html__('Switching cache purger type was failed.', 'runcloud-hub'),
            ];
        }
        return $req_status;
    }

    /**
     * update_cache_type.
     */
    public static function update_cache_type($type, &$message = '') {
        $cache_type = get_option(self::$db_type);
        if ( $cache_type === $type ) {
            $message = sprintf( esc_html__('Updating the cache type is not needed. Cache type: %s', 'runcloud-hub'), $type );
            self::debug(__METHOD__, $message);
            return true;
        }

        $updated = update_option(self::$db_type, $type);
        if ($updated) {
            $message = sprintf( esc_html__('Updating the cache type was successful. Cache type: %s', 'runcloud-hub'), $type );
        }
        else {
            $message = esc_html__('Updating the cache type was failed.', 'runcloud-hub');
        }
        self::debug(__METHOD__, $message);

        return $updated;
    }

    /**
     * is_ajax.
     */
    private static function is_ajax() 
    {
        if ( wp_doing_ajax() ) {
            return true;
        }

        if ( ! empty( $_SERVER[ 'HTTP_X_REQUESTED_WITH' ] ) && 'xmlhttprequest' === strtolower( $_SERVER[ 'HTTP_X_REQUESTED_WITH' ]) ) {
            return true;
        }

        return false;
    }

    /**
     * is_fl_builder.
     */
    private static function is_fl_builder() 
    {
        if ( isset($_POST) && isset($_POST['fl_builder']) ) {
            return true;
        }

        if ( isset($_GET) && isset($_GET['fl_builder']) ) {
            return true;
        }

        return false;
    }

    /**
     * fastcgi_close.
     */
    private static function fastcgi_close()
    {
        if ((php_sapi_name() === 'fpm-fcgi')
            && function_exists('fastcgi_finish_request')) {
            @session_write_close();
            @fastcgi_finish_request();
        }
    }

    /**
     * close_exit.
     */
    private static function close_exit($content = '')
    {
        if (!empty($content)) {
            if (is_array($content)) {
                header('Content-Type: application/json');
                echo json_encode($content, JSON_UNESCAPED_SLASHES);
            }
            else {
                echo esc_html($content);
            }
        }
        self::fastcgi_close();
        exit;
    }

    /**
     * register_locale.
     */
    private static function register_locale()
    {
        add_action(
            'plugins_loaded',
            function () {
                load_plugin_textdomain(
                    'runcloud-hub',
                    false,
                    RUNCLOUD_HUB_PATH_LANG
                );
            },
            0
        );
    }

    public static function wp_cache_delete() {
        wp_cache_delete('uninstall_plugins', 'options');
        wp_cache_delete('alloptions', 'options');
        wp_cache_delete('notoptions', 'options');
        wp_cache_delete(self::get_main_site_id().'-notoptions', 'site-options');
        wp_cache_delete(self::get_main_site_id().'-active_sitewide_plugins', 'site-options');
    }

    /**
     * register_init.
     */
    public static function register_init()
    {
        self::register_locale();
        self::register_wpcli_hooks();

        self::$hook              = RUNCLOUD_HUB_HOOK;
        self::$plugin_url        = plugin_dir_url(RUNCLOUD_HUB_FILE);
        self::$plugin_url_assets = self::$plugin_url . 'assets/';
        self::$cache_path        = (defined('RUNCLOUD_HUB_PATH_CACHE') ? trailingslashit(RUNCLOUD_HUB_PATH_CACHE) : WP_CONTENT_DIR . '/cache/' . self::$slug . '/');

        self::$ordfirst = -PHP_INT_MAX;
        self::$ordlast  = PHP_INT_MAX;

        $__varfunc_maybe_clear_alloptions_cache = function ($option) {
            if (!wp_installing()) {
                $alloptions = wp_load_alloptions();

                if (isset($alloptions[$option])) {
                    self::wp_cache_delete();
                }
            }
        };

        add_action('added_option', $__varfunc_maybe_clear_alloptions_cache);
        add_action('updated_option', $__varfunc_maybe_clear_alloptions_cache);
        add_action('deleted_option', $__varfunc_maybe_clear_alloptions_cache);

        self::reset_purge_action();

        if (defined('RUNCLOUD_HUB_OPTPAGE') && (bool) RUNCLOUD_HUB_OPTPAGE) {
            self::$optpage = true;
        }

        define('RUNCLOUD_HUB_INIT', true);
    }

    /**
     * register_cron_schedules.
     */
    private static function register_cron_schedules()
    {
        add_action(
            'cron_schedules',
            function ($schedules) {
                $name             = self::$slug . '-minute';
                $schedules[$name] = [
                    'interval' => MINUTE_IN_SECONDS,
                    'display'  => $name,
                ];

                $name             = self::$slug . '-hour';
                $schedules[$name] = [
                    'interval' => HOUR_IN_SECONDS,
                    'display'  => $name,
                ];

                $name             = self::$slug . '-day';
                $schedules[$name] = [
                    'interval' => DAY_IN_SECONDS,
                    'display'  => $name,
                ];

                $name             = self::$slug . '-week';
                $schedules[$name] = [
                    'interval' => WEEK_IN_SECONDS,
                    'display'  => $name,
                ];

                $name             = self::$slug . '-month';
                $schedules[$name] = [
                    'interval' => MONTH_IN_SECONDS,
                    'display'  => $name,
                ];

                $name             = self::$slug . '-year';
                $schedules[$name] = [
                    'interval' => YEAR_IN_SECONDS,
                    'display'  => $name,
                ];
                return $schedules;
            },
            self::$ordlast
        );
    }

    /**
     * default_settings.
     */
    private static function default_settings()
    {
        $options = [
            'purge_permalink_onn'                 => 1,
            'purge_theme_switch_onn'              => 1,
            'purge_customize_onn'                 => 0,
            'purge_upgrader_onn'                  => 0,
            'purge_plugin_onn'                    => 0,
            'purge_navmenu_onn'                   => 0,
            'purge_sidebar_onn'                   => 0,
            'purge_widget_onn'                    => 0,
            'homepage_post_onn'                   => 1,
            'homepage_removed_onn'                => 1,
            'content_publish_onn'                 => 1,
            'content_comment_approved_onn'        => 1,
            'archives_content_onn'                => 1,
            'archives_category_onn'               => 1,
            'archives_cpt_onn'                    => 1,
            'archives_author_onn'                 => 0,
            'archives_feed_onn'                   => 0,
            'redis_cache_onn'                     => 0,
            'redis_prefix'                        => '',
            'redis_maxttl_onn'                    => 1,
            'redis_maxttl_int'                    => 1,
            'redis_maxttl_unt'                    => 86400,
            'redis_maxttl_var'                    => 86400,
            'redis_ignored_groups_onn'            => 1,
            'redis_ignored_groups_mch'            => [
                'counts',
                'plugins',
                'themes',
                'comment',
                'wc_session_id',
                'bp_notifications',
                'bp_messages',
                'bp_pages',
            ],
            'redis_debug_onn'                     => 0,
            'rcapi_key'                           => '',
            'rcapi_secret'                        => '',
            'rcapi_webapp_id'                     => '',
            'rcapi_magiclink_onn'                 => 1,
            'stats_onn'                           => 1,
            'stats_schedule_onn'                  => 1,
            'stats_schedule_int'                  => 1,
            'stats_schedule_unt'                  => 86400,
            'stats_schedule_var'                  => 86400,
            'stats_transfer_onn'                  => 1,
            'stats_transfer_var'                  => 'daily',
            'stats_health_onn'                    => 1,
            'stats_health_var'                    => 'hourly',
            'exclude_url_onn'                     => 1,
            'exclude_url_mch'                     => [
                '/.well-known.*',
                '/store.*',
                '/cart.*',
                '/my-account.*',
                '/checkout.*',
                '/addons.*',
                '/login.*',
                '/wp-admin/',
                '/xmlrpc.php',
                'wp-.*.php',
                '/feed/',
                'index.php',
                'wp-comments-popup.php',
                'wp-links-opml.php',
                'wp-locations.php',
                'sitemap(_index)?.xml',
                '[a-z0-9_-]+-sitemap([0-9]+)?.xml',
            ],
            'exclude_cookie_onn'                  => 1,
            'exclude_cookie_mch'                  => [
                'comment_author',
                'wordpress_[a-f0-9]+',
                'wp-postpass',
                'wordpress_no_cache',
                'wordpress_logged_in',
                'woocommerce_cart_hash',
                'woocommerce_items_in_cart',
                'wp_woocommerce_session',
                'woocommerce_recently_viewed'
            ],
            'exclude_browser_onn'                 => 0,
            'exclude_browser_mch'                 => [],
            'exclude_visitorip_onn'               => 0,
            'exclude_visitorip_mch'               => [],
            'allow_query_onn'                     => 0,
            'allow_query_mch'                     => [
                'utm_source',
                'utm_medium',
                'utm_campaign',
                'utm_expid',
                'fb_action_ids',
                'fb_action_types',
                'fb_source',
                'fbclid',
                'gclid',
                'age-verified',
                'ao_noptimize',
                'usqp',
                'cn-reloaded',
                '_ga',
            ],
            'exclude_query_onn'                   => 0,
            'exclude_query_mch'                   => [],
            'schedule_purge_onn'                  => 0,
            'schedule_purge_int'                  => 12,
            'schedule_purge_unt'                  => 3600,
            'schedule_purge_var'                  => 43200,
            'url_path_onn'                        => 0,
            'url_path_mch'                        => [],
            'html_footprint_onn'                  => 0,
            'cache_key_extra_onn'                 => 0,
            'cache_key_extra_var'                 => '',
            'preload_speed'                       => 12,
            'preload_onn'                         => 0,
            'preload_schedule_onn'                => 0,
            'preload_schedule_int'                => 1,
            'preload_schedule_unt'                => 86400,
            'preload_schedule_var'                => 86400,
            'preload_path_onn'                    => 0,
            'preload_path_mch'                    => [],
        ];

        return $options;
    }

    /**
     * can_manage_network_options.
     */
    private static function can_manage_network_options()
    {
        return (is_multisite() && current_user_can(apply_filters('capability', 'manage_network_options')));
    }

    /**
     * can_manage_network_plugins.
     */
    private static function can_manage_network_plugins()
    {
        return (is_multisite() && current_user_can(apply_filters('capability_network', 'manage_network_plugins')));
    }

    /**
     * can_manage_options.
     */
    private static function can_manage_options()
    {
        return (current_user_can(apply_filters('capability', 'manage_options')));
    }

    /**
     * is_client_mode.
     */
    public static function is_client_mode()
    {
        return ( defined('RUNCLOUD_HUB_CLIENT_MODE') && RUNCLOUD_HUB_CLIENT_MODE ? true : false );
    }

    /**
     * get_main_site_id.
     */
    public static function get_main_site_id()
    {
        if (is_multisite()) {
            return get_network()->site_id;
        }

        return get_current_blog_id();
    }

    /**
     * get_main_site_url.
     */
    public static function get_main_site_url()
    {
        return get_site_url(self::get_main_site_id());
    }

    /**
     * is_main_site.
     */
    public static function is_main_site()
    {
        return (get_current_blog_id() === self::get_main_site_id());
    }

    /**
     * is_subdirectory.
     */
    public static function is_subdirectory()
    {
        if ( is_multisite() ) {
            return false;
        }
        $url = untrailingslashit(home_url());
        $part = wp_parse_url($url);
        return (!empty($part['path']) ? true : false);
    }

    /**
     * remove_protocol.
     */
    private static function remove_protocol($host)
    {
        return preg_replace('@^(https?://|//)@', '', trim($host));
    }

    /**
     * remove_www.
     */
    private static function remove_www($host)
    {
        return preg_replace('/^www\./', '', $host);
    }

    /**
     * is_num.
     */
    public static function is_num($str)
    {
        return preg_match('@^\d+$@', $str);
    }

    /**
     * install_options.
     */
    private static function install_options($all_site = false)
    {
        $__varfunc_install = function ($blog_id) {
            $options                 = self::default_settings();
            $options['redis_prefix'] = self::redis_key_prefix($options['redis_prefix'], $blog_id);
            add_option(self::$db_setting, $options);
            self::register_cron_hooks();
        };

        if (is_multisite() && self::can_manage_network_options() && $all_site) {
            foreach (get_sites(array('number' => 500)) as $site) {
                switch_to_blog($site->blog_id);

                $__varfunc_install($site->blog_id);

                restore_current_blog();
            }
        } else {
            $__varfunc_install(get_current_blog_id());
        }
    }

    /**
     * uninstall_options.
     */
    private static function uninstall_options($all_site = false)
    {

        self::wp_cache_delete();
        if ( !self::$onfirehooks ) {
            if (is_multisite()) {
                self::late_purge_cache_redis_all();
            }
            else {
                self::late_purge_cache_redis();
            }
        }

        $__varfunc_uninstall = function () {
            delete_option(self::$db_setting);
            self::register_cron_hooks(false);
        };

        if (is_multisite() && self::can_manage_network_options() && $all_site) {
            foreach (get_sites(array('number' => 500)) as $site) {
                switch_to_blog($site->blog_id);

                $__varfunc_uninstall();

                restore_current_blog();
            }
        } else {
            $__varfunc_uninstall();
        }
    }

    /**
     * stats_is_enabled.
     */
    private static function stats_is_enabled() {
        $options = self::get_setting();

        if (empty($options['rcapi_key']) || empty($options['rcapi_secret']) || empty($options['rcapi_webapp_id'])) {
            return false;
        }

        if (empty($options['stats_onn'])) {
            return false;
        }

        if (empty($options['stats_transfer_onn']) && empty($options['stats_health_onn'])) {
            return false;
        }

        return true;
    }

    /**
     * redis_is_connect.
     */
    public static function redis_is_connect()
    {
        global $wp_object_cache;
        if ( self::is_dropin_valid() && method_exists( $wp_object_cache, 'redis_status' ) ) {
            return $wp_object_cache->redis_status();
        }
        return false;
    }

    /**
     * redis_is_enabled.
     */
    public static function redis_is_enabled()
    {
        if (!is_multisite()) {
            return self::get_setting('redis_cache_onn');
        }
        else {
            if(defined('SUBDOMAIN_INSTALL') && SUBDOMAIN_INSTALL) {
                return self::get_setting('redis_cache_onn');
            }
            else {
                $blog_id = self::get_main_site_id();
                return self::get_setting('redis_cache_onn', $blog_id);
            }
        }

        return false;
    }

    /**
     * redis_can_enable.
     */
    public static function redis_can_enable()
    {
        if (!is_multisite()) {
            return true;
        }
        else {
            if (self::is_main_site()) {
                return true;
            }
            else {
                if(defined('SUBDOMAIN_INSTALL') && SUBDOMAIN_INSTALL) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * add_redis_stats.
     */
    public static function add_redis_stats() {
        if (!self::can_manage_options()) {
            return;
        }

        if (!self::redis_is_enabled()) {
            return;
        }

        if (defined('DOING_AJAX') && DOING_AJAX) {
            return;
        }
        if (defined('DOING_CRON') && DOING_CRON) {
            return;
        }
        if (defined('WP_CLI') && WP_CLI) {
            return;
        }
        if (defined('REST_REQUEST') && REST_REQUEST) {
            return;
        }

        // jetpack stats
        if (isset($_GET['noheader'])) {
            return;
        }

        $output = '';
        if (self::redis_is_connect()) {
            global $wp_object_cache;
            if (method_exists($wp_object_cache, 'info')) {
                $info = $wp_object_cache->info();
                $info_hits = isset($info->hits) ? $info->hits : '';
                $info_misses = isset($info->misses) ? $info->misses : '';
                $info_ratio = isset($info->ratio) ? $info->ratio : '';
                $info_bytes = isset($info->bytes) ? size_format( $info->bytes, 2 ) : '';
                $output = sprintf(esc_html__('Redis Object Cache: Hits %s, Misses %s, Hit Ratio %s%s, Size %s', 'runcloud-hub'), $info_hits, $info_misses, $info_ratio, '%', $info_bytes);
            }
            else {
                $output = esc_html__('Redis Object Cache: Could not get statistics info', 'runcloud-hub');
            }
        }
        else {
            $output = esc_html__('Redis Object Cache: Not connected', 'runcloud-hub');
        }

        if (!empty($output)) {
            echo '<style>.rc-redis-debug{background:#23282d;color:#ccc;text-align:center;padding:10px;font-size:14px;line-height:20px;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;}.wp-admin .rc-redis-debug{margin-left:160px;}.wp-admin.folded .rc-redis-debug{margin-left:36px;}</style>';
            echo '<div class="rc-redis-debug">';
            echo esc_html($output);
            echo '</div>';
        }
    }

    /**
     * redis_key_prefix.
     */
    public static function redis_key_prefix($salt = '', $blog_id = '')
    {
        if (empty($blog_id)) {
            $blog_id = get_current_blog_id();
        }

        if (empty($salt)) {
            $salt = self::get_hash(get_site_url() . md5(time() . $blog_id), 14);
        }

        $key = self::get_hash($salt . $blog_id, 14);
        return $key;
    }

    /**
     * get_dropin_source_file.
     */
    private static function get_dropin_source_file()
    {
        return RUNCLOUD_HUB_PATH_LIB . self::$dropin_source;
    }

    /**
     * get_dropin_file.
     */
    private static function get_dropin_file()
    {
        return WP_CONTENT_DIR . '/' . self::$dropin_file;
    }

    /**
     * get_dropin_config_file.
     */
    private static function get_dropin_config_file()
    {
        return WP_CONTENT_DIR . '/' . self::$dropin_config;
    }

    /**
     * is_dropin_exists.
     */
    private static function is_dropin_exists()
    {
        $file = self::get_dropin_file();
        if (is_file($file) && is_readable($file)) {
            clearstatcache(true, $file);
            return true;
        }
        return false;
    }

    /**
     * is_dropin_config_exist.
     */
    private static function is_dropin_config_exist()
    {
        $file = self::get_dropin_config_file();
        if (is_file($file) && is_readable($file)) {
            clearstatcache(true, $file);
            return true;
        }
        return false;
    }

    /**
     * is_dropin_hub.
     */
    private static function is_dropin_hub()
    {
        if ( !self::is_dropin_exists() ) {
            return false;
        }

        $dropin = get_plugin_data( self::get_dropin_file() );

        if (strpos($dropin['Description'], 'RunCloud Hub') === false) {
            return false;
        }

        return true;
    }

    /**
     * is_dropin_valid.
     */
    private static function is_dropin_valid()
    {
        if ( !self::is_dropin_exists() ) {
            return false;
        }

        $dropin = get_plugin_data( self::get_dropin_file() );
        $source = get_plugin_data( self::get_dropin_source_file() );

        if (strpos($dropin['Description'], 'RunCloud Hub') === false) {
            return false;
        }

        if (strcmp( $dropin['Version'], $source['Version'] ) !== 0) {
            return false;
        }

        return true;
    }

    /**
     * is_dropin_active.
     */
    private static function is_dropin_active()
    {
        if ( defined('RCWP_REDIS_DISABLED') && RCWP_REDIS_DISABLED ) {
            return false;
        }

        if ( !self::is_dropin_exists() ) {
            return false;
        }

        $dropin = get_plugin_data( self::get_dropin_file() );

        if (strpos($dropin[ 'Description' ], 'RunCloud Hub') === false) {
            return false;
        }

        if ( defined('RCWP_REDIS_DROPIN') && RCWP_REDIS_DROPIN ) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * is_dropin_need_install.
     */
    private static function is_dropin_need_install()
    {
        if ( defined('RUNCLOUD_HUB_INSTALL_DROPIN') && !RUNCLOUD_HUB_INSTALL_DROPIN ) {
            return false;
        }

        if ( self::is_dropin_exists() ) {
            return false;
        }

        if (!is_multisite()) {
            return (self::get_setting('redis_cache_onn') ? true : false);
        }
        else {
            if(!(defined('SUBDOMAIN_INSTALL') && SUBDOMAIN_INSTALL)) {
                $blog_id = self::get_main_site_id();
                return (self::get_setting('redis_cache_onn', $blog_id) ? true : false);
            }
        }

        return true;
    }

    /**
     * is_dropin_need_remove.
     */
    private static function is_dropin_need_remove()
    {
        $is_exist = self::is_dropin_exists() && self::is_dropin_hub() ? true : false;

        $is_needed = true;
        if (!is_multisite()) {
            $is_needed = (self::get_setting('redis_cache_onn') ? true : false);
        }
        else {
            if(!(defined('SUBDOMAIN_INSTALL') && SUBDOMAIN_INSTALL)) {
                $blog_id = self::get_main_site_id();
                $is_needed = (self::get_setting('redis_cache_onn', $blog_id) ? true : false);
            }
        }

        if ($is_exist && !$is_needed) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * is_dropin_need_update.
     */
    private static function is_dropin_need_update()
    {
        if ( defined('RCWP_REDIS_DISABLED') && RCWP_REDIS_DISABLED ) {
            return false;
        }

        if ( !self::is_dropin_exists() ) {
            return false;
        }

        // drop-in is enabled by other plugin
        if ( self::is_dropin_exists() && !self::is_dropin_hub() ) {
            return false;
        }

        if ( self::is_dropin_hub() && self::is_dropin_valid() ) {
            return false;
        }

        return true;
    }

    /**
     * is_dropin_need_replace.
     */
    private static function is_dropin_need_replace()
    {
        if ( self::is_dropin_exists() && !self::is_dropin_hub() ) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * is_dropin_need_install.
     */
    private static function is_dropin_config_need_install()
    {
        if ( defined('RUNCLOUD_HUB_INSTALL_DROPIN') && !RUNCLOUD_HUB_INSTALL_DROPIN ) {
            return false;
        }

        if ( self::is_dropin_config_exist() ) {
            return false;
        }

        return true;
    }

    /**
     * is_dropin_config_need_update.
     */
    /**
     * is_dropin_config_need_update.
     */
    private static function is_dropin_config_need_update()
    {
        if ( ! self::is_dropin_active() ) {
            return false;
        }

        $file_dropin_config = self::get_dropin_config_file();
        if ( !file_exists( $file_dropin_config ) ) {
            return true;
        }

        $config = file_exists($file_dropin_config) ? @include($file_dropin_config) : array();

        $main_blog_id = is_multisite() ? self::get_main_site_id() : '';
        $options = self::get_setting('', $main_blog_id);

        if (!isset($config['prefix'], $config['maxttl'])) {
            return true;
        }

        if ($config['prefix'] !== $options['redis_prefix']) {
            return true;
        }
        if ($config['maxttl'] !== (int) $options['redis_maxttl_var']) {
            return true;
        }

        $main_status_ok = false;
        $blog_status_ok = false;

        $main_status = $options['redis_cache_onn'];
        if ($main_status === 1 && isset($config['status']) && $config['status'] === 1) {
            $main_status_ok = true;
        }
        elseif ($main_status !== 1 && isset($config['status']) && $config['status'] !== 1) {
            $main_status_ok = true;
        }

        if (is_multisite() && defined('SUBDOMAIN_INSTALL') && SUBDOMAIN_INSTALL) {
            $blog_id = get_current_blog_id();
            $blog_status = self::get_setting('redis_cache_onn', $blog_id);
            $url = home_url();
            $part = wp_parse_url($url);
            $host = $part['host'];
            if ($blog_status === 1 && isset($config['status_sites'][$host]) && $config['status_sites'][$host] === 1) {
                $blog_status_ok = true;
            }
            elseif ($blog_status !== 1 && isset($config['status_sites'][$host]) && $config['status_sites'][$host] !== 1) {
                $blog_status_ok = true;
            }
        }
        else {
            $blog_status_ok = true;
        }

        if ($main_status_ok && $blog_status_ok) {
            return false;
        }
        else {
            return true;
        }
    }

    /**
     * try_fix_dropin.
     */
    public static function try_fix_dropin()
    {
        if ( self::is_ourscreen() ) {
            $req_status = null;
            if (self::is_dropin_need_install() || self::is_dropin_need_update()) {
                $req_status = self::install_dropin(true, true);
            }
            elseif (self::is_dropin_need_remove()) {
                $req_status = self::uninstall_dropin(true, true);
            }
            if (self::is_dropin_config_need_install() || self::is_dropin_config_need_update()) {
                $req_status = self::install_dropin_config(true, true);
            }
            if (!empty($req_status)) {
                update_option(self::$db_notice, $req_status);
            }
        }
    }

    /**
     * try_setup_dropin.
     */
    public static function try_setup_dropin()
    {
        self::debug(__METHOD__, 'event run');
        if (self::is_dropin_need_install() || self::is_dropin_need_update()) {
            self::install_dropin(true);
        }
        elseif (self::is_dropin_need_remove()) {
            self::uninstall_dropin(false);
        }
        if (self::is_dropin_config_need_install() || self::is_dropin_config_need_update()) {
            self::install_dropin_config(true);
        }
    }

    /**
     * install_dropin.
     */
    public static function install_dropin($force = false, $status = false)
    {
        if ( defined('RUNCLOUD_HUB_INSTALL_DROPIN') && !RUNCLOUD_HUB_INSTALL_DROPIN ) {
            if ($status) {
                return array(
                    'code' => 0,
                    'status' => esc_html__('Install Drop-in Stopped - RUNCLOUD_HUB_INSTALL_DROPIN', 'runcloud-hub'),
                );
            }
            return false;
        }

        $file_dropin_source = self::get_dropin_source_file();
        $file_dropin = self::get_dropin_file();
        $perm = self::get_fileperms('file');

        if (!file_exists($file_dropin_source)) {
            if ($status) {
                return array(
                    'code' => 0,
                    'status' => esc_html__('Install Drop-in Stopped - Drop-in source file does not exist', 'runcloud-hub'),
                );
            }
            return false;
        }

        $need_install = self::is_dropin_need_install();
        $need_update = self::is_dropin_need_update();

        if ($force || $need_install || $need_update) {
            // self::wp_cache_delete();

            if ($force || $need_update) {
                if (file_exists($file_dropin)) {
                    $remove = @unlink($file_dropin);
                    if (!$remove) {
                        if ($status) {
                            return array(
                                'code' => 0,
                                'status' => esc_html__('Install Drop-in Stopped - Drop-in file was not writable', 'runcloud-hub'),
                            );
                        }
                        return false;
                    }
                }
            }

            if ( !file_exists($file_dropin) ) {
                $buff = file_get_contents($file_dropin_source);
                if ( !empty($buff) && file_put_contents($file_dropin, $buff, LOCK_EX) ) {
                    @chmod($file_dropin, $perm);
                    if (!$force) {
                        if ($status) {
                            return array(
                                'code' => 200,
                                'status' => esc_html__('Installing/updating object cache drop-in was successful', 'runcloud-hub'),
                            );
                        }
                        return true;
                    }
                    else {
                        return self::install_dropin_config($force, $status);
                    }
                }
                else {
                    if ($status) {
                        return array(
                            'code' => 0,
                            'status' => esc_html__('Installing/updating object cache drop-in failed', 'runcloud-hub'),
                        );
                    }
                    return false;
                }
            }
            else {
                if ($status) {
                    return array(
                        'code' => 0,
                        'status' => esc_html__('Install Drop-in Stopped - Drop-in file could not be updated', 'runcloud-hub'),
                    );
                }
                return false;
            }
        }
    }

    /**
     * install_dropin_config.
     */
    public static function install_dropin_config($force = false, $status = false)
    {
        if ( defined('RUNCLOUD_HUB_INSTALL_DROPIN') && !RUNCLOUD_HUB_INSTALL_DROPIN ) {
            if ($status) {
                return array(
                    'code' => 0,
                    'status' => esc_html__('Update Drop-in Config Stopped - RUNCLOUD_HUB_INSTALL_DROPIN', 'runcloud-hub'),
                );
            }
            return false;
        }

        $file_dropin_config = self::get_dropin_config_file();
        $perm = self::get_fileperms('file');

        $need_install = self::is_dropin_config_need_install();
        $need_update = self::is_dropin_config_need_update();

        if ($force || $need_install || $need_update) {
            // self::wp_cache_delete();

            if ($force || $need_update) {
                if (file_exists($file_dropin_config)) {
                    $remove = @unlink($file_dropin_config);
                    if (!$remove) {
                        if ($status) {
                            return array(
                                'code' => 0,
                                'status' => esc_html__('Update Drop-in Config Stopped - Drop-in config file was not writable', 'runcloud-hub'),
                            );
                        }
                        return false;
                    }
                }
            }

            if ( !file_exists($file_dropin_config) ) {
                $buff = '<?php '.PHP_EOL.'return '.var_export(self::get_dropin_config(),1).';'.PHP_EOL;
                if ( !empty($buff) && file_put_contents($file_dropin_config, $buff, LOCK_EX) ) {
                    self::wp_cache_delete();
                    if ( !self::$onfirehooks ) {
                        wp_cache_flush();
                    }
                    @chmod($file_dropin_config, $perm);
                    if ($status) {
                        return array(
                            'code' => 200,
                            'status' => esc_html__('Updating object cache drop-in config was successful', 'runcloud-hub'),
                        );
                    }
                    return true;
                }
                else {
                    if ($status) {
                        return array(
                            'code' => 0,
                            'status' => esc_html__('Updating object cache drop-in config failed', 'runcloud-hub'),
                        );
                    }
                    return false;
                }
            }
            else {
                if ($status) {
                    return array(
                        'code' => 0,
                        'status' => esc_html__('Install Drop-in Config Stopped - Drop-in config file could not be updated', 'runcloud-hub'),
                    );
                }
                return false;
            }
        }
    }

    /**
     * get_dropin_config.
     */
    private static function get_dropin_config()
    {
        $config = array(
            'type' => '',
        );

        if (is_multisite()) {
            if(defined('SUBDOMAIN_INSTALL') && SUBDOMAIN_INSTALL) {
                $config['type'] = 'multisite_subdomain';
            }
            else {
                $config['type'] = 'multisite_subdirectory';
            }
            $url = self::get_main_site_url();
            $part = wp_parse_url($url);
            $config['domain'] = $part['host'];
            $config['path'] = (!empty($part['path']) ? str_replace('/', '__', trim( $part['path'], '/' ) ) : '');
        }
        else {
            $url = untrailingslashit(home_url());
            $part = wp_parse_url($url);
            if(empty($part['path'])) {
                $config['type'] = 'single_domain';
            }
            else {
                $config['type'] = 'single_subdirectory';
            }
            $config['domain'] = $part['host'];
            $config['path'] = (!empty($part['path']) ? str_replace('/', '__', trim( $part['path'], '/' ) ) : '');
        }

        if (is_multisite()) {
            $main_site_id = self::get_main_site_id();
            switch_to_blog($main_site_id);
        }

        $options = self::get_setting();
        $config['status'] = $options['redis_cache_onn'];
        $config['prefix'] = $options['redis_prefix'];
        $config['maxttl'] = (int) $options['redis_maxttl_var'];
        if (!empty($options['redis_ignored_groups_onn']) && !empty($options['redis_ignored_groups_mch']) && is_array($options['redis_ignored_groups_mch'])) {
            $ignored_groups = (array) $options['redis_ignored_groups_mch'];
            $config['ignored_groups'] = $ignored_groups;
        }
        else {
            $config['ignored_groups'] = [];
        }

        if (is_multisite()) {
            restore_current_blog();
        }

        if ($config['type'] == 'multisite_subdomain') {
            foreach (get_sites(array('number' => 500)) as $site) {
                $blog_id = $site->blog_id;
                $url = get_home_url($blog_id);
                $part = wp_parse_url($url);
                $host = $part['host'];
                $config['status_sites'][$host] = self::get_setting('redis_cache_onn', $blog_id);
            }
        }

        return $config;
    }

    /**
     * uninstall_dropin.
     */
    public static function uninstall_dropin($config = true, $status = false)
    {
        self::debug(__METHOD__, 'event run');

        $removed = false;
        $file_dropin = self::get_dropin_file();
        if (file_exists($file_dropin)) {
            self::debug(__METHOD__, 'remove dropin');
            $removed = @unlink($file_dropin);
        }

        if ($config) {
            $file_dropin_config = self::get_dropin_config_file();
            if (file_exists($file_dropin_config)) {
                self::debug(__METHOD__, 'remove dropin config');
                @unlink($file_dropin_config);
            }
        }

        wp_cache_flush();

        if ($status) {
            if ($removed) {
                return array(
                    'code' => 200,
                    'status' => esc_html__('Removing object cache drop-in was successful', 'runcloud-hub'),
                );
            }
            else {
                return array(
                    'code' => 0,
                    'status' => esc_html__('Removing object cache drop-in failed', 'runcloud-hub'),
                );
            }
        }

        return $removed;
    }

    /**
     * is_plugin_active.
     */
    public static function is_plugin_active($plugin)
    {
        if (!function_exists('is_plugin_active')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        return is_plugin_active($plugin);
    }

    /**
     * is_plugin_active_for_network.
     */
    private static function is_plugin_active_for_network($plugin)
    {
        if (!function_exists('is_plugin_active_for_network')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        return is_plugin_active_for_network($plugin);
    }

    /**
     * force_site_deactivate_plugin.
     */
    private static function force_site_deactivate_plugin()
    {
        if ((self::can_manage_network_plugins() || self::is_wp_cli())
            && self::is_plugin_active_for_network(self::$hook)) {
            foreach (get_sites(array('number' => 500)) as $site) {
                switch_to_blog($site->blog_id);
                deactivate_plugins(self::$hook, true, false);
                restore_current_blog();
            }
        }
    }

    /**
     * callback_links.
     */
    public static function callback_links($links)
    {
        array_unshift(
            $links,
            sprintf(
                '<a href="%s">%s</a>',
                admin_url('options-general.php?page=' . self::$slug),
                esc_html__('Settings', 'runcloud-hub')
            )
        );
        return $links;
    }

    /**
     * callback_page.
     */
    public static function callback_page()
    {
        $hook_id = add_submenu_page(
            'options-general.php',
            self::$name,
            self::$name,
            apply_filters('capability', 'manage_options'),
            self::$slug,
            [__CLASS__, 'view_index']
        );
    }

    /**
     * callback_network_page.
     */
    public static function callback_network_page()
    {
        $hook_id = add_submenu_page(
            'settings.php',
            self::$name,
            self::$name,
            apply_filters('capability', 'manage_network_options'),
            self::$slug,
            [__CLASS__, 'view_network_index']
        );
    }

    /**
     * is_ourscreen.
     */
    private static function is_ourscreen()
    {
        if(!function_exists('get_current_screen')) {
            return false;
        }
        $screen = get_current_screen();
        $allowed_screens = array(
            'settings_page_'.self::$slug,
            'settings_page_'.self::$slug.'-network',
        );
        if ( !empty($screen->id) && in_array($screen->id, $allowed_screens) ) {
            return true;
        }
        return false;
    }

    /**
     * view_timeduration_select.
     */
    private static function view_timeduration_select($selected = '60', $skip = '')
    {
        $lists = [
            [
                'id'  => 'minute',
                'sec' => MINUTE_IN_SECONDS,
                'txt' => esc_html__('Minutes', 'runcloud-hub'),
            ],
            [
                'id'  => 'hour',
                'sec' => HOUR_IN_SECONDS,
                'txt' => esc_html__('Hours', 'runcloud-hub'),
            ],
            [
                'id'  => 'day',
                'sec' => DAY_IN_SECONDS,
                'txt' => esc_html__('Days', 'runcloud-hub'),
            ],
            [
                'id'  => 'week',
                'sec' => WEEK_IN_SECONDS,
                'txt' => esc_html__('Weeks', 'runcloud-hub'),
            ],
            [
                'id'  => 'month',
                'sec' => MONTH_IN_SECONDS,
                'txt' => esc_html__('Months', 'runcloud-hub'),
            ],
            [
                'id'  => 'year',
                'sec' => YEAR_IN_SECONDS,
                'txt' => esc_html__('Years', 'runcloud-hub'),
            ],
        ];

        foreach ($lists as $arr) {
            $id  = $arr['id'];
            $val = $arr['sec'];
            $txt = $arr['txt'];

            if (!empty($skip)) {
                if (is_array($skip) && in_array($id, $skip)) {
                    continue;
                } elseif ($skip === $id) {
                    continue;
                }
            }

            echo '<option value="' . esc_attr($val) . '"' . ($val == $selected ? ' selected' : '') . '>' . esc_html($txt) . '</option>';
        }
    }

    /**
     * view_timeduration_select.
     */
    private static function view_number_select($selected = 0, $min = 0, $max = 100)
    {
        for ($x = $min; $x <= $max; $x++) {
            echo '<option value="' . esc_attr($x) . '"' . ($x == $selected ? ' selected' : '') . '>' . esc_html($x) . '</option>';
        }
    }

    /**
     * view_stats_select.
     */
    private static function view_stats_select($selected = 'hourly', $skip = '')
    {
        $types = [
            'hourly'  => esc_html__('Hourly', 'runcloud-hub'),
            'daily'   => esc_html__('Daily', 'runcloud-hub'),
            'monthly' => esc_html__('Monthly', 'runcloud-hub'),
        ];

        foreach ($types as $id => $name) {
            if (!empty($skip) && $skip === $id) {
                continue;
            }
            echo '<option value="' . esc_attr($id) . '"' . ($id == $selected ? ' selected' : '') . '>' . esc_html($name) . '</option>';
        }
    }

    /**
     * view_action_link.
     */
    private static function view_action_link($type)
    {
        echo esc_url_raw( self::get_action_link($type) );
    }

    /**
     * view_site_select.
     */
    private static function view_site_select()
    {
        foreach (get_sites(array('number' => 500)) as $site) {
            $n = $site->blog_id;
            $v = self::remove_protocol(get_home_url($n));
            if ((int) $n === (int) self::get_main_site_id()) {
                $v = $v . ' '.esc_html__('(primary)', 'runcloud-hub');
            }
            echo '<option value="' . esc_attr($n) . '"' . ($n == $selected ? ' selected' : '') . '>' . esc_html($v) . '</option>';
        }
    }

    /**
     * view_site_switch.
     */
    private static function view_site_switch()
    {
        if (!is_multisite()) {
            return;
        }

        $network_active = is_plugin_active_for_network( self::$slug.'/'.self::$slug.'.php' );
        $current_blog_id = get_current_blog_id();
        $main_site_id = self::get_main_site_id();

        if (is_network_admin()) {
            echo '<option value="">' . esc_html__('Select Site', 'runcloud-hub') . '</option>';
        }
        if ($network_active && !is_network_admin()) {
            $admin_url = network_admin_url('settings.php?page=' . self::$slug);
            echo '<option value="'.esc_attr($admin_url).'">' . esc_html__('Network Admin Settings', 'runcloud-hub') . '</option>';
        }

        $sites = get_sites(array('number' => 500));
        foreach ($sites as $site) {
            $blog_id = $site->blog_id;
            switch_to_blog($blog_id);
            if (!$network_active) {
                $active = is_plugin_active( self::$slug.'/'.self::$slug.'.php' );
                if (!$active) {
                    restore_current_blog();
                    continue;
                }
            }
            $label = home_url();
            $label = self::remove_protocol($label);
            if ($main_site_id == $blog_id) {
                $label = $label . ' '.esc_html__('(primary)', 'runcloud-hub');
            }
            $admin_url = admin_url('options-general.php?page=' . self::$slug);
            echo '<option value="' . esc_attr($admin_url) . '"' . (!is_network_admin() && $blog_id == $current_blog_id ? ' selected' : '') . '>' . esc_html($label) . '</option>';
            restore_current_blog();
        }
    }

    /**
     * view_fattr.
     */
    private static function view_fattr()
    {
        echo 'autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"';
    }

    /**
     * view_fname.
     */
    private static function view_fname($name)
    {
        echo self::$db_setting . '[' . esc_attr($name) . ']';
    }

    /**
     * view_checked.
     *
     * @since   0.0.0
     */
    private static function view_checked($name)
    {
        echo (isset(self::$checked[$name]) ? esc_attr(self::$checked[$name]) : '');
    }

    /**
     * view_rvalue.
     */
    private static function view_rvalue($name)
    {
        return (isset(self::$value[$name]) ? self::$value[$name] : '');
    }

    /**
     * view_fvalue.
     */
    private static function view_fvalue($name)
    {
        echo self::view_rvalue($name);
    }

    /**
     * view_do_preload.
     */
    private static function view_do_preload()
    {
        $preload = get_option(self::$db_preload);
        if (empty($preload)) {
            return;
        }

        if (!isset($preload['num'], $preload['total'], $preload['type'])) {
            return;
        }

        $num = $preload['num'];
        $total = $preload['total'];
        $type = $preload['type'];

        if (!$total || ($total && $num >= $total)) {
            delete_option(self::$db_preload);
            return;
        }

        if ($type === 'admin') {
            if ($num == 0) {
                // preload homepage
                $preload_url = home_url('/');
                self::fetch_preload($preload_url, false);
                // preload path
                $options = self::get_setting();
                if (!empty($options['preload_path_onn']) && !empty($options['preload_path_mch']) && is_array($options['preload_path_mch'])) {
                    foreach ($options['preload_path_mch'] as $path) {
                        if ('/' === $path) {
                            continue;
                        }
                        $path    = strtolower($path);
                        $preload_url = home_url('/') . ltrim($path, '/');
                        self::fetch_preload($preload_url, false);
                    }
                }
            }

            $time = time();
            $link_cancel = self::get_action_link('preload_cancel');
            $link_run_background = self::get_action_link('preload_run_background');
            $inc = self::get_preload_inc();
            $interval = self::get_preload_interval();

            $link_trigger = admin_url('admin-post.php?action=' . self::get_hash(self::$slug . '_preload') . '&_preload=' . $time);
            $link_trigger = add_query_arg('_wpnonce', wp_create_nonce('preload' . $time), $link_trigger);

            echo '<div class="notice notice-info" id="runcloud-preload" ';
            echo 'data-preload-abort="' . esc_url_raw($link_cancel) . '" ';
            echo 'data-preload-trigger="' . esc_url_raw($link_trigger) . '" ';
            echo 'data-preload-num="' . esc_attr($num) . '" ';
            echo 'data-preload-total="' . esc_attr($total) . '" ';
            echo 'data-preload-inc="' . esc_attr($inc) . '" ';
            echo 'data-preload-interval="' . esc_attr($interval) . '">';
            echo '<p id="preload-text"><strong>';
            if ($num) {
                esc_html_e('Preloading is running... Please wait...', 'runcloud-hub');
            }
            else {
                esc_html_e('Preloading is started... Please wait...', 'runcloud-hub');
            }
            echo '</strong></p>';
            echo '<p id="preload-button">';
            echo '<a href="' . esc_url_raw($link_cancel) . '" class="preload-stop button button-primary">'.esc_html__('Cancel', 'runcloud-hub').'</a>';
            echo '<a href="' . esc_url_raw($link_run_background) . '" class="preload-switch button-light">'.esc_html__('Switch to run in the background', 'runcloud-hub').'</a>';
            echo '</p>';
            echo '</div>';
        }
        else {
            $link_cancel = self::get_action_link('preload_cancel');
            $link_run_admin = self::get_action_link('preload_run_admin');
            echo '<div class="notice notice-info" id="runcloud-preload">';
            echo '<p id="preload-text"><strong>';
            printf( esc_html__('Preloading is running in the background (%s/%s). Please refresh to see the progress.', 'runcloud-hub'), $num, $total );
            echo '</strong></p>';
            echo '<p id="preload-button">';
            echo '<a href="' . esc_url_raw($link_cancel) . '" class="preload-stop button button-primary">'.esc_html__('Stop', 'runcloud-hub').'</a>';
            echo '<a href="' . esc_url_raw($link_run_admin) . '" class="preload-switch button-light">'.esc_html__('Switch to run in the browser', 'runcloud-hub').'</a>';
            echo '</p>';
            echo '</div>';
        }
    }

    /**
     * callback_view_preload.
     */
    public static function callback_view_preload()
    {
        if (!isset($_GET['_wpnonce'], $_GET['_preload'])) {
            self::debug(__METHOD__, esc_html__('Invalid request', 'runcloud-hub'));
            delete_option(self::$db_preload);
            self::close_exit(esc_html__('Invalid request', 'runcloud-hub'));
        }

        if (!wp_verify_nonce($_GET['_wpnonce'], 'preload' . $_GET['_preload'])) {
            self::debug(__METHOD__, esc_html__('Invalid nonce', 'runcloud-hub'));
            delete_option(self::$db_preload);
            self::close_exit(esc_html__('Invalid nonce', 'runcloud-hub'));
        }

        $preload = get_option(self::$db_preload);
        if (!empty($preload) && is_array($preload)) {
            if (isset($_GET['num']) && self::is_num($_GET['num'])) {
                $num  = (int) $_GET['num'];
                if ($num >= (int) $preload['total']) {
                    delete_option(self::$db_preload);
                    self::close_exit('Done');
                }
                else {
                    $inc = self::get_preload_inc();
                    $urls = self::get_preload_urls($preload['num'], $inc);
                    if (!empty($urls)) {
                        foreach ($urls as $url) {
                            $num++;
                            self::debug(__METHOD__, sprintf( esc_html__('Browser Preload %s / %s : %s', 'runcloud-hub'), $num, $preload['total'], $url ) );
                            self::fetch_preload($url, false);
                        }
                        $preload['num'] = $num;
                        update_option(self::$db_preload, $preload, false);
                        self::close_exit($url);
                    }
                    else {
                        self::debug(__METHOD__, sprintf( esc_html__('Browser Preload %s / %s : %s', 'runcloud-hub'), $num, $preload['total'], esc_html__('Preload URL is empty', 'runcloud-hub') ) );
                        self::close_exit();
                    }
                }
            }
        }
        self::debug(__METHOD__, esc_html__('Browser Preload : URL is empty', 'runcloud-hub') );
        self::close_exit();
    }

    /**
     * view_do_network.
     */
    private static function view_do_network()
    {
        if (!is_network_admin()) {
            return;
        }

        $network = get_option(self::$db_network);
        if (empty($network)) {
            return;
        }

        if (!isset($network['num'], $network['total'], $network['type'])) {
            return;
        }

        $num = $network['num'];
        $total = $network['total'];
        $type = $network['type'];

        if (!$total || ($total && $num >= $total)) {
            delete_option(self::$db_network);
            return;
        }

        $time = time();
        $link_cancel = self::get_action_link('network_cancel');
        $inc = 1;
        $interval = 2;

        $link_trigger = admin_url('admin-post.php?action=' . self::get_hash(self::$slug . '_network') . '&_network=' . $time);
        $link_trigger = add_query_arg('_wpnonce', wp_create_nonce('network' . $time), $link_trigger);

        echo '<div class="notice notice-info" id="runcloud-network" ';
        echo 'data-network-abort="' . esc_url_raw($link_cancel) . '" ';
        echo 'data-network-trigger="' . esc_url_raw($link_trigger) . '" ';
        echo 'data-network-num="' . esc_attr($num) . '" ';
        echo 'data-network-total="' . esc_attr($total) . '" ';
        echo 'data-network-inc="' . esc_attr($inc) . '" ';
        echo 'data-network-interval="' . esc_attr($interval) . '">';
        echo '<p id="network-text"><strong>';
        if ($num) {
            esc_html_e('Please wait...', 'runcloud-hub');
        }
        else {
            esc_html_e('Please wait...', 'runcloud-hub');
        }
        echo '</strong></p>';
        echo '<p id="network-button">';
        echo '<a href="' . esc_url_raw($link_cancel) . '" class="network-stop button button-primary">'.esc_html__('Cancel', 'runcloud-hub').'</a>';
        echo '</p>';
        echo '</div>';
    }

    /**
     * callback_view_network.
     */
    public static function callback_view_network()
    {
        if (!isset($_GET['_wpnonce'], $_GET['_network'])) {
            self::debug(__METHOD__, esc_html__('Invalid request', 'runcloud-hub'));
            delete_option(self::$db_network);
            self::close_exit(esc_html__('Invalid request', 'runcloud-hub'));
        }

        if (!wp_verify_nonce($_GET['_wpnonce'], 'network' . $_GET['_network'])) {
            self::debug(__METHOD__, esc_html__('Invalid nonce', 'runcloud-hub'));
            delete_option(self::$db_network);
            self::close_exit(esc_html__('Invalid nonce', 'runcloud-hub'));
        }

        $network = get_option(self::$db_network);
        if (!empty($network) && is_array($network)) {
            if (isset($_GET['num']) && self::is_num($_GET['num'])) {
                $num  = (int) $_GET['num'];
                if ($num >= (int) $network['total']) {
                    delete_option(self::$db_network);
                    self::close_exit('Done');
                }
                else {
                    $sites = get_sites(['number' => 1, 'offset' => $num]);
                    if (!empty($sites)) {
                        $num++;
                        $network['num'] = $num;
                        update_option(self::$db_network, $network, false);
                        $message = '';
                        foreach ($sites as $site) {
                            switch_to_blog($site->blog_id);
                            if ($network['type'] == 'purge') {
                                $purge = self::purge_cache_all();
                                $message = home_url('/').' - '.$purge['status'];
                            }
                            restore_current_blog();
                        }
                        self::close_exit($message);
                    }
                }
            }
        }
        self::close_exit();
    }

    /**
     * view_page.
     */
    public static function view_page($name, $once = false)
    {
        $__varfunc_view = function ($name, $once = false) {
            $file = RUNCLOUD_HUB_PATH_VIEW . $name . '.php';
            if (is_file($file) && is_readable($file)) {
                clearstatcache(true, $file);

                if ($once) {
                    include_once $file;
                } else {
                    include $file;
                }
            } else {
                echo sprintf( esc_html__( 'Cannot read "%s" file', 'runcloud-hub'), $name ).'<br>';
            }
        };

        if (!empty($name)) {
            if (is_array($name)) {
                foreach ($name as $nm) {
                    $__varfunc_view($nm);
                }
            } else {
                $__varfunc_view($name);
            }
        }
    }

    /**
     * view_index.
     */
    public static function view_index($page = '')
    {
        $options_default = self::default_settings();
        $options         = self::get_setting();
        self::$checked   = [];
        if (!empty($options) && is_array($options)) {
            foreach ($options as $key => $val) {
                if (preg_match('/.*_onn$/', $key)) {
                    $val                 = (int) $val;
                    self::$checked[$key] = (1 === $val ? ' checked' : '');
                    self::$value[$key]   = $val;
                } elseif (preg_match('/.*_mch$/', $key)) {
                    $ckey = str_replace('_mch', '_onn', $key);
                    if (!empty($val) && is_array($val)) {
                        self::$value[$key] = implode("\n", $val);
                    } else {
                        self::$checked[$ckey] = '';
                        self::$value[$key]    = (!empty($options_default[$key]) && is_array($options_default[$key]) ? implode("\n", $options_default[$key]) : '');
                    }
                } elseif (preg_match('/^rcapi.*_id$/', $key)) {
                    self::$value[$key] = (empty($val) ? '' : $val);
                } else {
                    self::$value[$key] = $val;
                }
            }
        }

        $page = (!empty($page) ? esc_attr($page) : 'admin');
        self::view_page($page);
    }

    /**
     * view_network_index.
     */
    public static function view_network_index()
    {
        self::view_index('admin-network');
    }

    /**
     * assets_version.
     */
    private static function assets_version($file)
    {
        $fm = RUNCLOUD_HUB_PATH_RSC . $file;
        if (file_exists($fm)) {
            return self::get_hash(filemtime($fm));
        }

        return self::get_hash(self::$version);
    }

    /**
     * admin_assets.
     */
    public static function admin_assets()
    {
        if ( self::can_manage_options() ) {
            $fm = 'css/' . self::$islug . '-wp.css';
            wp_enqueue_style(
                self::$slug . '-wp',
                self::$plugin_url_assets . $fm,
                [],
                self::assets_version($fm),
                'all'
            );
        }

        if(!self::is_ourscreen()) {
            return;
        }

        $fm = 'css/' . self::$islug . '.css';
        wp_enqueue_style(
            self::$slug,
            self::$plugin_url_assets . $fm,
            [],
            self::assets_version($fm),
            'all'
        );

        $fm = 'js/' . self::$islug . '.js';
        wp_enqueue_script(
            self::$slug,
            self::$plugin_url_assets . $fm,
            [],
            self::assets_version($fm),
            true
        );
    }

    /**
     * append_wp_http_referer.
     */
    private static function append_wp_http_referer()
    {
        $referer = '';
        if (!empty($_SERVER['REQUEST_URI'])) {
            $referer = filter_var(wp_unslash($_SERVER['REQUEST_URI']), FILTER_SANITIZE_URL);
            $referer = '&_wp_http_referer=' . rawurlencode(remove_query_arg('fl_builder', $referer));
        }

        return $referer;
    }

    /**
     * callback_bar_menu.
     */
    public static function callback_bar_menu($wp_admin_bar)
    {
        if (!self::can_manage_options()) {
            return;
        }

        if (is_network_admin() && !self::is_plugin_active_for_network(self::$hook)) {
            return;
        }

        global $pagenow, $post;

        $referer = self::append_wp_http_referer();
        $wp_admin_bar->add_menu(
            [
                'id'    => self::$slug,
                'title' => self::$name,
            ]
        );

        $admin_url = (is_network_admin() ? network_admin_url('settings.php?page=' . self::$slug) : admin_url('options-general.php?page=' . self::$slug));

        if (!self::$optpage) {
            $wp_admin_bar->add_menu(
                [
                    'parent' => self::$slug,
                    'id'     => self::$slug . '-setting',
                    'title'  => esc_html__('Settings', 'runcloud-hub'),
                    'href'   => $admin_url . (self::is_ourscreen() ? '#setting' : ''),
                ]
            );
        }

        if (is_admin()) {
            if ($post && 'post.php' === $pagenow && isset($_GET['action'], $_GET['post'])) {
                $wp_admin_bar->add_menu(
                    [
                        'parent' => self::$slug,
                        'id'     => self::$slug . '-clearcachepost',
                        'title'  => esc_html__('Clear Cache Of This Post', 'runcloud-hub'),
                        'href'   => self::get_action_link('purgepost-' . get_the_ID()),
                    ]
                );
            }
        } else {
            if (is_singular()) {
                $wp_admin_bar->add_menu(
                    [
                        'parent' => self::$slug,
                        'id'     => self::$slug . '-clearcachepost',
                        'title'  => esc_html__('Clear Cache Of This Post', 'runcloud-hub'),
                        'href'   => self::get_action_link('purgepost-' . get_the_ID()),
                    ]
                );
            }
            else {
                $wp_admin_bar->add_menu(
                    [
                        'parent' => self::$slug,
                        'id'     => self::$slug . '-clearcacheurl',
                        'title'  => esc_html__('Clear Cache Of This URL', 'runcloud-hub'),
                        'href'   => self::get_action_link('purgeurl'),
                    ]
                );
            }
        }

        if (!is_network_admin()) {
            $wp_admin_bar->add_menu(
                [
                    'parent' => self::$slug,
                    'id'     => self::$slug . '-clearcacheall',
                    'title'  => esc_html__('Clear All Cache', 'runcloud-hub'),
                    'href'   => self::get_action_link('purgeall'),
                ]
            );

            if (self::redis_is_connect() && self::is_dropin_active()) {
                $wp_admin_bar->add_menu(
                    [
                        'parent' => self::$slug,
                        'id'     => self::$slug . '-clearcacheredis',
                        'title'  => esc_html__('Clear Redis Object Cache', 'runcloud-hub'),
                        'href'   => self::get_action_link('purgeredis'),
                    ]
                );
            }
        }
        else {
            $wp_admin_bar->add_menu(
                [
                    'parent' => self::$slug,
                    'id'     => self::$slug . '-clearcacheallsites',
                    'title'  => esc_html__('Clear All Sites Cache', 'runcloud-hub'),
                    'href'   => self::get_action_link('purgesiteall'),
                ]
            );

            if (self::redis_is_connect() && self::is_dropin_active()) {
                $wp_admin_bar->add_menu(
                    [
                        'parent' => self::$slug,
                        'id'     => self::$slug . '-clearcacheredis',
                        'title'  => esc_html__('Clear All Sites Redis Object Cache', 'runcloud-hub'),
                        'href'   => self::get_action_link('purgeredisall'),
                    ]
                );
            }
        }
    }

    /**
     * after_update_setting.
     */
    public static function after_update_setting($old, $options)
    {
        if (!empty($options)) {

            $doreload = false;

            if (is_multisite()) {

                if ( false !== strpos(wp_get_referer(), '/network/settings.php')) {
                    self::debug(__METHOD__, ['multisite'=>get_site_url()]);
                    if ( !empty($options['redis_cache_net_onn']) ) {
                        $redis_cache_onn = $options['redis_cache_onn'];
                        foreach (get_sites(array('number' => 500)) as $site) {
                            switch_to_blog($site->blog_id);

                            self::debug(__METHOD__.'update_setting_var:redis_cache_onn='.$redis_cache_onn, ['multisite'=>get_site_url()]);

                            self::update_setting_var('redis_cache_onn', $redis_cache_onn);
                            restore_current_blog();
                        }
                    }

                    // display notice for network
                    update_option(self::$db_notice.'_reload_network', $doreload ? 1 : 3);
                }
            }

            self::wp_cache_delete();

            // 2 = reset with current changes
            self::schedule_cron_purge(2);
            self::schedule_cron_preload(2);
            self::schedule_cron_rcstats(2);

            // update rcapi push
            $referer = wp_get_raw_referer();
            if (!empty($referer) && preg_match('@#runcache-rules?$@', $referer, $mm)) {
                add_action('shutdown', [__CLASS__, 'rcapi_push'], self::$ordlast);
            }

            // fetch stats if stats is empty
            add_action('shutdown', [__CLASS__, 'rcapi_fetch_stats'], self::$ordlast);

            // check dropin
            add_action('shutdown', [__CLASS__, 'try_setup_dropin'], self::$ordlast);
        }
    }

    /**
     * callback_register_setting.
     */
    public static function callback_register_setting()
    {
        register_setting(
            self::$slug,
            self::$db_setting,
            [__CLASS__, 'update_setting']
        );
    }

    /**
     * callback_notices.
     */
    public static function callback_notices()
    {
        if (!is_admin()) {
            return;
        }

        if (defined('DOING_AUTOSAVE') || self::is_ajax() ) {
            return;
        }

        if (self::can_manage_options()) {
            add_action('all_admin_notices', [__CLASS__, 'callback_compatibility'], self::$ordlast);
            add_action('all_admin_notices', [__CLASS__, 'callback_use_permalink'], self::$ordlast);
            add_action('all_admin_notices', [__CLASS__, 'callback_redis_connect'], self::$ordlast);
            add_action('all_admin_notices', [__CLASS__, 'callback_action_notice'], self::$ordlast);
        }
    }

    /**
     * callback_compatibility.
     */
    public static function callback_compatibility()
    {
        if (self::maybe_deactivate_runcache_purger()) {
            $msg  = esc_html__('RunCache Purger has been automatically deactivated due to superseded by the RunCloud Hub Plugin', 'runcloud-hub');
            echo '<div id="rc-notice" class="notice notice-info is-dismissible">';
            echo '<p>';
            if (!self::is_ourscreen()) {
                echo '<strong>' . self::$name . ':</strong>&nbsp;' . esc_html($msg);
            } else {
                echo '<strong>' . esc_html($msg) . '</strong>';
            }
            echo '</p>';
            echo '</div>';
        }

        if ( self::is_ourscreen() ) {
            $__varfunc_redirect = function($opt = 1) {
                echo '<div id="rc-notice" class="notice notice-info is-dismissible">';
                echo '<p><strong>'.esc_html__('Updating... Please wait...', 'runcloud-hub').'</strong></p>';
                echo '</div>';

                echo '<script type="text/javascript" id="' . self::$slug . '-dropinrefresh">';
                echo '(function(){';
                if ( 1 === $opt ) {
                    echo 'var url = window.location.href.replace(/#/,"&rcpage=");';
                } else {
                    echo 'var url = window.location.href.replace(/&rcpage=/,"#");';
                }
                echo 'window.setTimeout(function() { window.location.replace(url); }, 1);';
                echo '})();';
                echo '</script>';
                self::fastcgi_close();
            };

            $__varfunc_flush_keys = function() {
                delete_option(self::$db_notice.'_reload');
                delete_option(self::$db_notice.'_reload_network');
            };

            $__varfunc_display_notice = function() {
                echo '<div id="rc-notice" class="notice notice-success is-dismissible">';
                echo '<p><strong>'.esc_html__('Settings saved.', 'runcloud-hub').'</strong></p>';
                echo '</div>';
            };

            $key = ( is_network_admin() ? '_reload_network' : '_reload' );
            $token = get_option(self::$db_notice.$key);
            if ( '3' === $token ) {
                $__varfunc_flush_keys();
                $__varfunc_display_notice();

            } elseif ( '2' === $token ) {
                update_option(self::$db_notice.$key, 3);
                $__varfunc_redirect(2);

            } elseif ( '1' === $token ) {
                update_option(self::$db_notice.$key, 2);
                $__varfunc_redirect(1);

            } else {
                $__varfunc_flush_keys();
            }
        }
    }

    /**
     * callback_use_permalink.
     */
    public static function callback_use_permalink()
    {
        if (get_option('permalink_structure')) {
            return;
        }
        if(function_exists('get_current_screen')) {
            $screen = get_current_screen();
            if (!empty($screen->id) && $screen->id === 'options-permalink') {
                return;
            }
        }

        $msg = esc_html__('Server-side page caching requires custom permalink structure to work properly.', 'runcloud-hub');
        echo '<div id="rc-redis-notice" class="notice notice-error">';
        echo '<p>';
        if (!self::is_ourscreen()) {
            echo '<strong>' . esc_html(self::$name) . ':</strong>&nbsp;' . esc_html($msg);
        } 
        else {
            echo '<strong>' . esc_html($msg) . '</strong>';
        }
        echo '</p>';
        echo '<p><a class="button" href="'.esc_url(admin_url('options-permalink.php')).'">'.esc_html__('Go To Permalink Settings', 'runcloud-hub').'</a></p>';
        echo '</div>';
    }

    /**
     * callback_redis_connect.
     */
    public static function callback_redis_connect()
    {
        if (!self::redis_is_enabled()) {
            return;
        }
        if (!self::is_dropin_hub()) {
            return;
        }
        if (self::redis_is_connect()) {
            return;
        }
        $msg = esc_html__('Redis Object Cache issue - Redis is not connected!', 'runcloud-hub');
        echo '<div id="rc-redis-notice" class="notice notice-error">';
        echo '<p>';
        if (!self::is_ourscreen()) {
            echo '<strong>' . esc_html(self::$name) . ':</strong>&nbsp;' . esc_html($msg);
        } 
        else {
            echo '<strong>' . esc_html($msg) . '</strong>';
        }
        echo '</p>';
        if (self::redis_can_enable()) {
            echo '<p>'.esc_html__('Please check Redis service in your server or disable Redis object cache.', 'runcloud-hub').'</p>';
        }
        else {
            echo '<p>'.esc_html__('Please contact site administrator to check Redis object cache.', 'runcloud-hub').'</p>';
        }
        echo '</div>';
    }

    /**
     * get_action_link.
     */
    private static function get_action_link($type)
    {
        $tag     = 'action';
        $referer = self::append_wp_http_referer();
        if (!is_admin()) {
            $referer .= '&__notadmin=1';
        }
        else {
            $referer .= '&__admin=1';
        }
        return wp_nonce_url(admin_url('admin-post.php?action=' . self::get_hash(self::$slug . '_' . $tag) . '&type=' . $type . $referer), $tag . '_' . $type);
    }

    /**
     * get_setting.
     */
    public static function get_setting($key = '', $blog_id = '')
    {
        if ($blog_id && is_multisite()) {
            switch_to_blog($blog_id);
        }

        $options = get_option(self::$db_setting, self::default_settings());
        if (!empty($options) && is_array($options)) {
            $options = array_merge(self::default_settings(), $options);
        } else {
            $options = [];
        }

        if (!empty($key)) {
            if (isset($options[$key])) {
                $return = $options[$key];
            }
            else {
                $return = null;
            }
        }
        else {
            $return = $options;
        }

        if ($blog_id && is_multisite()) {
            restore_current_blog();
        }

        return $return;
    }

    /**
     * dump_setting.
     */
    public static function dump_setting($all = true)
    {
        $__varfunc_options = function () {
            $options = get_option(self::$db_setting, self::default_settings());
            if (!empty($options) && is_array($options)) {
                $options = array_merge(self::default_settings(), $options);
            } else {
                $options = [];
            }
            return $options;
        };

        if (is_multisite()) {
            if ($all) {
                $results = [];
                $num = 0;
                foreach (get_sites(array('number' => 500)) as $site) {
                    $num++;
                    switch_to_blog($site->blog_id);
                    $results[$num]['blog_id'] = $site->blog_id;
                    $results[$num]['url']     = home_url();
                    $results[$num]['options'] = $__varfunc_options();
                    restore_current_blog();
                }
                return $results;
            }
        }

        return $__varfunc_options();
    }

    /**
     * set_settings.
     */
    public static function reset_purge_action()
    {
        self::$is_purge_cache_home    = false;
        self::$is_purge_cache_content = false;
        self::$is_purge_cache_archive = false;
        self::$is_purge_cache_urlpath = false;
        self::$is_run_preload         = false;
        self::$is_redis_debug         = false;
        self::$is_html_footprint      = false;

        $options = self::get_setting();
        if (!empty($options)) {
            if (!empty($options['url_path_onn'])) {
                self::$is_purge_cache_urlpath = true;
            }

            if (!empty($options['preload_onn'])) {
                self::$is_run_preload = true;
            }

            if (!empty($options['redis_debug_onn'])) {
                self::$is_redis_debug = true;
            }

            if (!empty($options['html_footprint_onn'])) {
                self::$is_html_footprint = true;
            }
        }
    }

    /**
     * setting_cast_int.
     */
    private static function setting_cast_int($input)
    {
        if (!empty($input) && is_array($input)) {
            foreach ($input as $key => $val) {
                if (preg_match('/.*(_onn|_int|_unt)$/', $key)) {
                    $input[$key] = (int) $val;
                }
            }
        }
        return $input;
    }

    /**
     * sanitize_input.
     */
    private static function sanitize_input($input)
    {
        if (!empty($input) && is_array($input)) {
            $input = self::setting_cast_int($input);

            foreach ($input as $key => $val) {
                if (preg_match('/.*_int$/', $key)) {
                    if (('schedule_purge_int' === $key || 'preload_schedule_int' === $key) && empty($val)) {
                        $val = 1;
                    }

                    $key_prefix = str_replace('_int', '', $key);
                    $unt_key    = $key_prefix . '_unt';

                    if (isset($input[$unt_key])) {
                        $unt_key_var         = $key_prefix . '_var';
                        $input[$unt_key_var] = (0 !== $val ? ((int) $val * (int) $input[$unt_key]) : 0);
                    }
                } elseif ( 'rcapi_webapp_id' === $key ) {
                    $key = trim($key);
                    $input[$key] = (self::is_num($val) ? (int) $val : '');
                } elseif ( 'rcapi_key' === $key || 'rcapi_secret' === $key ) {
                    $input[$key] = trim($val);
                } elseif (preg_match('/.*_mch$/', $key) && !is_array($val)) {
                    $list = explode("\n", str_replace(["\n", "\r"], "\n", $val));
                    $list = array_unique($list);
                    foreach ($list as $num => $lst) {
                        if (empty($lst)) {
                            unset($list[$num]);
                        }

                        if ('url_path_mch' === $key || 'exclude_url_mch' === $key || 'preload_path_mch' === $key) {
                            $path = wp_parse_url($lst, PHP_URL_PATH);
                            if (!empty($path)) {
                                $path = strtolower($path);
                                if ('/' !== $path) {
                                    $list[$num] = $path;
                                } else {
                                    unset($list[$num]);
                                }
                            }
                        }
                    }
                    if (!empty($list) && is_array($list)) {
                        $list = array_values($list);
                    }

                    $input[$key] = (is_array($list) && count($list) > 0 ? $list : []);
                } elseif ('redis_prefix' === $key && empty($val)) {
                    $input[$key] = self::redis_key_prefix();
                } elseif ('redis_port' === $key && !self::is_num($val)) {
                    unset($input[$key]); // reset default
                } elseif ('cache_key_extra_var' === $key) {
                    $val = preg_replace('@[^A-Za-z0-9_$]@', '', trim($val));
                    if (empty($val)) {
                        $input['cache_key_extra_onn'] = 0;
                    } else {
                        if ( '$' !== substr($val, 0, 1) ) {
                            $val = '$' . $val;
                        }
                    }
                    $input[$key] = $val;
                }
            }
        }

        return $input;
    }

    /**
     * update_setting.
     */
    public static function update_setting($input)
    {
        $options = array_merge(self::default_settings(), self::get_setting());
        if (!empty($input) && is_array($input)) {
            foreach ($options as $key => $val) {
                if (preg_match('/.*_onn$/', $key)) {
                    if (!isset($input[$key])) {
                        $input[$key] = 0;
                    }
                }
            }
        } else {
            $input = self::default_settings();
        }

        $input                = self::sanitize_input($input);
        $options              = array_merge($options, $input);
        $options['timestamp'] = gmdate('Y-m-d H:i:s') . ' UTC';

        // rcapi key/secret only for main site
        if (!self::is_main_site()) {
            $options['rcapi_key']    = '';
            $options['rcapi_secret'] = '';
            $options['rcapi_webapp_id'] = '';
        }

        if (isset($options['stats_onn'])) {
            $options['stats_schedule_onn'] = $options['stats_onn'];
        }

        if (empty($options['stats_transfer_onn']) && empty($options['stats_health_onn'])) {
            $options['stats_onn'] = 0;
        }

        return $options;
    }

    /**
     * update_setting_var.
     */
    public static function update_setting_var($key, $value)
    {
        $options = self::get_setting();
        if (isset($options[$key])) {
            $options[$key]          = $value;
            $options                = self::sanitize_input($options);
            $options['timestamp']   = gmdate('Y-m-d H:i:s') . ' UTC';
            return update_option(self::$db_setting, $options);
        }
        return false;
    }

    /**
     * update_setting_cli.
     */
    public static function update_setting_cli($input)
    {
        $options                = self::get_setting();
        $input                  = self::sanitize_input($input);
        $options                = array_merge($options, $input);
        $options['timestamp']   = gmdate('Y-m-d H:i:s') . ' UTC';
        return update_option(self::$db_setting, $options);
    }

    /**
     * register_admin_hooks.
     */
    public static function register_admin_hooks()
    {
        if (!self::$optpage) {
            add_filter('plugin_action_links_' . self::$hook, [__CLASS__, 'callback_links'], self::$ordfirst);
        }
        add_action('admin_menu', [__CLASS__, 'callback_page'], self::$ordfirst);

        if (self::is_plugin_active_for_network(self::$hook)) {
            add_action('network_admin_menu', [__CLASS__, 'callback_network_page'], self::$ordfirst);
        }

        add_action('admin_enqueue_scripts', [__CLASS__, 'admin_assets'], self::$ordlast);

        add_action('admin_bar_menu', [__CLASS__, 'callback_bar_menu'], self::$ordlast);
        add_action('update_option_' . self::$db_setting, [__CLASS__, 'after_update_setting'], self::$ordlast, 2);
        add_action('admin_init', [__CLASS__, 'callback_register_setting'], self::$ordfirst);
        add_action('admin_post_' . self::get_hash(self::$slug . '_action'), [__CLASS__, 'callback_action'], self::$ordlast);
        add_action('admin_post_' . self::$slug . '_deactivate_plugin', [__CLASS__, 'callback_deactivate_plugin']);
        add_action('plugins_loaded', [__CLASS__, 'callback_notices'], self::$ordfirst);

        // autologin
        add_action('init', [__CLASS__, 'callback_magiclink_login'], self::$ordlast);

        // cron
        add_action('cron_purge_' . self::get_hash(self::$slug . 'cron_purge'), [__CLASS__, 'callback_cron_purge'], self::$ordfirst);
        add_action('cron_preload_' . self::get_hash(self::$slug . 'cron_preload'), [__CLASS__, 'callback_cron_preload'], self::$ordfirst);
        add_action('cron_rcstats_' . self::get_hash(self::$slug . 'cron_rcstats'), [__CLASS__, 'callback_cron_rcstats'], self::$ordfirst);

        // preload
        add_action('admin_post_' . self::get_hash(self::$slug . '_preload'), [__CLASS__, 'callback_view_preload'], self::$ordfirst);
        add_action('preload_batch_' . self::get_hash(self::$slug . '_preload_batch'), [__CLASS__, 'callback_preload_batch'], self::$ordfirst);

        // network
        add_action('admin_post_' . self::get_hash(self::$slug . '_network'), [__CLASS__, 'callback_view_network'], self::$ordfirst);

        // try to auto-fix all drop-in issue
        add_action('admin_head', [__CLASS__, 'try_fix_dropin'], self::$ordlast);
    }

    /**
     * get_user_ip.
     */
    private static function get_user_ip()
    {
        foreach ([
            'HTTP_CF_CONNECTING_IP',
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_X_REAL_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR',
        ] as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = explode(',', $_SERVER[$key]);
                $ip = end($ip);

                if (false !== filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return '0.0.0.0';
    }

    /**
     * get_user_agent.
     */
    private static function get_user_agent($fallback_default = false)
    {
        $comp_ua    = '(compatible; RunCloud-Plugin ' . self::$version . '; +https://runcloud.io)';
        $default_ua = 'Mozilla/5.0 ' . $comp_ua;
        $ua         = '';
        if (!empty($_SERVER['HTTP_USER_AGENT'])) {
            if (false === strpos($_SERVER['HTTP_USER_AGENT'], 'RunCloud-Plugin')) {
                $ua = $_SERVER['HTTP_USER_AGENT'] . ' ' . $comp_ua;
            } else {
                $ua = $_SERVER['HTTP_USER_AGENT'];
            }
        }

        if ($fallback_default && empty($ua)) {
            $ua = $default_ua;
        }

        return $ua;
    }

    /**
     * __curl_resolve_loopback.
     */
    public static function __curl_resolve_loopback($handle, $r, $url)
    {
        $myhost = parse_url(get_site_url(), PHP_URL_HOST);

        $url_host   = parse_url($url, PHP_URL_HOST);
        $url_scheme = parse_url($url, PHP_URL_SCHEME);
        $url_port   = parse_url($url, PHP_URL_PORT);

        if ($myhost !== $url_host) {
            return;
        }

        $port = 80;

        if ('https' === $url_scheme) {
            $port = 443;
        }

        if (!empty($url_port) && $url_port !== $port) {
            $port = $url_port;
        }

        curl_setopt(
            $handle,
            CURLOPT_RESOLVE,
            [
                $url_host . ':' . $port . ':127.0.0.1',
            ]
        );

        curl_setopt(
            $handle,
            CURLOPT_DNS_USE_GLOBAL_CACHE, 
            false
        );
    }

    /**
     * purge_request.
     */
    private static function purge_request($url, $options = [])
    {
        if (self::is_defined_halt()) {
            return false;
        }

        $hostname = parse_url($url, PHP_URL_HOST);

        static $done = [];
 
        if (isset($done[$hostname][$url])) {
            return;
        }

        $args = [
            'method'      => 'GET',
            'timeout'     => 10,
            'redirection' => 5,
            'httpversion' => '1.1',
            'user-agent'  => self::get_user_agent(),
            'blocking'    => true,
            'headers'     => ['Host' => $hostname],
            'cookies'     => [],
            'body'        => null,
            'compress'    => false,
            'decompress'  => true,
            'sslverify'   => false,
            'stream'      => false,
            'filename'    => null,
        ];

        if (!empty($options) && is_array($options)) {
            $args = array_merge($args, $options);
        }

        if ($args['blocking'] === false) {
            $args['timeout'] = 0.01;
        }

        $return = [
            'blocking' => $args['blocking'],
            'code'   => 'X',
            'status' => '',
            'host'   => $args['headers']['Host'],
            'url'    => $url,
            'method' => $args['method'],
        ];

        add_action('http_api_curl', [__CLASS__, '__curl_resolve_loopback'], self::$ordlast, 3);

        $response = wp_remote_request($url, $args);

        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code === 403) {
            if (false !== strpos($url, 'http://')) {
                $url = str_replace('http://', 'https://', $url);
                $response = wp_remote_request($url, $args);
                $return['url'] = $url;
                $return['note'] = 'retry code : '.$response_code;
            }
        }

        remove_action('http_api_curl', [__CLASS__, '__curl_resolve_loopback'], self::$ordfirst);

        $response_code = wp_remote_retrieve_response_code($response);
        if (is_wp_error($response)) {
            $return['code']   = $response_code;
            $return['status'] = $response->get_error_message();
            self::define_halt();
        }
        else {
            $response_header = (is_object($response['headers']) ? (array) $response['headers'] : null);
            $return['header'] = $response_header;
            $return['code'] = $response_code;
            $cache_type_lc = wp_remote_retrieve_header( $response, 'x-runcache-type' );
            $cache_type_uc = wp_remote_retrieve_header( $response, 'X-RunCache-Type' );
            $cache_type = !empty($cache_type_lc) ? $cache_type_lc : $cache_type_uc;
            if (!empty($cache_type)) {
                $return['cache_type'] = $cache_type;
                if (self::is_srcache() && $cache_type === 'native') {
                    $return['code_response'] = $response_code;
                    $return['code'] = 88;
                    $return['status'] = esc_html__('Purge cache failed - Incorrect cache method detected!', 'runcloud-hub');
                }
                elseif (!self::is_srcache() && $cache_type !== 'native') {
                    $return['code_response'] = $response_code;
                    $return['code'] = 88;
                    $return['status'] = esc_html__('Purge cache failed - Incorrect cache method detected!', 'runcloud-hub');
                }
                if (self::is_debug()) {
                    $return['response'] = $response['body'];
                }
            }
            else {
                if ($response_code === 200 || $response_code === 404) {
                    $return['code_response'] = $response_code;
                    $return['code'] = 77;
                    $return['status'] = esc_html__('Purge cache failed - No server-side page caching detected!', 'runcloud-hub');
                }
            }
            if (self::is_debug()) {
                $response_body = str_replace(array("\r", "\n"), '', $response['body']);
                $response_body = str_replace(array("\t", '  ', '    '), ' ', $response_body);
                $response_body = substr($response_body, 0, 1000);
                $return['response'] = $response_body;
            }
        }

        if ($args['blocking'] === false) {
            $return['status'] = esc_html__('Purge cache was initiated and will run shortly', 'runcloud-hub');
        }

        if (empty($return['status'])) {
            switch ($return['code']) {
                case '200':
                    // Successful purge
                    $return['status'] = esc_html__('Purge cache was successful', 'runcloud-hub');
                    break;
                case '412':
                    // Preconditioned failed
                    $return['status'] = esc_html__('Purge cache was skipped (no cache)', 'runcloud-hub');
                    break;
                case '400':
                    // Request Forbidden
                    $return['status'] = sprintf(esc_html__('Purge cache failed - Request Forbidden (%s)', 'runcloud-hub'), $response_code);
                    break;
                case '403':
                    // Request Forbidden
                    $return['status'] = sprintf(esc_html__('Purge cache failed - Request Forbidden (%s)', 'runcloud-hub'), $response_code);
                    break;
                case '404':
                    // Request Not Found
                    $return['status'] = sprintf(esc_html__('Purge cache failed - Request Not Found (%s)', 'runcloud-hub'), $response_code);
                    break;
                case '405':
                    // Method Not Allowed
                    $return['status'] = sprintf(esc_html__('Purge cache failed - Method Not Allowed (%s)', 'runcloud-hub'), $response_code);
                    self::define_halt();
                    break;
                default:
                    $return['status'] = sprintf(esc_html__('Purge cache failed - Error Code %s', 'runcloud-hub'), $response_code);
                    self::define_halt();
            }
        }

        self::debug(__METHOD__, $return);

        $done[$hostname][$url] = 1;

        return $return;
    }

    /**
     * nginx_purge_type.
     */
    public static function nginx_purge_type()
    {
        if (self::is_srcache()) {
            return 'srcache';
        }

        return (self::is_nginx() ? 'fastcgi' : 'proxy');
    }

    /**
     * nginx_purge_all.
     */
    private static function nginx_purge_all($type = null, $proto = null, $run_action = true)
    {
        if (self::$done_purgeall) {
            return false;
        }

        if (self::is_defined_halt()) {
            return false;
        }

        $plugins = [];
        if ( $run_action ) {
            // always clear cache from other cache plugin first before purge all
            $plugins = self::purge_cache_known_plugins();
            // hook before purge all
            do_action('runcloud_purge_nginx_cache_before');
        }

        $type = (!empty($type) ? $type : self::nginx_purge_type());

        if (empty($proto) && 'http' !== $proto && 'https' !== $proto) {
            $proto = (self::is_wp_ssl() ? 'https' : 'http');
        }

        if ( !is_multisite() ) {
            $hostname = parse_url(home_url(), PHP_URL_HOST);
            $purge_prefix  = self::$purge_prefix_all;
            $request_query = $proto . '://' . $hostname . '/' . $purge_prefix . '-' . $type;
            $return = self::purge_request($request_query, ['method' => 'PURGE']);
        }
        else {
            $url = home_url('/');
            $hostname = parse_url($url, PHP_URL_HOST);
            $purge_prefix  = self::$purge_prefix_var;
            $request_query = str_replace($hostname, $hostname . '/' . $purge_prefix . '-' . $type . '/', $url) . '*';
            $request_query = str_replace($purge_prefix . '-' . $type . '//', $purge_prefix . '-' . $type . '/', $request_query);
            $return = self::purge_request($request_query, ['method' => 'GET']);
        }

        self::$done_purgeall = true;

        if ( $run_action ) {
            // hook after purge all
            do_action('runcloud_purge_nginx_cache_after');
            // old hook, backward compatibility
            do_action('runcloud_purge_nginx_cache');
        }

        if ( self::redis_is_enabled() || self::redis_is_connect() ) {
            self::purge_cache_redis();
        }

        if (!empty($return['code']) && (int)$return['code'] === 200) {
            if (!empty($plugins)) {
                $return['status'] = sprintf(esc_html__('Purge all %s and NGINX page cache was successful', 'runcloud-hub'), implode(', ', $plugins));
            }
            else {
                $return['status'] = esc_html__('Purge all NGINX page cache was successful', 'runcloud-hub');
            }
        }

        return $return;
    }

    /**
     * nginx_purge_all_sites.
     */
    private static function nginx_purge_all_sites($type = null, $proto = null, $run_action = true)
    {
        if (self::$done_purgesiteall) {
            return false;
        }

        if (self::is_defined_halt()) {
            return false;
        }

        $type = (!empty($type) ? $type : self::nginx_purge_type());

        if (empty($proto) && 'http' !== $proto && 'https' !== $proto) {
            $proto = (self::is_wp_ssl() ? 'https' : 'http');
        }

        $blog_id = self::get_main_site_id();
        $url = home_url('/');
        $hostname = parse_url($url, PHP_URL_HOST);
        $purge_prefix  = self::$purge_prefix_all;
        $request_query = $proto . '://' . $hostname . '/' . $purge_prefix . '-' . $type;
        $return = self::purge_request($request_query, ['method' => 'PURGE']);

        self::$done_purgesiteall = true;

        if ( self::redis_is_enabled() || self::redis_is_connect() ) {
            self::purge_cache_redis_all();
        }

        if ( $run_action && defined('WP_CLI') && WP_CLI) {
            if (is_multisite()) {
                foreach (get_sites(array('number' => 500)) as $site) {
                    switch_to_blog($site->blog_id);
                    // remove others cache
                    self::purge_cache_known_plugins();
                    // trigger others plugin
                    do_action('runcloud_purge_nginx_cache');
                    restore_current_blog();
                }
            }
        }

        if (!empty($return['code']) && (int)$return['code'] === 200) {
            $return['status'] = esc_html__('Purge all sites NGINX page cache was successful', 'runcloud-hub');
        }

        return $return;
    }

    /**
     * nginx_purge_cache_url.
     */
    private static function nginx_purge_cache_url($url, $wildcard = true, $blocking = true)
    {
        if (self::is_defined_halt()) {
            return false;
        }

        $hostname = parse_url($url, PHP_URL_HOST);

        $type          = self::nginx_purge_type();
        $purge_prefix  = self::$purge_prefix_var;
        $request_query = str_replace($hostname, $hostname . '/' . $purge_prefix . '-' . $type . '/', $url) . ( $wildcard ? '*' : '' );
        $request_query = str_replace($purge_prefix . '-' . $type . '//', $purge_prefix . '-' . $type . '/', $request_query);

        return self::purge_request($request_query, ['method' => 'GET', 'blocking' => $blocking]);
    }

    /**
     * callback_action.
     */
    public static function callback_action()
    {
        if (!self::can_manage_options()) {
            return;
        }

        if (!isset($_GET['type'], $_GET['_wpnonce'], $_GET['action']) ) {
            return;
        }

        $wp_referer = wp_get_referer();
        $get_type   = sanitize_text_field($_GET['type']);
        $get_nonce  = sanitize_text_field($_GET['_wpnonce']);
        $get_action = sanitize_text_field($_GET['action']);

        $type = explode('-', $get_type);
        $type = reset($type);
        $id   = explode('-', $get_type);
        $id   = end($id);

        $aname    = self::get_hash(self::$slug . '_action');
        $notadmin = (isset($_GET['__notadmin']) ? true : false);
        $status   = true;

        $req_status = '';

        if (wp_verify_nonce($get_nonce, 'action_' . $get_type)) {
            if ($aname === $get_action) {
                switch ($type) {
                    case 'purgeall':
                        $req_status = self::purge_cache_all();
                        break;
                    case 'purgesiteall':
                        $req_status = self::purge_cache_all_sites();
                        break;
                    case 'purgepost':
                        self::$is_purge_cache_content = true;
                        $options = self::get_setting();
                        if (!empty($options)) {
                            if (!empty($options['homepage_post_onn'])) {
                                self::$is_purge_cache_home = true;
                            }
                            if (self::$is_purge_cache_content && !empty($options['archives_content_onn'])) {
                                self::$is_purge_cache_archive = true;
                            }
                        }
                        $req_status = self::purge_cache_post($id);
                        break;
                    case 'purgeurl':
                        if ('/' === $wp_referer) {
                            $req_status = self::purge_cache_home();
                        } else {
                            $up = $wp_referer;
                            if ('/' === $up[0]) {
                                $up = home_url($up);
                            }
                            $req_status = self::purge_cache_url($up);
                        }
                        break;
                    case 'purgehomepage':
                        $req_status = self::purge_cache_home();
                        break;
                    case 'purgeredis':
                        $req_status = self::purge_cache_redis();
                        break;
                    case 'purgeredisall':
                        $req_status = self::purge_cache_redis_all();
                        break;
                    case 'preload':
                        // clear all cache first but do not trigger background preload
                        self::$is_run_preload = false;
                        self::purge_cache_all();
                        // setup browser preload
                        $status = false;
                        $total = self::get_total_posts();
                        if ($total) {
                            update_option(
                                self::$db_preload,
                                [
                                    'num'    => 0,
                                    'total'  => $total,
                                    'type'   => 'admin',
                                ],
                                false
                            );
                        }
                        break;
                    case 'preload_cancel':
                        $status = false;
                        delete_option(self::$db_preload);
                        self::schedule_preload_batch(1);
                        self::debug(__METHOD__, esc_html__('Preload is cancelled', 'runcloud-hub'));
                        break;
                    case 'preload_run_admin':
                        $status = false;
                        $preload = get_option(self::$db_preload);
                        $preload['type'] = 'admin';
                        update_option(self::$db_preload, $preload, false);
                        self::schedule_preload_batch(1);
                        self::debug(__METHOD__, esc_html__('Preload switch from background to browser', 'runcloud-hub'));
                        break;
                    case 'preload_run_background':
                        $status = false;
                        $preload = get_option(self::$db_preload);
                        $preload['type'] = 'background';
                        update_option(self::$db_preload, $preload, false);
                        self::schedule_preload_batch();
                        self::debug(__METHOD__, esc_html__('Preload switch from browser to background', 'runcloud-hub'));
                        break;
                    case 'network_cancel':
                        $status = false;
                        delete_option(self::$db_network);
                        self::debug(__METHOD__, esc_html__('Network action is cancelled', 'runcloud-hub'));
                        break;
                    case 'fetchstats':
                        $req_status = self::rcapi_fetch_stats(true, true);
                        break;
                    case 'installdropin':
                        $req_status = self::reinstall_dropin(true);
                        break;
                    case 'reset':
                        $req_status = self::reinstall_options(false, true);
                        break;
                    case 'switchpurger':
                        $req_status = self::switch_cache_type(true);
                        break;
                    default:
                        $req_status = [
                            'code' => 0,
                            'status' => esc_html__('Failed, no valid action was specified', 'runcloud-hub'),
                        ];
                }
            }
            else {
                $req_status = [
                    'code' => 0,
                    'status' => esc_html__('Failed, no valid action was specified', 'runcloud-hub'),
                ];
            }
        }
        else {
            $req_status = [
                'code' => 0,
                'status' => esc_html__('Failed, security nonce was invalid', 'runcloud-hub'),
            ];
        }

        self::reset_purge_action();

        if ($status) {
            if (empty($req_status)) {
                $req_status = [
                    'code' => 0,
                    'status' => '',
                ];
            }
            update_option(self::$db_notice, $req_status);
            if ($notadmin) {
                $wp_referer = remove_query_arg('fl_builder', $wp_referer);
                delete_option(self::$db_notice);
                wp_safe_redirect(esc_url_raw($wp_referer));
                self::close_exit();
            }
            else {
                if ($type == 'purgesiteall' && self::is_known_plugins_active()) {
                    wp_safe_redirect(network_admin_url('settings.php?page=' . self::$slug));
                    self::close_exit();
                }
            }
        }

        wp_safe_redirect(esc_url_raw($wp_referer));
        self::close_exit();
    }

    /**
     * callback_action_notice.
     */
    public static function callback_action_notice()
    {
        $req_status = get_option(self::$db_notice);
        if (!empty($req_status) && is_array($req_status)) {
            if (200 === $req_status['code'] || 412 === $req_status['code']) {
                $notice_type = 'success';
                if (!empty($req_status['status'])) {
                    $msg = $req_status['status'];
                }
                else {
                    $msg = esc_html__('Success', 'runcloud-hub');
                }
            }
            else {
                $notice_type = 'error';
                if (!empty($req_status['status'])) {
                    $msg = $req_status['status'];
                }
                else {
                    $msg = esc_html__('Unknown Error', 'runcloud-hub');
                }
            }

            if (isset($_GET['_rc_purge_ref'])) {
                $msg .= '.&nbsp; ' . esc_html__('Redirecting in 5 seconds...', 'runcloud-hub');
            }

            echo '<style>';

            echo '</style>';
            echo '<div id="rc-notice" class="notice notice-' . esc_attr($notice_type) . ' is-dismissible">';
            echo '<p>';
            if (!self::is_ourscreen()) {
                echo '<strong>' . esc_html(self::$name) . ':</strong>&nbsp;' . esc_html($msg);
            } 
            else {
                echo '<strong>' . esc_html($msg) . '</strong>';
            }
            if (isset($_GET['_rc_purge_ref'])) {
                echo '<noscript><meta http-equiv="refresh" content="5;url=' . esc_url(home_url('/').$_GET['_rc_purge_ref']) . '"></noscript>';
            }
            echo '</p>';
            if (88 === $req_status['code'] && !empty($req_status['cache_type'])) {
                if ($req_status['cache_type'] === 'native') {
                    $method_db = esc_html__('Redis Page Cache', 'runcloud-hub');
                    $method_detected = esc_html__('FastCGI/Proxy Cache', 'runcloud-hub');
                }
                else {
                    $method_db = esc_html__('FastCGI/Proxy Cache', 'runcloud-hub');
                    $method_detected = esc_html__('Redis Page Cache', 'runcloud-hub');
                }
                echo '<p>';
                printf( esc_html__('Please try again in few minutes if you just changed the method from %1$s to %2$s in your server.', 'runcloud-hub'), $method_db, $method_detected );
                echo '</p>';
                echo '<p>';
                printf( esc_html__('Or please switch the cache purger method to %1$s if you use %1$s in your server.', 'runcloud-hub'), $method_detected );
                echo '</p>';
                echo '<p><a class="button" href="'.esc_url_raw( self::get_action_link('switchpurger') ).'">'.esc_html(sprintf(esc_html__( 'Switch Cache Purger To %s', 'runcloud-hub'), $method_detected)).'</a></p>';
            }
            echo '</div>';
        }
        delete_option(self::$db_notice);
    }

    /**
     * callback_deactivate_plugin,
     */
    public static function callback_deactivate_plugin()
    {
        if (!wp_verify_nonce($_GET['_wpnonce'], 'deactivate_plugin')) {
            wp_nonce_ays('');
        }

        deactivate_plugins($_GET['plugin']);
        self::wp_cache_delete();

        wp_safe_redirect(wp_get_referer());
        self::close_exit();
    }

    /**
     * get_post_terms_urls.
     */
    private static function get_post_terms_urls($post_id)
    {
        $urls       = [];
        $taxonomies = get_object_taxonomies(get_post_type($post_id), 'objects');

        foreach ($taxonomies as $taxonomy) {
            if (!$taxonomy->public) {
                continue;
            }

            if (class_exists('WooCommerce')) {
                if ('product_shipping_class' === $taxonomy->name) {
                    continue;
                }
            }

            $terms = get_the_terms($post_id, $taxonomy->name);

            if (!empty($terms)) {
                foreach ($terms as $term) {
                    $term_url = get_term_link($term->slug, $taxonomy->name);
                    if (!is_wp_error($term_url)) {
                        $urls[] = $term_url;
                    }
                }
            }
        }

        return $urls;
    }

    /**
     * purge_cache_redis.
     */
    public static function purge_cache_redis()
    {
        static $done_purgeredis = false;
        $ok                 = false;
        $is_dropin          = self::is_dropin_exists();
        $is_connect         = self::redis_is_connect();
        $is_enabled         = self::redis_is_enabled();

        if ($is_connect) {
            $ok = wp_cache_flush();

            if (self::is_wp_cli()) {
                self::late_purge_cache_redis();
            }

            if ( $ok ) {
                $status = esc_html__('Purge Redis object cache was successful', 'runcloud-hub');
            }
            else {
                $status = esc_html__('Purge Redis object cache failed - Request not found', 'runcloud-hub');
            }
        }
        else {
            if ( $is_enabled ) {
                $status = esc_html__('Purge Redis object cache failed - Redis is not connected', 'runcloud-hub');
            }
            else {
                $status = esc_html__('Purge Redis object cache failed - Redis is not enabled', 'runcloud-hub');
            }
        }

        $return = [
            'code'       => ($ok ? 200 : 404),
            'status'     => $status,
            'is_redis'   => 1,
            'is_dropin'  => ($is_dropin ? 1 : 0),
            'is_connect' => ($is_connect ? 1 : 0),
            'is_enabled' => ($is_enabled ? 1 : 0),
        ];

        self::debug(__METHOD__, $return);

        if (!$done_purgeredis) {
            // trigger others plugin
            do_action('runcloud_purge_redis_cache');
            $done_purgeredis = true;
        }

        return $return;
    }

    /**
     * purge_cache_redis_all.
     */
    public static function purge_cache_redis_all()
    {
        static $done_purgeredisall = false;
        $ok                 = false;
        $is_dropin          = self::is_dropin_exists();
        $is_connect         = self::redis_is_connect();
        $is_enabled         = self::redis_is_enabled();

        if (self::is_wp_cli() || self::can_manage_network_options()) {
            if (function_exists('wp_cache_flush_sites')) {
                if ($is_connect) {
                    $ok = wp_cache_flush_sites();

                    if (self::is_wp_cli()) {
                        self::late_purge_cache_redis_all();
                    }

                    if ( $ok ) {
                        $status = esc_html__('Purge all sites Redis object cache was successful', 'runcloud-hub');
                    }
                    else {
                        $status = esc_html__('Purge all sites Redis object cache failed - Request not found', 'runcloud-hub');
                    }
                }
                else {
                    if ( $is_enabled ) {
                        $status = esc_html__('Purge all sites Redis object cache failed - Redis is not connected', 'runcloud-hub');
                    }
                    else {
                        $status = esc_html__('Purge all sites Redis object cache failed - Redis is not enabled', 'runcloud-hub');
                    }
                }
            }
            else {
                $status = esc_html__('Purge all sites Redis object cache failed - Redis object cache is disabled on main site', 'runcloud-hub');
            }
        }
        else {
            $status = esc_html__('Purge all sites Redis object cache failed - For Super Administrator only', 'runcloud-hub');
        }

        $return = [
            'code'       => ($ok ? 200 : 404),
            'status'     => $status,
            'is_redis'   => 1,
            'is_dropin'  => ($is_dropin ? 1 : 0),
            'is_connect' => ($is_connect ? 1 : 0),
            'is_enabled' => ($is_enabled ? 1 : 0),
        ];

        self::debug(__METHOD__, $return);

        if (!$done_purgeredisall) {
            // trigger others plugin
            do_action('runcloud_purge_redis_cache_all');
            $done_purgeredisall = true;
        }

        return $return;
    }

    /**
     * purge_cache_url.
     */
    public static function purge_cache_url($url)
    {
        if (self::is_defined_halt()) {
            return;
        }

        $return = self::nginx_purge_cache_url($url);

        if (self::$is_run_preload) {
            self::fetch_preload($url, false);
        }

        return $return;
    }

    /**
     * purge_cache_home.
     */
    public static function purge_cache_home($blocking = true)
    {
        if (self::is_defined_halt()) {
            return;
        }

        $home_url = home_url('/');

        if (self::is_srcache()) {
            $return = self::nginx_purge_cache_url($home_url.':', true, $blocking);
        }
        else {
            $return = self::nginx_purge_cache_url($home_url, false, $blocking);
        }

        $show_on_front = get_option('show_on_front');
        if ( $show_on_front !== 'page' ) {
            if ( get_option('permalink_structure') ) {
                $home_paged_url = home_url('/page/');
                self::nginx_purge_cache_url($home_paged_url, true, false);
            }
        }

        if (self::$is_run_preload) {
            self::fetch_preload($home_url, false);
        }

        // run hook after purge home
        do_action('runcloud_purge_nginx_cache_home');

        return $return;
    }

    /**
     * purge_cache_all.
     */
    public static function purge_cache_all()
    {
        if (self::$done_purgeall) {
            return;
        }

        if (self::is_defined_halt()) {
            return;
        }

        self::debug(__METHOD__, esc_attr($_SERVER['REQUEST_URI']));

        $return = self::nginx_purge_all();

        if (self::$is_run_preload) {
            $total = self::get_total_posts();
            if ($total) {
                update_option(
                    self::$db_preload,
                    [
                        'num'    => 0,
                        'total'  => $total,
                        'type'   => 'background',
                    ],
                    false
                );
                self::schedule_preload_batch();
            }
        }

        return $return;
    }

    /**
     * purge_cache_all_noaction.
     */
    public static function purge_cache_all_noaction()
    {
        if (self::$done_purgeall) {
            return;
        }

        if (self::is_defined_halt()) {
            return;
        }

        self::debug(__METHOD__, esc_attr($_SERVER['REQUEST_URI']));

        // run nginx_purge_all but with run_action false
        // it is dedicated for purger hooks with better plugin integration
        $return = self::nginx_purge_all(null, null, false);

        if (self::$is_run_preload) {
            $total = self::get_total_posts();
            if ($total) {
                update_option(
                    self::$db_preload,
                    [
                        'status' => 'queue',
                        'num'    => 0,
                        'total'  => $total,
                        'type'   => 'background',
                    ],
                    false
                );
                self::schedule_preload_batch();
            }
        }

        return $return;
    }

    /**
     * purge_cache_all_sites.
     */
    public static function purge_cache_all_sites()
    {
        if (self::$done_purgesiteall) {
            return;
        }

        if (self::is_defined_halt()) {
            return;
        }

        $return = self::nginx_purge_all_sites();

        if (isset($_GET['__admin']) && self::is_known_plugins_active()) {
            $counts = wp_count_sites();
            $total = isset($counts['all']) ? (int) $counts['all'] : 0;
            if ($total > 0) {
                update_option(
                    self::$db_network,
                    [
                        'num'    => 0,
                        'total'  => $total,
                        'type'   => 'purge',
                    ],
                    false
                );
            }
        }

        return $return;
    }

    /**
     * purge_cache_on_upgrader.
     */
    public static function purge_cache_on_upgrader($wp_upgrader, $options)
    {
        if (self::$done_purgeall) {
            return;
        }

        if (self::is_defined_halt()) {
            return;
        }

        // exit early if options is empty
        $options = self::get_setting();
        if (empty($options)) {
            return;
        }

        if ( ! (bool) $options['purge_upgrader_onn'] ) {
            return;
        }

        self::debug(__METHOD__, 'event run');

        if (is_multisite()) {
            self::purge_cache_all_sites();
        }
        else {
            self::purge_cache_all();
        }
    }

    /**
     * purge_cache_on_plugin_activate.
     */
    public static function purge_cache_on_plugin_activate($plugin, $network_wide)
    {
        if (self::$done_purgeall) {
            return;
        }

        if (self::is_defined_halt()) {
            return;
        }

        // exit early if options is empty
        $options = self::get_setting();
        if (empty($options)) {
            return;
        }

        if ( ! (bool) $options['purge_plugin_onn'] ) {
            return;
        }

        self::debug(__METHOD__, 'event run');

        if ($network_wide) {
            self::purge_cache_all_sites();
        }
        else {
            self::purge_cache_all();
        }
    }

    /**
     * purge_cache_on_plugin_deactivate.
     */
    public static function purge_cache_on_plugin_deactivate($plugin, $network_deactivating)
    {
        if (self::$done_purgeall) {
            return;
        }

        if (self::is_defined_halt()) {
            return;
        }

        // exit early if options is empty
        $options = self::get_setting();
        if (empty($options)) {
            return;
        }

        if ( ! (bool) $options['purge_plugin_onn'] ) {
            return;
        }

        self::debug(__METHOD__, 'event run');

        if ($network_deactivating) {
            self::purge_cache_all_sites();
        }
        else {
            self::purge_cache_all();
        }
    }

    /**
     * purge_cache_on_theme_switch.
     */
    public static function purge_cache_on_theme_switch()
    {
        if (self::$done_purgeall) {
            return;
        }

        if (self::is_defined_halt()) {
            return;
        }

        // exit early if options is empty
        $options = self::get_setting();
        if (empty($options)) {
            return;
        }

        if ( ! (bool) $options['purge_theme_switch_onn'] ) {
            return;
        }

        self::debug(__METHOD__, 'event run');

        self::nginx_purge_all();
    }

    /**
     * purge_cache_on_permalink_update.
     */
    public static function purge_cache_on_permalink_update()
    {
        if (self::$done_purgeall) {
            return;
        }

        if (self::is_defined_halt()) {
            return;
        }

        // exit early if options is empty
        $options = self::get_setting();
        if (empty($options)) {
            return;
        }

        if ( ! (bool) $options['purge_permalink_onn'] ) {
            return;
        }

        self::debug(__METHOD__, 'event run');

        self::nginx_purge_all();
    }

    /**
     * purge_cache_on_customize_save.
     */
    public static function purge_cache_on_customize_save()
    {
        if (self::$done_purgeall) {
            return;
        }

        if (self::is_defined_halt()) {
            return;
        }

        // exit early if options is empty
        $options = self::get_setting();
        if (empty($options)) {
            return;
        }

        if ( ! (bool) $options['purge_customize_onn'] ) {
            return;
        }

        self::debug(__METHOD__, 'event run');

        self::nginx_purge_all();
    }

    /**
     * purge_cache_on_navmenu_update.
     */
    public static function purge_cache_on_navmenu_update()
    {
        if (self::$done_purgeall) {
            return;
        }

        if (self::is_defined_halt()) {
            return;
        }

        // exit early if options is empty
        $options = self::get_setting();
        if (empty($options)) {
            return;
        }

        if ( ! (bool) $options['purge_navmenu_onn'] ) {
            return;
        }

        self::debug(__METHOD__, 'event run');

        self::nginx_purge_all();
    }

    /**
     * purge_cache_on_sidebar_update.
     */
    public static function purge_cache_on_sidebar_update()
    {
        if (self::$done_purgeall) {
            return;
        }

        if (self::is_defined_halt()) {
            return;
        }

        // exit early if options is empty
        $options = self::get_setting();
        if (empty($options)) {
            return;
        }

        if ( ! (bool) $options['purge_sidebar_onn'] ) {
            return;
        }

        self::debug(__METHOD__, 'event run');

        self::nginx_purge_all();
    }

    /**
     * purge_cache_on_widget_update.
     */
    public static function purge_cache_on_widget_update( $obj )
    {
        if (self::$done_purgeall) {
            return $obj;
        }

        if (self::is_defined_halt()) {
            return $obj;
        }

        // exit early if options is empty
        $options = self::get_setting();
        if (empty($options)) {
            return $obj;
        }

        if ( ! (bool) $options['purge_widget_onn'] ) {
            return $obj;
        }

        self::debug(__METHOD__, 'event run');

        self::nginx_purge_all();

        return $obj;
    }

    /**
     * purge_cache_on_post_change.
     */
    public static function purge_cache_on_post_change($post_id) {

        if (!empty($post_id)) {
            return;
        }

        $post_data = get_post($post_id);
        if (!is_object($post_data)) {
            return;
        }

        if ('auto-draft' === $post_data->post_status
            || empty($post_data->post_type)
            || 'revision' === $post_data->post_type
            || 'attachment' === $post_data->post_type
            || 'nav_menu_item' === $post_data->post_type) {
            return;
        }

        // exit early if options is empty
        $options = self::get_setting();
        if (empty($options)) {
            return;
        }

        // purge post/page/CPT on published/removed
        if ( (bool) $options['content_publish_onn']) {
            self::$is_purge_cache_content = true;
        }

        self::debug(__METHOD__, ['post_id' => $post_id]);

        self::purge_cache_post($post_id);
    }

    /**
     * purge_cache_on_post_status_change.
     */
    public static function purge_cache_on_post_status_change($new_status, $old_status, $post) {
        $post_id = $post->ID;

        $post_data = get_post($post_id);
        if (!is_object($post_data)) {
            return;
        }

        if ('auto-draft' === $post_data->post_status
            || empty($post_data->post_type)
            || 'revision' === $post_data->post_type
            || 'attachment' === $post_data->post_type
            || 'nav_menu_item' === $post_data->post_type) {
            return;
        }

        // exit early if options is empty
        $options = self::get_setting();
        if (empty($options)) {
            return;
        }

        if ($new_status == 'publish' || $old_status == 'publish' || $new_status == 'future' || $old_status == 'future') {
            // purge post/page/CPT on published/removed
            if ( (bool) $options['content_publish_onn']) {
                self::$is_purge_cache_content = true;
            }

            // purge homepage on post updated/added
            if ($new_status != 'trash' && (bool) $options['homepage_post_onn']) {
                self::$is_purge_cache_home = true;
            }

            // purge homepage on post removed
            if ($new_status == 'trash' && (bool) $options['homepage_removed_onn']) {
                self::$is_purge_cache_home = true;
            }

            // purge archives when a purged content is triggered
            if (self::$is_purge_cache_content && (bool) $options['archives_content_onn']) {
                self::$is_purge_cache_archive = true;
            }
        }

        self::debug(__METHOD__, ['oldstatus' => $old_status, 'newstatus' => $new_status, 'post_id' => $post_id, 'post' => $post]);

        self::purge_cache_post($post_id);
    }

    /**
     * purge_cache_on_comment.
     */
    public static function purge_cache_on_comment($comment_id, $comment) {
        $oldstatus = '';
        $newstatus = $comment->comment_approved === '1' ? 'approved' : 'unapproved';

        self::purge_cache_on_comment_status_change($newstatus, $oldstatus, $comment);
    }

    /**
     * purge_cache_on_comment_status_change.
     */
    public static function purge_cache_on_comment_status_change($newstatus, $oldstatus, $comment) {
        $post_id = $comment->comment_post_ID;

        $post_data = get_post($post_id);
        if (!is_object($post_data)) {
            return;
        }

        $post_data = get_post($post_id);
        if (!is_object($post_data)) {
            return;
        }

        if ('auto-draft' === $post_data->post_status
            || empty($post_data->post_type)
            || 'revision' === $post_data->post_type
            || 'attachment' === $post_data->post_type
            || 'nav_menu_item' === $post_data->post_type) {
            return;
        }

        // exit early if options is empty
        $options = self::get_setting();
        if (empty($options)) {
            return;
        }

        // purge post/page/CPT when a comment is approved/unapproved/trashed
        if ((bool) $options['content_comment_approved_onn']) {
            self::$is_purge_cache_content = true;
        }

        // purge archive when a purged content is triggered
        if (self::$is_purge_cache_content && (bool) $options['archives_content_onn']) {
            self::$is_purge_cache_archive = true;
        }

        self::debug(__METHOD__, ['oldstatus' => $oldstatus, 'newstatus' => $newstatus, 'post_id' => $post_id, 'comment' => $comment]);

        self::purge_cache_post($post_id);
    }

    /**
     * purge_cache_post.
     */
    public static function purge_cache_post($post_id)
    {
        if (defined('DOING_AUTOSAVE') || self::is_defined_halt()) {
            return;
        }

        if ( empty($post_id) ) {
            return;
        }

        $post_data = get_post($post_id);
        if (!is_object($post_data)) {
            return;
        }

        if ('auto-draft' === $post_data->post_status
            || empty($post_data->post_type)
            || 'revision' === $post_data->post_type
            || 'attachment' === $post_data->post_type
            || 'nav_menu_item' === $post_data->post_type) {
            return;
        }

        self::debug(__METHOD__, ['post_id' => $post_id]);

        $return = '';

        $post_is_home = false;

        if ('page' === $post_data->post_type ) {
            $show_on_front = get_option('show_on_front');
            if ( $show_on_front === 'page' ) {
                $page_on_front_id = (int) get_option('page_on_front');
                if ($post_id == $page_on_front_id) {
                    $post_is_home = true;
                    $return = self::purge_cache_home();
                }
            }
        }

        // Purge Post/Page/CPT
        if ( self::$is_purge_cache_content && !$post_is_home ) {
            $purge_permalink = '';
            if ( 'publish' === $post_data->post_status ) {
                $purge_permalink = get_permalink( $post_id );
            }
            elseif ( 'trash' === $post_data->post_status ) {
                $purge_permalink = get_permalink( $post_id );
                $purge_permalink = str_replace('__trashed', '', $purge_permalink);
            }
            else {
                if (!function_exists('get_sample_permalink')) {
                    include_once ABSPATH . 'wp-admin/includes/post.php';
                }
                $permalink_structure = get_sample_permalink($post_id);
                if(!empty($permalink_structure[1]) && !empty($permalink_structure[0])) {
                    $purge_permalink = str_replace(array('%postname%', '%pagename%'), $permalink_structure[1], $permalink_structure[0]);
                }
            }

            if (!empty($purge_permalink)) {
                $return = self::nginx_purge_cache_url($purge_permalink);
                if (self::$is_run_preload) {
                    self::fetch_preload($purge_permalink, false);
                }
            }
        }

        // Purge Homepage
        if ( self::$is_purge_cache_home && !$post_is_home ) {
            self::purge_cache_home(false);
        }

        // Purge Archive
        if ( self::$is_purge_cache_archive ) {
            $options = self::get_setting();

            $purge_data = [];

            if ((bool) $options['archives_cpt_onn'] && !$post_is_home) {
                $post_type = $post_data->post_type;

                // purge blog posts page, if available
                if ($post_type === 'post') {
                    $show_on_front = get_option('show_on_front');
                    if ( $show_on_front === 'page' ) {
                        $page_for_posts_id = (int) get_option('page_for_posts');
                        if ('post' === $post_data->post_type && $page_for_posts_id > 0) {
                            $purge_blog = get_permalink($page_for_posts_id);
                            $purge_data[] = $purge_blog;
                        }
                    }
                }

                // purge custom post type archive page, if available
                if ($post_type != 'post' && $post_type != 'page') {
                    if (function_exists('get_post_type_archive_link')) {
                        $post_type_archive_link = get_post_type_archive_link($post_data->post_type);
                        if ($post_type_archive_link) {
                            $purge_data[] = $post_type_archive_link;
                        }
                    }
                }
            }

            // purge category / tag / custom taxonomy
            if ((bool) $options['archives_category_onn'] && !$post_is_home) {
                $purge_terms = self::get_post_terms_urls($post_id);
                if (!empty($purge_terms) && is_array($purge_terms)) {
                    $purge_data = array_merge($purge_data, $purge_terms);
                }
            }

            // purge author page
            if ((bool) $options['archives_author_onn'] && !$post_is_home) {
                $author_id = $post_data->post_author;
                if ($author_id) {
                    $author_link = get_author_posts_url($author_id);
                    if ($author_link) {
                        $purge_data[] = $author_link;
                    }
                }
            }

            // purge RSS feed page
            if ((bool) $options['archives_feed_onn']) {
                if ( current_theme_supports( 'automatic-feed-links' ) ) {
                    $feed_url = get_feed_link();
                    $home_url = home_url();
                    if ( $feed_url !== $home_url ) {
                        $purge_data[] = get_feed_link();
                        $purge_data[] = get_feed_link( 'comments_' );
                    }
                }
            }

            // run purge archive
            if (!empty($purge_data) && is_array($purge_data) && count($purge_data) > 0) {
                foreach ($purge_data as $url) {
                    self::nginx_purge_cache_url($url, true, false);
                }
            }
        }

        // Purge URL Path
        if (self::$is_purge_cache_urlpath) {
            $options = self::get_setting();
            if (!empty($options['url_path_onn']) && !empty($options['url_path_mch']) && is_array($options['url_path_mch'])) {
                foreach ($options['url_path_mch'] as $path) {
                    if ('/' === $path) {
                        continue;
                    }
                    $path         = strtolower($path);
                    $purge_url    = home_url(ltrim($path, '/'));
                    self::nginx_purge_cache_url($purge_url, true, false);
                }
            }
        }

        // run hook after purge post
        do_action('runcloud_purge_nginx_cache_post', $post_id);

        return $return;
    }

    /**
     * upgrade_plugin.
     */
    public static function upgrade_plugin()
    {
        if (self::is_defined_halt()) {
            return;
        }

        $ver = get_option(self::$db_version);
        if ($ver == self::$version) {
            return;
        }

        self::debug(__METHOD__, sprintf(esc_html__('Upgrade to v%s ', 'runcloud-hub'), self::$version));

        self::clean_setup();

        update_option(self::$db_version, self::$version);
    }

    /**
     * clean_setup.
     */
    public static function clean_setup()
    {
        if (is_multisite()) {
            self::purge_cache_all_sites();
        }
        else {
            self::purge_cache_all();
        }

        self::try_setup_dropin();

        self::rcapi_fetch_stats(false);

        self::rcapi_push();
    }

    /**
     * purge_woo_product_variation.
     */
    public static function purge_woo_product_variation($variation_id)
    {
        if (self::is_defined_halt()) {
            return;
        }

        $product_id = wp_get_post_parent_id($variation_id);

        if (!empty($product_id)) {
            self::$is_purge_cache_home    = true;
            self::$is_purge_cache_content = true;
            self::$is_purge_cache_archive = true;
            self::$is_purge_cache_urlpath = true;

            self::purge_cache_post($product_id);

            self::reset_purge_action();
        }
    }

    /**
     * is_known_plugins_active.
     */
    private static function is_known_plugins_active()
    {

        // Autoptimize
        if (class_exists('autoptimizeCache')) {
            return true;
        }

        // Fast Velocity Minify
        if (function_exists('fvm_purge_all')) {
            return true;
        }

        // WPRocket
        if (function_exists('rocket_clean_domain')) {
            return true;
        }

        // Swift Performance
        if (class_exists('Swift_Performance_Cache')) {
            return true;
        }

        // WP Fastest Cache
        if (class_exists('WpFastestCache')) {
            return true;
        }
 
        // WP Super Cache
        if (function_exists('wp_cache_clear_cache')) {
            return true;
        }

        // W3 Total Cache
        if (function_exists('w3tc_flush_all')) {
            return true;
        }

        // Hyper Cache
        if (class_exists('HyperCache')) {
            return true;
        }

        // WP Optimize
        if (function_exists('wpo_cache_flush')) {
            return true;
        }

        return false;
    }

    /**
     * purge_cache_known_plugins.
     */
    private static function purge_cache_known_plugins()
    {

        // Remove Action Hooks
        remove_action('autoptimize_action_cachepurged', [__CLASS__, 'purge_cache_all_noaction'], self::$ordlast);
        remove_action('fvm_after_purge_all', [__CLASS__, 'purge_cache_all_noaction'], self::$ordlast);
        remove_action('after_rocket_clean_domain', [__CLASS__, 'purge_cache_all_noaction'], self::$ordlast);
        remove_action('swift_performance_after_clear_all_cache', [__CLASS__, 'purge_cache_all_noaction'], self::$ordlast);
        remove_action('wpfc_delete_cache', [__CLASS__, 'purge_cache_all_noaction'], self::$ordlast);
        remove_action('wp_cache_cleared', [__CLASS__, 'purge_cache_all_noaction'], self::$ordlast);
        remove_action('w3tc_flush_all', [__CLASS__, 'purge_cache_all_noaction'], self::$ordlast);
        remove_action('w3tc_flush_posts', [__CLASS__, 'purge_cache_all_noaction'], self::$ordlast);
        remove_action('hyper_cache_flush_all', [__CLASS__, 'purge_cache_all_noaction'], self::$ordlast);
        remove_action('hyper_cache_purged', [__CLASS__, 'purge_cache_all_noaction'], self::$ordlast);
        remove_action('wpo_cache_flush', [__CLASS__, 'purge_cache_all_noaction'], self::$ordlast);

        $plugins = [];

        // Autoptimize
        if (class_exists('autoptimizeCache')) {
            autoptimizeCache::clearall_actionless();
            $plugins[] = 'Autoptimize';
            self::debug(__METHOD__, 'Autoptimize');
        }

        // Fast Velocity Minify
        if (function_exists('fvm_purge_all')) {
            fvm_purge_all();
            $plugins[] = 'Fast Velocity Minify';
            self::debug(__METHOD__, 'Fast Velocity Minify');
        }

        // WPRocket
        if (function_exists('rocket_clean_domain')) {
            rocket_clean_domain();
            $plugins[] = 'WP Rocket';
            self::debug(__METHOD__, 'WPRocket');
        }

        // Swift Performance
        if (class_exists('Swift_Performance_Cache')) {
            Swift_Performance_Cache::clear_all_cache();
            $plugins[] = 'Swift Performance';
            self::debug(__METHOD__, 'Swift Performance');
        }

        // WP Fastest Cache
        if (class_exists('WpFastestCache')) {
            $wpfc = new WpFastestCache();
            $wpfc->deleteCache();
            $plugins[] = 'WP Fastest Cache';
            self::debug(__METHOD__, 'WP Fastest Cache');
        }
 
        // WP Super Cache
        if (function_exists('wp_cache_clear_cache')) {
            if (is_multisite()) {
                $blog_id = get_current_blog_id();
                wp_cache_clear_cache($blog_id);
            } 
            else {
                wp_cache_clear_cache();
            }
            $plugins[] = 'WP Super Cache';
            self::debug(__METHOD__, 'WP Super Cache');
        }

        // W3 Total Cache
        if (function_exists('w3tc_flush_all')) {
            w3tc_flush_all();
            $plugins[] = 'W3 Total Cache';
            self::debug(__METHOD__, 'W3 Total Cache');
        }

        // Hyper Cache
        if (class_exists('HyperCache')) {
            $hypercache = new HyperCache();
            $hypercache->clean();
            $plugins[] = 'Hyper Cache';
            self::debug(__METHOD__, 'Hyper Cache');
        }

        // WP Optimize
        if (function_exists('wpo_cache_flush')) {
            wpo_cache_flush();
            $plugins[] = 'WP Optimize';
            self::debug(__METHOD__, 'WP Optimize');
        }

        return $plugins;
    }

    /**
     * register_purge_hooks.
     */
    public static function register_purge_hooks()
    {

        // run plugin upgrader if needed after upgrading plugin
        add_action('admin_init', [__CLASS__, 'upgrade_plugin'], self::$ordlast);

        // clear cache when finish upgrading theme / plugin
        add_action('upgrader_process_complete', [__CLASS__, 'purge_cache_on_upgrader'], self::$ordlast, 2);

        // clear cache when a plugin is activated/deactivated
        add_action('activated_plugin', [__CLASS__, 'purge_cache_on_plugin_activate'], self::$ordlast, 2);
        add_action('deactivated_plugin', [__CLASS__, 'purge_cache_on_plugin_deactivate'], self::$ordlast, 2);

        // clear cache when switch to different theme
        add_action('switch_theme', [__CLASS__, 'purge_cache_on_theme_switch'], self::$ordlast);

        // clear cache when permalink structure is updated
        add_action('permalink_structure_changed', [__CLASS__, 'purge_cache_on_permalink_update'], self::$ordlast);
        add_action('update_option_category_base', [__CLASS__, 'purge_cache_on_permalink_update'], self::$ordlast);
        add_action('update_option_tag_base', [__CLASS__, 'purge_cache_on_permalink_update'], self::$ordlast);

        // clear cache when customizer / theme_mods is saved
        add_action('customize_save', [__CLASS__, 'purge_cache_on_customize_save'], self::$ordlast);

        // clear cache when update a menu item
        add_action('wp_update_nav_menu', [__CLASS__, 'purge_cache_on_navmenu_update'], self::$ordlast);

        // clear cache when add/remove sidebar widgets
        add_action('update_option_sidebars_widgets', [__CLASS__, 'purge_cache_on_sidebar_update'], self::$ordlast);

        // clear cache when update a widget parameter
        add_filter('widget_update_callback', [__CLASS__, 'purge_cache_on_widget_update'], self::$ordlast);

        // // clear cache when register/update/delete user
        // add_action('user_register', [__CLASS__, 'purge_cache_all'], self::$ordlast);
        // add_action('profile_update', [__CLASS__, 'purge_cache_all'], self::$ordlast);
        // add_action('deleted_user', [__CLASS__, 'purge_cache_all'], self::$ordlast);

        // // clear cache when create/edit/delete trem
        // add_action('create_term', [__CLASS__, 'purge_cache_all'], self::$ordlast);
        // add_action('edited_terms', [__CLASS__, 'purge_cache_all'], self::$ordlast);
        // add_action('delete_term', [__CLASS__, 'purge_cache_all'], self::$ordlast);

        // clear cache on post status change (publish/future/trash)
        add_action('transition_post_status', [__CLASS__, 'purge_cache_on_post_status_change'], self::$ordlast, 3);

        // clear cache on comment
        add_action('wp_insert_comment', [__CLASS__, 'purge_cache_on_comment'], self::$ordlast, 2);

        // clear cache on comment status change
        add_action('transition_comment_status', [__CLASS__, 'purge_cache_on_comment_status_change'], self::$ordlast, 3);

        // clear cache on saving product variation
        add_action('woocommerce_save_product_variation', [__CLASS__, 'purge_woo_product_variation'], self::$ordlast);

        // Autoptimize
        add_action('autoptimize_action_cachepurged', [__CLASS__, 'purge_cache_all_noaction'], self::$ordlast);

        // Fast Velocity Minify
        add_action('fvm_after_purge_all', [__CLASS__, 'purge_cache_all_noaction'], self::$ordlast);

        // WPRocket
        add_action('after_rocket_clean_domain', [__CLASS__, 'purge_cache_all_noaction'], self::$ordlast);

        // Swift Performance
        add_action('swift_performance_after_clear_all_cache', [__CLASS__, 'purge_cache_all_noaction'], self::$ordlast);

        // WP Fastest Cache
        add_action('wpfc_delete_cache', [__CLASS__, 'purge_cache_all_noaction'], self::$ordlast);

        // WP Super Cache
        add_action('wp_cache_cleared', [__CLASS__, 'purge_cache_all_noaction'], self::$ordlast);

        // W3 Total Cache
        add_action('w3tc_flush_all', [__CLASS__, 'purge_cache_all_noaction'], self::$ordlast);
        add_action('w3tc_flush_posts', [__CLASS__, 'purge_cache_all_noaction'], self::$ordlast);

        // Hyper Cache
        add_action('hyper_cache_flush_all', [__CLASS__, 'purge_cache_all_noaction'], self::$ordlast);
        add_action('hyper_cache_purged', [__CLASS__, 'purge_cache_all_noaction'], self::$ordlast);

        // WP Optimize
        add_action('wpo_cache_flush', [__CLASS__, 'purge_cache_all_noaction'], self::$ordlast);

        // Beaver Builder
        add_action('fl_builder_cache_cleared', [__CLASS__, 'purge_cache_all_noaction'], self::$ordlast);
        add_action('fl_builder_before_save_layout', [__CLASS__, 'purge_cache_on_post_change'], self::$ordlast);
        add_action('fl_builder_after_save_user_template', [__CLASS__, 'purge_cache_on_post_change'], self::$ordlast);

        // Custom Purger Hooks via filter
        $purge_action_hooks = apply_filters('runcloud_purge_nginx_cache_hooks', array());
        if (!empty($purge_action_hooks)) {
            foreach ($purge_action_hooks as $purge_action_hook) {
                add_action($purge_action_hook, [__CLASS__, 'purge_cache_all_noaction'], self::$ordlast);
            }
        }

        if (self::$is_redis_debug) {
            add_action('shutdown', [__CLASS__, 'add_redis_stats'], self::$ordlast);
        }

        if (self::$is_html_footprint) {
            add_action('shutdown', [__CLASS__, 'add_html_footprint'], self::$ordlast);
        }
    }

    /**
     * register_integrations.
     */
    public static function register_integrations()
    {
        // WPRocket
        add_action('rocket_htaccess_mod_expires', [__CLASS__, 'wprocket_remove_html_expire'], 5);
        add_filter('do_rocket_generate_caching_files', '__return_false', self::$ordlast);
        add_filter('rocket_cache_mandatory_cookies', '__return_empty_array', self::$ordlast);
        add_filter('rocket_display_varnish_options_tab', '__return_false');
        add_filter('pre_get_rocket_option_cache_mobile', '__return_true');
        add_filter('pre_get_rocket_option_do_caching_mobile_files', '__return_false');
    }

    /**
     * wprocket_remove_html_expire.
     */
    public static function wprocket_remove_html_expire( $rules ) {
        $rules = preg_replace( '@\s*#\s*Your document html@', '', $rules );
        $rules = preg_replace( '@\s*ExpiresByType text/html\s*"access plus \d+ (seconds|minutes|hour|week|month|year)"@', '', $rules );

        return $rules;
    }

    /**
     * add_html_footprint.
     */
    public static function add_html_footprint() {
        if (is_admin()) {
            return;
        }
        if (defined('DOING_AJAX') && DOING_AJAX) {
            return;
        }
        if (defined('DOING_CRON') && DOING_CRON) {
            return;
        }
        if (defined('WP_CLI') && WP_CLI) {
            return;
        }
        if (defined('REST_REQUEST') && REST_REQUEST) {
            return;
        }
        $footprint = "\n<!-- " . 'RunCloud Hub ' . current_time( 'mysql' ) . " -->";
        echo wp_kses( $footprint, array() );
    }

    /**
     * get_total_posts.
     */
    private static function get_total_posts()
    {
        $post_types = get_post_types(['public' => true]);
        $post_types = array_filter($post_types, 'is_post_type_viewable');

        $count_posts = 0;
        foreach ( $post_types as $post_type ) {
            $count = wp_count_posts($post_type);
            $count_posts += $count->publish;
        }
        return $count_posts;
    }

    /**
     * get_preload_urls.
     */
    private static function get_preload_urls($num, $inc = 1)
    {
        if (!$inc) {
            $inc = 1;
        }

        $urls = [];
        $post_types = get_post_types(['public' => true]);
        $post_types = array_filter($post_types, 'is_post_type_viewable');
        $args = array(
            'fields'              => 'ids',
            'posts_per_page'      => $inc,
            'post_type'           => $post_types,
            'offset'              => $num,
            'orderby'             => 'date',
            'order'               => 'DESC',
            'ignore_sticky_posts' => true,
        );
        $posts = get_posts($args);
        if (!empty($posts)) {
            foreach ($posts as $post_id) {
                $url = get_permalink($post_id);
                if ($url) {
                    $urls[] = $url;
                }
            }
        }

        return $urls;
    }

    /**
     * get_preload_speed.
     */
    private static function get_preload_speed()
    {
        $options = self::get_setting();
        $preload_speed = intval( $options['preload_speed'] );
        if (!$preload_speed) {
            $preload_speed = 12;
        }
        elseif ($preload_speed > 120) {
            $preload_speed = 120;
        }
        return $preload_speed;
    }

    /**
     * get_preload_inc.
     */
    private static function get_preload_inc()
    {
        $preload_speed = self::get_preload_speed();
        if ( $preload_speed < 60 ) {
            $inc = ceil($preload_speed/6);
        }
        else {
            $inc = 10;
        }
        return $inc;
    }

    /**
     * get_preload_interval.
     */
    private static function get_preload_interval()
    {
        $preload_speed = self::get_preload_speed();
        if ( $preload_speed < 60 ) {
            $interval = 10;
        }
        else {
            $interval = 600/$preload_speed;
        }
        $interval = intval($interval*1000)-250;
        return $interval;
    }

    /**
     * match_wildcard.
     */
    public static function match_wildcard($match, $string)
    {
        if (false === strpos($match, '*')) {
            return (false !== strpos($string, $match) ? true : false);
        } else {
            $wildcard_chars = ['\*', '\?'];
            $regexp_chars   = ['.*', '.'];
            $regex          = str_replace($wildcard_chars, $regexp_chars, preg_quote($match, '/'));
            if (preg_match('/^' . $regex . '$/is', $string)) {
                return true;
            }
        }

        return false;
    }

    /**
     * match_rules.
     */
    public static function match_filter($rules, $string)
    {
        if (is_object($rules)) {
            $rules = json_decode(json_encode($rules), true);
        }

        if (!empty($rules) && is_array($rules)) {
            foreach ($rules as $match) {
                if (self::match_wildcard($match, $string)) {
                    return true;
                }
            }
            return false;
        }

        return self::match_wildcard($rules, $string);
    }

    /**
     * deactivate_runcache_purger.
     */
    public static function maybe_deactivate_runcache_purger()
    {
        $file = 'runcache-purger/runcache-purger.php';

        if (self::is_plugin_active($file)) {
            deactivate_plugins($file);
            self::wp_cache_delete();
            return true;
        }

        return false;
    }

    /**
     * late_purge_cache_redis.
     */
    private static function late_purge_cache_redis()
    {
        self::debug(__METHOD__, 'event run');

        add_action(
            'shutdown',
            function () {
                wp_cache_flush();
            },
            self::$ordlast
        );
    }

    /**
     * late_purge_cache_redis_all.
     */
    private static function late_purge_cache_redis_all()
    {
        self::debug(__METHOD__, 'event run');

        add_action(
            'shutdown',
            function () {
                if (function_exists('wp_cache_flush_sites')) {
                    wp_cache_flush_sites();
                } else {
                    wp_cache_flush();
                }
            },
            self::$ordlast
        );
    }

    /**
     * get_schedule_name.
     */
    private static function get_schedule_name($num)
    {
        foreach (wp_get_schedules() as $name => $arr) {
            if (false !== strpos($name, self::$slug . '-')) {
                if ((int) $num === (int) $arr['interval']) {
                    return $name;
                }
            }
        }
        return null;
    }

    /**
     * callback_cron_purge.
     */
    public static function callback_cron_purge()
    {
        self::debug(__METHOD__, 'event run');
        self::purge_cache_all();
    }

    /**
     * schedule_cron_purge.
     */
    private static function schedule_cron_purge($reset = 0)
    {
        $hook_name = 'cron_purge_' . self::get_hash(self::$slug . 'cron_purge');

        if (!empty($reset)) {
            wp_clear_scheduled_hook($hook_name);
            if (1 === (int) $reset) {
                return true;
            }
        }

        $options = self::get_setting();
        if (!empty($options['schedule_purge_onn'])) {
            $schedule_name = self::get_schedule_name($options['schedule_purge_unt']);
            $duration      = (time() + (int) $options['schedule_purge_var']);
            if (!wp_next_scheduled($hook_name)) {
                return wp_schedule_event($duration, $schedule_name, $hook_name);
            }

            return false;
        }

        return wp_clear_scheduled_hook($hook_name);
    }

    /**
     * callback_cron_preload.
     */
    public static function callback_cron_preload()
    {
        self::debug(__METHOD__, 'event run');

        // clear all cache but do not trigger background preload
        self::$is_run_preload = false;
        self::purge_cache_all();

        $total = self::get_total_posts();
        if ($total) {
            update_option(
                self::$db_preload,
                [
                    'num'    => 0,
                    'total'  => $total,
                    'type'   => 'background',
                ],
                false
            );
            self::schedule_preload_batch();
        }
    }

    /**
     * callback_cron_rcstats.
     */
    public static function callback_cron_rcstats()
    {
        self::debug(__METHOD__, 'event run');
        self::rcapi_fetch_stats(true);
    }

    /**
     * schedule_cron_preload.
     */
    private static function schedule_cron_preload($reset = 0)
    {
        $hook_name = 'cron_preload_' . self::get_hash(self::$slug . 'cron_preload');

        if (!empty($reset)) {
            wp_clear_scheduled_hook($hook_name);
            if (1 === (int) $reset) {
                return true;
            }
        }

        $options = self::get_setting();
        if (!empty($options['preload_schedule_onn'])) {
            $schedule_name = self::get_schedule_name($options['preload_schedule_unt']);
            $duration      = (time() + (int) $options['preload_schedule_var']);
            if (!wp_next_scheduled($hook_name)) {
                return wp_schedule_event($duration, $schedule_name, $hook_name);
            }

            return false;
        }

        return wp_clear_scheduled_hook($hook_name);
    }

    /**
     * schedule_cron_rcstats.
     */
    private static function schedule_cron_rcstats($reset = 0)
    {
        $hook_name = 'cron_rcstats_' . self::get_hash(self::$slug . 'cron_rcstats');

        if (!empty($reset)) {
            wp_clear_scheduled_hook($hook_name);
            if (1 === (int) $reset) {
                return true;
            }
        }

        $options = self::get_setting();
        if (!empty($options['stats_onn']) && !empty($options['stats_schedule_onn'])) {
            $schedule_name = self::get_schedule_name($options['stats_schedule_unt']);
            $duration      = (time() + (int) $options['stats_schedule_var']);
            if (!wp_next_scheduled($hook_name)) {
                return wp_schedule_event($duration, $schedule_name, $hook_name);
            }

            return false;
        }

        return wp_clear_scheduled_hook($hook_name);
    }

    /**
     * register_cron_hooks.
     */
    private static function register_cron_hooks($install = true)
    {
        if ($install) {
            self::schedule_cron_purge();
            self::schedule_cron_preload();
            self::schedule_cron_rcstats();
        } else {
            self::schedule_cron_purge(1);
            self::schedule_cron_preload(1);
            self::schedule_cron_rcstats(1);
        }
    }

    /**
     * view_stats_charts.
     */
    public static function view_stats_charts($name)
    {
        $stats = [
            'type' => 'daily',
            'data' => [],
        ];

        if (!in_array($name, array('health','transfer'))) {
            return $stats;
        }

        $options = self::get_setting();
        if (!empty($options['stats_onn']) && !empty($options['stats_'.$name . '_onn'])) {
            if ($name === 'health') {
                $stats['type'] = in_array($options['stats_health_var'], array('daily','hourly')) ? $options['stats_health_var'] : 'hourly';
            }
            elseif ($name === 'transfer') {
                $stats['type'] = in_array($options['stats_transfer_var'], array('daily','monthly')) ? $options['stats_transfer_var'] : 'daily';
            }

            $data = self::rcapi_get_stats();
            if (!empty($data)) {
                if (!empty($data[$name][$stats['type']])) {
                    $stats['data'] = $data[$name][$stats['type']];
                }
            }
        }

        return $stats;
    }

    /**
     * view_stats_lastupdate.
     */
    public static function view_stats_lastupdate()
    {
        $data = get_option(self::$db_stats);
        if (!empty($data) && !empty($data['lastupdate'])) {
            $lastupdate = $data['lastupdate'];
            $time = human_time_diff(strtotime($lastupdate));
            if ($time) {
                $lastupdate .= sprintf(esc_html__(' (%s ago)', 'runcloud-hub'), $time);
            }
            return $lastupdate;
        }
        return '-';
    }

    /**
     * __shutdown.
     */
    private static function __shutdown()
    {
        add_action(
            'shutdown',
            function () {
                self::wp_cache_delete();
                if ( !self::$onfirehooks ) {
                    wp_cache_flush();
                }
                if (function_exists('wp_cache_self_remove') && (defined('RCWP_REDIS_DROPIN') || defined('RUNCACHE_PURGER_DROPIN'))) {
                    wp_cache_self_remove();
                }
            },
            self::$ordlast
        );
    }

    /**
     * activate.
     */
    public static function activate($network_wide)
    {
        self::$onfirehooks = true;
        self::install_options($network_wide);
        self::maybe_deactivate_runcache_purger();
        self::try_setup_dropin();
    }

    /**
     * deactivate.
     */
    public static function deactivate($network_wide)
    {
        self::$onfirehooks = true;
        self::force_site_deactivate_plugin();
        self::uninstall_dropin(true);
        self::__shutdown();
    }

    /**
     * uninstall.
     */
    public static function uninstall($network_wide)
    {
        self::$onfirehooks = true;
        self::uninstall_options($network_wide);
        self::uninstall_dropin(true);
        self::__shutdown();
    }

    /**
     * register_hook.
     */
    public static function register_plugin_hooks()
    {
        register_activation_hook(RUNCLOUD_HUB_HOOK, [__CLASS__, 'activate']);
        register_deactivation_hook(RUNCLOUD_HUB_HOOK, [__CLASS__, 'deactivate']);
        register_uninstall_hook(RUNCLOUD_HUB_HOOK, [__CLASS__, 'uninstall']);
    }

    /**
     * reinstall_options.
     */
    public static function reinstall_options($all_site = false, $status = false)
    {
        self::debug(__METHOD__, 'event run');

        if (self::is_main_site()) {
            $rcapi_key = self::get_setting('rcapi_key');
            $rcapi_secret = self::get_setting('rcapi_secret');
            $rcapi_webapp_id = self::get_setting('rcapi_webapp_id');
        }

        self::uninstall_options($all_site);
        self::install_options($all_site);

        if (self::is_main_site()) {
            self::update_setting_var('rcapi_key', $rcapi_key);
            self::update_setting_var('rcapi_secret', $rcapi_secret);
            self::update_setting_var('rcapi_webapp_id', $rcapi_webapp_id);
        }

        self::clean_setup();

        if ($status) {
            $req_status = [
                'code' => 200,
                'status' => esc_html__('Reset settings was successful.', 'runcloud-hub'),
            ];
            return $req_status;
        }
    }

    /**
     * reinstall_dropin.
     */
    public static function reinstall_dropin($status = false)
    {
        return self::install_dropin(true, $status);
    }

    /**
     * register_wpcli_hooks.
     */
    public static function register_wpcli_hooks()
    {
        if (self::is_wp_cli() && !class_exists('RunCloud_Hub_CLI')) {
            require_once RUNCLOUD_HUB_PATH_LIB . 'wp-cli.php';
            WP_CLI::add_command('runcloud-hub', esc_html__('RunCloud_Hub_CLI', 'runcloud-hub'), ['shortdesc' => esc_html__('Manages RunCloud Hub WordPress Plugin', 'runcloud-hub')]);
            WP_CLI::add_command('runcache-purger', esc_html__('RunCloud_Hub_CLI', 'runcloud-hub'), ['shortdesc' => esc_html__('RunCache Purger for RunCloud Hub', 'runcloud-hub')]);
        }
    }

    /**
     * array_merge_r.
     */
    private static function array_merge_r()
    {
        if (func_num_args() < 2) {
            trigger_error(__FUNCTION__ . ' invalid input', E_USER_WARNING);
            return;
        }

        $arrays = func_get_args();
        $merged = [];

        while ($array = @array_shift($arrays)) {
            if (!is_array($array)) {
                trigger_error(__FUNCTION__ . ' invalid input', E_USER_WARNING);
                return;
            }

            if (empty($array)) {
                continue;
            }

            foreach ($array as $key => $value) {
                if (is_string($key)) {
                    if (is_array($value) && array_key_exists($key, $merged) && is_array($merged[$key])) {
                        $merged[$key] = self::array_merge_r($merged[$key], $value);
                    } else {
                        $merged[$key] = $value;
                    }
                } else {
                    $merged[] = $value;
                }
            }
        }
        return $merged;
    }

    /**
     * get_fileperms.
     */
    private static function get_fileperms($type)
    {
        static $perms = [];

        $type = (string) $type;

        if (isset($perms[$type])) {
            return $perms[$type];
        }

        if ('dir' === $type) {
            if (defined('FS_CHMOD_DIR')) {
                $perms[$type] = FS_CHMOD_DIR;
            } else {
                clearstatcache();
                $perms[$type] = fileperms(ABSPATH) & 0777 | 0755;
            }

            return $perms[$type];
        } elseif ('file' === $type) {
            if (defined('FS_CHMOD_FILE')) {
                $perms[$type] = FS_CHMOD_FILE;
            } else {
                clearstatcache();
                $perms[$type] = fileperms(ABSPATH . 'index.php') & 0777 | 0644;
            }

            return $perms[$type];
        }

        return 0755;
    }

    /**
     * fetch_preload.
     */
    private static function fetch_preload($url, $blocking = true)
    {
        $args = [
            'timeout'     => $blocking ? 10 : 0.01,
            'redirection' => 5,
            'httpversion' => '1.1',
            'user-agent'  => self::get_user_agent(),
            'blocking'    => $blocking,
            'body'        => null,
            'compress'    => false,
            'decompress'  => true,
            'sslverify'   => false,
            'stream'      => false,
            'filename'    => null,
        ];

        $results = [
            'blocking' => $blocking,
            'code'     => '',
            'status'   => '',
            'host'     => parse_url($url, PHP_URL_HOST),
            'url'      => $url,
            'method'   => 'GET',
            'error'    => '',
        ];

        $response = wp_remote_get($url, $args);
        if (is_wp_error($response)) {
            $results['code']   = wp_remote_retrieve_response_code($response);
            $results['status'] = wp_remote_retrieve_response_message($response);
            $results['error']  = wp_strip_all_tags($response->get_error_message());
        } else {
            $results['code']   = wp_remote_retrieve_response_code($response);
            $results['status'] = wp_remote_retrieve_response_message($response);
            $results['header'] = (is_object($response['headers']) ? (array) $response['headers'] : null);
        }

        self::debug(__METHOD__, $results);
        return (empty($results['error']) ? true : false);
    }

    /**
     * schedule_preload_batch.
     */
    private static function schedule_preload_batch($reset = 0)
    {
        $hook_name = 'preload_batch_' . self::get_hash(self::$slug . '_preload_batch');

        if (!empty($reset)) {
            self::debug(__METHOD__, 'schedule preload batch cleared');
            wp_clear_scheduled_hook($hook_name);
        }
        else {
            if (!wp_next_scheduled($hook_name)) {
                self::debug(__METHOD__, 'schedule preload batch');
                wp_schedule_event(time(), 'runcloud-hub-minute', $hook_name);
            }
        }
    }

    /**
     * callback_preload_batch.
     */
    public static function callback_preload_batch()
    {
        self::fastcgi_close();

        $preload = get_option(self::$db_preload);
        if (empty($preload)) {
            self::schedule_preload_batch(1);
            return;
        }

        if (!isset($preload['num'], $preload['total'], $preload['type'])) {
            self::schedule_preload_batch(1);
            return;
        }

        if ($preload['type'] !== 'background') {
            self::schedule_preload_batch(1);
            return;
        }

        $num = (int) $preload['num'];
        $total = (int) $preload['total'];
        $type = $preload['type'];

        if (!$total || ($total && $num >= $total)) {
            self::schedule_preload_batch(1);
            self::debug(__METHOD__, sprintf( esc_html__('Background Preload is finished, num = %s, total = %s', 'runcloud-hub'), $num, $total ) );
            return;
        }

        if ($num == 0) {
            // preload homepage
            $preload_url = home_url('/');
            self::fetch_preload($preload_url, false);
            // preload path
            $options = self::get_setting();
            if (!empty($options['preload_path_onn']) && !empty($options['preload_path_mch']) && is_array($options['preload_path_mch'])) {
                foreach ($options['preload_path_mch'] as $path) {
                    if ('/' === $path) {
                        continue;
                    }
                    $path    = strtolower($path);
                    $preload_url = home_url('/') . ltrim($path, '/');
                    self::fetch_preload($preload_url, false);
                }
            }
        }

        $speed = self::get_preload_speed();
        $urls = self::get_preload_urls($num, $speed);
        if (!empty($urls)) {
            $count = count($urls);
            $num_batch = $num + $count;
            $preload['num'] = $num_batch;
            update_option(self::$db_preload, $preload, false);
            if ($num_batch >= $total) {
                self::schedule_preload_batch(1);
            }

            $i = 0;
            $num_fetch = $num;
            foreach ($urls as $url) {
                $i++;
                $num_fetch++;
                self::debug(__METHOD__, sprintf( esc_html__('Background Preload %s / %s : %s', 'runcloud-hub'), $num_fetch, $total, $url ) );
                self::fetch_preload($url, false);
                if ($i == 10 && $count > 10) {
                    $i = 0;
                    sleep(4);
                }
            }

            if ($num_batch >= $total) {
                delete_option(self::$db_preload);
                self::debug(__METHOD__, sprintf( esc_html__('Background Preload is finished, num = %s, total = %s', 'runcloud-hub'), $num_batch, $total ) );
            }
        }
    }

    /**
     * get_hash.
     */
    public static function get_hash($string, $len = 12)
    {
        $str = @md5($string);
        if ( strlen($str) > $len ) {
            return @substr($str, 0, $len);
        }
        return $str;
    }

    /**
     * is_debug.
     */
    private static function is_debug()
    {
        return (defined('RUNCLOUD_HUB_DEBUG') && RUNCLOUD_HUB_DEBUG);
    }

    /**
     * debug.
     */
    private static function debug($caller, $data)
    {
        if (!self::is_debug()) {
            return false;
        }

        $log = [
            'timestamp' => gmdate('Y-m-d H:i:s') . ' UTC',
            'caller'    => $caller,
        ];

        if (!empty($data) && is_array($data)) {
            $log = self::array_merge_r($log, $data);
        } else {
            $log['status'] = $data;
        }

        return self::debug_log($log);
    }

    /**
     * array_export.
     */
    public static function array_export($data)
    {
        $data_e = var_export($data, true);
        $data_e = str_replace('Requests_Utility_CaseInsensitiveDictionary::__set_state(', '', $data_e);

        $data_e = preg_replace('/^([ ]*)(.*)/m', '$1$1$2', $data_e);
        $data_r = preg_split("/\r\n|\n|\r/", $data_e);

        $data_r = preg_replace(['/\s*array\s\($/', '/\)(,)?$/', '/\s=>\s$/'], [null, ']$1', ' => ['], $data_r);
        return join(PHP_EOL, array_filter(['['] + $data_r));
    }

    /**
     * debug_log.
     */
    private static function debug_log($data, $filesave = '')
    {
        if (empty($filesave)) {
            $fname    = str_replace(' ', '_', self::$slug);
            $filesave = WP_CONTENT_DIR . '/' . $fname . '.log';
        }

        if (is_dir($filesave)) {
            return false;
        }

        if (!empty($data) && (is_array($data) || is_object($data))) {
            $data = str_replace('\\u0000', '', str_replace('\\u0000*', '', json_encode($data)));
            $data = json_decode($data, true);
            if (isset($data['header']['data'])) {
                $h              = $data['header']['data'];
                $data['header'] = $h;
                unset($h);
            }
        }

        if (!empty($data) && is_array($data)) {
            $data = str_replace('\\u0000', '', str_replace('\\u0000*', '', json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT))) . ',';
        } else {
            $data = trim($data);
        }

        $output = $data . PHP_EOL;

        $perm = self::get_fileperms('file');
        $is_file_exists = file_exists($filesave);

        if ( $is_file_exists ) {
            @chmod($filesave, $perm);
        }

        if (@file_put_contents($filesave, $output, FILE_APPEND|LOCK_EX)) {
            if ( !$is_file_exists ) {
                @chmod($filesave, $perm);
            }
            return true;
        }

        return false;
    }

    /**
     * wpcli_nginx_purge_all.
     */
    public static function wpcli_nginx_purge_all($type = null, $host_url = null)
    {
        $res = [];

        if ( $host_url ) {
            // reset
            $proto    = parse_url($host_url, PHP_URL_SCHEME);
            $hostname = parse_url($host_url, PHP_URL_HOST);

            // maybe port 443 is open. Then check if we should use the https proto instead
            if (self::is_wp_ssl() && 'https' !== $proto && @fsockopen('tls://' . $hostname, 443)) {
                $proto = 'https';
            }
        }
        else {
            $proto = null;
        }

        if (self::is_srcache()) {
            self::$done_purgeall = false;
            return self::nginx_purge_all('srcache', $proto);
        }
        else {
            $return = [
                'code' => '',
                'status' => '',
            ];

            self::$done_purgeall = false;
            $return['purge_fastcgi'] = self::nginx_purge_all('fastcgi', $proto);
            $return['code'] = $return['purge_fastcgi']['code'];
            $return['status'] = '[FastCGI] '.$return['purge_fastcgi']['status'].'. ';

            self::$done_purgeall = false;
            $return['purge_proxy'] = self::nginx_purge_all('proxy', $proto);
            if ( $return['purge_proxy']['code'] !== 200 ) {
                $return['code'] = $return['purge_proxy']['code'];
            }
            $return['status'] .= '[Proxy] '.$return['purge_proxy']['status'].'. ';

            return $return;
        }
    }

    /**
     * wpcli_nginx_purge_all_sites.
     */
    public static function wpcli_nginx_purge_all_sites($type = null, $host_url = null)
    {
        $res = [];

        if ( $host_url ) {
            // reset
            $proto    = parse_url($host_url, PHP_URL_SCHEME);
            $hostname = parse_url($host_url, PHP_URL_HOST);

            // maybe port 443 is open. Then check if we should use the https proto instead
            if (self::is_wp_ssl() && 'https' !== $proto && @fsockopen('tls://' . $hostname, 443)) {
                $proto = 'https';
            }
        }
        else {
            $proto = null;
        }

        if (self::is_srcache()) {
            self::$done_purgesiteall = false;
            return self::nginx_purge_all_sites('srcache', $proto);
        }
        else {
            $return = [
                'code' => '',
                'status' => '',
            ];

            self::$done_purgesiteall = false;
            $return['purge_fastcgi'] = self::nginx_purge_all_sites('fastcgi', $proto);
            $return['code'] = $return['purge_fastcgi']['code'];
            $return['status'] = '[FastCGI] '.$return['purge_fastcgi']['status'].'. ';

            self::$done_purgesiteall = false;
            $return['purge_proxy'] = self::nginx_purge_all_sites('proxy', $proto);
            if ( $return['purge_proxy']['code'] !== 200 ) {
                $return['code'] = $return['purge_proxy']['code'];
            }
            $return['status'] .= '[Proxy] '.$return['purge_proxy']['status'].'. ';

            return $return;
        }
    }

    /**
     * rcapi_endpoint.
     */
    private static function rcapi_endpoint()
    {
        static $lists;

        if (is_object($lists)) {
            return $lists;
        }

        $options         = self::get_setting();
        $rcapi_key       = $options['rcapi_key'];
        $rcapi_secret    = $options['rcapi_secret'];
        $rcapi_webapp_id = $options['rcapi_webapp_id'];

        // get key/secret from main site
        if (is_multisite() && !self::is_main_site()) {
            switch_to_blog(self::get_main_site_id());
            $options_site = self::get_setting();
            $rcapi_key    = $options_site['rcapi_key'];
            $rcapi_secret = $options_site['rcapi_secret'];
            restore_current_blog();
            unset($options_site);
        }

        $rcapi_url = (defined('RUNCLOUD_HUB_ENDPOINT') ? RUNCLOUD_HUB_ENDPOINT : self::$endpoint);

        $lists = [
            'url'       => $rcapi_url,
            'auth'      => base64_encode($rcapi_key . ':' . $rcapi_secret),
            'webapp_id' => $rcapi_webapp_id,
        ];

        $lists = (object) $lists;
        return $lists;
    }

    /**
     * rcapi_fetch.
     */
    public static function rcapi_fetch($url)
    {
        $args = [
            'timeout'     => 10,
            'httpversion' => '1.1',
            'user-agent'  => self::get_user_agent(),
            'blocking'    => true,
            'headers'     => ['Authorization' => 'Basic ' . self::rcapi_endpoint()->auth],
            'body'        => null,
            'compress'    => false,
            'decompress'  => true,
            'sslverify'   => false,
            'stream'      => false,
            'filename'    => null,
        ];

        $results = [
            'code'   => '',
            'status' => '',
            'host'   => parse_url($url, PHP_URL_HOST),
            'url'    => $url,
            'method' => 'GET',
        ];

        $response = wp_remote_get($url, $args);
        if (is_wp_error($response)) {
            $results['code']    = wp_remote_retrieve_response_code($response);
            $results['status']  = sprintf(esc_html__('Fetching server stats failed - %s', 'runcloud-hub'), wp_strip_all_tags($response->get_error_message()));
            $results['content'] = '';
        } else {
            $results['code']    = wp_remote_retrieve_response_code($response);
            if ($results['code'] == 200) {
                $results['status']  = esc_html__('Fetching server stats was successful', 'runcloud-hub');
                $results['content'] = $response['body'];
            }
            else {
                $error = 'Unknown';
                $content = json_decode($response['body'], true);
                if (isset($content['message']) && $content['message']) {
                    $error = $content['message'];
                }
                $results['status']  = sprintf(esc_html__('Fetching server stats failed - %s', 'runcloud-hub'), $error);
            }
            $results['header']  = (is_object($response['headers']) ? (array) $response['headers'] : null);
        }

        self::debug(__METHOD__, $results);
        return $results;
    }

    /**
     * rcapi_fetch_stats.
     */
    public static function rcapi_fetch_stats($update = false, $status = false)
    {
        $data = [];

        self::debug(__METHOD__, 'event run');

        // only can fetch stats from main site if multisite
        if (!self::is_main_site() || self::is_subdirectory()) {
            $message = sprintf(esc_html__('Only can fetch stats from main site. Current: %s', 'runcloud-hub'), get_site_url());
            self::debug(__METHOD__, $message);
            if ($status) {
                return array(
                    'code' => 0,
                    'status' => sprintf(esc_html__('Fetching server stats failed - %s', 'runcloud-hub'), $message),
                );
            }
            return $data;
        }

        if (!$update) {
            if (get_option(self::$db_stats)) {
                $message = esc_html__('Stats is not empty, no need to fetch new stats data', 'runcloud-hub');
                self::debug(__METHOD__, $message);
                if ($status) {
                    return array(
                        'code' => 200,
                        'status' => sprintf(esc_html__('Fetching server stats failed - %s', 'runcloud-hub'), $message),
                    );
                }
                return $data;
            }
        }

        $options = self::get_setting();
        if (empty($options['rcapi_key']) || empty($options['rcapi_secret']) || empty($options['rcapi_webapp_id'])) {
            $message = esc_html__('Missing API Key / Secret / WebApp ID', 'runcloud-hub');
            self::debug(__METHOD__, $message);
            if ($status) {
                return array(
                    'code' => 0,
                    'status' => sprintf(esc_html__('Fetching server stats failed - %s', 'runcloud-hub'), $message),
                );
            }
            return $data;
        }

        $ep = self::rcapi_endpoint();

        $success = false;
        if (!empty($ep->webapp_id) && false === strpos($ep->url, '{{PANELURL}}')) {
            $type  = in_array($options['stats_health_var'], array('daily','hourly')) ? $options['stats_health_var'] : 'hourly';
            $url   = $ep->url . '/webapps/' . $ep->webapp_id . '/server/health/periodic?key=' . $type;
            $fetch = self::rcapi_fetch($url);
            if (!empty($fetch['code']) && $fetch['code'] == 200 && !empty($fetch['content'])) {
                $data['health'][$type] = json_decode($fetch['content'], true);
                $success = true;
            }

            $type  = in_array($options['stats_transfer_var'], array('daily','monthly')) ? $options['stats_transfer_var'] : 'daily';
            $url   = $ep->url . '/webapps/' . $ep->webapp_id . '/stats/transfer?key=' . $type;
            $fetch = self::rcapi_fetch($url);
            if (!empty($fetch['code']) && $fetch['code'] == 200 && !empty($fetch['content'])) {
                $data['transfer'][$type] = json_decode($fetch['content'], true);
                $success = true;
            }

            if ($success) {
                $data['lastupdate'] = gmdate('Y-m-d H:i:s') . ' UTC';
                update_option(self::$db_stats, $data);
            }
        }
        else {
            if ($status) {
                $message = esc_html__('Endpoint not configured properly', 'runcloud-hub');
                return array(
                    'code' => 0,
                    'status' => sprintf(esc_html__('Fetching server stats failed - %s', 'runcloud-hub'), $message),
                );
            }
        }

        if ($status) {
            return array(
                'code' => $fetch['code'],
                'status' => $fetch['status'],
            );
        }

        return $data;
    }

    /**
     * rcapi_get_stats.
     */
    public static function rcapi_get_stats()
    {
        $data = get_option(self::$db_stats);
        if (!empty($data) && is_array($data)) {
            return $data;
        }
        return [];
    }

    /**
     * rcapi_push.
     */
    public static function rcapi_push(&$message = '')
    {
        $req_status = '';

        // only update from main site if multisite
        if (!self::is_main_site() || self::is_subdirectory()) {
            $message = sprintf( esc_html__('Only can update from main site. Current: %s', 'runcloud-hub'), get_site_url() );
            self::debug(__METHOD__, $message);
            return false;
        }

        $options = self::get_setting();

        if (empty($options['rcapi_key']) || empty($options['rcapi_secret']) || empty($options['rcapi_webapp_id'])) {
            $message = esc_html__('Missing API Key / Secret / WebApp ID', 'runcloud-hub');
            self::debug(__METHOD__, $message);
            if (!self::is_wp_cli()) {
                $req_status = array(
                    'code' => 0,
                    'status' => sprintf(esc_html__('Updating cache rules to the server failed - %s', 'runcloud-hub'), $message),
                );
                update_option(self::$db_notice, $req_status);
            }
            return false;
        }

        if (!empty($options) && is_array($options)) {
            $data = [];

            if ( empty($options['exclude_url_onn']) || empty( $options['exclude_url_mch'] ) ) {
                $options['exclude_url_mch'] = array();
            }

            if ( !in_array('/wp-admin/', $options['exclude_url_mch'] ) ) {
                $options['exclude_url_mch'][] = '/wp-admin/';
            }
            if ( !in_array('wp-.*.php', $options['exclude_url_mch'] ) ) {
                $options['exclude_url_mch'][] = 'wp-.*.php';
            }
            if ( !in_array('index.php', $options['exclude_url_mch'] ) ) {
                $options['exclude_url_mch'][] = 'index.php';
            }
            if ( !in_array('/.well-known.*', $options['exclude_url_mch'] ) ) {
                $options['exclude_url_mch'][] = '/.well-known.*';
            }

            $data['exclude']['uri']        = implode("\n", $options['exclude_url_mch']);

            if ( empty($options['exclude_cookie_onn']) || empty( $options['exclude_cookie_mch'] ) ) {
                $options['exclude_cookie_mch'] = array();
            }

            if ( !in_array('wordpress_[a-f0-9]+', $options['exclude_cookie_mch'] ) ) {
                $options['exclude_cookie_mch'][] = 'wordpress_[a-f0-9]+';
            }
            if ( !in_array('wordpress_logged_in', $options['exclude_cookie_mch'] ) ) {
                $options['exclude_cookie_mch'][] = 'wordpress_logged_in';
            }
            if ( !in_array('wordpress_no_cache', $options['exclude_cookie_mch'] ) ) {
                $options['exclude_cookie_mch'][] = 'wordpress_no_cache';
            }
            if ( !in_array('wp-postpass', $options['exclude_cookie_mch'] ) ) {
                $options['exclude_cookie_mch'][] = 'wp-postpass';
            }

            $data['exclude']['cookie']     = implode("\n", $options['exclude_cookie_mch']);

            $data['exclude']['browser']    = (!empty($options['exclude_browser_onn']) && !empty($options['exclude_browser_mch']) ? implode("\n", $options['exclude_browser_mch']) : '');
            $data['exclude']['visitor_ip'] = (!empty($options['exclude_visitorip_onn']) && !empty($options['exclude_visitorip_mch']) ? implode("\n", $options['exclude_visitorip_mch']) : '');

            $data['query_string']['exclude'] = (!empty($options['exclude_query_onn']) && !empty($options['exclude_query_mch']) ? implode("\n", $options['exclude_query_mch']) : '');
            $data['query_string']['include'] = (!empty($options['allow_query_onn']) && !empty($options['allow_query_mch']) ? implode("\n", $options['allow_query_mch']) : '');

            $data['cache_key']['extra'] = (!empty($options['cache_key_extra_onn']) && !empty($options['cache_key_extra_var']) ? trim($options['cache_key_extra_var']) : '');

            $ep = self::rcapi_endpoint();

            if (!empty($ep->webapp_id) && false === strpos($ep->url, '{{PANELURL}}')) {
                $url = $ep->url . '/webapps/' . $ep->webapp_id . '/wordpress/runcloud-hub/runcache-exclusion';

                $args = [
                    'timeout'     => 10,
                    'httpversion' => '1.1',
                    'user-agent'  => self::get_user_agent(),
                    'blocking'    => true,
                    'headers'     => ['Authorization' => 'Basic ' . self::rcapi_endpoint()->auth],
                    'body'        => $data,
                    'compress'    => false,
                    'decompress'  => true,
                    'sslverify'   => false,
                    'stream'      => false,
                    'filename'    => null,
                ];

                $results = [
                    'code'   => '',
                    'status' => '',
                    'host'   => parse_url($url, PHP_URL_HOST),
                    'url'    => $url,
                    'method' => 'POST',
                    'error'  => '',
                    'param'  => [
                        'Authorization' => $args['headers']['Authorization'],
                        'data'          => $args['body'],
                    ],
                ];

                $response = wp_remote_post($url, $args);
                if (is_wp_error($response)) {
                    $results['code']   = wp_remote_retrieve_response_code($response);
                    $results['status'] = wp_remote_retrieve_response_message($response);
                    $results['error']  = wp_strip_all_tags($response->get_error_message());
                } else {
                    $results['code']   = wp_remote_retrieve_response_code($response);
                    $results['status'] = wp_remote_retrieve_response_message($response);
                    $results['header'] = (is_object($response['headers']) ? (array) $response['headers'] : null);
                    if ($results['code'] !== 200) {
                        $response_body = wp_remote_retrieve_body($response);
                        $response_body = json_decode($response_body);
                        $results['error'] = !empty($response_body->message) ? $response_body->message : $results['status'];
                    }
                }

                $message = $results;
                self::debug(__METHOD__, $message);
                if (empty($results['error']) && 200 === $results['code']) {
                    if (!self::is_wp_cli()) {
                        $req_status = array(
                            'code' => $results['code'],
                            'status' => esc_html__('Updating cache rules to the server was successful', 'runcloud-hub'),
                        );
                        update_option(self::$db_notice, $req_status);
                    }
                    return true;
                }
                else {
                    if (!self::is_wp_cli()) {
                        $req_status = array(
                            'code' => $results['code'],
                            'status' => sprintf(esc_html__('Updating cache rules to the server failed - %s', 'runcloud-hub'), $message['error']),
                        );
                        update_option(self::$db_notice, $req_status);
                    }
                    return false;
                }
            }

            $message = ['status' => esc_html__('Endpoint not configured properly', 'runcloud-hub'), 'endpoint' => (array) $ep];
            self::debug(__METHOD__, $message);
            if (!self::is_wp_cli()) {
                $req_status = array(
                    'code' => 0,
                    'status' => sprintf(esc_html__('Updating cache rules to the server failed - %s', 'runcloud-hub'), $message['status']),
                );
                update_option(self::$db_notice, $req_status);
            }
            return false;
        }

        return false;
    }

    /**
     * get_magic_users.
     */
    public static function get_magic_users()
    {
        $__varfunc_getuser = function ($blog_id) {
            $args = [
                'role__in' => ['administrator', 'author', 'editor'],
                'order'    => 'ASC',
            ];

            $results = [];
            $list    = get_users($args);
            if (!empty($list) && is_array($list)) {
                foreach ($list as $num => $arr) {
                    if (!empty($arr->data->deleted)) {
                        continue;
                    }

                    $results[$num]['magiclink']  = (!empty(self::get_setting('rcapi_magiclink_onn')) ? true : false);
                    $results[$num]['blog_id']    = $blog_id;
                    $results[$num]['user_id']    = $arr->data->ID;
                    $results[$num]['user_login'] = $arr->data->user_login;
                    $results[$num]['user_email'] = $arr->data->user_email;
                    $results[$num]['user_role']  = implode(',', $arr->roles);
                    $results[$num]['url']        = get_site_url();
                    $results[$num]['domain']     = wp_parse_url(get_site_url(), PHP_URL_HOST);
                }
            }
            return $results;
        };

        $results = [];
        if (is_multisite()) {
            foreach (get_sites(array('number' => 500)) as $site) {
                switch_to_blog($site->blog_id);
                if (self::is_plugin_active(self::$hook)) {
                    $data    = $__varfunc_getuser($site->blog_id);
                    $results = array_merge($data, $results);
                }
                restore_current_blog();
            }
        } else {
            $blog_id = get_current_blog_id();
            $results = $__varfunc_getuser($blog_id);
        }

        return $results;
    }

    /**
     * get_magic_user_session.
     */
    public static function get_magic_user_session($user_id)
    {
        return get_user_meta($user_id, 'session_tokens', true);
    }

    /**
     * remove_magic_user_session.
     */
    public static function remove_magic_user_session($user_id)
    {
        return delete_user_meta($user_id, 'session_tokens');
    }

    /**
     * remove_magic_user_session_all.
     */
    public static function remove_magic_user_session_all()
    {
        $list = self::get_magic_users();
        if (!empty($list) && is_array($list)) {
            while ($row = @array_shift($list)) {
                $user_id = $row['user_id'];
                self::remove_magic_user_session($user_id);
            }
        }
    }

    /**
     * remove_magic_user_token.
     */
    public static function remove_magic_user_token()
    {
        $list = self::get_magic_users();
        if (!empty($list) && is_array($list)) {
            while ($row = @array_shift($list)) {
                $user_id = $row['user_id'];
                delete_user_meta($user_id, 'magic_link_token');
            }
        }
    }

    /**
     * generate_magic_link.
     */
    public static function generate_magic_link($user_id, $blog_id)
    {
        $url = get_site_url($blog_id, '/?' . self::$magictoken . '=');

        $token  = wp_hash($url . microtime(true) . $user_id . $blog_id, 'auth');
        $url    = $url . $token;
        $expiry = strtotime('+5 minutes');
        $data   = [
            'token'    => $token,
            'expiry'   => $expiry,
            'expiry_h' => gmdate('Y-m-d H:i:s') . ' UTC',
        ];

        self::remove_magic_user_token();
        add_user_meta($user_id, 'magic_link_token', $data, true);
        return [
            'url'    => $url,
            'expiry' => $data['expiry_h'],
        ];
    }

    /**
     * callback_magiclink_login.
     */
    public static function callback_magiclink_login()
    {
        if (!empty($_GET[self::$magictoken])) {
            $is_expired    = false;
            $is_found      = false;
            $found_user_id = null;

            $token = sanitize_text_field($_GET[self::$magictoken]);
            $list  = self::get_magic_users();
            if (!empty($list) && is_array($list)) {
                while ($row = @array_shift($list)) {
                    $user_id = (int) $row['user_id'];
                    $data    = get_user_meta($user_id, 'magic_link_token');

                    if (!empty($data) && is_array($data)) {
                        $data = $data[0];
                        self::debug(__METHOD__, $data);
                        if (!empty($data['token']) && strval($token) === strval($data['token'])) {
                            $is_found      = true;
                            $found_user_id = $user_id;
                            if (!empty($data['expiry']) && time() >= (int) $data['expiry']) {
                                $is_expired = true;
                                self::debug(
                                    __METHOD__,
                                    [
                                        'token'  => $token,
                                        'status' => 'expired',
                                        'data'   => $data,
                                    ]
                                );
                            }
                            break;
                        }
                    }
                }
            }

            if ($is_found && !is_null($found_user_id)) {
                $user_id = $found_user_id;
                delete_user_meta($user_id, 'magic_link_token');

                if ($is_expired) {
                    wp_die( esc_html__('Magic Link: link has expired', 'runcloud-hub'), self::$slug);
                }

                self::remove_magic_user_session($user_id);
                $user_object = get_user_by('id', $user_id);
                wp_set_auth_cookie($user_object->ID, false);
                do_action('wp_login', $user_object->data->user_login, $user_object);
                wp_redirect(admin_url('/'));
                self::close_exit();
            }

            self::debug(
                __METHOD__,
                [
                    'token'  => $token,
                    'status' => 'not exist',
                ]
            );
            wp_die( esc_html__('Magic Link: Invalid token', 'runcloud-hub'), self::$slug);
        }
    }

    /**
     * register_update.
     */
    private static function register_update()
    {
        if ('{{CURRENTVERSION}}' === self::$version || false !== strpos(self::$trunk, '{{PANELURL}}')) {
            return false;
        }

        $__varfunc_check_update = function ($res, $action, $args) {
            if ('plugin_information' !== $action) {
                return false;
            }

            if (self::$slug !== $args->slug) {
                return false;
            }

            $transient_key = self::$transientk . '/checkupdate';
            if (false == $remote = get_transient($transient_key)) {
                $remote = wp_remote_get(
                    self::$trunk,
                    [
                        'timeout' => 10,
                        'headers' => [
                            'Accept' => 'application/json',
                        ],
                    ]
                );

                if (!is_wp_error($remote) && isset($remote['response']['code']) && 200 === $remote['response']['code'] && !empty($remote['body'])) {
                    set_transient($transient_key, $remote, 43200); // 12 hours
                }
            }

            if (!is_wp_error($remote) && isset($remote['response']['code']) && 200 === $remote['response']['code'] && !empty($remote['body'])) {
                $remote = json_decode($remote['body']);
                $res    = new stdClass;

                $res->name     = $remote->name;
                $res->slug     = self::$slug;
                $res->version  = $remote->version;
                $res->tested   = $remote->tested;
                $res->requires = $remote->requires;
                $res->author   = $remote->author;

                if (!empty($remote->author_profile)) {
                    $res->author_profile = $remote->author_profile;
                }

                $res->download_link = $remote->download_url;
                $res->trunk         = $remote->download_url;
                $res->requires_php  = $remote->requires_php;
                $res->last_updated  = $remote->last_updated;

                $res->sections                 = [];
                $res->sections['description']  = $remote->sections->description;
                $res->sections['installation'] = $remote->sections->installation;

                if (!empty($remote->sections->changelog)) {
                    $res->sections['changelog'] = $remote->sections->changelog;
                }

                if (!empty($remote->sections->screenshots)) {
                    $res->sections['screenshots'] = $remote->sections->screenshots;
                }

                $res->banners         = [];
                $res->banners['low']  = $remote->banners->low;
                $res->banners['high'] = $remote->banners->high;

                return $res;
            }

            return false;
        };

        $__varfunc_push_update = function ($transient) {
            if (empty($transient->checked)) {
                return $transient;
            }

            $transient_key = self::$transientk . '/checkupdate';

            if (false == $remote = get_transient($transient_key)) {
                $remote = wp_remote_get(
                    self::$trunk,
                    [
                        'timeout' => 10,
                        'headers' => [
                            'Accept' => 'application/json',
                        ],
                    ]
                );

                if (!is_wp_error($remote) && isset($remote['response']['code']) && 200 === $remote['response']['code'] && !empty($remote['body'])) {
                    set_transient($transient_key, $remote, 43200); // 12 hours
                }
            }

            if ($remote && !is_wp_error($remote) && isset($remote['response']['code']) && 200 === $remote['response']['code'] && !empty($remote['body'])) {
                $remote = json_decode($remote['body']);
                if (is_object($remote) && version_compare(self::$version, $remote->version, '<') && version_compare($remote->requires, get_bloginfo('version'), '<')) {
                    $res              = new stdClass();
                    $res->id          = $remote->id;
                    $res->slug        = self::$slug;
                    $res->plugin      = self::$hook;
                    $res->new_version = $remote->version;
                    $res->tested      = $remote->tested;
                    $res->package     = $remote->download_url;
                    $res->url         = $remote->url;
                    $res->icons['1x'] = $remote->icon1;
                    if (isset($remote->icon2)) {
                        $res->icons['svg'] = $remote->icon2;
                    }
                    $transient->response[$res->plugin] = $res;
                }
            }

            return $transient;
        };

        add_filter('plugins_api', $__varfunc_check_update, 20, 3);
        add_filter('site_transient_update_plugins', $__varfunc_push_update);
    }

    /**
     * attach.
     */
    public static function attach()
    {
        self::register_init();
        self::register_cron_schedules();
        self::register_plugin_hooks();
        self::register_admin_hooks();
        self::register_purge_hooks();
        self::register_integrations();
        self::register_update();
    }
}

RunCloud_Hub::attach();
