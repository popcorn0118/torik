<?php
/* banner-php */
/**
 * Template Name: Fullwidth
 *
 */

if ( post_password_required() ) {
    get_template_part( 'template-parts/page/protected', 'page' );
    return;
}

get_header(); 

get_template_part( 'template-parts/post', 'head' ); ?>

<?php
while ( have_posts() ) : the_post();

	get_template_part( 'template-parts/page/content', 'fullwidth-page' );

	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;

endwhile; // End of the loop.
?>


<?php 
get_footer( );
