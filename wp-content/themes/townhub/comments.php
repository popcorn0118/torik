<?php
/* banner-php */
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>
<?php if ( have_comments() ) : ?>
<!-- list-single-main-item -->   
<div class="list-single-main-item fl-wrap block_box" id="post-comments">
    <div class="list-single-main-item-title">
        <h3><?php esc_html_e( 'Comments - ', 'townhub' );?><span> <?php echo number_format_i18n( get_comments_number() ) ;?> </span></h3>
    </div>
    <?php 
	$args = array(
		'walker'            => null,
		'max_depth'         => '',
		'style'             => 'div',
		'callback'          => 'townhub_comments',
		'end-callback'      => null,
		'type'              => 'all',
		'reply_text'        => esc_html__('Reply','townhub'),
		'page'              => '',
		'per_page'          => '',
		'avatar_size'       => 50,
		'reverse_top_level' => null,
		'reverse_children'  => '',
		'format'            => 'html5', //or xhtml if no HTML5 theme support
		'short_ping'        => false, // @since 3.6,
	    'echo'     			=> true, // boolean, default is true
	);
	?>
	<div class="list-single-main-item_content fl-wrap">
        <div class="reviews-comments-wrap">
        	<?php wp_list_comments($args);?>
        </div>
    </div>
    <?php
	// Are there comments to navigate through?
	if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
	?>
	<div class="comments-nav">
		<ul class="pager clearfix">
			<li class="previous"><?php previous_comments_link( wp_kses(__( '<i class="fa fa-angle-left"></i> Previous Comments', 'townhub' ), array('i'=>array('class'=>array())) ) ); ?></li>
			<li class="next"><?php next_comments_link( wp_kses(__( 'Next Comments <i class="fa fa-angle-right"></i>', 'townhub' ), array('i'=>array('class'=>array())) ) ); ?></li>
		</ul>
	</div>
	<?php endif; // Check for comment navigation ?>

  	<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.' , 'townhub' ); ?></p>
	<?php endif; ?>
</div>
<!-- list-single-main-item end -->   
<?php endif; ?>


<?php if(comments_open( )) : ?>
<!-- list-single-main-item -->   
<div class="list-single-main-item fl-wrap block_box" id="post-respond">
    <div id="add-review" class="add-review-box">
        <?php
    		$commenter = wp_get_current_commenter();
    		$req = get_option( 'require_name_email' );
			$aria_req = ( $req ? " aria-required='true'" : '' );
			$char_req = ( $req ? '*' : '' );

			$comment_args = array(
			'title_reply'=> esc_html__('Add Comment','townhub'),
			'fields' => apply_filters( 'comment_form_default_fields', 
			array(                     
					'author' => '<div class="row"><div class="col-md-6"><label for="author"><i class="fal fa-user"></i></label><input type="text" class="has-icon" id="author" name="author" placeholder="'.esc_attr__('Your Name ','townhub'). $char_req .'" value="' . esc_attr( $commenter['comment_author'] ) . '" ' . $aria_req . ' size="40"></div>',
					'email' =>'<div class="col-md-6"><label for="email"><i class="fal fa-envelope"></i></label><input class="has-icon" id="email" name="email" type="email" placeholder="'.esc_attr__('Your Email ','townhub'). $char_req .'" value="' . esc_attr(  $commenter['comment_author_email'] ) .'" ' . $aria_req . ' size="40"></div></div>',
					) 
				),
			'comment_field' => '<textarea name="comment" id="comment" cols="40" rows="3" placeholder="'.esc_attr__('Your Comment:','townhub').'" '.$aria_req.'></textarea>',
			'id_form'=>'commentform',
			'class_form'=>'add-comment custom-form',
			'id_submit' => 'submit',
			'class_submit'=>'btn color2-bg',
			'label_submit' => esc_html__('Submit Comment','townhub'),
			'must_log_in'=> '<p class="not-empty">' . __( 'You must be <a class="comment-log-popup logreg-modal-open" href="#">logged in</a> to post a comment.' ,'townhub') /*  sprintf( wp_kses(__( 'You must be <a href="%s">logged in</a> to post a comment.' ,'townhub'),array('a'=>array('href'=>array(),'title'=>array(),'target'=>array())) ), wp_login_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) */ . '</p>',
			'logged_in_as' => '<p class="not-empty">' . sprintf( wp_kses(__( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>','townhub' ),array('a'=>array('href'=>array(),'title'=>array(),'target'=>array())) ), ( method_exists('Esb_Class_Dashboard', 'screen_url') ? Esb_Class_Dashboard::screen_url() : admin_url( 'profile.php' ) ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p>',
			'comment_notes_before' => '<p class="text-center">'.esc_html__('Your email is safe with us.','townhub').'</p>',
			'comment_notes_after' => '',
			);

			if( function_exists('townhub_addons_get_login_button_attrs') ){
				$logBtnAttrs = townhub_addons_get_login_button_attrs( '', 'current' );
				$comment_args['must_log_in'] = sprintf( __( 'You must be <a class="comment-log-popup %1$s" href="%2$s">logged in</a> to post a comment.' ,'townhub'), esc_attr( $logBtnAttrs['class'] ), esc_url( $logBtnAttrs['url'] ) );
			}
		?>
		<?php comment_form($comment_args); ?>
	</div>
</div>
<!-- list-single-main-item end --> 

<?php endif;?>
