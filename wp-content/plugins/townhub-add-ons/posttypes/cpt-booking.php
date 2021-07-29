<?php
/* add_ons_php */

class Esb_Class_Booking_CPT extends Esb_Class_CPT {
    protected $name = 'lbooking';

    protected function init(){
        parent::init();

        add_action( 'townhub_addons_lbooking_change_status_to_completed', array($this, 'lbooking_change_status_to_completed'), 10, 1 );   
        $logged_in_ajax_actions = array(
            
            'townhub_addons_cancel_lbooking',
            'townhub_addons_delete_lbooking',
            'townhub_addons_approve_lbooking',
        );
        foreach ($logged_in_ajax_actions as $action) {
            $funname = str_replace('townhub_addons_', '', $action);   
            add_action('wp_ajax_'.$action, array( $this, $funname ));
        }

        $not_logged_in_ajax_actions = array(
            'townhub_addons_booking_listing',
            'check_availability',
            // 'tour_calendar_metas',
            'hotel_room_dates',
            // 'house_dates',
            'sroom_dates',
            'listing_dates',
            'tslots_avai',
            'tickets_avai',
        );
        foreach ($not_logged_in_ajax_actions as $action) {
            $funname = str_replace('townhub_addons_', '', $action);   
            add_action('wp_ajax_'.$action, array( $this, $funname ));
            add_action('wp_ajax_nopriv_'.$action, array( $this, $funname ));
        }
        do_action( $this->name.'_cpt_init_after' );
    }

    public function register(){

        $labels = array( 
            'name' => __( 'Booking', 'townhub-add-ons' ),
            'singular_name' => __( 'Booking', 'townhub-add-ons' ), 
            'add_new' => __( 'Add New Booking', 'townhub-add-ons' ),
            'add_new_item' => __( 'Add New Booking', 'townhub-add-ons' ),
            'edit_item' => __( 'Edit Booking', 'townhub-add-ons' ),
            'new_item' => __( 'New Booking', 'townhub-add-ons' ),
            'view_item' => __( 'View Booking', 'townhub-add-ons' ),
            'search_items' => __( 'Search Bookings', 'townhub-add-ons' ),
            'not_found' => __( 'No Bookings found', 'townhub-add-ons' ),
            'not_found_in_trash' => __( 'No Bookings found in Trash', 'townhub-add-ons' ),   
            'parent_item_colon' => __( 'Parent Booking:', 'townhub-add-ons' ),
            'menu_name' => __( 'Listing Bookings', 'townhub-add-ons' ),  
        );

        $args = array(  
            'labels' => $labels,
            'hierarchical' => false,
            'description' => __( 'Listing booking', 'townhub-add-ons' ),
            // 'supports' => array( 'title', 'editor', 'author', 'thumbnail','comments','excerpt'/*, 'post-formats'*/),
            'supports' => array( '' ),
            'taxonomies' => array(),
            'public' => townhub_addons_get_option('pt_public_booking') == 'yes' ? true : false,
            'show_ui' => true,
            'show_in_menu' => true,//default from show_ui
            'menu_position' => 25,
            'menu_icon' => 'dashicons-calendar-alt',
            'show_in_nav_menus' => false,
            // 'publicly_queryable' => false,
            'exclude_from_search' => true,
            'has_archive' => false,
            'query_var' => true,
            'can_export' => false,
            'rewrite' => array( 'slug' => __('booking','townhub-add-ons') ),
            'capability_type' => 'post',

            'capabilities' => array(
                'create_posts' => 'do_not_allow', // false < WP 4.5, credit @Ewout
            ),
            'map_meta_cap' => true, // Set to `false`, if users are not allowed to edit/delete existing posts
        );


        register_post_type( $this->name, $args );
    }
    protected function set_meta_columns(){
        $this->has_columns = true;
    }
    public function meta_columns_head($columns){
        unset($columns['date']);
        unset($columns['title']);
        unset($columns['author']);
        unset($columns['comments']);
        $columns['bk_id']             = __('Booking','townhub-add-ons');
        $columns['_listing']             = __('Listing','townhub-add-ons');
        $columns['_lb_room']   = __('Room Type','townhub-add-ons');
        $columns['_status']             = __('Status','townhub-add-ons');
        $columns['_checkin']   = __('Check In','townhub-add-ons');
        $columns['_checkout']   = __('Check Out','townhub-add-ons');
        $columns['_nights']   = __('Nights','townhub-add-ons');
        $columns['_adults']   = __('Adults','townhub-add-ons');
        $columns['_children']   = __('Children','townhub-add-ons');
        $columns['_total']   = __('Total','townhub-add-ons');
        return $columns;
    }
    public function meta_columns_content($column_name, $post_ID){
        $qty = get_post_meta( $post_ID, ESB_META_PREFIX.'qty', true );
        if ($column_name == 'bk_id') {
            $lb_name = get_post_meta( $post_ID, ESB_META_PREFIX.'lb_name', true );
            $lb_email = get_post_meta( $post_ID, ESB_META_PREFIX.'lb_email', true );

            $user_obj   = get_userdata(get_post_meta($post_ID, ESB_META_PREFIX . 'user_id', true));
            if( $user_obj ){
                if( empty($lb_name) ) $lb_name = $user_obj->display_name;
                if( empty($lb_email) ) $lb_email = $user_obj->user_email;
            }

            echo '<div class="tips">';
            echo '<a href="'.admin_url('post.php?post='.$post_ID.'&action=edit' ).'"><strong>#'.$post_ID.'</strong></a>';
            echo __(' by ','townhub-add-ons'). '<strong>'.$lb_name.'</strong>';
            echo '<br><small class="meta email"><a href="mailto:'.$lb_email.'">'.$lb_email.'</a></small>';
            echo '</div>';
        }
        if ($column_name == '_listing') {
            $listing = get_post( get_post_meta( $post_ID, ESB_META_PREFIX.'listing_id', true ) );
            if (null != $listing) echo '<strong>'.$listing->post_title.'</strong>';
            if (null != $qty) echo '<strong>'._e( ' ( Event )', 'townhub-add-ons' ).'</strong>';
        }
        if ($column_name == '_lb_room') {
            $rooms =  get_post_meta( $post_ID, ESB_META_PREFIX.'rooms', true );
            // var_dump($rooms);

            if (null != $rooms){
                foreach ($rooms as $key => $room) {
                  echo '<strong>'.$room['title'].'</strong><br>';
                }
            } 
            
        }
        if ($column_name == '_status') {
            echo '<strong>'.townhub_addons_get_booking_status_text(get_post_meta( $post_ID, ESB_META_PREFIX.'lb_status', true )).'</strong>';
            
        }
        if ($column_name == '_checkin') {
            
            if( $checkin = get_post_meta( $post_ID, ESB_META_PREFIX.'checkin', true ) ) echo '<strong>'.Esb_Class_Date::i18n($checkin).'</strong>';
            
        }
        if ($column_name == '_checkout') {
            
            if( $checkout = get_post_meta( $post_ID, ESB_META_PREFIX.'checkout', true ) ) echo '<strong>'.Esb_Class_Date::i18n($checkout).'</strong>';
            
        }
        if ($column_name == '_nights') {
            echo '<strong>'.get_post_meta( $post_ID, ESB_META_PREFIX.'nights', true ).'</strong>';
            
        }
        if ($column_name == '_adults') {
            echo '<strong>'.get_post_meta( $post_ID, ESB_META_PREFIX.'adults', true ).'</strong>';
            
        }
        if ($column_name == '_children') {
            echo '<strong>'.get_post_meta( $post_ID, ESB_META_PREFIX.'children', true ).'</strong>';
            
        }
        if ($column_name == '_total') {
            $price_total_room = get_post_meta( $post_ID, ESB_META_PREFIX.'price_total_room', true );
            if (!empty($price_total_room) && $price_total_room != '') {
               echo '<strong>'.townhub_addons_get_price_formated($price_total_room).'</strong>';
            }else{
                echo '<strong>'.townhub_addons_get_price_formated(get_post_meta( $post_ID, ESB_META_PREFIX.'price_total', true )).'</strong>';
            }
            
        }
    }

    protected function set_meta_boxes(){
        $this->meta_boxes = array(
            'customer'       => array(
                'title'         => __( 'Customer', 'townhub-add-ons' ),
                'context'       => 'normal', // normal - side - advanced
                'priority'       => 'core', // default - high - core - low
                'callback_args'       => array(),
            ),
            'meta'       => array(
                'title'         => __( 'Meta Data', 'townhub-add-ons' ),
                'context'       => 'normal', // normal - side - advanced
                'priority'       => 'core', // default - high - core - low
                'callback_args'       => array(),
            ),
            'status'       => array(
                'title'         => __( 'Status', 'townhub-add-ons' ),
                'context'       => 'side', // normal - side - advanced
                'priority'       => 'high', // default - high - core - low
                'callback_args'       => array(),
            )
        );
    }

