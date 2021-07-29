<?php
/* add_ons_php */ 

// pre_comment_approved
// https://developer.wordpress.org/reference/functions/wp_allow_comment/
// 3- Can ratings and reviews be published only by users that have booked the listing previously? I want to avoid vandalism and have toxic reviews by competitors, etc.
function townhub_addons_pre_comment_approved($approved, $commentdata){
    global $wpdb;
    if( townhub_addons_get_option('approve_booked_comment') == 'yes' && ! empty( $commentdata['comment_post_ID'] ) && get_post_type( $commentdata['comment_post_ID'] ) === 'listing' ){
        if ( ! empty( $commentdata['user_id'] ) ) {
            $user        = get_userdata( $commentdata['user_id'] );
            $post_author = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT post_author FROM $wpdb->posts WHERE ID = %d LIMIT 1",
                    $commentdata['comment_post_ID']
                )
            );

            // check customer
            if ( isset( $user ) && $commentdata['user_id'] != $post_author ) {  
                $bookings = get_posts( 
                    array(
                        'fields'            => 'ids',
                        'post_type'         => 'lbooking', 
                        'post_status'       => 'publish',
                        'posts_per_page'    => -1, // no limit
                        'meta_query'        => array(
                            array(
                                'key'     => ESB_META_PREFIX.'user_id',
                                'value'   => $commentdata['user_id'],
                            ),
                            array(
                                'key'     => ESB_META_PREFIX.'listing_id',
                                'value'   => $commentdata['comment_post_ID'],
                            ),
                            array(
                                'key'     => ESB_META_PREFIX.'lb_status',
                                'value'   => 'canceled', // completed
                                'compare' => '!='
                            ) 
                        ),
                        
                    )
                );

                if( !empty($bookings) ){
                    $approved = 1;
                }
            }elseif( !isset( $user ) ){
                $bookings = get_posts( 
                    array(
                        'fields'            => 'ids',
                        'post_type'         => 'lbooking', 
                        'post_status'       => 'publish',
                        'posts_per_page'    => -1, // no limit
                        'meta_query'        => array(
                            array(
                                'key'     => ESB_META_PREFIX.'lb_email',
                                'value'   => $commentdata['comment_author_email'],
                            ),
                            array(
                                'key'     => ESB_META_PREFIX.'listing_id',
                                'value'   => $commentdata['comment_post_ID'],
                            ),
                            array(
                                'key'     => ESB_META_PREFIX.'lb_status',
                                'value'   => 'canceled', // completed
                                'compare' => '!='
                            ) 
                        ),
                        
                    )
                );
                if( !empty($bookings) ){
                    $approved = 1;
                }
            }
        }
    }
    // comment_post_ID
    // user_id
    return $approved;
}
add_filter('pre_comment_approved','townhub_addons_pre_comment_approved', 10, 2);

function townhub_addons_get_average_ratings($postID) { 
    $average_key = ESB_META_PREFIX.'rating_average';
    $count_key = ESB_META_PREFIX.'rating_count';


    $laverage = get_post_meta($postID, $average_key, true);
    $listing_type_id = get_post_meta( $postID, ESB_META_PREFIX.'listing_type_id', true ); 
    $rating_fields = townhub_addons_get_rating_fields($listing_type_id); 
    $rating_values = array();
    if (!empty($rating_fields)) {
        foreach ((array)$rating_fields as $key => $field) {
            // $field = json_decode(json_encode($fields), true);
            // $rate_name = $field['fieldName'];
            // $rate_key =  ESB_META_PREFIX.$field['fieldName'];
            $rating_values[$field['fieldName']] = get_post_meta($postID, ESB_META_PREFIX.$field['fieldName'], true);    
        }
    }
    if( empty($laverage) ){ 
        return false;
    }else{
        return array( 
            'count'     => get_post_meta($postID, $count_key, true),
            'sum'       => number_format((float)$laverage, 1),
            'rating'    => number_format((float)$laverage, 1),
            'values'    => $rating_values, 
        );
    }
    
}

// http://oscardias.com/development/php/wordpress/how-to-add-a-rate-field-to-wordpress-comments/
add_action('comment_post','townhub_addons_comment_ratings', 10, 2);
 
