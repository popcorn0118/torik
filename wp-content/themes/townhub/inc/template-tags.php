<?php
/* banner-php */
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 */
function townhub_get_option( $setting, $default = null ) {
    global $townhub_options;

    $default_options = array(
        // thumbnail sizes
        'enable_custom_sizes' => true,
        
        'thumb_size_opt_4' => array(
            'width'     => '424',
            'height'    => '280',
            'hard_crop' => '1',
        ),
        'thumb_size_opt_5' => array(
            'width'     => '388',
            'height'    => '257',
            'hard_crop' => '1',
        ),
        'thumb_size_opt_6' => array(
            'width'     => '795',
            'height'    => '257',
            'hard_crop' => '1',
        ),
        'thumb_size_opt_7' => array(
            'width'     => '1200',
            'height'    => '532',
            'hard_crop' => '1',
        ),
        'thumb_size_opt_8' => array(
            'width'     => '381',
            'height'    => '240',
            'hard_crop' => '1',
        ),


        'thumb_size_opt_9' => array(
            'width'     => '806',
            'height'    => '530',
            'hard_crop' => '1',
        ),
        'thumb_size_opt_10' => array(
            'width'     => '806',
            'height'    => '530',
            'hard_crop' => '1',
        ),
        'thumb_size_opt_11' => array(
            'width'     => '82',
            'height'    => '54',
            'hard_crop' => '1',
        ),
        //
        'single_featured' => true,
        'blog_show_format' => true,

        'single_comments' => true,
        'single_author' => true,
        'single_date' => true,
        'single_cats' => true,
        'single_tags' => true,

        'blog_comments' => true,
        'blog_author' => true,
        'blog_date' => true,
        'blog_cats' => true,
        'blog_tags' => true,
        // blog
        'blog_layout' => 'right_sidebar',
        'blog_content_width' => 'big-container',
        'blog_header_image' =>  get_template_directory_uri().'/assets/images/bg/3.jpg',
        'blog_head_title' => 'Our Last News',
        'blog_head_intro' => '<p class="head-intro">Praesent nec leo venenatis elit semper aliquet id ac enim.</p>',
        // footer
        'footer_copyright' => '<span class="ft-copy">&#169; <a href="https://themeforest.net/user/cththemes" target="_blank">CTHthemes</a> 2019.  All rights reserved.</span>',

        'footer_widgets'     => townhub_get_footer_widgets_default(),
        'footer_widgets_top'=> townhub_get_footer_widgets_top_default(),
        

        'error404_bg' => get_template_directory_uri().'/assets/images/bg/29.jpg',
        'error404_btn' => true,
        'error404_msg' => "<p>We're sorry, but the Page you were looking for, couldn't be found.</p>",

        'loader_icon' => '',

        'show_mini_cart'    => true,

        'user_menu_style'   => 'one',

        'footer_currencies'         => 1,
        'enable_auto_update'        => 0,
        'shop_columns'              => 'three',
        'shop_columns_tablet'       => 'two',
        'header_height'             => 80,
        'post_heading_tag'          => 'h1',
        'single_title_tag'          => 'h1',
    );

    if( is_customize_preview() ) $townhub_options = get_option( 'townhub_options', array() );

    $value = false;
    if ( isset( $townhub_options[ $setting ] ) ) {
        $value = $townhub_options[ $setting ];
    }else {
        if(isset($default)){
            $value = $default;
        }else if( isset( $default_options[ $setting ] ) ){
            $value = $default_options[ $setting ];
        }
    }
    return $value;
}

function townhub_global_var($opt_1 = '', $opt_2 = '', $opt_check = false, $default = false){
    global $townhub_options;
    // if( is_customize_preview() ) $townhub_options = get_option( 'townhub_options', array() );
    if( $opt_check ) {
        if( isset($townhub_options[$opt_1]) && isset($townhub_options[$opt_1][$opt_2]) ) {
            return $townhub_options[$opt_1][$opt_2];
        }
    } else {
        if(isset($townhub_options[$opt_1])) {
            return $townhub_options[$opt_1];
        }
    }

    return $default;
    
}

