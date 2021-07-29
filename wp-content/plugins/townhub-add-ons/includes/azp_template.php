<?php 
/* add_ons_php */


class Azp_Template_Manager{
	public static function init(){
		add_action( 'init', array(__CLASS__, 'register_post_type') );
		$actions = array(
			'azp_template_save',
			'azp_template_get',
			'azp_template_delete',
		);

		foreach ($actions as $action) {
			// only for logged in user
			add_action( 'wp_ajax_'.$action, array(__CLASS__, str_replace("azp_template", "template", $action) ) );
		}
	}

	public static function register_post_type(){
		$labels = array( 
	        'name' => __( 'AZP Template', 'townhub-add-ons' ),
	        'singular_name' => __( 'AZP Template', 'townhub-add-ons' ),
	        'add_new' => __( 'Add Template', 'townhub-add-ons' ),
	        'add_new_item' => __( 'Add Template', 'townhub-add-ons' ),   
	        'edit_item' => __( 'Edit Template', 'townhub-add-ons' ),
	        'new_item' => __( 'New Template', 'townhub-add-ons' ),
	        'view_item' => __( 'View Template', 'townhub-add-ons' ),
	        'search_items' => __( 'Search Templates', 'townhub-add-ons' ),
	        'not_found' => __( 'No Templates found', 'townhub-add-ons' ),
	        'not_found_in_trash' => __( 'No Templates found in Trash', 'townhub-add-ons' ),
	        'parent_item_colon' => __( 'Parent Template:', 'townhub-add-ons' ), 
	        'menu_name' => __( 'AZP Template', 'townhub-add-ons' ),
	    );

	    $args = array( 
	        'labels' => $labels,
	        'hierarchical' => true,
	        'description' => 'AZP Page builder template',  
	        'supports' => array( 'title', 'editor', 'thumbnail'),
	        'taxonomies' =>  array(),
	        'public' => true,
	        'show_ui' => true,
	        'show_in_menu' => true,
	        'menu_position' => 25,
	        'menu_icon' => 'dashicons-location-alt', // plugin_dir_url( __FILE__ ) .'assets/admin_ico_listing.png', 
	        'show_in_nav_menus' => true,
	        'publicly_queryable' => true,
	        'exclude_from_search' => false,
	        'has_archive' => true,
	        'query_var' => true,
	        'can_export' => true,
	        'rewrite' => array( 'slug' => __('azp_template','townhub-add-ons') ),
	        'capability_type' => 'post',
            'capabilities' => array(
                'create_posts' => 'do_not_allow', // false < WP 4.5, credit @Ewout
            ),
            'map_meta_cap' => true, // Set to `false`, if users are not allowed to edit/delete existing posts
	    );

	    register_post_type( 'azp_template', $args );
	}

	public static function template_save(){
		$json = array(
			'success'	=> false,
			'POST'		=> $_POST,
		);
		$template_datas['post_title'] = $_POST['name'];
		$template_datas['post_content'] = $_POST['shortcode'];
		//$template_datas['post_author'] = '0';// default 0 for no author assigned
		$template_datas['post_status'] = 'publish';
		$template_datas['post_type'] = 'azp_template';
		$template_datas = apply_filters( 'azp_template_save_before', $template_datas ); 
		// do_action( 'azp_template_save_before', $template_datas );
		$template_id = wp_insert_post($template_datas ,true );
		if (!is_wp_error($template_id)) {
			$meta_fields = array(
				'builder'	=> 'text'
			);
			$template_metas = array();
            foreach($meta_fields as $field => $ftype){
                if(isset($_POST[$field])) 
                	$template_metas[$field] = $_POST[$field] ;
                else{
                    if($ftype == 'array'){
                        $template_metas[$field] = array();
                    }else{
                        $template_metas[$field] = '';
                    }
                } 
            }
            foreach ($template_metas as $key => $value) {
                update_post_meta( $template_id, '_azp_'.$key,  $value  );
            }
			$json['success'] = true;
			do_action( 'azp_template_save_after', $template_id );
		}
		wp_send_json( $json );
	}
	public static function template_get(){
		$json = array(
			'success'	=> false,
			'POST'		=> $_POST,
            'templates'     => array()
		);

		$templates = get_posts(array(
            'fields'                               => 'ids',
			'post_type'                            => 'azp_template',
			'posts_per_page'		               => -1,
			'meta_key'				               => '_azp_builder',
			'meta_value'				           => $_POST['builder'],

            'orderby'                              => 'title',
            'order'                                => 'ASC',

		));

        if(!empty($templates)){
            foreach ($templates as $tmpid) {
                # code...
                $json['templates'][] = array(
                    'ID'                => $tmpid,
                    'title'             => get_the_title( $tmpid ),
                    'shortcode'         => get_post_field( 'post_content', $tmpid),
                    'thumbnail'         => get_the_post_thumbnail( $tmpid, 'post-thumbnail' ),
                );
            }
        }



		$json['success'] = true;
		

		wp_send_json( $json );
		
	}
	public static function template_delete(){
		$json = array(
			'success'	=> false,
			'POST'		=> $_POST,
		);
        if( isset($_POST['id']) && wp_delete_post($_POST['id']) ){
            $json['success'] = true;
        }
		wp_send_json( $json );
		
	}
}

Azp_Template_Manager::init();