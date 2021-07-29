<?php
/* banner-php */
/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function townhub_body_classes( $classes ) {
	// Add class for fullscreen and fixed footer

    $classes[] = 'body-townhub';

	$classes[] = 'folio-archive-'.townhub_get_option('folio_layout');


    if(post_password_required()) $classes[] = 'is-protected-page';

	// Add class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Add class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Add class if we're viewing the Customizer for easier styling of theme options.
	if ( is_customize_preview() ) {
		$classes[] = 'townhub-customizer';
	}

	// Add class on front page.
	if ( is_front_page() && 'posts' !== get_option( 'show_on_front' ) ) {
		$classes[] = 'townhub-front-page';
	}
    if( townhub_is_woocommerce_activated() ){
        if( $shop_columns = townhub_get_option('shop_columns') ){
            $classes[] = 'woo-'.$shop_columns;
        }
        if( $shop_columns_tablet = townhub_get_option('shop_columns_tablet') ){
            $classes[] = 'woo-tablet-'.$shop_columns_tablet;
        }
    }
        

	return $classes;
}
add_filter( 'body_class', 'townhub_body_classes' );



/**
 * Return attachment image link by using wp_get_attachment_image_src function
 *
 */
function townhub_get_attachment_thumb_link( $id, $size = 'thumbnail' ){
    $image_attributes = wp_get_attachment_image_src( $id, $size, false );
    if ( $image_attributes ) {
        return $image_attributes[0];
    }
    return '';
}

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for content images.
 *
 * @since TownHub 1.2
 *
 * @param string $sizes A source size value for use in a 'sizes' attribute.
 * @param array  $size  Image size. Accepts an array of width and height
 *                      values in pixels (in that order).
 * @return string A source size value for use in a content image 'sizes' attribute.
 */
function townhub_content_image_sizes_attr( $sizes, $size ) {
    return '';
}

add_filter( 'wp_calculate_image_sizes', 'townhub_content_image_sizes_attr', 10, 2 );


if(!function_exists('townhub_get_template_part')){
    /**
     * Load a template part into a template
     *
     * Makes it easy for a theme to reuse sections of code in a easy to overload way
     * for child themes.
     *
     * Includes the named template part for a theme or if a name is specified then a
     * specialised part will be included. If the theme contains no {slug}.php file
     * then no template will be included.
     *
     * The template is included using require, not require_once, so you may include the
     * same template part multiple times.
     *
     * For the $name parameter, if the file is called "{slug}-special.php" then specify
     * "special".
      * For the var parameter, simple create an array of variables you want to access in the template
     * and then access them e.g. 
     * 
     * array("var1=>"Something","var2"=>"Another One","var3"=>"heres a third";
     * 
     * becomes
     * 
     * $var1, $var2, $var3 within the template file.
     *
     *
     * @param string $slug The slug name for the generic template.
     * @param string $name The name of the specialised template.
     * @param array $vars The list of variables to carry over to the template
     * @author CTHthemes 
     * @ref http://www.zmastaa.com/2015/02/06/php-2/wordpress-passing-variables-get_template_part
     * @ref http://keithdevon.com/passing-variables-to-get_template_part-in-wordpress/
     */
    function townhub_get_template_part( $xxxslug, $xxxname = null, $xxxvars = null ) {
        $xxxtemplate = "{$xxxslug}.php";
        $xxxname      = (string) $xxxname;
        if ( '' !== $xxxname ) {
            $xxxtemplate = "{$xxxslug}-{$xxxname}.php";
        }
        $xxxlocated = locate_template($xxxtemplate, false);
        if($xxxlocated !== ''){
            /*
             * This use of extract() cannot be removed. There are many possible ways that
             * templates could depend on variables that it creates existing, and no way to
             * detect and deprecate it.
             *
             * Passing the EXTR_SKIP flag is the safest option, ensuring globals and
             * function variables cannot be overwritten.
             */
            // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
            if(isset($xxxvars)) extract($xxxvars, EXTR_SKIP);
            include $xxxlocated;
        }
    }
}
if(!function_exists('townhub_get_the_password_form')){
    function townhub_get_the_password_form($post = 0){
        $post = get_post( $post );
        $label = 'pwbox-' . ( empty($post->ID) ? rand() : $post->ID );
        $output = '<p>' . esc_html__( 'This content is password protected. To view it please enter your password below:' , 'townhub') . '</p><form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="post-password-form" method="post"><input name="post_password" id="' . $label . '" type="password" size="20" /><button type="submit" class="search-submit color2-bg"><i class="fal fa-eye"></i></button></form>
        ';

        return $output ;
    }
}
add_filter('the_password_form','townhub_get_the_password_form' );

if(!function_exists('townhub_get_kirki_dynamic_css')){
    function townhub_get_kirki_dynamic_css($styles){
        if(townhub_get_option('use_custom_color', false)){
            return $styles;
        }else{
            return '';
        }
    }
}
add_filter('kirki/townhub_configs/dynamic_css','townhub_get_kirki_dynamic_css' );


/**
 * Modify category count format
 *
 * @since TownHub 1.0
 */
function townhub_custom_category_count_widget($output) {
    return preg_replace("/<\/a>\s*(\([\d]+\))\s*</", '</a><span>$1</span><', $output);
    
}
add_filter('wp_list_categories', 'townhub_custom_category_count_widget');

/**
 * Modify archive count format
 *
 * @since TownHub 1.0
 */
function townhub_custom_archives_count_widget($link_html) {
    return preg_replace("/&nbsp;([\s(\d)]*)/", '<span>$1</span>', $link_html);
}
add_filter('get_archives_link', 'townhub_custom_archives_count_widget');


function townhub_relative_protocol_url(){
    return is_ssl() ? 'https' : 'http';
}
