<?php
/* add_ons_php */
// don't show on customer dashboard


$current_user_id = get_current_user_id();           
$args = array(
    'post_type'         =>  'listing', 
    'author'            =>  $current_user_id, 
    // 'orderby'       =>  'date',
    // 'order'         =>  'DESC',
    // 'paged'         => $paged,
    'posts_per_page'    => -1,//-1 no limit
    'post_status'       => 'publish',
    'fields'            => 'ids'
);
$listings = get_posts( $args );
$listings_IDs = array();
if(!empty($listings)){ 
    foreach ($listings as $list_ID) {
        $listings_IDs[] = $list_ID;
    }
}

?>
<div class="dashboard-content-wrapper dashboard-content-reviews">
    <div class="dashboard-content-inner">
        
        <div class="dashboard-title fl-wrap">
            <h3><?php _e( 'Reviews', 'townhub-add-ons' ); ?></h3>
        </div>
        
        <div class="dashboard-reviews-grid">
            
            <?php 
            $comments = array();
            if($listings_IDs){
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                
                $comment_args = array(
                    'post__in'          => $listings_IDs,
                    'author__not_in'    => array($current_user_id), 

                    // 'number'                 => 5,
                    // 'offset'               => ($paged - 1) * 5,
                    'status'               => 'approve' //Change this to the type of comments to be displayed
                );

                $comments_count = get_comments( 
                    array_merge(
                        $comment_args, 
                        array(
                            'count'                 => true,
                        )
                    )
                );

                $comments_per_page = get_option( 'posts_per_page' );

                $comments = get_comments( 
                    array_merge(
                        $comment_args, 
                        array(
                            'number'                 => $comments_per_page,
                            'offset'               => ($paged - 1) * $comments_per_page,
                        )
                    )
                );
            }
            if( !empty($comments) ):

                // https://codex.wordpress.org/Function_Reference/wp_set_comment_status

                // https://codex.wordpress.org/Function_Reference/wp_list_comments
                $com_args = array(
                    'walker'            => null,
                    'max_depth'         => 0, // do not load reply
                    'style'             => 'div',
                    'callback'          => 'townhub_addons_comments',
                    'end-callback'      => null,
                    'type'              => 'all',
                    'reply_text'        => esc_html__('Reply','townhub-add-ons'),
                    'page'              => '',
                    'per_page'          => '',
                    'avatar_size'       => 50,
                    'reverse_top_level' => false, //Show the oldest comments at the top of the list
                    'reverse_children'  => '',
                    'format'            => 'html5', //or xhtml if no HTML5 theme support
                    'short_ping'        => false, // @since 3.6,
                    'echo'              => true, // boolean, default is true
                );

                echo '<div class="dashboard-reviews-wrap">';
                    //Display the list of comments
                    wp_list_comments($com_args, $comments);
                echo '</div>';
                townhub_addons_comments_pagination( ceil($comments_count / $comments_per_page) );

            else:
            ?> 
            <div id="review-no" class="dashboard-card dashboard-review-item">
                <div class="dashboard-card-content">
                    <?php _e( '<p>You have no review yet!</p>', 'townhub-add-ons' ); ?>
                </div>
            </div>
            <?php
            endif; ?> 

        </div>
    </div>
</div>
