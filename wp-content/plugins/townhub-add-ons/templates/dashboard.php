<?php
/* add_ons_php */
$dashboard = get_query_var('dashboard');

townhub_addons_get_template_part('template-parts/dashboard/headsec');
?>
<!--  section  -->
<section class="gray-bg main-dashboard-sec" id="sec1">
    <div class="container main-dashboard-container">
        <div class="row main-dashboard-row">
            <!--  dashboard-menu-->
            <div class="col-md-3 dashboard-sidebar-col">
                <?php townhub_addons_get_template_part('template-parts/dashboard/sidebar');?>

            </div>
            <!-- dashboard-menu  end-->
            <!-- dashboard content-->
            <div class="col-md-9 dashboard-main-col">
                <?php
                do_action( 'cth_listing_dashboard_content_before', $dashboard );
                $skipDashboard = apply_filters( 'cth_listing_dashboard_skip_content', $dashboard );
                if( $skipDashboard !== false ){
                    switch ($dashboard) {
                        case 'changepass':
                            townhub_addons_get_template_part('template-parts/dashboard/changepass');
                            break;
                        case 'packages':
                            if (townhub_addons_get_option('db_hide_packages') != 'yes') {
                                townhub_addons_get_template_part('template-parts/dashboard/packages');
                            }

                            break;
                        case 'ads':
                            if (townhub_addons_get_option('db_hide_ads') != 'yes') {
                                townhub_addons_get_template_part('template-parts/dashboard/ads');
                            }

                            break;
                        case 'invoices':
                            if (townhub_addons_get_option('db_hide_invoices') != 'yes') {
                                townhub_addons_get_template_part('template-parts/dashboard/invoices');
                            }

                            break;
                        case 'listings':
                            townhub_addons_get_template_part('template-parts/dashboard/listings');
                            break;

                            
                        case 'products':
                            if (townhub_addons_get_option('db_hide_products') != 'yes') {
                                $product_id = isset($_GET['edit']) && !empty( $_GET['edit'] ) ? abs($_GET['edit']) : 0; 
                                if( !empty($product_id) && get_post_type( $product_id) == 'product' && get_post_field( 'post_author', $product_id, 'display' ) == get_current_user_id()  ){
                                    townhub_addons_get_template_part('template-parts/dashboard/product','edit', array('product_id'=>$product_id));
                                }else{
                                    townhub_addons_get_template_part('template-parts/dashboard/products');
                                }
                            }

                            break;
                        case 'reviews':
                            if (townhub_addons_get_option('db_hide_reviews') != 'yes') {
                                townhub_addons_get_template_part('template-parts/dashboard/reviews');
                            }

                            break;
                        case 'chats':
                            if (townhub_addons_get_option('admin_chat') == 'yes') {
                                townhub_addons_get_template_part('template-parts/dashboard/chats');
                            }

                            break;
                        case 'messages':
                            if (townhub_addons_get_option('db_hide_messages') != 'yes') {
                                townhub_addons_get_template_part('template-parts/dashboard/messages');
                            }

                            break;
                        case 'bookings':
                            if (townhub_addons_get_option('db_hide_bookings') != 'yes') {
                                townhub_addons_get_template_part('template-parts/dashboard/bookings');
                            }

                            break;
                        case 'inquiries':
                            if (townhub_addons_get_option('db_show_inquiries') == 'yes') {
                                townhub_addons_get_template_part('template-parts/dashboard/inquiries');
                            }

                            break;
                        case 'wooorders':
                            if (townhub_addons_get_option('db_show_woo_orders') == 'yes') {
                                if( isset($_GET['view_order']) && !empty( $_GET['view_order'] ) ){
                                    townhub_addons_get_template_part('template-parts/dashboard/wooorder');
                                }else{
                                    townhub_addons_get_template_part('template-parts/dashboard/wooorders');
                                }
                                
                            }

                            break;
                        case 'bookmarks':
                            if (townhub_addons_get_option('db_hide_bookmarks') != 'yes') {
                                townhub_addons_get_template_part('template-parts/dashboard/bookmarks');
                            }

                            break;
                        case 'withdrawals':
                            if (townhub_addons_get_option('db_hide_withdrawals') != 'yes') {
                                townhub_addons_get_template_part('template-parts/dashboard/withdrawal');
                            }

                            break;
                        case 'profile':
                            townhub_addons_get_template_part('template-parts/dashboard/profile');
                            break;
                        case 'feed':
                            if( Esb_Class_Membership::is_author() ) 
                                townhub_addons_get_template_part('template-parts/dashboard/feed');
                            break;
                        case 'ical':
                            if( Esb_Class_Membership::is_author() && townhub_addons_get_option('db_hide_ical') != 'yes' ) 
                                townhub_addons_get_template_part('template-parts/dashboard/ical');
                            break;
                        default:
                            if( Esb_Class_Membership::is_author() == false )
                                townhub_addons_get_template_part('template-parts/dashboard/feed');
                            else
                                townhub_addons_get_template_part('template-parts/dashboard/dashboard');
                            break;

                    }
                }
                do_action( 'cth_listing_dashboard_content_switch', $dashboard );
                ?>
            </div>
            <!-- dashboard content end-->
        </div><!-- main-dashboard-row end -->
    </div><!-- main-dashboard-container end -->
</section>
<!--  section  end-->
<div class="limit-box fl-wrap"></div>
<?php
// add_filter( 'cth_listing_dashboard_skip_content', function($dashboard){
//     if( $dashboard == 'YOUR_DASHBOARD_SLUG' ) 
//         return false;
//     return $dashboard;
// } );
// add_action( 'cth_listing_dashboard_content_switch', function($dashboard){
//     if( $dashboard == 'YOUR_DASHBOARD_SLUG' ) {
//         echo get_the_content(null, false, 1000); // 1000 is your page id
//     }
// } );

