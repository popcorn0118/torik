<?php
/* add_ons_php */

class Esb_Class_FAQ_CPT extends Esb_Class_CPT {     
    protected $name = 'cthfaq';

    protected function init(){
        parent::init();

        add_action( 'init', array($this, 'taxonomies'), 0 ); 

        add_filter('manage_edit-cthfaq_cat_columns', array($this, 'tax_cat_columns_head') );
        add_filter('manage_cthfaq_cat_custom_column', array($this, 'tax_cat_columns_content'), 10, 3); 
        do_action( $this->name.'_cpt_init_after' );
 
    }
    public function disable_gutenberg( $current_status, $post_type ){
        if ($post_type === 'cthfaq') 
            return false;

        return $current_status;
    }
    public function tax_cat_columns_head($columns) {
        
        $columns['_id'] = __('ID','townhub-add-ons');
        return $columns;
    }

    public function tax_cat_columns_content($c, $column_name, $term_id) {
        if ($column_name == '_id') {
            echo $term_id;
        }
    }
    public function tax_alt_columns_head($columns) {
        $columns['_id'] = __('ID','townhub-add-ons');
        return $columns;
    }

    public function tax_alt_columns_content($c, $column_name, $term_id) {
        if ($column_name == '_id') {
            echo $term_id;
        }
    }
    
    public function register(){

        $labels = array( 
            'name' => __( 'FAQ', 'townhub-add-ons' ),
            'singular_name' => __( 'FAQ', 'townhub-add-ons' ),
            'add_new' => __( 'Add New FAQ', 'townhub-add-ons' ),
            'add_new_item' => __( 'Add New FAQ', 'townhub-add-ons' ),
            'edit_item' => __( 'Edit FAQ', 'townhub-add-ons' ),
            'new_item' => __( 'New FAQ', 'townhub-add-ons' ),
            'view_item' => __( 'View FAQ', 'townhub-add-ons' ),
            'search_items' => __( 'Search FAQs', 'townhub-add-ons' ),
            'not_found' => __( 'No FAQs found', 'townhub-add-ons' ),
            'not_found_in_trash' => __( 'No FAQs found in Trash', 'townhub-add-ons' ),
            'parent_item_colon' => __( 'Parent FAQ:', 'townhub-add-ons' ),
            'menu_name' => __( 'TownHub FAQs', 'townhub-add-ons' ),
        );

        $args = array( 
            'labels' => $labels,
            'hierarchical' => false,
            'description' => __( 'List FAQs', 'townhub-add-ons' ),
            'supports' => array( 'title', 'editor'/*, 'post-formats'*/),
            'taxonomies' => array('cthfaq_cat'),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 25,
            'menu_icon' => 'dashicons-editor-help', // plugin_dir_url( __FILE__ ) .'assets/admin_ico_cthfaq.png', 
            'show_in_nav_menus' => false,
            'has_archive' => false,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            
            'query_var' => false,
            'can_export' => true,
            'rewrite' => array( 'slug' => __('cthfaq','townhub-add-ons') ),
            'capability_type' => 'post'
        );
        register_post_type( $this->name, $args );
    }

    public function taxonomies(){
        $labels = array(
            'name' => __( 'Categories', 'townhub-add-ons' ),
            'singular_name' => __( 'Category', 'townhub-add-ons' ),
            'search_items' =>  __( 'Search Categories','townhub-add-ons' ),
            'all_items' => __( 'All Categories','townhub-add-ons' ),
            'parent_item' => __( 'Parent Category','townhub-add-ons' ),
            'parent_item_colon' => __( 'Parent Category:','townhub-add-ons' ),
            'edit_item' => __( 'Edit Category','townhub-add-ons' ), 
            'update_item' => __( 'Update Category','townhub-add-ons' ),
            'add_new_item' => __( 'Add New Category','townhub-add-ons' ),
            'new_item_name' => __( 'New Category Name','townhub-add-ons' ),
            'menu_name' => __( 'Categories','townhub-add-ons' ),
        );     
        // Now register the taxonomy
        register_taxonomy('cthfaq_cat',array('cthfaq'), array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_in_nav_menus'=> true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array( 'slug' => __('cthfaq_cat','townhub-add-ons') ),
            // https://codex.wordpress.org/Roles_and_Capabilities
            // 'capabilities' => array(
            //     'manage_terms' => 'manage_categories',
            //     'edit_terms' => 'manage_categories',
            //     'delete_terms' => 'manage_categories',
            //     'assign_terms' => 'edit_posts'
            // ),

        ));

        
    }

    

}

new Esb_Class_FAQ_CPT();