function townhub_addons_comment_ratings($comment_id, $approved) { 
    $comment = get_comment($comment_id);
    if( !empty($comment) && $comment->comment_parent == '0' ){
        $comment_post_ID = $comment->comment_post_ID;
        $listing_type_id = get_post_meta( $comment_post_ID, ESB_META_PREFIX.'listing_type_id', true );
        $rating_fields = townhub_addons_get_rating_fields($listing_type_id);
        if (!empty($rating_fields)) {
            foreach ((array)$rating_fields as $key => $field) {
                $rate_name = $field['fieldName'];
                if(isset($_POST[$rate_name])) add_comment_meta($comment_id, ESB_META_PREFIX.$rate_name, $_POST[$rate_name]);
            }
        }
        if( townhub_addons_get_option('allow_rating_imgs') == 'yes' ){
            if(!empty($comment)){
                $comment_post_ID = $comment->comment_post_ID;
            }
            $images = townhub_addons_handle_image_multiple_upload( 'images', $comment_post_ID);
            if( !empty( $images ) ) add_comment_meta($comment_id, ESB_META_PREFIX.'images', $images );
        }

        if( $approved === 1 ){
            townhub_addons_comment_unapproved_to_approved($comment);
        }
    }
    // allow comment images
        

}

// update listing rating for sort
add_action( 'comment_unapproved_to_approved', 'townhub_addons_comment_unapproved_to_approved' );
function townhub_addons_comment_unapproved_to_approved($comment){
    $postID = $comment->comment_post_ID;
    $average_key = ESB_META_PREFIX.'rating_average';
    $count_key = ESB_META_PREFIX.'rating_count';

    $count = (int)get_post_meta($postID, $count_key, true);
    $rvals = array();
    $rating_fields = townhub_addons_get_rating_fields( get_post_meta( $postID, ESB_META_PREFIX.'listing_type_id', true ) );
    if (!empty($rating_fields)) {
        foreach ($rating_fields as $key => $field) {
            $rFieldName = $field['fieldName'];
            $lfval = (float)get_post_meta($postID, ESB_META_PREFIX.$rFieldName, true);
            $rval = (float)get_comment_meta($comment->comment_ID, ESB_META_PREFIX.$rFieldName, true);
            if( $rval > 0  ){
                if( $lfval > 0 && $count > 0 ){
                    update_post_meta($postID, ESB_META_PREFIX.$rFieldName, ($lfval * $count + $rval)/($count+1) );
                }else{
                    update_post_meta($postID, ESB_META_PREFIX.$rFieldName, $rval );
                }
                $rvals[] = $rval;
            }
        }
    }
    $rval_new = 0;
    if ( !empty($rvals) ){
        $rval_new = array_sum($rvals) / count($rvals);
    }

    if( !empty($rval_new) ){
        // update rating average
        $laverage = (float)get_post_meta($postID, $average_key, true);
        if( $laverage > 0 ){
            update_post_meta( $postID, $average_key, ($laverage * $count + $rval_new)/($count+1) );
        }else{
            update_post_meta( $postID, $average_key, $rval_new );
        }

        // update rating count
        if( $count > 0 ) 
            $count++;
        else
            $count = 1;

        update_post_meta($postID, $count_key, $count);
    }
}
add_action( 'comment_approved_to_unapproved', 'townhub_addons_comment_approved_to_unapproved' );
function townhub_addons_comment_approved_to_unapproved($comment){
    $postID = $comment->comment_post_ID;
    $average_key = ESB_META_PREFIX.'rating_average';
    $count_key = ESB_META_PREFIX.'rating_count';
    $count = (int)get_post_meta($postID, $count_key, true);
    $rvals = array();

    $rating_fields = townhub_addons_get_rating_fields( get_post_meta( $postID, ESB_META_PREFIX.'listing_type_id', true ) );
    if (!empty($rating_fields)) {
        foreach ($rating_fields as $key => $field) {
            $rFieldName = $field['fieldName'];
            $lfval = (float)get_post_meta($postID, ESB_META_PREFIX.$rFieldName, true);
            $rval = (float)get_comment_meta($comment->comment_ID, ESB_META_PREFIX.$rFieldName, true);
            if( $rval > 0  ){
                if( $lfval > 0 && $count > 1 ){
                    update_post_meta($postID, ESB_META_PREFIX.$rFieldName, ($lfval * $count - $rval)/($count-1) );
                }else{
                    update_post_meta($postID, ESB_META_PREFIX.$rFieldName, '' );
                }
                $rvals[] = $rval;
            }
        }
    }
    $rval_new = 0;
    if ( !empty($rvals) ){
        $rval_new = array_sum($rvals) / count($rvals);
    }
    if( !empty($rval_new) ){
        // update rating average
        $laverage = (float)get_post_meta($postID, $average_key, true);
        if( $laverage > 0 && $count > 1 ){
            update_post_meta( $postID, $average_key, ($laverage * $count - $rval_new)/($count-1) );
        }else{
            update_post_meta( $postID, $average_key, '' );
        }

        // update rating count
        if( $count > 1 ) 
            $count--;
        else
            $count = 0;

        update_post_meta($postID, $count_key, $count);
    }
}
// trash comment
// trash_comment - Fires immediately before a comment is sent to the Trash. So comment_approved is 0, 1 or 'spam'
add_action( 'trash_comment', function($comment_ID, $comment){
    if( $comment->comment_approved == '1' ){
        townhub_addons_comment_approved_to_unapproved( $comment );
    }
}, 10, 2 );
// untrash comment
// untrashed_comment - Fires immediately after a comment is restored from the Trash. So comment_approved is 0, 1 or 'spam'
add_action( 'untrashed_comment', function($comment_ID, $comment){
    $new_comment = get_comment($comment_ID);
    if( $new_comment->comment_approved == '1' ){
        townhub_addons_comment_unapproved_to_approved( $new_comment );
    }
}, 10, 2 );
// delete comment
// add_action( 'delete_comment', function($comment_ID, $comment){
//     townhub_addons_comment_approved_to_unapproved( $comment );
// }, 10, 2 );


