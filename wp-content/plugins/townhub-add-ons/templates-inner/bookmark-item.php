<?php
/* add_ons_php */
$address = get_post_meta( get_the_ID(), ESB_META_PREFIX.'address', true );
$latitude = get_post_meta( get_the_ID(), ESB_META_PREFIX.'latitude', true );
$longitude = get_post_meta( get_the_ID(), ESB_META_PREFIX.'longitude', true );
?>
<li class="wishlist-item">
    <div class="widget-posts-img">
        <a href="<?php the_permalink(  ); ?>"><?php the_post_thumbnail( 'townhub-recent' ); ?></a>  
    </div>
    <div class="widget-posts-descr">
        <h4><a href="<?php the_permalink(  ); ?>"><?php the_title(); ?></a></h4>

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
        <div class="clear-wishlist delete-bookmark-btn" data-id="<?php the_ID(); ?>"><i class="fal fa-times-circle"></i></div>
    </div>
</li>