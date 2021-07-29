<?php
/* banner-php */
/**
 * Template Name: No Sidebar - Breadcrumbs
 *
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

// get_template_part( 'template-parts/post', 'head' ); ?>
<!--section -->   
<section class="gray-bg small-padding no-top-padding-sec" id="main-sec">

    <div class="container">
        <?php get_template_part( 'template-parts/breadcrumbs' ); ?>
        <div class="row">
            
            <div class="col-md-12 col-wrap display-page nosidebar">
                <div class="list-single-main-wrapper fl-wrap" id="sec2">
                
                    <?php
                    while ( have_posts() ) : the_post();

                        get_template_part( 'template-parts/page/content', 'page-woo' );

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
            

        </div>
        <!-- end row -->
    </div>
    <!-- end container -->

</section>
<!-- section end -->

<?php 
get_footer( );
