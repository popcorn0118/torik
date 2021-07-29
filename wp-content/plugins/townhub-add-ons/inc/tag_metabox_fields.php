<?php
/* add_ons_php */

//For post_tag taxonomy
// Add term page
function townhub_post_tag_add_new_meta_field() {
    
    // this will add the custom meta field to the add new term page
    // wp_enqueue_media();
    // wp_enqueue_script('townhub_tax_meta', ESB_DIR_URL . 'inc/assets/upload_file.js', array('jquery'), null, true);
    townhub_radio_options_field(array(
                                'id'=>'tax_show_header',
                                'name'=>esc_html__('Show Header Section','townhub-add-ons'),
                                'values' => array(
                                        'yes'=> esc_html__('Yes','townhub-add-ons'),
                                        'no'=> esc_html__('No','townhub-add-ons'),
                                    ),
                                'default'=>'yes'
    ));
    townhub_select_media_file_field('cat_header_image',esc_html__('Header Background Image','townhub-add-ons'), array());
    townhub_radio_options_field(array(
                                'id'=>'tax_title_in_content',
                                'name'=>esc_html__('Show Tag Info','townhub-add-ons'),
                                'values' => array(
                                        'yes'=> esc_html__('Yes','townhub-add-ons'),
                                        'no'=> esc_html__('No','townhub-add-ons'),
                                    ),
                                
                                'default'=>'no'
    ) );

}
add_action('post_tag_add_form_fields', 'townhub_post_tag_add_new_meta_field', 10, 2);

// Edit term page
function townhub_post_tag_edit_meta_field($term) {
    // wp_enqueue_media();
    // wp_enqueue_script('townhub_tax_meta', ESB_DIR_URL . 'inc/assets/upload_file.js', array('jquery'), null, true);
    
    // put the term ID into a variable
    $t_id = $term->term_id;
    
    // retrieve the existing value(s) for this meta field. This returns an array
    $term_meta = get_option("townhub_taxonomy_post_tag_$t_id");

    townhub_radio_options_field(array(
                                'id'=>'tax_show_header',
                                'name'=>esc_html__('Show Header Section','townhub-add-ons'),
                                'values' => array(
                                        'yes'=> esc_html__('Yes','townhub-add-ons'),
                                        'no'=> esc_html__('No','townhub-add-ons'),
                                    ),

                                'default'=>'yes'
    ),$term_meta,false);
    townhub_select_media_file_field('cat_header_image',esc_html__('Header Background Image','townhub-add-ons'), $term_meta,false);
    townhub_radio_options_field(array(
                                'id'=>'tax_title_in_content',
                                'name'=>esc_html__('Show Tag Info','townhub-add-ons'),
                                'values' => array(
                                        'yes'=> esc_html__('Yes','townhub-add-ons'),
                                        'no'=> esc_html__('No','townhub-add-ons'),
                                    ),
                                
                                'default'=>'no'
    ) ,$term_meta,false);
}
add_action('post_tag_edit_form_fields', 'townhub_post_tag_edit_meta_field', 10, 2);

// Save extra taxonomy fields callback function.
function townhub_save_post_tag_custom_meta($term_id) {
    if (isset($_POST['term_meta'])) {
        $t_id = $term_id;
        $term_meta = get_option("townhub_taxonomy_post_tag_$t_id");
        $cat_keys = array_keys($_POST['term_meta']);
        foreach ($cat_keys as $key) {
            if (isset($_POST['term_meta'][$key])) {
                $term_meta[$key] = $_POST['term_meta'][$key];
            }
        }
        
        // Save the option array.
        update_option("townhub_taxonomy_post_tag_$t_id", $term_meta);
    }
}
add_action('edited_post_tag', 'townhub_save_post_tag_custom_meta', 10, 2);
add_action('create_post_tag', 'townhub_save_post_tag_custom_meta', 10, 2);
