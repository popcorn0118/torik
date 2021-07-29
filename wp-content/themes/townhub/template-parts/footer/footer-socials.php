<?php
/* banner-php */
/**
 * Displays footer widgets if assigned
 *
 */

?>
<div class="footer-social">
	<?php
	if ( has_nav_menu( 'social' ) ) : 
		wp_nav_menu( array(
			'theme_location' => 'social',
			'menu_class'     => 'social-links-menu',
			'container'       => '',
            'container_class' => '',
            'container_id'    => '',
			'depth'          => 1,
			'link_before'    => '<span>',
			'link_after'     => '</span>',
		) );
	endif;
	?>
</div>
