<?php
/* add_ons_php */
// don't show on customer dashboard

$current_user = wp_get_current_user();    

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;             

$args = array(
    'post_type'     =>  'product', 
    'author'        =>  $current_user->ID, 
    'orderby'       =>  'date',
    'order'         =>  'DESC',
    'paged'         => $paged,
    // 'posts_per_page' => -1 // no limit

    'post_status'   => array( 'publish', 'pending', 'draft', 'future' ),
);

// The Query
$posts_query = new WP_Query( $args );

?>
<div class="dashboard-content-wrapper dashboard-content-products">
    <div class="dashboard-content-inner">
        
        <div class="dashboard-title fl-wrap">
            <h3><?php _ex( 'WooCommerce Products','Dashboard', 'townhub-add-ons' ); ?></h3>
        </div>
        
        <div class="dashboard-products-grid">
            
            <?php 
            $productsUrl = Esb_Class_Dashboard::screen_url('products');
            if($posts_query->have_posts()) :
                while($posts_query->have_posts()) : $posts_query->the_post(); 
                    $editUrl = add_query_arg( 'edit', get_the_ID(), $productsUrl );
                ?>
                <div id="product-<?php the_ID(); ?>" <?php post_class('dashboard-card dashboard-listing-item'); ?>>
                    
                    <?php
                    if(has_post_thumbnail( )){ ?>
                    <div class="dashboard-card-thumbnail">
                        <a href="<?php echo esc_url( get_permalink() );?>"><?php the_post_thumbnail('listing-featured',array('class'=>'respimg') ); ?></a>

                        <span class="booking-list-new green-bg"><?php echo townhub_addons_get_post_status(get_post_status(get_the_ID()));?></span>

                    </div>
                    <?php } ?>

                    <div class="dashboard-card-content">
                        <?php echo sprintf( __( '<h4 class="entry-title"><a href="%3$s" rel="bookmark">%1$s</a> - <span>%2$s</span></h4>', 'townhub-add-ons' ) , get_the_title(  ), get_the_date( get_option( 'date_format' ) ), esc_url( get_permalink() ) ); ?>
                    </div>

                    <div class="booking-list-contr">
                        <a href="<?php echo esc_url( $editUrl );?>" class="green-bg tolt" data-microtip-position="left" data-tooltip="<?php esc_attr_e( 'Edit', 'townhub-add-ons' ); ?>" ><i class="fal fa-edit"></i></a>
                    </div>
                </div>
                <!-- dashboard-list end--> 
            <?php 
                endwhile; 
                echo townhub_addons_custom_pagination($posts_query->max_num_pages,$range = 2, $posts_query);
            
                /* Restore original Post Data 
                 * NB: Because we are using new WP_Query we aren't stomping on the 
                 * original $wp_query and it does not need to be reset with 
                 * wp_reset_query(). We just need to set the post data back up with
                 * wp_reset_postdata().
                 */
                wp_reset_postdata();
            else:
            ?> 
            <div id="listing-no" class="dashboard-card dashboard-listing-item">
                <div class="dashboard-card-content">
                    <?php _ex( '<p>You have no products yet!</p>','Dashboard', 'townhub-add-ons' ); ?>
                </div>
            </div>
            <?php
            endif; ?> 

        </div>
    </div>
</div>