// modify comment template for listing post
add_filter( 'comments_template', function ( $template ) {
    $queried_object = get_queried_object();
    if (isset($queried_object->post_type) && $queried_object->post_type == 'listing') {
        return ESB_ABSPATH .'inc/comments.php';
    }
    return $template;
});

function townhub_addons_move_comment_field_to_bottom( $fields ) {
    $queried_object = get_queried_object();
    if (isset($queried_object->post_type) && $queried_object->post_type == 'listing') {
        $comment_field = $fields['comment'];
        unset( $fields['comment'] );
        $fields['comment'] = $comment_field . townhub_addons_get_comment_images_uploader();
        return $fields;
    }
    return $fields;
}
function townhub_addons_get_comment_images_uploader(){
    $uploader = '';
    
    if( townhub_addons_get_option('allow_rating_imgs') == 'yes' ): 
        ob_start();
        ?>
        <div class="leave-rating-imgs-wrap clearfix">
            <?php 
                townhub_addons_get_template_part( 'template-parts/images-upload', false, array( 'is_single'=>false, 'name'=>'images[]', 'desc_text' => __( '<i class="fal fa-image"></i> Add Photos', 'townhub-add-ons' ) ) );
            ?>
        </div>
        <?php 
        $uploader = ob_get_clean();
    endif; 

    return $uploader;
}
add_filter( 'comment_form_fields', 'townhub_addons_move_comment_field_to_bottom' );

function townhub_addons_change_submit_button( $submit_button ) {
    $queried_object = get_queried_object();
    if (isset($queried_object->post_type) && $queried_object->post_type == 'listing') {
        return '<button class="btn color2-bg" type="submit">'.__( 'Submit Review <i class="fal fa-paper-plane"></i>', 'townhub-add-ons' ).'</button>';
    }
    return $submit_button;
}
 
add_filter( 'comment_form_submit_button', 'townhub_addons_change_submit_button');

function townhub_addons_comment_rating_field(){
    
    if(!townhub_addons_get_option('single_show_rating')) return;

    $queried_object = get_queried_object();
    if (isset($queried_object->post_type) && $queried_object->post_type == 'listing') {
        $r_sum = array();
        $rating_base = (int)townhub_addons_get_option('rating_base');
        $rating_fields = townhub_addons_get_rating_fields( get_post_meta( $queried_object->ID, ESB_META_PREFIX.'listing_type_id', true ) );
        if($rating_fields != null && !empty($rating_fields)) {
        ?>
        <div class="review-score-form fl-wrap flex-items-center">
            <div class="review-range-container">
                <?php
                foreach ($rating_fields as $key => $field) {
                    $fobj = json_decode( json_encode($field), true);
                    $r_base = isset($fobj['rating_base']) && $fobj['rating_base'] != '' ? intval( $fobj['rating_base'] ) : $rating_base;
                    $r_default = isset($fobj['default']) && $fobj['default'] != '' ? intval( $fobj['default'] ) : $rating_base;
                    $r_sum[] = $r_default;
                ?>
                <!-- review-range-item-->
                <div class="review-range-item flex-items-center">
                    <div class="range-slider-title"><?php echo $fobj['title'] ?></div>
                    <div class="range-slider-wrap ">
                        <input name="<?php echo $fobj['fieldName'] ?>" type="text" class="rate-range" data-min="1" data-max="<?php echo esc_attr( $r_base ); ?>" data-step="1" value="<?php echo esc_attr( $r_default ); ?>">
                    </div>
                </div>
                <!-- review-range-item end --> 
                <?php 
                } ?>
                
                                                    
            </div>
            <?php 
            $r_sum = array_filter($r_sum); 
            $sum_val = array_sum($r_sum)/count($r_sum);
            ?>
            <div class="review-total">
                <div class="review-total-inner">
                    <span class="reviews-total-score" id="reviews-total-score"><?php echo number_format($sum_val, 1); ?></span> 
                    <strong><?php _e( 'Your Score', 'townhub-add-ons' ); ?></strong>
                </div>
            </div>

        </div>   
        <?php
        }
        
    }
}