    public function lbooking_customer_callback($post, $args){
        wp_nonce_field( 'cth-cpt-fields', '_cth_cpt_nonce' );

        $listing_id = get_post_meta( $post->ID, ESB_META_PREFIX.'listing_id', true );
        $listing_post = get_post( $listing_id );

        $lb_name = get_post_meta( $post->ID, ESB_META_PREFIX.'lb_name', true );
        $lb_email = get_post_meta( $post->ID, ESB_META_PREFIX.'lb_email', true );
        $lb_phone = get_post_meta( $post->ID, ESB_META_PREFIX.'lb_phone', true );
        $lb_user = 0;

        $user_obj   = get_userdata(get_post_meta($post->ID, ESB_META_PREFIX . 'user_id', true));
        if( $user_obj ){
            $lb_user = $user_obj->ID;
            if( empty($lb_name) ) $lb_name = $user_obj->display_name;
            if( empty($lb_email) ) $lb_email = $user_obj->user_email;
            if( empty($lb_phone) ) $lb_phone = get_user_meta( $user_obj->ID, ESB_META_PREFIX.'phone', true);
        }

        $price_based = get_post_meta( $post->ID, ESB_META_PREFIX.'price_based', true );
        $_price = get_post_meta( $post->ID, ESB_META_PREFIX.'price', true );

        $billingDetails = Esb_Class_User::billingDetails($lb_user);

        // listing phones
        $lphone = get_post_meta( $listing_id, ESB_META_PREFIX.'phone', true );
        $lwhatsapp = get_post_meta( $listing_id, ESB_META_PREFIX.'whatsapp', true );
        ?>
        <h2><?php _e( 'Customer details', 'townhub-add-ons' ); ?></h2>
        <p class="lbk-desc"></p>
        <table class="form-table lorder-details">
            <tbody>
                <tr class="hoz">
                    <th class="w20"><?php _e( 'Name :', 'townhub-add-ons' ); ?></th>
                    <td><a href="<?php echo get_edit_user_link( $lb_user ); ?>"><?php echo esc_html( $lb_name ); ?></a></td>
                </tr>
                <tr class="hoz">
                    <th class="w20"><?php _e( 'Email : ', 'townhub-add-ons' ); ?></th>
                    <td><a href="mailto:<?php echo esc_attr( $lb_email ); ?>"><?php echo esc_html( $lb_email ); ?></a></td>
                </tr>
                <tr class="hoz">
                    <th class="w20"><?php _e( 'Phone :', 'townhub-add-ons' ); ?></th>
                    <td><span><?php echo esc_html( $lb_phone ); ?></span></td>
                </tr>
                <?php 
                if( !empty($billingDetails) ): ?>
                <tr class="hoz">
                    <th class="w20"><?php _e( 'Billing Details :', 'townhub-add-ons' ); ?></th>
                    <td><span><?php echo $billingDetails; ?></span></td>
                </tr>
                <?php 
                endif; ?>
                <tr class="hoz">
                    <th class="w20"><?php _e( 'Listing Item :', 'townhub-add-ons' ); ?></th>
                    <?php 
                    
                    if (null != $listing_post) echo '<td><span>'.$listing_post->post_title.'</span></td>';
                    ?>

                </tr>

                <?php 
                if( !empty($lphone) ): ?>
                <tr class="hoz lb-lphone">
                    <th class="w20"><?php _ex( 'Listing Phone:', 'Booking post', 'townhub-add-ons' ); ?></th>
                    <td><span><a href="tel:<?php echo esc_attr($lphone); ?>"><?php echo esc_html($lphone); ?></a></span></td>
                </tr>
                <?php 
                endif; ?>

                <?php 
                if( !empty($lwhatsapp) ): ?>
                <tr class="hoz lb-lwhatsapp">
                    <th class="w20"><?php _ex( 'Listing Whatsapp:', 'Booking post', 'townhub-add-ons' ); ?></th>
                    <td><span><a href="https://wa.me/<?php echo esc_attr($lwhatsapp); ?>"><?php echo esc_html($lwhatsapp); ?></a></span></td>
                </tr>
                <?php 
                endif; ?>

                <?php
                $checkin = get_post_meta( $post->ID, ESB_META_PREFIX.'checkin', true );
                $checkout = get_post_meta( $post->ID, ESB_META_PREFIX.'checkout', true );
                if ( !empty($checkin) ): ?>
                    <tr class="hoz">
                        <th class="w20"><?php _e( 'Dates:', 'townhub-add-ons' ); ?></th>
                        <td><span><?php echo Esb_Class_Date::i18n($checkin); if($checkout != '') echo sprintf(__( ' - %s', 'townhub-add-ons' ), Esb_Class_Date::i18n($checkout) ); ?></span></td>
                    </tr>
                <?php endif; ?>

                <?php $this->tour_booking_details($post); ?>
                <?php $bktimes = get_post_meta( $post->ID, ESB_META_PREFIX.'times', true ); 
                if(!empty($bktimes)):
                ?>
                <tr class="hoz">
                    <th class="w20"><?php _e( 'Times:', 'townhub-add-ons' ); ?></th>
                    <td><span><?php echo implode("<br />", $bktimes ); ?></span></td>
                </tr>
                <?php endif; ?>

                <?php $bkslots = get_post_meta( $post->ID, ESB_META_PREFIX.'time_slots', true ); 
                if(!empty($bkslots)):
                    $bkTimeSlots = array();
                    foreach ($bkslots as $bksl) {
                        $bksl = (array)$bksl;
                        $bkTimeSlots[] = $bksl['title'];
                    }
                ?>
                <tr class="hoz">
                    <th class="w20"><?php _e( 'Time Slots:', 'townhub-add-ons' ); ?></th>
                    <td><span><?php echo implode("<br />", $bkTimeSlots ); ?></span></td>
                </tr>
                <?php endif; ?>

                <?php $qty = get_post_meta( $post->ID, ESB_META_PREFIX.'qty', true ); 
                    if(!empty($qty) && $qty != null){?>
                        <tr class="hoz">
                            <th class="w20"><?php _e( 'Tickets:', 'townhub-add-ons' ); ?></th>
                            <td><span><?php echo $qty; ?></span></td>
                        </tr> 
                <?php 
                    }
                ?>

                <?php $bk_qtts = get_post_meta( $post->ID, ESB_META_PREFIX.'bk_qtts', true );
                if( $price_based == 'listing' && !empty($bk_qtts) ): ?>
                <tr class="hoz">
                    <th class="w20"><?php _e( 'Quantity:', 'townhub-add-ons' ); ?></th>
                    <td><span><?php echo sprintf(_x( '%1$s x %2$s', 'Booking quantity', 'townhub-add-ons' ), $bk_qtts, $_price); ?></span></td>
                </tr>
                <?php endif; ?>
                
                <?php 
                $tickets = get_post_meta( $post->ID, ESB_META_PREFIX.'tickets', true ); 
                if( !empty($tickets) ) {
                ?>
                    <tr class="hoz">
                        <th class="w20"><?php esc_html_e( 'Tickets: ', 'townhub-add-ons' ); ?></th>
                        <td>
                            <ul class="no-list-style bkrooms-rooms">
                            <?php foreach ( (array)$tickets as $key => $ticket) { ?>
                                <li class="ctour-ticket-slot">
                                    <span class="booking-title"><?php echo esc_html($ticket['title']); ?></span>
                                    <span class="booking-text">
                                        <?php if(!empty($ticket['quantity'])) echo '<div>'.sprintf(__( '%s  x %s', 'townhub-add-ons' ), (int)$ticket['quantity'], townhub_addons_get_price_formated($ticket['price']) ).'</div>'; ?>
                                    </span>
                                </li>
                            <?php } ?>
                            </ul>
                        </td>
                    </tr>
                <?php  
                } ?>
                <?php 
                $bk_menus = get_post_meta( $post->ID, ESB_META_PREFIX.'bk_menus', true ); 
                if( !empty($bk_menus) ) {
                ?>
                    <tr class="hoz">
                        <th class="w20"><?php esc_html_e( 'Menus: ', 'townhub-add-ons' ); ?></th>
                        <td>
                            <ul class="no-list-style bkrooms-rooms">
                            <?php foreach ( (array)$bk_menus as $key => $ticket) { ?>
                                <li class="ctour-ticket-slot">
                                    <span class="booking-title"><?php echo esc_html($ticket['title']); ?></span>
                                    <span class="booking-text">
                                        <?php if(!empty($ticket['quantity'])) echo '<div>'.sprintf(__( '%s  x %s', 'townhub-add-ons' ), (int)$ticket['quantity'], townhub_addons_get_price_formated($ticket['price']) ).'</div>'; ?>
                                    </span>
                                </li>
                            <?php } ?>
                            </ul>
                        </td>
                    </tr>
                <?php  
                } ?>
                <?php 
                $tour_slots = get_post_meta( $post->ID, ESB_META_PREFIX.'tour_slots', true ); 
                if( !empty($tour_slots) ) { ?>
                    <tr class="hoz">
                        <th class="w20"><?php esc_html_e( 'Slots: ', 'townhub-add-ons' ); ?></th>
                        <td>
                            <ul class="no-list-style bkrooms-rooms">
                            <?php foreach ($tour_slots as $key => $ticket) { //var_dump($ticket); ?>
                                <li class="ctour-ticket-slot">
                                    <span class="booking-title"><?php echo esc_html($ticket['title']) //get_the_title($ticket['ID']); ?></span>
                                    <span class="booking-text">
                                        <?php if(!empty($ticket['adults'])) echo '<div>'.sprintf(__( 'Adults: %s x %s', 'townhub-add-ons' ), (int)$ticket['adults'], townhub_addons_get_price_formated($ticket['price']) ).'</div>'; ?>
                                        <?php if(!empty($ticket['children'])) echo '<div>'.sprintf(__( 'Children: %s x %s', 'townhub-add-ons' ), (int)$ticket['children'], townhub_addons_get_price_formated($ticket['child_price']) ).'</div>'; ?>
                                    </span>
                                </li>
                            <?php } ?>
                            </ul>
                        </td>
                    </tr>
                <?php  
                } ?>

                <?php 
                    $bkcoupon = get_post_meta( $post->ID, ESB_META_PREFIX.'bkcoupon', true ); 
                    if(!empty($bkcoupon) && $bkcoupon != null){?>
                        <tr class="hoz">
                            <th class="w20"><?php _e( 'Coupon Code:', 'townhub-add-ons' ); ?></th>
                            <td><span><?php echo $bkcoupon; ?></span></td>
                        </tr> 

                <?php 
                    }
                ?>
                <?php 
                    $date_event = get_post_meta( $post->ID, ESB_META_PREFIX.'date_event', true );
                    if(!empty($date_event) && $date_event != null){?>
                        <tr class="hoz">
                            <th class="w20"><?php _e( 'Date Event:', 'townhub-add-ons' ); ?></th>
                            <td><span><?php echo $date_event; ?></span></td>
                        </tr> 
                <?php 
                    }
                ?>
                <?php  $rooms =  get_post_meta( $post->ID, ESB_META_PREFIX.'rooms', true );
                     $quantity = get_post_meta( $post->ID, ESB_META_PREFIX.'quantity', true );
                    if (!empty($quantity) && $quantity != '' && $quantity > 0) {
                       if(!empty($rooms) && $rooms != '' ) {
                            foreach ($rooms as $key => $room) {?>
                           <tr class="hoz">
                                <th class="w20"><?php printf( esc_html__( 'Room %s : ', 'townhub-add-ons' ),$key+1); ?></th>
                                <td><?php printf( esc_html__( '%s  x %s', 'townhub-add-ons' ),get_the_title($room['ID']),$quantity); ?></td>
                            </tr>
                       
                            
                    <?php  }
                        }
                            
                    }else{

                        if(!empty($rooms) && $rooms != '' ) {
                            foreach ($rooms as $key => $room) {?>
                           <tr class="hoz">
                                <th class="w20"><?php printf( esc_html__( 'Room %s : ', 'townhub-add-ons' ),$key+1); ?></th>
                                <td><?php printf( esc_html__( '%s  x %s', 'townhub-add-ons' ),get_the_title($room['ID']),$room['quantity']); ?></td>
                            </tr>
                       
                            
                    <?php  
                            }
                        }
                    }
                    
                ?>

                <?php  
                $rooms_old = get_post_meta( $post->ID, ESB_META_PREFIX.'rooms_old_data', true );
                if( is_array($rooms_old) && !empty($rooms_old) ) { 
                foreach ( $rooms_old as $rdata ) {
                    ?>
                    <tr class="hoz">
                        <th class="w20"><?php echo $rdata['title'];?></th>
                        <td>
                            <?php 
                            foreach ($rdata['rdates'] as $rdte => $rdval) {

                                ?>
                                <div class="bkroom-date">
                                    <div class="bkroom-date-title"><?php echo Esb_Class_Date::i18n( $rdte ); ?></div>
                                    <div class="bkroom-date-persons">
                                        <?php if( !empty($rdata['quantity']) ): ?>
                                        <div class="bkroom-date-person">
                                            <strong class="bkroom-date-quantity"><?php echo sprintf( _x('Quantity %d x ', 'checkout room dates','townhub-add-ons'), $rdata['quantity'] ); ?></strong>
                                            <strong class="bkroom-date-price"><?php echo townhub_addons_get_price_formated($rdval); ?></strong>
                                        </div>
                                        <?php endif; ?>
                                        
                                    </div>
                                </div>
                                <?php
                            } ?>
                        </td>
                    </tr>
                    <?php
                    }  
                } ?>

                <?php  
                $rooms_persons = get_post_meta( $post->ID, ESB_META_PREFIX.'rooms_person_data', true );
                if( is_array($rooms_persons) && !empty($rooms_persons) ) { 
                foreach ( $rooms_persons as $rdata ) {
                    ?>
                    <tr class="hoz">
                        <th class="w20"><?php echo $rdata['title'];?></th>
                        <td>
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
                        </td>
                    </tr>
                    <?php
                    }  
                } ?>

                <tr class="hoz">
                    <?php 
                        $listing_id = get_post_meta( $post->ID, ESB_META_PREFIX.'listing_id', true );
                        $services = get_post_meta($listing_id, ESB_META_PREFIX.'lservices', true);
                        if(isset($services) && is_array($services) && $services!= '') {
                            $value_key_ser  = array();
                            $value_serv = array();
                            $addservices = get_post_meta( $post->ID, ESB_META_PREFIX.'addservices', true );
                            if( !empty($addservices) && is_array($addservices) ){
                                foreach ($addservices  as $key => $item_serv) {
                                    // var_dump($item_serv);
                                    $value_key_ser[]  = array_search($item_serv,array_column($services,  'service_id'));
                                }
                            }
                            foreach ($value_key_ser as $key => $value) {
                                 $value_serv[] = $services[$value];
                            }
                            if ( !empty( $value_serv) && is_array( $value_serv) ) {   
                                ?>
                                <th class="w20"><?php _e( 'Extra Services :', 'townhub-add-ons' ); ?></th>
                                <td>
                                    <ul>
                                        <?php
                                        foreach ($value_serv as $key => $value) {
                                            echo '<li>'.$value['service_name'].'</li>';
                                        }
                                        ?>
                                    </ul>
                                </td>
                                <?php
                            }
                        } 
                    ?>
                </tr>  
                
                <?php 
                $book_services = get_post_meta( $post->ID, ESB_META_PREFIX.'book_services', true ); 
                if( is_array($book_services) && !empty($book_services) ) {
                ?>
                    <tr class="hoz">
                        <th class="w20"><?php esc_html_e( 'Additional Services: ', 'townhub-add-ons' ); ?></th>
                        <td>
                            <ul class="no-list-style bkrooms-rooms">
                            <?php foreach ( $book_services as $key => $service ) { ?>
                                <li class="ctour-ticket-slot">
                                    <span class="booking-title"><?php echo esc_html($service['title']); ?></span>
                                    <span class="booking-text">
                                        <?php if(!empty($service['quantity'])) echo '<div>'.sprintf(__( '%s  x %s', 'townhub-add-ons' ), (int)$service['quantity'], townhub_addons_get_price_formated($service['price']) ).'</div>'; ?>
                                    </span>
                                </li>
                            <?php } ?>
                            </ul>
                        </td>
                    </tr>
                <?php  
                } ?>


                <tr class="hoz">
                    <th class="w20"><?php _e( 'Totals:', 'townhub-add-ons' ); ?></th>
                    <td><strong><?php echo townhub_addons_get_price_formated( get_post_meta( $post->ID, ESB_META_PREFIX.'price_total', true ) ); ?></strong></td>
                </tr>

            </tbody>
        </table>
        <?php   
    }

    protected function tour_booking_details($post){
        $booking_type = get_post_meta( $post->ID, ESB_META_PREFIX.'booking_type', true  );
        if($booking_type != 'tour') return;

        ?>
        <tr class="hoz">
            <th class="w20"><?php _e( 'Adults:', 'townhub-add-ons' ); ?></th>
            <td><span><?php echo get_post_meta( $post->ID, ESB_META_PREFIX.'adults', true ); ?></span></td>
        </tr>
        <tr class="hoz">
            <th class="w20"><?php _e( 'Children:', 'townhub-add-ons' ); ?></th>
            <td><span><?php echo get_post_meta( $post->ID, ESB_META_PREFIX.'children', true ); ?></span></td>
        </tr>
        <tr class="hoz">
            <th class="w20"><?php _e( 'Infants:', 'townhub-add-ons' ); ?></th>
            <td><span><?php echo get_post_meta( $post->ID, ESB_META_PREFIX.'infants', true ); ?></span></td>
        </tr>
        <?php
    }

    public function lbooking_meta_callback($post, $args){
        
        ?>
        <h2><?php _e( 'Booking Meta', 'townhub-add-ons' ); ?></h2>
        <p class="lbk-desc"></p>
        <table class="form-table lorder-details">
            <tbody>
                

                <?php $adults = get_post_meta( $post->ID, ESB_META_PREFIX.'adults', true );
                if( !empty($adults) ): ?>
                <tr class="hoz">
                    <th class="w20"><?php _e( 'Adults:', 'townhub-add-ons' ); ?></th>
                    <td><span><?php echo $adults; ?></span></td>
                </tr>
                <?php endif; ?>
                <?php $children = get_post_meta( $post->ID, ESB_META_PREFIX.'children', true );
                if( !empty($children) ): ?>
                <tr class="hoz">
                    <th class="w20"><?php _e( 'Children:', 'townhub-add-ons' ); ?></th>
                    <td><span><?php echo $children; ?></span></td>
                </tr>
                <?php endif; ?>
                <?php $infants = get_post_meta( $post->ID, ESB_META_PREFIX.'infants', true );
                if( !empty($infants) ): ?>
                <tr class="hoz">
                    <th class="w20"><?php _e( 'Infants:', 'townhub-add-ons' ); ?></th>
                    <td><span><?php echo $infants; ?></span></td>
                </tr>
                <?php endif; ?>
                <tr class="hoz">
                    <th class="w20"><?php _e( 'Gateway', 'townhub-add-ons' ); ?></th>
                    <td><?php echo townhub_addons_payment_names(get_post_meta( $post->ID, ESB_META_PREFIX.'payment_method', true ));?></td>
                </tr>
                <?php 
                if( 'woo' == get_post_meta( $post->ID, ESB_META_PREFIX.'payment_method', true ) ):
                ?>
                <tr class="hoz">
                    <th class="w20"><?php _e( 'WooCommerce Order', 'townhub-add-ons' ); ?></th>
                    <td><a href="<?php echo get_edit_post_link( get_post_meta( $post->ID, ESB_META_PREFIX.'woo_order', true ) ); ?>"><?php echo sprintf( __( '#%s', 'townhub-add-ons' ), get_post_meta( $post->ID, ESB_META_PREFIX.'woo_order', true ) ); ?></a></td>
                </tr>
                <?php endif; ?>


                <tr class="hoz">
                    <th class="w20"><?php _e( 'Note', 'townhub-add-ons' ); ?></th>
                    <td>
                        <textarea name="notes" id="notes" cols="30" rows="5" class="w100"><?php echo get_post_meta( $post->ID, ESB_META_PREFIX.'notes', true );?></textarea>
                    </td>
                </tr>

                <?php $cv_pdf_id = get_post_meta( $post->ID, ESB_META_PREFIX.'cv_pdf_id', true );
                if( !empty($cv_pdf_id) ): ?>
                <tr class="hoz bkcv_file">
                    <th class="w20"><?php _e( 'CV:', 'townhub-add-ons' ); ?></th>
                    <td><span><a href="<?php echo wp_get_attachment_url( $cv_pdf_id ); ?>" target="_blank"><?php echo get_the_title( $cv_pdf_id ); ?></a></span></td>
                </tr>
                <?php endif; ?>
                

            </tbody>
        </table>
        <?php   
    }

