<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $title = $sec_id = $cols = $hide_widget_on = ''; 

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
    'azp_element',
	'ltickets',
	'azp-element-' . $azp_mID,
    $el_class,
);
// $animation_data = self::buildAnimation($azp_attrs); 
// $classes[] = $animation_data['trigger'];
// $classes[] = self::buildTypography($azp_attrs);//will return custom class for the element without dot
// $azplgallerystyle = self::buildStyle($azp_attrs);

$classes = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $classes ) ) );  

if($el_id!=''){
    $el_id = 'id="'.$el_id.'"';
}
if(( $hide_widget_on_check = townhub_addons_is_hide_on_plans($hide_widget_on) ) !== 'true') :
// $tickets = get_post_meta( get_the_ID(), ESB_META_PREFIX.'tickets', true );
$tickets = Esb_Class_Booking_CPT::get_tickets(get_the_ID());
if ( is_array( $tickets) && !empty($tickets)) {
?>
<div class="<?php echo $classes; ?> authplan-hide-<?php echo $hide_widget_on_check;?>" <?php echo $el_id;?>>
    <div class="for-hide-on-author"></div>
    <!-- lsingle-block-box --> 
    <div class="lsingle-block-box lsingle-block-full mb-0">
        <?php if($title != ''): ?>
        <div class="lsingle-block-title">
            <h3><?php echo $title; ?></h3>
        </div>
        <?php endif; ?>
        <div class="lsingle-block-content">
            <div class="lsingle-tickets <?php echo $cols ?>-cols">
                <?php 
                foreach( $tickets as $key => $ticket): 
                    $excls = 'green-bg';
                    if( $key % 2 == 0 ) $excls = 'color-bg';
                ?>
                <!-- inline-tickets -->
                <div class="inline-ticket-wrap flex-fact-wrap">

                    <div class="inline-ticket-inner">
                        <div class="evticket-details <?php echo esc_attr( $excls ); ?>">
                            <div class="evticket-icon"><i class="fal fa-ticket"></i></div>
                            <h6 class="evticket-name"><?php echo $ticket['name']; ?></h6>
                            <div class="evticket-desc"><?php echo $ticket['desc']; ?></div>
                        </div>
                        <div class="evticket-meta">
                            <div class="evticket-price"><?php echo townhub_addons_get_price_formated( $ticket['price'] ); ?></div>
                            <div class="evticket-available"><?php echo sprintf(__( 'Available: <span>%d</span>', 'townhub-add-ons' ), (int)$ticket['available'] ); ?></div>
                            
                        </div>
                        
                        
                    </div>
                    
                </div>
                <!-- inline-tickets end -->
            <?php
            endforeach; ?>
            </div>
        </div>
    </div>
    <!-- lsingle-block-box end -->  
</div>
<?php 
    }

endif;// check hide on plans
