<?php
/* add_ons_php */
global $post;

$listing_bookmarks = get_user_meta( get_current_user_id(), ESB_META_PREFIX.'listing_bookmarks', true );

townhub_addons_reset_user_notification_type('bookmarked');
?>
<div class="dashboard-content-wrapper dashboard-content-bookmarks">
    <div class="dashboard-content-inner">
        
        <div class="dashboard-title fl-wrap">
            <h3><?php _e( 'Bookmark Listings', 'townhub-add-ons' ); ?></h3>
        </div>
        
        <div class="dashboard-listings-grid">
        <?php 
        if(!empty($listing_bookmarks) && is_array($listing_bookmarks)){
            foreach ($listing_bookmarks as $lid) {
                if( !get_post($lid) ) continue;
                $post = get_post($lid);
                setup_postdata( $post );
                $address = get_post_meta( get_the_ID(), ESB_META_PREFIX.'address', true );
                ?>
                <div id="listing-<?php the_ID(); ?>" class="dashboard-card dashboard-listing-item wishlist-item">
                    
                    <?php
                    if(has_post_thumbnail( )){ ?>
                    <div class="dashboard-card-thumbnail">
                        <a href="<?php echo esc_url( get_permalink() );?>"><?php the_post_thumbnail('listing-featured',array('class'=>'respimg') ); ?></a>
                    </div>
                    <?php } ?>

                    <div class="dashboard-card-content">
                        <?php echo sprintf( __( '<h4 class="entry-title"><a href="%3$s" rel="bookmark">%1$s</a> - <span>%2$s</span></h4>', 'townhub-add-ons' ) , get_the_title(  ), get_the_date( get_option( 'date_format' ) ), esc_url( get_permalink() ) ); ?>
                        <?php 
                        if( !empty($address) ): ?>
                        <div class="geodir-category-location clearfix"><small><?php echo $address; ?></small></div>
                        <?php 
                        endif; ?>

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
                        
                    </div>

                    <div class="booking-list-contr">
                        <a href="#" class="del-bg tolt delete-bookmark-btn" data-microtip-position="left" data-tooltip="<?php esc_attr_e( 'Delete', 'townhub-add-ons' ); ?>" data-id="<?php echo get_the_ID();?>" data-title="<?php echo esc_attr( get_the_title() ); ?>"><i class="fal fa-trash"></i></a>
                    </div>

                
                </div>
                <!-- dashboard-list end--> 
                <?php
                wp_reset_postdata();
            }
        }else{
            ?>
            <div id="listing-no" class="dashboard-card dashboard-listing-item">
                <div class="dashboard-card-content">
                    <?php _e( '<p>You have no bookmark.</p>', 'townhub-add-ons' ); ?>
                </div>
            </div>
            
            <?php
        }
        ?>
        </div>
    </div>
</div>

    