<?php
/* banner-php */
    /**
     * ReduxFramework Sample Config File
     * For full documentation, please visit: http://docs.reduxframework.com/ 
     */

    if ( ! class_exists( 'Redux' ) ) {
        return;
    }


    // This is your option name where all the Redux data is stored.
    $opt_name = "townhub_options";

    // This line is only for altering the demo. Can be easily removed.

    /*
     *
     * --> Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
     *
     */
    $theme = wp_get_theme(); // For use with some settings. Not necessary.

    $args = array(
        // TYPICAL -> Change these values as you need/desire
        'opt_name'             => $opt_name,
        // This is where your data is stored in the database and also becomes your global variable name.
        'display_name'         => $theme->get( 'Name' ),
        // Name that appears at the top of your panel
        'display_version'      => $theme->get( 'Version' ),
        // Version that appears at the top of your panel
        'menu_type'            => 'menu',
        //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
        'allow_sub_menu'       => true,
        // Show the sections below the admin menu item or not
        'menu_title'           => esc_html__( 'TownHub Options', 'townhub' ),
        'page_title'           => esc_html__( 'TownHub Options', 'townhub' ),
        // You will need to generate a Google API key to use this feature.
        // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
        'google_api_key'       => '',
        // Set it you want google fonts to update weekly. A google_api_key value is required.
        'google_update_weekly' => false,
        // Must be defined to add google fonts to the typography module
        'async_typography'     => false,
        // Use a asynchronous font on the front end or font string
        'disable_google_fonts_link' => false,                    
        // Disable this in case you want to create your own google fonts loader
        'admin_bar'            => true,
        // Show the panel pages on the admin bar
        'admin_bar_icon'       => 'dashicons-portfolio',
        // Choose an icon for the admin bar menu
        'admin_bar_priority'   => 50,
        // Choose an priority for the admin bar menu
        'global_variable'      => false,
        // Set a different name for your global variable other than the opt_name
        'dev_mode'             => false,
        // Show the time the page took to load, etc
        'forced_dev_mode_off'   => false,
        // to forcefully disable dev mode
        'update_notice'        => false,
        // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
        'customizer'           => true,
        // Enable basic customizer support
        'open_expanded'     => false,                    
        // Allow you to start the panel in an expanded way initially.
        'disable_save_warn' => false,                    
        // Disable the save warning when a user changes a field

        // OPTIONAL -> Give you extra features
        'page_priority'        => null,
        // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
        'page_parent'          => 'themes.php',
        // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
        'page_permissions'     => 'manage_options',
        // Permissions needed to access the options panel.
        'menu_icon'            => '',
        // Specify a custom URL to an icon
        'last_tab'             => '',
        // Force your panel to always open to a specific tab (by id)
        'page_icon'            => 'icon-themes',
        // Icon displayed in the admin panel next to your menu_title
        'page_slug'            => '',
        // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
        'save_defaults'        => true,
        // On load save the defaults to DB before user clicks save or not
        'default_show'         => false,
        // If true, shows the default value next to each field that is not the default value.
        'default_mark'         => '',
        // What to print by the field's title if the value shown is default. Suggested: *
        'show_import_export'   => true,
        // Shows the Import/Export panel when not used as a field.

        // CAREFUL -> These options are for advanced use only
        'transient_time'       => 60 * MINUTE_IN_SECONDS,
        'output'               => true,
        // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
        'output_tag'           => true,
        // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
        'footer_credit'     => '',                   
        // Disable the footer credit of Redux. Please leave if you can help it.

        // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
        'database'             => '',
        // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
        'use_cdn'              => true,
        // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

        // HINTS
        'hints'                => array(
            'icon'          => 'el el-question-sign',
            'icon_position' => 'right',
            'icon_color'    => 'lightgray',
            'icon_size'     => 'normal',
            'tip_style'     => array(
                'color'   => 'red',
                'shadow'  => true,
                'rounded' => false,
                'style'   => '',
            ),
            'tip_position'  => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect'    => array(
                'show' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'mouseover',
                ),
                'hide' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'click mouseleave',
                ),
            ),
        )
    );


    // Panel Intro text -> before the form
    if ( ! isset( $args['global_variable'] ) || $args['global_variable'] !== false ) {
        if ( ! empty( $args['global_variable'] ) ) {
            $v = $args['global_variable'];
        } else {
            $v = str_replace( '-', '_', $args['opt_name'] );
        }
        $args['intro_text'] = sprintf( wp_kses(__( '<p><strong>TownHub - Creative  Responsive  Architecture WordPress Theme</strong> is perfect if you like a clean and modern design. This theme is ideal for designers, photographers, and those who need an easy, attractive and effective way to share their work with clients.</p>', 'townhub' ),array('p'=>array(),'strong'=>array()) ) , $v );
    } else {
        $args['intro_text'] =  wp_kses(__( '<p><strong>TownHub - Creative  Responsive  Architecture WordPress Theme</strong> is perfect if you like a clean and modern design. This theme is ideal for designers, photographers, and those who need an easy, attractive and effective way to share their work with clients.</p>', 'townhub' ),array('p'=>array(),'strong'=>array()) );
    }

    // Add content after the form.
    $args['footer_text'] = '<p>'.esc_html__('Thanks all of you who stay with us, your co-operation is our inspiration.','townhub' ).' <a href="'.esc_url('http://themeforest.net/user/cththemes/portfolio/' ).'" target="_blank">CTHthemes</a></p>';

    Redux::setArgs( $opt_name, $args );

    /*
     * ---> END ARGUMENTS
     */



    /*
     *
     * ---> START SECTIONS
     *
     */

    /*

        As of Redux 3.5+, there is an extensive API. This API can be used in a mix/match mode allowing for


     */
        //////////////// CUSTOM ///////////////

    require get_parent_theme_file_path( '/includes/redux-sections/general.php' );
    require get_parent_theme_file_path( '/includes/redux-sections/header.php' );
    require get_parent_theme_file_path( '/includes/redux-sections/footer.php' );
    require get_parent_theme_file_path( '/includes/redux-sections/thumbnails.php' );
    
    require get_parent_theme_file_path( '/includes/redux-sections/colors.php' );
    require get_parent_theme_file_path( '/includes/redux-sections/fonts.php' );
    
    require get_parent_theme_file_path( '/includes/redux-sections/blog.php' );
    require get_parent_theme_file_path( '/includes/redux-sections/blog-single.php' );
    require get_parent_theme_file_path( '/includes/redux-sections/shop.php' );
    
    require get_parent_theme_file_path( '/includes/redux-sections/protected.php' );
    require get_parent_theme_file_path( '/includes/redux-sections/404.php' );
    
    require get_parent_theme_file_path( '/includes/redux-sections/custom-code.php' );
    

    




    


    

    






    

    

    /*
     * <--- END SECTIONS
     */

    /*
     *
     * YOU MUST PREFIX THE FUNCTIONS BELOW AND ACTION FUNCTION CALLS OR ANY OTHER CONFIG MAY OVERRIDE YOUR CODE.
     *
     */

    /*
    *
    * --> Action hook examples
    *
    */

    // If Redux is running as a plugin, this will remove the demo notice and links
    // add_action( 'redux/loaded', 'remove_demo' );


    add_filter( "redux/" . $opt_name . "/field/class/thumbnail_size", "overload_thumbnail_size_field_path" ); // Adds the local field
    add_filter( "redux/" . $opt_name . "/field/class/image_url", "townhub_load_image_url_field_class" ); // Adds the local field
    add_filter( "redux/" . $opt_name . "/field/class/image_id", "townhub_load_image_id_field_class" ); // Adds the local field
    add_filter( "redux/" . $opt_name . "/field/class/cthimage", "townhub_load_cthimage_field_class" ); // Adds the local field
    add_filter( "redux/" . $opt_name . "/field/class/fwidget", "townhub_load_fwidget_field_class" ); // Adds the local field

    function overload_thumbnail_size_field_path($field) {

        return get_template_directory().'/includes/redux_add_fields/field_thumbnail_size.php';
    }

    function townhub_load_image_url_field_class($field) {
        return get_template_directory().'/includes/redux_add_fields/field_image_url.php';
    }
    function townhub_load_image_id_field_class($field) {
        return get_template_directory().'/includes/redux_add_fields/field_image_id.php';
    }
    function townhub_load_cthimage_field_class($field) {
        return get_template_directory().'/includes/redux_add_fields/field_cthimage.php';
    }
    function townhub_load_fwidget_field_class($field) {
        return get_template_directory().'/includes/redux_add_fields/field_fwidget.php';
    }


    /**
     * This is a test function that will let you see when the compiler hook occurs.
     * It only runs if a field    set with compiler=>true is changed.
     * */
    if ( ! function_exists( 'compiler_action' ) ) {
        function compiler_action( $options, $css, $changed_values ) {
            echo '<h1>The compiler hook has run!</h1>';
            echo "<pre>";
            print_r( $changed_values ); // Values that have changed since the last save
            echo "</pre>";
        }
    }

    /**
     * Custom function for the callback validation referenced above
     * */
    if ( ! function_exists( 'redux_validate_callback_function' ) ) {
        function redux_validate_callback_function( $field, $value, $existing_value ) {
            $error   = false;
            $warning = false;

            //do your validation
            if ( $value == 1 ) {
                $error = true;
                $value = $existing_value;
            } elseif ( $value == 2 ) {
                $warning = true;
                $value   = $existing_value;
            }

            $return['value'] = $value;

            if ( $error == true ) {
                $return['error'] = $field;
                $field['msg']    = 'your custom error message';
            }

            if ( $warning == true ) {
                $return['warning'] = $field;
                $field['msg']      = 'your custom warning message';
            }

            return $return;
        }
    }

    /**
     * Custom function for the callback referenced above
     */
    if ( ! function_exists( 'redux_my_custom_field' ) ) {
        function redux_my_custom_field( $field, $value ) {
            print_r( $field );
            echo '<br/>';
            print_r( $value );
        }
    }

    /**
     * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
     * Simply include this function in the child themes functions.php file.
     * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
     * so you must use get_template_directory_uri() if you want to use any of the built in icons
     * */
    if ( ! function_exists( 'dynamic_section' ) ) {
        function dynamic_section( $sections ) {
            //$sections = array();
            $sections[] = array(
                'title'  => esc_html__( 'Section via hook', 'townhub' ),
                'desc'   => wp_kses(__( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'townhub' ),array('p'=>array('class'=>array())) ),
                'icon'   => 'el el-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }
    }

    /**
     * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
     * */
    if ( ! function_exists( 'change_arguments' ) ) {
        function change_arguments( $args ) {
            //$args['dev_mode'] = true;

            return $args;
        }
    }

    /**
     * Filter hook for filtering the default value of any given field. Very useful in development mode.
     * */
    if ( ! function_exists( 'change_defaults' ) ) {
        function change_defaults( $defaults ) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }
    }

    add_action('admin_footer', function(){
    ?>
        <script type="text/template" id="tmpl-redux-fwidget">
            <?php townhub_get_template_part('includes/redux_add_fields/tmpl-fwidget');?>
        </script>
    <?php
    });
