<?php
/* banner-php */
/**
 * Template part for displaying gallery posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */

?>
<!-- article> --> 
<article id="post-<?php the_ID(); ?>" <?php post_class('pos-single ptype-content-gallery post-article single-post-article'); ?>>
    <?php 
    if(townhub_get_option('single_featured' )): ?>
        <?php 
        // Get the list of files
        $slider_images = get_post_meta( get_the_ID(), '_cth_post_slider_images', true);
        if( !empty($slider_images)&& townhub_get_option('blog_show_format' ) ){ ?>
        <div class="list-single-main-media fl-wrap">
            <div class="cthiso-items cthiso-small-pad cthiso-<?php echo get_post_meta( get_the_ID() , '_cth_gallery_cols', true );?>-cols clearfix cthiso-flex">
            <?php 
            foreach ( (array) $slider_images as $img_id => $img_url ) {
                ?>
                <div class="cthiso-item">
                    <div class="grid-item-holder">
                        <?php echo wp_get_attachment_image( $img_id, 'townhub-folio-one'); ?>
                        <a href="<?php echo esc_url( wp_get_attachment_url( $img_id ) );?>" class="popup-image slider-zoom"><i class="fal fa-search"></i></a>
                    </div>
                </div>
            <?php
            }
            ?>
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