    public function lbooking_status_callback($post, $args){
        /*
         * Use get_post_meta() to retrieve an existing value
         * from the database and use the value for the form.
         */
        $value = get_post_meta( $post->ID, ESB_META_PREFIX.'lb_status', true );

        $status = townhub_addons_get_booking_statuses_array();
        ?>
        <table class="form-table lorder-details">
            <tbody>
                <tr class="hoz">
                    <td>
                        <select name="lb_status" class="w100">
                        <?php 
                        foreach ($status as $sts => $lbl) {
                            echo '<option value="'.$sts.'" '.selected( $value, $sts, false ).'>'.$lbl.'</option>';
                        }
                        ?>
                        </select>
                    </td>
                </tr>
                
            </tbody>
        </table>
        <?php   
    }

    public function save_post($post_id, $post, $update){
        if(!$this->can_save($post_id)) return;

        if(isset($_POST['notes'])){
            $new_val = sanitize_textarea_field( $_POST['notes'] ) ;
            $origin_val = get_post_meta( $post_id, ESB_META_PREFIX.'notes', true );
            if($new_val !== $origin_val){
                update_post_meta( $post_id, ESB_META_PREFIX.'notes', $new_val );
            }
            
        }

        
        if(isset($_POST['lb_status'])){
            $new_status = sanitize_text_field( $_POST['lb_status'] ) ;
            $origin_status = get_post_meta( $post_id, ESB_META_PREFIX.'lb_status', true );
            if($new_status !== $origin_status){
                update_post_meta( $post_id, ESB_META_PREFIX.'lb_status', $new_status );

                // unhook this function so it doesn't loop infinitely
                remove_action( 'save_post_lbooking', 'townhub_addons_lbooking_save_meta_box_datas', 10, 1 );
                    do_action('townhub_addons_lbooking_change_status_'.$origin_status.'_to_'.$new_status, $post_id );
                    do_action('townhub_addons_lbooking_change_status_to_'.$new_status, $post_id );
                // re-hook this function
                add_action( 'save_post_lbooking', 'townhub_addons_lbooking_save_meta_box_datas', 10, 1  );
                    
            }
        }
    }
    public function lbooking_change_status_to_completed($booking_id = 0){
        if(is_numeric($booking_id)&&(int)$booking_id > 0){
            $listing_id = get_post_meta( $booking_id, ESB_META_PREFIX.'listing_id', true );

            if( townhub_addons_get_option('booking_author_woo') == 'yes' && get_post_meta( $booking_id, ESB_META_PREFIX.'woo_order', true ) != '' ){
                $woo_order = wc_get_order( get_post_meta( $booking_id, ESB_META_PREFIX.'woo_order', true ) );
                if(!empty($woo_order)){
                    $woo_order->update_status( 'completed' , __( 'Administrator has approved this payment.', 'townhub-add-ons' ) );
                }
            }else{
                if ( !update_post_meta( $booking_id, ESB_META_PREFIX.'lb_status',  'completed'  ) ) {
                    if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). "Update booking status to completed failure" . PHP_EOL, 3, ESB_LOG_FILE);
                }else{
                    // push customer notification
                    $customer = get_user_by( 'email', get_post_meta( $booking_id, ESB_META_PREFIX.'lb_email', true ) );
                    if ( ! empty( $customer ) ) {
                        if( townhub_addons_get_option('db_hide_bookings') != 'yes' ){
                            // townhub_addons_user_add_notification($customer->ID, array(
                            //     'type' => 'booking_approved',
                            //     'message' => sprintf(__( 'Your booking for <strong>%s</strong> listing has been approved.', 'townhub-add-ons' ), get_post_field('post_title', $listing_id) )
                            // ));
                        }
                            
                    }
                    do_action( 'townhub_addons_edit_booking_approved', $booking_id );

                }
            }
        } 

    }
    public function booking_listing(){
        $json = array(
            'success' => false,
            'data' => array(
                'POST'=>$_POST,
            )
        );
        Esb_Class_Dashboard::verify_nonce('townhub-add-ons');
        $listing_id = $_POST['listing_id'];
        if(is_numeric($listing_id) && (int)$listing_id > 0){
            
            $lb_name = '';
            $lb_email = '';
            $lb_phone = '';

            $booking_title = _x( '%1$s booking request by %2$s', 'Inquiry post title', 'townhub-add-ons' ); 
            $booking_datas = array();
            // $booking_metas_loggedin = array();
            $buser_id = 0;
            $current_user = wp_get_current_user();
            if( $current_user->exists() ){
                $lb_name = $current_user->display_name;
                $lb_email = get_user_meta( $current_user->ID, ESB_META_PREFIX.'email', true);
                $lb_phone = get_user_meta( $current_user->ID, ESB_META_PREFIX.'phone', true);
                $buser_id = $current_user->ID;
            }
            // override user details by booking details
            if( !empty($_POST['lb_name']) ){
                $lb_name = esc_html( $_POST['lb_name'] ) ;
            }

            if( !empty($_POST['lb_email']) ){
                $lb_email = esc_html( $_POST['lb_email'] );
            }

            if( !empty($_POST['lb_phone']) ){
                $lb_phone = esc_html( $_POST['lb_phone'] );
            }

            if( empty($lb_email) && $current_user->exists() ) $lb_email = $current_user->user_email;

            $booking_datas['post_title'] = sprintf( $booking_title, get_the_title( $listing_id ), $lb_name );

            $booking_datas['post_content'] = '';
            //$booking_datas['post_author'] = '0';// default 0 for no author assigned
            $booking_datas['post_status'] = 'publish';
            $booking_datas['post_type'] = 'lbooking';

            do_action( 'townhub_addons_booking_request_before', $booking_datas );
            $booking_id = wp_insert_post($booking_datas ,true );

            if (!is_wp_error($booking_id)) {
                set_post_thumbnail( $booking_id, get_post_thumbnail_id( $listing_id ) );
                $listing_author_id = get_post_field( 'post_author', $listing_id );
                Esb_Class_Dashboard::add_notification($listing_author_id, array(
                    'type' => 'new_booking',
                    'entity_id'     => $listing_id,
                    'actor_id'      => $buser_id
                ));

                $meta_fields = array(
                    // 'listing_id' => 'text', listing_id will be set manually
                    'lb_name'               => 'text',
                    'lb_email'              => 'text',
                    'lb_phone'              => 'text',

                    'notes'                 => 'text',

                    // 'lb_quantity'           => 'text',
                    // 'lb_date'               => 'text',
                    // 'lb_time'               => 'text',
                    // 'lb_add_info'           => 'text',
                    'booking_type'       => 'text',
                    'price_based'       => 'text',

                    'checkin'              => 'text',
                    'checkout'              => 'text',
                    // 'nights'              => 'text',
                    // 'days'              => 'text',
                    'adults'              => 'text',
                    'children'              => 'text',
                    'infants'              => 'text',

                    'bk_qtts'               => 'text',
                );

                $meta_fields = apply_filters( 'esb_booking_request_meta_fields', $meta_fields );
                $booking_metas = array();
                foreach($meta_fields as $fname => $ftype){
                    if($ftype == 'array'){
                        $booking_metas[$fname] = isset($_POST[$fname]) ? $_POST[$fname]  : array();
                    }else{
                        $booking_metas[$fname] = isset($_POST[$fname]) ? wp_kses_post($_POST[$fname]) : '';
                    }
                }

                // check for custom avatar upload
                if (isset($_FILES['cv_upload']) && $_FILES['cv_upload']['error'] === UPLOAD_ERR_OK) {
                    $movefile = townhub_addons_handle_image_upload($_FILES['cv_upload']);

                    if (is_array($movefile)) {
                        // https://wordpress.stackexchange.com/questions/40301/how-do-i-set-a-featured-image-thumbnail-by-image-url-when-using-wp-insert-post
                        // https://codex.wordpress.org/Function_Reference/wp_insert_attachment
                        // Prepare an array of post data for the attachment.
                        $attachment = array(
                            // 'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
                            'post_mime_type' => $movefile['type'],
                            'post_title'     => sanitize_file_name(basename($movefile['file'])),
                            'post_content'   => '',
                            'post_status'    => 'inherit',
                        );

                        // Insert the attachment.
                        $attach_id = wp_insert_attachment($attachment, $movefile['file'], $booking_id);

                        if ($attach_id != 0) {
                            // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                            require_once ABSPATH . 'wp-admin/includes/image.php';

                            // Generate the metadata for the attachment, and update the database record.
                            $attach_data = wp_generate_attachment_metadata($attach_id, $movefile['file']);
                            // return value from update_post_meta -  https://codex.wordpress.org/Function_Reference/update_post_meta
                            // Returns meta_id if the meta doesn't exist, otherwise returns true on success and false on failure. NOTE: If the meta_value passed to this function is the same as the value that is already in the database, this function returns false.
                            wp_update_attachment_metadata($attach_id, $attach_data);

                            
                            $booking_metas['cv_pdf_id'] = $attach_id;
                        } else {
                            $json['data']['cv_upload_error'] = __("wp_insert_attachment error on cv upload", 'townhub-add-ons');
                        }
                    } else {
                        $json['data']['cv_upload_error'] = $movefile;
                    }
                }
                // end custom avatar upload

                $listing_price = (float)get_post_meta( $listing_id, '_price', true );
                $children_price = (float)get_post_meta( $listing_id, ESB_META_PREFIX .'children_price', true );
                $infant_price = (float)get_post_meta( $listing_id, ESB_META_PREFIX .'infant_price', true );
                // new services
                $bk_services= (isset($_POST['bk_services']) && !empty($_POST['bk_services'])) ? $_POST['bk_services'] : array();
                $services = get_post_meta($listing_id, ESB_META_PREFIX.'lservices', true);
                // new services
                $bk_services_data = array();
                $new_ser_total = 0;
                $total_services = 0;
                if(isset($services) && is_array($services) && !empty($services)) {
                    $addservices = (isset($_POST['addservices']) && !empty($_POST['addservices']) ) ? (array)$_POST['addservices'] : array();
                    foreach ($addservices  as $key => $item_serv) {
                        $lserid = array_search($item_serv, array_column($services,  'service_id'));

                        if( $lserid !== false ){
                            $bkedSer = $services[$lserid];
                            $total_services += (float)$bkedSer['service_price'];
                        }
                    }
                    // new services
                    if( !empty($bk_services) ){
                        foreach ($bk_services  as $sid => $ser_qtt) {
                            if( empty($ser_qtt) ) continue;
                            $lserid = array_search($sid, array_column($services,  'service_id'));
                            if( $lserid !== false ){
                                $bkedSer = $services[$lserid];
                                $bk_services_data[] = array(
                                    '_id' => $bkedSer['service_id'],
                                    'service_id' => $bkedSer['service_id'],
                                    'quantity' => $ser_qtt,
                                    'title' => $bkedSer['service_name'],
                                    'price' => $bkedSer['service_price'],
                                );
                                $new_ser_total += floatval($bkedSer['service_price']) * $ser_qtt;
                            }
                        }
                    }
                }
                $booking_metas['book_services'] = $bk_services_data;



                $booking_metas['listing_id'] = $listing_id;
                $booking_metas['lb_status'] = 'pending'; // pending - completed - failed - refunded - canceled
                // user id for non logged in user, will be override with loggedin info
                
                $booking_metas['lb_name'] =  $lb_name;
                $booking_metas['lb_email'] =  $lb_email;
                $booking_metas['lb_phone'] =  $lb_phone;

                $booking_metas['user_id'] = $buser_id;
                $booking_metas['payment_method'] = 'request'; // banktransfer - paypal - stripe - woo
                
                // merge with logged in customser data
                // $booking_metas = array_merge($booking_metas,$booking_metas_loggedin);

                // woo payment
                $booking_metas['payment_method'] = 'request'; // banktransfer - paypal - stripe - woo
                $booking_metas['bk_form_type'] = 'inquiry';
                // $cmb_prefix = '_cth_';
                foreach ($booking_metas as $key => $value) {
                    update_post_meta( $booking_id, ESB_META_PREFIX.$key,  $value  );
                }

                // for new event tickets
                $tickets_data = array();
                $tickets_price = 0;
                $tickets = get_post_meta( $listing_id, ESB_META_PREFIX.'tickets', true );
                $tickets_booking = isset( $_POST['tickets'] ) ? (array)$_POST['tickets'] : array();
                $tickets_booking = array_filter( $tickets_booking );
                if( !empty($tickets_booking) && !empty($tickets) ){
                    foreach ($tickets_booking as $tid => $tqtt) {
                        // search for ticket _id
                        $tkkey = array_search($tid, array_column($tickets, '_id'));
                        if( false !== $tkkey ){
                            $tkobj = array(
                                '_id'               => esc_attr($tid),
                                'title'             => esc_html($tickets[$tkkey]['name']),
                                'price'             => floatval( $tickets[$tkkey]['price'] ),
                                'quantity'          => (int)$tqtt,
                            );

                            $tickets_price += $tkobj['quantity'] * $tkobj['price'];
                            $tickets_data[] = $tkobj;
                        }
                            
                    }
                }
                if( !empty($tickets_data) ){
                    update_post_meta( $booking_id, ESB_META_PREFIX.'tickets', $tickets_data );   
                }

                // for res menu
                $resmenus_data = array();
                $resmenus_price = 0;
                $resmenus = (array)get_post_meta( $listing_id, ESB_META_PREFIX.'resmenus', true );
                if( isset($_POST['bkfmenus']) && !empty($_POST['bkfmenus']) && !empty($resmenus) ){
                    $bkfmenus = array_filter($_POST['bkfmenus']);
                    foreach ($bkfmenus as $tid => $tqtt) {
                        // search for ticket _id
                        $tkkey = array_search($tid, array_column($resmenus, '_id'));
                        if( false !== $tkkey ){
                            $tkobj = array(
                                '_id'               => $tid,
                                'title'             => $resmenus[$tkkey]['name'],
                                'price'             => floatval( $resmenus[$tkkey]['price'] ),
                                'quantity'          => (int)$tqtt,
                                'adults'            => (int)$tqtt,
                            );

                            $resmenus_price += $tkobj['quantity'] * $tkobj['price'];
                            $resmenus_data[] = $tkobj;
                        }
                            
                    }
                }
                if( !empty($resmenus_data) ){
                    update_post_meta( $booking_id, ESB_META_PREFIX.'bk_menus', $resmenus_data );   
                }

                $price_based = isset($_POST['price_based']) && !empty($_POST['price_based']) ? $_POST['price_based'] : 'per_night';

                // add _price for woo
                
                if( !empty($tickets_price) ){
                    $listing_price = $tickets_price;
                }elseif( !empty($resmenus_price) ){
                    $listing_price = $resmenus_price;
                }else{
                    update_post_meta( $booking_id, ESB_META_PREFIX.'price', $listing_price ); 
                    update_post_meta( $booking_id, ESB_META_PREFIX.'children_price', $children_price ); 
                    update_post_meta( $booking_id, ESB_META_PREFIX.'infant_price', $infant_price ); 
                    switch ($price_based) {
                        case 'none':
                            $listing_price = 0;
                            break;
                        case 'listing':
                            // update price for per listing value
                            // update_post_meta( $booking_id, ESB_META_PREFIX.'price', $listing_price );   
                            if( !empty($booking_metas['bk_qtts']) ) $listing_price *= $booking_metas['bk_qtts'];
                            break;
                        case 'per_hour': 
                            $slots_count = isset($_POST['slots']) && is_array($_POST['slots']) && !empty($_POST['slots']) ? count($_POST['slots']) : 0;
                            $listing_price *= $slots_count;
                            break;
                        case 'hour_person':
                            $slots_count = isset($_POST['slots']) && is_array($_POST['slots']) && !empty($_POST['slots']) ? count($_POST['slots']) : 0;
                            if( !empty($booking_metas['adults']) ){
                                $listing_price *= (int)$booking_metas['adults'] * $slots_count;
                            }else{
                                $listing_price = 0;
                            }
                            if( !empty($booking_metas['children']) ){
                                
                                $listing_price += $children_price * (int)$booking_metas['children'] * $slots_count;
                            }
                            if( !empty($booking_metas['infants']) ){
                                
                                $listing_price += $infant_price * (int)$booking_metas['infants'] * $slots_count;
                            }
                            break;
                        case 'per_person':
                            if( !empty($booking_metas['adults']) ){
                                $listing_price *= (int)$booking_metas['adults'];
                            }else{
                                $listing_price = 0;
                            }
                            if( !empty($booking_metas['children']) ){
                                
                                $listing_price += $children_price * (int)$booking_metas['children'];
                            }
                            if( !empty($booking_metas['infants']) ){
                                
                                $listing_price += $infant_price * (int)$booking_metas['infants'];
                            }
                            break;
                        case 'night_person':
                            $nights = !empty($_POST['nights']) ? $_POST['nights'] : 1;
                            update_post_meta( $booking_id, ESB_META_PREFIX.'nights', $nights ); 
                            if( !empty($booking_metas['adults']) ){
                                $listing_price *= (int)$booking_metas['adults']*$nights;
                            }else{
                                $listing_price = 0;
                            }
                            if( !empty($booking_metas['children']) ){
                                
                                $listing_price += $children_price * (int)$booking_metas['children']*$nights;
                            }
                            if( !empty($booking_metas['infants']) ){
                                
                                $listing_price += $infant_price * (int)$booking_metas['infants']*$nights;
                            }
                            break;
                        case 'day_person':
                            $days = !empty($_POST['days']) ? $_POST['days'] : 1;
                            update_post_meta( $booking_id, ESB_META_PREFIX.'days', $days ); 
                            if( !empty($booking_metas['adults']) ){
                                $listing_price *= (int)$booking_metas['adults']*$days;
                            }else{
                                $listing_price = 0;
                            }
                            if( !empty($booking_metas['children']) ){
                                
                                $listing_price += $children_price * (int)$booking_metas['children']*$days;
                            }
                            if( !empty($booking_metas['infants']) ){
                                
                                $listing_price += $infant_price * (int)$booking_metas['infants']*$days;
                            }
                            break;
                        case 'per_night':
                            $nights = !empty($_POST['nights']) ? $_POST['nights'] : 1;
                            update_post_meta( $booking_id, ESB_META_PREFIX.'nights', $nights ); 
                            //if( !empty($booking_metas['adults']) ){
                                $listing_price *= $nights;
                            //}
                            
                            break;
                        case 'per_day':
                            $days = !empty($_POST['days']) ? $_POST['days'] : 1;
                            update_post_meta( $booking_id, ESB_META_PREFIX.'days', $days ); 
                            //if( !empty($booking_metas['adults']) ){
                                $listing_price *= $days;
                            //}
                            
                            break;
                    }

                }

                
                // cal price with services
                $subtotal = apply_filters( 'cth_inquiry_subtotal', $listing_price, $listing_id, $_POST );

                $subtotal_fee = $subtotal * (float)apply_filters( 'esb_listing_inquiry_fees', townhub_addons_get_option('service_fee'), $listing_id )/100;
                $vat_default = townhub_addons_get_option('vat_tax', 10);
                if(townhub_addons_get_option('booking_vat_include_fee') == 'yes'){
                    $subtotal_vat = ($subtotal + $subtotal_fee)* (float)apply_filters( 'esb_listing_inquiry_vat', $vat_default, $listing_id )/100;
                }
                else{
                    $subtotal_vat = $subtotal * (float)apply_filters( 'esb_listing_inquiry_vat', $vat_default, $listing_id )/100;
                }

                $price_total = $subtotal + $subtotal_fee + $subtotal_vat + $total_services + $new_ser_total;

                


                if( floatval($price_total) > 0 || townhub_addons_get_option('woo_redirect_zero') == 'yes' ){
                    // update_post_meta( $booking_id, '_price',  $price_total );


                    if( townhub_addons_get_option('woo_redirect') == 'yes' ){
                        $quantity = (isset($_POST['lb_quantity']) && is_numeric($_POST['lb_quantity']) && $_POST['lb_quantity'] )? intval($_POST['lb_quantity']) : 1;
                        $json['data']['url'] = townhub_addons_get_add_to_cart_url( $booking_id, $quantity );
                    }
                        
                }

                if (isset($_POST['addservices']) && is_array($_POST['addservices']) && $_POST['addservices'] != ''){
                    update_post_meta( $booking_id, ESB_META_PREFIX.'addservices', $_POST['addservices']);     
                }
                // slot booking
                if ( isset($_POST['slots']) && is_array($_POST['slots']) && !empty($_POST['slots']) ){
                    // update_post_meta( $booking_id, ESB_META_PREFIX.'slots', $_POST['slots']);     
                    // update_post_meta( $booking_id, ESB_META_PREFIX.'slots_text', implode("|", $_POST['slots'] ) );    


                    
                    $listing_slots = self::listing_time_slots($listing_id, $booking_metas['checkin']);
                    $tSlots = array();
                    foreach ($_POST['slots'] as $bkslot) {
                        $slkey = array_search($bkslot, array_column($listing_slots, 'slot_id'));
                        if( false !== $slkey ){
                            $tSlots[] = array(
                                '_id'     => $bkslot,
                                'title'     => $listing_slots[$slkey]['time'],
                                'quantity'  => 1,
                                'price'     => get_post_meta( $booking_id, ESB_META_PREFIX.'price', true ),
                            );
                        }
                    }
                    update_post_meta( $booking_id, ESB_META_PREFIX.'time_slots', $tSlots );  

                }
                // tpicker booking
                if ( isset($_POST['times']) && is_array($_POST['times']) && !empty($_POST['times']) ){
                    update_post_meta( $booking_id, ESB_META_PREFIX.'times', $_POST['times']);     
                    update_post_meta( $booking_id, ESB_META_PREFIX.'times_text', implode("|", $_POST['times'] ) );     
                }

                if( get_post_meta( $booking_id, ESB_META_PREFIX.'checkin', true ) == '' ){
                    update_post_meta( $booking_id, ESB_META_PREFIX.'df_checkin', date_i18n( 'Y-m-d' ) );  
                    
                }


                
                update_post_meta( $booking_id, ESB_META_PREFIX.'subtotal',  $subtotal );
                update_post_meta( $booking_id, ESB_META_PREFIX.'subtotal_fee',  $subtotal_fee );
                update_post_meta( $booking_id, ESB_META_PREFIX.'subtotal_vat',  $subtotal_vat );
                update_post_meta( $booking_id, ESB_META_PREFIX.'price_total',  $price_total );
                update_post_meta( $booking_id, '_price',  $price_total );

                // update bookings count
                self::update_bookings_count($listing_author_id);
                // $json['data']['booking_metas'] = $booking_metas;
                $json['success'] = true;
                $json['data']['message'] = apply_filters( 'townhub_addons_insert_booking_message', __( 'Your booking is received. The listing author will contact with you soon.<br>You can also login with your email to manage bookings.<br>Thank you.', 'townhub-add-ons' ) );

                do_action( 'townhub_addons_booking_request_after', $booking_id );
            }else{
                $json['success'] = false;
                $json['data']['error'] = $booking_id->get_error_message();

                if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). "Insert booking post error: " . $booking_id->get_error_message() . PHP_EOL, 3, ESB_LOG_FILE);

                // throw new Exception($booking_id->get_error_message());

            }

        }else{
            $json['success'] = false;
            $json['data']['error'] = esc_html__( 'The listing id is incorrect.', 'townhub-add-ons' ) ;
        }

        wp_send_json($json );
    }


    public function cancel_lbooking(){
        $json = array(
            'success' => false,
            'data' => array(
                // 'POST'=>$_POST,
            ),
            'debug' => false
        );
        

        Esb_Class_Dashboard::verify_nonce('townhub-add-ons');


        $bkid = $_POST['bkid'];
        if(is_numeric($bkid) && (int)$bkid > 0){
            $booking_status = get_post_meta( $bkid, ESB_META_PREFIX.'lb_status', true );
            if( $booking_status === 'canceled' ){
                $json['data']['error'] = esc_html__( 'The booking has already canceled.', 'townhub-add-ons' ) ;
            }else{
                $current_user = wp_get_current_user(); 
                $listing_id = get_post_meta( $bkid, ESB_META_PREFIX.'listing_id', true );
                $listing_author_id = get_post_field('post_author', $listing_id);
                $buser_id = get_post_meta( $bkid, ESB_META_PREFIX.'user_id', true );

                if( $current_user->ID == $buser_id || $current_user->ID == $listing_author_id || $current_user->user_email == get_post_meta( $bkid, ESB_META_PREFIX.'lb_email', true ) ){
                    do_action( 'townhub_addons_booking_canceled_before', $bkid );
                    update_post_meta( $bkid, ESB_META_PREFIX.'lb_status',  'canceled'  );
                    update_post_meta( $bkid, ESB_META_PREFIX.'canceled_user',  $current_user->ID  );
                    $json['success'] = true;

                    Esb_Class_Dashboard::add_notification($current_user->ID, array(
                        'type' => 'booking_cancel',
                        'entity_id'     => $listing_id
                    ));
                    $canceledUser = $current_user->ID == $buser_id ? $listing_author_id : $buser_id;
                    Esb_Class_Dashboard::add_notification($canceledUser, array(
                        'type' => 'booking_canceled',
                        'entity_id'     => $listing_id
                    ));

                    do_action( 'townhub_addons_booking_canceled', $bkid );
                }else{
                    $json['data']['error'] = esc_html__( "You don't have permission to cancel this booking", 'townhub-add-ons' ) ;
                }
            }
        }else{
            
            $json['data']['error'] = esc_html__( 'The post id is incorrect.', 'townhub-add-ons' ) ;
        }

        wp_send_json($json );
    }

    public function approve_lbooking(){
        $json = array(
            'success' => false,
            'data' => array(
                // 'POST'=>$_POST,
            ),
            'debug' => false
        );
        

        Esb_Class_Dashboard::verify_nonce('townhub-add-ons');

        $bkid = $_POST['bkid'];
        if(is_numeric($bkid) && (int)$bkid > 0){
            $listing_id = get_post_meta( $bkid, ESB_META_PREFIX.'listing_id', true );
            $booking_status = get_post_meta( $bkid, ESB_META_PREFIX.'lb_status', true );
            $listing_author_id = get_post_field('post_author', $listing_id);
            if(get_current_user_id() != $listing_author_id ){
                $json['data']['error'] = __( "You don't have permission to approve this booking", 'townhub-add-ons' );
                $json['data']['user'] = get_current_user_id();
                $json['data']['author'] = $listing_author_id;
                wp_send_json($json );
            }
            if( $booking_status == 'canceled' ){
                $json['data']['error'] = __( "The booking was canceled by user.", 'townhub-add-ons' );
                wp_send_json($json );
            }
            if( townhub_addons_get_option('booking_author_woo') == 'yes' && get_post_meta( $bkid, ESB_META_PREFIX.'woo_order', true ) != '' ){
                $woo_order = wc_get_order( get_post_meta( $bkid, ESB_META_PREFIX.'woo_order', true ) );
                if(!empty($woo_order)){
                    $woo_order->update_status( 'completed' , __( 'Listing author has approved this payment.', 'townhub-add-ons' ) );
                }
            }else{
                if ( !update_post_meta( $bkid, ESB_META_PREFIX.'lb_status',  'completed'  ) ) {
                    $json['data']['error'] = sprintf(__('Insert booking %s meta failure or existing meta value','townhub-add-ons'),'lb_status');
                }else{
                    $json['success'] = true;
                    // if( $booking_status === 'pending' ){
                    //     // update bookings count
                    //     self::update_bookings_count($listing_author_id, true);
                    // }
                        
                    // push customer notification
                    $customer = get_user_by( 'email', get_post_meta( $bkid, ESB_META_PREFIX.'lb_email', true ) );
                    if ( ! empty( $customer ) ) {
                        if( townhub_addons_get_option('db_hide_bookings') != 'yes' ){
                            Esb_Class_Dashboard::add_notification($customer->ID, array(
                                'type' => 'booking_approved',
                                'message' => sprintf(__( 'Your booking for <strong>%s</strong> listing has been approved.', 'townhub-add-ons' ), get_post_field('post_title', $listing_id) ),
                                'entity_id'     => $listing_id
                            ));
                        }
                        
                    }

                    // update author earning
                    if( townhub_addons_get_option('calc_earning_on_author_approve', 'no' == 'yes' ) ){
                        $listing_author_id = get_post_field( 'post_author', $listing_id );
                        if($listing_author_id){
                            $inserted_earning = Esb_Class_Earning::insert($bkid, $listing_author_id, $listing_id);
                            
                        }

                        // update cth_booking status: 0 - insert - 1 - active
                        Esb_Class_Booking::update_cth_booking_status($bkid, 1);
                    }
                        

                    do_action( 'townhub_addons_edit_booking_approved', $bkid );

                }
            }
                
        }else{
            
            $json['data']['error'] = esc_html__( 'The booking id is incorrect.', 'townhub-add-ons' ) ;
        }

        wp_send_json($json );
    }
    public function delete_lbooking(){
        $json = array(
            'success' => false,
            'data' => array(
                // 'POST'=>$_POST,
            ),
            'debug' => false
        );
        
        Esb_Class_Dashboard::verify_nonce('townhub-add-ons');
        $bkid = $_POST['bkid'];
        if(is_numeric($bkid) && (int)$bkid > 0){
            $listing_id = get_post_meta( $bkid, ESB_META_PREFIX.'listing_id', true );
            $buser_id = get_post_meta( $bkid, ESB_META_PREFIX.'user_id', true );
            $listing_author_id = get_post_field('post_author', $listing_id);
            if(get_current_user_id() != $listing_author_id || townhub_addons_get_option('booking_author_delete') != 'yes' ){
                $json['data']['error'] = __( "You don't have permission to delete this booking", 'townhub-add-ons' );
                $json['data']['user'] = get_current_user_id();
                $json['data']['author'] = $listing_author_id;
                wp_send_json($json );
            }

            $force_delete = townhub_addons_get_option('booking_del_trash') == 'yes' ? false : true;
            $deleted_post = wp_delete_post( $bkid, $force_delete );
            if($deleted_post){
                $json['data']['deleted_booking'] = $deleted_post;
                $json['success'] = true;
                // if( get_post_meta( $bkid, ESB_META_PREFIX.'lb_status', true ) === 'pending' ){
                //     // update bookings count
                //     self::update_bookings_count($listing_author_id, true);
                // }
            }else{
                $json['data']['error'] = esc_html__( 'Delete booking failure', 'townhub-add-ons' ) ;
            }
                
        }else{
            
            $json['data']['error'] = esc_html__( 'The booking id is incorrect.', 'townhub-add-ons' ) ;
        }

        wp_send_json($json );
    }

    public static function update_bookings_count($user_id = 0, $decrease = false ){
        if(is_numeric($user_id) && (int)$user_id > 0){
            $bookings_count = intval( get_user_meta($user_id, ESB_META_PREFIX . 'bookings_count', true) ) ;
            if( $decrease ){
                if( $bookings_count > 1){
                    update_user_meta( $user_id, ESB_META_PREFIX . 'bookings_count', ($bookings_count - 1) );
                }else{
                    update_user_meta( $user_id, ESB_META_PREFIX . 'bookings_count', 0 );
                }
            }else{
                update_user_meta( $user_id, ESB_META_PREFIX . 'bookings_count', ($bookings_count + 1) );
            }
        }
    }

    public function check_availability(){
        $json = array(
            'success' => false,
            'data' => array(
                'POST'=>$_POST,
            ),
            'rooms' => array(),
            'available' => array(),
            'add_services'      => array(),
            'debug'             => false,
        );
        // wp_send_json($json );
        Esb_Class_Ajax_Handler::verify_nonce('townhub-add-ons');

        $listing_id = $_POST['listing_id'];
        if(is_numeric($listing_id) && (int)$listing_id > 0){
            $listing_post = get_post($listing_id);
            if(empty($listing_post) || $listing_post->post_type != 'listing'){
                $json['error'] = esc_html__( 'Invalid listing post', 'townhub-add-ons' ) ;
                wp_send_json($json );
            }
            $DATAS = $_POST;
            $DATAS['listing_id'] = $listing_id;
            $return = self::get_available_rooms_datas($DATAS);
            
            $json = array_merge($json, $return);
        }else{
            $json['error'] = esc_html__( 'The listing id is incorrect.', 'townhub-add-ons' ) ;
        }
        wp_send_json($json );
    }

    public static function get_available_rooms_datas($DATAS){
        $return = array(
            'success'           => false,
            'rooms'             => array(),
            'available'         => array(),
            'add_services'      => array(),
        );
        $listing_id = isset($DATAS['listing_id']) ? $DATAS['listing_id'] : 0;
        $checkin = isset($DATAS['checkin']) ? $DATAS['checkin'] : '';
        $checkout = isset($DATAS['checkout']) ? $DATAS['checkout'] : '';

        $ckInOutDates = townhub_addons_get_checkinout_dates($checkin, $checkout);
        // get rooms data
        $rooms_ids = get_post_meta( $listing_id, ESB_META_PREFIX.'rooms_ids', true );
        if(!empty($rooms_ids) && is_array($rooms_ids)){
            foreach ($rooms_ids as $rid) {
                // get checkin price
                $rprice = get_post_meta($rid, '_price', true);
                $rChildprice = get_post_meta($rid, ESB_META_PREFIX.'children_price', true);
                $rInfantprice = get_post_meta($rid, ESB_META_PREFIX.'infant_price', true);

                // minimum adults
                $min_adults = get_post_meta($rid, ESB_META_PREFIX.'min_adults', true);
                if( empty($min_adults) ){
                    $min_adults = 1;
                }
         
                // get meta price
                $rckin = Esb_Class_Date::format($checkin);
                $room_dates = get_post_meta( $rid, ESB_META_PREFIX.'calendar', true );
                $room_dates_metas = get_post_meta( $rid, ESB_META_PREFIX.'calendar_metas', true );
                if( false !== strpos($room_dates, $rckin) ){
                    
                    if(isset($room_dates_metas[$rckin])){
                        if( isset($room_dates_metas[$rckin]['price']) && $room_dates_metas[$rckin]['price'] !== '' ) $rprice = $room_dates_metas[$rckin]['price'];
                        if( isset($room_dates_metas[$rckin]['price_children']) && $room_dates_metas[$rckin]['price_children'] !== '' ) $rChildprice = $room_dates_metas[$rckin]['price_children'];
                        if( isset($room_dates_metas[$rckin]['price_infant']) && $room_dates_metas[$rckin]['price_infant'] !== '' ) $rInfantprice = $room_dates_metas[$rckin]['price_infant'];
                    }

                }
                $rDPrices = array();
                $roomObj = array( 
                    'id'                => $rid, 
                    'title'             => get_post_field( 'post_title', $rid), 
                    'adults'            => (int)get_post_meta($rid, ESB_META_PREFIX.'adults', true), 
                    'children'          => (int)get_post_meta($rid, ESB_META_PREFIX.'children', true), 
                    'price'             => $rprice,
                    'children_price'    => $rChildprice,
                    'infant_price'      => $rInfantprice,
                    'dPrices'           => $rDPrices,
                    'min_adults'        => $min_adults,
                );

                if( !empty($ckInOutDates) ){
                    foreach ($ckInOutDates as $ckdte) {
                        $calCkdte = str_replace("-", "", $ckdte);
                        if( false !== strpos($room_dates, $calCkdte) ){
                            if(isset($room_dates_metas[$calCkdte])){
                                if( isset($room_dates_metas[$calCkdte]['price']) && $room_dates_metas[$calCkdte]['price'] !== '' ) $rprice = $room_dates_metas[$calCkdte]['price'];
                                if( isset($room_dates_metas[$calCkdte]['price_children']) && $room_dates_metas[$calCkdte]['price_children'] !== '' ) $rChildprice = $room_dates_metas[$calCkdte]['price_children'];
                                if( isset($room_dates_metas[$calCkdte]['price_infant']) && $room_dates_metas[$calCkdte]['price_infant'] !== '' ) $rInfantprice = $room_dates_metas[$calCkdte]['price_infant'];
                            }
                        }
                        $rDPrices[$calCkdte] = array(
                            'iso'                   => $ckdte,
                            'price'                 => $rprice,
                            'children_price'        => $rChildprice,
                            'infant_price'          => $rInfantprice,
                        );
                    }
                    $roomObj['dPrices'] = $rDPrices;
                }


                $return['rooms'][] = $roomObj;
            }
        }
        if( !empty($checkin) && !empty($checkout) ){
            $return['available'] = townhub_addons_get_available_listings(
                array(
                    'checkin'       => $checkin,
                    'checkout'      => $checkout,
                    'listing_id'    => $listing_id,
                )
            );

        }
        if( !empty($return['available']) ) $return['add_services'] = get_post_meta( $listing_id, ESB_META_PREFIX.'lservices', true );
        
        $return['success'] = true;

        return $return;
    }

    public function tour_calendar_metas(){
        $json = array(
            'success' => false,
            'data' => array(
                'POST'=>$_POST,
            ),
            'debug'     => false
        );

        // $json['listing'] = get_the_ID(); --> not working

        if(isset($_POST['postid']) && $_POST['postid'] != '' ){
            $listing_id = $_POST['postid'];
            $tour_dates = get_post_meta( $listing_id, ESB_META_PREFIX.'tour_dates', true );
            $_show_metas = get_post_meta( $listing_id, ESB_META_PREFIX.'tour_dates_show_metas', true );
            
            $available = array();
            if( isset($_POST['dates']) && !empty($_POST['dates']) ){
                foreach ((array)$_POST['dates'] as $date) {
                    if( false !== strpos($tour_dates, $date) ){
                        if($_show_metas === 'false'){
                            $metas = array();
                        }else{
                            $metas = array(
                                'guests'                =>  townhub_addons_listing_max_guests($listing_id),
                                'price_adult'           => townhub_addons_get_price( get_post_meta( $listing_id, '_price', true ) ),
                                'price_children'           => townhub_addons_get_price( get_post_meta( $listing_id, ESB_META_PREFIX.'children_price', true ) ),
                                'price_infant'           => townhub_addons_get_price( get_post_meta( $listing_id, ESB_META_PREFIX.'infant_price', true ) ),
                            );
                            $tour_dates_metas = get_post_meta( $listing_id, ESB_META_PREFIX.'tour_dates_metas', true );
                            if(isset($tour_dates_metas[$date])){
                                if( isset($tour_dates_metas[$date]['guests']) && $tour_dates_metas[$date]['guests'] !== '' ) $metas['guests'] = $tour_dates_metas[$date]['guests'];
                                if( isset($tour_dates_metas[$date]['price_adult']) && $tour_dates_metas[$date]['price_adult'] !== '' ) $metas['price_adult'] = townhub_addons_get_price( $tour_dates_metas[$date]['price_adult'] );
                                if( isset($tour_dates_metas[$date]['price_children']) && $tour_dates_metas[$date]['price_children'] !== '' ) $metas['price_children'] = townhub_addons_get_price( $tour_dates_metas[$date]['price_children'] );
                                if( isset($tour_dates_metas[$date]['price_infant']) && $tour_dates_metas[$date]['price_infant'] !== '' ) $metas['price_infant'] = townhub_addons_get_price( $tour_dates_metas[$date]['price_infant'] );
                            }

                            $metas['html'] =    '<div class="date-metas-inner">'.
                                                    '<span class="date-meta-item">'.__( 'Max guests:', 'townhub-add-ons' ).
                                                        '<span class="date-meta-item-val">'.$metas['guests'].'</span>'.
                                                    '</span>'.
                                                    '<span class="date-meta-item">'.__( 'Adult:', 'townhub-add-ons' ).
                                                        '<span class="date-meta-item-val">'. sprintf( _x( '%s%s', 'calendar price', 'townhub-add-ons' ), townhub_addons_get_currency_symbol(), $metas['price_adult'] ) .'</span>'.
                                                    '</span>'.
                                                    '<span class="date-meta-item">'.__( 'Children:', 'townhub-add-ons' ).
                                                        '<span class="date-meta-item-val">'.sprintf( _x( '%s%s', 'calendar price', 'townhub-add-ons' ), townhub_addons_get_currency_symbol(), $metas['price_children'] ).'</span>'.
                                                    '</span>'.
                                                    '<span class="date-meta-item">'.__( 'Infant:', 'townhub-add-ons' ).
                                                        '<span class="date-meta-item-val">'.sprintf( _x( '%s%s', 'calendar price', 'townhub-add-ons' ), townhub_addons_get_currency_symbol(), $metas['price_infant'] ).'</span>'.
                                                    '</span>'.
                                                '</div>';

                            $metas['avaiHtml'] = '<span class="avai-cal-meta">'. sprintf( _x( '%s%s', 'calendar price', 'townhub-add-ons' ), townhub_addons_get_currency_symbol(), $metas['price_adult'] ) .'</span>';

                        }

                        $metas = (array)apply_filters( 'cth_tour_date_metas', $metas );

                        $available[$date] = $metas;
                    }
                }
            }
            $json['available'] = $available;
            // $json['tour_dates'] = $tour_dates;
            // $json['tour_dates_metas'] = $tour_dates_metas;
            
            $json['check_available'] = true;
            if($tour_dates == '') $json['check_available'] = false;
            $json['success'] = true;
        }

        wp_send_json($json );
    }

    private function rooms_date_check($listing_id = 0, $date = ''){
        $rooms = get_post_meta( $listing_id, ESB_META_PREFIX.'rooms_ids', true );
    }

    public function hotel_room_dates(){
        $json = array(
            'success' => false,
            'data' => array(
                'POST'=>$_POST,
            ),
            'debug'     => false
        );

        // $json['listing'] = get_the_ID(); --> not working

        if(isset($_POST['postid']) && $_POST['postid'] != '' ){
            $listing_id = $_POST['postid'];
            $hotel_rooms = get_post_meta( $listing_id, ESB_META_PREFIX.'rooms_ids', true );
            
            $_show_metas = 'true';
            $available = array();
            if( isset($_POST['dates']) && !empty($_POST['dates']) ){
                $available = self::get_hotel_availability($_POST['dates'], $listing_id);
            }
            // if( isset($_POST['dates']) && !empty($_POST['dates']) && !empty($hotel_rooms) ){
            //     foreach ((array)$_POST['dates'] as $date) {
            //         $rooms_datas = array();
            //         foreach ($hotel_rooms as $room_id) {
            //             $room_dates = get_post_meta( $room_id, ESB_META_PREFIX.'calendar', true );
            //             if( false === strpos($room_dates, $date) )
            //                 continue;

            //             $room_dates_metas = get_post_meta( $room_id, ESB_META_PREFIX.'calendar_metas', true );
            //             $_show_metas = get_post_meta( $room_id, ESB_META_PREFIX.'calendar_show_metas', true );

            //             $metas = array(
            //                 'quantity'                  =>  get_post_meta( $room_id, ESB_META_PREFIX.'quantity', true ),
            //                 'price'                     => townhub_addons_get_price( get_post_meta( $room_id, '_price', true ) ),
            //             );
            //             if(isset($room_dates_metas[$date])){
            //                 if( isset($room_dates_metas[$date]['quantity']) && $room_dates_metas[$date]['quantity'] !== '' ) $metas['quantity'] = $room_dates_metas[$date]['quantity'];
            //                 if( isset($room_dates_metas[$date]['price']) && $room_dates_metas[$date]['price'] !== '' ) $metas['price'] = townhub_addons_get_price( $room_dates_metas[$date]['price'] );
            //             }

            //             $rooms_datas[] = $metas;
            //         }
            //         if(!empty($rooms_datas)){
            //             if($_show_metas === 'false'){
            //                 $sum_metas = array();
            //             }else{

            //                 $sum_metas = array(
            //                     'quantity'      => 0,
            //                     'price'         => 0,
            //                 );
            //                 foreach ($rooms_datas as $rdata) {
            //                     $sum_metas['quantity'] += $rdata['quantity'];
            //                     // get min price to display in calendar
            //                     if( $sum_metas['price'] == 0 || $sum_metas['price'] > $rdata['price'])
            //                         $sum_metas['price'] = $rdata['price'];
            //                 }

            //                 $sum_metas['html'] =    '<div class="date-metas-inner">'.
            //                                         '<span class="date-meta-item">'.__( 'Available:', 'townhub-add-ons' ).
            //                                             '<span class="date-meta-item-val">'.$sum_metas['quantity'].'</span>'.
            //                                         '</span>'.
            //                                         '<span class="date-meta-item">'.__( 'Price:', 'townhub-add-ons' ).
            //                                             '<span class="date-meta-item-val">'. sprintf( _x( '%s%s', 'calendar price', 'townhub-add-ons' ), townhub_addons_get_currency_symbol(), $sum_metas['price'] ) .'</span>'.
            //                                         '</span>'.
            //                                     '</div>';

            //                 $sum_metas['avaiHtml'] = '<span class="avai-cal-meta">'. sprintf( _x( '%s%s', 'calendar price', 'townhub-add-ons' ), townhub_addons_get_currency_symbol(), $sum_metas['price'] ) .'</span>';
            //             }
            //             $sum_metas = (array)apply_filters( 'cth_rooms_date_metas', $sum_metas );

            //             $available[$date] = $sum_metas;
            //         }
            //     }
            // }
            $json['available'] = $available;
            $json['check_available'] = true;
            if(empty($hotel_rooms)) $json['check_available'] = false;
            $json['success'] = true;
        }

        wp_send_json($json );
    }

    public function sroom_dates(){
        $json = array(
            'success'   => false,
            'data'      => array(
                'POST' =>$_POST,
            ),
            'debug'     => false
        );

        // $json['listing'] = get_the_ID(); --> not working

        if( isset($_POST['postid']) && !empty( $_POST['postid'] ) ){
            $room_id = $_POST['postid'];
            
            $available = array();
            if( isset($_POST['dates']) && !empty($_POST['dates']) ){
                foreach ((array)$_POST['dates'] as $date) {
                    $modified_date = str_replace("-", "", $date);

                    $room_dates = get_post_meta( $room_id, ESB_META_PREFIX.'calendar', true );
                    if( false === strpos($room_dates, $modified_date) )
                        continue;

                    $room_dates_metas = get_post_meta( $room_id, ESB_META_PREFIX.'calendar_metas', true );
                    $_show_metas = get_post_meta( $room_id, ESB_META_PREFIX.'calendar_show_metas', true );

                    $sum_metas = array(
                        'quantity'                  => get_post_meta( $room_id, ESB_META_PREFIX.'quantity', true ),
                        'raw_price'                 => get_post_meta( $room_id, '_price', true ),
                        'price'                     => townhub_addons_get_price( get_post_meta( $room_id, '_price', true ) ),
                    );
                    if(isset($room_dates_metas[$modified_date])){
                        if( isset($room_dates_metas[$modified_date]['quantity']) && $room_dates_metas[$modified_date]['quantity'] !== '' ) $sum_metas['quantity'] = $room_dates_metas[$modified_date]['quantity'];
                        if( isset($room_dates_metas[$modified_date]['price']) && $room_dates_metas[$modified_date]['price'] !== '' ) $sum_metas['raw_price'] =  $room_dates_metas[$modified_date]['price'] ;
                    }

                    $sum_metas['price'] = townhub_addons_get_price( $sum_metas['raw_price'] );
                    $sum_metas['avaiHtml'] = '<span class="avai-cal-meta">'. townhub_addons_get_price_formated( $sum_metas['raw_price'] ) .'</span>';
                    $sum_metas['mbApps'] = $sum_metas['raw_price'];
                    $sum_metas = (array)apply_filters( 'cth_sroom_date_metas', $sum_metas );

                    $available[$date] = $sum_metas;

                } // end loop dates
                
            } // end check dates
            
            $json['available'] = $available;
            $json['check_available'] = true;
            $json['success'] = true;
        } // end check room id

        wp_send_json($json );
    }
    

    public static function get_hotel_availability($dates,$listing_id){
        $available = array();

        $hotel_rooms = get_post_meta( $listing_id, ESB_META_PREFIX.'rooms_ids', true );
        $_show_metas = 'true';
        if( !empty($hotel_rooms) ){
            foreach ((array)$dates as $date) {
                $modified_date = str_replace("-", "", $date);
                $rooms_datas = array();
                foreach ((array)$hotel_rooms as $room_id) {
                    $room_dates = get_post_meta( $room_id, ESB_META_PREFIX.'calendar', true );
                    if( false === strpos($room_dates, $modified_date) )
                        continue;

                    $room_dates_metas = get_post_meta( $room_id, ESB_META_PREFIX.'calendar_metas', true );
                    $_show_metas = get_post_meta( $room_id, ESB_META_PREFIX.'calendar_show_metas', true );

                    $metas = array(
                        'quantity'                  =>  get_post_meta( $room_id, ESB_META_PREFIX.'quantity', true ),
                        'price'                     => townhub_addons_get_price( get_post_meta( $room_id, '_price', true ) ),
                    );
                    if(isset($room_dates_metas[$modified_date])){
                        if( isset($room_dates_metas[$modified_date]['quantity']) && $room_dates_metas[$modified_date]['quantity'] !== '' ) $metas['quantity'] = $room_dates_metas[$modified_date]['quantity'];
                        if( isset($room_dates_metas[$modified_date]['price']) && $room_dates_metas[$modified_date]['price'] !== '' ) $metas['price'] = townhub_addons_get_price( $room_dates_metas[$modified_date]['price'] );
                    }

                    $rooms_datas[] = $metas;
                }
                if(!empty($rooms_datas)){
                    if($_show_metas === 'false'){
                        $sum_metas = array();
                    }else{

                        $sum_metas = array(
                            'quantity'      => 0,
                            'price'         => 0,
                        );
                        foreach ($rooms_datas as $rdata) {
                            $sum_metas['quantity'] += $rdata['quantity'];
                            // get min price to display in calendar
                            if( $sum_metas['price'] == 0 || $sum_metas['price'] > $rdata['price'])
                                $sum_metas['price'] = $rdata['price'];
                        }

                        $sum_metas['html'] =    '<div class="date-metas-inner">'.
                                                '<span class="date-meta-item">'.__( 'Available:', 'townhub-add-ons' ).
                                                    '<span class="date-meta-item-val">'.$sum_metas['quantity'].'</span>'.
                                                '</span>'.
                                                '<span class="date-meta-item">'.__( 'Price:', 'townhub-add-ons' ).
                                                    '<span class="date-meta-item-val">'. sprintf( _x( '%s%s', 'calendar price', 'townhub-add-ons' ), townhub_addons_get_currency_symbol(), $sum_metas['price'] ) .'</span>'.
                                                '</span>'.
                                            '</div>';

                        $sum_metas['avaiHtml'] = '<span class="avai-cal-meta">'. sprintf( _x( '%s%s', 'calendar price', 'townhub-add-ons' ), townhub_addons_get_currency_symbol(), $sum_metas['price'] ) .'</span>';
                        $sum_metas['mbApps'] = $sum_metas['price'];
                    }
                    $sum_metas = (array)apply_filters( 'cth_rooms_date_metas', $sum_metas );

                    $available[$date] = $sum_metas;
                }
                // end if 
            }
            // end foreach post dates
        }

        return $available;
    }

    public function house_dates(){
        $json = array(
            'success' => false,
            'data' => array(
                'POST'=>$_POST,
            ),
            'debug'     => false
        );

        // $json['listing'] = get_the_ID(); --> not working

        if(isset($_POST['postid']) && $_POST['postid'] != '' ){
            $listing_id = $_POST['postid'];
            $tour_dates = get_post_meta( $listing_id, ESB_META_PREFIX.'house_dates', true );
            $_show_metas = get_post_meta( $listing_id, ESB_META_PREFIX.'house_dates_show_metas', true );
            
            $available = array();
            if( isset($_POST['dates']) && !empty($_POST['dates']) ){
                foreach ((array)$_POST['dates'] as $date) {
                    if( false !== strpos($tour_dates, $date) ){
                        if($_show_metas === 'false'){
                            $metas = array();
                        }else{
                            $metas = array(
                                // 'guests'                =>  townhub_addons_listing_max_guests($listing_id),
                                'price_adult'           => townhub_addons_get_price( get_post_meta( $listing_id, '_price', true ) ),
                                'price_children'           => townhub_addons_get_price( get_post_meta( $listing_id, ESB_META_PREFIX.'children_price', true ) ),
                                'price_infant'           => townhub_addons_get_price( get_post_meta( $listing_id, ESB_META_PREFIX.'infant_price', true ) ),
                            );
                            $tour_dates_metas = get_post_meta( $listing_id, ESB_META_PREFIX.'house_dates_metas', true );
                            if(isset($tour_dates_metas[$date])){
                                // if( isset($tour_dates_metas[$date]['guests']) && $tour_dates_metas[$date]['guests'] !== '' ) $metas['guests'] = $tour_dates_metas[$date]['guests'];
                                if( isset($tour_dates_metas[$date]['price_adult']) && $tour_dates_metas[$date]['price_adult'] !== '' ) $metas['price_adult'] = townhub_addons_get_price( $tour_dates_metas[$date]['price_adult'] );
                                if( isset($tour_dates_metas[$date]['price_children']) && $tour_dates_metas[$date]['price_children'] !== '' ) $metas['price_children'] = townhub_addons_get_price( $tour_dates_metas[$date]['price_children'] );
                                if( isset($tour_dates_metas[$date]['price_infant']) && $tour_dates_metas[$date]['price_infant'] !== '' ) $metas['price_infant'] = townhub_addons_get_price( $tour_dates_metas[$date]['price_infant'] );
                            }

                            $metas['html'] =    '<div class="date-metas-inner">'.
                                                    // '<span class="date-meta-item">'.__( 'Max guests:', 'townhub-add-ons' ).
                                                    //     '<span class="date-meta-item-val">'.$metas['guests'].'</span>'.
                                                    // '</span>'.
                                                    '<span class="date-meta-item">'.__( 'Adult:', 'townhub-add-ons' ).
                                                        '<span class="date-meta-item-val">'. sprintf( _x( '%s%s', 'calendar price', 'townhub-add-ons' ), townhub_addons_get_currency_symbol(), $metas['price_adult'] ) .'</span>'.
                                                    '</span>'.
                                                    '<span class="date-meta-item">'.__( 'Children:', 'townhub-add-ons' ).
                                                        '<span class="date-meta-item-val">'.sprintf( _x( '%s%s', 'calendar price', 'townhub-add-ons' ), townhub_addons_get_currency_symbol(), $metas['price_children'] ).'</span>'.
                                                    '</span>'.
                                                    '<span class="date-meta-item">'.__( 'Infant:', 'townhub-add-ons' ).
                                                        '<span class="date-meta-item-val">'.sprintf( _x( '%s%s', 'calendar price', 'townhub-add-ons' ), townhub_addons_get_currency_symbol(), $metas['price_infant'] ).'</span>'.
                                                    '</span>'.
                                                '</div>';

                            $metas['avaiHtml'] = '<span class="avai-cal-meta">'. sprintf( _x( '%s%s', 'calendar price', 'townhub-add-ons' ), townhub_addons_get_currency_symbol(), $metas['price_adult'] ) .'</span>';

                        }
                        $metas = (array)apply_filters( 'cth_house_date_metas', $metas );

                        $available[$date] = $metas;
                    }
                }
            }
            $json['available'] = $available;
            // $json['tour_dates'] = $tour_dates;
            // $json['tour_dates_metas'] = $tour_dates_metas;
            
            $json['check_available'] = true;
            if($tour_dates == '') $json['check_available'] = false;
            $json['success'] = true;
        }

        wp_send_json($json );
    }

    public function event_dates(){
        $json = array(
            'success' => false,
            'data' => array(
                'POST'=>$_POST,
            ),
            'debug'     => false
        );

        // $json['listing'] = get_the_ID(); --> not working

        if(isset($_POST['postid']) && $_POST['postid'] != '' ){
            $listing_id = $_POST['postid'];
            $tour_dates = get_post_meta( $listing_id, ESB_META_PREFIX.'event_dates', true );
            
            $_show_metas = get_post_meta( $listing_id, ESB_META_PREFIX.'event_dates_show_metas', true );


            $available = array();
            if( isset($_POST['dates']) && !empty($_POST['dates']) ){
                foreach ((array)$_POST['dates'] as $date) {
                    if( false !== strpos($tour_dates, $date) ){

                        if($_show_metas === 'false'){
                            $metas = array();
                        }else{
                            $metas = array(
                                'guests'                =>  townhub_addons_listing_max_guests($listing_id),
                                'price'                 => townhub_addons_get_price( get_post_meta( $listing_id, '_price', true ) ),
                            );
                            $tour_dates_metas = get_post_meta( $listing_id, ESB_META_PREFIX.'event_dates_metas', true );
                            if(isset($tour_dates_metas[$date])){
                                if( isset($tour_dates_metas[$date]['guests']) && $tour_dates_metas[$date]['guests'] !== '' ) $metas['guests'] = $tour_dates_metas[$date]['guests'];
                                if( isset($tour_dates_metas[$date]['price']) && $tour_dates_metas[$date]['price'] !== '' ) $metas['price'] = townhub_addons_get_price( $tour_dates_metas[$date]['price'] );
                                
                            }

                            $metas['html'] =    '<div class="date-metas-inner">'.
                                                    '<span class="date-meta-item">'.__( 'Max guests:', 'townhub-add-ons' ).
                                                        '<span class="date-meta-item-val">'.$metas['guests'].'</span>'.
                                                    '</span>'.
                                                    '<span class="date-meta-item">'.__( 'Price:', 'townhub-add-ons' ).
                                                        '<span class="date-meta-item-val">'. sprintf( _x( '%s%s', 'calendar price', 'townhub-add-ons' ), townhub_addons_get_currency_symbol(), $metas['price'] ) .'</span>'.
                                                    '</span>'.
                                                    
                                                '</div>';

                            $metas['avaiHtml'] = '<span class="avai-cal-meta">'. sprintf( _x( '%s%s', 'calendar price', 'townhub-add-ons' ), townhub_addons_get_currency_symbol(), $metas['price'] ) .'</span>';

                        }
                        $metas = (array)apply_filters( 'cth_event_date_metas', $metas );
                        $available[$date] = $metas;
                    }
                }
            }
            $json['available'] = $available;
            // $json['tour_dates'] = $tour_dates;
            // $json['_show_metas'] = $_show_metas;
            
            $json['check_available'] = true;
            if($tour_dates == '') $json['check_available'] = false;
            $json['success'] = true;
        }

        wp_send_json($json );
    }

    public function listing_dates(){
        $json = array(
            'success' => false,
            'data' => array(
                'POST'=>$_POST,
            ),
            'debug'     => false
        );

        // $json['listing'] = get_the_ID(); --> not working

        if(isset($_POST['postid']) && $_POST['postid'] != '' ){
            $listing_id = $_POST['postid'];
            
            $available = array();
            if( isset($_POST['dates']) && !empty($_POST['dates']) ){
                $available = self::get_availability($_POST['dates'], $listing_id);
            }
            // end check post dates
            $json['available'] = $available;
            
            $json['check_available'] = true;
            if( get_post_meta( $listing_id, ESB_META_PREFIX.'listing_dates', true ) == '') $json['check_available'] = false;
            $json['success'] = true;
        }
        // end check post id

        wp_send_json($json );
    }

    public function tslots_avai(){
        $json = array(
            'success' => false,
            'data' => array(
                // 'POST'=>$_POST,
            ),
            'debug'     => false
        );

        if(isset($_POST['postid']) && $_POST['postid'] != '' ){
            $listing_id = $_POST['postid'];

            $serDate = current_time( 'Ymd' );

            $modified_date = isset($_POST['checkin']) && $_POST['checkin'] != '' ?  $_POST['checkin']  : $serDate ;

            $json['time_slots'] = self::get_time_slots($listing_id, $modified_date, '');
            $json['serDate'] = $serDate;
            
            $json['success'] = true;
        }
        // end check post id

        wp_send_json($json );
    }
    public function tickets_avai(){
        $json = array(
            'success' => false,
            'data' => array(
                // 'POST'=>$_POST,
            ),
            'debug'     => false
        );

        if(isset($_POST['postid']) && $_POST['postid'] != '' ){
            $listing_id = $_POST['postid'];

            $serDate = current_time( 'Ymd' );

            $modified_date = isset($_POST['checkin']) && $_POST['checkin'] != '' ?  $_POST['checkin']  : $serDate ;

            $json['tickets'] = self::get_tickets($listing_id, $modified_date);
            $json['serDate'] = $serDate;
            
            $json['success'] = true;
        }
        // end check post id

        wp_send_json($json );
    }

    public static function get_ltype_id($id){
        $ltype_id = get_post_meta( $id, ESB_META_PREFIX.'listing_type_id', true );
        if( empty($ltype_id) ) $ltype_id = esb_addons_get_wpml_option('default_listing_type', 'listing_type');

        return $ltype_id;
    }

    public static function get_availability($dates,$listing_id){
        $available = array();

        $listing_dates = get_post_meta( $listing_id, ESB_META_PREFIX.'listing_dates', true );
        $_show_metas = get_post_meta( $listing_id, ESB_META_PREFIX.'listing_dates_show_metas', true );

        $ltype_id = self::get_ltype_id( $listing_id );
        $price_based = get_post_meta( $ltype_id, ESB_META_PREFIX.'price_based', true );
        foreach ((array)$dates as $date) {
            $modified_date = str_replace("-", "", $date);
            if( false !== strpos($listing_dates, $modified_date) ){

                // fix for per day
                if( ($price_based == 'per_day' || $price_based == 'per_listing') && false == self::check_avai_qtts($listing_id, $modified_date, $price_based) ){
                    continue;
                }
                if($_show_metas === 'false' && 1 == 2 ){ // fix not showing time slots meta
                    $metas = array();
                }else{
                    $metas = array();
                    $dprice = townhub_addons_get_price( get_post_meta( $listing_id, '_price', true ) );
                    $children_price = townhub_addons_get_price( get_post_meta( $listing_id, ESB_META_PREFIX.'children_price', true ) );
                    $infant_price = townhub_addons_get_price( get_post_meta( $listing_id, ESB_META_PREFIX.'infant_price', true ) );

                    $listing_dates_metas = get_post_meta( $listing_id, ESB_META_PREFIX.'listing_dates_metas', true );
                    if(isset($listing_dates_metas[$modified_date])){
                        // for price meta
                        if( isset($listing_dates_metas[$modified_date]['price']) ){
                            if( $listing_dates_metas[$modified_date]['price'] !== '' ){
                                $dprice = $metas['price'] = townhub_addons_get_price( $listing_dates_metas[$modified_date]['price'] );
                            }else
                                $metas['price'] = $dprice;
                        }elseif( isset($listing_dates_metas[$modified_date]['price_adult']) ){
                            if( $listing_dates_metas[$modified_date]['price_adult'] !== '' ){
                                $dprice = $metas['price'] = townhub_addons_get_price( $listing_dates_metas[$modified_date]['price_adult'] );
                            }else{
                                $metas['price'] = $dprice;
                            }
                        }
                        // for guests meta
                        if( isset($listing_dates_metas[$modified_date]['guests']) ){
                            if( $listing_dates_metas[$modified_date]['guests'] !== '' ) 
                                $metas['guests'] = $listing_dates_metas[$modified_date]['guests'];
                            else
                                $metas['guests'] = townhub_addons_listing_max_guests($listing_id);
                        }
                        // for price_adult meta
                        // if( isset($listing_dates_metas[$modified_date]['price_adult']) ){
                        //     if( $listing_dates_metas[$modified_date]['price_adult'] !== '' ) 
                        //         $metas['price_adult'] = townhub_addons_get_price( $listing_dates_metas[$modified_date]['price_adult'] );
                        //     // else
                        //     //     $metas['price_adult'] = 0;
                        // }
                        // for guests meta
                        if( isset($listing_dates_metas[$modified_date]['price_children']) ){
                            if( $listing_dates_metas[$modified_date]['price_children'] !== '' ) 
                                $metas['price_children'] = townhub_addons_get_price( $listing_dates_metas[$modified_date]['price_children'] );
                            // else
                            //     $metas['price_children'] = 0;
                        }
                        if( isset($listing_dates_metas[$modified_date]['price_infant']) ){
                            if( $listing_dates_metas[$modified_date]['price_infant'] !== '' ) 
                                $metas['price_infant'] = townhub_addons_get_price( $listing_dates_metas[$modified_date]['price_infant'] );
                            // else
                            //     $metas['price_infant'] = 0;
                        }

                        // repeat event time
                        if( isset($listing_dates_metas[$modified_date]['start_time']) ){
                            $metas['start_time'] = $listing_dates_metas[$modified_date]['start_time'];
                        }
                        if( isset($listing_dates_metas[$modified_date]['end_date']) ){
                            $metas['end_date'] = $listing_dates_metas[$modified_date]['end_date'];
                        }
                        // $metas['html'] =    '<div class="date-metas-inner">';
                        // if(isset($metas['guests'])) 
                        //     $metas['html'] .= '<span class="date-meta-item">'.__( 'Max guests:', 'townhub-add-ons' ).
                        //                             '<span class="date-meta-item-val">'.(int)$metas['guests'].'</span>'.
                        //                         '</span>';
                        // if(isset($metas['price'])) 
                        //     $metas['html'] .= '<span class="date-meta-item">'.__( 'Price:', 'townhub-add-ons' ).
                        //                             '<span class="date-meta-item-val">'. townhub_addons_get_price_with_symbol( $metas['price'] ) .'</span>'.
                        //                         '</span>';
                        // if(isset($metas['price_adult'])) 
                        //     $metas['html'] .= '<span class="date-meta-item">'.__( 'Adult:', 'townhub-add-ons' ).
                        //                             '<span class="date-meta-item-val">'. townhub_addons_get_price_with_symbol( $metas['price_adult'] ) .'</span>'.
                        //                         '</span>';
                        // if(isset($metas['price_children'])) 
                        //     $metas['html'] .= '<span class="date-meta-item">'.__( 'Children:', 'townhub-add-ons' ).
                        //                             '<span class="date-meta-item-val">'. townhub_addons_get_price_with_symbol( $metas['price_children'] ) .'</span>'.
                        //                         '</span>';
                        // if(isset($metas['price_infant'])) 
                        //     $metas['html'] .= '<span class="date-meta-item">'.__( 'Infant:', 'townhub-add-ons' ).
                        //                             '<span class="date-meta-item-val">'. townhub_addons_get_price_with_symbol( $metas['price_infant'] ) .'</span>'.
                        //                         '</span>';

                        // $metas['html'] .= '</div>';

                        // $metas = (array)apply_filters( 'cth_listing_date_metas_inner', $metas, $listing_id, $modified_date, $listing_dates_metas );
                    }else{
                        $metas['guests'] = townhub_addons_listing_max_guests($listing_id);
                        $metas['price'] = $dprice;
                        if( $children_price !== '' ){
                            $metas['price_children'] = $children_price;
                        }
                        if( $infant_price !== '' ){
                            $metas['price_infant'] = $infant_price;
                        }
                    }
                    // per person hour/slot
                    if( $price_based == 'hour_person' ){
                        $person_slots = self::get_person_slots($listing_id, $modified_date, $price_based);
                        if( !empty($person_slots) ) $metas['person_slots'] = $person_slots;
                    }
                    // per hour/slot
                    if( $price_based == 'per_hour' ){
                        $time_slots = self::get_time_slots($listing_id, $modified_date, $price_based);
                        if( !empty($time_slots) ) $metas['ltime_slots'] = $time_slots;

                        // $metas['ltime_slots'] = [];
                    }
                    // repeat event tickets
                    $ev_tickets = self::get_tickets($listing_id, $modified_date);
                    if( !empty($ev_tickets) ) $metas['ev_tickets'] = $ev_tickets;
                    // and have date metas
                    if( isset($metas['guests']) || isset($metas['price']) || isset($metas['price_adult']) || isset($metas['price_children']) || isset($metas['price_infant']) ){
                        $metas['html'] =    '<div class="date-metas-inner">';
                        if(isset($metas['guests'])) 
                            $metas['html'] .= '<span class="date-meta-item date-meta-item-guests">'.__( 'Max guests:', 'townhub-add-ons' ).
                                                    '<span class="date-meta-item-val">'.(int)$metas['guests'].'</span>'.
                                                '</span>';
                        if(isset($metas['price'])) 
                            $metas['html'] .= '<span class="date-meta-item date-meta-item-guests">'.__( 'Price:', 'townhub-add-ons' ).
                                                    '<span class="date-meta-item-val">'. townhub_addons_get_price_with_symbol( $metas['price'] ) .'</span>'.
                                                '</span>';
                        if(isset($metas['price_adult'])) 
                            $metas['html'] .= '<span class="date-meta-item date-meta-item-adult">'.__( 'Adult:', 'townhub-add-ons' ).
                                                    '<span class="date-meta-item-val">'. townhub_addons_get_price_with_symbol( $metas['price_adult'] ) .'</span>'.
                                                '</span>';
                        if(isset($metas['price_children'])) 
                            $metas['html'] .= '<span class="date-meta-item date-meta-item-children">'.__( 'Children:', 'townhub-add-ons' ).
                                                    '<span class="date-meta-item-val">'. townhub_addons_get_price_with_symbol( $metas['price_children'] ) .'</span>'.
                                                '</span>';
                        if(isset($metas['price_infant'])) 
                            $metas['html'] .= '<span class="date-meta-item date-meta-item-infant">'.__( 'Infant:', 'townhub-add-ons' ).
                                                    '<span class="date-meta-item-val">'. townhub_addons_get_price_with_symbol( $metas['price_infant'] ) .'</span>'.
                                                '</span>';

                        $metas['html'] .= '</div>';

                    }

                    

                    $metas = (array)apply_filters( 'cth_listing_date_metas_inner', $metas, $listing_id, $modified_date, $listing_dates_metas );
                        
                    
                    $metas['avaiHtml'] = '<span class="avai-cal-meta">'. townhub_addons_get_price_with_symbol( $dprice ) .'</span>';

                    if( $_show_metas === 'false' ){
                        $metas['html'] = '';
                        $metas['avaiHtml'] = '';
                    }
                    $metas['mbApps'] = $dprice ;

                }
                // end else show metas ?

                $metas = (array)apply_filters( 'cth_listing_date_metas', $metas, $listing_id, $modified_date, $listing_dates );

                $available[$date] = $metas;
            }
            // end if false !== strpos($listing_dates, $date)
        }
        // end foreach post dates

        return $available;
    }

    public static function get_tickets($id, $checkin = '', $event_single = true){
        $ltMetas = get_post_meta( $id, ESB_META_PREFIX.'tickets', true );
        $newMetas = array();
        if( empty($ltMetas) || !is_array($ltMetas) ) return $newMetas;
        $bk_args = array(
            'fields'        => 'ids',
            'post_type'     => 'lbooking', 
            'post_status'   => 'publish',
            'posts_per_page' => -1, // no limit
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key'       => '_cth_listing_id',
                    'value'     => $id,
                ),
                // array(
                //     'key'       => '_cth_booking_type',
                //     'value'     => 'custom_tour',
                // ),
                // array(
                //     'key'       => '_cth_lb_status',
                //     'value'     => 'canceled',
                //     'compare'   => '!=',
                // ),
            ),
        );
        $bk_count_status = townhub_addons_get_option('bk_count_status');
        $bk_count_status = array_filter($bk_count_status);
        if( !empty($bk_count_status) ){
            $bk_args['meta_query'][] = array(
                'key'       => '_cth_lb_status',
                'value'     => (array)$bk_count_status,
                'compare'   => 'IN',
            );
        }
        if( !empty($checkin) ){
            $bk_args['meta_query'][] = array(
                'key'       => '_cth_checkin',
                'value'     => townhub_addons_format_cal_date($checkin),
            );
            
            
        }
        // devide tickets by dates - for multi-days event
        $event_dates = townhub_addons_get_calendar_type_dates($id);
        $bookings = get_posts( $bk_args );
        if( !empty($bookings) ){

            $bkqtts = array();
            foreach ($bookings as $bkid) {
                $bkmetas = get_post_meta( $bkid, '_cth_tickets',  true );
                if(!empty($bkmetas)){
                    foreach ((array)$bkmetas as $bkmeta) {
                        if( !empty($bkmeta['_id']) && !empty($bkmeta['quantity']) ){
                            if( isset($bkqtts[$bkmeta['_id']]) ){
                                $bkqtts[$bkmeta['_id']] += (int)$bkmeta['quantity'];
                            }else{
                                $bkqtts[$bkmeta['_id']] = (int)$bkmeta['quantity'];
                            }
                            
                        }
                    }
                }
            }
            // cal bk qtts

            if( !empty($bkqtts) ){
                foreach ($ltMetas as $ltMeta) {
                    $ltMeta['available'] = (int)$ltMeta['available'];
                    if( !empty($checkin) && !empty($event_dates) ){
                        $ltMeta['available'] = floor( $ltMeta['available']/count($event_dates) );
                    }
                    if( !empty($ltMeta['_id']) && !empty($bkqtts[$ltMeta['_id']]) ){
                        $ltMeta['available'] -= $bkqtts[$ltMeta['_id']];
                    }
                    if( $ltMeta['available'] < 0 ) $ltMeta['available'] = 0;
                    $newMetas[] = $ltMeta;
                }
            }

        }elseif( !empty($checkin) ){
            foreach ($ltMetas as $ltMeta) {
                $ltMeta['available'] = (int)$ltMeta['available'];
                if( !empty($event_dates) ){
                    $ltMeta['available'] = floor( $ltMeta['available']/count($event_dates) );
                }
                $newMetas[] = $ltMeta;
            }
        }
        
        if( !empty($newMetas) ) return $newMetas;

        return $ltMetas;
    }
    public static function get_person_slots($id, $checkin = '', $price_based = '' ){
        // $ltMetas = get_post_meta( $id, ESB_META_PREFIX.'time_slots', true );
        $checkin = townhub_addons_format_cal_date($checkin);
        $ltMetas = self::listing_time_slots($id, $checkin);

        $newMetas = array();
        if( empty($ltMetas) || !is_array($ltMetas) ) return $newMetas;
        $ltMetas = array_map(function($mt){
            $mt['_id'] = $mt['slot_id'];
            return $mt;
        }, $ltMetas);
        $bk_args = array(
            'fields'        => 'ids',
            'post_type'     => 'lbooking', 
            'post_status'   => 'publish',
            'posts_per_page' => -1, // no limit
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key'       => '_cth_listing_id',
                    'value'     => $id,
                ),
            ),
        );
        $bk_count_status = townhub_addons_get_option('bk_count_status');
        $bk_count_status = array_filter($bk_count_status);
        if( !empty($bk_count_status) ){
            $bk_args['meta_query'][] = array(
                'key'       => '_cth_lb_status',
                'value'     => (array)$bk_count_status,
                'compare'   => 'IN',
            );
        }
        if( !empty($checkin) ){
            $bk_args['meta_query'][] = array(
                'key'       => '_cth_checkin',
                'value'     => $checkin,
            );
        }
        $bookings = get_posts( $bk_args );
        if( !empty($bookings) ){

            $bkqtts = array();
            foreach ($bookings as $bkid) {
                $bkmetas = get_post_meta( $bkid, '_cth_person_slots',  true );
                if(!empty($bkmetas)){
                    foreach ((array)$bkmetas as $bkmeta) {
                        if( !empty($bkmeta['_id']) && !empty($bkmeta['quantity']) ){
                            if( isset($bkqtts[$bkmeta['_id']]) ){
                                $bkqtts[$bkmeta['_id']] += (int)$bkmeta['quantity'];
                            }else{
                                $bkqtts[$bkmeta['_id']] = (int)$bkmeta['quantity'];
                            }
                            
                        }
                    }
                }
            }
            // cal bk qtts

            if( !empty($bkqtts) ){
                foreach ($ltMetas as $ltMeta) {
                    
                    $ltMeta['available'] = (int)$ltMeta['available'];
                    if( !empty($ltMeta['slot_id']) && !empty($bkqtts[$ltMeta['slot_id']]) ){
                        $ltMeta['available'] -= $bkqtts[$ltMeta['slot_id']];
                    }
                    $newMetas[] = $ltMeta;
                }
            }

        }
        
        if( !empty($newMetas) ) return $newMetas;

        return $ltMetas;

    }

    public static function generateTimes($open = '09:00:00', $close = '19:00:00', $interval = 30){
        $openDate = new DateTime( "2050-09-10 $open");
        $closeDate = new DateTime( "2050-09-10 $close");
        // $ophrAfter = intval( str_replace(":", "", $open) );
        // $clhrAfter = intval( str_replace(":", "", $close) );
        // $testing = $ophrAfter;
        // while($testing < $clhrAfter) {
        //     $testing += $interval;
        //     $slots[] = substr_replace( substr_replace( str_pad($testing, 6, '0', STR_PAD_LEFT), ':', 4, 0 ), ':', 2, 0 ); 
        // }
        $slots = array( $openDate->format( get_option( 'time_format', 'H:i:s' ) ) );
        while ( $openDate->modify("+$interval minutes") <= $closeDate ) {
            // $slots[] = $openDate->format('H:i:s');
            $slots[] = $openDate->format( get_option( 'time_format', 'H:i:s' ) );
        }
        // $slots[] = $close;

        return $slots;
    }

    public static function listing_time_slots($id, $checkin = ''){
        if( empty($checkin) ) return array();
        $listing_type_ID = get_post_meta( $id, ESB_META_PREFIX.'listing_type_id', true );
        $listing_type_ID = apply_filters( 'wpml_object_id', $listing_type_ID, 'listing_type', true );
        $whour_slots = get_post_meta( $listing_type_ID, ESB_META_PREFIX.'whour_slots', true );

        

        $checkin = townhub_addons_format_cal_date($checkin);
        if( $whour_slots != '' ){
            $wkHours = Esb_Class_Listing_CPT::day_wkhours( $id, $checkin );
            if( !empty($wkHours) && isset($wkHours['static']) ){
                $generateSlots = array();
                $generateTimes = array();
                if( $wkHours['static'] == 'openAllDay' ){
                    $generateTimes = self::generateTimes( '00:00:00', '23:59:00', $whour_slots );
                }else if( $wkHours['static'] == 'enterHours' ){
                    
                    if( !empty($wkHours['hours']) ){
                        foreach ($wkHours['hours'] as $opcl) {
                            $tmpTimes = self::generateTimes( $opcl['open'], $opcl['close'], $whour_slots );
                            $generateTimes = array_merge($generateTimes, $tmpTimes);
                        }
                    }
                }
                if( !empty($generateTimes) ){
                    $auto_available = apply_filters( "cth_hour_slots_avai", 1, $id, $listing_type_ID );
                    foreach ($generateTimes as $time) {
                        $tid = sanitize_title_with_dashes( 'wkslot_'.$checkin .'_'. $time, '', 'display' );
                        $generateSlots[] = array(
                            '_id'           => $tid,
                            'slot_id'       => $tid,
                            'available'     => $auto_available,
                            'time'          => $time,
                            'guests'        => $auto_available,
                        );
                    }
                }
                
                return $generateSlots;
            }
            return array();
        }

        return get_post_meta( $id, ESB_META_PREFIX.'time_slots', true );
    }

    public static function get_time_slots($id, $checkin = '', $price_based = '' ){
        // $listing_type_ID = get_post_meta( $id, ESB_META_PREFIX.'listing_type_id', true );
        // $listing_type_ID = apply_filters( 'wpml_object_id', $listing_type_ID, 'listing_type', true );
        // $whour_slots = get_post_meta( $listing_type_ID, ESB_META_PREFIX.'whour_slots', true );

        // $checkin = townhub_addons_format_cal_date($checkin);
        // if( $whour_slots != '' ){
        //     $wkHours = Esb_Class_Listing_CPT::day_wkhours( $id, $checkin );
        //     if( !empty($wkHours) && isset($wkHours['static']) ){
        //         $generateSlots = array();
        //         $generateTimes = array();
        //         if( $wkHours['static'] == 'openAllDay' ){
        //             $generateTimes = self::generateTimes( '00:00:00', '23:59:00', $whour_slots );
        //         }else if( $wkHours['static'] == 'enterHours' ){
                    
        //             if( !empty($wkHours['hours']) ){
        //                 foreach ($wkHours['hours'] as $opcl) {
        //                     $tmpTimes = self::generateTimes( $opcl['open'], $opcl['close'], $whour_slots );
        //                     $generateTimes = array_merge($generateTimes, $tmpTimes);
        //                 }
        //             }
        //         }
        //         if( !empty($generateTimes) ){
        //             foreach ($generateTimes as $time) {
        //                 $tid = sanitize_title_with_dashes( 'wkslot_'.$checkin .'_'. $time, '', 'display' );
        //                 $generateSlots[] = array(
        //                     '_id'           => $tid,
        //                     'slot_id'       => $tid,
        //                     'available'     => 1,
        //                     'time'          => $time,
        //                     'available'     => 1,
        //                 );
        //             }
        //         }
                
        //         return $generateSlots;
        //     }
        //     return array();
        // }
        $checkin = townhub_addons_format_cal_date($checkin);
        $ltMetas = self::listing_time_slots($id, $checkin);
        $newMetas = array();
        if( empty($ltMetas) || !is_array($ltMetas) ) return $newMetas;
        $ltMetas = array_map(function($mt){
            $mt['_id'] = $mt['slot_id'];
            return $mt;
        }, $ltMetas);
        // $newMetas = $ltMetas;
        $bk_args = array(
            'fields'        => 'ids',
            'post_type'     => 'lbooking', 
            'post_status'   => 'publish',
            'posts_per_page' => -1, // no limit
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key'       => '_cth_listing_id',
                    'value'     => $id,
                ),
            ),
        );
        $bk_count_status = townhub_addons_get_option('bk_count_status');
        $bk_count_status = array_filter($bk_count_status);
        if( !empty($bk_count_status) ){
            $bk_args['meta_query'][] = array(
                'key'       => '_cth_lb_status',
                'value'     => (array)$bk_count_status,
                'compare'   => 'IN',
            );
        }
        if( !empty($checkin) ){
            $bk_args['meta_query'][] = array(
                'key'       => '_cth_checkin',
                'value'     => $checkin,
            );
        }
        $bookings = get_posts( $bk_args );
        $bkqtts = array();
        if( !empty($bookings) ){

            // $bkqtts = array();
            foreach ($bookings as $bkid) {
                $bkmetas = get_post_meta( $bkid, '_cth_time_slots',  true );
                if(!empty($bkmetas)){
                    foreach ((array)$bkmetas as $bkmeta) {
                        if( !empty($bkmeta['_id']) && !empty($bkmeta['quantity']) ){
                            if( isset($bkqtts[$bkmeta['_id']]) ){
                                $bkqtts[$bkmeta['_id']] += (int)$bkmeta['quantity'];
                            }else{
                                $bkqtts[$bkmeta['_id']] = (int)$bkmeta['quantity'];
                            }
                            
                        }
                    }
                }
            }
            // cal bk qtts

            // if( !empty($bkqtts) ){
            //     foreach ($ltMetas as $ltMeta) {
                    
            //         $ltMeta['available'] = (int)$ltMeta['available'];
            //         if( !empty($ltMeta['slot_id']) && !empty($bkqtts[$ltMeta['slot_id']]) ){
            //             $ltMeta['available'] -= $bkqtts[$ltMeta['slot_id']];
            //         }
            //         if( intval( $ltMeta['available'] ) > 0 ){
            //             $newMetas[] = $ltMeta;
            //         }
                    
            //     }
            // }

        }
        foreach ($ltMetas as $ltMeta) {
                    
            $ltMeta['available'] = (int)$ltMeta['available'];
            if( !empty($ltMeta['slot_id']) && !empty($bkqtts[$ltMeta['slot_id']]) ){
                $ltMeta['available'] -= $bkqtts[$ltMeta['slot_id']];
            }
            
            if( intval( $ltMeta['available'] ) > 0 ){
                $newMetas[] = $ltMeta;
            }
            
        }
        return $newMetas;
        // if( !empty($newMetas) ) return $newMetas;

        // return $ltMetas;

    }

    public static function check_avai_qtts($id, $checkin = '', $price_based = '' ){
        $checkin = townhub_addons_format_cal_date($checkin);
        $lqtts = get_post_meta( $id, ESB_META_PREFIX.'quantities', true );
        if( empty($lqtts) ) $lqtts = 1;
        $bk_args = array(
            'fields'        => 'ids',
            'post_type'     => 'lbooking', 
            'post_status'   => 'publish',
            'posts_per_page' => -1, // no limit
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key'       => '_cth_listing_id',
                    'value'     => $id,
                ),
            ),
        );
        $bk_count_status = townhub_addons_get_option('bk_count_status');
        $bk_count_status = array_filter($bk_count_status);
        if( !empty($bk_count_status) ){
            $bk_args['meta_query'][] = array(
                'key'       => '_cth_lb_status',
                'value'     => (array)$bk_count_status,
                'compare'   => 'IN',
            );
        }
        if( !empty($checkin) ){
            $bk_args['meta_query'][] = array(
                'key'       => '_cth_checkin',
                'value'     => $checkin,
            );
        }
        $bookings = get_posts( $bk_args );
        $qttsSum = 0;
        if( !empty($bookings) ){
            foreach ($bookings as $bkid) {
                $bk_qtts  = get_post_meta( $bkid, ESB_META_PREFIX.'bk_qtts', true );
                if( empty($bk_qtts) ) $bk_qtts = 1;
                $qttsSum += $bk_qtts;
            }
            // cal bk qtts
        }

        return $qttsSum < $lqtts;
    }

}

new Esb_Class_Booking_CPT();


