<?php
/* add_ons_php */

/**
 * Core class used to implement a Recent Posts widget.
 *
 *
 * @see WP_Widget
 */
class TownHub_Banner extends WP_Widget {

	/**
	 * Sets up a new Recent Posts widget instance.
	 *
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array('classname' => 'townhub_banner', 'description' => __( "TownHub banner widget",'townhub-add-ons') );
		
		// Add Widget scripts
   		// add_action('admin_enqueue_scripts', array($this, 'scripts'));

   		parent::__construct('townhub-banner', __('TownHub Banner','townhub-add-ons'), $widget_ops);
		$this->alt_option_name = 'townhub_banner';
	}

	public function scripts()
	{
	   	wp_enqueue_media();
	   	wp_enqueue_script('cth_wid_js', ESB_DIR_URL . 'assets/admin/cth_wid_js.js', array('jquery'));
	}

	/**
	 * Outputs the content for the current Recent Posts widget instance.
	 *
	 * @access public
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Recent Posts widget instance.
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$bgimg = ! empty( $instance['bgimg'] ) ? $instance['bgimg'] : array();
		$text = ! empty( $instance['text'] ) ? $instance['text'] : '';

		$text = apply_filters( 'banner_widget_text', $text, $instance, $this );
		?>

		<?php echo $args['before_widget']; ?>
		<?php if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		} ?>

			<div class="banner-wdget fl-wrap">
                
                <?php if(!empty($bgimg['id'])): ?>
                <div class="bg" data-bg="<?php echo esc_url( townhub_addons_get_attachment_thumb_link($bgimg['id'], 'medium_large')  ); ?>"></div>
                <?php endif;?>
                <div class="overlay"></div>
                <div class="banner-wdget-content fl-wrap">
                    <?php echo !empty( $instance['filter'] ) ? wpautop( $text ) : $text; ?>
                </div>
            </div>

        <?php echo $args['after_widget']; ?>

	<?php

	}

	/**
	 * Handles updating the settings for the current Recent Posts widget instance.
	 *
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		$instance['bgimg'] = ( ! empty( $new_instance['bgimg'] ) ) ? $new_instance['bgimg'] : array();

		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['text'] = $new_instance['text'];
		} else {
			$instance['text'] = wp_kses_post( $new_instance['text'] );
		}

		return $instance;
	}

	/**
	 * Outputs the settings form for the Recent Posts widget.
	 *
	 * @access public
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'bgimg' => array(), 'text' => '' ) );

		$title     = sanitize_text_field( $instance['title'] );
		$bgimg = ! empty( $instance['bgimg'] ) ? $instance['bgimg'] : array();
		
		$filter = isset( $instance['filter'] ) ? $instance['filter'] : 0;
?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ,'townhub-add-ons'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
		
		<label><?php _e( 'Background Photo:','townhub-add-ons' ); ?></label>
   		<div class="form-field media-field-wrap">

			<img class="<?php echo $this->get_field_id( 'bgimg' ); ?>_preview auwid-avatar" src="<?php echo isset($bgimg['url']) ? $bgimg['url'] : '';  ?>" alt=""<?php echo isset($bgimg['url']) && $bgimg['url'] != ''  ? ' style="display:block;"' : '';  ?>>
			<input type="hidden" name="<?php echo $this->get_field_name( 'bgimg' ); ?>[url]" class="<?php echo $this->get_field_id( 'bgimg' ); ?>_url" value="<?php echo isset($bgimg['url']) ? $bgimg['url'] : '';  ?>">
			<input type="hidden" name="<?php echo $this->get_field_name( 'bgimg' ); ?>[id]" class="<?php echo $this->get_field_id( 'bgimg' ); ?>_id" value="<?php echo isset($bgimg['id']) ? $bgimg['id'] : '';  ?>">
			<p class="descriptions">
				<a href="#" data-uploader_title="<?php esc_attr_e( 'Upload Image', 'townhub-add-ons' ); ?>" class="button button-primary upload_image_button metakey- fieldkey-<?php echo $this->get_field_id( 'bgimg' ); ?>"><?php esc_html_e('Upload Image', 'townhub-add-ons'); ?></a>  
				<a href="#" class="button button-secondary remove_image_button metakey- fieldkey-<?php echo $this->get_field_id( 'bgimg' ); ?>"><?php esc_html_e('Remove', 'townhub-add-ons'); ?></a>
			</p>
   		</div>

   		<p><label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Content' ,'townhub-add-ons'); ?></label>
		<textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo esc_textarea( $instance['text'] ); ?></textarea></p>

   		<p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked( $filter ); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs','townhub-add-ons'); ?></label></p>
		
<?php
	}
}
