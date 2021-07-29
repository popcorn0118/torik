<?php
/* add_ons_php */

get_header(  );

global $laumember;
$current_user_id = get_current_user_id();
$current_user = wp_get_current_user();

$au_phone = get_user_meta( $laumember->ID, '_cth_phone', true );
$au_address = get_user_meta( $laumember->ID, '_cth_address', true );
$au_email = get_user_meta( $laumember->ID, '_cth_email', true );
$au_company = get_user_meta( $laumember->ID,  ESB_META_PREFIX.'company', true );

$cover_bg = get_user_meta($laumember->ID,  ESB_META_PREFIX.'cover_bg', true ); 
$cover_id = '';
if(is_array($cover_bg) && count($cover_bg)){
    $cover_id = reset($cover_bg);
}

$following = Esb_Class_Dashboard::filter_users( (array)get_user_meta( $laumember->ID, ESB_META_PREFIX.'following', true ) ) ;
$follower = Esb_Class_Dashboard::filter_users( (array)get_user_meta( $laumember->ID, ESB_META_PREFIX.'follower', true ) ) ;
// var_dump($following);
// var_dump($follower);

$loggedName = '';
$loggedEmail = '';
$loggedPhone = '';
if( is_user_logged_in() ){
    $loggedName = $current_user->display_name;
    $loggedEmail = get_user_meta($current_user->ID,  ESB_META_PREFIX.'email', true );
    if( empty($loggedEmail) ) $loggedEmail = $current_user->user_email;
    $loggedPhone = get_user_meta($current_user->ID,  ESB_META_PREFIX.'phone', true );
}

