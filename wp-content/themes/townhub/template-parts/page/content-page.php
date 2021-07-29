<?php
/* banner-php */
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */
$show_page_title = get_post_meta(get_the_ID(),'_cth_show_page_title',true );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('single-page-content-wrap'); ?>>
	<?php 
	if( get_post_meta(get_the_ID(),'_cth_show_page_title',true ) != 'yes' ): ?>
	<div class="single-page-title-inside">
		<?php the_title( '<h3 class="entry-title">', '</h3>' ); ?>
	</div><!-- .list-single-main-item-title-->
	<?php endif; ?>
	<?php townhub_edit_link( get_the_ID() ); ?>
	<div class="entry-content clearfix">
		<?php
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
