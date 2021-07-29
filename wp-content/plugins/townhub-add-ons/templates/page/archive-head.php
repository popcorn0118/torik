<?php
/* add_ons_php */

if( townhub_addons_get_option('show_lheader') == 'yes' ) :
    $header_bg = townhub_addons_get_option('lheader_bg');
    if(!empty($header_bg) && isset($header_bg['id'])) $header_bg = $header_bg['id'];
?>
<!--  section  -->
<section class="parallax-section single-par" data-scrollax-parent="true">
    <div class="bg par-elem" data-bg="<?php echo esc_url( townhub_addons_get_attachment_thumb_link( $header_bg, 'full') );?>" data-scrollax="properties: { translateY: '30%' }"></div>
    <div class="overlay op7"></div>
    <div class="container">
        <div class="section-title center-align big-title">
            
            <h1 class="head-sec-title"><span><?php echo townhub_addons_get_option('lheader_title'); ?></span></h1>

            <span class="section-separator"></span>
            <?php echo townhub_addons_get_option('lheader_intro'); ?>

            <?php townhub_breadcrumbs(); ?>

        </div>
    </div>
    <div class="header-sec-link">
        <a href="#main-sec" class="custom-scroll-link"><i class="fal fa-angle-double-down"></i></a> 
    </div>
</section>
<!--  section  end-->
<?php endif;