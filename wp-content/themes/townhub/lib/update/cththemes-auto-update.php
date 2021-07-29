<?php
/* banner-php */

/**
* This is a modified fork of the original Envato Market WordPress Plugin 
* with availability to check update for included plugins from our premium theme.
* This script use purchase code to verify that customers are able update plugins.
**/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
define( 'CTHTHEMES_AUTO_UPDATE_PATH', trailingslashit( dirname(__FILE__) ) );

if(!class_exists('CTHthemesAutoUpdate\\Update')){
    require_once CTHTHEMES_AUTO_UPDATE_PATH . 'inc/update.php';
} 

if ( ! function_exists( 'cththemes_auto_update' ) ){
    function cththemes_auto_update() {
        return CTHthemesAutoUpdate\Update::instance();
    }
}
cththemes_auto_update();