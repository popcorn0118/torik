<?php
/* add_ons_php */

$current_user = wp_get_current_user();  

$views_count = 0;
// $bookmarks_count = 0;
$comments_count = 0;
$bookings_count = 0;
$post_args = array(
    'post_type'         => 'listing',
    'author'            => $current_user->ID,
    'posts_per_page'    => -1,
    'post_status'       => array('publish','pending','future','private'), // 'publish',
    'fields'            => 'ids'
);
$posts_get = get_posts( $post_args );
foreach ( $posts_get as $pid ) {
    if( townhub_addons_get_option('db_hide_lviews') != 'yes' ) $views_count += Esb_Class_LStats::get_stats( $pid );
    // $bookmarks_count += Esb_Class_Listing_CPT::get_bookmark_count( $pid );
    if( townhub_addons_get_option('db_hide_lreviews') != 'yes' ) $comments_count += get_comments_number( $pid );
    if( townhub_addons_get_option('db_hide_lbookings') != 'yes' ) $bookings_count += count( get_posts(
        array(
            'post_type'         => 'lbooking',
            'posts_per_page'    => -1,
            'post_status'       => 'publish',
            'fields'            => 'ids',
            // 'meta_key'          => ESB_META_PREFIX .'listing_id',
            // 'meta_value_num'    => $pid,

            'meta_query' => array(
                // 'relation' => 'OR',
                array(
                    'key'     => ESB_META_PREFIX .'listing_id',
                    'value'   => $pid,
                    'type'      => 'NUMERIC',
                ),
                array(
                    'key' => ESB_META_PREFIX .'lb_status',
                    'value'   => array( 'canceled', 'refunded', 'failed' ),
                    'compare' => 'NOT IN',
                ),
            ),

        )
    ) );

    // $reviews = townhub_addons_get_average_ratings($listing_ID);
    // if($reviews) $reviews_count += $reviews['count'];
}


$notifications = Esb_Class_Dashboard::get_notifications( $current_user->ID );
// $notifications = json_decode(json_encode($notifications),true);