/**
 * Blog post nav
 *
 * @since TownHub 1.0
 */
if (!function_exists('townhub_post_nav')) {
    function townhub_post_nav() {
        
        if(townhub_get_option('single_post_nav', true ) == false) return ;

        $prev_post = get_adjacent_post( townhub_get_option('single_same_term', false ) , '', true );
        $next_post = get_adjacent_post( townhub_get_option('single_same_term', false ) , '', false );

        if ( is_a( $prev_post, 'WP_Post' ) || is_a( $next_post, 'WP_Post' ) ) :
?>
<div class="post-nav-wrap fl-wrap">
    <?php
        if ( is_a( $prev_post, 'WP_Post' ) ) :
        ?>
        <a href="<?php echo get_permalink( $prev_post->ID ); ?>" class="post-nav post-nav-prev<?php if( has_post_thumbnail( $prev_post->ID ) ) echo ' post-nav-has-thumb'; ?>" title="<?php echo get_the_title($prev_post->ID ); ?>">
            <?php if( has_post_thumbnail( $prev_post->ID ) ): ?>
            <span class="post-nav-img"><?php echo get_the_post_thumbnail( $prev_post->ID ); ?></span>
            <?php endif; ?>
            <span class="post-nav-text"><strong><?php esc_html_e( 'Prev Post', 'townhub' ); ?></strong><?php echo sprintf(__( '<br /> %s', 'townhub' ), get_the_title($prev_post->ID ) ); ?></span>
        </a>
        <?php 
        endif ; ?>
    <?php
        if ( is_a( $next_post, 'WP_Post' ) ) :
        ?>
        <a href="<?php echo get_permalink( $next_post->ID ); ?>" class="post-nav post-nav-next<?php if( has_post_thumbnail( $next_post->ID ) ) echo ' post-nav-has-thumb'; ?>" title="<?php echo get_the_title($next_post->ID ); ?>">
            <?php if( has_post_thumbnail( $next_post->ID ) ): ?>
            <span class="post-nav-img"><?php echo get_the_post_thumbnail( $next_post->ID ); ?></span>
            <?php endif; ?>
            <span class="post-nav-text"><strong><?php esc_html_e( 'Next Post', 'townhub' ); ?></strong><?php echo sprintf(__( '<br /> %s', 'townhub' ), get_the_title($next_post->ID ) ); ?></span>
        </a>
        <?php 
        endif ; ?>
</div>
    <?php
        endif;
    }
}

/**
 * Single Portfolio Slider nav
 *
 * @since TownHub 1.0
 */
if (!function_exists('townhub_folio_slider_nav')) {
    function townhub_folio_slider_nav() {
        
        if(townhub_get_option('folio_show_nav', true ) == false) return ;

        $prev_post = get_adjacent_post( townhub_get_option('folio_nav_same_term', false ) , '', true , 'portfolio_cat');

        $next_post = get_adjacent_post( townhub_get_option('folio_nav_same_term', false ) , '', false , 'portfolio_cat');

        if ( is_a( $prev_post, 'WP_Post' ) || is_a( $next_post, 'WP_Post' ) ) :
    ?>
    <!-- swiper-slide-->  
    <div class="swiper-slide portfolio-nav-slide">
        <div class="slider-content-nav-wrap full-height">
            <div class="slider-content-nav fl-wrap">
                <ul>
                    <?php
                    if ( is_a( $prev_post, 'WP_Post' ) ) :
                    ?>
                    <li class="prev-post">
                        <span><?php esc_html_e('Prev','townhub' );?></span>
                        <a href="<?php echo get_permalink( $prev_post->ID ); ?>" title="<?php echo get_the_title($prev_post->ID ); ?>"><?php echo get_the_title($prev_post->ID ); ?></a>
                    </li>
                    <?php 
                    endif ; ?>
                    <?php
                    if ( is_a( $next_post, 'WP_Post' ) ) :
                    ?>
                    <li class="next-post">
                        <span><?php esc_html_e('Next','townhub' );?></span>
                        <a href="<?php echo get_permalink( $next_post->ID ); ?>" title="<?php echo get_the_title($next_post->ID ); ?>"><?php echo get_the_title($next_post->ID ); ?></a>
                    </li>
                    <?php 
                    endif ; ?>
                </ul>
            </div>
        </div>
    </div>
    <!-- swiper-slide end--> 
    <?php 
    endif;
    }
}

