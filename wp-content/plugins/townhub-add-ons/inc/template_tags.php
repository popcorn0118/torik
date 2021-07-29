<?php
/* add_ons_php */



function townhub_addons_get_user_role( $user_id = 0 ) {
    // https://core.trac.wordpress.org/ticket/22624
    $user = ( $user_id ) ? get_userdata( $user_id ) : wp_get_current_user();   
    if( !$user || null == $user->roles || empty( $user->roles ) ) {
        return 'subscriber';
    }
    return current( $user->roles );
}
function townhub_addons_get_user_role_name($role = ''){
    if($role == '') $role = townhub_addons_get_user_role();
    global $wp_roles;
    $textdomain = 'default';
    if( in_array( $role, array('l_customer', 'listing_author') ) ){
        $textdomain = 'townhub-add-ons';
    }
    return translate_user_role( $wp_roles->roles[ $role ]['name'] , $textdomain);
}
// not used yet
function townhub_addons_get_author_roles( $include_admin = false ){
    if ( ! function_exists( 'get_editable_roles' ) ) {
        require_once ABSPATH . 'wp-admin/includes/user.php';
    }
    $roles = array();
    $editable_roles = array_reverse( get_editable_roles() );
    foreach ( $editable_roles as $role => $details ) {
        // $name = translate_user_role($details['name'] );
        // exclude 'administrator'
        if( $include_admin || ( $include_admin == false && 'administrator' !== $role ) ) $roles[esc_attr( $role )] = translate_user_role( $details['name'] , 'townhub-add-ons');
    }

    return $roles;
}

function townhub_addons_current_user_can($custom_cap = 'submit_listing'){
    $user_can = false;
    // check for submit listing capability
    if($custom_cap === 'submit_listing'){
        if( townhub_addons_get_option('users_can_submit_listing') == 'yes' ){
            $user_can = true;
        }elseif(current_user_can( 'submit_listing' )){
            $current_role = townhub_addons_get_user_role();
            if($current_role == 'administrator'){
                $user_can = true;
            }else{
                

                $user_current_subscription = townhub_addons_get_current_subscription();
                if($user_current_subscription){
                    $order_listings = get_post_meta( $user_current_subscription['id'], ESB_META_PREFIX.'listings', true );
                    if($user_current_subscription['plan_llimit'] == 'unlimited' || count((array)$order_listings) < (int)$user_current_subscription['plan_llimit'] ) $user_can = true;
                }
            }
            // end if not administrator
        }

        // if( townhub_addons_get_option('users_can_submit_listing') == 'yes' || current_user_can( 'submit_listing' ) ){
        //     $user_can = true;
        // }
    }
    if($custom_cap === 'view_listings_dashboard'){
        if( townhub_addons_get_option('users_can_submit_listing') == 'yes' || current_user_can( 'submit_listing' )){
            $user_can = true;
        }
    }
    // return false;
    return $user_can;
}

// function townhub_addons_get_submit_link(){
//     $current_sub = townhub_addons_get_current_subscription();
//     // if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). "Current subscription: " .json_encode($current_sub). PHP_EOL, 3, ESB_LOG_FILE);
//     // if($current_sub && $current_sub['valid']) 
//     $free_submit_page = esb_addons_get_wpml_option('free_submit_page');
//     if($free_submit_page == 'default' || ($current_sub && $current_sub['valid']) ){
//         $submit_link = get_permalink( esb_addons_get_wpml_option('submit_page') );
//     }else{
//         $submit_link = get_permalink( esb_addons_get_wpml_option('free_submit_page') );
//     }

//     return esc_url($submit_link);
// }

/**
 * Return attachment image link by using wp_get_attachment_image_src function
 *
 */
function townhub_addons_get_attachment_thumb_link( $id, $size = 'thumbnail'){
    $image_attributes = wp_get_attachment_image_src( $id, $size, false );
    if ( $image_attributes ) {
        return $image_attributes[0];
    }
    return '';
}

function townhub_addons_get_current_url(){
    global $wp;
    // get current page with query string
    return add_query_arg( $_SERVER['QUERY_STRING'], '', home_url( $wp->request ) );

    
    // $current_url = home_url(add_query_arg(array(),$wp->request));
    // return $current_url;
}

/** 
 * get template part file related to plugin folder
 *
 */
if(!function_exists('townhub_addons_get_template_part')){
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
    function townhub_addons_get_template_part( $xxxslug, $xxxname = null, $xxxvars = null, $xxxinclude = true ) {

        $xxxtemplate = "{$xxxslug}.php";
        $xxxname = (string) $xxxname;
        // if ( '' !== $xxxname && file_exists( ESB_ABSPATH ."{$xxxslug}-{$xxxname}.php" ) ) {
        if ( '' !== $xxxname ) {
            $xxxtemplate = "{$xxxslug}-{$xxxname}.php";
        }

        if(isset($xxxvars)) extract($xxxvars, EXTR_SKIP);
        if($xxxlocated = locate_template( 'cth_listing/'.$xxxtemplate )){
            if($xxxinclude) 
                include $xxxlocated;
            else 
                return $xxxlocated;
        }else{
            if($xxxinclude) 
                include ESB_ABSPATH.$xxxtemplate;
            else 
                return ESB_ABSPATH.$xxxtemplate;
            
        }
        // include(townhub_addons_locate_template($template));
    }

 //    function townhub_addons_locate_template($template_names, $load = false, $require_once = true ) {
    //  $located = '';
    //  foreach ( (array) $template_names as $template_name ) {
    //      if ( !$template_name )
    //          continue;
    //      if ( file_exists(ESB_ABSPATH . '/' . $template_name)) {
    //          $located = ESB_ABSPATH . '/' . $template_name;
    //          break;
    //      } elseif ( file_exists(ESB_ABSPATH . '/' . $template_name) ) {
    //          $located = ESB_ABSPATH . '/' . $template_name;
    //          break;
    //      } elseif ( file_exists( ABSPATH . WPINC . '/theme-compat/' . $template_name ) ) {
    //          $located = ABSPATH . WPINC . '/theme-compat/' . $template_name;
    //          break;
    //      }
    //  }

    //  if ( $load && '' != $located )
    //      load_template( $located, $require_once );

    //  return $located;
    // }
}





function townhub_addons_generate_timezone_list()
{
    static $regions = array(
        DateTimeZone::AFRICA,
        DateTimeZone::AMERICA,
        DateTimeZone::ANTARCTICA,
        DateTimeZone::ASIA,
        DateTimeZone::ATLANTIC,
        DateTimeZone::AUSTRALIA,
        DateTimeZone::EUROPE,
        DateTimeZone::INDIAN,
        DateTimeZone::PACIFIC,
    );

    $timezones = array();
    foreach( $regions as $region )
    {
        $timezones = array_merge( $timezones, DateTimeZone::listIdentifiers( $region ) );
    }

    $timezone_offsets = array();
    foreach( $timezones as $timezone )
    {
        $tz = new DateTimeZone($timezone);
        $timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
    }

    // sort timezone by offset
    asort($timezone_offsets);

    $timezone_list = array();
    foreach( $timezone_offsets as $timezone => $offset )
    {
        $offset_prefix = $offset < 0 ? '-' : '+';
        $offset_formatted = gmdate( 'H:i', abs($offset) );

        $pretty_offset = "UTC${offset_prefix}${offset_formatted}";

        $timezone_list[$timezone] = "(${pretty_offset}) $timezone";
    }

    return $timezone_list;
    // https://developer.wordpress.org/reference/functions/wp_timezone_choice/
}

