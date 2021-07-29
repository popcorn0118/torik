<?php
/* banner-php */
/**
 * Template part for displaying audio posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */

?>
<!-- article> --> 
<article id="post-<?php the_ID(); ?>" <?php post_class('pos-single ptype-content-audio post-article single-post-article'); ?>>
    <?php 
    if(townhub_get_option('single_featured' )): ?>
        <?php 
		if(get_post_meta(get_the_ID(), '_cth_embed_video', true)!=""){ ?>	
		    <div class="list-single-main-media fl-wrap">
		    	<?php
		    		$audio_url = get_post_meta(get_the_ID(), '_cth_embed_video', true);
					if(preg_match('/(.mp3|.ogg|.wma|.m4a|.wav)$/i', $audio_url )){
						$attr = array(
							'src'      => $audio_url,
							'loop'     => '',
							'autoplay' => '',
							'preload'  => 'none'
						);
						echo wp_audio_shortcode( $attr );
					}else{
				?>
					<div class="resp-audio">
						<?php echo wp_oembed_get(esc_url( $audio_url ) , array('height'=>'166') ); ?>
					</div>
				<?php } ?>
		    	
	        </div>
        <?php
        }elseif(has_post_thumbnail( )){ ?>
        <div class="list-single-main-media fl-wrap">
            <?php the_post_thumbnail('townhub-single-image',array('class'=>'respimg') ); ?>
        </div>
        <?php } 
        ?>
    <?php 
    endif; ?>
    <div class="list-single-main-item fl-wrap block_box">
        <?php 
        if( get_post_meta(get_the_ID(),'_cth_show_page_header',true ) != 'yes' || ( get_post_meta(get_the_ID(),'_cth_show_page_header',true ) == 'yes' && get_post_meta(get_the_ID(),'_cth_show_page_title',true ) != 'yes' ) ) the_title( '<'.townhub_get_option('single_title_tag').' class="post-opt-title">', '</'.townhub_get_option('single_title_tag').'>' );
        townhub_edit_link( get_the_ID() );
        ?>
        <?php townhub_single_post_meta(); ?>
        <?php the_content();?>
        <div class="clearfix"></div>
        <?php townhub_link_pages();?>
        
        <?php townhub_single_post_tags(); ?>
        
        
    </div>
</article>
<!-- article end -->       

