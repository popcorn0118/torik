<?php
/* add_ons_php */
if(!townhub_addons_get_option('single_show_rating')) return;
$rating = townhub_addons_get_average_ratings(get_the_ID());       
if($rating):
?>
<div class="listing-rating card-popup-rainingvis" data-rating="<?php echo esc_attr( $rating['rating'] );?>" data-stars="<?php echo esc_attr( townhub_addons_get_option('rating_base') ); ?>">
    <span>(<?php echo esc_html( $rating['count'] );?><?php esc_html_e( ' reviews',  'townhub-add-ons' );?>)</span>
</div>
<?php else : ?>
<div class="listing-rating">
    <span><?php esc_html_e( 'Not review yet', 'townhub-add-ons' );?></span>
</div>
<?php endif;
