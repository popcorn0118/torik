<?php
/* banner-php */

?>
	

<div class="search-widget">
	<form role="search" method="get" action="<?php echo esc_url(home_url( '/' ) ); ?>" class="fl-wrap">
	    <input name="s" type="text" class="search" placeholder="<?php echo esc_attr_x( 'Search...', 'search input placeholder','townhub' ) ?>" value="<?php echo get_search_query() ?>" />
	    <button class="search-submit color2-bg" type="submit"><i class="fal fa-search"></i> </button>
	</form>
</div>
