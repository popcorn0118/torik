<?php
/* add_ons_php */
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 */

?><!DOCTYPE html>
<html class="no-js no-svg" itemscope>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <title><?php echo sprintf(__( 'Invoice details - %s', 'townhub-add-ons' ), wp_get_document_title() ); ?></title>
    <link rel="stylesheet"  href="https://fonts.googleapis.com/css?family=Raleway:300,400,700,800,900|Roboto:400,500,700,900&display=swap" type="text/css" media="all">
    <link rel="stylesheet" href="<?php echo ESB_DIR_URL.'assets/css/invoice.css'; ?>" type="text/css" media="all">
</head>

<body>
    <div class="view-invoice-wrap">
        <?php 
        $id = isset($_GET['invid']) ? $_GET['invid'] : 0;
        if( absint( $id ) ){
            $post = get_post( $id );
            if( $post ){
        ?>
        
        <div class="invoice-box">
            <table>
                <tbody><tr class="top">
                    <td colspan="2">
                        <table>
                            <tbody><tr>
                                <td class="title invoice-logo">
                                    <?php 
                                    $invoice_logo = townhub_addons_get_option('invoice_logo');
                                    if( !empty($invoice_logo) ){
                                        echo '<a class="custom-logo-link logo-custom" href="'.esc_url( home_url('/' ) ).'">'.wp_get_attachment_image( $invoice_logo['id'], 'full', false, '' ).'</a>'; 
                                    }elseif(has_custom_logo()) 
                                        the_custom_logo(); 
                                    else 
                                        echo '<a class="custom-logo-link logo-text" href="'.esc_url( home_url('/' ) ).'"><h2>'.get_bloginfo( 'name' ).'</h2></a>'; 
                                    ?>
                                </td>
                                <td>
                                    <?php echo sprintf( esc_html_x( 'Invoice #: %s','Invoice', 'townhub-add-ons' ), $id ); ?><br>
                                    <?php echo sprintf( esc_html_x( 'Created: %s','Invoice', 'townhub-add-ons' ), Esb_Class_Date::i18n( $post->post_date ) ); ?><br>
                                    <?php echo sprintf( esc_html_x( 'Due: %s','Invoice', 'townhub-add-ons' ), Esb_Class_Date::i18n( $post->post_date ) ); ?>
                                </td>
                            </tr>
                        </tbody></table>
                    </td>
                </tr>
                <tr class="information">
                    <td colspan="2">
                        <table>
                            <tbody>
                                <tr>
                                    <td><strong><?php echo esc_html_x( 'From:','Invoice', 'townhub-add-ons' ); ?></strong><br>
                                        <?php echo townhub_addons_get_option('invoice_from'); ?>                                
                                    </td>
                                    <td><strong><?php echo esc_html_x( 'To:','Invoice', 'townhub-add-ons' ); ?></strong><br>
                                        <?php echo get_post_meta( $id, ESB_META_PREFIX.'user_name', true ); ?><br>
                                        <a href="#" style="color:#666; text-decoration:none"><?php echo esc_html( get_post_meta( $id, ESB_META_PREFIX.'user_email', true ) ); ?></a>
                                        <br>
                                        <a href="#" style="color:#666; text-decoration:none"><?php echo esc_html( get_post_meta( $id, ESB_META_PREFIX.'phone', true ) ); ?></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr class="heading">
                    <td><?php echo esc_html_x( 'Payment Method','Invoice', 'townhub-add-ons' ); ?></td>
                    <td><?php echo esc_html_x( 'Check #','Invoice', 'townhub-add-ons' ); ?></td>
                </tr>
                <tr class="details">
                    <td><?php echo townhub_addons_payment_names(get_post_meta( $id, ESB_META_PREFIX.'payment', true )); ?></td>
                    <td><?php echo esc_html_x( 'Check','Invoice', 'townhub-add-ons' ); ?></td>
                </tr>
                <tr class="heading">
                    <td><?php echo esc_html_x( 'Option','Invoice', 'townhub-add-ons' ); ?></td>
                    <td><?php echo esc_html_x( 'Details','Invoice', 'townhub-add-ons' ); ?></td>
                </tr>
                <tr class="item">
                    <td><?php echo esc_html_x( 'Name','Invoice', 'townhub-add-ons' ); ?></td>
                    <td><?php echo $post->post_title; // get_post_meta( $id, ESB_META_PREFIX.'user_name', true ); ?><br>
                        <?php echo get_the_title(  get_post_meta( $id, ESB_META_PREFIX.'order_id', true ) ); ?>
                    </td>
                </tr>
                <?php 
                $for_add = get_post_meta( $id, ESB_META_PREFIX.'for_listing_ad', true ); 
                if( $for_add == 'yes' ):
                ?>
                <tr class="item">
                    <td><?php echo esc_html_x( 'AD Package','Invoice', 'townhub-add-ons' ); ?></td>
                    <td><?php echo get_post_meta( $id, ESB_META_PREFIX.'plan_title', true ); ?></td>
                </tr>
                <?php else: ?>
                <tr class="item">
                    <td><?php echo esc_html_x( 'Plan','Invoice', 'townhub-add-ons' ); ?></td>
                    <td><?php echo get_post_meta( $id, ESB_META_PREFIX.'plan_title', true ); ?></td>
                </tr>
                <?php endif; ?>
                <tr class="item">
                    <td><?php echo esc_html_x( 'Subtotal','Invoice', 'townhub-add-ons' ); ?></td>
                    <td><?php echo townhub_addons_get_price_formated( get_post_meta( $id, ESB_META_PREFIX.'subtotal', true ) ); ?></td>
                </tr>
                <tr class="item last">
                    <td><?php echo esc_html_x( 'Taxes','Invoice', 'townhub-add-ons' ); ?></td>
                    <td><?php echo townhub_addons_get_price_formated( get_post_meta( $id, ESB_META_PREFIX.'subtotal_vat', true ) ); ?></td>
                </tr>
                <tr class="total">
                    <td></td>
                    <td style="padding-top:50px;"><?php echo sprintf(_x( 'Total: %s','Invoice', 'townhub-add-ons' ), townhub_addons_get_price_formated( get_post_meta( $id, ESB_META_PREFIX.'price_total', true ) ) ); ?></td>
                </tr>
            </tbody></table>
        </div>

        <a href="javascript:window.print()" class="print-button"><?php esc_html_e( 'Print this invoice', 'townhub-add-ons' ); ?></a>

        <?php 
            } // end check post
        } // end check id
        ?>
    </div>
    <!-- Main end -->
    <script>
        var images = document.querySelectorAll("img");

        images.forEach(function(img) {
            if( img.hasAttribute("data-src") ){ 
                img.setAttribute( 'src', img.getAttribute('data-src') );
            }
        });
    </script>
</body>
</html>
