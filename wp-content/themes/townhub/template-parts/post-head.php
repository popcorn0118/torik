<?php
/* banner-php */

if( get_post_meta(get_the_ID(),'_cth_show_page_header',true ) == 'yes' ) :
?>
<!--  section  -->
<section class="parallax-section single-par" data-scrollax-parent="true">
    <div class="bg par-elem" data-bg="<?php echo esc_url( get_post_meta( get_the_ID(), '_cth_page_header_bg', true ) );?>" data-scrollax="properties: { translateY: '30%' }"></div>
    <div class="overlay op7"></div>
    <div class="container">
        <div class="section-title center-align big-title">
        	<?php if( get_post_meta(get_the_ID(),'_cth_show_page_title',true ) == 'yes') : ?>
            <<?php echo townhub_get_option('post_heading_tag');?> class="head-sec-title"><?php single_post_title( ); ?></<?php echo townhub_get_option('post_heading_tag');?>>
            <?php endif ; ?>
            <span class="section-separator"></span>
            <?php 
                echo wp_kses_post( get_post_meta(get_the_ID(),'_cth_page_header_intro',true ) );
            ?>

            <?php if( is_singular( 'page' ) ) townhub_breadcrumbs(); ?>

        </div>
    </div>
    <div class="header-sec-link">
        <a href="#main-sec" class="custom-scroll-link"><i class="fal fa-angle-double-down"></i></a> 
    </div>
</section>
<!--  section  end-->
<?php endif;