function townhub_addons_get_meta_values( $key = '', $type = 'post', $status = 'publish' ) {
    global $wpdb;
    $metas = array();
    if( empty( $key ) )
        return $metas;
    if( is_array( $status ) && count( $status ) ){
        $w_status = " AND (";

        $statuswheres = array();

        foreach ($status as $stval) {
            $statuswheres[] = $wpdb->prepare("p.post_status = %s", $stval);
        }

        $w_status .= implode(' OR ', $statuswheres);

        $w_status .= ") ";

    }else{
        $w_status = $wpdb->prepare(" AND p.post_status = %s", $status);
    }
    
    $r = $wpdb->get_results( $wpdb->prepare( "
        SELECT p.ID, pm.meta_value FROM {$wpdb->postmeta} pm
        LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        WHERE pm.meta_key = '%s' 
        AND p.post_type = '%s'
        $w_status 
    ", $key, $type ));

    foreach ( $r as $my_r )
        $metas[$my_r->ID] = $my_r->meta_value;

    return $metas;
}


function townhub_addons_re_format_date( $string,$format = 'Y-m-d H:i:s' ){
    $datetime = date_create( $string );
    if ( ! $datetime ) {
        return gmdate( $format );
    }
    return $datetime->format( $format );
}

function townhub_addons_get_gmt_from_date( $string, $tz = '', $format = 'Y-m-d H:i:s' ){
    if( !$tz ) $tz = get_option( 'timezone_string' );
    if ( $tz ) {
            $datetime = date_create( $string, new DateTimeZone( $tz ) );
            if ( ! $datetime ) {
                    return gmdate( $format, 0 );
            }
            $datetime->setTimezone( new DateTimeZone( 'UTC' ) );
            $string_gmt = $datetime->format( $format );
    } else {
            if ( ! preg_match( '#([0-9]{1,4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})#', $string, $matches ) ) {
                    $datetime = strtotime( $string );
                    if ( false === $datetime ) {
                            return gmdate( $format, 0 );
                    }
                    return gmdate( $format, $datetime );
            }
            $string_time = gmmktime( $matches[4], $matches[5], $matches[6], $matches[2], $matches[3], $matches[1] );
            $string_gmt = gmdate( $format, $string_time - get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );
    }
    return $string_gmt;
}



function townhub_addons_get_utc_time($format = 'Y-m-d H:i:s'){

    // $currentDateTime = new DateTime('now', new DateTimeZone( 'UTC' ));
    return (new DateTime('now', new DateTimeZone( 'UTC' )))->format($format);
}



function townhub_addons_get_socials_list(){

    $socials = array(
        'facebook-f' => __( 'Facebook',  'townhub-add-ons' ),
        'twitter' => __( 'Twitter',  'townhub-add-ons' ),
        'youtube' => __( 'Youtube',  'townhub-add-ons' ),
        'vimeo-v' => __( 'Vimeo',  'townhub-add-ons' ),
        'instagram' => __( 'Instagram',  'townhub-add-ons' ),
        'flickr' => __( 'Flickr',  'townhub-add-ons' ),
        'whatsapp' => __( 'Whatsapp',  'townhub-add-ons' ),
        'telegram' => __( 'Telegram',  'townhub-add-ons' ),
    );

    $socials = (array)apply_filters( 'cth_socials_list', $socials );

    return $socials ;

}  

function townhub_addons_get_listing_price_range($range = '', $single = false){

    $ranges = array(
        'none' => __( 'Unset',  'townhub-add-ons' ),
        'inexpensive' => __( 'Inexpensive',  'townhub-add-ons' ),
        'moderate' => __( 'Moderate',  'townhub-add-ons' ),
        'pricey' => __( 'Pricey',  'townhub-add-ons' ),
        'ultrahigh' => __( 'Ultra High',  'townhub-add-ons' ),
    );

    $ranges = (array)apply_filters( 'esb_price_ranges', $ranges );
    if($range !='' && isset($ranges[$range])) 
        return $ranges[$range];

    if($single) 
        return _x( '', 'no price range text', 'townhub-add-ons' );
    else
        return $ranges;

}  
function townhub_addons_get_price_range_rate($range = ''){

    $ranges = array(
        'none' => 0,
        'inexpensive' => 1,
        'moderate' => 2,
        'pricey' => 3,
        'ultrahigh' => 4,
    );

    $ranges = (array)apply_filters( 'esb_price_ranges_rate', $ranges );
    if($range !='' && isset($ranges[$range])) 
        return $ranges[$range];

    return 0;
}  
// facebook login
// https://www.codexworld.com/login-with-facebook-using-php/
// https://stackoverflow.com/questions/12069703/facebook-login-for-wordpress-without-a-plugin
// https://developers.facebook.com/docs/php/howto/example_facebook_login
// https://developers.facebook.com/docs/facebook-login/web#logindialog 


function townhub_addons_the_excerpt_max_charlength($charlength = 150, $echo = true, $id = null ) {
    $excerpt = get_the_excerpt($id);
    $excerpt = strip_tags($excerpt);
    $charlength++;

    $return = $excerpt;

    if ( mb_strlen( $excerpt ) > $charlength ) {
        $subex = mb_substr( $excerpt, 0, $charlength - 5 );
        $exwords = explode( ' ', $subex );
        $excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
        if ( $excut < 0 ) {
            $return = mb_substr( $subex, 0, $excut );
        } else {
            $return = $subex;
        }
        $return .= esc_html__( '...', 'townhub-add-ons' );
    }
    if(!$echo) return $return;
    else echo $return;
}
// post archive pagination
function townhub_addons_pagination(){

    the_posts_pagination( array(
        'prev_text' =>  wp_kses(__('<i class="fa fa-caret-left"></i>','townhub-add-ons'),array('i'=>array('class'=>array(),),) ) ,
        'next_text' =>  wp_kses(__('<i class="fa fa-caret-right"></i>','townhub-add-ons'),array('i'=>array('class'=>array(),),) ) ,
        'screen_reader_text' => esc_html__( 'Posts navigation', 'townhub-add-ons' ),
    ) );

}

function townhub_addons_ajax_pagination($pages = '', $range = 2, $current_query = ''){
    $showitems = ($range * 2) + 1;
    
    if ($current_query == '') {
        global $paged;
        if (empty($paged)) $paged = 1;
    }else {
        $paged = $current_query->query_vars['paged'];
    }
    
    if ($pages == '') {
        if ($current_query == '') {
            global $wp_query;
            $pages = $wp_query->max_num_pages;
            if (!$pages) {
                $pages = 1;
            }
        } 
        else {
            $pages = $current_query->max_num_pages;
        }
    }
    if (1 < $pages) {
        echo '<span class="section-separator"></span>
        <nav class="navigation pagination custom-pagination ajax-pagination" role="navigation">
            <div class="nav-links">';

                if($paged > 1) 
                    echo '<a data-page="'.($paged - 1).'" href="#" class="prevposts-link page-numbers ajax-pagi-item">'.__('<i class="fas fa-caret-left" aria-hidden="true"></i><span>Prev</span>','townhub-add-ons').'</a>';
                else
                    echo '<span data-page="1" class="prevposts-link page-numbers">'.__('<i class="fas fa-caret-left" aria-hidden="true"></i><span>Prev</span>','townhub-add-ons').'</span>';
                
                for ($i = 1; $i <= $pages; $i++) {
                    if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems)) {
                        echo ($paged == $i) ? '<span data-page="'.$i.'" aria-current="page" class="page-numbers current">' . $i . "</span>" : '<a data-page="'.$i.'" href="#" class="page-numbers ajax-pagi-item">' . $i . '</a>';
                    }
                }

                if($paged < $pages) 
                    echo '<a data-page="'.($paged + 1).'" href="#" class="nextposts-link page-numbers ajax-pagi-item">'.__('<span>Next</span><i class="fas fa-caret-right" aria-hidden="true"></i>','townhub-add-ons').'</a>';
                else
                    echo '<span data-page="'.$paged.'" class="nextposts-link page-numbers">'.__('<span>Next</span><i class="fas fa-caret-right" aria-hidden="true"></i>','townhub-add-ons').'</span>';

            echo'</div>
        </nav>';
    }
}



/**
 * Pagination for custom query page
 *
 * @since TownHub 1.0
 */
if (!function_exists('townhub_addons_custom_pagination')) {
    function townhub_addons_custom_pagination($pages = '', $range = 2, $current_query = '') {
        // var_dump($pages);die;
        $showitems = ($range * 2) + 1;
        
        if ($current_query == '') {
            global $paged;
            if (empty($paged)) $paged = 1;
        } 
        else {
            
            $paged = $current_query->query_vars['paged'];
        }
        
        if ($pages == '') {
            if ($current_query == '') {
                global $wp_query;
                $pages = $wp_query->max_num_pages;
                if (!$pages) {
                    $pages = 1;
                }
            } 
            else {
                $pages = $current_query->max_num_pages;
            }
        }
        
        if (1 < $pages) {
            echo '
            <nav class="navigation pagination custom-pagination" role="navigation">
                <h2 class="screen-reader-text">'.__( 'Posts navigation',  'townhub-add-ons' ).'</h2>
                <div class="nav-links">';

                    if ($paged > 1) 
                        echo '<a href="' . get_pagenum_link($paged - 1) . '" class="prev page-numbers">'.__('<i class="fas fa-caret-left" aria-hidden="true"></i><span>Prev</span>','townhub-add-ons').'</a>';

                    else
                        echo '<a href="' . get_pagenum_link( $paged ) . '" class="prev page-numbers">'.__('<i class="fas fa-caret-left" aria-hidden="true"></i><span>Prev</span>','townhub-add-ons').'</a>';
                    
                    for ($i = 1; $i <= $pages; $i++) {
                        if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems)) {
                            echo ($paged == $i) ? '<span aria-current="page" class="page-numbers current">' . $i . "</span>" : "<a href='" . get_pagenum_link($i) . "' class='page-numbers'>" . $i . "</a>";
                        }
                    }

                    if ($paged < $pages) 
                        echo '<a href="' . get_pagenum_link($paged + 1) . '" class="next page-numbers">'.__('<span>Next</span><i class="fas fa-caret-right" aria-hidden="true"></i>','townhub-add-ons').'</a>';
                    else
                        echo '<a href="' . get_pagenum_link($paged ) . '" class="next page-numbers">'.__('<span>Next</span><i class="fas fa-caret-right" aria-hidden="true"></i>','townhub-add-ons').'</a>';
                echo'</div>
            </nav>';
        }

    }
}

function townhub_addons_comments_pagination($pages = 1, $range = 2) {

    $showitems = ($range * 2) + 1;
    global $paged;
    if (empty($paged)) $paged = 1;
    if (1 < $pages) {
        echo '
        <nav class="navigation pagination custom-pagination" role="navigation">
            <h2 class="screen-reader-text">'.__( 'Posts navigation',  'townhub-add-ons' ).'</h2>
            <div class="nav-links">';

                if ($paged > 1) 
                    echo '<a href="' . get_pagenum_link($paged - 1) . '" class="prev page-numbers">'.__('<i class="fas fa-caret-left" aria-hidden="true"></i><span>Prev</span>','townhub-add-ons').'</a>';

                else
                    echo '<a href="' . get_pagenum_link( $paged ) . '" class="prev page-numbers">'.__('<i class="fas fa-caret-left" aria-hidden="true"></i><span>Prev</span>','townhub-add-ons').'</a>';
                
                for ($i = 1; $i <= $pages; $i++) {
                    if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems)) {
                        echo ($paged == $i) ? '<span aria-current="page" class="page-numbers current">' . $i . "</span>" : "<a href='" . get_pagenum_link($i) . "' class='page-numbers'>" . $i . "</a>";
                    }
                }

                if ($paged < $pages) 
                    echo '<a href="' . get_pagenum_link($paged + 1) . '" class="next page-numbers">'.__('<span>Next</span><i class="fas fa-caret-right" aria-hidden="true"></i>','townhub-add-ons').'</a>';
                else
                    echo '<a href="' . get_pagenum_link($paged ) . '" class="next page-numbers">'.__('<span>Next</span><i class="fas fa-caret-right" aria-hidden="true"></i>','townhub-add-ons').'</a>';
            echo'</div>
        </nav>';
    }

}


// 0 for root level and so on
function townhub_addons_get_listing_categories($max_level = 3, $tax = 'listing_cat'){
    $listing_cats = get_terms( array(
        'taxonomy' => $tax,
        'hide_empty' => false,
        // add new ordre object
        // 'orderby'       => 'name',
        // 'order'         => 'ASC',
    ) );

    $listing_cats_arr = array();
    townhub_addons_parse_listing_cats($listing_cats,$listing_cats_arr,0,-1,$max_level);

    return $listing_cats_arr;
}

function townhub_addons_parse_listing_cats($cats = array(),&$return =array(),$parent_id = 0,$curlevel = -1,$maxlevel = 3){
    $return = $return? $return : array();
    if ( !empty($cats) ) :
        foreach( $cats as $cat ) {
            if( $cat->parent == $parent_id ) {
                // $return[$cat->term_id] = array('name'=>$cat->name,'level'=>$curlevel+1,'children'=>array());
                $return[] = array('id'=>$cat->term_id, 'slug' => $cat->slug,'name'=>$cat->name,'level'=>$curlevel+1);
                // if($return[$cat->term_id]['level'] < $maxlevel ) $this->parse_listing_cats($cats,$return[$cat->term_id]['children'],$cat->term_id,$return[$cat->term_id]['level']);
                if($curlevel+1 < $maxlevel ) townhub_addons_parse_listing_cats($cats,$return,$cat->term_id,$curlevel+1,$maxlevel);

                
            }
        }
    endif;
    // return $return;
}


function townhub_addons_get_listing_categories_select2(){
    $cats = townhub_addons_get_listing_categories();
    if(!empty($cats)){
        $return = array();
        foreach ($cats as $cat ){
            // $return[] = array($cat['id'] => str_repeat("-", $cat['level']).$cat['name']);
            $return[$cat['id']] = str_repeat("-", $cat['level']).$cat['name'];
        }
        return $return;
    }else{
        return array();
    }
}
function townhub_addons_get_listing_locations_hierarchy($max_level = 3){
    $terms = get_terms( array(
        'taxonomy' => 'listing_location',
        'hide_empty' => false
    ) );

    $terms_arr = array();
    townhub_addons_parse_listing_cats($terms,$terms_arr,0,-1,$max_level);

    return $terms_arr;
}
function townhub_addons_get_listing_locations_hierarchy_select2(){
    $terms = townhub_addons_get_listing_locations_hierarchy();
    if(!empty($terms)){
        $return = array();
        foreach ($terms as $term ){
            $return[$term['id']] = str_repeat("-", $term['level']).$term['name'];
        }
        return $return;
    }else{
        return array();
    }
}