add_action('comment_form_before_fields','townhub_addons_comment_rating_field');
add_action('comment_form_logged_in_after','townhub_addons_comment_rating_field');


add_action( 'comments-list-before', function($post_ID){
    $rating = townhub_addons_get_average_ratings( $post_ID ); 
    
    $rating_fields = townhub_addons_get_rating_fields( get_post_meta( $post_ID , ESB_META_PREFIX.'listing_type_id', true ) );
    if( !empty($rating) && townhub_addons_get_option('show_score_rating') == '1' && townhub_addons_get_option('single_show_rating') == '1' ): 
        $rating_base = (int)townhub_addons_get_option('rating_base'); 
        if(empty($rating_base)) $rating_base = 5;
    ?>
    <!--reviews-score-wrap-->   
    <div class="reviews-score-wrap fl-wrap">
        <div class="review-score-total">
            <span class="review-score-total-item"><?php echo $rating['sum']; ?><strong class="review-text"><?php echo townhub_addons_rating_text($rating['sum']); ?></strong></span>
            <div class="listing-rating card-popup-rainingvis" data-rating="<?php echo $rating['sum']; ?>" data-stars="<?php echo $rating_base; ?>"></div>
        </div>
        <div class="review-score-detail">
            <!-- review-score-detail-list-->
            <div class="review-score-detail-list">
                <?php
                if(!empty($rating_fields)) {
                    foreach ((array)$rating_fields as $key => $field) {
                        $val = floatval( $rating['values'][$field['fieldName']] );
                    ?>
                    <!-- rate item-->
                    <div class="rate-item">
                        <div class="rate-item-title"><span><?php echo $field['title']; ?></span></div>
                        <div class="rate-item-bg" data-percent="<?php echo ($val/$rating_base)*100; ?>%">
                            <div class="rate-item-line gradient-bg"></div>
                        </div>
                        <div class="rate-item-percent"><?php echo number_format($val, 1);?></div>
                    </div>
                    <!-- rate item end-->
                    <?php 
                    }
                }
                ?> 
            </div>
            <!-- review-score-detail-list end-->
        </div>
    </div>
    <!-- reviews-score-wrap end -->  
    <?php endif;
}, 10);

/**
 * Custom comments list
 *
 * @since TownHub 1.0
 */