/**
 * Single Portfolio Slider nav
 *
 * @since TownHub 1.0
 */
if (!function_exists('townhub_folio_nav')) {
    function townhub_folio_nav() {
        
        if(townhub_get_option('folio_show_nav', true ) == false) return ;

        $prev_post = get_adjacent_post( townhub_get_option('folio_nav_same_term', false ) , '', true , 'portfolio_cat');

        $next_post = get_adjacent_post( townhub_get_option('folio_nav_same_term', false ) , '', false , 'portfolio_cat');

        if ( is_a( $prev_post, 'WP_Post' ) || is_a( $next_post, 'WP_Post' ) ) :
    ?> 
    <div class="content-nav fl-wrap">
        <ul>
            <?php
            if ( is_a( $prev_post, 'WP_Post' ) ) :
            ?>
            <li class="prev-post">
                <span><?php esc_html_e('Prev','townhub' );?></span>
                <a href="<?php echo get_permalink( $prev_post->ID ); ?>" title="<?php echo get_the_title($prev_post->ID ); ?>"><?php echo get_the_title($prev_post->ID ); ?></a>
            </li>
            <?php 
            endif ; ?>
            <?php
            if ( is_a( $next_post, 'WP_Post' ) ) :
            ?>
            <li class="next-post">
                <span><?php esc_html_e('Next','townhub' );?></span>
                <a href="<?php echo get_permalink( $next_post->ID ); ?>" title="<?php echo get_the_title($next_post->ID ); ?>"><?php echo get_the_title($next_post->ID ); ?></a>
            </li>
            <?php 
            endif ; ?>
        </ul>
    </div> 
    <?php 
    endif;
    }
} 

/**
 * Custom comments list
 *
 * @since TownHub 1.0
 */
