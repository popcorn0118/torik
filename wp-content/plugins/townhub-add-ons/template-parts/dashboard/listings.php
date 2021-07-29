<?php
/* add_ons_php */
// don't show on customer dashboard

townhub_addons_reset_user_notification_type('listing_publish');

$current_user = wp_get_current_user();    

if(is_front_page()) {
    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
} else {
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
}                 

$args = array(
    'post_type'     =>  'listing', 
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
<div class="dashboard-content-wrapper dashboard-content-listings">
    <div class="dashboard-content-inner">
        
        <div class="dashboard-title fl-wrap">
            <h3><?php _e( 'Listings', 'townhub-add-ons' ); ?></h3>
        </div>
        
        <div class="dashboard-listings-grid">
            
            <?php 
            if($posts_query->have_posts()) :
                while($posts_query->have_posts()) : $posts_query->the_post(); 
                    $address = get_post_meta( get_the_ID(), ESB_META_PREFIX.'address', true );
                    $latitude = get_post_meta( get_the_ID(), ESB_META_PREFIX.'latitude', true );
                    $longitude = get_post_meta( get_the_ID(), ESB_META_PREFIX.'longitude', true );
                    $direction = 'javascript:void(0);';
                    if( !empty($latitude) && !empty($longitude) ) $direction = "https://www.google.com/maps/search/?api=1&query=$latitude,$longitude";
                ?>
                <div id="listing-<?php the_ID(); ?>" <?php post_class('dashboard-card dashboard-listing-item'); ?>>
                    
                    <?php
                    if(has_post_thumbnail( )){ ?>
                    <div class="dashboard-card-thumbnail">
                        <a href="<?php echo esc_url( get_permalink() );?>"><?php the_post_thumbnail('listing-featured',array('class'=>'respimg') ); ?></a>

                        <span class="booking-list-new green-bg"><?php echo townhub_addons_get_post_status(get_post_status(get_the_ID()));?></span>

                    </div>
                    <?php } ?>

                    <div class="dashboard-card-content">
                        <?php echo sprintf( __( '<h4 class="entry-title"><a href="%3$s" rel="bookmark">%1$s</a> - <span>%2$s</span></h4>', 'townhub-add-ons' ) , get_the_title(  ), get_the_date( get_option( 'date_format' ) ), esc_url( get_permalink() ) ); ?>
                        <?php 
                        if( !empty($address) ): ?>
                        <div class="geodir-category-location clearfix">
                            <a href="<?php echo $direction; ?>"><?php echo $address; ?></a></div>
                        <?php 
                        endif; ?>
                        <a href="#" class="set-lfeatured<?php if( get_post_meta( get_the_ID(), ESB_META_PREFIX.'featured', true ) == '1' ) echo ' lfeatured' ;?>" data-id="<?php echo get_the_ID();?>"><span class="lfeatured-loading"><i class="fa fa-spinner fa-pulse"></i></span><span class="as-lfeatured"><?php esc_html_e( 'Set as featured', 'townhub-add-ons' );?></span><span class="lfeatured"><?php esc_html_e( 'Featured', 'townhub-add-ons' );?></span></a>
                    </div>

                    <div class="booking-list-contr">
                        <a href="<?php echo esc_url( townhub_addons_get_edit_listing_url( ) );?>" class="green-bg tolt" data-microtip-position="left" data-tooltip="<?php esc_attr_e( 'Edit', 'townhub-add-ons' ); ?>" ><i class="fal fa-edit"></i></a>
                        
                        <a href="#" class="del-bg tolt del-listing" data-microtip-position="left" data-tooltip="<?php esc_attr_e( 'Delete', 'townhub-add-ons' ); ?>" data-id="<?php echo get_the_ID();?>" data-title="<?php echo esc_attr( get_the_title() ); ?>"><i class="fal fa-trash"></i></a>
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
                    <?php _e( '<p>You have no listing yet!</p>', 'townhub-add-ons' ); ?>
                </div>
            </div>
            <?php
            endif; ?> 

        </div>
    </div>
</div>
