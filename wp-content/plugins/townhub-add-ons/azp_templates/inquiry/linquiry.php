<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $hide_widget_on = $title = '';
// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'linquiry',
    'azp-element-' . $azp_mID,
    $el_class,
);
$classes = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $classes ) ) );

if($el_id!=''){
    $el_id = 'id="'.$el_id.'"';
}
if( $hide_not_claimed == 'yes' && get_post_meta( get_the_ID() , ESB_META_PREFIX.'verified', true ) !== '1' ) return;
// array(5) { ["checkout"]=> string(10) "2018-12-20" ["checkin"]=> string(10) "2018-12-19" ["lb_adults"]=> string(1) "1" ["lb_children"]=> string(1) "0" ["rooms"]=> array(1) { [5174]=> string(1) "2" } }
if(( $hide_widget_on_check = townhub_addons_is_hide_on_plans($hide_widget_on) ) !== 'true') :
    $curr_attrs = townhub_addons_get_currency_attrs();
    
$max_guests = townhub_addons_listing_max_guests();
?>
<div class="<?php echo $classes; ?> authplan-hide-<?php echo $hide_widget_on_check;?>" <?php echo $el_id;?>>
    <div class="for-hide-on-author"></div>

    <!--box-widget-item -->
    <div class="box-widget-item fl-wrap block_box" id="widget-general-booking">
        <?php if($title != ''): ?>
        <div class="box-widget-item-header">
            <h3><?php echo $title; ?></h3>
        </div>
        <?php endif; ?>
        <div class="box-widget opening-hours fl-wrap">
            <div class="box-widget-content">
                
                <div class="general-booking-inner box-widget-content">
                    <form method="POST" class="inquiry-booking-formxs custom-form" enctype="multipart/form-data"> 
                        
                        <?php echo townhub_addons_azp_parser_listing( get_post_meta( get_the_ID(), ESB_META_PREFIX.'listing_type_id', true ) , 'booking_from', get_the_ID() );?>
                        
                        <?php //if( $bprice != 'none' ): ?>
                        <div class="total-coast fl-wrap clearfix">
                            <strong><?php _e( 'Total Cost', 'townhub-add-ons' ); ?></strong>
                            <span>
                                <?php if($curr_attrs['sb_pos'] == 'left_space') echo $curr_attrs['symbol']."&nbsp;"; ?><?php if($curr_attrs['sb_pos'] == 'left') echo $curr_attrs['symbol']; ?>
                                <input readonly class="total-cost-input" type="text" name="grand_total" value="" data-jcalc="SUM({item_total})" size="5">
                                <?php if($curr_attrs['sb_pos'] == 'right_space') echo "&nbsp;".$curr_attrs['symbol']; ?><?php if($curr_attrs['sb_pos'] == 'right') echo $curr_attrs['symbol']; ?>
                            </span>
                        </div>
                        <?php //endif; ?>
                        <div class="booking-buttons">
                            <button class="btn big-btn color-bg flat-btn book-btn"  type="submit"><?php esc_html_e( 'Book Now', 'townhub-add-ons' ); ?><i class="fa fa-angle-right"></i></button>
                        </div>

                        <input type="hidden" name="booking_type" value="general">
                        <input type="hidden" name="price_based" value="<?php echo esc_attr( $bprice ); ?>">
                        <input type="hidden" name="product_id" value="<?php the_ID(); ?>">
                        <input type="hidden" name="action" value="esb_add_to_cart">
                        <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('townhub-add-to-cart'); ?>">
                        
                    </form>

                </div>

            </div>
        </div>
    </div>
    <!--box-widget-item end --> 

</div>
<?php
endif; 