if (!function_exists('townhub_comments')) {
    function townhub_comments($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        extract($args, EXTR_SKIP);
        
        if ('div' == $args['style']) {
            $tag = 'div';
            $add_below = 'comment';
        } 
        else {
            $tag = 'li';
            $add_below = 'div-comment';
        }
?>
        <<?php
        echo esc_attr($tag); ?> <?php
        comment_class(empty($args['has_children']) ? 'comment-item comment-nochild reviews-comments-item only-comments' : 'comment-item comment-haschild reviews-comments-item only-comments') ?> id="comment-<?php
        comment_ID() ?>">
        <?php
        if ('div' != $args['style']): ?>
        <div id="div-comment-<?php
            comment_ID() ?>" class="comment-body thecomment">
        <?php
        endif; ?>
        
            <div class="comment-avatar review-comments-avatar">
                <?php if ($args['avatar_size'] != 0) echo get_avatar($comment, $args['avatar_size'], 'https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s='.$args['avatar_size'], get_comment_author( $comment->comment_ID ) ); ?>
            </div>
            <div class="comment-text reviews-comments-item-text fl-wrap">
                <div class="reviews-comments-header fl-wrap">
                    <h4 class="comment-author"><?php echo get_comment_author_link($comment->comment_ID); ?></h4>
                </div>
                <?php comment_text(); ?>
                <div class="reviews-comments-item-footer fl-wrap">
                    <div class="reviews-comments-item-date">
                        <span class="comment-date"><i class="far fa-calendar-check"></i><?php echo get_comment_date(esc_html__('F j, Y g:i a', 'townhub')); ?></span>
                    </div>
                    <span class="comment-reply"><?php comment_reply_link(array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?></span>
                    <?php
                    if ($comment->comment_approved == '0'): ?>
                            <em class="comment-awaiting-moderation alignleft"><?php
                        esc_html_e('Your comment is awaiting moderation.', 'townhub'); ?></em>
                            <br />
                        <?php
                    endif; ?> 
                </div>
            </div>
            


        
        <?php
        if ('div' != $args['style']): ?>
        </div> 
        <?php
        endif; ?>

    <?php
    }
}


if (!function_exists('townhub_pagination')) {
    function townhub_pagination(){

        the_posts_pagination( array(
            'prev_text' =>  wp_kses(__('<i class="fas fa-caret-left"></i><span>Prev</span>','townhub'),array('span'=>array('class'=>array(),),'i'=>array('class'=>array(),),) ) ,
            'next_text' =>  wp_kses(__('<span>Next</span><i class="fas fa-caret-right"></i>','townhub'),array('span'=>array('class'=>array(),),'i'=>array('class'=>array(),),) ) ,
            'screen_reader_text' => esc_html__( 'Posts navigation', 'townhub' ),
        ) );

    }
}
/**
 * Pagination for Portfolio page templates
 *
 * @since TownHub 1.0
 */
if (!function_exists('townhub_custom_pagination')) {
    function townhub_custom_pagination($pages = '', $range = 2, $current_query = '', $sec_wrap = false) {
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
            echo '<div class="pagination-container">';
            if ($paged > 1) echo '<a href="' . get_pagenum_link($paged - 1) . '" class="prevposts-link">'.wp_kses(__('<i class="fa fa-chevron-left"></i>','townhub'),array('i'=>array('class'=>array(),),) ).'</a>';
            for ($i = 1; $i <= $pages; $i++) {
                if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems)) {
                    if ($paged == $i) 
                        echo "<a class='blog-page current-page' href='javascript:void(0);'>" . $i . "</a>" ;
                    else 
                        echo "<a href='" . get_pagenum_link($i) . "' class='blog-page'>" . $i . "</a>";
                }
            }
            if ($paged < $pages) echo '<a href="' . get_pagenum_link($paged + 1) . '" class="nextposts-link">'.wp_kses(__('<i class="fa fa-chevron-right"></i>','townhub'),array('i'=>array('class'=>array(),),) ).'</a>';
            echo '</div>';
        }

    }
}

/**
 * Pagination for Portfolio Slider page templates
 *
 * @since TownHub 1.0
 */
if (!function_exists('townhub_folio_slider_pagination')) {
    function townhub_folio_slider_pagination($pages = '', $range = 2, $current_query = '', $sec_wrap = false) {

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
        	?>
        	<div class="swiper-slide">
                <div class="slider-content-nav-wrap full-height">
                    <div class="slider-content-nav fl-wrap">
                        <ul>
                            <li><?php if ($paged > 1) : ?>
                            	<span><?php esc_html_e('Prev','townhub' );?></span>
                                <a href="<?php echo get_pagenum_link($paged - 1) ; ?>"><?php esc_html_e('Previous Projects','townhub' );?></a>
                            <?php endif;?></li>
                            <li><?php if ($paged < $pages) : ?>
                            	<span><?php esc_html_e('Next','townhub' );?></span>
                                <a href="<?php echo get_pagenum_link($paged + 1) ; ?>"><?php esc_html_e('Next Projects','townhub' );?></a>
                            <?php endif;?></li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php 
            
        }

    }
}



