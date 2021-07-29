<?php
/* add_ons_php */

//For portfolio_cat taxonomy
//https://make.wordpress.org/core/2015/09/04/taxonomy-term-metadata-proposal/
// Add term page
function townhub_portfolio_cat_add_new_meta_field() {
    
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

}
add_action('portfolio_cat_add_form_fields', 'townhub_portfolio_cat_add_new_meta_field', 10, 2);

// Edit term page
function townhub_portfolio_cat_edit_meta_field($term) {
    // wp_enqueue_media();
    // wp_enqueue_script('townhub_tax_meta', ESB_DIR_URL . 'inc/assets/upload_file.js', array('jquery'), null, true);
    
    // put the term ID into a variable
    $t_id = $term->term_id;
    
    // retrieve the existing value(s) for this meta field. This returns an array
    $term_meta = get_option("townhub_taxonomy_portfolio_cat_$t_id");
    
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
}
add_action('portfolio_cat_edit_form_fields', 'townhub_portfolio_cat_edit_meta_field', 10, 2);

// Save extra taxonomy fields callback function.
function townhub_save_portfolio_cat_custom_meta($term_id) {
    if (isset($_POST['term_meta'])) {
        $t_id = $term_id;
        $term_meta = get_option("townhub_taxonomy_portfolio_cat_$t_id");
        $cat_keys = array_keys($_POST['term_meta']);
        foreach ($cat_keys as $key) {
            if (isset($_POST['term_meta'][$key])) {
                $term_meta[$key] = $_POST['term_meta'][$key];
            }
        }
        
        // Save the option array.
        update_option("townhub_taxonomy_portfolio_cat_$t_id", $term_meta);
    }
}
add_action('create_portfolio_cat', 'townhub_save_portfolio_cat_custom_meta', 10, 2);
add_action('edited_portfolio_cat', 'townhub_save_portfolio_cat_custom_meta', 10, 2);
