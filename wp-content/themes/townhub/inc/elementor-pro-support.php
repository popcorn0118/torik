<?php
/* banner-php */

use Elementor\TemplateLibrary\Source_Local;
use ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager;
use ElementorPro\Modules\ThemeBuilder\Module;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class CTH_Theme_ElePro_Support {

    /**
     * @param Locations_Manager $manager
     */
    public function register_locations( $manager ) {
        $manager->register_core_location( 'header' );
        $manager->register_core_location( 'footer' );
    }

    public function metabox_capability( $capability ) {
        if ( Source_Local::CPT === get_post_type() ) {
            $capability = 'do_not_allow';
        }

        return $capability;
    }

    public function do_header() {
        $did_location = Module::instance()->get_locations_manager()->do_location( 'header' );
        if ( $did_location ) {
            remove_action( 'townhub_header', 'townhub_header_content' );
        }
    }

    public function do_footer() {
        $did_location = Module::instance()->get_locations_manager()->do_location( 'footer' );
        if ( $did_location ) {
            remove_action( 'townhub_footer', 'townhub_footer_content' );
        }
    }

    public function body_classes( $classes ) {
        if ( in_array( 'elementor-template-full-width', $classes ) ) {
            $classes[] = 'full-width-content';
        }

        return $classes;
    }

    public function __construct() {
        add_action( 'elementor/theme/register_locations', [ $this, 'register_locations' ], 11 );
        // add_filter( 'generate_metabox_capability', [ $this, 'metabox_capability' ] );

        add_action( 'townhub_header', [ $this, 'do_header' ], 0 );
        add_action( 'townhub_footer', [ $this, 'do_footer' ], 0 );

        add_filter( 'body_class', [ $this, 'body_classes' ], 11 );

        
    }
}

new CTH_Theme_ElePro_Support();

