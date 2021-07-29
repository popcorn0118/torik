<?php
/* banner-php */
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */
get_header(); ?>

<!--  section  -->
<section class="parallax-section small-par" data-scrollax-parent="true" id="main-sec">
    <div class="bg" data-bg="<?php echo esc_url( townhub_get_attachment_thumb_link( townhub_get_option('error404_bg' ), 'full' )  );?>" data-scrollax="properties: { translateY: '30%' }"></div>
    <div class="overlay op7"></div>
    <div class="container">
        <div class="error-wrap protected-wrap">
            <div class="bubbles">
                <h2><?php esc_html_e( 'Protected','townhub' ); ?></h2>
            </div>
            
            <h4 class="protected-title"><?php the_title( );?></h4>

            <div class="clearfix"></div>
            <?php echo get_the_password_form(); ?>
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

<?php     
get_footer();
