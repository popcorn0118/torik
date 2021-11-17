<?php
/* banner-php */
/**
 * TownHub functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 */

/**
 * TownHub only works in WordPress 5.0 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '5.2', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
	return;
}

function townhub_get_footer_widgets_default(){
    return array(
        array(
            'title' => esc_attr__( 'About Us', 'townhub' ),
            'classes'  => 'col-sm-12 col-md-4',
            'widid'    => 'footer-about-us',

        ),
        array(
            'title' => esc_attr__( 'Our Last News', 'townhub' ),
            'classes'  => 'col-sm-12 col-md-4',
            'widid'    => 'footer-our-last-news',
        ),
        array(
            'title' => esc_attr__( 'Our Tweets', 'townhub' ),
            'classes'  => 'col-sm-12 col-md-4',
            'widid'    => 'footer-our-tweets',
        )
    );
}
function townhub_get_footer_widgets_top_default(){
    return array(
        array(
            'title' => esc_attr__( 'Subscribe Text', 'townhub' ),
            'classes'  => 'col-md-5',
            'widid'    => 'footer-subscribe-text',

        ),
        array(
            'title' => esc_attr__( 'Subscribe Form', 'townhub' ),
            'classes'  => 'col-md-7',
            'widid'    => 'footer-subscribe-form',
        ),
        
    );
}


if ( file_exists(get_template_directory() . '/includes/redux-configs.php')) {
    require_once get_template_directory() . '/includes/redux-configs.php';
}

if(!isset($townhub_options)) $townhub_options = get_option( 'townhub_options', array() );

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function townhub_setup() {
	/*
	 * Make theme available for translation.
	 */
	load_theme_textdomain( 'townhub' , get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	townhub_get_thumbnail_sizes();

	// Set the default content width.
	$GLOBALS['content_width'] = 724;

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'top'    => esc_html__( 'Top Menu', 'townhub' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
		'gallery',
		'audio',
	) );

	// Add theme support for Custom Logo.
	add_theme_support( 'custom-logo', array(
		'width'       => 225,
		'height'      => 44,
		'flex-width'  => true,
		'flex-height' => true,
		'header-text' => array( 'site-title', 'site-description' ),

	) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	

    add_theme_support( 'woocommerce'
    );

    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, and column width.
 	 */
	add_editor_style( array( 'assets/css/editor-style.css', townhub_fonts_url() ) );

    // deactivate new block editor
    remove_theme_support( 'widgets-block-editor' );
	
}
add_action( 'after_setup_theme', 'townhub_setup' );

if(!function_exists('townhub_get_thumbnail_sizes')){
    function townhub_get_thumbnail_sizes(){
    	// options default must have these values
    	if(!townhub_get_option('enable_custom_sizes')) return;
        $option_sizes = array(
        	'townhub-lgal'=>'thumb_size_opt_3',
        	'townhub-listing-grid'=>'thumb_size_opt_4',
        	'townhub-lcat-one'=>'thumb_size_opt_5',
        	'townhub-lcat-two'=>'thumb_size_opt_6',
        	'townhub-lcat-three'=>'thumb_size_opt_7',
        	'townhub-post-grid'=>'thumb_size_opt_8',
        	'townhub-featured-image'=>'thumb_size_opt_9',
        	'townhub-single-image'=>'thumb_size_opt_10',
        	'townhub-recent'=>'thumb_size_opt_11'
        );

       	foreach ($option_sizes as $name => $opt) {
       		$option_size = townhub_get_option($opt);
       		if($option_size !== false && is_array($option_size)){
       			$size_val = array(
       				'width' => (isset($option_size['width']) && !empty($option_size['width']) )? (int)$option_size['width'] : (int)'9999',
       				'height' => (isset($option_size['height']) && !empty($option_size['height']) )? (int)$option_size['height'] : (int)'9999',
       				'hard_crop' => (isset($option_size['hard_crop']) && !empty($option_size['hard_crop']) )? (bool)$option_size['hard_crop'] : (bool)'0',
       			);

       			add_image_size( $name, $size_val['width'], $size_val['height'], $size_val['hard_crop'] );
       		}
       	}
    }
}
/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function townhub_content_width() {

	$content_width = $GLOBALS['content_width'];


	// Check if is single post and there is no sidebar.
	if ( is_single() && ! is_active_sidebar( 'sidebar-1' ) ) {
		$content_width = 724;
	}

	/**
	 * Filter TownHub content width of the theme.
	 *
	 * @since TownHub 1.0
	 *
	 * @param int $content_width Content width in pixels.
	 */
	$GLOBALS['content_width'] = apply_filters( 'townhub_content_width', $content_width );
}
add_action( 'template_redirect', 'townhub_content_width', 0 );