?>
<div class="dashboard-content-wrapper dashboard-content-dashboard">
    <div class="dashboard-content-inner">
        <?php 
        $substatus = Esb_Class_Membership::subscription_status(); 
        if( !empty($substatus) ): ?>
        <div class="dashboard-list-box fl-wrap dashboard-substatus">
            <div class="notification-list-item">
                <div class="notification-list-inner">
                    <div class="notification-list-text">
                        <div class="notification-msg"><?php echo $substatus; ?></div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php 
        if( townhub_addons_get_option('show_stats', 'yes') == 'yes' ): 
            $countCol = array(
                'lviews'        => '',
                'lreviews'      => '',
                'lbookings'     => '',
            );
            if( townhub_addons_get_option('db_hide_lviews') == 'yes' ) unset( $countCol['lviews'] );
            if( townhub_addons_get_option('db_hide_lreviews') == 'yes' ) unset( $countCol['lreviews'] );
            if( townhub_addons_get_option('db_hide_lbookings') == 'yes' ) unset( $countCol['lbookings'] );

            $countCol = count( $countCol );
        ?>
        <div class="dashboard-title fl-wrap">
            <h3><?php _e( 'Your Statistics', 'townhub-add-ons' ); ?></h3>
        </div>
        
        <!-- list-single-facts -->
        <div class="list-single-facts dashboard-facts fl-wrap">
            <div class="row">
                <?php if( townhub_addons_get_option('db_hide_lviews') != 'yes' ): ?>
                <div class="lstats-lviews col-md-<?php echo 12/$countCol;?>">
                    <!-- inline-facts -->
                    <div class="inline-facts-wrap gradient-bg dashboard-fact">
                        <div class="inline-facts">
                            <i class="fal fa-chart-bar"></i>
                            <div class="milestone-counter">
                                <div class="stats animaper">
                                    <div class="num" data-content="0" data-num="<?php echo $views_count; ?>"><?php echo $views_count; ?></div>
                                </div>
                            </div>
                            <h6><?php _e( 'Listing Views', 'townhub-add-ons' ); ?></h6>
                        </div>
                        <div class="stat-wave">
                            <svg viewbox="0 0 100 25">
                                <path fill="#fff" d="M0 30 V12 Q30 17 55 2 T100 11 V30z" />
                            </svg>
                        </div>
                    </div>
                    <!-- inline-facts end -->
                </div>
                <?php endif; ?>
                <?php if( townhub_addons_get_option('db_hide_lreviews') != 'yes' ): ?>
                <div class="lstats-lreviews col-md-<?php echo 12/$countCol;?>">
                    <!-- inline-facts  -->
                    <div class="inline-facts-wrap gradient-bg  dashboard-fact">
                        <div class="inline-facts">
                            <i class="fal fa-comments-alt"></i>
                            <div class="milestone-counter">
                                <div class="stats animaper">
                                    <div class="num" data-content="0" data-num="<?php echo $comments_count; ?>"><?php echo $comments_count; ?></div>
                                </div>
                            </div>
                            <h6><?php _e( 'Total Reviews', 'townhub-add-ons' ); ?></h6>
                        </div>
                        <div class="stat-wave">
                            <svg viewbox="0 0 100 25">
                                <path fill="#fff" d="M0 30 V12 Q30 17 55 12 T100 11 V30z" />
                            </svg>
                        </div>
                    </div>
                    <!-- inline-facts end -->
                </div>
                <?php endif; ?>
                <?php if( townhub_addons_get_option('db_hide_lbookings') != 'yes' ): ?>
                <div class="lstats-lbookings col-md-<?php echo 12/$countCol;?>">
                    <!-- inline-facts  -->
                    <div class="inline-facts-wrap gradient-bg  dashboard-fact">
                        <div class="inline-facts">
                            <i class="fal fa-envelope-open-dollar"></i>
                            <div class="milestone-counter">
                                <div class="stats animaper">
                                    <div class="num" data-content="0" data-num="<?php echo $bookings_count; ?>"><?php echo $bookings_count; ?></div>
                                </div>
                            </div>
                            <h6><?php _e( 'Bookings ', 'townhub-add-ons' ); ?></h6>
                        </div>
                        <div class="stat-wave">
                            <svg viewbox="0 0 100 25">
                                <path fill="#fff" d="M0 30 V12 Q30 12 55 5 T100 11 V30z" />
                            </svg>
                        </div>
                    </div>
                    <!-- inline-facts end -->
                </div>
                <?php endif; ?>
            </div>
        </div>
        <!-- list-single-facts end -->
        <?php endif; ?>
        <?php 
        if( townhub_addons_get_option('show_chart', 'yes') == 'yes' ): ?>
        <div class="list-single-main-item fl-wrap block_box">
            <!-- chart-wra-->
            <div class="chart-wrap fl-wrap">
                <div id="dashboard-chart"></div>
            </div>
            <!--chart-wrap end-->
        </div>
        <?php endif; ?>
        <?php do_action( 'cth_front_dashboard_dashboard'); ?>
        <div class="dashboard-title dt-inbox fl-wrap">
            <h3><?php _e( 'Recent Activities', 'townhub-add-ons' ); ?></h3>
        </div>
        <!-- dashboard-list-box-->
        <div class="dashboard-list-box  fl-wrap">
            <?php 
            if( !empty($notifications) ):
            $npages = array_pop($notifications);
            foreach ($notifications as $key => $noti) {
            ?>  
            <div class="notification-list-item">
                <span class="notification-list-remove delete-notification" data-notification="<?php echo $noti->id ; ?>"><i class="fal fa-times"></i></span>
                <div class="notification-list-inner">
                    <div class="notification-list-time"><i class="fal fa-calendar-week"></i><?php echo $noti->time ; ?></div>
                    <div class="notification-list-text">
                        <div class="notification-msg"><?php echo  $noti->message ; ?></div>
                    </div>
                </div>
            </div>
            <!-- dashboard-list end-->    
            <?php 
            } ?>

            <?php else: ?> 
            <div class="notification-list-item">
                <div class="notification-list-inner">
                    <div class="notification-list-text">
                        <div class="notification-msg"><?php _e( 'You have no activity.', 'townhub-add-ons' ); ?></div>
                    </div>
                </div>
            </div>
            <!-- dashboard-list end-->  
            <?php endif; ?> 

            
        </div>
        <!-- dashboard-list-box end-->

    </div>
</div>
