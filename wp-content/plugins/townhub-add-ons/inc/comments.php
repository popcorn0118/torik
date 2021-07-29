<?php
/* add_ons_php */
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
<div id="lreviews_sec"></div>
<?php if ( have_comments() ) : ?>
<!-- lsingle-block-box -->   
<div class="lsingle-block-box" id="lreviews_sec_wrap">
    <div class="lsingle-block-title">
        <h3><?php echo sprintf( __( 'Items Reviewed - <span>%s</span>', 'townhub-add-ons' ), number_format_i18n( get_comments_number() ) );?></h3>
    </div>
    <?php 
    do_action( 'comments-list-before', get_the_ID() ); 

	$args = array(
		'walker'            => null,
		'max_depth'         => '',
		'style'             => 'div',
		'callback'          => 'townhub_addons_comments',
		'end-callback'      => null,
		'type'              => 'all',
		'reply_text'        => esc_html__('Reply','townhub-add-ons'),
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
	<div class="lsingle-block-content">
		
		<div class="reviews-comments-wrap">
	        <?php wp_list_comments($args);?>
	    </div>
	    <?php
		// Are there comments to navigate through?
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
		?>
		<div class="comments-nav">
			<ul class="pager clearfix">
				<li class="previous"><?php previous_comments_link( wp_kses(__( '<i class="fa fa-angle-double-left"></i> Previous Comments', 'townhub-add-ons' ), array('i'=>array('class'=>array())) ) ); ?></li>
				<li class="next"><?php next_comments_link( wp_kses(__( 'Next Comments <i class="fa fa-angle-double-right"></i>', 'townhub-add-ons' ), array('i'=>array('class'=>array())) ) ); ?></li>
			</ul>
		</div>
		<?php endif; // Check for comment navigation ?>

	  	<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.' , 'townhub-add-ons' ); ?></p>
		<?php endif; ?>
		
	</div>
	    
</div>
<!-- lsingle-block-box end -->   
<?php endif; ?>


<?php if(comments_open( )) : ?>
	<!-- lsingle-block-box -->   
    <div class="lsingle-block-box" id="lreviews_form">
        <?php
        	$logBtnAttrs = townhub_addons_get_login_button_attrs( '', 'current' );

    		$commenter = wp_get_current_commenter();
    		$req = get_option( 'require_name_email' );
			$aria_req = ( $req ? " required aria-required='true'" : '' );
			$char_req = ( $req ? '*' : '' );

			$comment_args = array(
			'title_reply_before'   => '<div class="comment-reply-title-wrap"><h3 id="reply-title" class="comment-reply-title">',
			'title_reply_after'    => '</h3></div>',
			'title_reply'=> esc_html__('Add Review','townhub-add-ons'),
			'fields' => apply_filters( 'comment_form_default_fields', 
			array(
                                            
					'author' => '<div class="row"><div class="col-md-6"><label for="author"><i class="fal fa-user"></i></label><input type="text" class="has-icon" id="author" name="author" placeholder="'.esc_attr__('Your Name ','townhub-add-ons'). $char_req .'" value="' . esc_attr( $commenter['comment_author'] ) . '" ' . $aria_req . ' size="40"></div>',
					'email' =>'<div class="col-md-6"><label for="email"><i class="fal fa-envelope"></i></label><input class="has-icon" id="email" name="email" type="email" placeholder="'.esc_attr__('Your Email ','townhub-add-ons'). $char_req .'" value="' . esc_attr(  $commenter['comment_author_email'] ) .'" ' . $aria_req . ' size="40"></div></div>',
					) 
				),
			'comment_field' => '<textarea name="comment" id="comment" cols="40" rows="3" placeholder="'.esc_attr__('Your Review:','townhub-add-ons').'" '.$aria_req.'></textarea>',
			'id_form'=>'commentform',
			'class_form'=>'add-comment custom-form listing-review-form',
			'id_submit' => 'submit',
			'class_submit'=>'btn big-btn color-bg',
			'label_submit' => esc_html__('Submit Review','townhub-add-ons'),
			'must_log_in'=> '<div class="cm-must-log-in not-empty">' . sprintf( __( 'You must be <a class="comment-log-popup %1$s" href="%2$s">logged in</a> to post a comment.' ,'townhub-add-ons'), esc_attr( $logBtnAttrs['class'] ), esc_url( $logBtnAttrs['url'] ) ) . '</div>',
			'logged_in_as' => '<div class="cm-logged-in-as not-empty">' . sprintf( wp_kses(__( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>','townhub-add-ons' ),array('a'=>array('href'=>array(),'title'=>array(),'target'=>array())) ), Esb_Class_Dashboard::screen_url(), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</div>',
			'comment_notes_before' => '<div class="cm-notes-before text-center">'.esc_html__('Your email is safe with us.','townhub-add-ons').'</div>',
			'comment_notes_after' => '',
			);
		?>
		<?php comment_form($comment_args); ?>
    </div>
    <!-- lsingle-block-box end --> 

<?php endif;?>
