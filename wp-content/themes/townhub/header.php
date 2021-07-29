<?php
/* banner-php */
/**
 * The header for our theme 
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials 
 *
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg" itemscope> 
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="profile" href="//gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php 
    do_action( 'wp_body_open' );
    
    ?>
<?php 
if(townhub_get_option('show_loader', true ) ) : 
    $loader_icon = townhub_get_option('loader_icon');
?>
    <!--loader-->
    <div class="loader-wrap">
        <div class="loader-inner<?php if(!empty($loader_icon)) echo ' loader-image-inner'; ?>">
        <?php if(!empty($loader_icon)): ?>
            <div class="loader-icon-img">
                <?php echo wp_get_attachment_image( $loader_icon, 'full', false, array('class'=>'no-lazy')); ?> 
            </div>
        <?php else: ?>
            <div class="loader-inner-cirle"></div>
        <?php endif; ?>
        </div>
    </div>
    <!--loader end-->
    <div id="main-theme">
<?php else :?>
    <div id="main-theme" class="is-hide-loader">
<?php endif;?>

        <?php do_action( 'townhub_header' ); ?>

        <!--  wrapper  -->
        <div id="wrapper">
            <!-- Content-->
            <div class="content">

                
