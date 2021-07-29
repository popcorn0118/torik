<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $post_type = $posts_per_page = $order_by = $order = $title = $hide_widget_on = '';    

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'lrooms',
    'azp-element-' . $azp_mID,
    $el_class,
);
$classes = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $classes ) ) );  
if($el_id!=''){
    $el_id = 'id="'.$el_id.'"';
} 
if(( $hide_widget_on_check = townhub_addons_is_hide_on_plans($hide_widget_on) ) !== 'true') :
$lrooms = get_post_meta( get_the_ID(), ESB_META_PREFIX.'rooms_ids', true );  
    
if( !empty($lrooms) && is_array($lrooms) ){ 
    $listing_type_ID = get_post_meta( get_the_ID(), ESB_META_PREFIX.'listing_type_id', true );
    $child_pt = get_post_meta( $listing_type_ID, ESB_META_PREFIX.'child_type_meta', true );
    $child_type = ($child_pt == 'product') ? 'product' : 'lrooms';
?>
<div class="<?php echo $classes; ?> authplan-hide-<?php echo $hide_widget_on_check;?>" <?php echo $el_id;?>> 
    <div class="for-hide-on-author"></div>
    <!-- lsingle-block-box --> 
    <div class="lsingle-block-box">
        <?php if($title != ''): ?>
        <div class="lsingle-block-title">
            <h3><?php echo $title; ?></h3>
        </div>
        <?php endif; ?>
        <div class="lsingle-block-content">
            <?php 
            $args = array(
                'post_type'         => $child_type,
                'post_status'       => 'publish',
                'post__in'          => $lrooms,
                'posts_per_page'    => -1,
                'orderby'           => $order_by,
                'order'             => $order,
            );
            $posts_query = new \WP_Query($args);
            if($posts_query->have_posts()) : ?>
            <!--  rooms-container -->
            <div class="rooms-container fl-wrap">
            <?php
            while($posts_query->have_posts()) : $posts_query->the_post(); 
                ?>
                <!-- lrooms-item-wrap -->
                <div class="lrooms-item-wrap">
                    <?php 
                        echo townhub_addons_azp_parser_listing( $listing_type_ID  , 'preview_room' );
                    ?>
                </div>
                <!-- lrooms-item-wrap end -->      
            <?php
            endwhile; //end the while loop
            ?>
            </div>
            <!--   rooms-container end -->
            <?php
                endif; // end of the loop. 
                wp_reset_postdata();
            ?>  
        </div>
    </div>
    <!-- lsingle-block-box end --> 
</div>
<?php 
    }

endif;// check hide on plans
