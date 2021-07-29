<?php
/* add_ons_php */

/**
 * Core class used to implement a Recent Posts widget.
 *
 *
 * @see WP_Widget
 */
class TownHub_Recent_Posts extends WP_Widget {

	/**
	 * Sets up a new Recent Posts widget instance.
	 *
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array('classname' => 'townhub_recent_posts', 'description' => __( "Your site&#8217;s most recent Posts.",'townhub-add-ons') );
		parent::__construct('townhub-recent-posts', __('TownHub Recent Posts','townhub-add-ons'), $widget_ops);
		$this->alt_option_name = 'townhub_recent_posts';
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

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'TownHub Recent Posts' ,'townhub-add-ons');

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number )
			$number = 5;
		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
		$show_thumbnail = isset( $instance['show_thumbnail'] ) ? $instance['show_thumbnail'] : false;
		$show_comment = isset( $instance['show_comment'] ) ? $instance['show_comment'] : false;

		/**
		 * Filter the arguments for the Recent Posts widget.
		 *
		 *
		 * @see WP_Query::get_posts()
		 *
		 * @param array $args An array of arguments used to retrieve the recent posts.
		 */
		$r = new WP_Query( apply_filters( 'widget_posts_args', array(
			'posts_per_page'      => $number,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true
		) ) );

		if ($r->have_posts()) :
		?>
		<?php echo $args['before_widget']; ?>
		<?php if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		} ?>
		<div class="widget-posts fl-wrap">
			<ul class="no-list-style">
			<?php while ( $r->have_posts() ) : $r->the_post(); ?>
				<li class="widget-posts-item clearfix">
				<?php if(has_post_thumbnail( ) && $show_thumbnail == true) :?>
	                <a href="<?php the_permalink(); ?>" class="widget-posts-img"><?php the_post_thumbnail( 'townhub-recent',array('class'=>'respimg'));?></a>
	            <?php endif;?>
	            	<div class="widget-posts-descr<?php if($show_thumbnail == false || !has_post_thumbnail( ) ) echo ' widget-hide-thumb';?>">

	                    <a href="<?php the_permalink(); ?>" title="<?php get_the_title() ? the_title() : the_ID(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>
						<?php if ( $show_date || $show_comment ) : ?>
	                    <span class="widget-posts-date">
	                    <?php if ( $show_date ) : ?>
                            <span class="post-date"><i class="fal fa-calendar"></i> <?php echo get_the_date(); ?></span> 
                        <?php endif; ?>
                        <?php if ( $show_comment ) : ?>
                            <a href="<?php comments_link(); ?>" class="post-comments"><?php
		printf( _nx( '%1$s Comment', '%1$s Comments', get_comments_number(), 'comments_num_title', 'townhub-add-ons' ),
			number_format_i18n( get_comments_number() ) );
	?></a> 
                        <?php endif; ?>
                        </span>
                        <?php endif; ?>

	                </div>
	            </li>
			<?php endwhile; ?>
            </ul>

			<a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>" class="footer-link"><?php _e( 'Read all ', 'townhub-add-ons' ); ?><i class="fal fa-long-arrow-right"></i></a>
        </div>


		<?php echo $args['after_widget']; ?>
		<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;
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
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		$instance['show_thumbnail'] = isset( $new_instance['show_thumbnail'] ) ? (bool) $new_instance['show_thumbnail'] : false;
		$instance['show_comment'] = isset( $new_instance['show_comment'] ) ? (bool) $new_instance['show_comment'] : false;
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
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
		$show_thumbnail = isset( $instance['show_thumbnail'] ) ? (bool) $instance['show_thumbnail'] : false;
		$show_comment = isset( $instance['show_comment'] ) ? (bool) $instance['show_comment'] : false;
?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ,'townhub-add-ons'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ,'townhub-add-ons'); ?></label>
		<input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" /></p>
		
		<p><input class="checkbox" type="checkbox" <?php checked( $show_thumbnail ); ?> id="<?php echo $this->get_field_id( 'show_thumbnail' ); ?>" name="<?php echo $this->get_field_name( 'show_thumbnail' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_thumbnail' ); ?>"><?php _e( 'Display post thumbnail?' ,'townhub-add-ons'); ?></label></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?','townhub-add-ons'); ?></label></p>
		
		<p><input class="checkbox" type="checkbox" <?php checked( $show_comment ); ?> id="<?php echo $this->get_field_id( 'show_comment' ); ?>" name="<?php echo $this->get_field_name( 'show_comment' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_comment' ); ?>"><?php _e( 'Display comment count?','townhub-add-ons'); ?></label></p>


<?php
	}
}
