<?php
/* banner-php */
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */

?>
<!-- article> --> 
<article id="post-<?php the_ID(); ?>" <?php post_class('post-article ptype-content'); ?>>
    <?php 
	// Get the list of files
    $slider_images = get_post_meta( get_the_ID(), '_cth_post_slider_images', true);
    if( !empty($slider_images)&& townhub_get_option('blog_show_format', true ) && get_post_format( ) !== 'gallery' ){ ?>
	<div class="list-single-main-media fl-wrap">
        <div class="single-slider-wrap">
            <div class="single-slider fl-wrap">
                <div class="swiper-container">
                    <div class="swiper-wrapper lightgallery">
                    	<?php 
		                foreach ( (array) $slider_images as $img_id => $img_url ) {
					        echo '<div class="swiper-slide hov_zoom">';
					        	echo wp_get_attachment_image($img_id, 'townhub-featured-image','',array('class'=>'respimg no-lazy') );
					        	echo '<a href="' . esc_url( wp_get_attachment_url( $img_id ) ) . '" class="box-media-zoom popup-image"><i class="fal fa-search"></i></a>';
					        echo '</div>';
					    }
						?>
                    </div>
                </div>
            </div>
            <div class="listing-carousel_pagination">
                <div class="listing-carousel_pagination-wrap">
                    <div class="ss-slider-pagination"></div>
                </div>
            </div>
            <div class="ss-slider-cont ss-slider-cont-prev color2-bg"><i class="fal fa-long-arrow-left"></i></div>
            <div class="ss-slider-cont ss-slider-cont-next color2-bg"><i class="fal fa-long-arrow-right"></i></div>
        </div>
    </div>
	<?php
	}elseif(has_post_thumbnail( )){ ?>
	<div class="list-single-main-media fl-wrap">
        <?php the_post_thumbnail('townhub-featured-image',array('class'=>'respimg') ); ?>
    </div>
	<?php } ?>
	<div class="list-single-main-item fl-wrap block_box post-content-wrap">
        <?php
        townhub_sticky_post();
        
        the_title( '<h2 class="post-opt-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' );
        
        the_excerpt();
        ?>
		<?php townhub_link_pages();?>

		<?php townhub_post_tags(); ?>

		
        <?php townhub_post_meta(); ?>
        
        

    </div>
</article>
<!-- article end -->       
