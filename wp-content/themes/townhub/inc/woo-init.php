<?php
/* banner-php */
// remove wrapper divs
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
// remove default woo sidebar
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );


//add action woo before main content
add_action( 'woocommerce_before_main_content', function(){
    if( townhub_get_option('show_shop_header', false) && ( is_shop() || is_product_category() || is_product_tag() ) ): ?>
    <!--  section  -->
    <section class="parallax-section single-par" data-scrollax-parent="true">
        <div class="bg par-elem" data-bg="<?php echo esc_url( townhub_get_attachment_thumb_link( townhub_get_option('shop_header_image' ), 'full' )  );?>" data-scrollax="properties: { translateY: '30%' }"></div>
        <div class="overlay op7"></div>
        <div class="container">
            <div class="section-title center-align big-title">
                <h1 class="head-sec-title"><span><?php echo wp_kses_post( townhub_get_option('shop_head_title') );?></span></h1>
                <span class="section-separator"></span>
                <?php echo wp_kses_post( townhub_get_option('shop_head_intro') ); ?>
            </div>
        </div>
        <div class="header-sec-link">
            <a href="#main-sec" class="custom-scroll-link"><i class="fal fa-angle-double-down"></i></a> 
        </div>
    </section>
    <!--  section  end-->
    <?php 
    endif;

    do_action( 'townhub_shop_header' ); 

}, 2 );

add_filter( 'woocommerce_page_title', function($title){
    if(is_single()) $title = single_post_title('',false);
    return $title;
});

// change single title
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
add_action( 'woocommerce_single_product_summary', function(){
    the_title( '<h2 class="product_title entry-title">', '</h2>' );
}, 5 );



add_action( 'woocommerce_before_shop_loop', function(){
    echo '<div class="shop-list-header block_box no-vis-shadow">';
}, 15 );
add_action( 'woocommerce_before_shop_loop', function(){
    echo '</div>';
}, 35 );



// add woo shop layout
add_action( 'woocommerce_before_main_content', function(){
    $sb_w = townhub_get_option('blog-sidebar-width','4');
    $shop_sidebar = townhub_get_option('shop_sidebar','left_sidebar');


    add_action( 'townhub_shop_sidebar', 'woocommerce_get_sidebar' );
    ?>
    <section class="gray-section cth-shop-sec gray-bg small-padding no-top-padding-sec" id="main-sec">
        <div class="container">
            <div class="breadcrumbs-wrapper inline-breadcrumbs block-breadcrumbs">
                <?php do_action( 'townhub_shop_breadcrumbs' ); ?>
                <?php if( is_singular( 'product' ) && function_exists('townhub_addons_breadcrumbs_socials_share') ) townhub_addons_breadcrumbs_socials_share(); ?>  
            </div>
            <div class="row">
                <?php 
                if( $shop_sidebar == 'fullwidth' || !is_active_sidebar('sidebar-shop') ): ?>
                    <div class="col-md-12 col-wrap">
                <?php else:
                    if($shop_sidebar == 'left_sidebar'): ?>
                        <div class="col-md-<?php echo esc_attr($sb_w );?> shop-sidebar-column">
                            <div class="shop-sidebar box-widget-wrap fl-wrap left-sidebar">
                                <?php do_action( 'townhub_shop_sidebar' ); ?>                
                            </div>
                        </div>
                        <div class="col-md-<?php echo (12 - $sb_w);?> col-wrap shop-content-column">
                    <?php else: ?>
                        <div class="col-md-<?php echo (12 - $sb_w);?> col-wrap shop-content-column">
                    <?php endif;
                    
                endif; 

},5);

//add action woo after main content
add_action( 'woocommerce_after_main_content', function(){
    $sb_w = townhub_get_option('blog-sidebar-width','4');
    $shop_sidebar = townhub_get_option('shop_sidebar','left_sidebar');
    ?>
                </div>
                <!-- end col-md-9 -->
                <?php 
                if($shop_sidebar == 'right_sidebar' && is_active_sidebar('sidebar-shop') ): ?>
                    <div class="col-md-<?php echo esc_attr($sb_w );?> shop-sidebar-column">
                        <div class="shop-sidebar box-widget-wrap fl-wrap right-sidebar">
                            <?php do_action( 'townhub_shop_sidebar' ); ?>                
                        </div>
                    </div>
                <?php endif; ?>

            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </section>
    <!-- end gray-section cth-shop-sec -->
    <?php
}, 30 );

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );



add_action( 'woocommerce_before_shop_loop_item', function(){
    ?>
    <div class="cth-woo-item-wrap">
        <div class="cth-woo-img"><?php do_action( 'cth-woo-product-top' ); ?></div><!-- .cth-woo-img -->
        <div class="cth-woo-content clearfix">
    <?php
}, 1 );

add_action( 'woocommerce_after_shop_loop_item', function(){

        
    ?>
            <div class="cth-woo-content-bot"><?php do_action( 'cth_woo_content_bot' ); ?></div>
        </div>
        <!-- .cth-woo-content -->
    </div>
    <!-- .cth-woo-item-wrap -->
    <?php
}, 99 );

add_action('cth-woo-product-top', 'woocommerce_template_loop_product_link_open', 5);
add_action('cth-woo-product-top', 'woocommerce_show_product_loop_sale_flash', 10);
add_action('cth-woo-product-top', 'woocommerce_template_loop_product_thumbnail', 10);
add_action('cth-woo-product-top', function(){
    echo '<div class="overlay"></div>';
}, 10);
add_action('cth-woo-product-top', 'woocommerce_template_loop_product_link_close', 15);
add_action('cth-woo-product-top', 'woocommerce_template_loop_add_to_cart', 20);
add_action('cth-woo-product-top', 'woocommerce_template_loop_rating', 20);


add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 11);


add_action('cth_woo_content_bot', 'woocommerce_template_loop_product_link_open', 5);
add_action('cth_woo_content_bot', function(){
    esc_html_e( 'Details', 'townhub' );
}, 10);
add_action('cth_woo_content_bot', 'woocommerce_template_loop_product_link_close', 15);


// define the woocommerce_pagination_args callback 
function townhub_woocommerce_pagination_args( $array ) { 
    // make filter magic happen here... 
    $array = array(
        'prev_text' => '<i class="fa fa-caret-left"></i>', 
        'next_text' => '<i class="fa fa-caret-right"></i>',
    );
    return $array; 
}; 
         
// add the filter 
add_filter( 'woocommerce_pagination_args', 'townhub_woocommerce_pagination_args', 10, 1 ); 


//remove action
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
//add action woo breadcrumb
add_action( 'townhub_shop_breadcrumbs', 'woocommerce_breadcrumb');


// add wrapper div for single content top
add_action( 'woocommerce_before_single_product_summary', function(){
    echo '<div class="fl-wrap block_box product-header">';
}, 5 );
add_action( 'woocommerce_after_single_product_summary', function(){
    echo '</div><!-- end fl-wrap block_box product-header -->';
}, 5 );