function townhub_addons_get_listing_locations_select2(){
    $listing_locs = get_terms( array(
        'taxonomy' => 'listing_location',
        'hide_empty' => false
    ) );
    if ( ! empty( $listing_locs ) && ! is_wp_error( $listing_locs ) ){
        $return = array();
        foreach ($listing_locs as $loc ){
            $return[$loc->term_id] = $loc->name;
        }
        return $return;
    }else{
        return array();
    }
}

function townhub_addons_get_listing_locations($hide_empty = false){
    $listing_locs = get_terms( array(
        'taxonomy' => 'listing_location',
        'hide_empty' => $hide_empty
    ) );

    $locations = array();
    if ( ! empty( $listing_locs ) && ! is_wp_error( $listing_locs ) ){
        foreach ( $listing_locs as $loc ) {
            $locations[$loc->slug] = $loc->name;
        }
    }
    
    return $locations;
}

function townhub_addons_get_edit_listing_url($listing_id = null){

    $edit_page_id = esb_addons_get_wpml_option('edit_page');

    if(!isset($listing_id)) $listing_id = get_the_ID();

    return add_query_arg( 'listing_id', $listing_id,  get_permalink( $edit_page_id ) );
}




if(!function_exists('townhub_addons_breadcrumbs')){
    function townhub_addons_breadcrumbs($classes='') {
               
        // Settings
        $breadcrums_id      = 'breadcrumbs';
        $breadcrums_class   = 'breadcrumbs '.$classes;
        $home_title         = esc_html__('Home','townhub-add-ons');
        $blog_title         = esc_html__('Blog','townhub-add-ons');


        // If you have any custom post types with custom taxonomies, put the taxonomy name below (e.g. product_cat)
        // $custom_taxonomy    = 'product_cat,portfolio_cat,cth_speaker';
        // $custom_taxonomy    = 'listing_cat,listing_feature,listing_location';
        $custom_taxonomy    = 'listing_cat';

        $custom_post_types = array(
                                'listing' => esc_html_x('Listing','listing archive in breadcrumb','townhub-add-ons'),
                                'product' => esc_html_x('Products','product archive in breadcrumb','townhub-add-ons'),
                                
                            );
          
        // Get the query & post information
        global $post;
          
        // Do not display on the homepage
        if ( !is_front_page() ) {
          
            // Build the breadcrums
            echo '<div class="' . esc_attr($breadcrums_class ) . '">';
              
            // Home page
            echo '<a class="breadcrumb-link breadcrumb-home" href="' . esc_url( home_url('/') ) . '" title="' . esc_attr($home_title ) . '">' . esc_attr($home_title ) . '</a>';

            if(is_home()){
                // Blog page
                echo '<span class="breadcrumb-current breadcrumb-item-blog">' . esc_attr($blog_title ) . '</span>';
            }
              
            if ( is_archive() && !is_tax() ) {

                // If post is a custom post type
                $post_type = get_post_type();

                if($post_type && array_key_exists($post_type, $custom_post_types)){
                    echo '<span class="breadcrumb-current breadcrumb-item-custom-post-type-' . $post_type . '">' . $custom_post_types[$post_type] . '</span>';
                }else{
                    echo '<span class="breadcrumb-current breadcrumb-item-archive">' . get_the_archive_title() . '</span>';
                }
                 
            } else if ( is_archive() && is_tax() ) {
                 
                // If post is a custom post type
                $post_type = get_post_type();
                 
                // If it is a custom post type display name and link
                if($post_type && $post_type != 'post') {
                     
                    $post_type_object = get_post_type_object($post_type);
                    $post_type_archive = get_post_type_archive_link($post_type);
                 
                    echo '<a class="breadcrumb-link breadcrumb-custom-post-type-' . $post_type . '" href="' . esc_url($post_type_archive ) . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a>';
                 
                }
                 
                $custom_tax_name = get_queried_object()->name;
                echo '<span class="breadcrumb-current bread-item-archive">' . $custom_tax_name . '</span>';
                 
            } else if ( is_single() ) {
                
                // If post is a custom post type
                $post_type = get_post_type();
                $last_category = '';
                // If it is a custom post type (not support custom taxonomy) display name and link
                if( !in_array( $post_type, array('post','listing') ) ) {
                     
                    $post_type_object = get_post_type_object($post_type);
                    $post_type_archive = get_post_type_archive_link($post_type);

                    if(array_key_exists($post_type, $custom_post_types)){
                        echo '<a class="breadcrumb-link breadcrumb-cat breadcrumb-custom-post-type-' . $post_type . '" href="' . esc_url($post_type_archive ) . '" title="' . $custom_post_types[$post_type] . '">' . $custom_post_types[$post_type] . '</a>';
                    }else{
                        echo '<a class="breadcrumb-link breadcrumb-cat breadcrumb-custom-post-type-' . $post_type . '" href="' . esc_url($post_type_archive ) . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a>';
                    }
                    
                    echo '<span class="breadcrumb-current breadcrumb-item-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</span>';
                }elseif($post_type == 'post'){
                    // Get post category info
                    $category = get_the_category();
                     
                    // Get last category post is in
                    
                    if($category){
                        $last_cateogries = array_values($category);
                        $last_category = end($last_cateogries);
                     
                        // Get parent any categories and create array
                        $get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','),',');
                        $cat_parents = explode(',',$get_cat_parents);
                         
                        // Loop through parent categories and store in variable $cat_display
                        $cat_display = '';
                        foreach($cat_parents as $parents) {
                            $cat_display .= '<span class="breadcrumb-current breadcrumb-item-cat">'.$parents.'</span>';
                            
                        }
                    }
                    
                    if(!empty($last_category)) {
                        echo wp_kses_post($cat_display );
                        echo '<span class="breadcrumb-current breadcrumb-item-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</span>';
                         
                    // Else if post is in a custom taxonomy
                    }
                }
                    
                     
                // If it's a custom post type within a custom taxonomy
                if(empty($last_category) && !empty($custom_taxonomy)) {
                    $custom_taxonomy_arr = explode(",", $custom_taxonomy) ;
                    foreach ($custom_taxonomy_arr as $key => $custom_taxonomy_val) {
                        $taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy_val );
                        if($taxonomy_terms && !($taxonomy_terms instanceof WP_Error) ){
                            $cat_id         = $taxonomy_terms[0]->term_id;
                            $cat_nicename   = $taxonomy_terms[0]->slug;
                            $cat_link       = townhub_addons_get_term_link($taxonomy_terms[0]->term_id, $custom_taxonomy_val);
                            $cat_name       = $taxonomy_terms[0]->name;

                            if(!empty($cat_id)) {
                         
                                echo '<a class="breadcrumb-link bread-cat-' . $cat_id . ' bread-cat-' . $cat_nicename . '" href="' . esc_url($cat_link ) . '" title="' . $cat_name . '">' . $cat_name . '</a>';
                                
                                echo '<span class="breadcrumb-current breadcrumb-item-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</span>';
                             
                            }
                        }

                     } 
                    
                  
                }
                 
                
                 
            } else if ( is_category() ) {
                  
                // Category page
                echo '<span class="breadcrumb-current breadcrumb-item-cat-' . $category[0]->term_id . ' bread-cat-' . $category[0]->category_nicename . '">' . $category[0]->cat_name . '</span>';
                  
            } else if ( is_page() ) {
                
                $dashboard_page_id = esb_addons_get_wpml_option('dashboard_page');
                $dashboard_var = get_query_var('dashboard');

                // Standard page
                if( $post->post_parent ){

                    $parents = '';
                      
                    // If child page, get parents 
                    $anc = get_post_ancestors( $post->ID );
                      
                    // Get parents in the right order
                    $anc = array_reverse($anc);
                      
                    // Parent page loop
                    foreach ( $anc as $ancestor ) {
                        $parents .= '<a class="breadcrumb-link breadcrumb-parent-' . $ancestor . '" href="' . esc_url(get_permalink($ancestor) ) . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a>';
                        
                    }
                      
                    // Display parent pages
                    echo wp_kses_post($parents );

                    if(is_page($dashboard_page_id) && $dashboard_var != ''){

                        // dashboard page
                        echo '<a class="breadcrumb-link breadcrumb-dashboard" href="' . esc_url(get_permalink($dashboard_page_id) ) . '" title="' . get_the_title($dashboard_page_id) . '">' . get_the_title($dashboard_page_id) . '</a>';
                        // Current page
                        echo_attr( Esb_Class_Dashboard::subpage($dashboard_var) ). '">' . Esb_Class_Dashboard::subpage($dashboard_var) . '</span>';

                    }else{
                      
                        // Current page
                        echo '<span class="breadcrumb-current breadcrumb-item-page-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</span>';
                    }
                    
                      
                } else {
                      
                    // Just display current page if not parents
                    if(is_page($dashboard_page_id) && $dashboard_var != ''){

                        // dashboard page
                        echo '<a class="breadcrumb-link breadcrumb-dashboard" href="' . esc_url(get_permalink($dashboard_page_id) ) . '" title="' . get_the_title($dashboard_page_id) . '">' . get_the_title($dashboard_page_id) . '</a>';
                        // Current page
                        echo '<span class="breadcrumb-current breadcrumb-dashboard-subpage" title="' . esc_attr( Esb_Class_Dashboard::subpage($dashboard_var) ). '">' . Esb_Class_Dashboard::subpage($dashboard_var) . '</span>';

                    }else{
                      
                        // Current page
                        echo '<span class="breadcrumb-current breadcrumb-item-page-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</span>';
                    }
                      
                }
                  
            } else if ( is_tag() ) {
                  
                // Tag page
                  
                // Get tag information
                $term_id = get_query_var('tag_id');
                $taxonomy = 'post_tag';
                $args ='include=' . $term_id;
                $terms = get_terms( $taxonomy, $args );
                  
                // Display the tag name
                echo '<span class="breadcrumb-current breadcrumb-item-tag-' . $terms[0]->term_id . ' bread-tag-' . $terms[0]->slug . '">' . $terms[0]->name . '</span>';
              
            } elseif ( is_day() ) {
                  
                // Day archive
                  
                // Year link
                echo '<a class="breadcrumb-link breadcrumb-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . esc_html__(' Archives','townhub-add-ons').'</a>';
                
                  
                // Month link
                echo '<a class="breadcrumb-link breadcrumb-month bread-month-' . get_the_time('m') . '" href="' . get_month_link( get_the_time('Y'), get_the_time('m') ) . '" title="' . get_the_time('M') . '">' . get_the_time('M') . esc_html__(' Archives','townhub-add-ons').'</a>';
                
                  
                // Day display
                echo '<span class="breadcrumb-current bread-' . get_the_time('j') . '"> ' . get_the_time('jS') . ' ' . get_the_time('M') .  esc_html__(' Archives','townhub-add-ons').'</span>';
                  
            } else if ( is_month() ) {
                  
                // Month Archive
                  
                // Year link
                echo '<a class="breadcrumb-link breadcrumb-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . esc_html__(' Archives','townhub-add-ons').'</a>';
                
                  
                // Month display
                echo '<span class="breadcrumb-current breadcrumb-month breadcrumb-month-' . get_the_time('m') . '" title="' . get_the_time('M') . '">' . get_the_time('M') . esc_html__(' Archives','townhub-add-ons').'</span>';
                  
            } else if ( is_year() ) {
                  
                // Display year archive
                echo '<strong class="breadcrumb-current breadcrumb-current-' . get_the_time('Y') . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . esc_html__(' Archives','townhub-add-ons').'</span>';
                  
            } else if ( is_author() ) {
                  
                // Auhor archive
                  
                // Get the author information
                global $author;
                $userdata = get_userdata( $author );
                  
                // Display author name
                echo '<span class="breadcrumb-current breadcrumb-current-' . $userdata->user_nicename . '" title="' . $userdata->display_name . '">' .  esc_html__(' Author: ','townhub-add-ons') . $userdata->display_name . '</span>';
              
            } else if ( get_query_var('paged') ) {
                  
                // Paginated archives
                echo '<a href="#" class="breadcrumb-current breadcrumb-current-' . get_query_var('paged') . '" title="'.esc_html__('Page','townhub-add-ons') . get_query_var('paged') . '">'.esc_html__('Page','townhub-add-ons') . ' ' . get_query_var('paged') . '</a>';
                  
            } else if ( is_search() ) {
              
                // Search results page
                echo '<span class="breadcrumb-current breadcrumb-current-' . get_search_query() . '" title="'.esc_html__('Search results for: ','townhub-add-ons') . get_search_query() . '">'.esc_html__('Search results for: ','townhub-add-ons') . get_search_query() . '</span>';
              
            } elseif ( is_404() ) {
                  
                // 404 page
                echo '<span class="breadcrumb-current breadcrumb-current-404">' . esc_html__('Error 404','townhub-add-ons') . '</span>';
            }
          
            echo '</div>';
              
        }
          
    }
}