function townhub_breadcrumbs($classes='') {
           
    // Settings
    $breadcrums_id      = 'breadcrumbs';
    $breadcrums_class   = 'breadcrumbs fl-wrap '.$classes;
    $home_title         = esc_html__('Home','townhub');
    $blog_title         = esc_html__('Blog','townhub');


    // If you have any custom post types with custom taxonomies, put the taxonomy name below (e.g. product_cat)
    $custom_taxonomy    = '';

    $custom_post_types = array(
                                'listing' => esc_html_x('Listing','listing archive in breadcrumb','townhub'),
                                'product' => esc_html_x('Products','product archive in breadcrumb','townhub'),
                                
                            );
      
    // Get the query & post information
    global $post;
      
    // Do not display on the homepage
    // if ( !is_front_page() ) {
      
        // Build the breadcrums
        echo '<div class="' . esc_attr($breadcrums_class ) . '">';
          
        // Home page
        echo '<a class="breadcrumb-link breadcrumb-home" href="' . esc_url( home_url() ) . '" title="' . esc_attr($home_title ) . '">' . esc_attr($home_title ) . '</a>';

        if(is_home()){
            // Blog page
            echo '<span class="breadcrumb-current breadcrumb-item-blog">' . $blog_title . '</span>';
        }

        // if ( is_singular( 'post' ) || is_category() ){
        //     echo '<a class="breadcrumb-link breadcrumb-item-blog" href="' . get_permalink( get_option( 'page_for_posts' ) ) . '" title="'.esc_attr( get_the_title( get_option( 'page_for_posts' ) ) ).'">' . get_the_title( get_option( 'page_for_posts' ) ) .'</a> ';
        // }
          
        if ( is_archive() && !is_tax() ) {

            // If post is a custom post type
            $post_type = get_post_type();

            if($post_type && array_key_exists($post_type, $custom_post_types)){
                echo '<span class="breadcrumb-current breadcrumb-item-custom-post-type-' . esc_attr($post_type) . '">' . $custom_post_types[$post_type] . '</span>';
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
             
                echo '<a class="breadcrumb-link breadcrumb-custom-post-type-' . esc_attr($post_type) . '" href="' . esc_url($post_type_archive ) . '" title="' . esc_attr($post_type_object->labels->name) . '">' . $post_type_object->labels->name . '</a>';
             
            }
             
            $custom_tax_name = get_queried_object()->name;
            echo '<span class="breadcrumb-current bread-item-archive">' . esc_attr($custom_tax_name) . '</span>';
             
        } else if ( is_single() ) {
            
            // If post is a custom post type
            $post_type = get_post_type();
            $last_category = '';
            // If it is a custom post type (not support custom taxonomy) display name and link
            if( !in_array( $post_type, array('post','listing') ) ) {
                 
                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);

                if(array_key_exists($post_type, $custom_post_types)){
                    echo '<a class="breadcrumb-link breadcrumb-cat breadcrumb-custom-post-type-' . esc_attr($post_type) . '" href="' . esc_url($post_type_archive ) . '" title="' . esc_attr($custom_post_types[$post_type]) . '">' . $custom_post_types[$post_type] . '</a>';
                }else{
                    echo '<a class="breadcrumb-link breadcrumb-cat breadcrumb-custom-post-type-' . esc_attr($post_type) . '" href="' . esc_url($post_type_archive ) . '" title="' . esc_attr($post_type_object->labels->name) . '">' . $post_type_object->labels->name . '</a>';
                }
                
                echo '<span class="breadcrumb-current breadcrumb-item-' . esc_attr($post->ID) . '" title="' . esc_attr(get_the_title()) . '">' . get_the_title() . '</span>';
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
                        $cat_display .= '<span class="breadcrumb-items breadcrumb-item-cat">'.$parents.'</span>';
                        
                    }
                }
                
                if(!empty($last_category)) {
                    echo wp_kses_post($cat_display );
                    echo '<span class="breadcrumb-current breadcrumb-item-' . esc_attr($post->ID) . '" title="' . esc_attr(get_the_title()) . '">' . get_the_title() . '</span>';
                     
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
                        $cat_link       = get_term_link($taxonomy_terms[0]->term_id, $custom_taxonomy_val);
                        $cat_name       = $taxonomy_terms[0]->name;

                        if(!empty($cat_id)) {
                     
                            echo '<a class="breadcrumb-link bread-cat-' . esc_attr($cat_id) . ' bread-cat-' . esc_attr($cat_nicename) . '" href="' . esc_url($cat_link ) . '" title="' . esc_attr($cat_name) . '">' . $cat_name . '</a>';
                            
                            echo '<span class="breadcrumb-current breadcrumb-item-' . esc_attr($post->ID) . '" title="' . esc_attr(get_the_title()) . '">' . get_the_title() . '</span>';
                         
                        }
                    }

                 } 
                
              
            }
             
            
             
        } else if ( is_category() ) {
              
            // Category page
            echo '<span class="breadcrumb-current breadcrumb-item-cat-' . esc_attr($category[0]->term_id) . ' bread-cat-' . esc_attr($category[0]->category_nicename) . '">' . $category[0]->cat_name . '</span>';
              
        } else if ( is_page() ) {
            

            // Standard page
            if( $post->post_parent ){

                $parents = '';
                  
                // If child page, get parents 
                $anc = get_post_ancestors( $post->ID );
                  
                // Get parents in the right order
                $anc = array_reverse($anc);
                  
                // Parent page loop
                foreach ( $anc as $ancestor ) {
                    $parents .= '<a class="breadcrumb-link breadcrumb-parent-' . esc_attr($ancestor) . '" href="' . esc_url(get_permalink($ancestor) ) . '" title="' . esc_attr(get_the_title($ancestor)) . '">' . get_the_title($ancestor) . '</a>';
                    
                }
                  
                // Display parent pages
                echo wp_kses_post($parents );


                  
                    // Current page
                    echo '<span class="breadcrumb-current breadcrumb-item-page-' . esc_attr($post->ID) . '" title="' . esc_attr(get_the_title()) . '">' . get_the_title() . '</span>';

                
                  
            } else {
                  
                
                    // Current page
                    echo '<span class="breadcrumb-current breadcrumb-item-page-' . esc_attr($post->ID) . '" title="' . esc_attr(get_the_title()) . '">' . get_the_title() . '</span>';

                  
            }
              
        } else if ( is_tag() ) {
              
            // Tag page
              
            // Get tag information
            $term_id = get_query_var('tag_id');
            $taxonomy = 'post_tag';
            $args ='include=' . $term_id;
            $terms = get_terms( $taxonomy, $args );
              
            // Display the tag name
            echo '<span class="breadcrumb-current breadcrumb-item-tag-' . esc_attr($terms[0]->term_id). ' bread-tag-' . esc_attr($terms[0]->slug) . '">' . $terms[0]->name . '</span>';
          
        } elseif ( is_day() ) {
              
            // Day archive
              
            // Year link
            echo '<a class="breadcrumb-link breadcrumb-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . esc_html__(' Archives','townhub').'</a>';
            
              
            // Month link
            echo '<a class="breadcrumb-link breadcrumb-month bread-month-' . get_the_time('m') . '" href="' . get_month_link( get_the_time('Y'), get_the_time('m') ) . '" title="' . get_the_time('M') . '">' . get_the_time('M') . esc_html__(' Archives','townhub').'</a>';
            
              
            // Day display
            echo '<span class="breadcrumb-current bread-' . get_the_time('j') . '"> ' . get_the_time('jS') . ' ' . get_the_time('M') .  esc_html__(' Archives','townhub').'</span>';
              
        } else if ( is_month() ) {
              
            // Month Archive
              
            // Year link
            echo '<a class="breadcrumb-link breadcrumb-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . esc_html__(' Archives','townhub').'</a>';
            
              
            // Month display
            echo '<span class="breadcrumb-current breadcrumb-month breadcrumb-month-' . get_the_time('m') . '" title="' . get_the_time('M') . '">' . get_the_time('M') . esc_html__(' Archives','townhub').'</span>';
              
        } else if ( is_year() ) {
              
            // Display year archive
            echo '<strong class="breadcrumb-current breadcrumb-current-' . get_the_time('Y') . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . esc_html__(' Archives','townhub').'</span>';
              
        } else if ( is_author() ) {
              
            // Auhor archive
              
            // Get the author information
            global $author;
            $userdata = get_userdata( $author );
              
            // Display author name
            echo '<span class="breadcrumb-current breadcrumb-current-' . esc_attr( $userdata->user_nicename ) . '" title="' . esc_attr($userdata->display_name) . '">' .  esc_html__(' Author: ','townhub') . $userdata->display_name . '</span>';
          
        } else if ( get_query_var('paged') ) {
              
            // Paginated archives
            echo '<span class="breadcrumb-current breadcrumb-current-' . get_query_var('paged') . '" title="'.esc_attr__('Page','townhub') . get_query_var('paged') . '">'.esc_html__('Page','townhub') . ' ' . get_query_var('paged') . '</span>';
              
        } else if ( is_search() ) {
          
            // Search results page
            echo '<span class="breadcrumb-current breadcrumb-current-' . get_search_query() . '" title="'.esc_attr__('Search results for: ','townhub') . get_search_query() . '">'.esc_html__('Search results for: ','townhub') . get_search_query() . '</span>';
          
        } elseif ( is_404() ) {
              
            // 404 page
            echo '<span class="breadcrumb-current breadcrumb-current-404">' . esc_html__('Error 404','townhub') . '</span>';
        }
      
        echo '</div>';
          
    // }
      
}




