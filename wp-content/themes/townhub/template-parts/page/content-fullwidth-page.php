<?php
/* banner-php */
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content clearfix">
		<?php
			townhub_edit_link( get_the_ID() );
			the_content();
		?>
	</div><!-- .entry-content -->
	<?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'townhub' ),
			'after'  => '</div>',
		) );
	?>
</article><!-- #post-## -->
