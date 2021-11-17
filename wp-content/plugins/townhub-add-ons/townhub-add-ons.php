<?php
/*
Plugin Name: TownHub Add-Ons
Plugin URI: https://townhub.cththemes.com
Description: A custom plugin for TownHub - Directory & Listing WordPress Theme
Version: 1.6.5
Author: CTHthemes
Author URI: http://themeforest.net/user/cththemes
Text Domain: townhub-add-ons
Domain Path: /languages/
Copyright: ( C ) 2014 - 2021 cththemes.com . All rights reserved.
License: GNU General Public License version 3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/
if ( ! defined('ABSPATH') ) {
    die('Please do not load this file directly!');
}

// require_once( ABSPATH . '/wp-includes/pluggable.php' );

if ( ! defined( 'ESB_PLUGIN_FILE' ) ) {
    define( 'ESB_PLUGIN_FILE', __FILE__ );
}
if ( ! defined( 'CTH_DEMO' ) ) {
    define( 'CTH_DEMO', false );
}
if ( ! class_exists( 'TownHub_Addons' ) ) {
    include_once dirname( __FILE__ ) . '/includes/class-addons.php';
}

function ESB_ADO() {
    return TownHub_Addons::getInstance();
}

ESB_ADO();
