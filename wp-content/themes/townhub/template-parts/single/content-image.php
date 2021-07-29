<?php
/* banner-php */
/**
 * Template part for displaying image posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */


?>
<!-- article> --> 
<article id="post-<?php the_ID(); ?>" <?php post_class('pos-single ptype-content-image post-article single-post-article'); ?>>
    <?php 
    if(townhub_get_option('single_featured' )): ?>
        <?php
        if(has_post_thumbnail( )){ ?>
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

