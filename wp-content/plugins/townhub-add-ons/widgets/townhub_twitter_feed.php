<?php
/* add_ons_php */

/**
 * Core class used to implement a Twitter Feed widget.
 *
 *
 * @see WP_Widget
 */
class TownHub_Twitter_Feed extends WP_Widget {

	/**
	 * Sets up a new Recent Posts widget instance.
	 *
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'townhub_twitter_feed', 
			'description' => __( "Display tweets on your site.",'townhub-add-ons') 
		);
		parent::__construct('townhub-twitter-feed', __('TownHub Twitter Feed','townhub-add-ons'), $widget_ops);
		$this->alt_option_name = 'townhub_twitter_feed';
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

		
		$username = $instance['username'];
		$list = $instance['list'];
		$hashtag = $instance['hashtag'];
		$count = ( ! empty( $instance['count'] ) ) ? absint( $instance['count'] ) : 2;
		if ( ! $count )
			$count = 2;

		$follow_url = $instance['follow_url'];
		$list_ticker = $instance['list_ticker'];

		?>
		<?php echo $args['before_widget']; ?>
		<?php if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		} ?>
		<?php 
		if($list_ticker != 'yes') : ?>
		<div class="twitter-holder fl-wrap scrollbar-inner2" data-simplebar data-simplebar-auto-hide="false">
		<?php endif;?>
            <div class="tweet townhub-tweet tweet-count-<?php echo esc_attr($count );?> tweet-ticker-<?php echo esc_attr($list_ticker );?>" data-username="<?php echo esc_attr($username );?>" data-list="<?php echo esc_attr($list );?>" data-hashtag="<?php echo esc_attr($hashtag );?>" data-ticker="<?php echo esc_attr($list_ticker );?>" data-count="<?php echo esc_attr($count );?>"></div>
        <?php 
		if($list_ticker != 'yes') : ?>
		</div>
        <?php endif;?>
		<?php 
		if($follow_url != '') : ?>
		<div class="follow-wrap">
			<a  href="<?php echo esc_url( $follow_url );?>" target="_blank" class="twiit-button footer-link twitter-link"><?php _e(' Follow Us','townhub-add-ons');?><i class="fal fa-long-arrow-right"></i></a>  
		</div>
		<?php endif;?>

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
		$instance['username'] = $new_instance['username'];
		$instance['list'] = $new_instance['list'];
		$instance['hashtag'] = $new_instance['hashtag'];
		$instance['follow_url'] = $new_instance['follow_url'];
		$instance['count'] = (int) $new_instance['count'];

		$instance['list_ticker'] = $new_instance['list_ticker'];

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
		$username    = isset( $instance['username'] ) ?  $instance['username'] : '';
		$list    = isset( $instance['list'] ) ?  $instance['list'] : '';
		$hashtag    = isset( $instance['hashtag'] ) ?  $instance['hashtag'] : '';
		$count    = isset( $instance['count'] ) ? absint( $instance['count'] ) : 2;

		$follow_url    = isset( $instance['follow_url'] ) ?  $instance['follow_url'] : '';

		$list_ticker    = isset( $instance['list_ticker'] ) ?  $instance['list_ticker'] : '';

?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title' ,'townhub-add-ons'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		
		<p><label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php _e( 'Username' ,'townhub-add-ons'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" type="text" value="<?php echo $username; ?>" /></p>
		<p><?php _e('Option to load tweets from another account - Optional. Leave this empty to load from your own (account with API keys on <strong>Settings -> TownHub Add-Ons -> Twitter Feeds Section</strong> tab).','townhub-add-ons');?></p>
		
		<p><label for="<?php echo $this->get_field_id( 'list' ); ?>"><?php _e( 'List name' ,'townhub-add-ons'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'list' ); ?>" name="<?php echo $this->get_field_name( 'list' ); ?>" type="text" value="<?php echo $list; ?>" /></p>

		<p><?php _e('List name to load tweets from - Optional. If you define list name you also must define the username of the list owner in the Username option.','townhub-add-ons');?></p>

		<p><label for="<?php echo $this->get_field_id( 'hashtag' ); ?>"><?php _e( 'Hashtag' ,'townhub-add-ons'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'hashtag' ); ?>" name="<?php echo $this->get_field_name( 'hashtag' ); ?>" type="text" value="<?php echo $hashtag; ?>" /></p>
		<p><?php _e('Option to load tweets with a specific hashtag - Optional.','townhub-add-ons');?></p>
		
		
		<p><label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Number of tweets you want to display.' ,'townhub-add-ons'); ?></label>
		<input class="tiny-text" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" type="number" step="1" min="1" value="<?php echo $count; ?>" size="3" /></p>

		<p><label for="<?php echo $this->get_field_id( 'follow_url' ); ?>"><?php _e( 'Follow Us link' ,'townhub-add-ons'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'follow_url' ); ?>" name="<?php echo $this->get_field_name( 'follow_url' ); ?>" type="text" value="<?php echo $follow_url; ?>" /></p>

		<p>
			<label for="<?php echo $this->get_field_id( 'list_ticker' ); ?>"><?php _e( 'Display tweets as a list ticker','townhub-add-ons'); ?></label>
			<select id="<?php echo $this->get_field_id( 'list_ticker' ); ?>" name="<?php echo $this->get_field_name( 'list_ticker' ); ?>">
				<option value="no" <?php selected( $list_ticker, 'no' ); ?>>
					<?php _e( 'No','townhub-add-ons'); ?>
				</option>
				<option value="yes" <?php selected( $list_ticker, 'yes' ); ?>>
					<?php _e( 'Yes','townhub-add-ons'); ?>
				</option>
				
			</select>
		</p>

		


<?php
	}
}