// edit listing link
function townhub_addons_edit_listing_link($id = 0){
    if(!$id) $id = get_the_ID();
    if ( !current_user_can( 'edit_post', $id ) ) return;
    
    $edit_page_id = esb_addons_get_wpml_option('edit_page');

    echo '<a class="edit-listing-link" href="'.esc_url( add_query_arg( 'listing_id', $id,  get_permalink($edit_page_id) ) ).'">'.esc_html__( 'Edit', 'townhub-add-ons' ).'</a>';
}

function townhub_addons_get_contact_form7_forms(){
    $forms = get_posts( 'post_type=wpcf7_contact_form&posts_per_page=-1&suppress_filters=false' );

    $results = array();
    if ( $forms ) {
        $results[] = __( 'Select A Form', 'townhub-add-ons' );
        foreach ( $forms as $form ) {
            $results[ $form->ID ] = $form->post_title;
        }
        // array_unshift( $results, __( 'Select A Form', 'townhub-add-ons' ) );
        // $results[] = __( 'Select A Form', 'townhub-add-ons' );
    } else {
        $results[] =  __( 'No contact forms found', 'townhub-add-ons' ) ;
    }

    return $results;
}
// echo socials share content
function townhub_addons_echo_socials_share(){
    $widgets_share_names = townhub_addons_get_option('widgets_share_names','facebook, pinterest, googleplus, twitter, linkedin');
    if($widgets_share_names !=''):
    ?>
    <div class="share-holder hid-share">
        <div class="showshare"><span class="share-show"><?php esc_html_e( 'Share ', 'townhub-add-ons' ); ?></span><span class="share-close"><?php esc_html_e( 'Close ', 'townhub-add-ons' ); ?></span><i class="fa fa-share"></i></div>
        <div class="share-container isShare" data-share="<?php echo esc_attr( trim($widgets_share_names, ", \t\n\r\0\x0B") ); ?>"></div>
    </div>
    <?php
    endif;  
}

function townhub_addons_breadcrumbs_socials_share(){
    $widgets_share_names = townhub_addons_get_option('widgets_share_names','facebook, pinterest, googleplus, twitter, linkedin');
    if($widgets_share_names !='' ):
    ?>
    <div class="showshare brd-show-share color2-bg"><i class="fas fa-share"></i><?php esc_html_e( 'Share ', 'townhub-add-ons' ); ?></div>
    <div class="share-holder hid-share sing-page-share top_sing-page-share">
        <div class="share-container isShare" data-share="<?php echo esc_attr( trim($widgets_share_names, ", \t\n\r\0\x0B") ); ?>"></div>
    </div>
    <?php
    endif;  
}

// for payment
function townhub_addons_get_currency_array(){
    $world_curr = array (
        'ALL' => 'Albania Lek',
        'AFN' => 'Afghanistan Afghani',
        'ARS' => 'Argentina Peso',
        'AWG' => 'Aruba Guilder',
        'AUD' => 'Australia Dollar',
        'AZN' => 'Azerbaijan New Manat',
        'BSD' => 'Bahamas Dollar',
        'BBD' => 'Barbados Dollar',
        'BDT' => 'Bangladeshi taka',
        'BHD' => 'Dinar Bahrain',
        'BYR' => 'Belarus Ruble',
        'BZD' => 'Belize Dollar',
        'BMD' => 'Bermuda Dollar',
        'BOB' => 'Bolivia Boliviano',
        'BAM' => 'Bosnia and Herzegovina Convertible Marka',
        'BWP' => 'Botswana Pula',
        'BGN' => 'Bulgaria Lev',
        'BRL' => 'Brazil Real',
        'BND' => 'Brunei Darussalam Dollar',
        'KHR' => 'Cambodia Riel',
        'CAD' => 'Canada Dollar',
        'KYD' => 'Cayman Islands Dollar',
        'CLP' => 'Chile Peso',
        'CNY' => 'China Yuan Renminbi',
        'COP' => 'Colombia Peso',
        'CRC' => 'Costa Rica Colon',
        'HRK' => 'Croatia Kuna',
        'CUP' => 'Cuba Peso',
        'CZK' => 'Czech Republic Koruna',
        'DKK' => 'Denmark Krone',
        'DOP' => 'Dominican Republic Peso',

        'XCD' => 'East Caribbean Dollar',
        'EGP' => 'Egypt Pound',
        'ETB'   => 'Ethiopian Birr',
        'SVC' => 'El Salvador Colon',
        'EEK' => 'Estonia Kroon',
        'EUR' => 'Euro Member Countries',
        'FKP' => 'Falkland Islands (Malvinas) Pound',
        'FJD' => 'Fiji Dollar',
        'GEL' => 'Georgian lari',
        'GHC' => 'Ghana Cedis',
        'GIP' => 'Gibraltar Pound',
        'GTQ' => 'Guatemala Quetzal',
        'GGP' => 'Guernsey Pound',
        'GYD' => 'Guyana Dollar',
        'HNL' => 'Honduras Lempira',
        'HKD' => 'Hong Kong Dollar',
        'HUF' => 'Hungary Forint',
        'ISK' => 'Iceland Krona',
        'INR' => 'India Rupee',
        'IDR' => 'Indonesia Rupiah',
        'IRR' => 'Iran Rial',
        'IMP' => 'Isle of Man Pound',
        'ILS' => 'Israel Shekel',
        'JMD' => 'Jamaica Dollar',
        'JPY' => 'Japan Yen',
        'JEP' => 'Jersey Pound',
        'KZT' => 'Kazakhstan Tenge',
        'KPW' => 'Korea (North) Won',
        'KRW' => 'Korea (South) Won',
        'KGS' => 'Kyrgyzstan Som',
        'LAK' => 'Laos Kip',
        'LVL' => 'Latvia Lat',
        'LBP' => 'Lebanon Pound',
        'LRD' => 'Liberia Dollar',
        'LTL' => 'Lithuania Litas',
        'MKD' => 'Macedonia Denar',
        'MMK' => 'Myanmar Kyat',
        'MYR' => 'Malaysia Ringgit',
        'MUR' => 'Mauritius Rupee',
        'MXN' => 'Mexico Peso',
        'MNT' => 'Mongolia Tughrik',
        'MZN' => 'Mozambique Metical',
        'MAD' => 'Moroccan dirham',
        'NAD' => 'Namibia Dollar',
        'NPR' => 'Nepal Rupee',
        'ANG' => 'Netherlands Antilles Guilder',
        'NZD' => 'New Zealand Dollar',
        'NIO' => 'Nicaragua Cordoba',
        'NGN' => 'Nigeria Naira',
        'NOK' => 'Norway Krone',
        'OMR' => 'Oman Rial',
        'PKR' => 'Pakistan Rupee',
        'PAB' => 'Panama Balboa',
        'PYG' => 'Paraguay Guarani',
        'PEN' => 'Peru Nuevo Sol',
        'PHP' => 'Philippines Peso',
        'PLN' => 'Poland Zloty',
        'QAR' => 'Qatar Riyal',
        'RON' => 'Romania New Leu',
        'RUB' => 'Russia Ruble',
        'SHP' => 'Saint Helena Pound',
        'SAR' => 'Saudi Arabia Riyal',
        'RSD' => 'Serbia Dinar',
        'SCR' => 'Seychelles Rupee',
        'SGD' => 'Singapore Dollar',
        'SBD' => 'Solomon Islands Dollar',
        'SOS' => 'Somalia Shilling',
        'ZAR' => 'South Africa Rand',
        'LKR' => 'Sri Lanka Rupee',
        'SEK' => 'Sweden Krona',
        'CHF' => 'Switzerland Franc',
        'SRD' => 'Suriname Dollar',
        'SYP' => 'Syria Pound',
        'TWD' => 'Taiwan New Dollar',
        'TZS' => 'Tanzanian shilling',
        'THB' => 'Thailand Baht',
        'TTD' => 'Trinidad and Tobago Dollar',
        'TRY' => 'Turkey Lira',
        'TRL' => 'Turkey Lira',
        'TVD' => 'Tuvalu Dollar',
        'UAH' => 'Ukraine Hryvna',
        'GBP' => 'United Kingdom Pound',
        'UGX' => 'Uganda Shilling',
        'USD' => 'United States Dollar',
        'UYU' => 'Uruguay Peso',
        'UZS' => 'Uzbekistan Som',
        'VEF' => 'Venezuela Bolivar',
        'VND' => 'Viet Nam Dong',
        'YER' => 'Yemen Rial',
        'ZWD' => 'Zimbabwe Dollar',
        'CFA' => 'CFA Franc', 
        'KSH' => 'Kenya shillings',
        'AED' => 'United Arab Emirates',
        'DZD'   => 'Algerian dinar',
    );
    $paypal_curr = array(
        "USD" => "US Dollars ($) - Paypal acceptable", 
        "EUR" => "Euros (€) - Paypal acceptable",
        "GBP" => "Pounds Sterling (£) - Paypal acceptable",
        "AUD" => "Australian Dollars ($) - Paypal acceptable",
        "BRL" => "Brazilian Real (R$) - Paypal acceptable",
        "CAD" => "Canadian Dollars ($) - Paypal acceptable",
        "CZK" => "Czech Koruna - Paypal acceptable",
        "DKK" => "Danish Krone - Paypal acceptable",
        "HKD" => "Hong Kong Dollar ($) - Paypal acceptable",
        "HUF" => "Hungarian Forint - Paypal acceptable",
        "ILS" => "Israeli Shekel (₪) - Paypal acceptable",
        "JPY" => "Japanese Yen (¥) - Paypal acceptable",
        "MYR" => "Malaysian Ringgits - Paypal acceptable",
        "MXN" => "Mexican Peso ($) - Paypal acceptable",
        "NZD" => "New Zealand Dollar ($) - Paypal acceptable",
        "NOK" => "Norwegian Krone - Paypal acceptable",
        "PHP" => "Philippine Pesos - Paypal acceptable",
        "PLN" => "Polish Zloty - Paypal acceptable",
        "SGD" => "Singapore Dollar ($) - Paypal acceptable",
        "SEK" => "Swedish Krona - Paypal acceptable",
        "CHF" => "Swiss Franc - Paypal acceptable",
        "TWD" => "Taiwan New Dollars - Paypal acceptable",
        "THB" => "Thai Baht (฿) - Paypal acceptable",
        "INR" => "Indian Rupee (₹) - Paypal acceptable",
        "TRY" => "Turkish Lira (₺) - Paypal acceptable",
        "RIAL" => "Iranian Rial (﷼) - Paypal acceptable",
        "RUB" => "Russian Rubles - Paypal acceptable",

    );

    return array_merge($world_curr, $paypal_curr);
}

