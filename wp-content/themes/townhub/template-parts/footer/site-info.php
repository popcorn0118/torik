<?php
/* banner-php */
/**
 * Displays footer site info
 *
 */

?>
<div class="copyright">
	<?php echo wp_kses_post( townhub_get_option( 'footer_copyright' ) ); ?>
</div>