if ( ! function_exists( 'townhub_edit_link' ) ) :
/**
 * Returns an accessibility-friendly link to edit a post or page.
 *
 * This also gives us a little context about what exactly we're editing
 * (post or page?) so that users understand a bit more where they are in terms
 * of the template hierarchy and their content. Helpful when/if the single-page
 * layout with multiple posts/pages shown gets confusing.
 */
function townhub_edit_link() {
	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'townhub' ),
			get_the_title()
		),
		'<span class="edit-link">',
		'</span>'
	);
}
endif;

/** 
 * Check for existing shortcode or not
 * https://codex.wordpress.org/Function_Reference/get_shortcode_regex
 */ 
if ( !function_exists('townhub_check_shortcode') ) {
    function townhub_check_shortcode($content,$shortcode=''){

        if ( !empty($shortcode) && !shortcode_exists( $shortcode ) ) {

            $pattern = get_shortcode_regex(array($shortcode));

            return preg_replace('/'. $pattern .'/s', '', $content );

        }else {
            return $content;
        }  
    }
}

/**
 * Woocommerce support
 *
 */

if ( ! function_exists( 'townhub_is_woocommerce_activated' ) ) {
    function townhub_is_woocommerce_activated() {
        if ( class_exists( 'WooCommerce' ) ) { return true; } else { return false; }
    }
}

