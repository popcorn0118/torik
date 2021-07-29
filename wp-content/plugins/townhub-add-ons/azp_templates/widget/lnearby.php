<?php
/* add_ons_php */



//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $posts_per_page = $order_by = $order = $title = $responsive = $taxonomy = $nearby_type = $hide_widget_on = $cat_ids = '';  

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
    'azp_element',
    'lnearby_listings',
    'azp-element-' . $azp_mID,
    $el_class,
);

$classes = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $classes ) ) );  

if($el_id!=''){
    $el_id = 'id="'.$el_id.'"';
}

if(( $hide_widget_on_check = townhub_addons_is_hide_on_plans($hide_widget_on) ) !== 'true') :

$post_args = array(
    'post_type'             => 'listing',  
    'post__not_in'          => array(get_the_ID()),
    // 'orderby'            => $order_by,
    // 'order'              => $order, 
    'posts_per_page'        => $posts_per_page,
    'suppress_filters'      => false,
    'cthqueryid'            => $nearby_type,
    
);
$tax_queries = array();
if( !empty($cat_ids) ){
    $tax_queries[] =    array(
                            'taxonomy' => 'listing_cat',
                            'field'    => 'term_id',
                            'terms'    => explode( ",", $cat_ids ),
                        );
}
if(!empty($tax_queries)){
    $post_args['tax_query'] = $tax_queries;
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
                <!--widget-posts-->
                <div class="widget-posts  fl-wrap">
                    <ul class="no-list-style">
                    <?php 
                    while($posts_query->have_posts()) : $posts_query->the_post(); 
                        $address = get_post_meta( get_the_ID(), ESB_META_PREFIX.'address', true );
                        $latitude = get_post_meta( get_the_ID(), ESB_META_PREFIX.'latitude', true );
                        $longitude = get_post_meta( get_the_ID(), ESB_META_PREFIX.'longitude', true );

                        ?>
                        <li class="dis-flex">
                            <?php if(has_post_thumbnail()): ?>
                            <div class="widget-posts-img">
                                <a href="<?php the_permalink( ); ?>">
                                    <?php the_post_thumbnail( 'townhub-recent' ); ?>
                                </a>  
                            </div>
                            <?php endif; ?>
                            <div class="widget-posts-descr">
                                <h4><a href="<?php the_permalink( ); ?>"><?php the_title(); ?></a></h4>
                                <?php
                                if($address != ''): ?>
                                <div class="geodir-category-location fl-wrap"><a href="https://www.google.com/maps/search/?api=1&query=<?php echo $latitude.','.$longitude;?>" target="_blank"><i class="fas fa-map-marker-alt"></i><?php echo $address;?></a></div>
                                <?php endif;?>
                                <?php 
                                $cats = get_the_terms(get_the_ID(), 'listing_cat');
                                if ( $cats && ! is_wp_error( $cats ) ){ ?>
                                    <div class="widget-posts-descr-link">
                                        <?php 
                                        foreach( $cats as $key => $cat){
                                            
                                            echo sprintf( '<a href="%1$s" class="widget-post-cat">%2$s</a> ',
                                                townhub_addons_get_term_link( $cat->term_id, 'listing_cat' ),
                                                esc_html( $cat->name )
                                            );
                                        }
                                        ?>
                                    </div>
                                <?php }  ?>
                                <?php 
                                $rating = townhub_addons_get_average_ratings(get_the_ID());    ?>
                                <?php if( $rating != false && !empty($rating['sum']) ): ?>
                                    <div class="widget-posts-descr-score"><?php echo $rating['sum']; ?></div>
                                <?php endif; ?> 
                                
                                <?php 
                                global $post;
                                if( isset($post->listing_distance) && !empty($post->listing_distance) ){
                                    echo '<div class="lcard-distance">';
                                        echo sprintf(_x( 'Distance: <strong>%s km</strong>', 'Lcard distance', 'townhub-add-ons' ), number_format($post->listing_distance,2) );
                                    echo '</div>';
                                } ?>

                            </div>
                        </li>
                        <?php
                    endwhile;
                    ?> 
                    </ul>
                </div>
                <!-- widget-posts end-->
            </div>
        </div>
    </div>
    <!--box-widget-item end -->  
</div> 
<?php endif;
wp_reset_postdata();

endif;// check hide on plans 

