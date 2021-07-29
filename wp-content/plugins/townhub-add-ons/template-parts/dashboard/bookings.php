<?php
/* add_ons_php */
$current_user = wp_get_current_user(); 
update_user_meta( $current_user->ID, ESB_META_PREFIX . 'bookings_count', 0 );
if(is_front_page()) {
    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
} else {
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
} 
$bk_show_status = (array)townhub_addons_get_option('bk_show_status');
$bk_show_status = array_filter($bk_show_status);
$bktype_query = array(
                    'key'     => ESB_META_PREFIX.'bk_form_type',
                    'value'   => 'inquiry',
                    'compare'   => '!=',
                );
$meta_queries = array();
// author bookings
if( Esb_Class_Membership::is_author() && !isset($_GET['mybks'])  ){
    // author listings
    $au_listings = get_posts( array(
        'fields'                => 'ids',
        'post_type'             =>  'listing', 
        'author'                =>  $current_user->ID, 
        'orderby'               =>  'date',
        'order'                 =>  'DESC',
        'post_status'           => 'publish',
        'posts_per_page'        => -1, // no limit 
    ) );
    $au_listing_ids = array();
    foreach ( $au_listings as $post_ID ) {
       $au_listing_ids[] = $post_ID;
    } 
    if(!empty($au_listing_ids))  {
        $meta_queries = array(
            'relation' => 'AND',
            array(
                'key'       => ESB_META_PREFIX.'listing_id',
                'value'     => $au_listing_ids,
                'compare'   => 'IN',
                'type'      => 'NUMERIC',
            )

        ); 
        if( townhub_addons_get_option('separate_inquiries') == 'yes' ){
            $meta_queries[] = $bktype_query;
        }
        if( !empty($bk_show_status) ){
            $meta_queries[] = array(
                        'key'     => ESB_META_PREFIX.'lb_status',
                        'value'   => $bk_show_status,
                        'compare' => 'IN'
                    );
        }
    }
        
}else{
    $meta_queries = array(
        'relation' => 'AND',
        array(
            'relation' => 'OR',
            array(
                'key'     => ESB_META_PREFIX.'lb_email',
                'value'   => $current_user->user_email,
            ),
            array(
                'key'     => ESB_META_PREFIX.'user_id',
                'value'   => $current_user->ID,
            ),
        ),

    ); 
    if( townhub_addons_get_option('separate_inquiries') == 'yes' ){
        $meta_queries[] = $bktype_query;
    }
    if( !empty($bk_show_status) ){
        $meta_queries[] = array(
                    'key'     => ESB_META_PREFIX.'lb_status',
                    'value'   => $bk_show_status,
                    'compare' => 'IN'
                );
    }
}

