<?php
/* banner-php */
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 */
if ( post_password_required() ) {
    get_template_part( 'template-parts/page/protected', 'page' );
    return;
}
get_header(); 
$sb_w = townhub_get_option('blog-single-sidebar-width','4');

get_template_part( 'template-parts/post', 'head' );
?>
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
                <div class="col-md-12 display-post nosidebar">
                <?php else:?>
                <div class="col-md-<?php echo (12 - $sb_w);?> col-wrap display-post hassidebar">
                <?php endif;?>
                    <div class="list-single-main-wrapper fl-wrap content-post-wrap" id="sec2">
                
                        <?php
                        /* Start the Loop */
                        while ( have_posts() ) : the_post();
                            // set post view
                            if(function_exists('townhub_addons_set_post_views')){
                                townhub_addons_set_post_views(get_the_ID());
                            }

                            get_template_part( 'template-parts/single/content', get_post_format() );

                            townhub_post_nav();

                            if( townhub_get_option('single_author_block', true ) && get_the_author_meta('description') !='' ) get_template_part( 'template-parts/single/author', 'block' );

                            // If comments are open or we have at least one comment, load up the comment template.
                            if ( comments_open() || get_comments_number() ) :
                                comments_template();
                            endif;

                            

                        endwhile; // End of the loop.
                        ?>

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
