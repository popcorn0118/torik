<?php
/* add_ons_php */
$show_counter = $hide_title = '';
$hide_status = '';
$hide_rating = $hide_cats = $hide_author = $hide_views = $hide_bookmarks = $hide_address = $hide_phone = $hide_email = $show_logo = $show_web = $disable_address_url = '';
if(!isset($azp_attrs)) $azp_attrs = array();
extract($azp_attrs);
?>
<div class="list-single-header-item  fl-wrap single-head-top flex-items-center">
	<div class="single-head-top-left">
		<?php if($hide_title != 'yes'): ?><h1>
        	<?php the_title( ) ;?>
            <?php if( get_post_meta( get_the_ID(), ESB_META_PREFIX.'verified', true ) == '1' ): ?><span class="verified-badge"><i class="fal fa-check"></i></span><?php endif; ?>
        	<?php townhub_addons_edit_listing_link(get_the_ID());?>
        </h1>
        <?php endif; ?>
        <?php 
        $phone = get_post_meta( get_the_ID(), ESB_META_PREFIX.'phone', true );
        $email = get_post_meta( get_the_ID(), ESB_META_PREFIX.'email', true );
        $website = get_post_meta( get_the_ID(), ESB_META_PREFIX.'website', true );
        $address = get_post_meta( get_the_ID(), ESB_META_PREFIX.'address', true );
        $latitude = get_post_meta( get_the_ID(), ESB_META_PREFIX.'latitude', true );
        $longitude = get_post_meta( get_the_ID(), ESB_META_PREFIX.'longitude', true );
        if($phone != '' || $email != '' || $website != '' || $address != '' ):

            $address_url = 'javascript:void(0);';
            if( $disable_address_url != 'yes' && !empty($latitude) && !empty($longitude) ) $address_url = 'https://www.google.com/maps/search/?api=1&query='.esc_attr($latitude).','.esc_attr($longitude);
        ?>
        <div class="geodir-category-location fl-wrap">
        	<?php if( $hide_address != 'yes' && $address != '' ): ?><a href="<?php echo $address_url;?>" target="_blank"><i class="fas fa-map-marker-alt"></i><?php echo esc_html($address); ?></a><?php endif; ?>
        	<?php if( $hide_phone != 'yes' && $phone != '' ): ?><a href="tel:<?php echo esc_attr( $phone );?>"> <i class="fal fa-phone"></i><?php echo esc_html($phone) ?></a><?php endif; ?>
        	<?php if( $hide_email != 'yes' && $email != '' ): ?><a href="mailto:<?php echo esc_attr( $email ); ?>"><i class="fal fa-envelope"></i><?php echo esc_html($email) ?></a><?php endif; ?>
            <?php if( $show_web == 'yes' && !empty($website) ): ?><a href="<?php echo esc_url( $website ); ?>" target="_blank"><i class="fal fa-globe"></i><?php echo esc_html($website) ?></a><?php endif; ?>
        </div>
        <?php endif; ?>

        <?php do_action( 'cth_listing_header_left' ); ?>
    </div>
    
    <div class="single-head-top-right">
        <?php if( $hide_rating != 'yes' ): ?>
		<?php 
	    $rating = townhub_addons_get_average_ratings(get_the_ID());    ?>
	    <?php if( $rating != false ): ?>
	        <div class="single-head-review-wrap">
	            <a class="custom-scroll-link review-comments-link" href="#lreviews_sec">
                    <div class="listing-rating-count-wrap single-list-count flex-items-center">
                        <div class="review-score"><?php echo $rating['sum']; ?></div>
                        <div class="review-details">
                        	<div class="listing-rating card-popup-rainingvis" data-rating="<?php echo $rating['sum']; ?>"></div>                                              
                            <div class="reviews-count"><?php echo sprintf( _nx( '%s review', '%s reviews', (int)$rating['count'], 'reviews count', 'townhub-add-ons' ), (int)$rating['count'] ); ?></div>
                        </div> 
                    </div>
                </a>
	        </div>
	    <?php endif; ?> 
        <?php endif; ?> 
        <?php do_action( 'cth_listing_header_right' ); ?>
    </div>
    
</div>

<div class="list-single-header_bottom fl-wrap dis-flex">
	<div class="single-head-bot-left flex-items-center">
		<?php
        if( $hide_cats != 'yes' ){ 
            $cats = get_the_terms(get_the_ID(), 'listing_cat');
            if ( $cats && ! is_wp_error( $cats ) ){ ?>
                <div class="listing-cats-wrap dis-flex">
                    <?php 
                    foreach( $cats as $key => $cat){
                        $term_metas = townhub_addons_custom_tax_metas($cat->term_id); 
                        echo sprintf( '<a href="%1$s" class="listing-item-category-wrap flex-items-center">%3$s<span>%2$s</span></a> ',
                            townhub_addons_get_term_link( $cat->term_id, 'listing_cat' ),
                            esc_html( $cat->name ),
                            ($term_metas['icon'] != '' ? '<div class="listing-item-category '.$term_metas['color'].'"><i class="'.$term_metas['icon'].'"></i></div>' : ''),
                            $term_metas['color']
                        );
                    }
                    ?>
                </div>
            <?php } 
        } ?>
        <?php if( $hide_author != 'yes' ){ ?>
        <div class="list-single-author">
        	<a class="flex-items-center" href="<?php echo get_author_posts_url(get_the_author_meta('ID'), get_the_author_meta('user_nicename')); ?>"><span class="author_avatar"><?php
	        echo get_avatar(get_the_author_meta('user_email'), '80', 'https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=80', get_the_author_meta('display_name'));
	        ?></span><?php echo sprintf( __('By %s', 'townhub-add-ons'), get_the_author() ); ?></a>
        </div>
        <?php } ?>
        <?php 
        if( $show_logo == 'yes' ){ 
            $llogo = get_post_meta( get_the_ID(), ESB_META_PREFIX.'llogo', true );
            if( !empty($llogo) ){
                if( !is_array($llogo) ) $llogo = explode(",", $llogo);
        ?>
        <div class="list-single-logo">
            <?php echo wp_get_attachment_image( $llogo[0], 'thumbnail', false, array('class'=>'llogo-img') );  ?>
        </div>
        <?php }
        } ?>
		<?php townhub_addons_get_template_part( 'templates-inner/status', '', array( 'show_counter' => $show_counter, 'hide_status' => $hide_status ) ); ?>
        <?php do_action( 'cth_listing_bottom_left' ); ?>
	</div>
    <div class="single-head-bot-right">
		<div class="list-single-stats">
            <ul class="no-list-style dis-flex">
                <?php if( $hide_views != 'yes' ){ ?>
                <li><span class="viewed-counter"><i class="fas fa-eye"></i><?php echo sprintf(__( ' Viewed - %s', 'townhub-add-ons' ), Esb_Class_LStats::get_stats(get_the_ID()) ); ?></span></li>
                <?php } ?>
                <?php 
                if( $hide_bookmarks != 'yes' ){
                    $bookmark_count = Esb_Class_Listing_CPT::get_bookmark_count( get_the_ID() ); 
                    if( !empty($bookmark_count) ): ?>
                    <li><span class="bookmark-counter"><?php echo sprintf( __( '<i class="fas fa-heart"></i> Bookmark -  %d', 'townhub-add-ons' ), intval($bookmark_count) ); ?></span></li>
                    <?php 
                    endif; 
                } ?>
            </ul>
        </div>
        <?php do_action( 'cth_listing_bottom_right' ); ?>
    </div>
    
        
</div>
