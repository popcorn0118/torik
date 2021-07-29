<?php
/* add_ons_php */
?>
		<ul class="tabs-menu fl-wrap no-list-style filter-tabs-menu filter-tabs-menu-pages">
            <li class="current tabs-menu-filterform"><a href="#filters-search"><?php _e( '<i class="fal fa-sliders-h"></i> Filters', 'townhub-add-ons' ); ?></a></li>
            <?php if( townhub_addons_get_option('hide_cats_tab') != 'yes' ): ?>
            <li class="tabs-menu-cats"><a href="#category-search"><?php _e( '<i class="fal fa-image"></i>Categories', 'townhub-add-ons' ); ?></a></li>
        	<?php endif; ?>
        </ul>