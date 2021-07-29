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
    <title><?php echo wp_get_document_title() ?></title>
    <link rel="stylesheet"  href="https://fonts.googleapis.com/css?family=Montserrat%3A400%2C500%2C600%2C700%2C800%2C800i%2C900%7CQuicksand%3A300%2C400%2C500%2C700&amp;subset=cyrillic%2Ccyrillic-ext%2Clatin-ext%2Cvietnamese" type="text/css" media="all">
    <link rel="stylesheet" href="<?php echo ESB_DIR_URL.'assets/vendors/fontawesome-pro-5.10.0-web/css/all.min.css'; ?>" type="text/css" media="all">
    <link rel="stylesheet" href="<?php echo ESB_DIR_URL.'assets/css/maintenance.css'; ?>" type="text/css" media="all">
</head>

<body>
    <div id="main-theme" class="is-hide-loader">
        <div class="fixed-bg">
            <?php $bgimg = townhub_addons_get_option('coming_soon_bg'); ?>
            <div class="bg" 
            <?php if( !empty($bgimg)): ?>
            data-bg="<?php echo esc_url( townhub_addons_get_attachment_thumb_link($bgimg['id'], 'bg-image') ); ?>"
            <?php endif; ?>></div>
            <div class="overlay"></div>
            <div class="bubble-bg"></div>
        </div>
        <!-- cs-wrapper -->
        <div class="cs-wrapper fl-wrap">
            <!-- container  -->
            <div class="container small-container">
                <div class="cs-logo">
                    <?php 
                    if(has_custom_logo()) the_custom_logo(); 
                    else echo '<a class="custom-logo-link logo-text" href="'.esc_url( home_url('/' ) ).'"><h2>'.get_bloginfo( 'name' ).'</h2></a>'; 
                    ?>
                </div>
                <span class="section-separator"></span>
                <?php echo do_shortcode( townhub_addons_get_option('coming_soon_msg') ); ?>
                
                <?php 
                $cmsDate = townhub_addons_get_option('coming_soon_date');
                $cmsTime = townhub_addons_get_option('coming_soon_time');
                if( $cmsDate != ''){ 
                    $date = date_i18n( 'Y-m-d', strtotime($cmsDate) );
                    if( !empty($cmsTime) ){
                        $date .= 'T'.$cmsTime;
                    }
                ?>
                <!-- countdown -->
                <div class="countdown fl-wrap countdown-widget" data-countdate="<?php echo esc_attr( $date ); ?>" data-tz="<?php echo esc_attr( townhub_addons_get_option('coming_soon_tz') ); ?>">
                    <div class="cs-countdown-item">
                        <span class="days rot">00</span>
                        <p><?php esc_html_e( 'days', 'townhub-add-ons' ); ?></p>
                    </div>
                    <div class="cs-countdown-item">
                        <span class="hours rot">00</span>
                        <p><?php esc_html_e( 'hours', 'townhub-add-ons' ); ?></p>
                    </div>
                    <div class="cs-countdown-item no-dec">
                        <span class="minutes rot2">00</span>
                        <p><?php esc_html_e( 'minutes', 'townhub-add-ons' ); ?></p>
                    </div>
                    <div class="cs-countdown-item">
                        <span class="seconds rot2">00</span>
                        <p><?php esc_html_e( 'seconds', 'townhub-add-ons' ); ?></p>
                    </div>
                </div>
                <!-- countdown end -->
                <?php } ?>
                <?php echo do_shortcode( townhub_addons_get_option('coming_soon_msg_after') ); ?>
                
            </div>
            <!-- container end -->
        </div>
        <!-- cs-wrapper end-->


    </div>
    <!-- Main end -->
    <script type='text/javascript' src='<?php echo ESB_DIR_URL.'assets/js/jquery.min.js'; ?>'></script>
    <script type='text/javascript'>
    /* <![CDATA[ */
    var _townhub_add_ons = {"pl_w":"Please wait...","url": "<?php echo esc_url(admin_url( 'admin-ajax.php' ) ); ?>","disable_bubble":"<?php _ex( 'no', 'Disable bubbles on coming soon page: yes or no', 'townhub-add-ons' ) ?>"};
    /* ]]> */
    </script>
    <script type='text/javascript' src='<?php echo ESB_DIR_URL.'assets/js/maintenance.min.js'; ?>'></script>
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
