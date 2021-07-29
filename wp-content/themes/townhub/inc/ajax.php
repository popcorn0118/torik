<?php
/* banner-php */
/**
 * TownHub ajax calls functionality
 *
 * Prevents TownHub from running on WordPress versions prior to 4.7,
 * since this theme is not meant to be backward compatible beyond that and
 * relies on many newer functions and markup changes introduced in 4.7.
 *
 */


add_action('wp_ajax_nopriv_townhub_lm_folio', 'townhub_lm_folio_callback');
add_action('wp_ajax_townhub_lm_folio', 'townhub_lm_folio_callback');

function townhub_lm_folio_callback(){

    $output = array();
    $output['status'] = 'fail';
    if ( ! isset( $_POST['_lmnonce'] ) || ! wp_verify_nonce( $_POST['_lmnonce'], 'townhub_lm_folio' ) ) {
        // This nonce is not valid.
        $output['content'] = esc_html__('Sorry, your nonce did not verify.','townhub' );
        $output['is_remaining'] = 'no';
    } else {
        // The nonce was valid.
        // Do stuff here.
        $default_args = array(
            'post_type' => 'portfolio',
            'paged' => 1,
            'posts_per_page'=> 3,
            'post__in' => array(),
            'post__not_in' => array(),
            'orderby'=> 'date',
            'order'=> 'DESC',
            'cat'=> '',
            'lmore_items'=> 3,
            'click_num'=> 1,
        );

        $args = wp_parse_args( $_POST['wp_query'], $default_args );

        $lmore_items = $args['lmore_items'];

        if(isset($args['layout'])){
            $layout = $args['layout'];
            unset($args['layout']);
        }else{
            $layout = townhub_get_option('folio_layout');
        }
        if(isset($args['additional_vars'])){
            $additional_vars = $args['additional_vars'];
            unset($args['additional_vars']);
        }

        if($layout != 'slider') $layout = 'default';//item template layout
        
        unset($args['action']);
        unset($args['lmore_items']);
        

        $args['offset'] = $current_offset = $args['posts_per_page'] + $lmore_items*($_POST['click_num']-1);
        $args['posts_per_page'] = $lmore_items;

        $folio_posts = new WP_Query($args);
        ob_start(); 
        if($folio_posts->have_posts()) : 
            while($folio_posts->have_posts()) : $folio_posts->the_post(); 
                
                if(isset($additional_vars)){

                    townhub_get_template_part('template-parts/portfolio/item', $layout, $additional_vars); 
                }else{
                    get_template_part('template-parts/portfolio/item', $layout); 
                }



            endwhile;
            
        endif;

        $output['status'] = 'success';

        $output['content'] = ob_get_clean();

        //check for remaining items
        if($folio_posts->found_posts && $folio_posts->found_posts > $current_offset + $lmore_items){
            $output['is_remaining'] = 'yes';
        }else{
            $output['is_remaining'] = 'no';
        }

        wp_reset_postdata();
    }
    wp_send_json( $output );
}

add_action('wp_ajax_nopriv_townhub_lm_gal', 'townhub_lm_gal_callback');
add_action('wp_ajax_townhub_lm_gal', 'townhub_lm_gal_callback');

function townhub_lm_gal_callback(){
    $output = array();
    $output['status'] = 'fail';
    $output['content'] = '';
    $output['is_remaining'] = 'no';
    if ( ! isset( $_POST['_lmnonce'] ) || ! wp_verify_nonce( $_POST['_lmnonce'], 'townhub_lm_gal' ) ) {
        // This nonce is not valid.
        $output['content'] = esc_html__('Sorry, your nonce did not verify.','townhub' );
        $output['is_remaining'] = 'no';
    } else {
        // The nonce was valid.
        // Do stuff here.
        $default_args = array(
            'images' => '',
            'lmore_items'=> 3,
        );

        $args = wp_parse_args( $_POST['wp_query'], $default_args );
        $lmore_items = $args['lmore_items'];
        $lmore_images = $args['images'];
        $loaded = $args['loaded'];


        if(isset($args['show_img_title'])){
            $show_img_title = $args['show_img_title'];
        }else{
            $show_img_title = 'no';
        }

        if(isset($args['show_img_desc'])){
            $show_img_desc = $args['show_img_desc'];
        }else{
            $show_img_desc = 'no';
        }

        $loaded += $lmore_items*($_POST['click_num']-1);
        $new_loaded = $loaded + $lmore_items;

        if(!empty($lmore_images)){

            $images = explode(",", $lmore_images);

            if(!empty($images)) : 

            ob_start(); 

            foreach ($images as $key => $img) {
                if($key >= $loaded && $key < $new_loaded) {
                    $at_img = get_post($img);

                    $at_tit = $at_img->post_title;
                    $at_cap = $at_img->post_excerpt;
                    $at_des = $at_img->post_content;

                    $item_filter = '';

                    if( preg_match_all('/-f-([^-]*)-f-/m', $at_cap, $matches ) !== false ){
                        if(!empty($matches[1])){
                            foreach ($matches[1] as $fil) {
                                $item_filter .= ' '.sanitize_title( $fil );
                            }
                        }
                    }

                ?>
                <div class="gallery-item <?php echo esc_attr( $item_filter );?>">
                    <div class="grid-item-holder">
                        <?php echo wp_get_attachment_image( $img, 'townhub-folio-one'); ?>
                        <a href="<?php echo esc_url( wp_get_attachment_url($img ) );?>" class="popup-image slider-zoom"><i class="fa fa-search"></i></a>

                        <?php if($show_img_title == 'yes' || $show_img_desc == 'yes' ) : ?>
                        <div class="thumb-info">
                            <?php if($show_img_title == 'yes') : ?>
                            <h3><?php echo esc_html( $at_tit );?></h3>
                            <?php endif;?>
                            <?php if($show_img_desc == 'yes') echo wp_kses_post( $at_des ); ?>
                        </div>
                        <?php endif;?>
                    </div>
                </div>

            <?php
                }

            }

            if($key >= $new_loaded){
                $output['is_remaining'] = 'yes';
            }

            $output['content'] = ob_get_clean();

            endif;

        }


        $output['status'] = 'success';

    }
    wp_send_json( $output );

}