function townhub_addons_get_plan_prices($plan_id = 0, $raw_price = 0){
    
    if($raw_price) 
        $price = $raw_price;
    else 
        $price = get_post_meta( $plan_id, '_cth_price', true );
    
    $price = floatval($price);

    $vat_percent = townhub_addons_get_option('vat_tax');

    $tax = floatval($vat_percent) * $price / 100;

    $total = $price + $tax ;

    return array(
        'price' => $price,
        'tax' => $tax,
        'total' => $total,
    );

}
function townhub_addons_get_currency(){
    $return = townhub_addons_get_option('currency', 'USD') ;
    if(isset($_COOKIE["esb_currency"])){
        $currency = stripslashes($_COOKIE['esb_currency']);
        if($currency != '' && array_key_exists($currency, townhub_addons_get_currency_array())) $return = $currency;
    }

    return apply_filters('esb_currency', $return);
}
function townhub_addons_find_currency($currency = null){
    if( $currency == null ) $currency = townhub_addons_get_currency();
    return array_filter((array)townhub_addons_get_option('currencies'), function($cur) use ($currency) {
        return is_array($cur) && isset($cur['currency']) && $cur['currency'] == $currency;
    });
}
function townhub_addons_get_currency_rate($currency = null){
    $foundCurrs = townhub_addons_find_currency($currency);
    if(!empty($foundCurrs)){
        $foundCurr = reset($foundCurrs);
        return (float)$foundCurr['rate'];
    }
    return 1;
}
function townhub_addons_get_currency_attrs($currency = null){
    $currency_attrs = array();
    $foundCurrs = townhub_addons_find_currency($currency);
    if(!empty($foundCurrs)){
        // var_dump($foundCurrs);reset()
        $foundCurr = reset($foundCurrs);

        // var_dump($foundCurr);

        $currency_attrs = array(
            'rate'          => $foundCurr['rate'] ? : 1,
            'decimal'          => $foundCurr['decimal'],
            'dec_sep'          => $foundCurr['dec_sep'] ,
            'ths_sep'          => $foundCurr['ths_sep'] ,
            'symbol'          => $foundCurr['symbol'],
            'sb_pos'          => $foundCurr['sb_pos'] ,
            'currency'          => $foundCurr['currency'] ,
        );

    }else{
        $currency_attrs = townhub_addons_get_base_currency();
    }

    return apply_filters( 'esb_currency_attrs', $currency_attrs );

}
function townhub_addons_get_stripe_amount($amount=0){
    // The amount (in cents) that's shown to the user. Note that you will still have to explicitly include the amount when you create a charge using the API.
    // $20 -> 2000
    return floatval($amount)*100;
}
function townhub_addons_get_price_formated($price = 0, $show_currency = true){
    $price = floatval($price);
    if( is_admin() && !wp_doing_ajax() ){
        $curr_attrs = townhub_addons_get_base_currency();
    }else{
        $curr_attrs = townhub_addons_get_currency_attrs();
    }
    // $curr_attrs = townhub_addons_get_currency_attrs();
    $return = number_format( (float)$price  * $curr_attrs['rate'], $curr_attrs['decimal'], $curr_attrs['dec_sep'], $curr_attrs['ths_sep'] );
    if($show_currency){
        $currency = $curr_attrs['symbol'];
        $currency_pos = $curr_attrs['sb_pos'];
        switch ($currency_pos) {
            case 'left':
                $return = $currency .$return;
                break;
            case 'right':
                $return .= $currency;
                break;
            case 'right_space':
                $return .= '&nbsp;'. $currency;
                break;
            default:
                $return = $currency . '&nbsp;'. $return;
                break;
        }
        
    }

    return $return;
}
// function townhub_addons_get_base_price_formated($price = 0, $show_currency = true){
//     if($price == '') $price = 0;
//     $return = number_format( (float)$price, townhub_addons_get_option('decimals','2'), townhub_addons_get_option('decimal_sep','.'), townhub_addons_get_option('thousand_sep',',') );
//     if($show_currency){
//         $currency = townhub_addons_get_option('currency_symbol','$');
//         $currency_pos = townhub_addons_get_option('currency_pos','left_space');
//         switch ($currency_pos) {
//             case 'left':
//                 $return = $currency .$return;
//                 break;
//             case 'right':
//                 $return .= $currency;
//                 break;
//             case 'right_space':
//                 $return .= '&nbsp;'. $currency;
//                 break;
//             default:
//                 $return = $currency . '&nbsp;'. $return;
//                 break;
//         }
        
//     }

//     return $return;
// }


function townhub_addons_get_price($price = 0){
    $price = floatval($price);
    $curr_attrs = townhub_addons_get_currency_attrs();
    return  $price * floatval($curr_attrs['rate']);
    // return number_format( (float)$price * $curr_attrs['rate'], $curr_attrs['decimal'], $curr_attrs['dec_sep'], $curr_attrs['ths_sep'] );
}
function townhub_addons_get_price_with_symbol($price = 0){
    $curr_attrs = townhub_addons_get_currency_attrs();

    $return = number_format( (float)$price, $curr_attrs['decimal'], $curr_attrs['dec_sep'], $curr_attrs['ths_sep'] );

    $currency = $curr_attrs['symbol'];
    $currency_pos = $curr_attrs['sb_pos'];
    switch ($currency_pos) {
        case 'left':
            $return = $currency .$return;
            break;
        case 'right':
            $return .= $currency;
            break;
        case 'right_space':
            $return .= '&nbsp;'. $currency;
            break;
        default:
            $return = $currency . '&nbsp;'. $return;
            break;
    }
        
    return $return;
}
function townhub_addons_parse_price($price = 0){
    $price = floatval($price);
    if(empty($price)) return 0;
    $curr_attrs = townhub_addons_get_currency_attrs();
    if(!empty($curr_attrs['rate'])){
        return $price / floatval($curr_attrs['rate']);
    }
    return $price;
    // $foundCurrs = townhub_addons_find_currency();
    // if(!empty($foundCurrs)){
    //     $foundCurr = reset($foundCurrs);
    //     if(!empty($foundCurr['rate']))
    //         // return $price / floatval($foundCurr['rate']);
    //         return number_format( $price / floatval($foundCurr['rate']), townhub_addons_get_option('decimals','2'), townhub_addons_get_option('decimal_sep','.'), townhub_addons_get_option('thousand_sep',',') ); // default currency is usd
    // }
    // return $price;
}
function townhub_addons_get_currency_symbol($currency = null){
    $curr_attrs = townhub_addons_get_currency_attrs();
    return $curr_attrs['symbol'];
    
    // $foundCurrs = townhub_addons_find_currency($currency);
    // if(!empty($foundCurrs)){
    //     $foundCurr = reset($foundCurrs);
    //     return $foundCurr['symbol'];
    // }else{
        
    //     return townhub_addons_get_option('currency_symbol','$');
    // }
    
}

function townhub_addons_payment_names($method = '',$is_array = false){
   $methods = array(
        'free' => __( 'Free', 'townhub-add-ons' ),
        'submitform' => __( 'Submit Form', 'townhub-add-ons' ),
        'cod' => __( 'Cash on delivery', 'townhub-add-ons' ),
        'banktransfer' => __( 'Bank Transfer', 'townhub-add-ons' ),
        'request' => __( 'Booking Request', 'townhub-add-ons' ),


        // 'paypal' => __( 'Paypal', 'townhub-add-ons' ),
        // 'stripe' => __( 'Stripe', 'townhub-add-ons' ),
        // 'woo' => __( 'WooCommerce Integration', 'townhub-add-ons' ),
        // 'payfast' => __( 'PayFast', 'townhub-add-ons' ),
    );
    $methods = (array)apply_filters( 'esb_payment_method_texts', $methods );
    if(isset($methods[$method])) return $methods[$method];
    elseif ($is_array != false) return $methods;
    else return reset($methods);
}

