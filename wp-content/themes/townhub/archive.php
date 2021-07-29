<?php
/* banner-php */
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */

get_header(); 

$sb_w = townhub_get_option('blog-sidebar-width','4');


if( townhub_get_option('show_blog_header', false) ) :?>
<!--  section  -->
<section class="parallax-section single-par" data-scrollax-parent="true">
    <div class="bg par-elem" data-bg="<?php echo esc_url( townhub_get_attachment_thumb_link( townhub_get_option('blog_header_image' ), 'full' )  );?>" data-scrollax="properties: { translateY: '30%' }"></div>
    <div class="overlay op7"></div>
    <div class="container">
        <div class="section-title center-align big-title">
            <?php 
            the_archive_title('<h1 class="head-sec-title"><span>','</span></h1>') ;
            ?>
            <span class="section-separator"></span>
            <?php
            the_archive_description( '<div class="taxonomy-description">', '</div>' );
            ?>
        </div>
    </div>
    <div class="header-sec-link">
        <a href="#main-sec" class="custom-scroll-link"><i class="fal fa-angle-double-down"></i></a> 
    </div>
</section>
<!--  section  end-->
<?php 
endif;?>
<!--section -->   
<section class="gray-bg no-top-padding-sec pad-bot-80" id="main-sec">
    <div class="container">
        <?php get_template_part( 'template-parts/breadcrumbs' ); ?>
            
        <div class="post-container fl-wrap">
            <div class="row">
                <?php if( townhub_get_option('blog_layout') ==='left_sidebar' && is_active_sidebar('sidebar-1')):?>
                <div class="col-md-<?php echo esc_attr($sb_w );?> blog-sidebar-column">
                    <div class="blog-sidebar box-widget-wrap fl-wrap fixed-bar left-sidebar">
                        <?php 
                            get_sidebar(); 
                        ?>                 
                    </div>
                </div>
                <?php endif;?>
                <?php if( townhub_get_option('blog_layout') ==='fullwidth' || !is_active_sidebar('sidebar-1')):?>
                <div class="col-md-12 display-posts nosidebar">
                <?php else:?>
                <div class="col-md-<?php echo (12 - $sb_w);?> col-wrap display-posts hassidebar">
                <?php endif;?>
                    <div class="list-single-main-wrapper fl-wrap list-posts-wrap" id="sec2">

                        <?php get_template_part( 'template-parts/loop' ); ?>

                    </div>
                    <!-- end list-single-main-wrapper -->
                </div>
                <!-- end display-posts col-md-8 -->

                <?php if( townhub_get_option('blog_layout') === 'right_sidebar' && is_active_sidebar('sidebar-1')):?>
                <div class="col-md-<?php echo esc_attr($sb_w );?> blog-sidebar-column">
                    <div class="blog-sidebar box-widget-wrap fl-wrap fixed-bar right-sidebar">
                        <?php 
                            get_sidebar(); 
                        ?>                 
                    </div>
                </div>
                <?php endif;?>
            </div><!-- end row -->
        </div><!-- end post-container -->
    </div><!-- end container -->
</section>
<div class="limit-box fl-wrap"></div>
<?php get_footer();
