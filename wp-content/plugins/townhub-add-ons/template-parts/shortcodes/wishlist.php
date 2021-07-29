<?php
/* add_ons_php */
$listing_bookmarks = (array)get_user_meta( get_current_user_id(), ESB_META_PREFIX.'listing_bookmarks', true );
$listing_bookmarks = array_filter($listing_bookmarks);
$bmcount = count($listing_bookmarks);
if( $bmcount ){
    $post_args = array(
        'post_type'         => 'listing',
        'post__in'          => $listing_bookmarks,
        'posts_per_page'    => -1,
        'orderby'           => 'post__in',
        'post_status'       => 'publish'
    );
    $posts_query = new WP_Query($post_args);
    $bmcount = $posts_query->found_posts;
}
?>
<div class="cart-btn bookmark-header-btn show-header-modal" data-microtip-position="bottom" role="tooltip" aria-label="<?php esc_attr_e( 'Your Wishlist', 'townhub-add-ons' ); ?>"><i class="fal fa-heart"></i><span class="cart-counter bmcounter-head green-bg"><?php echo $bmcount; ?></span></div>

<!-- wishlist-wrap--> 
<div class="header-modal novis_wishlist">
    <!-- header-modal-container--> 
    <div class="header-modal-container scrollbar-inner fl-wrap" data-simplebar>
        <!--widget-posts-->
        <div class="widget-posts  fl-wrap">
            <ul class="no-list-style wishlist-items-wrap">
            <?php
            if( !empty($listing_bookmarks) && $posts_query->have_posts() ){
                while ( $posts_query->have_posts() ) {
                    $posts_query->the_post(); 
                    townhub_addons_get_template_part( 'templates-inner/bookmark', 'item' );
                }
            }else{
                ?>
                <li class="wishlist-item no-bookmark-wrap">
                    <p><?php echo _e( 'You have no bookmark.', 'townhub-add-ons' ); ?></p>
                </li>
            <?php
            }

            wp_reset_postdata();
            
            ?>
            </ul>
        </div>
        <!-- widget-posts end-->
    </div>
    <!-- header-modal-container end--> 
    <div class="header-modal-top fl-wrap">
        <h4><?php _e( 'Your Wishlist : ', 'townhub-add-ons' ); ?><span class="bmcounter-bot"><?php echo sprintf( _n( '<strong>%s</strong> listing', '<strong>%s</strong> listings', $bmcount, 'townhub-add-ons' ), $bmcount ); ?></span></h4>
        <div class="close-header-modal"><i class="far fa-times"></i></div>
    </div>
</div>
<!--wishlist-wrap end -->