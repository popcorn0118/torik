<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $title = $images_to_show = $show_contact =  $hide_widget_on = $show_claim = $hide_on_claimed = '';

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'lhostedby',
    'azp-element-' . $azp_mID, 
    $el_class,
);

$lverified = get_post_meta( get_the_ID() , ESB_META_PREFIX.'verified', true );

if( $hide_on_claimed == 'yes' && $lverified  === '1' ) return;

$classes = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $classes ) ) );

if($el_id!=''){
    $el_id = 'id="'.$el_id.'"';
}
if(( $hide_widget_on_check = townhub_addons_is_hide_on_plans($hide_widget_on) ) !== 'true') :
?>
<div class="<?php echo $classes; ?> authplan-hide-<?php echo $hide_widget_on_check;?>" <?php echo $el_id;?>>
    <div class="for-hide-on-author"></div>

	<!--box-widget-item -->
    <div class="box-widget-item fl-wrap block_box">
        <?php if($title != ''): ?>
        <div class="box-widget-item-header">
            <h3><?php echo $title; ?></h3>
        </div>
        <?php endif; ?>
        <?php 
	    $author_ID = get_the_author_meta( 'ID' );
	    ?>
        <div class="box-widget">
            <div class="box-widget-author fl-wrap">
                <div class="box-widget-author-title">
                    <div class="box-widget-author-title-img">
                        <?php 
			                echo get_avatar(get_the_author_meta('user_email'),'150','https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=150', get_the_author_meta('display_name') );
			            ?> 
                    </div>
                    <div class="box-widget-author-title_content">
                        <a href="<?php echo get_author_posts_url( $author_ID ); ?>"><?php echo get_the_author_meta('display_name');?></a>
                        <span><?php echo sprintf(__( '%d Places Hosted', 'townhub-add-ons' ), count_user_posts( $author_ID , "listing" , true ) ) ?></span>
                    </div>
                    <div class="box-widget-author-title_opt">
                        <a href="<?php echo get_author_posts_url( $author_ID ); ?>" class="tolt green-bg" data-microtip-position="top" data-tooltip="<?php esc_attr_e( 'View Profile', 'townhub-add-ons' ); ?>"><i class="fas fa-user"></i></a> 
                        <?php if( townhub_addons_get_option('show_fchat') == 'yes' ): ?>
                        <a href="#" class="tolt color-bg cwb" data-microtip-position="top" data-tooltip="<?php esc_attr_e( 'Chat With Owner', 'townhub-add-ons' ); ?>"><i class="fas fa-comments-alt"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if( $show_claim == 'yes' && $lverified !== '1' ): ?>
            <div class="claim-widget-link fl-wrap">
                <?php _e( '<span>Own or work here?</span>', 'townhub-add-ons' ); ?>
                <?php if(is_user_logged_in()) : ?>
                <a class="open-listing-claim" href="#">
                <?php else : 
                    $logBtnAttrs = townhub_addons_get_login_button_attrs( 'claim', 'current' );
                ?>
                <a class="<?php echo esc_attr( $logBtnAttrs['class'] );?>" href="<?php echo esc_url( $logBtnAttrs['url'] );?>" data-message="<?php esc_attr_e( 'You must be logged in to claim listing.', 'townhub-add-ons' ); ?>">
                <?php endif; ?>
                    <?php _e( 'Claim Now!', 'townhub-add-ons' ); ?>
                </a>
                
            </div>
            <?php endif; ?>

        </div>
    </div>
    <!--box-widget-item end -->  

</div>
<?php endif;

