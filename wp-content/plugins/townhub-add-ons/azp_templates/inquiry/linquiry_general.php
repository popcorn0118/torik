<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $hide_widget_on = $title = $dformat = $tformat = $ticon = $dlabel = $tlabel = $sllable = $dicon = ''; 
$checkin_show = $checkout_show = $tpicker_show = $slots_show = $bprice = '';
$adult_show = $adult_lbl = $adult_desc = $child_show = $child_lbl = $child_desc = $infant_show = $infant_lbl = $infant_desc = '';
$show_name = $show_email = $show_phone = $show_notes = '';
$show_lservices = $quanity_lservices = $show_quantity = $qtt_lbl = $qtt_desc = $qtt_default = $show_total_cost = $show_menu = $menu_lbl = $menu_desc = '';
$slots_single = $hide_dfdate = $hide_if_no_price = $ckin_required = $show_upload_cv = $hide_not_claimed = $cin_plhd = $dates_source = $ad_note = $hide_dmetas = '';
$ticket_lbl = $show_tickets = '';
// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'linquiry_general',
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
    $symbol_multiple = sprintf( __( '%s<span class="multiplication">x</span>', 'townhub-add-ons' ), $curr_attrs['symbol'] );
    $max_guests = townhub_addons_listing_max_guests();

    $listing_price = get_post_meta( get_the_ID(), '_price', true );

    if( $hide_if_no_price == 'yes' && empty($listing_price) ) return;

    $listing_type_id = get_post_meta( get_the_ID(), ESB_META_PREFIX.'listing_type_id', true );
    $bprice = get_post_meta( $listing_type_id, ESB_META_PREFIX.'price_based', true );
    if($bprice === '') $bprice = 'listing';

    $id_prefix = uniqid('preid');

    $checkin_get = isset($_GET['checkin']) ? $_GET['checkin'] : '';
    $checkout_get = isset($_GET['checkout']) ? $_GET['checkout'] : '';
    $default_value = !empty($checkin_get) && !empty($checkout_get) ? esc_attr($checkin_get).';'.esc_attr($checkout_get) : 'current';

    $current_user = wp_get_current_user();
    
    $loggedName = '';
    $loggedEmail = '';
    $loggedPhone = '';
    if( is_user_logged_in() ){
        $loggedName = $current_user->display_name;
        $loggedEmail = get_user_meta($current_user->ID,  ESB_META_PREFIX.'email', true );
        if( empty($loggedEmail) ) $loggedEmail = $current_user->user_email;
        $loggedPhone = get_user_meta($current_user->ID,  ESB_META_PREFIX.'phone', true );
    }

    $df_checkin_date = '';
    if( $hide_dfdate == 'yes' ) $df_checkin_date = 'hide';

    $min_nights = get_post_meta( get_the_ID(), ESB_META_PREFIX.'min_nights', true );
    if( empty($min_nights) ) $min_nights = 1;
