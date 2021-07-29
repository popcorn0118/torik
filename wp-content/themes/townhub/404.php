<?php
/* banner-php */
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 */


get_header(); 

?>
<!--  section  -->
<section class="parallax-section small-par" data-scrollax-parent="true" id="main-sec">
    <div class="bg" data-bg="<?php echo esc_url( townhub_get_attachment_thumb_link( townhub_get_option('error404_bg' ), 'full' )  );?>" data-scrollax="properties: { translateY: '30%' }"></div>
    <div class="overlay op7"></div>
    <div class="container">
        <div class="error-wrap">
            <div class="bubbles">
                <h2><?php esc_html_e( '404','townhub' ); ?></h2>
            </div>
            <?php echo townhub_get_option('error404_msg');?>
            
            <div class="clearfix"></div>
            <?php get_search_form();?>
            <div class="clearfix"></div>
            <?php 
            if (townhub_get_option('error404_btn')) : 
            ?>
                <p><?php esc_html_e( 'Or', 'townhub' );?></p>
                <a href="<?php echo esc_url( home_url() );?>" class="btn color2-bg"><?php esc_html_e( 'Back to Home Page', 'townhub' ); ?><i class="far fa-home-alt"></i></a>
            <?php 
            endif; ?>
        </div>
    </div>
</section>
<!--  section  end-->

<?php get_footer();