$sb_w = 4;
$has_nosidebar = townhub_addons_get_option('author_hide_about') == 'yes' && townhub_addons_get_option('author_hide_contacts') == 'yes' && townhub_addons_get_option('author_hide_form') == 'yes' && !is_active_sidebar('author-sidebar');
if( $has_nosidebar ){
    $sb_w = 0;
}
?>
<section class="gray-bg no-top-padding-sec pad-bot-80" id="main-sec">
    <div class="container">
        <?php townhub_get_template_part( 'template-parts/breadcrumbs' ); ?>
            
        <div class="post-container fl-wrap">
            <div class="row">
                
                <div class="col-md-<?php echo esc_attr(12 - $sb_w);?> author-single-content">

                    <!-- list-single-main-item --> 
                    <div class="user-profile-header fl-wrap clearfix">
                        <div class="user-profile-header_media fl-wrap">
                            <div class="bg"  data-bg="<?php echo esc_url( townhub_addons_get_attachment_thumb_link( $cover_id, 'full' )  );?>"></div>
                            <div class="user-profile-header_media_title">
                                <h3><?php echo sprintf(__( 'Author : %s', 'townhub-add-ons' ), $laumember->display_name); ?></h3>
                                <?php if($au_company != ''){ ?><h4><?php echo $au_company; ?></h4><?php } ?>
                            </div>
                            <div class="user-profile-header_stats">
                                <ul class="no-list-style flex-items-center">
                                    <li><?php echo sprintf(__( '<span>%d</span> Places', 'townhub-add-ons' ), count_user_posts( $laumember->ID , "listing" , true ) ); ?></li>
                                    <li><?php echo sprintf(__( '<span>%d</span> Followers', 'townhub-add-ons' ), count($follower) ); ?></li>
                                    <li><?php echo sprintf(__( '<span>%d</span> Following', 'townhub-add-ons' ), count($following) ); ?></li>
                                </ul>
                            </div>
                            <?php 
                            if($current_user_id ): 
                                if( $laumember->ID !== $current_user_id ): ?>
                                    <?php if( !empty($follower) && in_array($current_user_id, $follower) ): ?>
                                    <div class="follow-btn color2-bg unfollow-author" data-id="<?php echo esc_attr( $laumember->ID ); ?>"><?php _e( 'Following', 'townhub-add-ons' ); ?></div>
                                    <?php else: ?>
                                    <div class="follow-btn color2-bg do-follow-author" data-id="<?php echo esc_attr( $laumember->ID ); ?>"><?php _e( 'Follow', 'townhub-add-ons' ); ?><i class="fal fa-user-plus"></i></div>
                                    <?php endif; ?>
                            <?php 
                                endif; // endif current user is author
                            else: 
                                $logBtnAttrs = townhub_addons_get_login_button_attrs( 'follow', 'current' );
                            ?>
                                <a href="<?php echo esc_url( $logBtnAttrs['url'] );?>" class="follow-btn color2-bg <?php echo esc_attr( $logBtnAttrs['class'] );?>" data-message="<?php esc_attr_e( 'Logging in first to follow author.', 'townhub-add-ons' ); ?>"><?php _e( 'Follow <i class="fal fa-user-plus"></i>', 'townhub-add-ons' ); ?></a>
                            <?php 
                            endif;?>
                        </div>
                        <div class="user-profile-header_content fl-wrap">
                            <div class="user-profile-header-avatar">
                                <?php
                                    echo get_avatar($laumember->user_email, '150', 'https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=150', $laumember->display_name );
                                ?>
                                <?php if( $laumember->ID === $current_user_id ): ?>
                                    <a href="<?php echo esc_url( Esb_Class_Dashboard::screen_url('profile') ); ?>" class="color-bg edit-prof_btn" ><i class="fal fa-edit"></i></a>
                                <?php endif; ?>
                            </div>
                            <?php 
                            echo wpautop( get_the_author_meta('description',$laumember->ID), false );
                            if($laumember->user_url!='') 
                                echo '<a href="'.esc_url( $laumember->user_url ).'" class="btn color2-bg" rel="nofollow">'.__( 'Visit Website <i class="fal fa-chevron-right"></i>', 'townhub-add-ons' ).'</a>';
                            ?>
                            
                        </div>
                    </div>
                    <!-- list-single-main-item end -->  


                    <?php 
                    if(is_front_page()) {
                        $paged = (get_query_var('page')) ? get_query_var('page') : 1;
                    } else {
                        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                    }
                    $post_args = array(
                        'post_type' => 'listing',
                        'author' => $laumember->ID,
                        'paged' => $paged,
                        'posts_per_page'=> townhub_addons_get_option('listings_count'),
                        // 'posts_per_page'=> $settings['posts_per_page'],
                        // 'orderby'=> $settings['order_by'],
                        // 'order'=> $settings['order'],
                        'post_status' => 'publish'
                    );
                    $posts_query = new WP_Query($post_args);
                    if($posts_query->have_posts()) { 
                    ?>
                    <!-- list-main-wrap-header-->
                    <div class="list-main-wrap-header fl-wrap block_box no-vis-shadow clearfix flex-items-center">
                        <!-- list-main-wrap-title-->
                        <div class="list-main-wrap-title">
                            <h2 id="lsearch-results-title"><?php echo sprintf(__( 'Listings by  : <span>%s</span>', 'townhub-add-ons' ), $laumember->display_name); ?></h2>
                        </div>
                        <!-- list-main-wrap-title end-->
                        <!-- list-main-wrap-opt-->
                        <div class="list-main-wrap-opt flex-items-center">
                            <?php // townhub_addons_get_template_part('template-parts/filter/sortby'); ?>
                            <?php townhub_addons_get_template_part('template-parts/filter/grid-list'); ?>
                            
                        </div>
                        <!-- list-main-wrap-opt end-->                    
                    </div>
                    <!-- list-main-wrap-header end--> 
                    
                    <div class="listings-grid-wrap two-cols pad-top-30">
                        <!-- list-main-wrap-->
                        <div class="list-main-wrap fl-wrap card-listing ">
                            <div id="listing-items" class="listing-items listing-items-wrapper">
                            <?php
                                /* Start the Loop */
                                while($posts_query->have_posts()) : $posts_query->the_post(); 
                                    townhub_addons_get_template_part('template-parts/listing');
                                endwhile;
                            ?>
                            </div>
                            <?php
                                townhub_addons_custom_pagination($posts_query->max_num_pages,$range = 2, $posts_query);
                            ?>                                
                        </div>
                        <!-- list-main-wrap end-->
                    </div>
                    <!-- llistings-grid-wrap end-->
                    <?php 
                    }
                    //end if has_posts
                    wp_reset_postdata(); ?>  
                    <?php 
                    if( townhub_addons_get_option('author_show_posts') == 'yes' && have_posts() ): ?>
                    <!-- list-main-wrap-header-->
                    <div class="list-main-wrap-header fl-wrap block_box no-vis-shadow clearfix flex-items-center lauthor-posts mt-30">
                        <!-- list-main-wrap-title-->
                        <div class="list-main-wrap-title">
                            <h2><?php echo sprintf(_x( 'Posts by <span>%s</span>', 'Author page', 'townhub-add-ons' ), $laumember->display_name); ?></h2>
                        </div>
                        <!-- list-main-wrap-title end-->
                                           
                    </div>
                    <!-- list-main-wrap-header end--> 
                    <div class="list-single-main-wrapper fl-wrap list-posts-wrap two-cols pad-top-30" id="auth-posts-sec">

                        <?php get_template_part( 'template-parts/loop' ); ?>

                    </div>
                    <!-- end list-single-main-wrapper -->
                    <?php endif; ?>

                </div><!-- end author-single-content -->
                <?php 
                if( false === $has_nosidebar ): ?>
                <div class="col-md-<?php echo esc_attr( $sb_w ); ?> author-single-sidebar">
                    <?php 
                    if( townhub_addons_get_option('author_hide_about') != 'yes' ): ?>
                    <!--box-widget-item -->
                    <div class="box-widget-item fl-wrap block_box">
                        <div class="box-widget-item-header">
                            <h3><?php _e( 'About Author ', 'townhub-add-ons' ); ?></h3>
                        </div>
                        <div class="box-widget">
                            <div class="box-widget-author fl-wrap">
                                <div class="box-widget-author-title">
                                    <div class="box-widget-author-title-img">
                                        <?php
                                            echo get_avatar($laumember->user_email, '150', 'https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=150', $laumember->display_name );
                                        ?>
                                    </div>
                                    <div class="box-widget-author-title_content">
                                        <a href="javascript:void(0);" rel="nofollow"><?php echo $laumember->display_name; ?></a>
                                        <?php echo sprintf(__( '<span>%d Places Hosted</span>', 'townhub-add-ons' ), count_user_posts( $laumember->ID , "listing" , true ) ); ?>
                                        <?php 
                                        if( get_user_meta( $laumember->ID, ESB_META_PREFIX.'verified', true ) == 'yes' ): ?>
                                            <div class="card-verified tolts" data-microtip-position="top-left" data-tooltip="<?php esc_attr_e( 'Verified', 'townhub-add-ons' ); ?>"><i class="fal fa-user-check"></i><?php esc_attr_e( 'Verified', 'townhub-add-ons' ); ?></div>
                                        <?php else:  ?>
                                            <div class="card-verified cv_not tolts" data-microtip-position="top-left" data-tooltip="<?php esc_attr_e( 'Not Verified', 'townhub-add-ons' ); ?>"><i class="fal fa-minus-octagon"></i><?php esc_attr_e( 'Not Verified', 'townhub-add-ons' ); ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if( townhub_addons_get_option('show_fchat') == 'yes' ): ?>
                                    <div class="box-widget-author-title_opt">
                                        <a href="#" class="tolt color-bg cwb open-chat" data-microtip-position="top" data-tooltip="<?php esc_attr_e( 'Chat With Owner', 'townhub-add-ons' ); ?>" rel="nofollow"><i class="fas fa-comments-alt"></i></a>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--box-widget-item end -->  
                    <?php endif; ?>       
                    <?php 
                    if( townhub_addons_get_option('author_hide_contacts') != 'yes' ): ?>                           
                    <!--box-widget-item -->                                       
                    <div class="box-widget-item fl-wrap block_box">
                        <div class="box-widget-item-header">
                            <h3><?php _e( 'User Contacts  ', 'townhub-add-ons' ); ?></h3>
                        </div>
                        <div class="box-widget">

                            <div class="box-widget-content">
                                
                                <div class="list-author-widget-contacts list-item-widget-contacts">
                                    <ul class="no-list-style">
                                        <?php if($au_address != ''){ ?>
                                            <li>
                                                <span><i class="fal fa-map-marker"></i><?php _e( ' Address :', 'townhub-add-ons' ); ?></span>
                                                <span><?php echo $au_address;?></span>
                                            </li>
                                        <?php } ?>
                                        <?php if($au_phone != ''){ ?>
                                            <li>
                                                <span><i class="fal fa-phone"></i><?php _e( ' Phone :', 'townhub-add-ons' ); ?></span>
                                                <a href="tell:<?php echo esc_attr($au_phone);?>"><?php echo $au_phone;?></a>
                                            </li>
                                        <?php } ?>
                                        <?php if($au_email != ''){ ?>
                                            <li>
                                                <span><i class="fal fa-envelope"></i><?php _e( ' Mail :', 'townhub-add-ons' ); ?></span>
                                                <a href="mailto:<?php echo $au_email;?>"><?php echo $au_email;?></a>
                                            </li>
                                        <?php } ?>
                                        <?php if($laumember->user_url != ''){ ?>
                                            <li>
                                                <span><i class="fal fa-browser"></i><?php _e( ' Website :', 'townhub-add-ons' ); ?></span>
                                                <a href="<?php echo esc_url( $laumember->user_url );?>" target="_blank"><?php echo esc_url( $laumember->user_url );?></a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <?php 
                                $au_socials = get_user_meta( $laumember->ID, '_cth_socials', true );
                                // echo'<pre>';var_dump($au_socials);
                                if(is_array($au_socials) && count($au_socials)) : ?>
                                <div class="list-widget-social bottom-bcw-box  fl-wrap author-single-widget-socials">
                                    <ul class="no-list-style">
                                        <?php 
                                        foreach ($au_socials as $social) {
                                            echo '<li><a href="'.esc_url( $social['url'] ).'" target="_blank" ><i class="fab fa-'.esc_attr( $social['name'] ).'"></i></a></li>';
                                        }
                                        ?>
                                    </ul>
                                    <div class="bottom-bcw-box_link"><a href="#author-single-form" class="show-single-contactform tolt custom-scroll-link" data-microtip-position="top" data-tooltip="<?php esc_attr_e( 'Write Message', 'townhub-add-ons' ); ?>"><i class="fal fa-envelope"></i></a></div>
                                </div>
                                <?php 
                                endif;?>

                            </div>
                        </div>
                    </div>
                    <!--box-widget-item end -->    
                    <?php endif; ?>     
                    <?php 
                    if( townhub_addons_get_option('author_hide_form') != 'yes' ): ?>                                 
                    <!--box-widget-item -->
                    <div class="box-widget-item fl-wrap block_box" id="author-single-form">
                        <div class="box-widget-item-header">
                            <h3><?php _e( 'Get in Touch ', 'townhub-add-ons' ); ?></h3>
                        </div>
                        <div class="box-widget">
                            <div class="box-widget-content">

                                <form class="author-message-form custom-form" action="#" method="post">
                                    <?php do_action( 'townhub_author_contact_form_before', $laumember->ID ); ?>
                                    <fieldset>
                                    
                                        <label><i class="fal fa-user"></i></label>
                                        <input name="lmsg_name" class="has-icon" type="text" placeholder="<?php esc_attr_e( 'Your Name*', 'townhub-add-ons' ); ?>" value="<?php echo esc_attr( $loggedName ); ?>" required="required">
                                        <div class="clearfix"></div>
                                        <label><i class="fal fa-envelope"></i></label>
                                        <input name="lmsg_email" class="has-icon" type="text" placeholder="<?php esc_attr_e( 'Email Address*', 'townhub-add-ons' ); ?>" value="<?php echo esc_attr( $loggedEmail ); ?>" required="required">
                                        <label><i class="fal fa-phone"></i></label>
                                        <input name="lmsg_phone" class="has-icon" type="text" placeholder="<?php esc_attr_e( 'Phone', 'townhub-add-ons' ); ?>" value="<?php echo esc_attr( $loggedPhone ); ?>">

                                        <textarea name="lmsg_message" cols="40" rows="3" placeholder="<?php esc_attr_e( 'Additional Information:', 'townhub-add-ons' ); ?>"></textarea>
                                    </fieldset>
                                    
                                    <?php do_action( 'townhub_author_contact_form_after', $laumember->ID ); ?>
                                    <div class="author-message-error"></div>
                                    <button class="btn color2-bg author-msg-submit" type="submit"><?php _e( 'Send Message <i class="fal fa-paper-plane"></i>', 'townhub-add-ons' ); ?></button>
                                    <input type="hidden" name="authid" value="<?php echo $laumember->ID; ?>">
                                </form>

            
                            </div>
                        </div>
                    </div>
                    <!--box-widget-item end -->  
                    <?php endif; ?>         
                    <?php 
                    if(is_active_sidebar('author-sidebar')){
                        dynamic_sidebar('author-sidebar');
                    } ?>                               
                </div><!-- end author-single-sidebar col-md-4 -->
                <?php endif; ?>      

            </div><!-- end row -->
        </div><!-- end post-container -->
    </div><!-- end container -->
</section>
<div class="limit-box fl-wrap"></div>


<?php

get_footer(  );