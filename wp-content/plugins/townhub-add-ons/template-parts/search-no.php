<?php
/* add_ons_php */
?>
<div class="no-results-search">
	<h2><?php esc_html_e( 'No Results', 'townhub-add-ons' );?></h2>
	<p><?php esc_html_e( 'There are no listings matching your search.', 'townhub-add-ons' );?></p>
	<p><?php echo sprintf(__( 'Try changing your search filters or <a href="%1$s" class="reset-filter-link">Reset Filter</a>', 'townhub-add-ons' ), townhub_addons_get_current_url() );?></p>
</div>