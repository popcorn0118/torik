<?php
/* add_ons_php */

$current_user = wp_get_current_user();

$notifications = Esb_Class_Dashboard::get_notifications( $current_user->ID, '', 10 );
// $notifications = json_decode(json_encode($notifications),true);

?>
<div class="dashboard-content-wrapper dashboard-content-feed">
    <div class="dashboard-content-inner">
                
        <div class="dashboard-feed-row row">
            <div class="dashboard-feed-col dashboard-feed-col-left col-md-8">
                
                <?php 
                $substatus = Esb_Class_Membership::subscription_status(); 
                if( Esb_Class_Membership::is_author() == false && !empty($substatus) ): ?>
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

                <div class="dashboard-title fl-wrap">
                    <h3><?php _e('Your Feed', 'townhub-add-ons');?></h3>
                </div>

                <!-- dashboard-list-box-->
                <div class="dashboard-list-box  fl-wrap">
                <?php
                if (!empty($notifications)):
                    $npages = array_pop($notifications);
                    foreach ($notifications as $key => $noti) {
                        ?>
                        <div class="notification-list-item">
                            <span class="notification-list-remove delete-notification" data-notification="<?php echo $noti->id; ?>"><i class="fal fa-times"></i></span>
                            <div class="notification-list-inner">
                                <div class="notification-list-time"><i class="fal fa-calendar-week"></i><?php echo $noti->time; ?></div>
                                <div class="notification-list-text">
                                    <div class="notification-msg"><?php echo $noti->message; ?></div>
                                </div>
                            </div>
                        </div>
                        <!-- dashboard-list end-->
                        <?php
                    }

                    townhub_addons_comments_pagination( $npages );

                else: ?>
                    <div class="notification-list-item">
                        <div class="notification-list-inner">
                            <div class="notification-list-text">
                                <div class="notification-msg"><?php _e('You have no activity.', 'townhub-add-ons');?></div>
                            </div>
                        </div>
                    </div>
                    <!-- dashboard-list end-->
                <?php endif;?>


                </div>
                <!-- dashboard-list-box end-->

            </div><!-- dashboard-feed-col-left end -->
            <div class="dashboard-feed-col dashboard-feed-col-right col-md-4">
            <?php 
            $following = Esb_Class_Dashboard::filter_users( (array)get_user_meta( $current_user->ID, ESB_META_PREFIX.'following', true ) ) ;
            $follower = Esb_Class_Dashboard::filter_users( (array)get_user_meta( $current_user->ID, ESB_META_PREFIX.'follower', true ) ) ;
            //if( !empty($following) || !empty($follower) ):
            ?>
                <div class=" fl-wrap   tabs-act block_box dashboard-tabs tabs-wrapper dashboard-follow-tabs">
                    <div class="filter-sidebar-header fl-wrap" id="filters-column">
                        <ul class="tabs-menu fl-wrap no-list-style">
                            <li class="current"><a href="#follow-you"><i class="fal fa-rss"></i><?php echo sprintf( __( 'Following <span>%d</span>', 'townhub-add-ons' ), count($following) ); ?></a></li>
                            <li><a href="#follow-me"><i class="fal fa-users"></i><?php echo sprintf( __( 'Followers <span>%d</span>', 'townhub-add-ons' ), count($follower) ); ?></a></li>
                        </ul>
                    </div>
                    <div class="scrl-content filter-sidebar fs-viscon">
                        <!--tabs -->
                        <div class="tabs-container fl-wrap">
                            <!--tab -->
                            <div class="tab">
                                <div id="follow-you" class="tab-content  first-tab ">
                                    <div class="follow-user-list fl-wrap">
                                        <ul class="no-list-style dis-flex flw-wrap">
                                            <?php 
                                            foreach ($following as $fling) {
                                                ?>
                                                <li>
                                                    <a href="<?php echo get_author_posts_url( $fling ); ?>">
                                                        <?php 
                                                            echo get_avatar( get_the_author_meta( 'user_email', $fling ) ,'80','https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=80', get_the_author_meta( 'display_name', $fling ) );
                                                        ?>
                                                        <span><?php echo get_the_author_meta( 'display_name', $fling ); ?></span>
                                                    </a>
                                                </li>
                                            <?php
                                            } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!--tab end-->
                            <!--tab -->
                            <div class="tab">
                                <div id="follow-me" class="tab-content">
                                    <div class="follow-user-list fl-wrap">
                                        <ul class="no-list-style dis-flex flw-wrap">
                                            <?php 
                                            foreach ($follower as $fling) {
                                                ?>
                                                <li>
                                                    <a href="<?php echo get_author_posts_url( $fling ); ?>">
                                                        <?php 
                                                            echo get_avatar( get_the_author_meta( 'user_email', $fling ) ,'80','https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=80', get_the_author_meta( 'display_name', $fling ) );
                                                        ?>
                                                        <span><?php echo get_the_author_meta( 'display_name', $fling ); ?></span>
                                                    </a>
                                                </li>
                                            <?php
                                            } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!--tab end-->
                        </div>
                        <!--tabs end-->
                    </div>
                </div>
            <?php
            //endif; ?>
                <?php 
                if(is_active_sidebar('dashboard-feed')){
                    dynamic_sidebar('dashboard-feed');
                } ?>
                

            </div><!-- dashboard-feed-col-right end -->
        </div><!-- dashboard-feed-row end -->



    </div>
</div>
