<?php
/* banner-php */
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */

?>
<!-- list-single-main-item -->   
<div class="list-single-main-item fl-wrap block_box">
    <div class="post-author-block">

        <div class="list-author-infos-wrap">
            
            <div class="list-author-avatar">
                <?php 
                    echo get_avatar(get_the_author_meta('user_email'), '80', 'https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=80', get_the_author_meta( 'display_name' ) );
                ?>
            </div>
            
            
            <div class="list-author-infos">
                <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php echo get_the_author_meta('nickname');?></a>
                <span></span>
            </div>

            
        </div>
        
        <div class="list-author-widget-text">
            <div class="list-author-widget-contacts">
                <?php echo get_the_author_meta('description');?>
            </div>
            <?php 
            $socials = get_user_meta( get_the_author_meta('ID'), ESB_META_PREFIX.'socials' ,true ); 
            if( !empty($socials) && 'no' !== esc_html_x( 'yes', 'Show author socials on single post page: yes or no', 'townhub' ) ):
            ?>
            <div class="list-widget-social">
                
                <ul>
                    <?php 
                    foreach ((array)$socials as $social) {
                        $iconcs = 'fab fa-'.$social['name'];
                        if($social['name'] == 'envelope') $iconcs = 'fal fa-'.$social['name'];
                        echo '<li><a href="'.esc_url($social['url']).'" target="_blank"><i class="'.esc_attr($iconcs).'"></i></a></li>';
                    } ?>
                </ul>
            </div>
            <?php endif; ?> 
        </div>
        
    </div>
</div>
<!-- list-single-main-item end -->   