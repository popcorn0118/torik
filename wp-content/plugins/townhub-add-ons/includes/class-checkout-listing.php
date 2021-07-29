<?php 
/* add_ons_php */

defined( 'ABSPATH' ) || exit;


class Esb_Class_Checkout_Listing extends Esb_Class_Checkout{   
    protected $type = 'booking'; 
    protected $stripe_data = array(); 
    public function __construct(){ 
        $this->data_user();
    }
    public function render(){
        $price_total = ESB_ADO()->cart->get_total();
        $cart_details = ESB_ADO()->cart->get_cart_details(); 

        // var_dump($price_total);

        // echo '<pre>';
        // var_dump($cart_details);die;
        // // var_dump(get_user_meta ( get_current_user_id()));


        ?>
        <section class="lcheckout-page-sec gray-bg no-top-padding-sec pad-bot-50" id="main-sec">
            <div class="container">
                <div class="breadcrumbs-wrapper inline-breadcrumbs block-breadcrumbs">
                    <?php townhub_addons_breadcrumbs();?>
                </div>
                
                <!-- <div class="lcheckout-title">
                    <h2>Booking form for : <span>Iconic Cafe</span></h2>
                </div> -->
                
                <div class="lcheckout-wrap">
                    <?php if(empty($cart_details)): ?>
                        <div class="lcheckout-cart-empty"> 
                            <h2><?php _e( 'Your cart is empty', 'townhub-add-ons' ); ?></h2>
                        </div>
                    <?php else: 
                        

                        $first_cart_item = reset($cart_details);

                        $this->stripe_data = array(
                            'amount' => townhub_addons_get_stripe_amount( $price_total ),
                            'plan' => sprintf(__( '%s booking', 'townhub-add-ons' ), $first_cart_item['title'] ),
                            // 'email' => $current_user->user_email,
                            'is_recurring' => false,
                        );

                        $ck_type = !empty($first_cart_item['cart_item_type']) ? $first_cart_item['cart_item_type'] : 'listing_booking';
                        $need_logged_in = ($ck_type === 'listing_booking') ? (bool)townhub_addons_get_option('ck_book_logged_in') : townhub_addons_get_option('ck_need_logged_in') ;
                        $need_logged_in = apply_filters( 'esb_checkout_need_logged_in', $need_logged_in,  $ck_type );
                        ?>
                        <div class="row lcheckout-row">
                            <div class="col-md-8 lcheckout-col-left">
                                <?php if( $need_logged_in && !is_user_logged_in() ): 
                                    $logBtnAttrs = townhub_addons_get_login_button_attrs( '', 'current' );
                                ?>
                                <div class="checkout-must-logged-in">
                                    <span class="must-logged-in-msg"><?php _e( 'You must be logged in to checkout.', 'townhub-add-ons' ); ?></span>
                                    <span class="must-logged-in-btn"><a href="<?php echo esc_url( $logBtnAttrs['url'] );?>" class="btn-link <?php echo esc_attr( $logBtnAttrs['class'] );?>"><?php _e( 'Login or register', 'townhub-add-ons' ); ?></a></span>
                                </div>
                                <!-- .checkout-must-logged-in end -->
                                <?php else: 
                                    $class_cs = '';
                                    if(townhub_addons_get_option('ck_hide_tabs') != 'yes'){
                                        $class_cs = 'lcheckout-tabs-wrap';
                                    }else{
                                        $class_cs = 'lcheckout-tabs-wrap hide-tabs';
                                    }
                                ?>
                                <div class="<?php echo $class_cs;?>">
                                    <?php $this->progressbar(); ?>
                                    
                                    <div class="bookiing-form-wrap fl-wrap">
                                         
                                        <div class="ck-form">
                                            <form class="listing-payments-form" id="townhub-checkout-form">

                                                <?php 
                                                    $this->render_information(); 
                                                    $this->render_billingAddress(); 
                                                    $this->render_payments(); 
                                                    // $this->render_confirm(); 
                                                    if( townhub_addons_get_option('ck_hide_tabs') == 'yes' ){
                                                        $this->render_terms();
                                                        echo '<span class="fw-separator"></span>';
                                                        $this->render_submit(); 
                                                    } 
                                                ?>
                                                
                                                <?php $_wpnonce = wp_create_nonce('esb-checkout-security'); ?>
                                                <input type="hidden" name="_wpnonce" value="<?php echo $_wpnonce;?>">
                                                
                                            </form>
                                        </div>

                                    </div><!-- .bookiing-form-wrap end -->
                                </div>
                                <!-- .lcheckout-tabs-wrap end -->
                                <?php endif; // end check logged in user for checkout ?>
                            </div><!-- lcheckout-col-left end -->
                            <div class="col-md-4 lcheckout-col-right">
                                <div class="cart-details-wrap">
                                    <div class="cart-details-header">
                                        <h3><?php _e( 'Your Booking Cart', 'townhub-add-ons' ); ?></h3>
                                    </div>
                                    <!--cart-details  --> 
                                    <div class="cart-details">
                                        <?php $this->render_cart_items($cart_details); ?>
                                    </div>
                                    <!--cart-details end --> 
                                    <!--cart-total --> 
                                    <div class="cart-total color2-bg flex-items-center">
                                        <span class="cart-total-text"><?php esc_html_e( 'Total Cost', 'townhub-add-ons' ); ?></span>
                                        <strong class="cart-total-total cart-dtright"><?php echo townhub_addons_get_price_formated($price_total); ?></strong>                                
                                    </div>
                                    <!--cart-total end --> 


                                </div>
                                <?php 
                                if(is_active_sidebar('dashboard-feed')){
                                    dynamic_sidebar('dashboard-feed');
                                } ?>                      
                            </div><!-- lcheckout-col-right end -->     
                        </div><!-- lcheckout-row end -->     
                    <?php endif; // check cart data ?>


                        
                </div>
            </div>
        </section>

        <?php
    }
    protected function render_cart_items($cart_details = array()){
        // echo '<pre>';
        // var_dump($cart_details);
        foreach ($cart_details as $key => $data) {
            if(isset($data['cart_item_type']) && $data['cart_item_type'] == 'plan') 
                $this->render_cart_item_plan($data, $key);
            elseif(isset($data['cart_item_type']) && $data['cart_item_type'] == 'ad') 
                $this->render_cart_item_ad($data, $key);
            else
                $this->render_cart_item_booking($data, $key);
        }
    }
    protected function render_cart_item_ad($data = array(), $cart_key = ''){
        
        ?>
        <!--cart-details_header--> 
        <div class="cart-product-details cart-product-ad">
            <?php //if($data['thumbnail'] != '') echo $data['thumbnail']; ?>
            <div class="cart-product-desc">
                <div class="cart-product-title"><?php echo $data['title']; ?></div>
            </div>
        </div>
        <!--cart-details_header end--> 
        <!--ccart-details_text-->          
        <div class="cart-details_text">
            <ul class="cart-listi">
                <?php
                if($data['price']): ?>
                <li class="clearfix bk-price"><?php esc_html_e( 'Price', 'townhub-add-ons' ); ?>
                    <div class="cart-dtright">
                        <strong class="plan-quantity"><?php echo sprintf(__(' %d x ','townhub-add-ons'), $data['quantity']); ?></strong>
                        <strong><?php echo townhub_addons_get_price_formated($data['price']); ?></strong>
                    </div>
                </li>   
                <?php endif; ?>
                <?php if (!empty($data['period_text'])): ?>
                    <li class="clearfix bk-period"><?php esc_html_e( 'Period', 'townhub-add-ons' ); ?><div class="cart-dtright"><span class="period-text"><?php echo $data['period_text']; ?></span></div></li>   
                <?php endif ?>
                <?php if (!empty($data['expired'])): ?>
                    <li class="clearfix bk-expired"><?php esc_html_e( 'Expired', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo Esb_Class_Date::i18n($data['expired'], true); ?></div></li>   
                <?php endif ?>
                <?php
                if($data['subtotal_vat']): ?>
                <li class="clearfix bk-taxes"><?php esc_html_e( 'VAT', 'townhub-add-ons' ); ?><div class="cart-dtright"><strong><?php echo townhub_addons_get_price_formated($data['subtotal_vat']); ?></strong></div></li>
                <?php endif; ?>

            </ul>
        </div>
        <!--cart-details_text end --> 
        <?php
    }
    protected function render_cart_item_booking($data = array(), $cart_key = ''){
        ?>
        <!--cart-details_header--> 
        <div class="cart-product-details cart-product-listing">
            <?php if($data['thumbnail'] != ''){ ?>
            <a href="<?php echo $data['permalink']; ?>" class="cart-product-thumbnail">
                <?php echo $data['thumbnail']; ?>
            </a>
            <?php } ?>
            <div class="cart-product-desc">
                <a class="cart-product-title" href="<?php echo $data['permalink']; ?>" title="<?php echo esc_attr($data['title']); ?>"><?php echo $data['title']; ?></a>
                <!-- <div class="listing-rating card-popup-rainingvis" data-starrating2="4"></div> -->
                <?php if($data['address'] != ''): ?><div class="booking-listing-address"><?php echo $data['address']; ?></div><?php endif; ?>
            </div>
        </div>
        <!--cart-details_header end--> 
        <?php
            $this->render_cart_item_rooms($data, $cart_key);
            $this->render_cart_item_rooms_new($data, $cart_key);
            $this->render_cart_item_rooms_old($data, $cart_key);
            $this->render_cart_event_item($data, $cart_key);
            $this->render_cart_tour_item($data, $cart_key);
            $this->render_cart_custom_tour_item($data, $cart_key);
            $this->render_cart_general_item($data, $cart_key);

            do_action( 'esb_render_cart_item_booking_after', $data, $cart_key );
    }
    protected function render_cart_event_item($data = array(), $cart_key = ''){
        // var_dump($data);
        if(!isset($data['qty']) || empty($data['qty']) || !isset($data['lprice']) || empty($data['lprice'])) return;
        ?>
        <div class="cart-details_text">
            <ul class="cart-listi">
                <?php if (!empty($data['date_event'])): ?>
                    <li class="clearfix bk-checkin"><?php esc_html_e( 'Date', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo Esb_Class_Date::i18n( $data['date_event'] ); ?></div></li>   
                <?php endif ?>
                <?php if (!empty($data['qty'])): ?>
                    <li class="clearfix bk-quantity"><?php esc_html_e( 'Tickets', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo $data['qty']; ?></div></li>   
                <?php endif ?>
                <?php $this->render_fee_services_coupon($data); ?>
            </ul>
        </div>
        
        <?php
        $this->render_form_coupon($data['listing_id']);
    }
    protected function render_form_coupon($listing_id = 0){?>
        <div class="cart-product-detailss coupon-warp custom-form">
            <form action="" method="post" accept-charset="utf-8" class="coupon-code-form">
                <label for="coupon_code"><?php esc_html_e( 'Coupon:', 'townhub-add-ons' ); ?></label> 
                <div class="coupon-inner flex-items-center">
                    
                    <div class="coupon-input">
                        <?php 
                        $cart_coupon = ESB_ADO()->cart->get_coupon_code();
                        ?>
                        <input type="text" name="coupon_code" class="coupons-code" id="coupon_code" value="<?php echo esc_attr( $cart_coupon ); ?>" placeholder="<?php esc_attr_e( 'Coupon code','townhub-add-ons' ); ?>">
                    </div>
                    <div class="coupon-button">
                        <button type="submit" class="coupon-btn color2-bg"><?php esc_html_e( 'Apply coupon', 'townhub-add-ons' ); ?></button>
                    </div>
                </div>
                <div id="message-coupon"></div>
                
                <input type="hidden" name="action" value="esb_add_to_coupon"/>
                <input type="hidden" name="lid" value="<?php echo $listing_id;?>"/>
                <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'townhub-add-to-coupon' ); ?>"> 

            </form>
        </div>
    <?php
    }
    protected function render_cart_item_rooms($data = array(), $cart_key = ''){
        if(!isset($data['rooms']) || empty($data['rooms'])) return;
        ?>
        <!--ccart-details_text-->          
        <div class="cart-details_text">
            <ul class="cart-listi">
                <li class="clearfix bk-rooms">
                    <span class="bkchild-items-name"><?php esc_html_e( 'Rooms', 'townhub-add-ons' ); ?></span>
                    <div class="bkchild-items">
                    <?php 
                    foreach ($data['rooms'] as $rdata) {
                        ?>
                        <div class="bkchild-item">
                            <span class="bkchild-item-title"><?php echo $rdata['title'];?></span>
                            <div class="bkchild-item-details">
                                <strong class="bkchild-item-quantity"><?php echo sprintf(__(' %d x ','townhub-add-ons'), $rdata['quantity']); ?></strong>
                                <strong class="bkchild-item-price"><?php echo townhub_addons_get_price_formated($rdata['price']); ?></strong>
                            </div>
                        </div>
                        <?php
                    } ?>
                    </div>
                </li>
                <?php if (!empty($data['checkin'])): ?>
                    <li class="clearfix bk-checkin"><?php esc_html_e( 'From', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo Esb_Class_Date::i18n( $data['checkin'] ); ?></div></li>   
                <?php endif ?>
                <?php if (!empty($data['checkout'])): ?>
                    <li class="clearfix bk-checkout"><?php esc_html_e( 'To', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo Esb_Class_Date::i18n( $data['checkout'] ); ?></div></li>   
                <?php endif ?>
                <?php if ( !empty($data['nights'])): ?>
                    <li class="clearfix bk-nights"><?php esc_html_e( 'Nights', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo $data['nights']; ?></div></li>    
                <?php endif ?>
                <?php if (!empty($data['adults'])): ?>
                    <li class="clearfix bk-adults"><?php esc_html_e( 'Adults', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo $data['adults']; ?></div></li>
                <?php endif ?>
                <?php if (!empty($data['children'])): ?>
                    <li class="clearfix bk-children"><?php esc_html_e( 'Children', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo $data['children']; ?></div></li>
                <?php endif ?>
                <?php if (!empty($data['infants'])): ?>
                    <li class="clearfix bk-infants"><?php esc_html_e( 'Infants', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo $data['infants']; ?></div></li>
                <?php endif ?>
                <?php $this->render_fee_services_coupon($data); ?>
            </ul>
        </div>
        <!--cart-details_text end -->
        <?php
        $this->render_form_coupon($data['listing_id']);
        
    }
    protected function render_cart_item_rooms_old($data = array(), $cart_key = ''){
        if(!isset($data['rooms_old_data']) || empty($data['rooms_old_data'])) return;
        ?>
        <!--ccart-details_text-->          
        <div class="cart-details_text">
            <ul class="cart-listi">
                <li class="clearfix bk-rooms-new">
                    
                    <div class="bkchild-items">
                    <?php 
                    foreach ($data['rooms_old_data'] as $rdata) {
                        ?>
                        <div class="bkroom-item">
                            <div class="bkroom-item-title"><?php echo $rdata['title'];?></div>
                            <?php 
                            foreach ($rdata['rdates'] as $rdte => $rdval) {

                                ?>
                                <div class="bkroom-date">
                                    <div class="bkroom-date-title"><?php echo Esb_Class_Date::i18n( $rdte ); ?></div>
                                    <div class="bkroom-date-persons">

                                        <?php if( !empty($rdata['quantity']) ): ?>
                                        <div class="bkroom-date-person">
                                            <strong class="bkroom-date-quantity"><?php echo sprintf( _x('%d x ', 'checkout room dates','townhub-add-ons'), $rdata['quantity'] ); ?></strong>
                                            <strong class="bkroom-date-price"><?php echo townhub_addons_get_price_formated($rdval); ?></strong>
                                        </div>
                                        <?php endif; ?>
                                        
                                    </div>
                                </div>
                                <?php
                            } ?>
                        </div>
                        <?php
                    } ?>
                    </div>
                </li>
                <?php if (!empty($data['checkin'])): ?>
                    <li class="clearfix bk-checkin"><?php esc_html_e( 'From', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo Esb_Class_Date::i18n( $data['checkin'] ); ?></div></li>   
                <?php endif ?>
                <?php if (!empty($data['checkout'])): ?>
                    <li class="clearfix bk-checkout"><?php esc_html_e( 'To', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo Esb_Class_Date::i18n( $data['checkout'] ); ?></div></li>   
                <?php endif ?>
                <?php if ( !empty($data['nights'])): ?>
                    <li class="clearfix bk-nights"><?php esc_html_e( 'Nights', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo $data['nights']; ?></div></li>    
                <?php endif ?>
                <?php if (!empty($data['adults'])): ?>
                    <li class="clearfix bk-adults"><?php esc_html_e( 'Adults', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo $data['adults']; ?></div></li>
                <?php endif ?>
                <?php if (!empty($data['children'])): ?>
                    <li class="clearfix bk-children"><?php esc_html_e( 'Children', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo $data['children']; ?></div></li>
                <?php endif ?>
                <?php if (!empty($data['infants'])): ?>
                    <li class="clearfix bk-infants"><?php esc_html_e( 'Infants', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo $data['infants']; ?></div></li>
                <?php endif ?>
                
                <?php $this->render_fee_services_coupon($data); ?>
            </ul>
        </div>
        <!--cart-details_text end -->
        <?php
        $this->render_form_coupon($data['listing_id']);
        
    }
    protected function render_cart_item_rooms_new($data = array(), $cart_key = ''){
        if(!isset($data['rooms_person_data']) || empty($data['rooms_person_data'])) return;
        ?>
        <!--ccart-details_text-->          
        <div class="cart-details_text">
            <ul class="cart-listi">
                <li class="clearfix bk-rooms-new">
                    <!-- <span class="bkchild-items-name"><?php esc_html_e( 'Rooms', 'townhub-add-ons' ); ?></span> -->
                    <div class="bkchild-items">
                    <?php 
                    foreach ($data['rooms_person_data'] as $rdata) {
                        ?>
                        <div class="bkroom-item">
                            <div class="bkroom-item-title"><?php echo $rdata['title'];?></div>
                            <?php 
                            foreach ($rdata['rdates'] as $rdte => $rdval) {

                                ?>
                                <div class="bkroom-date">
                                    <div class="bkroom-date-title"><?php echo Esb_Class_Date::i18n( $rdte ); ?></div>
                                    <div class="bkroom-date-persons">
                                        <?php if( !empty($rdata['adults']) ): ?>
                                        <div class="bkroom-date-person">
                                            <strong class="bkroom-date-quantity"><?php echo sprintf( _x('Adults %d x ', 'checkout room persons','townhub-add-ons'), $rdata['adults'] ); ?></strong>
                                            <strong class="bkroom-date-price"><?php echo townhub_addons_get_price_formated($rdval['adults']); ?></strong>
                                        </div>
                                        <?php endif; ?>
                                        <?php if( !empty($rdata['children']) ): ?>
                                        <div class="bkroom-date-person">
                                            <strong class="bkroom-date-quantity"><?php echo sprintf( _x('Children %d x ', 'checkout room persons','townhub-add-ons'), $rdata['children'] ); ?></strong>
                                            <strong class="bkroom-date-price"><?php echo townhub_addons_get_price_formated($rdval['children']); ?></strong>
                                        </div>
                                        <?php endif; ?>
                                        <?php if( !empty($rdata['infant']) ): ?>
                                        <div class="bkroom-date-person">
                                            <strong class="bkroom-date-quantity"><?php echo sprintf( _x('Infant %d x ', 'checkout room persons','townhub-add-ons'), $rdata['infant'] ); ?></strong>
                                            <strong class="bkroom-date-price"><?php echo townhub_addons_get_price_formated($rdval['infant']); ?></strong>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php
                            } ?>
                        </div>
                        <?php
                    } ?>
                    </div>
                </li>
                <?php if (!empty($data['checkin'])): ?>
                    <li class="clearfix bk-checkin"><?php esc_html_e( 'From', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo Esb_Class_Date::i18n( $data['checkin'] ); ?></div></li>   
                <?php endif ?>
                <?php if (!empty($data['checkout'])): ?>
                    <li class="clearfix bk-checkout"><?php esc_html_e( 'To', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo Esb_Class_Date::i18n( $data['checkout'] ); ?></div></li>   
                <?php endif ?>
                <?php if ( !empty($data['nights'])): ?>
                    <li class="clearfix bk-nights"><?php esc_html_e( 'Nights', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo $data['nights']; ?></div></li>    
                <?php endif ?>
                
                <?php $this->render_fee_services_coupon($data); ?>
            </ul>
        </div>
        <!--cart-details_text end -->
        <?php
        $this->render_form_coupon($data['listing_id']);
        
    }
    protected function render_cart_tour_item($data = array(), $cart_key = ''){
         // var_dump($data);
        if(!isset($data['booking_type']) || $data['booking_type'] != 'tour') return;
        ?>
        <!--ccart-details_text-->          
        <div class="cart-details_text">
            <ul class="cart-listi">
                
                <?php if (!empty($data['checkin'])): ?>
                    <li class="clearfix bk-checkin"><?php esc_html_e( 'Departure date', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo Esb_Class_Date::i18n( $data['checkin'] ); ?></div></li>   
                <?php endif ?>
                <?php if (!empty($data['checkout'])): ?>
                    <li class="clearfix bk-checkout"><?php esc_html_e( 'To', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo Esb_Class_Date::i18n( $data['checkout'] ); ?></div></li>   
                <?php endif ?>
                <?php if (!empty($data['adults'])): ?>
                    <li class="clearfix bk-adults"><?php esc_html_e( 'Adults', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo sprintf(__( '%sx%s', 'townhub-add-ons' ), townhub_addons_get_price_formated($data['adult_price']), $data['adults']); ?></div></li>
                <?php endif ?>
                <?php if (!empty($data['children'])): ?>
                    <li class="clearfix bk-children"><?php esc_html_e( 'Children', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo sprintf(__( '%sx%s', 'townhub-add-ons' ), townhub_addons_get_price_formated($data['children_price']), $data['children']); ?></div></li>
                <?php endif ?>
                <?php if (!empty($data['infants'])): ?>
                    <li class="clearfix bk-infants"><?php esc_html_e( 'Infants', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo sprintf(__( '%sx%s', 'townhub-add-ons' ), townhub_addons_get_price_formated($data['infant_price']), $data['infants']); ?></div></li>
                <?php endif ?>
                <?php $this->render_fee_services_coupon($data); ?>
            </ul>
        </div>
        <!--cart-details_text end --> 
      
        <?php
        $this->render_form_coupon($data['listing_id']);
    }

    protected function render_cart_general_item($data = array(), $cart_key = ''){
         // var_dump($data);
        if(!isset($data['booking_type']) || $data['booking_type'] != 'general') return;
        $listing_id = $data['listing_id'];
        // 
        ?>
        <!--ccart-details_text-->          
        <div class="cart-details_text">
            <ul class="cart-listi">
                
                <?php if (!empty($data['checkin'])): ?>
                    <li class="clearfix bk-checkinout">
                        <div class="bkdates-dates">
                            <?php esc_html_e( 'Dates', 'townhub-add-ons' ); ?>
                            <div class="cart-dtright">
                                <?php echo Esb_Class_Date::i18n( $data['checkin'] ); ?>
                                <?php if(!empty($data['checkout'])) echo esc_html__( ' - ', 'townhub-add-ons' ) . Esb_Class_Date::i18n( $data['checkout'] ); ?>
                            </div>
                        </div>
                        <div class="bkdates-details">
                            
                    
                        <?php if (!empty($data['day_prices'])){
                            foreach ($data['day_prices'] as $dte => $pri) {
                                echo '<div class="bkdates-date">';
                                    echo Esb_Class_Date::i18n( townhub_addons_format_cal_date($dte) );
                                    echo '<div class="bkdates-date-detail">';
                                    
                                        echo townhub_addons_get_price_formated($pri) ;

                                    echo '</div>';
                                echo '</div>';
                            }
                        } ?>

                        <?php if (!empty($data['adult_prices'])){
                            foreach ($data['adult_prices'] as $dte => $pri) {
                                echo '<div class="bkdates-date">';
                                    echo Esb_Class_Date::i18n( townhub_addons_format_cal_date($dte) );
                                    echo '<div class="bkdates-date-detail">';
                                    
                                        echo sprintf(__( '<div class="bkdates-date-adult"><span>Adult:</span> %s x <strong>%s</strong></div>', 'townhub-add-ons' ), $data['adults'], townhub_addons_get_price_formated($pri) );
                                        if(isset($data['children_prices'][$dte])) 
                                            echo sprintf(__( '<div class="bkdates-date-children"><span>Children:</span> %s x <strong>%s</strong></div>', 'townhub-add-ons' ), $data['children'], townhub_addons_get_price_formated( $data['children_prices'][$dte] ) );
                                        if(isset($data['infant_prices'][$dte])) 
                                            echo sprintf(__( '<div class="bkdates-date-infant"><span>Infant:</span> %s x <strong>%s</strong></div>', 'townhub-add-ons' ), $data['infants'], townhub_addons_get_price_formated( $data['infant_prices'][$dte] ) );

                                    echo '</div>';
                                echo '</div>';


                            }
                        } ?>

                        </div>

                    </li>   
                <?php endif; ?>
                
                        
                

                <?php if ( isset($data['price_based']) && ( $data['price_based'] == 'per_day' || $data['price_based'] == 'day_person' ) && !empty($data['days'])): ?>
                    <li class="clearfix bk-days"><?php esc_html_e( 'Days', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo $data['days']; ?></div></li>    
                <?php endif ?>

                <?php if (!empty($data['slots'])):

                    
                    $listing_slots = Esb_Class_Booking_CPT::listing_time_slots($listing_id, $data['checkin']);
                    $tSlots = array();
                    foreach ($data['slots'] as $bkslot) {
                        $slkey = array_search($bkslot, array_column($listing_slots, 'slot_id'));
                        if( false !== $slkey ){
                            if( $data['price_based'] == 'hour_person'){
                                $stpersons = '';
                                if(!empty($data['adults'])){
                                    $stpersons .= '<div class="bkchild-details-child">
                                        <span class="bkchild-item-quantity">'.sprintf(_x('Adults %d x ','Slots','townhub-add-ons'), $data['adults']).'</span>
                                        <span class="bkchild-item-price">'.townhub_addons_get_price_formated($data['adult_price']).'</span>
                                    </div>';
                                }
                                if(!empty($data['children'])){
                                    $stpersons .= '<div class="bkchild-details-child">
                                        <span class="bkchild-item-quantity">'.sprintf(_x('Children %d x ','Slots','townhub-add-ons'), $data['children']).'</span>
                                        <span class="bkchild-item-price">'.townhub_addons_get_price_formated($data['children_price']).'</span>
                                    </div>';
                                }
                                if(!empty($data['infants'])){
                                    $stpersons .= '<div class="bkchild-details-child">
                                        <span class="bkchild-item-quantity">'.sprintf(_x('Infants %d x ','Slots','townhub-add-ons'), $data['infants']).'</span>
                                        <span class="bkchild-item-price">'.townhub_addons_get_price_formated($data['infant_price']).'</span>
                                    </div>';
                                }
                                if( !empty($stpersons) ){
                                    $tSlots[] = '<div class="bkslots-item-title text-right">'.$listing_slots[$slkey]['time'].'</div><div class="bkchild-item mt-10"><div class="bkchild-item-details text-right">'.$stpersons.'</div></div>';
                                }
                                

                            }else{
                                $tSlots[] = $listing_slots[$slkey]['time'];
                            }
                            
                        }
                    }

                 ?>
                    <li class="clearfix bk-slots bkslots-<?php echo esc_attr($data['price_based']);?>"><?php esc_html_e( 'Time Slots', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo implode("<br />", $tSlots ); ?></div></li>
                <?php endif ?>

                <?php if (!empty($data['times'])): ?>
                    <li class="clearfix bk-times"><?php esc_html_e( 'Times', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo implode("<br />", $data['times'] ); ?></div></li>
                <?php endif ?>

                <?php if ( isset($data['price_based']) && $data['price_based'] == 'listing' && !empty($data['bk_qtts'])): ?>
                    <li class="clearfix bk-qtts"><?php esc_html_e( 'Quantity', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo sprintf(_x( '%1$s x %2$s', 'Checkout quantity', 'townhub-add-ons' ), $data['bk_qtts'], $data['price'] ); ?></div></li>    
                <?php endif ?>

                <?php if (!empty($data['bk_menus'])): ?>
                <li class="clearfix bk-menus">
                    <span class="bkchild-items-name"><?php esc_html_e( 'Menus:', 'townhub-add-ons' ); ?></span>
                    <div class="bkchild-items">
                    <?php 
                    foreach ($data['bk_menus'] as $rdata) {
                        ?>
                        <div class="bkchild-item">
                            <span class="bkchild-item-title"><?php echo $rdata['title'];?></span>
                            <div class="bkchild-item-details">
                                <span class="bkchild-item-quantity"><?php echo sprintf(__(' %d x ','townhub-add-ons'), $rdata['quantity']); ?></span>
                                <span class="bkchild-item-price"><?php echo townhub_addons_get_price_formated($rdata['price']); ?></span>
                            </div>
                        </div>
                        <?php
                    } ?>
                    </div>
                </li>
                <?php endif; ?>
                <?php if (!empty($data['tickets'])): ?>
                <li class="clearfix bk-tickets">
                    <span class="bkchild-items-name"><?php esc_html_e( 'Tickets:', 'townhub-add-ons' ); ?></span>
                    <div class="bkchild-items">
                    <?php 
                    foreach ($data['tickets'] as $rdata) {
                        ?>
                        <div class="bkchild-item">
                            <span class="bkchild-item-title"><?php echo $rdata['title'];?></span>
                            <div class="bkchild-item-details">
                                <span class="bkchild-item-quantity"><?php echo sprintf(__(' %d x ','townhub-add-ons'), $rdata['quantity']); ?></span>
                                <span class="bkchild-item-price"><?php echo townhub_addons_get_price_formated($rdata['price']); ?></span>
                            </div>
                        </div>
                        <?php
                    } ?>
                    </div>
                </li>
                <?php endif; ?>

                <?php if ( isset($data['price_based']) && ( $data['price_based'] == 'per_night' || $data['price_based'] == 'night_person' ) && !empty($data['nights'])): ?>
                    <li class="clearfix bk-nights"><?php esc_html_e( 'Nights', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo $data['nights']; ?></div></li>    
                <?php endif ?>
                <?php if ( isset($data['price_based']) && ( $data['price_based'] == 'per_day' || $data['price_based'] == 'per_night' ) ): ?>
                    <?php if (!empty($data['adults'])): ?>
                        <li class="clearfix bk-adults"><?php esc_html_e( 'Adults', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo $data['adults']; ?></div></li>
                    <?php endif ?>
                    <?php if (!empty($data['children'])): ?>
                        <li class="clearfix bk-children"><?php esc_html_e( 'Children', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo $data['children']; ?></div></li>
                    <?php endif ?>
                    <?php if (!empty($data['infants'])): ?>
                        <li class="clearfix bk-infants"><?php esc_html_e( 'Infants', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo $data['infants']; ?></div></li>
                    <?php endif ?>
                <?php endif ?>
                <?php $this->render_fee_services_coupon($data); ?>
            </ul>
        </div>
        <!--cart-details_text end --> 
      
        <?php
        // echo '<pre>';
        // var_dump($data);
        $this->render_form_coupon($data['listing_id']);
    }

    protected function render_cart_custom_tour_item($data = array(), $cart_key = ''){
         // var_dump($data);
        if(!isset($data['booking_type']) || $data['booking_type'] != 'custom_tour') return;

        // 
        ?>
        <!--ccart-details_text-->          
        <div class="cart-details_text">
            <ul class="cart-listi">
                
                <?php if (!empty($data['checkin'])): ?>
                    <li class="clearfix bk-checkinout">
                        <div class="bkdates-dates">
                            <?php esc_html_e( 'Date', 'townhub-add-ons' ); ?>
                            <div class="cart-dtright">
                                <?php echo Esb_Class_Date::i18n( $data['checkin'] ); ?>
                            </div>
                        </div>

                    </li>   
                <?php endif; ?>
                <?php if (!empty($data['tour_slots'])): ?>
                <li class="clearfix bk-tour-slots">
                    <span class="bkchild-items-name"><?php esc_html_e( 'Slots:', 'townhub-add-ons' ); ?></span>
                    <div class="bkchild-items">
                    <?php 
                    foreach ($data['tour_slots'] as $rdata) {
                        ?>
                        <div class="bkchild-item">
                            <span class="bkchild-item-title"><?php echo $rdata['title'];?></span>
                            <div class="bkchild-item-details text-right">
                                <?php if(!empty($rdata['adults'])): ?>
                                <div class="bkchild-details-child">
                                    <span class="bkchild-item-quantity"><?php echo sprintf(__('Adults %d x ','townhub-add-ons'), $rdata['adults']); ?></span>
                                    <span class="bkchild-item-price"><?php echo townhub_addons_get_price_formated($rdata['price']); ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if(!empty($rdata['children'])): ?>
                                <div class="bkchild-details-child">
                                    <span class="bkchild-item-quantity"><?php echo sprintf(__('Children %d x ','townhub-add-ons'), $rdata['children']); ?></span>
                                    <span class="bkchild-item-price"><?php echo townhub_addons_get_price_formated($rdata['child_price']); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                    } ?>
                    </div>
                </li>
                <?php endif; ?>
                        
                <?php $this->render_fee_services_coupon($data); ?>
            </ul>
        </div>
        <!--cart-details_text end --> 
      
        <?php
        // echo '<pre>';
        // var_dump($data);
        $this->render_form_coupon($data['listing_id']);
    }

    protected function render_fee_services_coupon($data){
        if(!empty($data['subtotal_fee'])): ?>
        <li class="clearfix bk-fees"><?php esc_html_e( 'Service fee', 'townhub-add-ons' ); ?><div class="cart-dtright"><strong><?php echo townhub_addons_get_price_formated($data['subtotal_fee']); ?></strong></div></li>
        <?php endif; ?>
        <?php if(!empty($data['addservices'])): ?>
            <li class="clearfix bk-addservices"><?php esc_html_e( 'Services:', 'townhub-add-ons' ); ?>
                <ul>
                <?php
                $listing_id = $data['listing_id'] != '' ? $data['listing_id'] : 0;
                $services = get_post_meta($listing_id, ESB_META_PREFIX.'lservices', true);
                    if(isset($services) && is_array($services) && $services!= '') {
                       $value_key_ser  = array();
                        $value_serv = array();
                        $addservices = $data['addservices'] != '' ? $data['addservices'] : array();
                        foreach ($addservices  as $key => $item_serv) {
                            // var_dump($item_serv);
                            $value_key_ser[]  = array_search($item_serv,array_column($services,  'service_id'));
                        }
                        foreach ($value_key_ser as $key => $value) {
                             $value_serv[] = $services[$value];
                        } 
                        foreach ($value_serv as $key => $value) {
                            ?>
                            <li><?php echo sprintf(__('%1$s <div class="cart-dtright">%2$s</div>', 'townhub-add-ons'),$value['service_name'],townhub_addons_get_price_formated($value['service_price'])); ?></li>
                       <?php }
                    } ?>
                </ul>
            </li>
        <?php endif; ?>

        <?php if (!empty($data['book_services'])): ?>
        <li class="clearfix bk-book_services">
            <span class="bkchild-items-name"><?php esc_html_e( 'Services:', 'townhub-add-ons' ); ?></span>
            <div class="bkchild-items">
            <?php 
            foreach ($data['book_services'] as $rdata) {
                ?>
                <div class="bkchild-item">
                    <span class="bkchild-item-title"><?php echo $rdata['title'];?></span>
                    <div class="bkchild-item-details">
                        <span class="bkchild-item-quantity"><?php echo sprintf(__(' %d x ','townhub-add-ons'), $rdata['quantity']); ?></span>
                        <span class="bkchild-item-price"><?php echo townhub_addons_get_price_formated($rdata['price']); ?></span>
                    </div>
                </div>
                <?php
            } ?>
            </div>
        </li>
        <?php endif; ?>

        <?php
        if(!empty($data['subtotal_vat'])): ?>
        <li class="clearfix bk-taxes"><?php esc_html_e( 'VAT', 'townhub-add-ons' ); ?><div class="cart-dtright"><strong><?php echo townhub_addons_get_price_formated($data['subtotal_vat']); ?></strong></div></li>
        <?php endif; ?>
        <?php if (!empty($data['amount_of_discount']) && $data['amount_of_discount'] != ''): ?>
            <li class="clearfix bk-infants"><?php esc_html_e( 'Discount amount', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo sprintf(__( '- %s', 'townhub-add-ons' ), townhub_addons_get_price_formated( $data['amount_of_discount'] ) ); ?></div></li>
        <?php endif;
    }

    protected function render_cart_item_plan($data = array(), $cart_key = ''){
        // var_dump($data);
        // array(20) { ["quantity"]=> int(1) ["key"]=> string(19) "checkout_individual" ["product_id"]=> int(2320) ["cart_item_type"]=> string(4) "plan" ["price"]=> string(6) "149.00" ["limit"]=> string(1) "1" ["unlimited"]=> string(2) "on" ["interval"]=> string(1) "1" ["period"]=> string(5) "month" ["never_expire"]=> string(2) "on" ["is_recurring"]=> string(0) "" ["trial_interval"]=> string(1) "0" ["trial_period"]=> string(3) "day" ["permalink"]=> string(59) "http://localhost:8888/wpclean/plan/professional-membership/" ["thumbnail"]=> string(0) "" ["title"]=> string(12) "Professional" ["address"]=> string(0) "" ["subtotal"]=> float(149) ["subtotal_vat"]=> float(14.9) ["price_total"]=> float(163.9) }
        ?>
        <!--cart-details_header--> 
        <div class="cart-product-details cart-product-plan">
            <?php if($data['thumbnail'] != ''){ ?>
            <a href="<?php echo $data['permalink']; ?>" class="cart-product-thumbnail">
                <?php echo $data['thumbnail']; ?>
            </a>
            <?php } ?>
            <div class="cart-product-desc">
                <div class="cart-product-title"><?php echo $data['title']; ?></div>
                <div class="subtitle"><?php _e( 'Membership plan', 'townhub-add-ons' ); ?></div>
            </div>
        </div>
        <!--cart-details_header end--> 
        <!--ccart-details_text-->          
        <div class="cart-details_text">
            <ul class="cart-listi">
                <?php
                if($data['price']): ?>
                <li class="clearfix bk-price"><?php esc_html_e( 'Price', 'townhub-add-ons' ); ?>
                    <div class="cart-dtright">
                        <strong class="plan-quantity"><?php echo sprintf(__(' %d x ','townhub-add-ons'), $data['quantity']); ?></strong>
                        <strong><?php echo townhub_addons_get_price_formated($data['price']); ?></strong>
                    </div>
                </li>   
                <?php endif; ?>
                <?php if (!empty($data['limit_text'])): ?>
                    <li class="clearfix bk-limit"><?php esc_html_e( 'Listings Limit', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo $data['limit_text']; ?></div></li>   
                <?php endif ?>
                <?php if (!empty($data['author_fee'])): ?>
                    <li class="clearfix bk-author-fee"><?php esc_html_e( 'Author Fee', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo sprintf(__( '%s %%', 'townhub-add-ons' ), $data['author_fee']); ?></div></li>   
                <?php endif ?>
                <?php if (!empty($data['period_text'])): ?>
                    <li class="clearfix bk-period"><?php esc_html_e( 'Period', 'townhub-add-ons' ); ?><div class="cart-dtright"><span class="period-text"><?php echo $data['period_text']; ?></span></div></li>   
                <?php endif ?>
                <?php if (!empty($data['expired'])): ?>
                    <li class="clearfix bk-expired"><?php esc_html_e( 'Expired', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo Esb_Class_Date::i18n($data['expired'], true); ?></div></li>   
                <?php endif ?>
                <?php if (!empty($data['trial_text'])): ?>
                    <li class="clearfix bk-trial"><?php esc_html_e( 'Trial', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo $data['trial_text']; ?></div></li>   
                <?php endif ?>
                <?php
                if($data['subtotal_vat']): ?>
                <li class="clearfix bk-taxes"><?php esc_html_e( 'VAT', 'townhub-add-ons' ); ?><div class="cart-dtright"><strong><?php echo townhub_addons_get_price_formated($data['subtotal_vat']); ?></strong></div></li>
                <?php endif; ?>
                
                <?php if (!empty($data['amount_of_discount']) && $data['amount_of_discount'] != ''): ?>
                    <li class="clearfix bk-infants"><?php esc_html_e( 'Discount amount', 'townhub-add-ons' ); ?><div class="cart-dtright"><?php echo sprintf(__( '- %s', 'townhub-add-ons' ), townhub_addons_get_price_formated( $data['amount_of_discount'] ) ); ?></div></li>
                <?php endif; ?>
            </ul>
        </div>
        <!--cart-details_text end --> 
        <?php

        $this->render_form_coupon($data['product_id']);
    }

    protected function render_terms(){
        if(townhub_addons_get_option('ck_agree_terms') != 'yes') return;
        ?>
        <div class="ck-form-item ck-form-terms"> 
            <label class="flex-items-center">
                <div class="ck-validate-field">
                    <input class="check" value="1" name="term_condition" type="checkbox" required/>
                </div>
                <div class="ck-terms-text"><?php echo townhub_addons_get_option('ck_terms');?></div>
            </label>
        </div>
        <?php
    }
}