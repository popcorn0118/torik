<?php
/* add_ons_php */

/**
 * Core class used to implement a Instagram Feed widget.
 *
 *
 * @see WP_Widget
 */
class TownHub_Instagram_Feed extends WP_Widget {

	/**
	 * Sets up a new Recent Posts widget instance.
	 *
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'townhub_instagram_feed', 
			'description' => __( "Display your instagram images as grid view.",'townhub-add-ons') 
		);
		parent::__construct('townhub-instagram-feed', __('TownHub Instagram','townhub-add-ons'), $widget_ops);
		$this->alt_option_name = 'townhub_instagram_feed';
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
		// echo'<pre>';var_dump($instance);

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$limit = ( ! empty( $instance['limit'] ) ) ? absint( $instance['limit'] ) : 6;
		if ( ! $limit )
			$limit = 6;
		
		$get = $instance['get'];
		if($get == 'tagged') {
			$getval = $instance['tagname'];
		}else{
			$getval = $instance['userid'];
		}

		$columns = $instance['columns'];
		$res = $instance['res'];
		$clientid = $instance['clientid'];
		$access = $instance['access'];


		?>
		<?php echo $args['before_widget']; ?>
		<?php if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		} ?>
			<div class="townhub-instafeed grid-cols-<?php echo esc_attr($columns );?>" data-limit="<?php echo esc_attr($limit );?>" data-get="<?php echo esc_attr($get );?>" data-getval="<?php echo esc_attr($getval );?>" data-client="<?php echo esc_attr($clientid );?>" data-access="<?php echo esc_attr($access );?>" data-res="<?php echo esc_attr($res );?>"><div class='jr-insta-thumbs'><ul class="townhub-instafeed-ul clearfix" id="<?php echo uniqid('townhub-instafeed');?>"></ul></div></div>
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
		$instance['get'] = $new_instance['get'];
		$instance['res'] = $new_instance['res'];
		$instance['limit'] = (int) $new_instance['limit'];
		$instance['columns'] = $new_instance['columns'];


		$instance['clientid'] = $new_instance['clientid'];
		$instance['access'] = $new_instance['access'];

		$instance['tagname'] = $new_instance['tagname'];
		$instance['userid'] = $new_instance['userid'];


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
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$get    = isset( $instance['get'] ) ?  $instance['get'] : 'tagged';
		$res    = isset( $instance['res'] ) ?  $instance['res'] : 'thumbnail';
		$limit    = isset( $instance['limit'] ) ? absint( $instance['limit'] ) : 6;
		$columns    = isset( $instance['columns'] ) ? $instance['columns'] : 3;

		$clientid    = isset( $instance['clientid'] ) ?  $instance['clientid'] : '';
		$access    = isset( $instance['access'] ) ?  $instance['access'] : '';

		$tagname    = isset( $instance['tagname'] ) ?  $instance['tagname'] : '';
		$userid    = isset( $instance['userid'] ) ?  $instance['userid'] : '';

?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title' ,'townhub-add-ons'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'clientid' ); ?>"><?php _e( 'Client ID.' ,'townhub-add-ons'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'clientid' ); ?>" name="<?php echo $this->get_field_name( 'clientid' ); ?>" type="text" value="<?php echo $clientid; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id( 'access' ); ?>"><?php _e( 'Access Token.' ,'townhub-add-ons'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'access' ); ?>" name="<?php echo $this->get_field_name( 'access' ); ?>" type="text" value="<?php echo $access; ?>" /></p>

		<p><?php _e('<a href="http://jelled.com/instagram/access-token" target="_blank">"How to create your Client ID and generate an Instagram Access Token?"</a>','townhub-add-ons');?></p>

		<p style="word-break: break-all;">Use this link to generate correct Access Token: <strong>https://instagram.com/oauth/authorize/?client_id=[YOUR_CLIENT_ID_HERE]&scope=basic+public_content&redirect_uri=http://localhost&response_type=token</strong></p>

		<p>
			<label for="<?php echo $this->get_field_id( 'get' ); ?>"><?php _e( 'What do you want to display?','townhub-add-ons'); ?></label>
			<select id="<?php echo $this->get_field_id( 'get' ); ?>" name="<?php echo $this->get_field_name( 'get' ); ?>">
				<option value="tagged" <?php selected( $get, 'tagged' ); ?>>
					<?php _e( 'Tagged - Images with a specific tag','townhub-add-ons'); ?>
				</option>
				<option value="user" <?php selected( $get, 'user' ); ?>>
					<?php _e( 'User - Images from a user','townhub-add-ons'); ?>
				</option>
			</select>
		</p>

		<p><label for="<?php echo $this->get_field_id( 'tagname' ); ?>"><?php _e( 'Tag Name (for get Tagged option above).' ,'townhub-add-ons'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tagname' ); ?>" name="<?php echo $this->get_field_name( 'tagname' ); ?>" type="text" value="<?php echo $tagname; ?>" /></p>


		<p><label for="<?php echo $this->get_field_id( 'userid' ); ?>"><?php _e( 'User ID (for get User option above).' ,'townhub-add-ons'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'userid' ); ?>" name="<?php echo $this->get_field_name( 'userid' ); ?>" type="text" value="<?php echo $userid; ?>" /></p>

		<p><?php _e('This is your Instagram <strong>account ID (eg: 4385108)</strong>, not your username. If you do not know your
account ID, do a quick google search for <a href="https://google.com/search?q=What%20is%20my%20Instagram%20account%20ID%3F" target="_blank">"What is my Instagram account ID?"</a>.
There a several free tools available online that will look it up for you.','townhub-add-ons');?></p>

		<p><label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Number of Images to show. Max of 60' ,'townhub-add-ons'); ?></label>
		<input class="tiny-text" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" type="number" step="1" min="1" value="<?php echo $limit; ?>" size="3" /></p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'columns' ); ?>"><?php _e( 'Columns grid','townhub-add-ons'); ?></label>
			<select id="<?php echo $this->get_field_id( 'columns' ); ?>" name="<?php echo $this->get_field_name( 'columns' ); ?>">
				<option value="1" <?php selected( $columns, '1' ); ?>>
					<?php _e( 'One Column','townhub-add-ons'); ?>
				</option>
				<option value="2" <?php selected( $columns, '2' ); ?>>
					<?php _e( 'Two Columns','townhub-add-ons'); ?>
				</option>
				<option value="3" <?php selected( $columns, '3' ); ?>>
					<?php _e( 'Three Columns','townhub-add-ons'); ?>
				</option>
				<option value="4" <?php selected( $columns, '4' ); ?>>
					<?php _e( 'Four Columns','townhub-add-ons'); ?>
				</option>
				<option value="5" <?php selected( $columns, '5' ); ?>>
					<?php _e( 'Five Columns','townhub-add-ons'); ?>
				</option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'res' ); ?>"><?php _e( 'Image Size','townhub-add-ons'); ?></label>
			<select id="<?php echo $this->get_field_id( 'res' ); ?>" name="<?php echo $this->get_field_name( 'res' ); ?>">
				<option value="thumbnail" <?php selected( $res, 'thumbnail' ); ?>>
					<?php _e( 'Thumbnail - 150x150 px','townhub-add-ons'); ?>
				</option>
				<option value="low_resolution" <?php selected( $res, 'low_resolution' ); ?>>
					<?php _e( 'Low Resolution - 306x306 px','townhub-add-ons'); ?>
				</option>
				<option value="standard_resolution" <?php selected( $res, 'standard_resolution' ); ?>>
					<?php _e( 'Standard Resolution - 612x612 px','townhub-add-ons'); ?>
				</option>
			</select>
		</p>

		


<?php
	}
}
