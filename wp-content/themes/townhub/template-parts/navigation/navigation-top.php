<?php
/* banner-php */
/**
 * Displays top navigation
 *
 */

?>
<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Top Menu', 'townhub' ); ?>">
    <?php 
    wp_nav_menu( 
    	array(
			'theme_location'     => 'top',
			'container'          => '',
            'container_class'    => 'no-list-style',
            'container_id'       => '',
			'menu_id'            => 'top-menu',
		) 
	); 
	?>
</nav><!-- #site-navigation -->
