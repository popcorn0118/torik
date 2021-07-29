<?php
/* banner-php */
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */

?>
<!-- article> --> 
<article class="no-results not-found-post post-article">
	<div class="list-single-main-item fl-wrap block_box post-content-wrap not-found-wrap">

		<h2 class="post-opt-title"><?php esc_html_e( 'Nothing Found', 'townhub' ); ?></h2>
        <?php
		if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'townhub' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>

		<?php else : ?>

			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'townhub' ); ?></p>
			<?php
				get_search_form();

		endif; ?>
    </div>
</article>
<!-- article end -->  