/**
 * Add preconnect for Google Fonts.
 *
 * @since TownHub 1.0
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function townhub_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'townhub-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'townhub_resource_hints', 10, 2 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function townhub_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Blog Sidebar', 'townhub' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar on blog posts and archive pages.', 'townhub' ),
		'before_widget' => '<div id="%1$s" class="box-widget-item fl-wrap townhub-mainsidebar-widget main-sidebar-widget %2$s block_box clearfix">', 
        'before_title' => '<div class="box-widget-item-header"><h3 class="widget-title">', 
        'after_title' => '</h3></div>',
        'after_widget' => '</div>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Page Sidebar', 'townhub' ),
		'id'            => 'sidebar-2',
		'description' => esc_html__('Appears in the sidebar section of the page template.', 'townhub'), 
        'before_widget' => '<div id="%1$s" class="box-widget-item fl-wrap townhub-pagesidebar-widget page-sidebar-widget %2$s block_box clearfix">', 
        'before_title' => '<div class="box-widget-item-header"><h3 class="widget-title">', 
        'after_title' => '</h3></div>',
        'after_widget' => '</div>',
	) );

    register_sidebar( array(
        'name'          => esc_html__( 'Shop Sidebar', 'townhub' ),
        'id'            => 'sidebar-shop',
        'description' => esc_html__('Appears in the sidebar section of the shop pages.', 'townhub'), 
        'before_widget' => '<div id="%1$s" class="box-widget-item fl-wrap townhub-shopsidebar-widget shop-sidebar-widget %2$s block_box clearfix">', 
        'before_title' => '<div class="box-widget-item-header"><h3 class="widget-title">', 
        'after_title' => '</h3></div>',
        'after_widget' => '</div>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Languages Switcher', 'townhub' ),
        'id'            => 'header-languages',
        'description' => esc_html__('Appears in the header section of the pages.', 'townhub'), 
        'before_widget' => '<div id="%1$s" class="townhub-lang-curr-wrap %2$s">', 
        'before_title' => '<h3 class="widget-title widget-title-hide">', 
        'after_title' => '</h3>',
        'after_widget' => '</div>',
    ) );

    
    

    $footer_widgets = townhub_get_option('footer_widgets_top',array());
    if ($footer_widgets) {
        foreach ($footer_widgets as  $widget) {
            if($widget['title']&&$widget['classes']){
                $widid = isset($widget['widid']) ? $widget['widid'] : sanitize_title_with_dashes($widget['title']);
                register_sidebar(
                    array(
                        'name' => $widget['title'], 
                        'id' => $widid, 
                        'before_widget' => '<div id="%1$s" class="footer-widget widget %2$s">', 
                        'after_widget' => '</div>', 
                        'before_title' => '<h3 class="widgets-titles">', 
                        'after_title' => '</h3>',
                    )
                );
            }
        }
    }
    $footer_widgets = townhub_get_option('footer_widgets'); 
    if ($footer_widgets) {
        foreach ($footer_widgets as  $widget) {
            if($widget['title']&&$widget['classes']){
                $widid = isset($widget['widid']) ? $widget['widid'] : sanitize_title_with_dashes($widget['title']);
                register_sidebar(
                    array(
                        'name' => $widget['title'], 
                        'id' => $widid,
                        'before_widget' => '<div id="%1$s" class="footer-widget fl-wrap %2$s">', 
                        'after_widget' => '</div>', 
                        'before_title' => '<h3 class="wid-tit">', 
                        'after_title' => '</h3>',
                    )
                );
            }
        }
    }

    
    register_sidebar( array(
        'name'          => esc_html__( 'Footer Menu', 'townhub' ),
        'id'            => 'footer-menu',
        'description' => esc_html__('Appears in the subfooter section.', 'townhub'), 
        'before_widget' => '<div id="%1$s" class="townhub-footer-menu %2$s">', 
        'before_title' => '<h3 class="widget-title widget-title-hide">', 
        'after_title' => '</h3>',
        'after_widget' => '</div>',
    ) );

	

}
add_action( 'widgets_init', 'townhub_widgets_init' );


/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... and
 * a 'Continue reading' link.
 *
 * @since TownHub 1.0
 *
 * @param string $link Link to single post/page.
 * @return string 'Continue reading' link prepended with an ellipsis.
 */