if (!function_exists('townhub_addons_comments')) {
    function townhub_addons_comments($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        extract($args, EXTR_SKIP);
        
        if ('div' == $args['style']) {
            $tag = 'div';
            $add_below = 'comment';
        } 
        else {
            $tag = 'li';
            $add_below = 'div-comment';
        }
?>
        <<?php
        echo esc_attr($tag); ?> <?php
        comment_class(empty($args['has_children']) ? 'reviews-comments-item comment-nochild' : 'reviews-comments-item comment-haschild') ?> id="comment-<?php
        comment_ID() ?>">
        <?php
        if ('div' != $args['style']): ?>
        <div id="div-comment-<?php
            comment_ID() ?>" class="comment-body thecomment">
        <?php
        endif; ?>

            <div class="review-comments-avatar">
                <?php if ($args['avatar_size'] != 0) echo get_avatar($comment, $args['avatar_size'], 'https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s='.$args['avatar_size'], get_comment_author( $comment->comment_ID )); ?>
            </div>
            <div class="reviews-comments-item-text">

                <div class="reviews-comments-header fl-wrap">
                    <h4><?php echo get_comment_author_link($comment->comment_ID); ?>
                    <?php
                    if(is_singular() && $comment->comment_post_ID != get_the_ID()) { 
                        echo esc_html__( ' on ', 'townhub-add-ons' ) . sprintf( '<a href="%1$s" class="reviews-comments-item-link">%2$s</a> ',
                                                            esc_url( get_the_permalink( $comment->comment_post_ID ) ),
                                                            esc_html( get_the_title( $comment->comment_post_ID ) )
                                                        );
                    }
                    ?>

                    </h4>
                    <?php 
                        $rate_cacl = array();
                        $listing_type_id = get_post_meta( $comment->comment_post_ID, ESB_META_PREFIX.'listing_type_id', true );
                        $rating_fields = townhub_addons_get_rating_fields($listing_type_id);
                        if (!empty($rating_fields)) {
                            foreach ((array)$rating_fields as $key => $field) {
                                $rate_cacl[] = get_comment_meta($comment->comment_ID,ESB_META_PREFIX.$field['fieldName'], true);
                            }
                        }
                        if (!empty($rate_cacl)){
                            $total_rating = round((array_sum($rate_cacl) / count($rate_cacl)), 1, PHP_ROUND_HALF_UP);
                        }
                    ?>
                    <?php 
                    if(townhub_addons_get_option('single_show_rating') ):
                        if(!empty($total_rating) && $total_rating > 0):
                    ?>
                    <div class="review-score-user">
                        <span class="review-score-user_item"><?php echo $total_rating; ?></span>
                        <div class="listing-rating card-popup-rainingvis" data-rating="<?php echo esc_attr($total_rating);?>" data-stars="<?php echo esc_attr( townhub_addons_get_option('rating_base') ); ?>"></div>
                    </div>
                    <?php 
                        endif;
                    endif;?>

                    
                </div>
                <?php comment_text(); ?>
                <?php 
                $rating_imgs = get_comment_meta( $comment->comment_ID, ESB_META_PREFIX.'images', true );
                // var_dump($rating_imgs);
                if( townhub_addons_get_option('allow_rating_imgs') == 'yes' && !empty($rating_imgs) ):
                ?>
                    <div class="review-images lightgallery review-images-<?php echo townhub_addons_get_option('submit_media_limit', 3); ?>">
                        
                        <?php 
                        foreach ( (array)$rating_imgs as $id => $url ) {
                            ?>
                            <a href="<?php echo wp_get_attachment_url( $id ); ?>" class="popup-image review-image">
                                <?php echo wp_get_attachment_image( $id, 'townhub-recent', false, array('class'=>'resp-img') ); ?>
                            </a>
                        <?php
                        }
                        ?>
                    </div>
                <?php endif;?>
                <div class="reviews-comments-item-footer fl-wrap flex-items-center">
                    <div class="reviews-comments-item-date"><span><i class="far fa-calendar-check"></i><?php echo get_comment_date( get_option( 'date_format' ) .' '. get_option( 'time_format' ) ); ?></span></div>
                    <span class="review-item-reply"><?php comment_reply_link(array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?></span>
                    <?php echo townhub_addons_comment_like_button( $comment->comment_ID, 1 ); ?>
                </div>
                <?php
                if ($comment->comment_approved == '0'): ?>
                        <em class="comment-awaiting-moderation alignleft"><?php
                    esc_html_e('The comment is awaiting moderation.', 'townhub-add-ons'); ?></em>
                        <br />
                    <?php
                endif; ?> 
            </div>       
        <?php
        if ('div' != $args['style']): ?>
        </div> 
        <?php
        endif; ?>

    <?php
    }
}
function townhub_addons_rating_text($score = ''){
    if((int)townhub_addons_get_option('rating_base') == 10) $score /= 2;
    $score = floatval($score);
    $score_text = __( 'No rating', 'townhub-add-ons' );
    if($score >= 5) 
        $score_text = __( 'Very Good', 'townhub-add-ons' );
    elseif($score >= 4) 
        $score_text = __( 'Good', 'townhub-add-ons' );
    elseif($score >= 3) 
        $score_text = __( 'Pleasant', 'townhub-add-ons' );
    elseif($score >= 2) 
        $score_text = __( 'Bad', 'townhub-add-ons' );
    elseif($score >= 0.5) 
        $score_text = __( 'Very Bad', 'townhub-add-ons' );

    return $score_text;
}
function townhub_addons_rating_score($score = '', $classes = 'review-score-user'){
    if (!empty($score) && is_numeric($score) ): ?>
        <div class="<?php echo esc_attr( $classes ); ?>">
            <span class="review-value"><?php echo $score; ?></span>
            <strong class="review-text"><?php echo townhub_addons_rating_text($score); ?></strong>
        </div>
    <?php endif;
}
