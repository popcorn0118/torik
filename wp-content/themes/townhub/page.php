<?php
/* banner-php */
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */

if ( post_password_required() ) {
    get_template_part( 'template-parts/page/protected', 'page' );
    return;
}

get_header(); 

$sb_w = townhub_get_option('blog-sidebar-width','4');

get_template_part( 'template-parts/post', 'head' ); ?>
<!--section -->   
<section class="gray-bg small-padding no-top-padding-sec" id="main-sec">
    
    <div class="container">
        <?php get_template_part( 'template-parts/breadcrumbs' ); ?>
        <div class="row">
            <?php if( townhub_get_option('blog_layout') ==='left_sidebar' && is_active_sidebar('sidebar-2')):?>
            <div class="col-md-<?php echo esc_attr($sb_w );?> page-sidebar-column">
                <div class="blog-sidebar box-widget-wrap fl-wrap left-sidebar">
                    <?php 
                        get_sidebar('page'); 
                    ?>                 
                </div>
            </div>
            <?php endif;?>
            <?php if( townhub_get_option('blog_layout') ==='fullwidth' || !is_active_sidebar('sidebar-2')):?>
            <div class="col-md-12 display-page nosidebar">
            <?php else:?>
            <div class="col-md-<?php echo (12 - $sb_w);?> col-wrap display-page hassidebar">
            <?php endif;?>
                <div class="list-single-main-wrapper fl-wrap" id="sec2">
                
                    <?php
                    while ( have_posts() ) : the_post();

                        get_template_part( 'template-parts/page/content', 'page' );

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

            <?php if( townhub_get_option('blog_layout') === 'right_sidebar' && is_active_sidebar('sidebar-2')):?>
            <div class="col-md-<?php echo esc_attr($sb_w );?> page-sidebar-column">
                <div class="blog-sidebar box-widget-wrap fl-wrap right-sidebar">
                    <?php 
                        get_sidebar('page'); 
                    ?>                 
                </div>
            </div>
            <?php endif;?>

        </div>
        <!-- end row -->
    </div>
    <!-- end container -->

</section>
<!-- section end -->

<?php 
get_footer( );