function townhub_addons_get_payments($method = ''){
    $payments = array();
    if(townhub_addons_get_option('payments_form_enable') == 'yes'){
        $payments['submitform'] = array(
            'title' => __( 'Submit Form', 'townhub-add-ons' ),
            
            'icon' => '', //get_site_icon_url(),
            'desc' => townhub_addons_get_option('payments_form_details',''),

            'checkout_text' => __( 'Place Order', 'townhub-add-ons' ),
        );
    }
    if(townhub_addons_get_option('payments_cod_enable') == 'yes'){
        $payments['cod'] = array(
            'title' => __( 'Cash on delivery', 'townhub-add-ons' ),
            
            'icon' => '', //get_site_icon_url(),
            'desc' => townhub_addons_get_option('payments_cod_details',''),

            'checkout_text' => __( 'Place Order', 'townhub-add-ons' ),
        );
    }
    // banktransfer
    if(townhub_addons_get_option('payments_banktransfer_enable') == 'yes'){
        $payments['banktransfer'] = array(
            'title' => __( 'Bank Transfer', 'townhub-add-ons' ),
            
            'icon' => ESB_DIR_URL.'assets/images/bank-transfer.png',
            'desc' => townhub_addons_get_option('payments_banktransfer_details',''),

            'checkout_text' => __( 'Place Order', 'townhub-add-ons' ),
        );
    }
    // // paypal
    // if(townhub_addons_get_option('payments_paypal_enable') == 'yes'){
    //     $payments['paypal'] = array(
    //         'title' => __( 'Pay via Paypal', 'townhub-add-ons' ),
    //         'icon' => ESB_DIR_URL.'assets/images/ppcom.png',
    //         'desc' => townhub_addons_get_option('payments_paypal_desc',''),

    //         'checkout_text' => __( 'Process to Paypal', 'townhub-add-ons' ),
    //     );
    // }
    // // stripe
    // if(townhub_addons_get_option('payments_stripe_enable') == 'yes'){
    //     $payments['stripe'] = array(
    //         'title' => __( 'Pay via Stripe', 'townhub-add-ons' ),
    //         'icon' => ESB_DIR_URL.'assets/images/stripe.png',
    //         'desc' => townhub_addons_get_option('payments_stripe_desc',''),

    //         'checkout_text' => __( 'Pay Now', 'townhub-add-ons' ),
    //     );
    // }
    // // payfast
    // if(townhub_addons_get_option('payments_payfast_enable') == 'yes'){
    //     $payments['payfast'] = array(
    //         'title' => __( 'Pay via Payfast', 'townhub-add-ons' ),
    //         'icon' => ESB_DIR_URL.'assets/images/payfast.png',
    //         'desc' => townhub_addons_get_option('payments_payfast_desc',''),

    //         'checkout_text' => __( 'Process to Payfast', 'townhub-add-ons' ),
    //     );
    // }
    $payments = (array)apply_filters( 'esb_payment_methods', $payments );
    if(isset($payments[$method])) return $payments[$method];
    else return $payments;
}

// convert PDT (Paypal time to UTC) --> NOTE: need to return to local date
function townhub_add_ons_payment_date($date ='', $format = 'Y-m-d H:i:s'){

    // $dateObj = new DateTime($date, new DateTimeZone('America/Los_Angeles'));
    $dateObj = new DateTime($date);
    
    // $tz = new DateTimeZone('America/Los_Angeles'); 04:31:57+Apr+21,+2018+PDT04:31:57+Apr+21,+2018+PDT
    $tz = new DateTimeZone('UTC');
    $dateObj->setTimezone($tz);

    // $stamp = $dateObj->format('U');
    // $zone = $tz->getTransitions($stamp, $stamp);
    // if(!$zone[0]['isdst']) $dateObj->modify('+1 hour');

    // return $dateObj->format($format); // --> this will return gmt date

    return get_date_from_gmt( $dateObj->format('Y-m-d H:i:s'), $format );
}

// convert Unix epoch timestamp to UTC (Stripe time to UTC) --> Note this return local time not UTC
function townhub_add_ons_charge_date($timestamp ='', $format = 'Y-m-d H:i:s'){
    $dateObj = new DateTime();
    $dateObj->setTimestamp($timestamp);
    
    return $dateObj->format($format);
}

function townhub_addons_booking_nights($checkin = 'now', $checkout = 'now'){
    if($checkout == '') $checkout = $checkin;
    $datetime_checkin = new DateTime($checkin);
    $datetime_checkout = new DateTime($checkout);
    if($datetime_checkin && $datetime_checkout){
        // $interval = $datetime_checkin->diff($datetime_checkout);
        return (int)$datetime_checkin->diff($datetime_checkout)->format('%a');
    }
    return 0;

    // $date1 = new DateTime("2010-07-06");
    // $date2 = new DateTime("2010-07-09");

    // // this calculates the diff between two dates, which is the number of nights
    // $numberOfNights= $date2->diff($date1)->format("%a"); 
}


function townhub_add_ons_cal_next_date($date = '', $period = 'day', $interval = 0, $format = 'Y-m-d H:i:s'){
    $dateObj = new DateTime($date);

    if($interval){
        switch ($period) {
            case 'day':
                townhub_add_ons_add_days($dateObj, $interval);
                break;
            case 'week':
                townhub_add_ons_add_weeks($dateObj, $interval);
                break;
            case 'month':
                townhub_add_ons_add_months($dateObj, $interval);
                break;
            case 'year':
                townhub_add_ons_add_years($dateObj, $interval);
                break;
            
        }
    }

    return $dateObj->format($format);
}
// for changing data base on plan period
function townhub_add_ons_add_months($date,$months = 0){
     
    $init=clone $date;
    $modifier=$months.' months';
    $back_modifier =-$months.' months';
    
    $date->modify($modifier);
    $back_to_init= clone $date;
    $back_to_init->modify($back_modifier);
    
    while($init->format('m')!=$back_to_init->format('m')){
    $date->modify('-1 day')    ;
    $back_to_init= clone $date;
    $back_to_init->modify($back_modifier);    
    }
    
    /*
    if($months<0&&$date->format('m')>$init->format('m'))
    while($date->format('m')-12-$init->format('m')!=$months%12)
    $date->modify('-1 day');
    else
    if($months>0&&$date->format('m')<$init->format('m'))
    while($date->format('m')+12-$init->format('m')!=$months%12)
    $date->modify('-1 day');
    else
    while($date->format('m')-$init->format('m')!=$months%12)
    $date->modify('-1 day');
    */
    
}
 
function townhub_add_ons_add_years($date,$years = 0){
    
    $init=clone $date;
    $modifier=$years.' years';
    $date->modify($modifier);
    
    while($date->format('m')!=$init->format('m'))
    $date->modify('-1 day');
    
    
} 
function townhub_add_ons_add_weeks($date, $weeks = 0){
    // $init=clone $date;
    $modifier=$weeks.' weeks';
    $date->modify($modifier);
}
function townhub_add_ons_add_days($date, $days = 0){
    // $init=clone $date;
    $modifier= $days.' days';
    $date->modify($modifier);
}
// end changing date function

function townhub_add_ons_get_plan_period_text($interval = 1, $period = 'month'){
    $period_texts = array(
        'hour'          => esc_html__( 'hour', 'townhub-add-ons' ),
        'day'           => esc_html__( 'day', 'townhub-add-ons' ),
        'week'          => esc_html__( 'week', 'townhub-add-ons' ),
        'month'         => esc_html__( 'month', 'townhub-add-ons' ),
        'year'          => esc_html__( 'year', 'townhub-add-ons' ),
    );
    if($interval){
        $formatted_period = $period_texts[$period];
        if($interval > 1) $formatted_period = sprintf( _nx( '%d %s', '%d %ss', $interval, 'pricing per period text', 'townhub-add-ons' ), $interval, $period_texts[$period] );
        return sprintf(__( '<span class="period-per">Per</span> %s', 'townhub-add-ons' ), $formatted_period);
    }

    return __( '', 'townhub-add-ons' );

    // $formatted_period = _n( $period_texts[$period], '%s '.$period_texts[$period], $interval, 'townhub-add-ons' );


    
}

function townhub_add_ons_get_plan_trial_text($interval = 1, $period = 'month'){
    $period_texts = array(
        'hour'          => esc_html__( 'hour', 'townhub-add-ons' ),
        'day'           => esc_html__( 'day', 'townhub-add-ons' ),
        'week'          => esc_html__( 'week', 'townhub-add-ons' ),
        'month'         => esc_html__( 'month', 'townhub-add-ons' ),
        'year'          => esc_html__( 'year', 'townhub-add-ons' ),
    );
    if($interval){
        $formatted_period = $period_texts[$period];
        if($interval > 1) $formatted_period = sprintf(__( '%d %ss', 'townhub-add-ons' ), $interval, $period_texts[$period] );
        return sprintf(__( '<span class="trial-per">for</span> %s', 'townhub-add-ons' ), $formatted_period);
    }

    return __( '', 'townhub-add-ons' );
}

// for subscription plan
function townhub_add_ons_get_subscription_duration_units($unit = ''){
    $duration_units = array(
        // 'hour'          => esc_html__( 'Hour', 'townhub-add-ons' ),
        'day'           => esc_html__( 'Days', 'townhub-add-ons' ),
        'week'          => esc_html__( 'Weeks', 'townhub-add-ons' ),
        'month'         => esc_html__( 'Months', 'townhub-add-ons' ),
        'year'          => esc_html__( 'Years', 'townhub-add-ons' ),
    );
    if( !empty($unit) && isset( $duration_units[$unit] ) ) return $duration_units[$unit];

    return $duration_units;
}
//
function townhub_add_ons_get_paypal_duration_unit($unit = ''){
    /*
    D. Days. Valid range for p3 is 1 to 90.
    W. Weeks. Valid range for p3 is 1 to 52.
    M. Months. Valid range for p3 is 1 to 24.
    Y. Years. Valid range for p3 is 1 to 5.
    */

    $default = array(
        'day' => 'D',
        'week' => 'W',
        'month' => 'M',
        'year' => 'Y',
    );

    if( !empty($unit) && isset( $default[$unit] ) ) return $default[$unit];

    return $default['month'];
}
function townhub_add_ons_get_paypal_duration($duration = '', $unit = ''){
    if($duration){
        $duration_unit = townhub_add_ons_get_paypal_duration_unit($unit);
        switch ($duration_unit) {
            case 'D':
                if((int)$duration > 90) return 90;
                return (int)$duration;
                break;
            case 'W':
                if((int)$duration > 52) return 52;
                return (int)$duration;
                break;
            case 'M':
                if((int)$duration > 24) return 24;
                return (int)$duration;
                break;
            case 'Y':
                if((int)$duration > 5) return 5;
                return (int)$duration;
                break;
            
        }
    }

    return 1;

}
function townhub_add_ons_get_stripe_duration($duration = '', $unit = ''){
    if($duration){
        switch ($unit) {
            case 'day':
                return (int)$duration;
                break;
            case 'week':
                return 7*$duration;
                break;
            case 'month':
                return 30*$duration;
                break;
            case 'year':
                return 365*$duration;
                break;
            
        }
    }
    return 1;

}
function townhub_addons_get_current_subscription($user_ID = 0, $per_listing = false){
    if(!$user_ID){
        // return false when no user
        if(!is_user_logged_in()) return false;
        $user_ID = get_current_user_id();
    }

    $per_listing_sub = $per_listing ? 'yes' : 'no';

    $args = array(
        'posts_per_page'   => 1,
        'orderby'          => 'date',
        'order'            => 'DESC',
        // 'meta_key'         => ESB_META_PREFIX.'user_id',
        // 'meta_value'       => $user_ID,

        'post_author'       => $user_ID,
        'post_type'        => 'lorder',
        'post_status'      => 'publish',
        'suppress_filters'  => false,

        'meta_query' => array(
            'relation'  => 'AND',
            array(
                'relation'  => 'OR',
                array(
                    'key' => ESB_META_PREFIX.'status',
                    'value' => 'completed',
                ),
                array(
                    'key' => ESB_META_PREFIX.'status',
                    'value' => 'trialing',
                ),
            ),
            array(
                'key' => ESB_META_PREFIX.'user_id',
                'value' => $user_ID,
            ),
            array(
                'relation'  => 'OR',
                array(
                    'key'     => ESB_META_PREFIX.'end_date',
                    'value'   => 'NEVER',
                ),
                array(
                    'key'     => ESB_META_PREFIX.'end_date',
                    'value'   => current_time('mysql', 1),
                    'compare' => '>=',
                    'type'    => 'DATETIME',
                ),
            ),
            array(
                'key'     => ESB_META_PREFIX.'is_per_listing_sub',
                'value'   => $per_listing_sub,
            ),
        )

    );
    $posts_array = get_posts( $args );

    if(count($posts_array)){
        $order = $posts_array[0];
        $order_listings = get_post_meta( $order->ID, ESB_META_PREFIX.'listings', true );
        $order_limit = get_post_meta( $order->ID, ESB_META_PREFIX.'plan_llimit', true );
        return array(
            'id'            => $order->ID,
            'plan_id'       => get_post_meta( $order->ID, ESB_META_PREFIX.'plan_id', true ),
            'end_date'      => get_post_meta( $order->ID, ESB_META_PREFIX.'end_date', true ),
            // listings attached to the subscription
            'listings'      => $order_listings,
            'plan_llimit'   => $order_limit, // unlimited or number

            'valid'         => count((array)$order_listings) < (int)$order_limit
        );
    }


    return false;


}
// for listing claim
function townhub_add_ons_get_claim_status($status = ''){
    $defaults = array(
        'pending'                   => esc_html__( 'Pending', 'townhub-add-ons' ),
        'asked_charge'              => esc_html__( 'Asked charge fee', 'townhub-add-ons' ),
        'paid'                      => esc_html__( 'Paid', 'townhub-add-ons' ),
        'approved'                  => esc_html__( 'Appproved', 'townhub-add-ons' ),
        'decline'                   => esc_html__( 'Decline', 'townhub-add-ons' ),
    );

    if( $status == 'all' ) return $defaults;

    if($status != '' && isset($defaults[$status])) 
        return $defaults[$status];

    return $status;
    
}

