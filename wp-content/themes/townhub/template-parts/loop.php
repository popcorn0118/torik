<?php
/* banner-php */

if ( have_posts() ) :

	/* Start the Loop */
	while ( have_posts() ) : the_post();

	    /*
	     * Include the Post-Format-specific template for the content.
	     * If you want to override this in a child theme, then include a file
	     * called content-___.php (where ___ is the Post Format name) and that will be used instead.
	     */
	    if(townhub_get_option('blog_show_format', true ))
	        get_template_part( 'template-parts/post/content', ( post_type_supports( get_post_type(), 'post-formats' ) ? get_post_format() : get_post_type() ) );
	    else
	       get_template_part( 'template-parts/post/content' );

	endwhile;

	townhub_pagination();


else :

get_template_part( 'template-parts/post/content', 'none' );

endif;