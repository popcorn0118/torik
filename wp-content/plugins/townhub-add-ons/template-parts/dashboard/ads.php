<?php
/* add_ons_php */
// don't show on customer dashboard


townhub_addons_reset_user_notification_type('ad_completed');

$current_user = wp_get_current_user();    

if(is_front_page()) {
    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
} else {
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
}                 

$args = array(
    'post_type'         =>  'cthads', 
    'author'            =>  $current_user->ID, 
    'orderby'           =>  'date',
    'order'             =>  'DESC',
    'paged'             => $paged,

    'post_status'       => array( 'publish', 'pending', 'draft', 'future' ),

    'meta_query' => array(
        // 'relation' => 'AND',
        array(
            'key'     => ESB_META_PREFIX.'user_id',
            'value'   => $current_user->ID,
        ),
        // array(
        //     'key' => ESB_META_PREFIX.'order_type',
        //     'value'   => 'listing_ad',
        // ),
    ),

);

// The Query
$posts_query = new WP_Query( $args );

?>
<div class="dashboard-content-wrapper dashboard-content-adcampaigns">
    <div class="dashboard-content-inner">
        
        <div class="dashboard-title   fl-wrap">
            <h3><?php _e( 'AD Campaigns', 'townhub-add-ons' ); ?></h3>
        </div>
        
        <div class="dashboard-adcampaigns-grid">
            <?php
            if($posts_query->have_posts()) : ?>
            <div class="dashboard-card card-has-content dis-block dashboard-table-wrap">
                <div class="new-adcampaign">
                    <a href="#" class="btn color-bg new-campaign-btn open-new-campaign-modal"><?php _e( 'Add new campaign', 'townhub-add-ons' ); ?><i class="fal fa-cart-plus"></i></a>
                </div>
                <table class="cth-table cth-table-no-footer table-ads">
                    <thead>
                        <tr>
                            <th><?php _e( 'ID', 'townhub-add-ons' ); ?></th>
                            <th><?php _e( 'AD Package', 'townhub-add-ons' ); ?></th>

                            <th><?php _e( 'Listing', 'townhub-add-ons' ); ?></th>
                            <th><?php _e( 'End', 'townhub-add-ons' ); ?></th>
                            <th><?php _e( 'Status', 'townhub-add-ons' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    while($posts_query->have_posts()) : $posts_query->the_post(); 
                        $status = get_post_meta( get_the_ID(), ESB_META_PREFIX.'status', true );
                        // check expired
                        $ad_end = get_post_meta( get_the_ID(), ESB_META_PREFIX.'end_date', true );
                        if( $ad_end != 'NEVER' && Esb_Class_Date::compare($ad_end, current_time('Y-m-d') , '<') ){
                            $status = 'expired';
                        }
                        $status_text = Esb_Class_ADs::status_text($status);
                        $time_status = townhub_addons_get_package_time_status(get_the_ID());
                        $plan_id = get_post_meta( get_the_ID(), ESB_META_PREFIX.'plan_id', true);
                    ?>
                    <tr id="lad-<?php the_ID(); ?>" <?php post_class('dashboard-list'); ?>>
                        <td><?php the_ID();?></td>
                        <td><?php 
                        $ad_package = get_term( $plan_id, 'cthads_package' );
                        // check if the ad package is deleted
                        if ( ! empty( $ad_package ) && ! is_wp_error( $ad_package ) ){
                            echo $ad_package->name;
                        }
                        ?></td>
                        
                        <td><?php echo get_the_title( get_post_meta( get_the_ID(), ESB_META_PREFIX.'listing_id', true) ); ?></td>
                        <td><?php echo Esb_Class_Date::i18n($ad_end); ?></td>
                        <td><span class="ad-status ad-status-<?php echo esc_attr( $status ); ?>"><?php echo $status_text; ?></span></td>
                    </tr>
                    
                    <?php 
                    endwhile; 
                    ?>
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
                    wp_reset_postdata();

                //endif; ?>
                <?php
                else: ?>
                <div class="dashboard-card card-has-no">
                    <div class="dashboard-card-content">
                        <?php _e( '<p>You have no ad campaign yet!</p>', 'townhub-add-ons' ); ?>
                    </div>
                    <div class="new-adcampaign">
                        <a href="#" class="btn color-bg new-campaign-btn open-new-campaign-modal"><?php _e( 'Add new campaign', 'townhub-add-ons' ); ?></a>
                    </div>
                <?php
                endif;?> 


            </div>
            

        </div>
    </div>
</div>
