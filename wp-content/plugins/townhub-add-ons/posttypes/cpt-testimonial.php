<?php
/* add_ons_php */

class Esb_Class_CTHTesti_CPT extends Esb_Class_CPT {
    protected $name = 'cth_testimonial';

    public function register(){

        $labels = array( 
            'name' => __( 'Testimonial', 'townhub-add-ons' ),
            'singular_name' => __( 'Testimonial', 'townhub-add-ons' ),
            'add_new' => __( 'Add New Testimonial', 'townhub-add-ons' ),
            'add_new_item' => __( 'Add New Testimonial', 'townhub-add-ons' ),
            'edit_item' => __( 'Edit Testimonial', 'townhub-add-ons' ),
            'new_item' => __( 'New Testimonial', 'townhub-add-ons' ),
            'view_item' => __( 'View Testimonial', 'townhub-add-ons' ),
            'search_items' => __( 'Search Testimonials', 'townhub-add-ons' ),
            'not_found' => __( 'No Testimonials found', 'townhub-add-ons' ),
            'not_found_in_trash' => __( 'No Testimonials found in Trash', 'townhub-add-ons' ),
            'parent_item_colon' => __( 'Parent Testimonial:', 'townhub-add-ons' ),
            'menu_name' => __( 'Testimonials', 'townhub-add-ons' ),
        );

        $args = array( 
            'labels' => $labels,
            'hierarchical' => true,
            'description' => __( 'List Testimonials', 'townhub-add-ons' ),
            'supports' => array( 'title', 'editor', 'thumbnail'/*,'comments', 'post-formats'*/),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 25,
            'menu_icon' => 'dashicons-format-chat', 
            'show_in_nav_menus' => false,
            'publicly_queryable' => true,
            'exclude_from_search' => true,
            'has_archive' => false,
            'query_var' => true,
            'can_export' => true,
            'rewrite' => true,
            'capability_type' => 'post'
        );
        register_post_type( $this->name, $args );
    }
    protected function set_meta_columns(){
        $this->has_columns = true;
    }
    public function meta_columns_head($columns){
        $columns['_thumbnail'] = __( 'Thumbnail', 'townhub-add-ons' );
        $columns['_rating'] = __( 'Rating', 'townhub-add-ons' );
        $columns['_id'] = __( 'ID', 'townhub-add-ons' );
        return $columns;
    }
    public function meta_columns_content($column_name, $post_ID){
        if ($column_name == '_id') {
            echo $post_ID;
        }
        if ($column_name == '_thumbnail') {
            echo get_the_post_thumbnail( $post_ID, 'thumbnail', array('style'=>'width:100px;height:auto;') );
        }
        if ($column_name == '_rating') {
            $rated = get_post_meta($post_ID, ESB_META_PREFIX.'testim_rate', true );
            if($rated != '' && $rated != 'no'){
                $ratedval = floatval($rated);
                echo '<ul class="star-rating">';
                for ($i=1; $i <= 5; $i++) { 
                    if($i <= $ratedval){
                        echo '<li><i class="testimfa testimfa-star"></i></li>';
                    }else{
                        if($i - 0.5 == $ratedval){
                            echo '<li><i class="testimfa testimfa-star-half"></i></li>';
                        }
                    }
                    
                }
                echo '</ul>';
            }else{
                esc_html_e('Not Rated','townhub-add-ons' );
            }
        }
    }

}

new Esb_Class_CTHTesti_CPT();