function townhub_get_header_cart_link(){
    if(townhub_is_woocommerce_activated() && townhub_get_option('show_mini_cart') ){
        global $woocommerce;
        $my_cart_count = $woocommerce->cart->cart_contents_count;
        if($my_cart_count > 0){
            $url = wc_get_page_permalink( 'cart' );
        }else{
            $url = wc_get_page_permalink( 'shop' );
        }
        return array('url'=>$url,'count'=>$my_cart_count);
    }else{
        return false;
    }
}
function townhub_link_pages(){
    wp_link_pages( array(
        'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'townhub' ) . '</span>',
        'after'       => '</div>',
        'link_before' => '<span>',
        'link_after'  => '</span>',
    ) );
}
function townhub_post_meta(){
    // if( townhub_get_option( 'blog_author') || townhub_get_option( 'blog_date' )  || townhub_get_option( 'blog_cats' ) || townhub_get_option( 'blog_comments' ) || townhub_get_option( 'blog_tags' ) ):
    ?>
    <span class="fw-separator"></span>
    <div class="post-metas-wrap flex-items-center">
        <?php if( townhub_get_option( 'blog_author') ):?>
        <div class="post-author">
            <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>">
                <?php 
                    echo get_avatar(get_the_author_meta('user_email'),'80','https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=80', get_the_author_meta( 'display_name' ) );
                ?>
                <?php echo sprintf( __( '<span>By, %s</span>', 'townhub' ), get_the_author() ); ?>
                    
            </a>
        </div>
        <?php endif;?>
        <?php if( townhub_get_option( 'blog_date' )  || townhub_get_option( 'blog_cats' ) || townhub_get_option( 'blog_comments' )  ):?>
        <div class="post-opt">
            <ul class="no-list-style flex-items-center">
                <?php if( townhub_get_option( 'blog_date' ) ):?><li class="postl-date"><i class="fal fa-calendar"></i><span><?php the_time(get_option('date_format'));?></span></li><?php endif;?>
                <?php if( function_exists('townhub_addons_get_post_views') ) :?>
                <li class="postl-views"><i class="fal fa-eye"></i><span><?php echo townhub_addons_get_post_views(get_the_ID());?></span></li>
                <?php endif;?>
                <?php if( townhub_get_option( 'blog_cats' ) && get_the_category( )  ):?>
                    <li class="postl-cats"><i class="fal fa-tags"></i><?php the_category( ' , ' ); ?></li>  
                <?php endif;?>
            </ul>
        </div>
        <?php endif;?>

        <a href="<?php the_permalink(  ); ?>" class="btn color2-bg readmore-btn"><?php esc_html_e( 'Read more', 'townhub' ); ?><i class="fal fa-angle-right"></i></a>

    </div>
    <?php
    // endif;
}
function townhub_post_tags(){
    if( townhub_get_option( 'blog_tags' ) && get_the_tags( ) ) :?>
    <span class="fw-separator"></span>
    <div class="list-single-tags tags-stylwrap">
        <span class="tags-title"><i class="fas fa-tag"></i><?php esc_html_e( 'Tags: ', 'townhub' ); ?></span>
        <div class="tag-items-wrap">
            <?php the_tags('','','');?>                                                                          
        </div>
    </div>
    <?php endif;
}
function townhub_single_post_meta(){
    
    ?>
    
    <div class="post-metas-wrap flex-items-center">
        <?php if( townhub_get_option( 'single_author') ):?>
        <div class="post-author">
            <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>">
                <?php 
                    echo get_avatar(get_the_author_meta('user_email'),'80','https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=80', get_the_author_meta( 'display_name' ) );
                ?>
                <?php echo sprintf( __( '<span>By, %s</span>', 'townhub' ), get_the_author() ); ?>
                    
            </a>
        </div>
        <?php endif;?>
        <?php if( townhub_get_option( 'single_date' )  || townhub_get_option( 'single_cats' ) || townhub_get_option( 'single_comments' ) ):?>
        <div class="post-opt">
            <ul class="no-list-style flex-items-center">
                <?php if( townhub_get_option( 'single_date' ) ):?><li class="spost-date"><i class="fal fa-calendar"></i><span><?php the_time(get_option('date_format'));?></span></li><?php endif;?>
                <?php if( function_exists('townhub_addons_get_post_views') ) :?>
                <li class="spost-views"><i class="fal fa-eye"></i><span><?php echo townhub_addons_get_post_views(get_the_ID());?></span></li>
                <?php endif;?>
                <?php if( townhub_get_option( 'single_cats' ) && get_the_category( )  ):?>
                    <li class="spost-cats"><i class="fal fa-tags"></i><?php the_category( ' , ' ); ?></li>  
                <?php endif;?>
                <?php if( townhub_get_option( 'single_comments' ) ):?>
                <li class="spost-comments"><i class="fal fa-comments"></i> <?php comments_popup_link( esc_html_x('0 comment','comment counter None format' ,'townhub'), esc_html_x('1 comment','comment counter One format', 'townhub'), esc_html_x('% comments','comment counter Plural format', 'townhub') ); ?></li>
                <?php endif;?>
            </ul>
        </div>
        <?php endif;?>
    </div>
    <span class="fw-separator"></span>
    <?php
    // endif;
}
function townhub_single_post_tags(){
    if( townhub_get_option( 'single_tags' ) && get_the_tags( ) ) :?>
    <span class="fw-separator"></span>
    <div class="list-single-tags tags-stylwrap">
        <span class="tags-title"><i class="fas fa-tag"></i><?php esc_html_e( 'Tags: ', 'townhub' ); ?></span>
        <div class="tag-items-wrap">
            <?php the_tags('','','');?>                                                                          
        </div>
    </div>
    <?php endif;
}
function townhub_sticky_post(){
    if( is_sticky(  ) ){
        echo '<span class="sticky-post-badge">'.esc_html__( 'FEATURED', 'townhub' ).'</span>';
    }
}
