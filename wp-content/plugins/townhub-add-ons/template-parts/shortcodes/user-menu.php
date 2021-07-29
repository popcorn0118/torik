<?php
/* add_ons_php */
$umenu_earnings = townhub_addons_get_option('umenu_earnings');
?>
<div class="header-user-menu user-menu-<?php echo $style;?>">
    <div class="header-user-name user-name-<?php echo $style;?>">
        <span class="au-avatar"><?php 
            echo get_avatar($current_user->user_email,'80','https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=80', $current_user->display_name );
        ?></span>
        <?php if($style != 'two'): ?>
        <span class="au-name"><?php echo sprintf(__( 'Hello , %s', 'townhub-add-ons' ), $current_user->display_name); ?></span>
        <?php endif; ?>
    </div>
    <ul class="head-user-menu">
        <?php if($style == 'two'): ?>
        <li class="user-menu-details"><div class="au-name-li">
                <h2 class="au-name"><?php echo sprintf(__( 'Hello , %s', 'townhub-add-ons' ), $current_user->display_name); ?></h2>
                <div class="au-role"><?php echo townhub_addons_get_user_role_name() ; ?></div>
                <?php if( $umenu_earnings != 'yes' && Esb_Class_Membership::is_author() ): ?>
                <div class="au-earning">
                    <?php if( townhub_addons_get_option('db_hide_withdrawals') != 'yes' ): ?>
                    <a href="<?php echo Esb_Class_Dashboard::screen_url('withdrawals');?>">
                    <?php endif; ?>
                        <?php echo sprintf(__( 'Earning: %s', 'townhub-add-ons' ), townhub_addons_get_price_formated( Esb_Class_Earning::getBalance($current_user->ID) ) ) ;?>
                    <?php if( townhub_addons_get_option('db_hide_withdrawals') != 'yes' ): ?>      
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </li>
        <?php elseif( $umenu_earnings != 'yes' && Esb_Class_Membership::is_author() ): ?>
        <li class="user-menu-details">
            <div class="au-earning">
                <?php if( townhub_addons_get_option('db_hide_withdrawals') != 'yes' ): ?>
                <a href="<?php echo Esb_Class_Dashboard::screen_url('withdrawals');?>">
                <?php endif; ?>
                    <?php echo sprintf(__( 'Earning: %s', 'townhub-add-ons' ), townhub_addons_get_price_formated( Esb_Class_Earning::getBalance($current_user->ID) ) ) ;?>
                <?php if( townhub_addons_get_option('db_hide_withdrawals') != 'yes' ): ?>      
                </a>
                <?php endif; ?>
            </div>
        </li>
        <?php endif; ?>
        <li class="user-menu-dashboard"><a href="<?php echo Esb_Class_Dashboard::screen_url();?>"><?php _e( 'Dashboard', 'townhub-add-ons' );?></a></li>
        <li class="user-menu-profile"><a href="<?php echo get_author_posts_url( $current_user->ID );?>"><?php _e( 'View profile', 'townhub-add-ons' );?></a></li>


    <?php if( townhub_addons_current_user_can('view_listings_dashboard') ): ?>
        <li class="user-menu-addlisting"><a href="<?php echo townhub_addons_add_listing_url();?>"><?php _e( 'Add Listing', 'townhub-add-ons' );?></a></li>
        <?php if (townhub_addons_get_option('db_hide_bookings') != 'yes'): ?>
            <li class="user-menu-bookings"><a href="<?php echo Esb_Class_Dashboard::screen_url('bookings');?>"><?php _e( 'Bookings', 'townhub-add-ons' );?></a></li>
        <?php endif; ?>
        <li class="user-menu-reviews"><a href="<?php echo Esb_Class_Dashboard::screen_url('reviews');?>"><?php _e( 'Reviews', 'townhub-add-ons' );?></a></li>
    <?php else : ?>
        <?php if (townhub_addons_get_option('db_hide_bookings') != 'yes'): ?>
            <li class="user-menu-bookings"><a href="<?php echo Esb_Class_Dashboard::screen_url('bookings');?>"><?php _e( 'Bookings', 'townhub-add-ons' );?></a></li>
        <?php endif; ?>
        <?php if( townhub_addons_get_option('admin_chat') == 'yes' ): ?>
            <li class="user-menu-messages"><a href="<?php echo Esb_Class_Dashboard::screen_url('chats');?>"><?php _e( 'Chats', 'townhub-add-ons' );?></a></li>
        <?php endif; ?>
        <?php if( townhub_addons_get_option('db_hide_messages') != 'yes' ): ?>
            <li class="user-menu-messages"><a href="<?php echo Esb_Class_Dashboard::screen_url('messages');?>"><?php _e( 'Messages', 'townhub-add-ons' );?></a></li>
        <?php endif; ?>
    <?php endif; ?>
        <li class="user-menu-logout"><a href="<?php echo wp_logout_url( townhub_addons_get_current_url() ); ?>"><?php _e('Log Out','townhub-add-ons');?></a></li>



    </ul>

</div>
