<?php
/* add_ons_php */


$current_user = wp_get_current_user(); 

Esb_Class_Message_CPT::delete_messages_count($current_user->ID);


if(is_front_page()) {
    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
} else {
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
}                 

$post_args = array(
    'post_type'     => 'lmessage', 
    // 'author'        =>  0, 
    'orderby'       => 'date',
    'order'         => 'DESC',
    'paged'         => $paged,
    'post_status'   => 'publish',
    // 'posts_per_page' => -1, // no limit
    
    'meta_query' => array(
        // show listing author bookings
        // 'relation' => 'OR',
        array(
            'key'       => ESB_META_PREFIX.'to_user_id',
            'value'     => $current_user->ID,
            // 'compare' => 'IN',
            'type'      => 'NUMERIC'
        ),
        // show user bookings
        // array(
        //     'relation' => 'AND',
        //     array(
        //         'key'     => ESB_META_PREFIX.'lmsg_email',
        //         'value'   => $current_user->user_email,
        //     ),
        //     array(
        //         'key'     => ESB_META_PREFIX.'lmsg_status',
        //         'value'   => 'canceled',
        //         'compare' => '!='
        //     )  
        // ),
        
    ),
    
);
if(isset($_GET['id']) && $_GET['id'] > 0){
    $post_args['meta_query'] = array(
        array(
            'key'       => ESB_META_PREFIX.'first_msg',
            'value'     => $_GET['id'],
            'type'      => 'NUMERIC'
        )
    );
}

// The Query
$posts_query = new WP_Query( $post_args );

?>

<div class="dashboard-content-wrapper dashboard-content-messages">
    <div class="dashboard-content-inner">
        
        <div class="dashboard-title   fl-wrap">
            <h3><?php _e( 'Messages', 'townhub-add-ons' ); ?></h3>
        </div>
        
        <div class="dashboard-messages-grid">
            
            <?php 
            if($posts_query->have_posts()) :
                while($posts_query->have_posts()) : $posts_query->the_post(); 
                    $lmsg_email = get_post_meta( get_the_ID(), ESB_META_PREFIX.'lmsg_email', true );
                    $lmsg_phone = get_post_meta( get_the_ID(), ESB_META_PREFIX.'lmsg_phone', true );
                    $lmsg_name = get_post_meta( get_the_ID(), ESB_META_PREFIX.'lmsg_name', true );

                    $from_user = get_post_meta( get_the_ID(), ESB_META_PREFIX.'from_user_id', true );
                    $first_msg_meta = get_post_meta( get_the_ID(), ESB_META_PREFIX.'first_msg', true );
                    $lmsg_status = get_post_meta( get_the_ID(), ESB_META_PREFIX.'lmsg_status', true );
                    $listing_id             = get_post_meta( get_the_ID(), ESB_META_PREFIX.'listing_id', true );
                ?>
                <div id="message-<?php the_ID(); ?>" <?php post_class('dashboard-card dashboard-message-item'); ?>>

                    <div class="dashboard-card-avatar">
                        <?php echo get_avatar($lmsg_email,'80','https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=80', $lmsg_name ); ?>
                        <span class="booking-list-new green-bg lmsg-<?php echo $lmsg_status;?>"><?php echo Esb_Class_Message_CPT::status_text( $lmsg_status ); ?></span>
                    </div>
                    <div class="dashboard-card-content">
                        <?php echo sprintf( __( '<h4 class="entry-title">%1$s - <span>%2$s</span></h4>', 'townhub-add-ons' ) , get_the_title(  ), get_the_date( get_option( 'date_format' ) ) ); ?>
                        <?php 
                        if( !empty($first_msg_meta) ): 
                            $first_msg_url = add_query_arg('id', $first_msg_meta, Esb_Class_Dashboard::screen_url('messages') );
                        ?>
                        <div class="booking-details fl-wrap">                                                               
                            <span class="booking-title"><?php _e( 'Reply to: ', 'townhub-add-ons' ); ?></span>  
                            <span class="booking-text"><a class="msg-reply-to-link" href="<?php echo esc_url( $first_msg_url );?>"><?php echo get_the_title( $first_msg_meta ); ?></a></span>
                        </div>
                        <?php 
                        endif; ?>
                        <div class="booking-details fl-wrap">                                                               
                            <span class="booking-title"><?php _e( 'Name :', 'townhub-add-ons' ); ?></span>  
                            <span class="booking-text"><a href="javascript:void(0);" target="_top"><?php echo esc_html($lmsg_name); ?></a></span>
                        </div>
                        <div class="booking-details fl-wrap">                                                               
                            <span class="booking-title"><?php _e( 'Mail :', 'townhub-add-ons' ); ?></span>  
                            <span class="booking-text"><a href="mailto:<?php echo esc_attr($lmsg_email); ?>" target="_top"><?php echo esc_html($lmsg_email); ?></a></span>
                        </div>
                        <div class="booking-details fl-wrap">
                            <span class="booking-title"><?php _e( 'Phone :', 'townhub-add-ons' ); ?></span>   
                            <span class="booking-text"><a href="tel:<?php echo esc_attr($lmsg_phone); ?>" target="_top"><?php echo esc_html($lmsg_phone); ?></a></span>
                        </div>
                        <?php 
                        if( !empty($listing_id) ): ?>
                        <div class="booking-details fl-wrap">
                            <span class="booking-title"><?php _e( 'For listing :', 'townhub-add-ons' ); ?></span>   
                            <span class="booking-text"><a href="<?php echo get_permalink( $listing_id ); ?>" target="_blank"><?php echo get_the_title( $listing_id ); ?></a></span>
                        </div>
                        <?php endif; ?>
                        <span class="fw-separator"></span>
                        <?php echo wp_kses_post( get_post_meta( get_the_ID(), ESB_META_PREFIX.'lmsg_message', true ) ); ?>
                    </div>

                    <div class="booking-list-contr"><?php if( !empty($from_user) ): 
                            $first_msg = $first_msg_meta != '' ? $first_msg_meta : get_the_ID();
                        ?><a href="#" class="green-bg tolt reply-lauthor_msg" data-microtip-position="left" data-tooltip="<?php esc_attr_e( 'Reply', 'townhub-add-ons' ); ?>" data-id="<?php echo $from_user;?>" data-replyto="<?php echo get_the_ID();?>" data-firstmsg="<?php echo esc_attr($first_msg);?>" data-title="<?php echo esc_attr( get_the_title() ); ?>"><i class="fal fa-paper-plane"></i></a><?php endif; ?><?php
                        if(get_current_user_id() == get_post_meta( get_the_ID(), ESB_META_PREFIX.'to_user_id', true ) ): ?><a href="#" class="del-bg tolt del-lauthor_msg" data-microtip-position="left" data-tooltip="<?php esc_attr_e( 'Delete', 'townhub-add-ons' ); ?>" data-id="<?php echo get_the_ID();?>" data-title="<?php echo esc_attr( get_the_title() ); ?>"><i class="fal fa-trash"></i></a><?php endif; ?></div>


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
            <div id="message-no" class="dashboard-card dashboard-message-item">
                <div class="dashboard-card-content">
                    <?php _e( '<p>You have no message yet!</p>', 'townhub-add-ons' ); ?>
                </div>
            </div>
            <?php
            endif; ?> 

        </div>
    </div>
</div>