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
<article id="post-<?php the_ID(); ?>" <?php post_class('pos-single ptype-content post-article single-post-article'); ?>>
    <?php 
    if(townhub_get_option('single_featured' )): ?>
        <?php 
        // Get the list of files
        $slider_images = get_post_meta( get_the_ID(), '_cth_post_slider_images', true);
        if( !empty($slider_images)&& townhub_get_option('blog_show_format' ) && get_post_format( ) !== 'gallery' ){ ?>
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