function townhub_excerpt_more( $link ) {
	
	return ' &hellip; ';
}
add_filter( 'excerpt_more', 'townhub_excerpt_more' );


/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function townhub_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", get_bloginfo( 'pingback_url' ) );
	}
}
add_action( 'wp_head', 'townhub_pingback_header' );


/**
 * Register custom fonts.
 */
function townhub_fonts_url() {
	$fonts_url = '';
    $font_families     = array();

    
    if ( 'off' !== esc_html_x( 'on', 'Raleway font: on or off', 'townhub' ) ) {
        $font_families[] = 'Raleway:300,400,700,800,900';
    }

    
    if ( 'off' !== esc_html_x( 'on', 'Roboto font: on or off', 'townhub' ) ) {
        $font_families[] = 'Roboto:400,500,700,900';
    }


    if ( $font_families ) {
    	$query_args = array(
            'family' => urlencode( implode( '|', $font_families ) ),
			'display' => 'swap',
			'subset' => urlencode( 'cyrillic,vietnamese' ),
		);

        $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
    }



    return esc_url_raw( $fonts_url );

}

/**
 * Enqueue scripts and styles.
 */
function townhub_scripts() {
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'townhub-fonts', townhub_fonts_url(), array(), null );


	wp_enqueue_style( 'townhub-plugins', get_theme_file_uri( '/assets/css/plugins.css' ), array(  ), null );
    wp_style_add_data( 'townhub-plugins', 'rtl', 'replace' );
	// Theme stylesheet.
	wp_enqueue_style( 'townhub-style', get_stylesheet_uri() );
    wp_style_add_data( 'townhub-style', 'rtl', 'replace' );

    if( townhub_get_option('header_height', 80) != '80' ){
        wp_add_inline_style( 'townhub-style', townhub_headerstyle() );
    }
    

	wp_enqueue_style( 'townhub-color', get_theme_file_uri( '/assets/css/color.min.css' ), array(  ), null );

    // if(townhub_get_option('use_custom_color', false) && townhub_get_option('theme-color') != '#4DB7FE'){
    if( townhub_get_option('use_custom_color', false) ){
        wp_add_inline_style( 'townhub-color', townhub_overridestyle() );
    }

    $inline_custom_style = trim( townhub_get_option('custom-css','') );
    $medium_css = trim( townhub_get_option('custom-css-medium','') );
    if( !empty( $medium_css ) ){
        $inline_custom_style .= '@media only screen and  (max-width: 1064px){'.$medium_css.'}';
    }
    $tablet_css = trim( townhub_get_option('custom-css-tablet','') );
    if( !empty( $tablet_css ) ){
        $inline_custom_style .= '@media only screen and  (max-width: 768px){'.$tablet_css.'}';
    }
    $mobile_css = trim( townhub_get_option('custom-css-mobile','') );
    if( !empty( $mobile_css ) ){
        $inline_custom_style .= '@media only screen and  (max-width: 650px){'.$mobile_css.'}';
    }
    
    if (!empty($inline_custom_style)) {
        wp_add_inline_style('townhub-color', townhub_stripWhitespace($inline_custom_style) );
    }


    wp_enqueue_script("jquery-easing", get_theme_file_uri( '/assets/js/jquery.easing.min.js' ), array('jquery'), '1.4.0', true);
    wp_enqueue_script("jquery-appear", get_theme_file_uri( '/assets/js/jquery.appear.js' ) , array(), '0.3.6', true);
    wp_enqueue_script("scrollax", get_theme_file_uri( '/assets/js/Scrollax.js' ) , '1.0.0', true);
    wp_enqueue_script("jquery-countto", get_theme_file_uri( '/assets/js/jquery.countTo.js' ) , array(), null, true);
    wp_enqueue_script("single-page-nav", get_theme_file_uri( '/assets/js/navigation.js' ) , array(), null, true);


	wp_enqueue_script( 'townhub-scripts', get_theme_file_uri( '/assets/js/scripts.js' ), array( 'jquery', 'imagesloaded' ), null , true );
    wp_localize_script('townhub-scripts', '_townhub', array(
        'hheight' => townhub_get_option('header_height', 80),
    ));

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'townhub_scripts' );

// modify tag cloud
function townhub_widget_tag_cloud_args($args = array()){
    $args['number'] = 7; // default 45

    return $args;
}
add_filter( 'widget_tag_cloud_args', 'townhub_widget_tag_cloud_args' );
/**
 * Custom template tags for this theme.
 */
require get_parent_theme_file_path( '/inc/template-tags.php' );

/**
 * Additional features to allow styling of the templates.
 */
