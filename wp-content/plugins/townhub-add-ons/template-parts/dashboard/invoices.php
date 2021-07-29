<?php
/* add_ons_php */
// don't show on customer dashboard


townhub_addons_reset_user_notification_type('new_invoice');

$current_user = wp_get_current_user();    

if(is_front_page()) {
    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
} else {
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
}                 

$args = array(
    'post_type'     =>  'cthinvoice', 
    'author'        =>  $current_user->ID, 
    'orderby'       =>  'date',
    'order'         =>  'DESC',
    'paged'         => $paged,
    // 'posts_per_page' => -1 // no limit

    // double test for user invoice
    'meta_key'      => ESB_META_PREFIX.'user_id',
    'meta_value'    => $current_user->ID,

    'post_status'   => array( 'publish', 'pending', 'draft', 'future' ),
);

// The Query
$posts_query = new WP_Query( $args );

?>
<div class="dashboard-content-wrapper dashboard-content-invoices">
    <div class="dashboard-content-inner">
        
        <div class="dashboard-title   fl-wrap">
            <h3><?php _e( 'Invoices', 'townhub-add-ons' ); ?></h3>
        </div>
        
        <div class="dashboard-invoices-grid">
            <?php
            if($posts_query->have_posts()) : ?>
            <div class="dashboard-card card-has-content dis-block dashboard-table-wrap">
                <table class="cth-table cth-table-no-footer table-ads">
                    <thead>
                        <tr>
                            <th><?php _e( 'Title', 'townhub-add-ons' ); ?></th>
                            <th><?php _e( 'Plan', 'townhub-add-ons' ); ?></th>
                            <th><?php _e( 'Date', 'townhub-add-ons' ); ?></th>
                            <th><?php _e( 'End', 'townhub-add-ons' ); ?></th>
                            <th><?php _e( 'Amount', 'townhub-add-ons' ); ?></th>
                            <th><?php _e( 'Action', 'townhub-add-ons' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $vurl = add_query_arg( '_wpnonce', wp_create_nonce( 'cth_view_invoice' ), home_url() );
                    while($posts_query->have_posts()) : $posts_query->the_post(); 
                        $vurl = add_query_arg(array(
                            'thview'    => 'invoice',
                            'invid'     => get_the_ID(),
                        ), $vurl);
                    ?>
                    <tr id="lad-<?php the_ID(); ?>" <?php post_class('dashboard-list'); ?>>
                        <td><?php the_title( '<h4 class="entry-title">', '</h4>' );;?></td>
                        <td><?php echo get_post_meta( get_the_ID(), ESB_META_PREFIX.'plan_title', true); ?></td>
                        
                        <td><?php echo Esb_Class_Date::i18n( get_post_meta( get_the_ID(), ESB_META_PREFIX.'from_date', true) ); ?></td>
                        <td><?php echo Esb_Class_Date::i18n( get_post_meta( get_the_ID(), ESB_META_PREFIX.'end_date', true) ); ?></td>
                        <td><?php echo townhub_addons_get_price_formated( get_post_meta( get_the_ID(), ESB_META_PREFIX.'price_total', true ) );?></td>
                        <td><a href="<?php echo esc_url( $vurl ); ?>" target="_blank" class="btn color-bg view-invoice-btn"><?php _e( 'View details', 'townhub-add-ons' ); ?></a></td>
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
                        <?php _e( '<p>You have no invoice yet!</p>', 'townhub-add-ons' ); ?>
                    </div>
                <?php
                endif;?> 


            </div>
            

        </div>
    </div>
</div>