// for user notification
function townhub_addons_user_add_notification( $user_id = 0, $message = array() ){
    $user = get_user_by('ID', $user_id);
    if(!$user) return;

    if(!isset($message['type']) || !isset($message['message'])) return;

    $notifications = get_user_meta( $user->ID, ESB_META_PREFIX.'notifications', true );
    if(empty($notifications)) $notifications = array();

    $notifications[uniqid($message['type'])] = $message['message'];

    update_user_meta( $user->ID, ESB_META_PREFIX.'notifications', $notifications );

}
function townhub_addons_get_notification_type($key){

    if(strpos($key, 'listing_publish') !== false){
        return 'listing_publish';
    }elseif(strpos($key, 'role_change') !== false){
        return 'role_change';
    }elseif(strpos($key, 'order_completed') !== false){
        return 'order_completed';
    }elseif(strpos($key, 'plan_change') !== false){
        return 'plan_change';
    }elseif(strpos($key, 'listing_submitted') !== false){
        return 'listing_submitted';
    }elseif(strpos($key, 'listing_edit') !== false){
        return 'listing_edit';
    }elseif(strpos($key, 'listing_deleted') !== false){
        return 'listing_deleted';
    }elseif(strpos($key, 'new_bookmarks') !== false){ // for listing author
        return 'new_bookmarks';
    }elseif(strpos($key, 'bookmarked') !== false){ // for user bookmarks
        return 'bookmarked';
    }elseif(strpos($key, 'review') !== false){
        return 'review';
    }elseif(strpos($key, 'delete_listing') !== false){
        return 'delete_listing';
    }elseif(strpos($key, 'new_invoice') !== false){
        return 'new_invoice';
    }elseif(strpos($key, 'ad_completed') !== false){
        return 'ad_completed';
    }elseif(strpos($key, 'edit_profile') !== false){
        return 'edit_profile';
    }elseif(strpos($key, 'password_changed') !== false){
        return 'password_changed';
    }elseif(strpos($key, 'booked') !== false){
        return 'booked';
    }elseif(strpos($key, 'new_booking') !== false){
        return 'new_booking';
    }elseif(strpos($key, 'booking_approved') !== false){
        return 'booking_approved';
    }elseif(strpos($key, 'listing_expired') !== false){
        return 'listing_expired';
    }elseif(strpos($key, 'ad_expired') !== false){
        return 'ad_expired';
    }

    
    return 'no_type';
}

// for user notification
function townhub_addons_reset_user_notification_type( $type = '', $user_id = 0 ){
    if(is_user_logged_in()){
        $user_id = get_current_user_id();
    }

    if(!empty($type)){
        $notifications = get_user_meta( $user_id, ESB_META_PREFIX.'notifications', true );
        if(!empty($notifications) && is_array($notifications)){
            $type_keys = array();
            foreach ($notifications as $key => $msg) {
                if(strpos($key, $type) !== false) $type_keys[] = $key;
            }
            if(!empty($type_keys)){
                foreach ($type_keys as $tkey) {
                    unset($notifications[$tkey]);
                }
                update_user_meta( $user_id, ESB_META_PREFIX.'notifications', $notifications );
            }
        }
    }
        
}



function townhub_addons_get_post_status($status){
    $statuses = array(
        'publish' => __( 'Publish', 'townhub-add-ons' ),
        'pending' => __( 'Pending', 'townhub-add-ons' ),
        'draft' => __( 'Draft', 'townhub-add-ons' ),
        'auto-draft' => __( 'Auto Draft', 'townhub-add-ons' ),
        'future' => __( 'Future', 'townhub-add-ons' ),
        'private' => __( 'Private', 'townhub-add-ons' ),
        'inherit' => __( 'Inherit', 'townhub-add-ons' ),
        'trash' => __( 'Trash', 'townhub-add-ons' ),
    );

    if(!empty($status) && isset($statuses[$status])) return $statuses[$status];

    return $statuses;

}
// display pacakge status - depends on backend order status and its expired date
function townhub_addons_get_package_status( $status ) {
    $statuses = array(
        'completed_in_time' => __( 'Active', 'townhub-add-ons' ),
        'completed_expired' => __( 'Expired', 'townhub-add-ons' ),
        'pending_in_time' => __( 'Pending', 'townhub-add-ons' ),
        'pending_expired' => __( 'Pending - Expired', 'townhub-add-ons' ),

        'trialing_in_time' => __( 'Trialing', 'townhub-add-ons' ),
        'trialing_expired' => __( 'Trialing - Expired', 'townhub-add-ons' ),
        
    );
    if(!empty($status) && isset($statuses[$status])) return $statuses[$status];

    return $statuses['pending_in_time'];
}
function townhub_addons_get_package_time_status($post_ID){
    $end_date = get_post_meta( $post_ID, ESB_META_PREFIX.'end_date', true );
    $current_date = current_time( 'mysql' , 1);
    if($end_date >= $current_date)
        return 'in_time';
    else
        return 'expired';
}
// get order/package/membership payment type - One Time or Recurring
function townhub_addons_get_order_type($meta = ''){
    if($meta == 'on'){
        // on is because using cmb2
        return __( 'Recurring', 'townhub-add-ons' );
    }

    return __( 'One Time', 'townhub-add-ons' );
}

function townhub_addons_get_post_orderby($order = ''){
    $orders = array(
        'none'  => __( 'No order', 'townhub-add-ons' ),
        'ID'  => __( 'Order by post id', 'townhub-add-ons' ),
        'author'  => __( 'Order by author', 'townhub-add-ons' ),
        'title'  => __( 'Order by post title', 'townhub-add-ons' ),
        'name'  => __( 'Order by post slug', 'townhub-add-ons' ),
        'type'  => __( 'Order by post type', 'townhub-add-ons' ),
        'date'  => __( 'Order by date', 'townhub-add-ons' ),
        'modified'  => __( 'Order by last modified date', 'townhub-add-ons' ),
        'parent'  => __( 'Order by post parent id', 'townhub-add-ons' ),
        'rand'  => __( 'Random order', 'townhub-add-ons' ),
        'comment_count'  => __( 'Order by number of comments', 'townhub-add-ons' ),
        'relevance'  => __( 'Order by search terms', 'townhub-add-ons' ),
        'menu_order'  => __( 'Order by Page Order', 'townhub-add-ons' ),
        'listing_featured'  => __( 'Listing Featured', 'townhub-add-ons' ),
        'event_start_date'  => __( 'Event Start Date', 'townhub-add-ons' ),
        'most_viewed'  => __( 'Popularity - Most viewed', 'townhub-add-ons' ),


    );

    if(!empty($order) && isset($orders[$order])) return $orders[$order];

    return $orders;
}

function townhub_addons_get_listing_content_order_default(){
    return array('promo_video','content','gallery','slider','faqs','speaker');
}
function townhub_addons_get_listing_widget_order_default(){
    return array('wkhour','countdown','price_range','booking','weather','contacts','author','moreauthor','addfeas');
}

function townhub_addons_display_recaptcha($ele_id){
    if( townhub_addons_get_option('enable_g_recaptcah') == 'yes' && townhub_addons_get_option('g_recaptcha_site_key') != '' ) 
        echo '<div id="'.$ele_id.'" class="cth-recaptcha"></div>';
}

function townhub_addons_verify_recaptcha(){
        
    if( townhub_addons_get_option('enable_g_recaptcah') == 'yes' && townhub_addons_get_option('g_recaptcha_secret_key') != '' ){
        if( !isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) return false;
        // $response = wp_remote_get( 
        //     add_query_arg( 
        //         array(
        //             'secret'   => townhub_addons_get_option('g_recaptcha_secret_key'),
        //             'response' => isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '',
        //             'remoteip' => isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']
        //         ), 
        //         'https://www.google.com/recaptcha/api/siteverify' 
        //     ) 
        // );

        $response = wp_remote_post( 
            'https://www.google.com/recaptcha/api/siteverify' ,
            array(
                'body' =>   array(
                                'secret'   => townhub_addons_get_option('g_recaptcha_secret_key'),
                                'response' => $_POST['g-recaptcha-response'],
                                'remoteip' => isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']
                            )
            )
        );

        // return json_decode( $response['body'] ); // captcha: {success: true, challenge_ts: "2019-03-19T09:58:27Z", hostname: "localhost"}



        if( is_wp_error( $response ) || empty($response['body']) || ! ($json = json_decode( $response['body'] )) || ! $json->success ) {
            //return new WP_Error( 'validation-error',  __('reCAPTCHA validation failed. Please try again.' ) );
            return false;
        }

        return true;
    }

    return true;
}
// https://stackoverflow.com/questions/10808109/script-tag-async-defer
function townhub_addons_add_async_forscript($url)
{
    if(is_admin()){
        return str_replace(array('#cthasync','#cthdefer'), '', $url);
    }else{
        if(strpos($url, '#cthasync') !== false) $url = str_replace('#cthasync', '', $url)."' async='async"; 
        if(strpos($url, '#cthdefer') !== false) $url = str_replace('#cthdefer', '', $url)."' defer='defer"; 
    }

    return $url;


    // if (strpos($url, '#cthasync')===false)
    //     return $url;
    // else if (is_admin())
    //     return str_replace('#cthasync', '', $url);
    // else
    //     return str_replace('#cthasync', '', $url)."' async='async"; 
}
add_filter('clean_url', 'townhub_addons_add_async_forscript', 11, 1);