require get_parent_theme_file_path( '/inc/template-functions.php' );

require get_parent_theme_file_path( '/inc/layout/header.php' );
require get_parent_theme_file_path( '/inc/layout/footer.php' );


/**
 * SVG icons functions and filters.
 */
require get_parent_theme_file_path( '/inc/icon-functions.php' );
require get_parent_theme_file_path( '/inc/color-patterns.php' );


require_once get_parent_theme_file_path( '/inc/woo-init.php' );

if( class_exists('ElementorPro\\Plugin') ){
    require_once get_parent_theme_file_path( '/inc/elementor-pro-support.php' );
}

/**
 * Implement the One Click Demo Import plugin
 *
 * @since TownHub 1.0
 */
require_once get_parent_theme_file_path( '/inc/one-click-import-data.php' );
if( true == townhub_get_option('enable_auto_update') ){
    require_once get_parent_theme_file_path( '/lib/update/cththemes-auto-update.php' );
}

/**
 * Include the TGM_Plugin_Activation class.
 */
require_once get_parent_theme_file_path( '/lib/class-tgm-plugin-activation.php' );

add_action('tgmpa_register', 'townhub_register_required_plugins');

/**
 * Register the required plugins for this theme.
 *
 * In this example, we register five plugins:
 * - one included with the TGMPA library
 * - two from an external source, one from an arbitrary source, one from a GitHub repository
 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
 *
 * The variables passed to the `tgmpa()` function should be:
 * - an array of plugin arrays;
 * - optionally a configuration array.
 * If you are not changing anything in the configuration array, you can remove the array and remove the
 * variable from the function call: `tgmpa( $plugins );`.
 * In that case, the TGMPA default settings will be used.
 *
 * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
 */
