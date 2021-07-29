<?php
/* add_ons_php */



//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $posts_per_page = $order_by = $order = $title = $responsive = $taxonomy = $hide_widget_on = '';  

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
    'azp_element',
    'lsimilar_listings',
    'azp-element-' . $azp_mID,
    $el_class,
);

$classes = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $classes ) ) );  

if($el_id!=''){
    $el_id = 'id="'.$el_id.'"';
}

if(( $hide_widget_on_check = townhub_addons_is_hide_on_plans($hide_widget_on) ) !== 'true') :

    if( $taxonomy == 'featured' ){
        $post_args = array(
            'post_type'         => 'listing',  
            'post__not_in'      => array(get_the_ID()),
            'orderby'           => $order_by,
            'order'             => $order, 
            'posts_per_page'    => $posts_per_page,
            'meta_query'   => array(
                array(
                    'key'     => ESB_META_PREFIX .'featured',
                    'value'   => '1',
                    'type'      => 'NUMERIC'
                )
            ),
        );
    }else{
        $terms_ids = array();
        $terms = get_the_terms( get_the_ID(), $taxonomy );
        if ( $terms && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $terms_ids[] = $term->term_id;
            }
        }
        $post_args = array(
            'post_type'         => 'listing',  
            'post__not_in'      => array(get_the_ID()),
            'orderby'           => $order_by,
            'order'             => $order, 
            'posts_per_page'    => $posts_per_page,
            'tax_query' => array(
                array(
                    'taxonomy' => $taxonomy,
                    'field'    => 'term_id',
                    'terms'    => $terms_ids,
                ),
            ),
            // 'meta_query'   => array(
            //     array(
            //         'key'     => ESB_META_PREFIX .'featured',
            //         'value'   => '1',
            //         'type'      => 'NUMERIC'
            //     )
            // ),
        );
    }
$posts_query = new \WP_Query($post_args);
if($posts_query->have_posts()) : ?>
<div class="<?php echo $classes; ?> authplan-hide-<?php echo $hide_widget_on_check;?>" <?php echo $el_id;?>>
    <div class="for-hide-on-author"></div>
    <!--box-widget-item -->
    <div class="box-widget-item fl-wrap block_box">
        <?php if($title != ''): ?>
        <div class="box-widget-item-header">
            <h3><?php echo $title; ?></h3>
        </div>
        <?php endif; ?>
        <div class="box-widget  fl-wrap">
            <div class="box-widget-content">

                <!-- carousel -->
                <div class="similar-listings-slider-wrap airbnb-style">
                    
                    <?php 
                    $slider_args = array();
                    $breakpoints = array();
                    $slidesPerView = array();
                    $responsive = explode( ",", trim( $responsive ) );
                    
                    if( !empty($responsive) ){
                        foreach ($responsive as $breakpoint) {
                            $breakpoint = explode( ":", trim($breakpoint) );
                            if( count($breakpoint) === 2 ){
                                $breakpoints[$breakpoint[0]] = array( 'slidesPerView'=>intval($breakpoint[1]) );
                                $slidesPerView[] = intval($breakpoint[1]);
                            }
                        }
                    }
                    if( !empty($breakpoints) ){
                        $slider_args['slidesPerView'] = max($slidesPerView);
                        
                        $slider_args['breakpoints'] = $breakpoints;
                    }

                    ?>
                    
                    <div class="listing-slider fl-wrap">
                        <div class="swiper-container" data-options='<?php echo json_encode($slider_args); ?>'>
                            <div class="swiper-wrapper">
                                <?php 
                                while($posts_query->have_posts()) : $posts_query->the_post(); 
                                    townhub_addons_get_template_part( 'template-parts/listing-slider', false, array( 
                                        'for_slider'    => true, 
                                        'hide_status'   => 'yes', 
                                        'hide_featured' => 'yes',
                                        'hide_author'   => 'yes',
                                    ) );
                                endwhile; ?>
                            </div>
                        </div>
                        <div class="listing-carousel-button listing-carousel-button-next2"><i class="fas fa-caret-right"></i></div>
                        <div class="listing-carousel-button listing-carousel-button-prev2"><i class="fas fa-caret-left"></i></div>
                    </div>
                    <div class="tc-pagination_wrap">
                        <div class="tc-pagination2"></div>
                    </div>
                    

                </div>
                <!--  carousel end-->

            </div>
        </div>
    </div>
    <!--box-widget-item end -->  
</div> 
<?php endif;
wp_reset_postdata();

endif;// check hide on plans 