function townhub_addons_get_active_plan_ids(){

    $post_args = array(
        'fields'            => 'ids',
        'post_type'         => 'lplan',
        'posts_per_page'    => -1,
        'post_status'       => 'publish',

        'suppress_filters'  => false,
    );

    return get_posts($post_args);

}

// handle image upload with multiple files
function townhub_addons_handle_image_multiple_upload($field_name, $post_id = 0){
    $return_array = array();

    if($field_name != '' && isset($_FILES[$field_name])){
        $process_files = array();
        $field_name_files = $_FILES[$field_name];  
        foreach ($field_name_files['name'] as $key => $value) {            
            if ($field_name_files['name'][$key]) {
                $file = array( 
                    'name' => $field_name_files['name'][$key],
                    'type' => $field_name_files['type'][$key], 
                    'tmp_name' => $field_name_files['tmp_name'][$key], 
                    'error' => $field_name_files['error'][$key],
                    'size' => $field_name_files['size'][$key]
                ); 
                $process_files[] = $file;
                
            }
        } 

        

        foreach ($process_files as $key => $file) {
                
            $movefile = townhub_addons_handle_image_upload($file);

            if(is_array($movefile)){

                // https://wordpress.stackexchange.com/questions/40301/how-do-i-set-a-featured-image-thumbnail-by-image-url-when-using-wp-insert-post
                // https://codex.wordpress.org/Function_Reference/wp_insert_attachment
                // Prepare an array of post data for the attachment.
                $attachment = array(
                    // 'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
                    'post_mime_type' => $movefile['type'],
                    'post_title'     => sanitize_file_name(basename($movefile['file'])),
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                );

                // // Insert the attachment.
                $attach_id = wp_insert_attachment( $attachment, $movefile['file'], $post_id );

                if($attach_id != 0){
                    // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                    require_once( ABSPATH . 'wp-admin/includes/image.php' );

                    // Generate the metadata for the attachment, and update the database record.
                    $attach_data = wp_generate_attachment_metadata( $attach_id, $movefile['file'] );
                    // return value from update_post_meta -  https://codex.wordpress.org/Function_Reference/update_post_meta
                    // Returns meta_id if the meta doesn't exist, otherwise returns true on success and false on failure. NOTE: If the meta_value passed to this function is the same as the value that is already in the database, this function returns false.
                    wp_update_attachment_metadata( $attach_id, $attach_data );
                    // Post meta ID on success, false on failure.
                    // $json['data']['meta_id'] = set_post_thumbnail( $listing_id, $attach_id );

                    // $headerimgsMeta[] = array( $attach_id , wp_get_attachment_url( $attach_id ) );
                    $return_array[$attach_id] = wp_get_attachment_url( $attach_id ) ;
                }

            }

        }

        

    }
    // end if check

    return $return_array;
}

// https://codex.wordpress.org/Function_Reference/wp_handle_upload
function townhub_addons_handle_image_upload($uploadedfile){
    if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
    }

    // check to make sure its a successful upload
    if ($uploadedfile['error'] !== UPLOAD_ERR_OK) return 'No file was uploaded.';
    // $uploadedfile = $_FILES['file'];
    if( $uploadedfile['size']/1024/1024 > townhub_addons_get_option('submit_media_limit_size') ){
        if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). "File uploaded is too large. " . $uploadedfile['size']/1024/1024 . PHP_EOL, 3, ESB_LOG_FILE);
        return 'File uploaded is too large';
    }

    $upload_overrides = array( 'test_form' => false );

    $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

    if ( $movefile && ! isset( $movefile['error'] ) ) {
        // echo "File is valid, and was successfully uploaded.\n";
        // var_dump( $movefile );
        return $movefile;
    } else {
        /**
         * Error generated by _wp_handle_upload()
         * @see _wp_handle_upload() in wp-admin/includes/file.php
         */
        return $movefile['error'];
    }
}
function townhub_addons_limit_upload_size( $file ) {

    // check for file is image
    // Do basic extension validation and MIME mapping.
    $wp_filetype = wp_check_filetype( $file['name'], false );
    $ext         = $wp_filetype['ext'];
    $type        = $wp_filetype['type']; // or using uploade $file['type']
    // Validate image types.
    if ( $type && 0 === strpos( $type, 'image/' ) ) {
        // exclude admins
        if ( ! current_user_can( 'manage_options' ) ) {
            // Set the desired file size limit
            $file_size_limit = townhub_addons_get_option('submit_media_limit_size'); // in MB
        

            $current_size = $file['size'];
            $current_size = $current_size / 1024 / 1024; //get size in MB

            if ( $current_size > $file_size_limit ) {
                return array( "error"=> sprintf( _x( 'ERROR: File size limit is %d MB.','TownHub Add-Ons', 'townhub-add-ons' ), $file_size_limit ) );
            }
            // check for image size
            // https://wordpress.stackexchange.com/questions/28359/how-to-require-a-minimum-image-dimension-for-uploading
            $imgsize = getimagesize( $file['tmp_name'] );
            $min_width = townhub_addons_get_option('media_min_width', '480');
            $min_height = townhub_addons_get_option('media_min_height', '320');
            $width = $imgsize[0];
            $height = $imgsize[1];

            if( $width < $min_width ){
                return array( "error"=> sprintf( _x( 'Image dimensions are too small. Minimum width is %spx. Uploaded image width is %spx','TownHub Add-Ons', 'townhub-add-ons' ), $min_width, $min_width ) );
            }elseif( $height < $min_height ){
                return array( "error"=> sprintf( _x( 'Image dimensions are too small. Minimum height is %spx. Uploaded image height is %spx','TownHub Add-Ons', 'townhub-add-ons' ), $min_height, $min_height ) );
            }
        }
    }

    return $file; 
}
add_filter ( 'wp_handle_upload_prefilter', 'townhub_addons_limit_upload_size', 10, 1 );


function townhub_addons_post_nav( $tax = 'category' ) {
    
    if( townhub_addons_get_option('single_post_nav' ) != 'yes' ) return ;

    $prev_post = get_adjacent_post( townhub_addons_get_option('single_same_term' ) , '', true, $tax );
    $next_post = get_adjacent_post( townhub_addons_get_option('single_same_term' ) , '', false, $tax );

    if ( is_a( $prev_post, 'WP_Post' ) || is_a( $next_post, 'WP_Post' ) ) :
?>
<div class="post-nav single-post-nav listing-post-nav fl-wrap">
<?php
    if ( is_a( $prev_post, 'WP_Post' ) ) :
    ?>
    <a href="<?php echo get_permalink( $prev_post->ID ); ?>" class="post-link prev-post-link" title="<?php echo get_the_title($prev_post->ID ); ?>"><i class="fa fa-angle-left"></i><?php esc_html_e('Prev','townhub-add-ons' );?><span class="clearfix"><?php echo get_the_title($prev_post->ID ); ?></span></a>
    <?php 
    endif ; ?>
<?php
    if ( is_a( $next_post, 'WP_Post' ) ) :
    ?>
    <a href="<?php echo get_permalink( $next_post->ID ); ?>" class="post-link next-post-link" title="<?php echo get_the_title($next_post->ID ); ?>"><i class="fa fa-angle-right"></i><?php esc_html_e('Next','townhub-add-ons' );?><span class="clearfix"><?php echo get_the_title($next_post->ID ); ?></span></a>
    <?php 
    endif ; ?>
</div>
<?php
    endif;
}
// get active plan for setting selection
function townhub_addons_get_listing_plans(){
    $results = array(
        ''      => __( 'None', 'townhub-add-ons' ),
    );

    $post_args = array(
        'post_type' => 'lplan',
        
        'posts_per_page'=> -1,
        'orderby'          => 'date',
        'order'            => 'DESC',

        'post_status' => 'any',

        'suppress_filters'  => false,
    );

    $posts = get_posts( $post_args );
    if ( $posts ) {
        foreach ( $posts as $post ) {
            $results[$post->ID] = apply_filters( 'the_title' , $post->post_title, $post->ID );
            
        }
    }

    return $results;
}
function townhub_addons_cont_fiels_select(){
     $listing_locs = get_terms( array(
        'taxonomy' => 'listing_location',
        'hide_empty' => false
    ) );
    if ( ! empty( $listing_locs ) && ! is_wp_error( $listing_locs ) ){
        $locs = array();
        foreach ($listing_locs as $loc ){
             $locs[]= array(
                'value' => $loc->term_id,
                'label' => $loc->name
             );
        }
    }
    // $listing_locations = townhub_addons_get_listing_locations(true); 
    $listing_cats = townhub_addons_get_listing_categories(townhub_addons_get_option('search_cat_level'));
    $cont = array();
    foreach ($listing_cats as $cat) {
        $cont = array(
            'category'  => $listing_cats,
            'location'  => $locs,
        );         
    }
    return $cont;
}
function townhub_addons_get_listing_features($hide_empty = false){
    $featuresed = array();
    // $features = get_the_terms(get_the_ID(), 'listing_feature');
    $features =  get_terms( 
        array(
            'taxonomy' => 'listing_feature',
            'hide_empty' => $hide_empty,
        )
    );
    if ( $features && ! is_wp_error( $features ) ){ 
        foreach ( $features as $term ) {
            $featuresed[] = array(
                'name' => $term->name,
                'id'   =>  $term->term_id,
            );      
        }
    }
    return $featuresed;
}



function townhub_addons_compare_dates($date_one = '', $date_two = '', $compare = '<'){
    $date_one = new DateTime($date_one);
    $date_two = new DateTime($date_two);

    switch ($compare) {
        case '<=':
            return $date_one <= $date_two;
            break;
        case '=':
            return $date_one == $date_two;
            break;
        case '>=':
            return $date_one >= $date_two;
            break;
        case '<':
            return $date_one < $date_two;
            break;
        case '>':
            return $date_one > $date_two;
            break;
        default:
            return $date_one < $date_two;
            break;
    }
}

function cth_get_week_days(){
    $days = array(
                _x('Mon', 'calendar', 'townhub-add-ons'),
                _x('Tue', 'calendar', 'townhub-add-ons'),
                _x('Wed', 'calendar', 'townhub-add-ons'),
                _x('Thu', 'calendar', 'townhub-add-ons'),
                _x('Fri', 'calendar', 'townhub-add-ons'),
                _x('Sat', 'calendar', 'townhub-add-ons'),
            );
    $sunday = _x('Sun', 'calendar', 'townhub-add-ons');
    if( townhub_addons_get_option('week_starts_monday') == 'yes' ){
        $days[] = $sunday;
    }else{
        array_unshift($days, $sunday);
    }
    return $days;
}