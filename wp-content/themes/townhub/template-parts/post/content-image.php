<?php
/* banner-php */
/**
 * Template part for displaying image posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */

?>
<!-- article> --> 
<article id="post-<?php the_ID(); ?>" <?php post_class('post-article ptype-content-image'); ?>>
	<?php
	if(has_post_thumbnail( )){ ?>
	<div class="list-single-main-media fl-wrap">
        <?php the_post_thumbnail('townhub-featured-image',array('class'=>'respimg') ); ?>
    </div>
	<?php } ?>
	<div class="list-single-main-item fl-wrap block_box post-content-wrap">
        <?php
        townhub_sticky_post();
        
        the_title( '<h2 class="post-opt-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' );
        
        the_excerpt();
        ?>
		<?php townhub_link_pages();?>

		<?php townhub_post_tags(); ?>

		
        <?php townhub_post_meta(); ?>
        
        
    </div>
</article>
<!-- article end -->       