$args = array(
    'post_type'     =>  'lbooking', 
    // 'author'        =>  0, 
    'orderby'       =>  'date',
    'order'         =>  'DESC',
    'paged'         => $paged,
    'post_status'   => 'publish',
    // 'posts_per_page' => -1, // no limit
    // 'posts_per_page' => 1,
    // 'meta_query' => $meta_queries
    
);
if( !empty($meta_queries) ) $args['meta_query'] = $meta_queries;
// The Query
$posts_query = new WP_Query( $args );
?>
<div class="dashboard-content-wrapper dashboard-content-bookings">
    <div class="dashboard-content-inner">
        
        <div class="dashboard-title fl-wrap">
            <h3><?php _e( 'Bookings', 'townhub-add-ons' ); ?></h3>
        </div>

        <?php 
        if( Esb_Class_Membership::is_author() ){
            if( !isset($_GET['mybks']) ){
                $url = add_query_arg('mybks', '', Esb_Class_Dashboard::screen_url('bookings') );  
                $btntext = esc_html_x( 'My Bookings', 'Front-end dashboard', 'townhub-add-ons' );
            }else{
                $url = Esb_Class_Dashboard::screen_url('bookings');  
                $btntext = esc_html_x( 'Listing Bookings', 'Front-end dashboard', 'townhub-add-ons' );
            }
                
            ?>
            <div class="bkreports-btn-wrap mb-20">
                <a href="<?php echo esc_url( $url ); ?>" class="bkmybks-btn btn btn-noicon color2-bg"><?php echo $btntext; ?></a>
            </div>
            <?php
        }
        ?>
        
        <div class="dashboard-bookings-grid">
            
            <?php 
            if( !empty($meta_queries) && $posts_query->have_posts() ) :
                while($posts_query->have_posts()) : $posts_query->the_post(); 
                    $listing_id = get_post_meta( get_the_ID(), ESB_META_PREFIX.'listing_id', true );
                    $listing_author_id = get_post_field('post_author', $listing_id);
                    // $booking = get_post( get_post_meta( get_the_ID(), ESB_META_PREFIX.'booking_id', true ) );
                    // $lb_email = get_post_meta( get_the_ID(), ESB_META_PREFIX.'lb_email', true );
                    // $lb_phone = get_post_meta( get_the_ID(), ESB_META_PREFIX.'lb_phone', true );

                    $lb_name = get_post_meta( get_the_ID(), ESB_META_PREFIX.'lb_name', true );
                    $lb_email = get_post_meta( get_the_ID(), ESB_META_PREFIX.'lb_email', true );
                    $lb_phone = get_post_meta( get_the_ID(), ESB_META_PREFIX.'lb_phone', true );
                    $lb_user = 0;

                    $user_obj   = get_userdata( get_post_meta( get_the_ID(), ESB_META_PREFIX . 'user_id', true) );
                    if( $user_obj ){
                        $lb_user = $user_obj->ID;
                        if( empty($lb_name) ) $lb_name = $user_obj->display_name;
                        
                        if( empty($lb_email) ) $lb_email = $user_obj->user_email;
                        if( empty($lb_phone) ) $lb_phone = get_user_meta( $user_obj->ID, ESB_META_PREFIX.'phone', true);
                    }
                    $nights   = get_post_meta( get_the_ID(), ESB_META_PREFIX.'nights', true );
                    
                    //$person = (int)$lb_adults + (int)$lb_children;
                    $payment_method = get_post_meta( get_the_ID(), ESB_META_PREFIX.'payment_method', true );
                    $lb_quantity = get_post_meta( get_the_ID(), ESB_META_PREFIX.'lb_quantity', true );
                    $qty = get_post_meta( get_the_ID(), ESB_META_PREFIX.'qty', true );
                    $date_event = get_post_meta( get_the_ID(), ESB_META_PREFIX.'date_event', true );
                    
                    // $listing_id =  get_post_meta( get_the_ID(), ESB_META_PREFIX.'listing_id', true );
                    $services = get_post_meta($listing_id, ESB_META_PREFIX.'lservices', true);
                    $value_serv = array();
                    if( is_array($services) && !empty($services) ) {
                        $value_key_ser  = array();
                        $addservices = get_post_meta( get_the_ID(), ESB_META_PREFIX.'addservices', true );
                        if(  is_array($addservices) && !empty($addservices) ){
                            foreach ($addservices  as $key => $item_serv) {
                                // var_dump($item_serv);
                                $value_key_ser[]  = array_search($item_serv,array_column($services,  'service_id'));
                            }
                            foreach ($value_key_ser as $key => $value) {
                                 $value_serv[] = $services[$value];
                            }
                        } 
                    };
                    $bkcoupon = get_post_meta( get_the_ID(), ESB_META_PREFIX.'bkcoupon', true ); 

                    $lb_status = get_post_meta( get_the_ID(), ESB_META_PREFIX.'lb_status', true );

                    $price = get_post_meta( get_the_ID(), ESB_META_PREFIX.'price', true );
                    $children_price = get_post_meta( get_the_ID(), ESB_META_PREFIX.'children_price', true );
                    $infant_price = get_post_meta( get_the_ID(), ESB_META_PREFIX.'infant_price', true );

                    $billingDetails = Esb_Class_User::billingDetails($lb_user);

                    // listing phones
                    $lphone = get_post_meta( $listing_id, ESB_META_PREFIX.'phone', true );
                    $lwhatsapp = get_post_meta( $listing_id, ESB_META_PREFIX.'whatsapp', true );
                    


                ?>
                <div id="booking-<?php the_ID(); ?>" <?php post_class('dashboard-card dashboard-booking-item'); ?>>
                    <div class="dashboard-card-avatar">
                        <?php echo get_avatar($lb_email,'80','https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=80', $lb_name ); ?>
                        <?php if( $lb_status == 'pending'){ ?>
                            <span class="booking-list-new green-bg"><?php _e( 'New', 'townhub-add-ons' ); ?></span>
                        <?php }elseif( $lb_status == 'canceled'){ ?>
                            <span class="booking-list-new cancel-bg"><?php _e( 'Canceled', 'townhub-add-ons' ); ?></span>
                        <?php }elseif( $lb_status == 'refunded'){ ?>
                            <span class="booking-list-new cancel-bg"><?php _e( 'Refunded', 'townhub-add-ons' ); ?></span>
                        <?php }elseif( $lb_status == 'partially_refunded'){ ?>
                            <span class="booking-list-new cancel-bg"><?php _e( 'Partially Refunded', 'townhub-add-ons' ); ?></span>
                        <?php }elseif( $lb_status == 'completed'){ ?>
                            <span class="booking-list-new green-bg approved-bg"><?php _e( 'Approved', 'townhub-add-ons' ); ?></span>
                        <?php } ?>
                    </div>

                    <div class="dashboard-card-content">
                        <?php echo sprintf( __( '<h4 class="entry-title">%1$s - <span>%2$s</span></h4>', 'townhub-add-ons' ) , get_the_title(  ), get_the_date( get_option( 'date_format' ) ) ); ?>
                        <div class="booking-details fl-wrap bktitle-booking">
                            <span class="booking-title"><?php esc_html_e( 'Listing Item: ', 'townhub-add-ons' ); ?></span>
                            <span class="booking-text"><a href="<?php echo esc_url( get_permalink( $listing_id ) ); ?>" target="_blank"><?php echo get_the_title( $listing_id ); ?></a></span>
                        </div>

                        <?php if ( !empty($lphone) ): ?>
                            <div class="booking-details fl-wrap bk-lphone">
                                <span class="booking-title"><?php echo esc_html_x( 'Listing Phone: ', 'Dashboard - Booking', 'townhub-add-ons' ); ?></span>
                                <span class="booking-text"><a href="tel:<?php echo esc_attr($lphone); ?>"><?php echo esc_html($lphone); ?></a></span>
                            </div>
                        <?php endif ?>

                        <?php if ( !empty($lwhatsapp) ): ?>
                            <div class="booking-details fl-wrap bk-lwhatsapp">
                                <span class="booking-title"><?php echo esc_html_x( 'Listing Whatsapp: ', 'Dashboard - Booking', 'townhub-add-ons' ); ?></span>
                                <span class="booking-text"><a href="https://wa.me/<?php echo esc_attr($lwhatsapp); ?>"><?php echo esc_html($lwhatsapp); ?></a></span>
                            </div>
                        <?php endif ?>

                        <?php if ( !empty($lb_name) ): ?>
                        <div class="booking-details fl-wrap bkemail">                                                               
                            <span class="booking-title"><?php esc_html_e( 'Name: ', 'townhub-add-ons' ); ?></span>
                            <span class="booking-text"><?php echo esc_html($lb_name); ?></span>
                        </div>
                        <?php endif ?>
                        <?php if ( !empty($lb_email) ): ?>
                        <div class="booking-details fl-wrap bkemail">                                                               
                            <span class="booking-title"><?php esc_html_e( 'Mail: ', 'townhub-add-ons' ); ?></span>
                            <span class="booking-text"><a href="mailto:<?php echo esc_attr($lb_email); ?>"><?php echo esc_html($lb_email); ?></a></span>
                        </div>
                        <?php endif ?>
                        <?php if ( !empty($lb_phone) ): ?>
                            <div class="booking-details fl-wrap bkphone">
                                <span class="booking-title"><?php esc_html_e( 'Phone: ', 'townhub-add-ons' ); ?></span>
                                <span class="booking-text"><a href="tel:<?php echo esc_attr($lb_phone); ?>"><?php echo esc_html($lb_phone); ?></a></span>
                            </div>
                        <?php endif ?>

                        <?php if ( !empty($billingDetails) ): ?>
                            <div class="booking-details fl-wrap bkbilling">
                                <div class="booking-title"><?php esc_html_e( 'Billing Details: ', 'townhub-add-ons' ); ?></div>
                                <div class="booking-text"><?php echo $billingDetails; ?></div>
                            </div>
                        <?php endif ?>
                        
                        <?php 
                        $checkin = get_post_meta( get_the_ID(), ESB_META_PREFIX.'checkin', true );
                        $checkout = get_post_meta( get_the_ID(), ESB_META_PREFIX.'checkout', true );
                        if ( !empty($checkin) ): ?>
                        <div class="booking-details fl-wrap bkdates">
                            <span class="booking-title"><?php esc_html_e( 'Dates:', 'townhub-add-ons' ); ?></span>
                            <span class="booking-text"><?php echo Esb_Class_Date::i18n( $checkin ); if($checkout != '') echo sprintf(__( ' - %s', 'townhub-add-ons' ), Esb_Class_Date::i18n( $checkout ) ); ?></span>
                        </div>
                        <?php endif ?>

                        <?php $bktimes = get_post_meta( get_the_ID(), ESB_META_PREFIX.'times', true ); 
                        if(!empty($bktimes)):
                        ?>
                        <div class="booking-details fl-wrap bktimes">
                            <span class="booking-title"><?php esc_html_e( 'Times:', 'townhub-add-ons' ); ?></span>
                            <span class="booking-text"><?php echo implode("<br />", $bktimes ); ?></span>
                        </div>
                        <?php endif; ?>

                        <?php $bkslots = get_post_meta( get_the_ID(), ESB_META_PREFIX.'time_slots', true ); 
                        if(!empty($bkslots)):
                            $listing_slots = get_post_meta( $listing_id, ESB_META_PREFIX.'time_slots', true );
                            $tSlots = array();
                            foreach ($bkslots as $bkslot) {
                                $slkey = array_search($bkslot, array_column($listing_slots, 'slot_id'));
                                if( false !== $slkey ){
                                    $tSlots[] = $listing_slots[$slkey]['time'];
                                }
                            }
                        ?>
                        <div class="booking-details fl-wrap bktime-slots dis-flex" style="display: none">
                            <span class="booking-title"><?php esc_html_e( 'Time Slots:', 'townhub-add-ons' ); ?></span>
                            <span class="booking-text"><?php echo implode("<br />", $tSlots ); ?></span>
                        </div>
                        <?php endif; ?>

                        <?php  
                        $rooms = get_post_meta( get_the_ID(), ESB_META_PREFIX.'rooms', true );
                        if( is_array($rooms) && !empty($rooms) ) { ?>
                        <div class="booking-details fl-wrap bkrooms">                                                               
                            <span class="booking-title"><?php esc_html_e( 'Rooms: ', 'townhub-add-ons' ); ?></span>
                            <span class="booking-text">
                                <ul class="no-list-style bkrooms-rooms">
                                <?php foreach ($rooms as $key => $room) { //var_dump($room); ?>
                                    <li>
                                        <span class="booking-title"><?php echo esc_html($room['title']) //get_the_title($room['ID']); ?></span>
                                        <span class="booking-text"><?php echo sprintf(__( '%s x %s', 'townhub-add-ons' ), (int)$room['quantity'], townhub_addons_get_price_formated($room['price']) ); ?></span>
                                    </li>
                                <?php } ?>
                                </ul>
                            </span>
                        </div>  
                        <?php  
                        } ?>

                        <?php  
                        $rooms_persons = get_post_meta( get_the_ID(), ESB_META_PREFIX.'rooms_person_data', true );
                        if( is_array($rooms_persons) && !empty($rooms_persons) ) { ?>
                        <div class="booking-details fl-wrap bkrooms">
                        <?php 
                        foreach ( $rooms_persons as $rdata ) {
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
                        <?php  
                        } ?>
                        
                        <?php  
                        $rooms_old = get_post_meta( get_the_ID(), ESB_META_PREFIX.'rooms_old_data', true );
                        if( is_array($rooms_old) && !empty($rooms_old) ) { ?>
                        <div class="booking-details fl-wrap bkrooms">
                        <?php 
                        foreach ( $rooms_old as $rdata ) {
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
                                                <strong class="bkroom-date-quantity"><?php echo sprintf( _x('Adults %d x ', 'checkout room dates','townhub-add-ons'), $rdata['quantity'] ); ?></strong>
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
                        <?php  
                        } ?>
                        
                        
                        <?php  
                        $tickets = get_post_meta( get_the_ID(), ESB_META_PREFIX.'tickets', true );
                        if( is_array($tickets) && !empty($tickets) ) { ?>
                        <div class="booking-details fl-wrap bkrooms">                                                               
                            <span class="booking-title"><?php esc_html_e( 'Tickets: ', 'townhub-add-ons' ); ?></span>
                            <span class="booking-text">
                                <ul class="no-list-style bkrooms-rooms">
                                <?php foreach ($tickets as $key => $ticket) { //var_dump($ticket); ?>
                                    <li>
                                        <span class="booking-title"><?php echo esc_html($ticket['title']) //get_the_title($ticket['ID']); ?></span>
                                        <span class="booking-text"><?php echo sprintf(__( '%s x %s', 'townhub-add-ons' ), (int)$ticket['quantity'], townhub_addons_get_price_formated($ticket['price']) ); ?></span>
                                    </li>
                                <?php } ?>
                                </ul>
                            </span>
                        </div>  
                        <?php  
                        } ?>

                        <?php  
                        $bk_menus = get_post_meta( get_the_ID(), ESB_META_PREFIX.'bk_menus', true );
                        if( is_array($bk_menus) && !empty($bk_menus) ) { ?>
                        <div class="booking-details fl-wrap bkmenus">                                                               
                            <span class="booking-title"><?php esc_html_e( 'Menus: ', 'townhub-add-ons' ); ?></span>
                            <span class="booking-text">
                                <ul class="no-list-style bkrooms-rooms">
                                <?php foreach ($bk_menus as $key => $ticket) { //var_dump($ticket); ?>
                                    <li>
                                        <span class="booking-title"><?php echo esc_html($ticket['title']); ?></span>
                                        <span class="booking-text"><?php echo sprintf(__( '%s x %s', 'townhub-add-ons' ), (int)$ticket['quantity'], townhub_addons_get_price_formated($ticket['price']) ); ?></span>
                                    </li>
                                <?php } ?>
                                </ul>
                            </span>
                        </div>  
                        <?php  
                        } ?>

                        <?php  
                        $tour_slots = get_post_meta( get_the_ID(), ESB_META_PREFIX.'tour_slots', true );
                        if( is_array($tour_slots) && !empty($tour_slots) ) { ?>
                        <div class="booking-details fl-wrap bkrooms">                                                               
                            <span class="booking-title"><?php esc_html_e( 'Slots: ', 'townhub-add-ons' ); ?></span>
                            <span class="booking-text">
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
                            </span>
                        </div>  
                        <?php  
                        } ?>

                        


                        
                        <?php 
                        $adults     = get_post_meta( get_the_ID(), ESB_META_PREFIX.'adults', true );
                        if ( !empty($adults) ): ?>
                            <div class="booking-details fl-wrap bkadults">
                                <span class="booking-title"><?php esc_html_e( 'Adults: ', 'townhub-add-ons' ); ?></span>
                                <span class="booking-text"><?php echo sprintf(__( '%s x %s', 'townhub-add-ons' ), (int)$adults, townhub_addons_get_price_formated($price) ) ; ?></span>
                            </div>
                        <?php endif ?>
                        <?php 
                        $children  = get_post_meta( get_the_ID(), ESB_META_PREFIX.'children', true );
                        if ( !empty($children) ): ?>
                            <div class="booking-details fl-wrap bkchildren">
                                <span class="booking-title"><?php esc_html_e( 'Children: ', 'townhub-add-ons' ); ?></span>
                                <span class="booking-text"><?php echo sprintf(__( '%s x %s', 'townhub-add-ons' ), (int)$children, townhub_addons_get_price_formated($children_price) ) ; ?></span>
                            </div>
                        <?php endif ?>
                        <?php 
                        $infants  = get_post_meta( get_the_ID(), ESB_META_PREFIX.'infants', true );
                        if ( !empty($infants) ): ?>
                            <div class="booking-details fl-wrap bkinfants">
                                <span class="booking-title"><?php esc_html_e( 'Infants: ', 'townhub-add-ons' ); ?></span>
                                <span class="booking-text"><?php echo sprintf(__( '%s x %s', 'townhub-add-ons' ), (int)$infants, townhub_addons_get_price_formated($infant_price) ) ; ?></span>
                            </div>
                        <?php endif ?>

                        <?php 
                        $bk_qtts  = get_post_meta( get_the_ID(), ESB_META_PREFIX.'bk_qtts', true );
                        if ( !empty($bk_qtts) ): ?>
                            <div class="booking-details fl-wrap bkbk_qtts">
                                <span class="booking-title"><?php esc_html_e( 'Quantity: ', 'townhub-add-ons' ); ?></span>
                                <span class="booking-text"><?php echo sprintf(__( '%s x %s', 'townhub-add-ons' ), (int)$bk_qtts, townhub_addons_get_price_formated($price) ) ; ?></span>
                            </div>
                        <?php endif ?>

                        <?php  
                        $person_slots = get_post_meta( get_the_ID(), ESB_META_PREFIX.'person_slots', true );
                        if( is_array($person_slots) && !empty($person_slots) ) { ?>
                        <div class="booking-details fl-wrap bkrooms">                                                               
                            <span class="booking-title"><?php esc_html_e( 'Slots: ', 'townhub-add-ons' ); ?></span>
                            <span class="booking-text">
                                <ul class="no-list-style bkrooms-rooms">
                                <?php foreach ($person_slots as $key => $ticket) { //var_dump($ticket); ?>
                                    <li class="ctour-ticket-slot">
                                        <span class="booking-title"><?php echo esc_html($ticket['title']) //get_the_title($ticket['ID']); ?></span>
                                        <span class="booking-text">
                                            <?php if(!empty($ticket['adults'])) echo '<div>'.sprintf(__( 'Adults: %s x %s', 'townhub-add-ons' ), (int)$ticket['adults'], townhub_addons_get_price_formated($price) ).'</div>'; ?>
                                            <?php if(!empty($ticket['children'])) echo '<div>'.sprintf(__( 'Children: %s x %s', 'townhub-add-ons' ), (int)$ticket['children'], townhub_addons_get_price_formated($children_price) ).'</div>'; ?>
                                            <?php if(!empty($ticket['infants'])) echo '<div>'.sprintf(__( 'Infants: %s x %s', 'townhub-add-ons' ), (int)$ticket['infants'], townhub_addons_get_price_formated($infant_price) ).'</div>'; ?>
                                        </span>
                                    </li>
                                <?php } ?>
                                </ul>
                            </span>
                        </div>  
                        <?php  
                        } ?>

                        <?php  
                        $time_slots = get_post_meta( get_the_ID(), ESB_META_PREFIX.'time_slots', true );
                        if( is_array($time_slots) && !empty($time_slots) ) { ?>
                        <div class="booking-details fl-wrap bkrooms">                                                               
                            <span class="booking-title"><?php esc_html_e( 'Slots: ', 'townhub-add-ons' ); ?></span>
                            <span class="booking-text">
                                <ul class="no-list-style bkrooms-rooms">
                                <?php foreach ($time_slots as $key => $ticket) { //var_dump($ticket); ?>
                                    <li>
                                        <span class="booking-title"><?php echo esc_html($ticket['title']) //get_the_title($ticket['ID']); ?></span>
                                        <span class="booking-text"><?php // echo sprintf(__( '%s x %s', 'townhub-add-ons' ), (int)$ticket['quantity'], townhub_addons_get_price_formated($ticket['price']) ); ?></span>
                                    </li>
                                <?php } ?>
                                </ul>
                            </span>
                        </div>  
                        <?php  
                        } ?>
                        
                        
                        <?php if ($qty != ''): ?>
                            <div class="booking-details fl-wrap bktickets">
                                <span class="booking-title"><?php esc_html_e( 'Tickets: ', 'townhub-add-ons' ); ?></span>
                               
                                <span class="booking-text"><?php echo (int)$qty; ?></span>
                            </div>
                        <?php endif ?>
                        <?php if ($bkcoupon != ''): ?>
                            <div class="booking-details fl-wrap bkcoupon">
                                <span class="booking-title"><?php esc_html_e( 'Coupon Code: ', 'townhub-add-ons' ); ?></span>
                                <span class="booking-text"><?php echo esc_html($bkcoupon); ?></span>
                            </div>
                        <?php endif ?>
                        <?php if (  is_array($value_serv) && !empty($value_serv)  ): ?>
                            <div class="booking-details fl-wrap bkservices">
                                <span class="booking-title"><?php esc_html_e( 'Services: ', 'townhub-add-ons' ); ?></span>
                                <div class="booking-text">
                                    <ul class="no-list-style bkservices-services">
                                    <?php foreach ($value_serv as $key => $value) { ?>
                                        <li><span class="booking-title"><?php echo esc_html($value['service_name']); ?></span>
                                            <span class="booking-text"><?php echo townhub_addons_get_price_formated($value['service_price']); ?></span></li>
                                    <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        <?php endif ?>

                        <?php  
                        $book_services = get_post_meta( get_the_ID(), ESB_META_PREFIX.'book_services', true );
                        if( is_array($book_services) && !empty($book_services) ) { ?>
                        <div class="booking-details fl-wrap bkrooms">                                                               
                            <span class="booking-title"><?php esc_html_e( 'Additional services: ', 'townhub-add-ons' ); ?></span>
                            <span class="booking-text">
                                <ul class="no-list-style bkrooms-rooms">
                                <?php foreach ($book_services as $key => $service) { ?>
                                    <li>
                                        <span class="booking-title"><?php echo esc_html($service['title']); ?></span>
                                        <span class="booking-text"><?php echo sprintf(__( '%s x %s', 'townhub-add-ons' ), (int)$service['quantity'], townhub_addons_get_price_formated($service['price']) ); ?></span>
                                    </li>
                                <?php } ?>
                                </ul>
                            </span>
                        </div>  
                        <?php  
                        } ?>

                        <?php if ($date_event != ''): ?>
                            <div class="booking-details fl-wrap bkdate-event">
                                <span class="booking-title"><?php esc_html_e( 'Date Event: ', 'townhub-add-ons' ); ?></span>
                               
                                <span class="booking-text"><?php echo esc_html($date_event); ?></span>
                            </div>
                        <?php endif ?>
                        <?php if ((get_post_meta( get_the_ID(), ESB_META_PREFIX.'lb_date', true ) != '' && (get_post_meta( get_the_ID(), ESB_META_PREFIX.'lb_time', true )) != '')): ?>
                            <div class="booking-details fl-wrap bkldate">
                                <span class="booking-title"><?php esc_html_e( 'Booking Date: ', 'townhub-add-ons' ); ?></span>
                                <span class="booking-text"><?php echo sprintf(__( '%1$s at %2$s', 'townhub-add-ons' ), esc_html(get_post_meta( get_the_ID(), ESB_META_PREFIX.'lb_date', true )), esc_html(get_post_meta( get_the_ID(), ESB_META_PREFIX.'lb_time', true ) ) ); ?></span>
                            </div>
                        <?php endif ?>

                        <?php 
                        $cv_pdf_id = get_post_meta( get_the_ID(), ESB_META_PREFIX.'cv_pdf_id', true );
                        if ( !empty($cv_pdf_id) ): ?>
                            <div class="booking-details fl-wrap bkcv_file">
                                <span class="booking-title"><?php esc_html_e( 'CV: ', 'townhub-add-ons' ); ?></span>
                                <span class="booking-text"><a href="<?php echo wp_get_attachment_url( $cv_pdf_id ); ?>" target="_blank"><?php echo get_the_title( $cv_pdf_id ); ?></a></span>
                            </div>
                        <?php endif ?>
                        
                        <?php 
                        $subtotal_vat =  get_post_meta( get_the_ID(), ESB_META_PREFIX.'subtotal_vat', true );
                        if( !empty($subtotal_vat) ):
                        ?>
                        <div class="booking-details fl-wrap bktax">
                            <span class="booking-title"><?php esc_html_e( 'Tax: ', 'townhub-add-ons' ); ?></span>
                               <span class="booking-text"><?php echo townhub_addons_get_price_formated( $subtotal_vat ); ?></span>
                        </div>
                        <?php endif; ?>

                        <div class="booking-details fl-wrap bktotal">
                            <span class="booking-title"><?php esc_html_e( 'Total: ', 'townhub-add-ons' ); ?></span>
                               <span class="booking-text"><?php echo townhub_addons_get_price_formated(get_post_meta( get_the_ID(), ESB_META_PREFIX.'price_total', true )) ?></span>
                        </div>
                        
                        <?php if ($payment_method != ''): ?>
                            <div class="booking-details fl-wrap bkpayment">
                                <span class="booking-title"><?php esc_html_e( 'Payment method: ', 'townhub-add-ons' ); ?></span>
                                <span class="booking-text"><?php echo sprintf(__( 'Paid using %s', 'townhub-add-ons' ), townhub_addons_payment_names($payment_method) ); ?></span>
                            </div>
                        <?php endif ?>

                        <?php $woo_order = get_post_meta( get_the_ID(), ESB_META_PREFIX.'woo_order', true ); 
                            if( !empty($woo_order) ):
                        ?>
                            <div class="booking-details fl-wrap woo-order">
                                <span class="booking-title"><?php esc_html_e( 'WooCommerce Order: ', 'townhub-add-ons' ); ?></span>
                                <span class="booking-text"><?php echo sprintf(_x( '#%s', 'WooCommerce Order', 'townhub-add-ons' ), $woo_order ); ?></span>
                            </div>
                        <?php endif; ?>
                        <!-- <span class="fw-separator"></span> -->
                        <?php echo wp_kses_post( get_post_meta( get_the_ID(), ESB_META_PREFIX.'lb_add_info', true ) ); ?>

                    </div>

                    <div class="booking-list-contr"><?php if( $current_user->ID == $listing_author_id && $lb_status != 'refunded' ): ?>
                        <?php 
                        if( $lb_status == 'completed' ): ?>
                        <a href="javascript:void(0);" class="green-bg tolt" data-microtip-position="left" data-tooltip="<?php esc_attr_e( 'Approved', 'townhub-add-ons' ); ?>"><i class="fal fa-check"></i></a>
                        <?php elseif( $lb_status != 'canceled' ): ?>
                        <a href="#" class="green-bg tolt approve-booking" data-microtip-position="left" data-tooltip="<?php esc_attr_e( 'Approve', 'townhub-add-ons' ); ?>" data-id="<?php echo get_the_ID();?>" data-title="<?php echo esc_attr( get_the_title() ); ?>"><i class="fal fa-check"></i></a>
                        <?php endif; ?>
                        <?php if( $lb_status != 'canceled' && ( $lb_status != 'completed' || 'yes' == townhub_addons_get_option('booking_approved_cancel') ) ): ?>
                        <a href="#" class="cancel-bg tolt cancel-booking" data-microtip-position="left" data-tooltip="<?php esc_attr_e( 'Cancel', 'townhub-add-ons' ); ?>" data-id="<?php echo get_the_ID();?>" data-title="<?php echo esc_attr( get_the_title() ); ?>"><i class="fal fa-ban"></i></a>
                        <?php endif; ?>
                        <?php if( townhub_addons_get_option('booking_author_delete') == 'yes' ): ?>
                            <a href="#" class="del-bg tolt del-booking" data-microtip-position="left" data-tooltip="<?php esc_attr_e( 'Delete', 'townhub-add-ons' ); ?>" data-id="<?php echo get_the_ID();?>" data-title="<?php echo esc_attr( get_the_title() ); ?>"><i class="fal fa-trash"></i></a>
                        <?php endif; ?><?php elseif( $lb_status != 'canceled' && $lb_status != 'refunded' && ( $lb_status != 'completed' || 'yes' == townhub_addons_get_option('booking_approved_cancel_customer') ) ): // for booked user ?><a href="#" class="cancel-bg tolt cancel-booking" data-microtip-position="left" data-tooltip="<?php esc_attr_e( 'Cancel', 'townhub-add-ons' ); ?>" data-id="<?php echo get_the_ID();?>" data-title="<?php echo esc_attr( get_the_title() ); ?>"><i class="fal fa-ban"></i></a><?php endif; ?></div>

                
                </div>
                <!-- dashboard-list end--> 
            <?php 
                endwhile; 
                echo townhub_addons_custom_pagination($posts_query->max_num_pages,$range = 2, $posts_query);
            
                /* Restore original Post Data 
                 * NB: Because we are using new WP_Query we aren't stomping on the 
                 * original $wp_query and it does not need to be reset with 
                 * wp_reset_query(). We just need to set the post data back up with
                 * wp_reset_postdata().
                 */
                wp_reset_postdata();
            else:
            ?> 
            <div id="booking-no" class="dashboard-card dashboard-booking-item">
                <div class="dashboard-card-content">
                    <?php _e( '<p>You have no booking yet!</p>', 'townhub-add-ons' ); ?>
                </div>
            </div>
            <?php
            endif; ?> 

        </div>
    </div>
</div>

