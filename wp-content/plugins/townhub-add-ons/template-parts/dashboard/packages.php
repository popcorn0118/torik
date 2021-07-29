<?php
/* add_ons_php */
// don't show on customer dashboard


townhub_addons_reset_user_notification_type('order_completed');

$current_user = wp_get_current_user();    

if(is_front_page()) {
    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
} else {
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
}                 

$args = array(
    'post_type'         =>  'lorder', 
    'author'            =>  $current_user->ID, 
    'orderby'           =>  'date',
    'order'             =>  'DESC',
    'paged'             => $paged,
    // double test for user package
    
    'post_status'       => array( 'publish', 'pending', 'draft', 'future' ),

    'meta_query' => array(
        // 'relation' => 'AND',
        array(
            'key'     => ESB_META_PREFIX.'user_id',
            'value'   => $current_user->ID,
        ),
        // array(
        //     'relation' => 'OR',
        //     array(
        //             'key' => ESB_META_PREFIX.'order_type',
        //             'compare' => 'NOT EXISTS',
        //     ),
        //     array(
        //             'key' => ESB_META_PREFIX.'order_type',
        //             'value'   => 'listing_ad',
        //             'compare' => '!=',
        //     ),
        // ),
    ),

);

// The Query
$posts_query = new WP_Query( $args );