function townhub_register_required_plugins() {
    /*
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(

        array('name' => esc_html__('Redux Framework','townhub'),
             // The plugin name.
            'slug' => 'redux-framework',
             // The plugin source.
            'required' => true,
             // If false, the plugin is only 'recommended' instead of required.
            'external_url' => esc_url(townhub_relative_protocol_url().'://wordpress.org/plugins/redux-framework/' ),
             // If set, overrides default API URL and points to an external URL.
            'function_to_check'         => '',
            'class_to_check'            => 'ReduxFramework'
        ), 


        array(
            'name' => esc_html__('Elementor Page Builder','townhub'),
             // The plugin name.
            'slug' => 'elementor',
             // The plugin slug (typically the folder name).
            'required' => true,
             // If false, the plugin is only 'recommended' instead of required.
            'external_url' => esc_url(townhub_relative_protocol_url().'://wordpress.org/plugins/elementor/' ),
             // If set, overrides default API URL and points to an external URL.

            'function_to_check'         => 'elementor_load_plugin_textdomain',
            'class_to_check'            => '\Elementor\Plugin'
        ), 

        array(
            'name' => esc_html__('Contact Form 7','townhub'),
             // The plugin name.
            'slug' => 'contact-form-7',
             // The plugin slug (typically the folder name).
            'required' => true,
             // If false, the plugin is only 'recommended' instead of required.
            'external_url' => esc_url(townhub_relative_protocol_url().'://wordpress.org/plugins/contact-form-7/' ),
             // If set, overrides default API URL and points to an external URL.

            'function_to_check'         => 'wpcf7',
            'class_to_check'            => 'WPCF7'
        ), 

        array(
            'name' => esc_html__('CMB2','townhub'),
             // The plugin name.
            'slug' => 'cmb2',
             // The plugin slug (typically the folder name).
            'required' => true,
             // If false, the plugin is only 'recommended' instead of required.
            'external_url' => esc_url(townhub_relative_protocol_url().'://wordpress.org/support/plugin/cmb2'),
             // If set, overrides default API URL and points to an external URL.

            'function_to_check'         => 'cmb2_bootstrap',
            'class_to_check'            => 'CMB2_Base'
        ),
        

        array(
            'name' => esc_html__('WooCommerce','townhub'),
             // The plugin name.
            'slug' => 'woocommerce',
             // The plugin slug (typically the folder name).
            'required' => false,
             // If false, the plugin is only 'recommended' instead of required.
            'external_url' => esc_url('https://wordpress.org/plugins/woocommerce/' ),
             // If set, overrides default API URL and points to an external URL.

            'function_to_check'         => '',
            'class_to_check'            => 'WooCommerce'
        ), 

        
        array(
            'name' => esc_html__('TownHub Add-ons','townhub' ),
             // The plugin name.
            'slug' => 'townhub-add-ons',
             // The plugin slug (typically the folder name).
            // 'source' => cththemes_auto_update()->api()->deferred_download( 5240, 'install_plugin' ), // 'townhub-add-ons.zip',
            'source' => 'townhub-add-ons.zip',
             // The plugin source.
            'required' => true,
             // If false, the plugin is only 'recommended' instead of required.

            'function_to_check'         => '',
            'class_to_check'            => 'TownHub_Addons'
        ), 

        array(
            'name' => esc_html__('TownHub WooCommerce Payments','townhub' ),
             // The plugin name.
            'slug' => 'townhub-woo-payments',
             // The plugin slug (typically the folder name).
            // 'source' => cththemes_auto_update()->api()->deferred_download( 5350, 'install_plugin' ), // 'townhub-add-ons.zip',
            'source' => 'townhub-woo-payments.zip',
             // The plugin source.
            'required' => true,
             // If false, the plugin is only 'recommended' instead of required.

            'function_to_check'         => '',
            'class_to_check'            => 'CTH_Woo_Payments'
        ), 
        

        

        array(
            'name' => esc_html__('Loco Translate','townhub'),
             // The plugin name.
            'slug' => 'loco-translate',
             // The plugin slug (typically the folder name).
            'required' => false,
             // If false, the plugin is only 'recommended' instead of required.
            'external_url' => esc_url(townhub_relative_protocol_url().'://wordpress.org/plugins/loco-translate/'),
             // If set, overrides default API URL and points to an external URL.

            'function_to_check'         => 'loco_autoload',
            'class_to_check'            => 'Loco_Locale'
        ), 

        array('name' => esc_html__('One Click Demo Import','townhub'),
             // The plugin name.
            'slug' => 'one-click-demo-import',
             // The plugin slug (typically the folder name).
            'required' => false,
             // If false, the plugin is only 'recommended' instead of required.
            'external_url' => esc_url(townhub_relative_protocol_url().'://wordpress.org/plugins/one-click-demo-import/'),
             // If set, overrides default API URL and points to an external URL.

            'function_to_check'         => '',
            'class_to_check'            => 'OCDI_Plugin'
        ),



        array('name' => esc_html__('Regenerate Thumbnails','townhub'),
             // The plugin name.
            'slug' => 'regenerate-thumbnails',
             // The plugin slug (typically the folder name).
            'required' => false,
             // If false, the plugin is only 'recommended' instead of required.
            'external_url' => esc_url(townhub_relative_protocol_url().'://wordpress.org/plugins/regenerate-thumbnails/' ),
             // If set, overrides default API URL and points to an external URL.

            'function_to_check'         => 'RegenerateThumbnails',
            'class_to_check'            => 'RegenerateThumbnails'
        ),

        // array('name' => esc_html__('WP Facebook Login for WordPress','townhub'),
        //      // The plugin name.
        //     'slug' => 'wp-facebook-login',
        //      // The plugin slug (typically the folder name).
        //     'required' => false,
        //      // If false, the plugin is only 'recommended' instead of required.
        //     'external_url' => esc_url(townhub_relative_protocol_url().'://wordpress.org/plugins/wp-facebook-login/' ),
        //      // If set, overrides default API URL and points to an external URL.

        //     'function_to_check'         => 'run_facebook_login',
        //     'class_to_check'            => 'Facebook_Login'
        // ),

        array('name' => esc_html__('WordPress Social Login (Facebook, Google, Twitter)','townhub'),
             // The plugin name.
            'slug' => 'miniorange-login-openid',
             // The plugin slug (typically the folder name).
            'required' => false,
             // If false, the plugin is only 'recommended' instead of required.
            'external_url' => esc_url(townhub_relative_protocol_url().'://wordpress.org/plugins/miniorange-login-openid/' ),
             // If set, overrides default API URL and points to an external URL.

            'function_to_check'         => 'mo_register_openid',
            'class_to_check'            => 'miniorange_openid_sso_settings'
        ),


    );

    /*
     * Array of configuration settings. Amend each line as needed.
     *
     * TGMPA will start providing localized text strings soon. If you already have translations of our standard
     * strings available, please help us make TGMPA even better by giving us access to these translations or by
     * sending in a pull-request with .po file(s) with the translations.
     *
     * Only uncomment the strings in the config array if you want to customize the strings.
     */
    $config = array(
        'id'           => 'townhub',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => get_template_directory() . '/lib/plugins/',                      // Default absolute path to bundled plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.

        
    );

    tgmpa( $plugins, $config );
}