?>
<div class="<?php echo $classes; ?> authplan-hide-<?php echo $hide_widget_on_check;?>" <?php echo $el_id;?>>
    <div class="for-hide-on-author"></div>

    <!--box-widget-item -->
    <div class="box-widget-item fl-wrap block_box" id="widget-inquiry-general">
        <?php if($title != ''): ?>
        <div class="box-widget-item-header">
            <h3><?php echo $title; ?></h3>
        </div>
        <?php endif; ?>
        <div class="box-widget opening-hours fl-wrap">
            <div class="box-widget-content">
                
                <div class="inquiry-general-inner box-widget-content">
                    <form method="POST" class="listing-booking-formxs" enctype="multipart/form-data" name="esb-inquiry-form"> 

                        <?php 
                        $is_logged_in = is_user_logged_in();
                        if ( $is_logged_in === false || $show_name === 'yes' ) { ?>
                            <div class="fl-wrap">
                                <label class="lbl-hasIcon"><i class="fal fa-user"></i></label>
                                <input name="lb_name" class="has-icon" type="text" placeholder="<?php esc_attr_e( 'Your Name*', 'townhub-add-ons' ); ?>" value="<?php echo esc_attr( $loggedName ); ?>" required="required">
                            </div>
                        <?php } 
                        if ( $is_logged_in === false || $show_email === 'yes' ) { ?>
                            <div class="fl-wrap">
                                <label class="lbl-hasIcon"><i class="fal fa-envelope"></i></label>
                                <input name="lb_email" class="has-icon" type="text" placeholder="<?php esc_attr_e( 'Email Address*', 'townhub-add-ons' ); ?>" value="<?php echo esc_attr( $loggedEmail ); ?>" required="required">
                            </div>
                        <?php } 
                        if ( $is_logged_in === false || $show_phone === 'yes' ) { ?>
                            <div class="fl-wrap">
                                <label class="lbl-hasIcon"><i class="fal fa-phone"></i></label>
                                <input name="lb_phone" class="has-icon" type="text" placeholder="<?php esc_attr_e( 'Phone', 'townhub-add-ons' ); ?>" value="<?php echo esc_attr( $loggedPhone ); ?>" required="required">
                            </div>
                        <?php } ?>


                        <?php if( $checkin_show === '1' && $checkout_show === '1' ): 
                            
                        ?>
                        <div class="cth-daterange-picker"
                            data-name="checkin" 
                            data-name2="checkout" 
                            data-format="<?php echo $dformat; ?>" 
                            data-default="<?php echo esc_attr( $default_value ); ?>"
                            data-label="<?php echo esc_attr( $dlabel ); ?>" 
                            data-icon="<?php echo $dicon;?>" 
                            data-selected="general_daterange"
                            data-action="<?php echo $dates_source;?>" 
                            data-postid="<?php the_ID();?>" 
                            data-mnights="<?php echo intval( $min_nights ); ?>"
                        ></div>
                        
                        <?php elseif( $checkin_show === '1' ): ?>
                        <div class="cth-date-picker-wrap esb-field has-icon">
                            <div class="lfield-header">
                                <?php if( $dlabel != '' ): ?><label class="lfield-label"><?php echo esc_html( $dlabel ); ?></label><?php endif; ?>
                                <?php if( $dicon != '' ): ?><span class="lfield-icon"><i class="<?php echo esc_attr( $dicon ); ?>"></i></span><?php endif; ?>
                            </div>
                            <div class="cth-date-picker<?php if( $hide_dmetas == 'yes' ) echo ' cth-date-picker-hide-metas'; ?>" 
                                data-name="checkin" 
                                data-format="<?php echo $dformat; ?>" 
                                data-default="<?php echo esc_attr( $df_checkin_date ); ?>"
                                data-action="<?php echo $dates_source;?>" 
                                data-postid="<?php the_ID();?>" 
                                data-selected="general_date"
                                data-required="<?php echo esc_attr( $ckin_required ); ?>"
                                data-placeholder="<?php echo esc_attr( $cin_plhd ); ?>"
                            ></div>
                        </div>
                        <?php endif; ?>

                        <div class="price-days-nights hid-input">
                            <input class="hid-input jscal-nights" readonly="readonly" type="text" name="nights" value="<?php echo intval( $min_nights ); ?>">
                            <input class="hid-input jscal-days" readonly="readonly" type="text" name="days" value="1">
                            <input class="hid-input jscal-price" readonly="readonly" type="text" name="lprice" value="<?php echo townhub_addons_get_price( $listing_price ); ?>">
                        
                            <?php if($bprice == 'per_night'): ?>
                                <input class="hid-input" type="text" name="item_total" value="" data-jcalc="{nights} * {lprice}">
                            <?php endif; ?>
                            <?php if($bprice == 'per_day'): ?>
                                <input class="hid-input" type="text" name="item_total" value="" data-jcalc="{days} * {lprice}">
                            <?php endif; ?>
                        </div>
                        
                        <?php if($tpicker_show === '1'): ?>
                        <label class="tpicker-label"><?php echo esc_html( $tlabel ); ?></label>
                        <div class="cth-time-picker booking-time-picker" data-name="times[]" data-format="<?php echo $tformat; ?>" data-icon="<?php echo esc_attr( $ticon ); ?>"></div>
                        <?php endif; ?>

                        <?php 
                        if($slots_show === '1'): 
                            $slotsNumID = uniqid();
                        ?>
                            <div class="cth-bk-tslots" 
                                data-postid="<?php the_ID();?>" 
                                data-label="<?php echo esc_attr( $sllable );?>"
                                data-single="<?php echo esc_attr( $slots_single );?>"
                                data-required="yes" 
                                data-countid="<?php echo esc_attr( $slotsNumID ); ?>"
                            ></div>
                            <?php 
                            echo '<input id="'.esc_attr( $slotsNumID ).'" class="hid-input jscal-bkslots" readonly="readonly" type="text" name="bkslots" value="0">';
                            if( $bprice == 'per_hour' ){
                                // echo '<input id="'.esc_attr( $slotsNumID ).'" class="hid-input jscal-bkslots" readonly="readonly" type="text" name="bkslots" value="0">';
                                echo '<input class="hid-input" type="text" name="item_total" value="" data-jcalc="{bkslots} * {lprice}">';
                            } ?>
                        <?php
                        endif; ?>

                        <?php if($adult_show === '1'): ?>
                        <div class="qtt-item qtt-item-full qtt-item-js fl-wrap m-b20">
                            <div class="qtt-label-wrap">
                                <?php if($adult_lbl != ''): ?><label class="qtt-label"><?php echo $adult_lbl; ?></label><?php endif; ?>
                                <?php if($adult_desc != ''): ?><span><?php echo $adult_desc; ?></span><?php endif; ?>
                            </div>
                            <?php if( $bprice == 'per_person' || $bprice == 'night_person' || $bprice == 'day_person' || $bprice == 'hour_person' ): ?>
                            <div class="qtt-price text-right">
                                <input class="hids-input jscal-price" readonly="readonly" type="text" name="price_adult" value="<?php echo townhub_addons_get_price_formated( $listing_price, false ); ?>"><?php echo $symbol_multiple; ?>
                            </div>
                            <?php endif; ?>
                            <div class="qtt-input">
                                <input type="number" name="adults" min="0" max="<?php echo $max_guests; ?>" step="1" value="1">
                                <div class="qtt-nav">
                                    <div class="qtt-btn qtt-up">+</div>
                                    <div class="qtt-btn qtt-down">-</div>
                                </div>
                            </div>
                            <?php if( $bprice == 'per_person' ): ?>
                            <input class="hid-input" type="text" name="item_total" value="" data-jcalc="{adults} * {price_adult}">
                            <?php elseif( $bprice == 'night_person' ): ?>
                            <input class="hid-input" type="text" name="item_total" value="" data-jcalc="{adults} * {price_adult} * {nights}">
                            <?php elseif( $bprice == 'day_person' ): ?>
                            <input class="hid-input" type="text" name="item_total" value="" data-jcalc="{adults} * {price_adult} * {days}">
                            <?php elseif( $bprice == 'hour_person' ): ?>
                            <input class="hid-input" type="text" name="item_total" value="" data-jcalc="{adults} * {price_adult} * {bkslots}">
                            <?php endif; ?>
                            
                        </div>
                        <?php endif; ?>
                        <?php if($child_show === '1'): ?>
                        <div class="qtt-item qtt-item-full qtt-item-js fl-wrap m-b20">
                            <div class="qtt-label-wrap">
                                <?php if($child_lbl != ''): ?><label class="qtt-label"><?php echo $child_lbl; ?></label><?php endif; ?>
                                <?php if($child_desc != ''): ?><span><?php echo $child_desc; ?></span><?php endif; ?>
                            </div>
                            <?php if( $bprice == 'per_person' || $bprice == 'night_person' || $bprice == 'day_person' || $bprice == 'hour_person' ): ?>
                            <div class="qtt-price text-right">
                                <input class="hids-input jscal-price" readonly="readonly" type="text" name="price_children" value="<?php echo townhub_addons_get_price_formated( get_post_meta( get_the_ID(), ESB_META_PREFIX .'children_price', true ), false ); ?>"><?php echo $symbol_multiple; ?>
                            </div>
                            <?php endif; ?>
                            <div class="qtt-input">
                                <input type="number" name="children" min="0" max="<?php echo $max_guests; ?>" step="1" value="0">
                                <div class="qtt-nav">
                                    <div class="qtt-btn qtt-up">+</div>
                                    <div class="qtt-btn qtt-down">-</div>
                                </div>
                            </div>

                            <?php if( $bprice == 'per_person' ): ?>
                            <input class="hid-input" type="text" name="item_total" value="" data-jcalc="{children} * {price_children}">
                            <?php elseif( $bprice == 'night_person' ): ?>
                            <input class="hid-input" type="text" name="item_total" value="" data-jcalc="{children} * {price_children} * {nights}">
                            <?php elseif( $bprice == 'day_person' ): ?>
                            <input class="hid-input" type="text" name="item_total" value="" data-jcalc="{children} * {price_children} * {days}">
                            <?php elseif( $bprice == 'hour_person' ): ?>
                            <input class="hid-input" type="text" name="item_total" value="" data-jcalc="{children} * {price_children} * {bkslots}">
                            <?php endif; ?>

                        </div>
                        <?php endif; ?>
                        <?php if($infant_show === '1'): ?>
                        <div class="qtt-item qtt-item-full qtt-item-js fl-wrap m-b20">
                            <div class="qtt-label-wrap">
                                <?php if($infant_lbl != ''): ?><label class="qtt-label"><?php echo $infant_lbl; ?></label><?php endif; ?>
                                <?php if($infant_desc != ''): ?><span><?php echo $infant_desc; ?></span><?php endif; ?>
                            </div>
                            <?php if( $bprice == 'per_person' || $bprice == 'night_person' || $bprice == 'day_person' || $bprice == 'hour_person' ): ?>
                            <div class="qtt-price text-right">
                                <input class="hids-input jscal-price" readonly="readonly" type="text" name="price_infant" value="<?php echo townhub_addons_get_price_formated( get_post_meta( get_the_ID(), ESB_META_PREFIX .'infant_price', true ), false ); ?>"><?php echo $symbol_multiple; ?>
                            </div>
                            <?php endif; ?>
                            <div class="qtt-input">
                                <input type="number" name="infants" min="0" max="<?php echo $max_guests; ?>" step="1" value="0">
                                <div class="qtt-nav">
                                    <div class="qtt-btn qtt-up">+</div>
                                    <div class="qtt-btn qtt-down">-</div>
                                </div>
                            </div>

                            <?php if( $bprice == 'per_person' ): ?>
                            <input class="hid-input" type="text" name="item_total" value="" data-jcalc="{infants} * {price_infant}">
                            <?php elseif( $bprice == 'night_person' ): ?>
                            <input class="hid-input" type="text" name="item_total" value="" data-jcalc="{infants} * {price_infant} * {nights}">
                            <?php elseif( $bprice == 'day_person' ): ?>
                            <input class="hid-input" type="text" name="item_total" value="" data-jcalc="{infants} * {price_infant} * {days}">
                            <?php elseif( $bprice == 'hour_person' ): ?>
                            <input class="hid-input" type="text" name="item_total" value="" data-jcalc="{infants} * {price_infant} * {bkslots}">
                            <?php endif; ?>

                        </div>
                        <?php endif; ?>

                        <?php if($show_quantity === 'yes'): ?>
                        <div class="qtt-item qtt-item-full qtt-item-js fl-wrap m-b20">
                            <div class="qtt-label-wrap">
                                <?php if($qtt_lbl != ''): ?><label class="qtt-label"><?php echo $qtt_lbl; ?></label><?php endif; ?>
                                <?php if($qtt_desc != ''): ?><span><?php echo $qtt_desc; ?></span><?php endif; ?>
                            </div>
                            <?php if( $bprice == 'listing' ): ?>
                            <div class="qtt-price text-right">
                                <input class="hids-input jscal-price" readonly="readonly" type="text" name="listing_price" value="<?php echo townhub_addons_get_price_formated( $listing_price, false ); ?>"><?php echo $symbol_multiple; ?>
                            </div>
                            
                            <?php endif; ?>
                            <div class="qtt-input">
                                <input type="number" name="bk_qtts" min="0" max="<?php echo $max_guests; ?>" step="1" value="<?php echo intval($qtt_default);?>">
                                <div class="qtt-nav">
                                    <div class="qtt-btn qtt-up">+</div>
                                    <div class="qtt-btn qtt-down">-</div>
                                </div>
                            </div>

                            <?php if( $bprice == 'listing' ): ?>
                            <input class="hid-input" type="text" name="item_total" value="" data-jcalc="{bk_qtts} * {listing_price}">
                            <?php endif; ?>

                        </div>
                        <?php endif; ?>

                        <?php if($show_menu == 'yes'): 
                            $cats = array();
                            $child_items = '';
                            $resmenus = (array)get_post_meta( get_the_ID(), ESB_META_PREFIX.'resmenus', true );
                            if( !empty($resmenus) ):
                                foreach ((array)$resmenus as $menuitm) {
                                    $ccats = array();
                                    if( isset($menuitm['cats']) && !empty($menuitm['cats']) ){
                                        $ccats = explode(",", $menuitm['cats']);
                                        $cats = array_merge($cats, $ccats);
                                    }
                                    $ccats = array_map( 'townhub_addons_escapse_class', $ccats);

                                    if( empty($menuitm['_id']) ) $menuitm['_id'] = uniqid('mnid');
                                    $price_name = $menuitm['_id'] ."_price";
                                    $qtt_name = 'bkfmenus['.$menuitm['_id'].']';
                                    $qtt_id = uniqid('lmenu');

                                    $child_items .=     '<!--restmenu-item-->';
                                    $child_items .=     '<div class="cthiso-item rest-menu-item '. implode(" ", $ccats) .'">';

                                    $child_items .=     '<div class="lservices-qtts-item">';
                                        $child_items .=     '<div class="qtt-item qtt-item-full qtt-item-js">';
                                            $child_items .=     '<div class="qtt-label-wrap">'.esc_html($menuitm['name']).'</div>';
                                            
                                            $child_items .=     '<div class="qtt-price text-right">';
                                                $child_items .=     '<input class="hids-input jscal-price" readonly="readonly" type="text" name="'.esc_attr( $price_name ).'" value="'.townhub_addons_get_price_formated( $menuitm['price'], false ).'">'.$symbol_multiple;
                                            $child_items .=     '</div>';
                                            
                                            $child_items .=     '<div class="qtt-input">';
                                                $child_items .=     '<input type="number" id="'.esc_attr( $qtt_id ).'" name="'.esc_attr( $qtt_name ).'" min="0" step="1" value="0">';
                                                $child_items .=     '<div class="qtt-nav">';
                                                    $child_items .=     '<div class="qtt-btn qtt-up">+</div>';
                                                    $child_items .=     '<div class="qtt-btn qtt-down">-</div>';
                                                $child_items .=     '</div>';
                                            $child_items .=     '</div>';
                                            
                                            $child_items .=     '<input class="hid-input" type="text" name="item_total" value="" data-jcalc="{#'.esc_attr( $qtt_id ).'} * {'.esc_attr( $price_name ).'}">';
                                            
                                        $child_items .=     '</div>';
                                    $child_items .=     '</div>';

                                    $child_items .=     '</div><!--restmenu-item end-->';

                                }
                                // end foreach menus
                        ?>
                            <div class="lservices-qtts-wrap">

                                <div class="cthiso-isotope-wrapper cthiso-resmenu">
                                    <div class="cthiso-resmenu-top-wrap">
                                        <div class="cthiso-resmenu-label">
                                            <?php if($menu_lbl != ''): ?><label class="lservices-qtts-label"><?php echo $menu_lbl; ?></label><?php endif; ?>
                                            <?php if($menu_desc != ''): ?><span><?php echo $menu_desc; ?></span><?php endif; ?>
                                        </div>
                                        <?php 
                                        $cats = array_unique($cats);
                                        if(!empty($cats)):
                                        ?>
                                            <div class="cthiso-filters">
                                                
                                                <?php
                                                $ccount = 0;
                                                foreach ($cats as $key => $cat) {
                                                    if( $ccount == 0 ){
                                                        echo '<a href="#" class="cthiso-filter cthiso-filter-active" data-filter=".'. townhub_addons_escapse_class($cat) .'">'.$cat.'</a>';
                                                    }else{
                                                        echo '<a href="#" class="cthiso-filter" data-filter=".'. townhub_addons_escapse_class($cat) .'">'.$cat.'</a>';
                                                    }
                                                    $ccount++;
                                                } ?>
                                                
                                                <a href="#" class="cthiso-filter" data-filter="*"><?php _ex( 'All', 'Restaurant menus', 'townhub-add-ons' ); ?></a>
                                                
                                            </div>
                                        <?php 
                                        endif; ?>
                                    </div>
                                    <div class="cthiso-items cthiso-flex cthiso-one-cols">
                                        <div class="cthiso-sizer"></div>
                                        <?php echo $child_items; ?>
                                    </div>
                                </div>

                            </div>
                        <?php endif; 
                        endif; ?>


                        

                        <?php 
                        if($show_tickets == 'single_day' ):
                        // $tickets = get_post_meta( get_the_ID(), ESB_META_PREFIX.'tickets', true );
                        $tickets = Esb_Class_Booking_CPT::get_tickets(get_the_ID());
                        if ( is_array( $tickets) && !empty($tickets)) { 
                        ?>
                        <!--tickets-select-wrap -->
                        <div class="fl-wrap tickets-select-wrap">
                            <?php 
                            $tkey = 0;
                            foreach( $tickets as $ticket): 
                                $tkprice_name = $ticket['_id'] ."_price";
                                $tkqtt_name = 'tickets['.$ticket['_id'].']';
                                $tkqtt_id = uniqid('ticket');
                            ?>
                            <!--tickets-select_item -->
                            <div class="tickets-select_item">
                                <div class="tickets-select_header<?php if($tkey === 0 ) echo ' vis-ticket_select';?> fl-wrap"><?php echo esc_html($ticket['name']); ?><span class="available_counter green-bg"><?php echo intval($ticket['available']); ?></span><i class="fal 
                        fa-plus"></i></div>
                                <div class="tickets-select_content<?php if($tkey === 0 ) echo ' vis-ticket_select_cont';?>">
                                    
                                    <div class="qtt-item qtt-item-full qtt-item-js">
                                        <div class="qtt-label-wrap"><?php _e( 'Quantity', 'townhub-add-ons' ); ?></div>
                                        
                                        <div class="qtt-price text-right">
                                            <input class="hids-input jscal-price" readonly="readonly" type="text" name="<?php echo esc_attr( $tkprice_name ); ?>" value="<?php echo townhub_addons_get_price_formated( $ticket['price'], false ); ?>"><?php echo $symbol_multiple; ?>
                                        </div>
                                        
                                        <div class="qtt-input">
                                            <input type="number" id="<?php echo esc_attr( $tkqtt_id ); ?>" name="<?php echo esc_attr( $tkqtt_name ); ?>" min="0" max="<?php echo intval($ticket['available']); ?>" step="1" value="<?php echo $tkey === 0 ? '1' : '0'; ?>">
                                            <div class="qtt-nav">
                                                <div class="qtt-btn qtt-up">+</div>
                                                <div class="qtt-btn qtt-down">-</div>
                                            </div>
                                        </div>
                                        
                                        <input class="hid-input" type="text" name="item_total" value="" data-jcalc="{#<?php echo esc_attr( $tkqtt_id ); ?>} * {<?php echo esc_attr( $tkprice_name ); ?>}">
                                        
                                    </div>

                                </div>
                            </div>
                            <!--tickets-select_item end -->
                            <?php
                            $tkey++;
                            endforeach; ?>
                        </div>
                        <!--tickets-select-wrap end -->
                        <?php } ?>

                        <?php 
                        elseif($show_tickets == 'multi_day' ):
                        //if($slots_show === '1'): 
                            $ticketsNumID = uniqid();
                        ?>
                            <div class="cth-bk-tickets" 
                                data-postid="<?php the_ID();?>" 
                                data-label="<?php echo esc_attr( $ticket_lbl );?>"
                                data-single="<?php echo esc_attr( $slots_single );?>"
                                data-required="yes" 
                                data-countid="<?php echo esc_attr( $ticketsNumID ); ?>"
                            ></div>
                            <?php 
                            // if( $bprice == 'per_hour' ){
                            echo '<input id="'.esc_attr( $ticketsNumID ).'" class="hid-input jscal-bktickets" readonly="readonly" type="text" name="multi_tickets_total" value="0">';
                            echo '<input class="hid-input" type="text" name="item_total" value="0" data-jcalc="{multi_tickets_total}">';
                            // } 
                            ?>
                        <?php
                        endif; 
                        ?>


                        <?php 
                        if( $show_lservices == 'yes' ):
                        $lservices = get_post_meta( get_the_ID(), ESB_META_PREFIX.'lservices', true );
                        if( !empty($lservices) && is_array($lservices) ){ ?>
                            <?php if($quanity_lservices == 'yes'): ?>
                            <div class="lservices-qtts-wrap">
                                <label class="lservices-qtts-label"><?php esc_html_e( 'Extra Services', 'townhub-add-ons' ); ?></label>
                                <?php
                                // $serprefix = uniqid('lserv');
                                foreach ($lservices as $serv) {
                                    $serprice_name = $serv['service_id'] ."_price";
                                    $serqtt_name = 'bk_services['.$serv['service_id'].']';
                                    $serqtt_id = uniqid('lserv');
                                ?>
                                    <div class="lservices-qtts-item">
                                        <div class="qtt-item qtt-item-full qtt-item-js">
                                            <div class="qtt-serv-label">
                                                <div class="qtt-label-wrap"><?php echo esc_html($serv['service_name']);?></div>
                                                <?php if( !empty( $serv['service_desc'] ) ): ?><div class="serv-item-desc"><?php echo esc_html($serv['service_desc']);?></div><?php endif; ?>
                                            </div>
                                            
                                            
                                            <div class="qtt-price text-right">
                                                <input class="hids-input jscal-price" readonly="readonly" type="text" name="<?php echo esc_attr( $serprice_name ); ?>" value="<?php echo townhub_addons_get_price_formated( $serv['service_price'], false ); ?>"><?php echo $symbol_multiple; ?>
                                            </div>
                                            
                                            <div class="qtt-input">
                                                <input type="number" id="<?php echo esc_attr( $serqtt_id ); ?>" name="<?php echo esc_attr( $serqtt_name ); ?>" min="0" step="1" value="0">
                                                <div class="qtt-nav">
                                                    <div class="qtt-btn qtt-up">+</div>
                                                    <div class="qtt-btn qtt-down">-</div>
                                                </div>
                                            </div>
                                            
                                            <input class="hid-input" type="text" name="item_total" value="" data-jcalc="{#<?php echo esc_attr( $serqtt_id ); ?>} * {<?php echo esc_attr( $serprice_name ); ?>}">
                                            
                                        </div>
                                    </div>
                                    <?php
                                } ?>
                            </div>
                            <?php else : ?>
                            <div class="lservices-row">
                                <div class="cth-dropdown-warp">
                                    <div class="cth-dropdown-header"><label><?php esc_html_e('Extra Services','townhub-add-ons') ?><span class="count-select-ser">0</span></label></div>
                                    <div class="cth-dropdown-options">
                                        <div class="cth-dropdown-inner">
                                            <?php
                                            $serprefix = uniqid('lserv');
                                            foreach ($lservices as $serv) {
                                                $serv_price = townhub_addons_get_price( $serv['service_price'] );
                                            ?>
                                                <div class="cth-dropdown-item lserv-dropdown">
                                                    <input type="checkbox" id="<?php echo $serprefix .'-'. esc_attr($serv['service_id']);?>" name='addservices[]' class="lserv-item-checkbox" data-price="<?php echo $serv_price;?>" value="<?php echo esc_attr($serv['service_id']);?>">
                                                    <label for="<?php echo $serprefix .'-'. esc_attr($serv['service_id']);?>"><?php echo esc_html($serv['service_name']);?><span class="cth-dropdown-meta"><?php echo townhub_addons_get_price_formated( $serv['service_price'] );?></span></label>
                                                    <?php if( !empty( $serv['service_desc'] ) ): ?><div class="serv-item-desc"><?php echo esc_html($serv['service_desc']);?></div><?php endif; ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <input class="hid-input" type="text" name="item_total" value="0">
                            </div>
                        <?php 
                            endif;
                            } 
                        endif;
                        ?>   
                        <?php if( $show_upload_cv == 'yes' ): ?> 
                        <div class="CVUploader photoUpload">
                            <span><i class="fal fa-file-pdf"></i><?php _e( 'Upload CV', 'townhub-add-ons' ); ?></span>
                            <div class="cv-uploaded-name"></div>
                            <input type="file" class="upload cth-file-upload" name="cv_upload" accept=".pdf,.gif,.jpg,.png,.doc,.docx">
                        </div>
                        <?php endif; ?>
                        

                        <?php 
                        if($show_notes == 'yes'): ?> 
                        <textarea class="bk-notes" name="notes" cols="40" rows="3" placeholder="<?php esc_attr_e( 'Additional Information', 'townhub-add-ons' ); ?>"></textarea>
                        <?php endif; ?>
                        
                        <?php if( $show_total_cost == 'yes' ): ?>
                        <div class="total-coast fl-wrap clearfix">
                            <strong><?php _e( 'Total Cost', 'townhub-add-ons' ); ?></strong>
                            <span>
                                <?php if($curr_attrs['sb_pos'] == 'left_space') echo $curr_attrs['symbol']."&nbsp;"; ?><?php if($curr_attrs['sb_pos'] == 'left') echo $curr_attrs['symbol']; ?>
                                <input readonly class="total-cost-input" type="text" name="grand_total" value="" data-jcalc="SUM({item_total})" size="5">
                                <?php if($curr_attrs['sb_pos'] == 'right_space') echo "&nbsp;".$curr_attrs['symbol']; ?><?php if($curr_attrs['sb_pos'] == 'right') echo $curr_attrs['symbol']; ?>
                            </span>
                        </div>
                        <?php endif; ?>

                        <div class="booking-buttons">
                            <button class="btn big-btn color-bg flat-btn book-btn lbooking-submitxs"  type="submit"><?php esc_html_e( 'Submit', 'townhub-add-ons' ); ?><i class="fal fa-angle-right"></i></button>
                        </div>

                        <?php 
                        if( !empty($ad_note) ): ?>
                        <div class="booking-additional-note"><?php echo $ad_note; ?></div>
                        <?php endif; ?>

                        <input type="hidden" name="booking_type" value="general">
                        <input type="hidden" name="price_based" value="<?php echo esc_attr( $bprice ); ?>">
                        <input type="hidden" name="listing_id" value="<?php the_ID(); ?>">
                        <input type="hidden" name="product_id" value="<?php the_ID(); ?>">
                        
                        
                    </form>

                </div>

            </div>
        </div>
    </div>
    <!--box-widget-item end --> 

</div>
<?php
endif; 