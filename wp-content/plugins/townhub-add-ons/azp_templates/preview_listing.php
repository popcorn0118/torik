<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $hide_status = $hide_bookmark = $hide_overlay = $hide_author = $hide_rating = $hide_saleoff = $hide_featured = $show_logo = '';

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
    'azp_element',
    'preview_listing',
    'azp-element-' . $azp_mID,
    'geodir-category-img',
    $el_class,
);
// $animation_data = self::buildAnimation($azp_attrs);
// $classes[] = $animation_data['trigger'];
// $classes[] = self::buildTypography($azp_attrs);//will return custom class for the element without dot
// $azplgallerystyle = self::buildStyle($azp_attrs);

$classes = preg_replace('/\s+/', ' ', implode(' ', array_filter($classes)));

if ($el_id != '') {
    $el_id = 'id="' . $el_id . '"';
}
if( $hide_overlay == 'yes' ) $classes .= ' card-hide-overlay';
// if (has_post_thumbnail()) {
?>
<div class="<?php echo $classes; ?>" <?php echo $el_id; ?>>
    <?php if( $hide_bookmark != 'yes' ): ?>
    <div class="geodir-js-favorite_btn">
        <?php if(!is_user_logged_in()): 
            $logBtnAttrs = townhub_addons_get_login_button_attrs( 'savelist', 'current' );
        ?>
            <a href="<?php echo esc_url( $logBtnAttrs['url'] );?>" class="save-btn <?php echo esc_attr( $logBtnAttrs['class'] );?>" data-message="<?php esc_attr_e( 'Logging in first to save this listing.', 'townhub-add-ons' ); ?>"><i class="fal fa-heart"></i><span><?php _e( 'Save', 'townhub-add-ons' ); ?></span></a>
        <?php elseif( townhub_addons_already_bookmarked(get_the_ID()) ): ?>
            <a href="javascript:void(0);" class="save-btn" data-id="<?php the_ID(); ?>"><i class="fas fa-heart"></i><span><?php _e( 'Saved', 'townhub-add-ons' ); ?></span></a>
        <?php else: ?>
            <a href="#" class="save-btn bookmark-listing-btn" data-id="<?php the_ID(); ?>" ><i class="fal fa-heart"></i><span><?php _e( 'Save', 'townhub-add-ons' ); ?></span></a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if( $hide_featured != 'yes' && get_post_meta( get_the_ID(), ESB_META_PREFIX.'featured', true ) == '1' ) : ?>
        <div class="listing-featured"><?php _e( 'Featured', 'townhub-add-ons' ); ?></div>
    <?php endif;?>
        

    <a href="<?php the_permalink();?>" class="listing-thumb-link geodir-category-img-wrap fl-wrap">
        
        <?php 
        echo wp_get_attachment_image( townhub_addons_get_listing_thumbnail( get_the_ID() ) , 'townhub-listing-grid', false, array('class'=>'respimg') );
        ?>

        <?php if( $hide_overlay != 'yes' ): ?><div class="overlay"></div><?php endif; ?>
    </a>
    <?php if( $hide_author != 'yes' ): ?>
    <div class="listing-avatar"><a href="<?php echo get_author_posts_url(get_the_author_meta('ID'), get_the_author_meta('user_nicename')); ?>"><?php
        echo get_avatar(get_the_author_meta('user_email'), '80', 'https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=80', get_the_author_meta('display_name'));
        ?></a>
        <span class="avatar-tooltip lpre-avatar"><?php echo sprintf(__('Added By <strong>%s</strong>', 'townhub-add-ons'), get_the_author()); ?></span>
    </div>
    <?php endif; ?>

    <?php 
    if( $show_logo == 'yes' ){ 
        $llogo = get_post_meta( get_the_ID(), ESB_META_PREFIX.'llogo', true );
        if( !empty($llogo) ){
            if( !is_array($llogo) ) $llogo = explode(",", $llogo);
    ?>
    <div class="lcard-logo lcard-grid">
        <a class="lcard-logo-link" href="<?php the_permalink();?>"><?php echo wp_get_attachment_image( $llogo[0], 'thumbnail', false, array('class'=>'llogo-img') );  ?></a>
    </div>
    <?php }
    } ?>

    <?php 
    if( $hide_saleoff != 'yes' ):
    $saleoff = get_post_meta( get_the_ID(), ESB_META_PREFIX.'sale_off', true );
    if( !empty($saleoff) ): ?>
        <div class="lcard-saleoff">
            <div class="saleoff-inner"><?php echo sprintf( esc_html__( "Sale %s%%", 'townhub-add-ons' ) , $saleoff ); ?></div>
        </div>
    <?php endif;
    endif; ?> 

    
    
    <?php townhub_addons_get_template_part( 'templates-inner/status', '', array( 'hide_status'=> $hide_status ) ); ?>

    
     
    <?php 
    if( $hide_rating != 'yes' ):
    $rating = townhub_addons_get_average_ratings(get_the_ID());    ?>
    <?php if( $rating != false ): ?>
        <div class="geodir-category-opt clearfix">
            
            <div class="listing-rating-count-wrap flex-items-center">
                <div class="review-score"><?php echo $rating['sum']; ?></div>
                <div class="review-details">
                    <div class="listing-rating card-popup-rainingvis" data-rating="<?php echo $rating['sum']; ?>"></div>
                    <div class="reviews-count"><?php echo sprintf( _nx( '%s comment', '%s comments', (int)$rating['count'], 'comments count', 'townhub-add-ons' ), (int)$rating['count'] ); ?></div>
                </div>
            </div>
        </div>
    <?php endif;
    endif; ?>  
    <?php do_action( 'cth_listing_card_thumbnail' ); ?>
    <div class="lcfields-wrap dis-flex"><?php echo wp_kses_post( $azp_content ); ?></div>
    
</div>
<?php // }