?>
<div class="col-md-9 dashboard-content-col">
    <table class="cth-table table-package">
        <thead>
            <tr>
                <th><?php _e( 'ID', 'townhub-add-ons' ); ?></th>
                <th><?php _e( 'Plan', 'townhub-add-ons' ); ?></th>
                <th><?php _e( 'Payment Type', 'townhub-add-ons' ); ?></th>
                <th><?php _e( 'Payment Count', 'townhub-add-ons' ); ?></th>
                <th><?php _e( 'Start', 'townhub-add-ons' ); ?></th>
                <th><?php _e( 'End', 'townhub-add-ons' ); ?></th>
                <th><?php _e( 'Status', 'townhub-add-ons' ); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php 
        if($posts_query->have_posts()) :
            while($posts_query->have_posts()) : $posts_query->the_post(); 
                $status = get_post_meta( get_the_ID(), ESB_META_PREFIX.'status', true );
                $time_status = townhub_addons_get_package_time_status(get_the_ID());
                $plan_id = get_post_meta( get_the_ID(), ESB_META_PREFIX.'plan_id', true);
            ?>
            <tr id="lorder-<?php the_ID(); ?>" <?php post_class('dashboard-list'); ?>>
                <td><?php the_ID();?></td>
                <td><?php echo get_the_title($plan_id); ?></td>
                <td><?php echo townhub_addons_get_order_type( get_post_meta( get_the_ID(), ESB_META_PREFIX.'is_recurring_plan', true) ); ?></td>
                <td><?php echo get_post_meta( get_the_ID(), ESB_META_PREFIX.'payment_count', true); ?></td>
                <td><?php echo Esb_Class_Date::i18n( get_post_meta( get_the_ID(), ESB_META_PREFIX.'from_date', true) ); ?></td>
                <td><?php echo Esb_Class_Date::i18n( get_post_meta( get_the_ID(), ESB_META_PREFIX.'end_date', true) ); ?></td>
                <td><span class="package-status <?php echo esc_attr( 'is-'.$status ); ?>"><?php echo townhub_addons_get_package_status($status.'_'.$time_status);?></span></td>
            </tr>

            <tr>
                <td colspan="7">
                    
                    <?php 
                    $listings_limit = get_post_meta( get_the_ID(), ESB_META_PREFIX.'plan_llimit', true );
                    if($listings_limit == 'unlimited') $listings_limit = __( 'Unlimited', 'townhub-add-ons' );
                    $listings = get_post_meta( get_the_ID(), ESB_META_PREFIX.'listings', true);
                    // var_dump($listings);
                    if(!empty($listings) && is_array($listings)){
                        $listings_limit_text = sprintf(__( '<span class="listing-count">%d</span>/<span class="listing-limit">%s</span>', 'townhub-add-ons' ), count((array)$listings), $listings_limit);
                    }else{
                        $listings_limit_text = sprintf(__( '<span class="listing-count zero">0</span>/<span class="listing-limit">%2$s</span>', 'townhub-add-ons' ), count((array)$listings), $listings_limit);
                    }
                    // featured
                    $featured_limit = get_post_meta( $plan_id, ESB_META_PREFIX.'lfeatured', true );
                    $featured = get_post_meta( get_the_ID(), ESB_META_PREFIX.'featured', true);
                    if(!empty($featured) && is_array($featured)){
                        $featued_limit_text = sprintf(__( '<span class="listing-count">%2$s</span>/<span class="listing-limit">%1$s</span>', 'townhub-add-ons' ), $featured_limit, count((array)$featured) );
                    }else{
                        $featued_limit_text = sprintf(__( '<span class="listing-count zero">0</span>/<span class="listing-limit">%s</span>', 'townhub-add-ons' ), $featured_limit );
                    }
                    ?>
                    <div class="package-listings-wrap">
                        <div class="package-listings-col w20">
                            <h6><?php _e( 'Available Listings', 'townhub-add-ons' ); ?></h6>
                            <div class="listings-count"><?php echo $listings_limit_text; ?></div>
                        </div>
                        <div class="package-listings-col w40">
                            <h6><?php _e( 'Associated Listings', 'townhub-add-ons' ); ?></h6>
                            <div class="listings-list">
                                <?php 
                                if(!empty($listings) && is_array($listings)){
                                    foreach ($listings as  $lid) {
                                        $lpost = get_post($lid);
                                        if(null != $lpost){
                                            echo $lpost->post_title.'<br>';
                                        }else{
                                            echo sprintf(__( 'Deleted listing. Old ID: %s<br>', 'townhub-add-ons' ), $lid );
                                        }
                                        
                                    }
                                }else{
                                    _e( 'There is no listing associating with this package.', 'townhub-add-ons' );
                                }
                                ?>
                            </div>
                        </div>

                        <div class="package-listings-col w20">
                            <h6><?php _e( 'Featured Listings', 'townhub-add-ons' ); ?></h6>
                            <div class="listings-count"><?php echo $featued_limit_text; ?></div>
                        </div>
                        <div class="package-listings-col w40">
                            <h6><?php _e( 'Associated Listings', 'townhub-add-ons' ); ?></h6>
                            <div class="listings-list">
                                <?php 
                                if(!empty($featured) && is_array($featured)){
                                    foreach ($featured as  $lid) {
                                        $lpost = get_post($lid);
                                        if(null != $lpost){
                                            echo $lpost->post_title.'<br>';
                                        }else{
                                            echo sprintf(__( 'Deleted listing. Old ID: %s<br>', 'townhub-add-ons' ), $lid );
                                        }
                                        
                                    }
                                }else{
                                    _e( 'There is no featured listing.', 'townhub-add-ons' );
                                }
                                ?>
                            </div>
                        </div>

                    </div>

                    <?php 
                    $transactions = get_post_meta(get_the_ID(),  ESB_META_PREFIX.'transactions', true );
                    // var_dump($transactions);
                    ?>
                    
                </td>
            </tr>
        
        <?php endwhile; 
            
        else:
        ?> 
        <tr id="lorder-no" class="dashboard-list">
            <td colspan="7">
                <?php _e( 'You have no package/membership yet!', 'townhub-add-ons' ); ?>
            </td>
        </tr>
        <?php
        endif; ?>

        </tbody>
    </table>
    
    <?php 
    echo townhub_addons_custom_pagination($posts_query->max_num_pages,$range = 2, $posts_query);
        
    /* Restore original Post Data 
     * NB: Because we are using new WP_Query we aren't stomping on the 
     * original $wp_query and it does not need to be reset with 
     * wp_reset_query(). We just need to set the post data back up with
     * wp_reset_postdata().
     */
    wp_reset_postdata(); ?>
    

</div>

    