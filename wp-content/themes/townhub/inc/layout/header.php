<?php
/* banner-php */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! function_exists( 'townhub_header_content' ) ) {
    add_action( 'townhub_header', 'townhub_header_content' );
    
    function townhub_header_content() {
        ?>
        <!-- header-->
        <header id="masthead" class="townhub-header main-header dark-header fs-header sticky">

            <div class="logo-holder">
                <?php 
                if(has_custom_logo()) the_custom_logo(); 
                else echo '<a class="custom-logo-link logo-text" href="'.esc_url( home_url('/' ) ).'"><h2>'.get_bloginfo( 'name' ).'</h2></a>'; 
                ?>
            </div>
            <!-- header-search_btn-->         
            <?php if(townhub_get_option('show_fixed_search', true )) echo do_shortcode( townhub_check_shortcode('[townhub_search_top]', 'townhub_search_top') );?>


            <!-- header opt -->
            <?php /* if(townhub_get_option('show_addlisting', true ))*/ echo do_shortcode( townhub_check_shortcode('[townhub_submit_button]', 'townhub_submit_button') );?>
             
            
            
            <?php if(townhub_get_option('show_wishlist', true )) echo do_shortcode( townhub_check_shortcode('[townhub_wishlist]', 'townhub_wishlist') );?>

            <?php 
                 if(townhub_get_option('show_userprofile', true )) echo do_shortcode( townhub_check_shortcode('[townhub_login style="'.townhub_get_option('user_menu_style').'"]', 'townhub_login') ); 
            ?>
            <!-- header opt end--> 
            <?php 
            if(is_active_sidebar('header-languages')){
                dynamic_sidebar('header-languages');
            } ?>
            
            <!-- lang-wrap-->
            
            <!-- lang-wrap end-->                                 
            <!-- nav-button-wrap--> 
            <div class="nav-button-wrap color-bg">
                <div class="nav-button">
                    <span></span><span></span><span></span>
                </div>
            </div>
            <!-- nav-button-wrap end-->
            <?php if ( has_nav_menu( 'top' ) ) : ?>
                <!--  .nav-holder -->
                <div class="nav-holder main-menu">
                    <?php get_template_part( 'template-parts/navigation/navigation', 'top' ); ?>
                </div><!-- .nav-holder -->
            <?php endif; ?>

        </header>
        <!--  header end -->
        <?php
    }
}