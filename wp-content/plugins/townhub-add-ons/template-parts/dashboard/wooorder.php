<?php
/* add_ons_php */
// don't show on customer dashboard


// townhub_addons_reset_user_notification_type('new_invoice');

$current_user = wp_get_current_user();    


?>
<div class="dashboard-content-wrapper dashboard-content-invoices">
    <div class="dashboard-content-inner">
        
        
        
        <div class="dashboard-invoices-grid view_order-wrap">
            <?php 
            $order_id = isset($_GET['view_order']) && !empty( $_GET['view_order'] ) ? abs($_GET['view_order']) : 0; 
            $order = wc_get_order( $order_id );

            if ( ! $order || ! current_user_can( 'view_order', $order_id ) ) {
                echo '<div class="woocommerce-error">' . esc_html__( 'Invalid order.', 'townhub-add-ons' ) . ' <a href="' . esc_url( wc_get_page_permalink( 'myaccount' ) ) . '" class="wc-forward">' . esc_html__( 'My account', 'townhub-add-ons' ) . '</a></div>';

                // return;
            }else{
                // Backwards compatibility.
                $status       = new stdClass();
                $status->name = wc_get_order_status_name( $order->get_status() );

                wc_get_template(
                    'myaccount/view-order.php',
                    array(
                        'status'   => $status, // @deprecated 2.2.
                        'order'    => $order,
                        'order_id' => $order->get_id(),
                    )
                );
            }

                
            ?>

        </div>
    </div>
</div>
