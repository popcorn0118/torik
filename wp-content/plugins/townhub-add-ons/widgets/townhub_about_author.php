<?php
/* add_ons_php */

/**
 * Core class used to implement a Recent Posts widget.
 *
 *
 * @see WP_Widget
 */
class TownHub_About_Author extends WP_Widget {

	/**
	 * Sets up a new Recent Posts widget instance.
	 *
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array('classname' => 'townhub_about_author', 'description' => __( "TownHub about author widget",'townhub-add-ons') );
		// Add Widget scripts
   		// add_action('admin_enqueue_scripts', array($this, 'scripts'));
 
		parent::__construct('townhub-about-author', __('TownHub Author','townhub-add-ons'), $widget_ops);
		$this->alt_option_name = 'townhub_about_author';
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

		$au_text = ! empty( $instance['text'] ) ? $instance['text'] : '';
		$au_name = ! empty( $instance['au_name'] ) ? $instance['au_name'] : '';
		$au_job = ! empty( $instance['au_job'] ) ? $instance['au_job'] : '';

		// $au_photo = ! empty( $instance['au_photo'] ) ? $instance['au_photo'] : '';
		$avatar = ! empty( $instance['avatar'] ) ? $instance['avatar'] : array();
		$au_link = ! empty( $instance['au_link'] ) ? $instance['au_link'] : 'javascript:void(0)';
		$au_socials = ! empty( $instance['au_socials'] ) ? $instance['au_socials'] : '';



		$text = apply_filters( 'author_widget_text', $au_text, $instance, $this );

		?>

		<?php echo $args['before_widget']; ?>
		<?php if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		} ?>
			<div class="box-widget list-author-widget">
                <div class="list-author-infos-wrap">
                	<?php if(!empty($avatar['id'])): ?>
					<div class="list-author-avatar">
						<?php echo wp_get_attachment_image( $avatar['id'], 'thumbnail', false, array('class'=>'respimg') ); ?>
					</div>
					<?php endif;?>
					<?php if(!empty($au_name) || !empty($au_job)): ?>
					<div class="list-author-infos">
						<a href="<?php echo $au_link; ?>"><?php echo esc_html($au_name); ?></a>
						<span><?php echo $au_job; ?></span>
					</div>
					<?php endif;?>
                	
                </div>
                
                <div class="list-author-widget-text">
                    <div class="list-author-widget-contacts">
                        <?php echo !empty( $instance['filter'] ) ? wpautop( $text ) : $text; ?>
                    </div>
                    <?php if($au_socials !=''): ?>
                    <div class="list-widget-social">
						<?php echo $au_socials; ?>
                    </div>
                    <?php endif;?>
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

		// $instance['au_photo'] = ( ! empty( $new_instance['au_photo'] ) ) ? $new_instance['au_photo'] : '';
		$instance['au_link'] = ( ! empty( $new_instance['au_link'] ) ) ? $new_instance['au_link'] : '';
		$instance['avatar'] = ( ! empty( $new_instance['avatar'] ) ) ? $new_instance['avatar'] : array();

		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['text'] = $new_instance['text'];
		} else {
			$instance['text'] = wp_kses_post( $new_instance['text'] );
		}

		$instance['au_name'] = ( ! empty( $new_instance['au_name'] ) ) ? $new_instance['au_name'] : '';
		$instance['au_job'] = ( ! empty( $new_instance['au_job'] ) ) ? $new_instance['au_job'] : '';
		$instance['au_socials'] = ( ! empty( $new_instance['au_socials'] ) ) ? $new_instance['au_socials'] : '';

		$instance['filter'] = ! empty( $new_instance['filter'] );

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

		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '','avatar' => array(), 'au_sig' => '') );

		$title     = sanitize_text_field( $instance['title'] );
		// $au_photo = ! empty( $instance['au_photo'] ) ? $instance['au_photo'] : '';
		// $au_sig = ! empty( $instance['au_sig'] ) ? $instance['au_sig'] : '';
		$avatar = ! empty( $instance['avatar'] ) ? $instance['avatar'] : array();

		

		$au_name = isset( $instance['au_name'] ) ? $instance['au_name'] : '';
		$au_job = isset( $instance['au_job'] ) ? $instance['au_job'] : '';
		$au_link = isset( $instance['au_link'] ) ? $instance['au_link'] : '';
		$au_socials = isset( $instance['au_socials'] ) ? $instance['au_socials'] : '<ul>
    <li><a href="#" target="_blank"><i class="fab fa-facebook"></i></a></li>
    <li><a href="#" target="_blank"><i class="fab fa-twitter"></i></a></li>
    <li><a href="#" target="_blank"><i class="fab fa-vk"></i></a></li>
    <li><a href="#" target="_blank"><i class="fab fa-whatsapp"></i></a></li>
</ul>';
		$filter = isset( $instance['filter'] ) ? $instance['filter'] : 0;

		// var_dump($avatar);
?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ,'townhub-add-ons'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
		
		<label><?php _e( 'Author Photo:','townhub-add-ons' ); ?></label>
   		<div class="form-field media-field-wrap">

			<img class="<?php echo $this->get_field_id( 'avatar' ); ?>_preview auwid-avatar" src="<?php echo isset($avatar['url']) ? $avatar['url'] : '';  ?>" alt=""<?php echo isset($avatar['url']) && $avatar['url'] != ''  ? ' style="display:block;"' : '';  ?>>
			<input type="hidden" name="<?php echo $this->get_field_name( 'avatar' ); ?>[url]" class="<?php echo $this->get_field_id( 'avatar' ); ?>_url" value="<?php echo isset($avatar['url']) ? $avatar['url'] : '';  ?>">
			<input type="hidden" name="<?php echo $this->get_field_name( 'avatar' ); ?>[id]" class="<?php echo $this->get_field_id( 'avatar' ); ?>_id" value="<?php echo isset($avatar['id']) ? $avatar['id'] : '';  ?>">
			<p class="descriptions">
				<a href="#" data-uploader_title="<?php esc_attr_e( 'Upload Image', 'townhub-add-ons' ); ?>" class="button button-primary upload_image_button metakey- fieldkey-<?php echo $this->get_field_id( 'avatar' ); ?>"><?php esc_html_e('Upload Image', 'townhub-add-ons'); ?></a>  
				<a href="#" class="button button-secondary remove_image_button metakey- fieldkey-<?php echo $this->get_field_id( 'avatar' ); ?>"><?php esc_html_e('Remove', 'townhub-add-ons'); ?></a>
			</p>
   		</div>


   		<p><label for="<?php echo $this->get_field_id( 'au_name' ); ?>"><?php _e( 'Author Name:' ,'townhub-add-ons'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'au_name' ); ?>" name="<?php echo $this->get_field_name( 'au_name' ); ?>" type="text" value="<?php echo $au_name; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id( 'au_job' ); ?>"><?php _e( 'Job:' ,'townhub-add-ons'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'au_job' ); ?>" name="<?php echo $this->get_field_name( 'au_job' ); ?>" type="text" value="<?php echo $au_job; ?>" /></p>
		

		<p><label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Author Description:' ,'townhub-add-ons'); ?></label>
		<textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo esc_textarea( $instance['text'] ); ?></textarea></p>

   		<p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox"<?php checked( $filter ); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs','townhub-add-ons'); ?></label></p>
		
		<p><label for="<?php echo $this->get_field_id( 'au_link' ); ?>"><?php _e( 'Author URL:' ,'townhub-add-ons'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'au_link' ); ?>" name="<?php echo $this->get_field_name( 'au_link' ); ?>" type="text" value="<?php echo $au_link; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id( 'au_socials' ); ?>"><?php _e( 'Socials:' ,'townhub-add-ons'); ?></label>
		<textarea class="widefat" id="<?php echo $this->get_field_id( 'au_socials' ); ?>" name="<?php echo $this->get_field_name( 'au_socials' ); ?>" rows="7"><?php echo $au_socials; ?></textarea></p>


		
<?php
	}
}
