<?php
/* add_ons_php */

class Esb_Class_Member_CPT extends Esb_Class_CPT {
    protected $name = 'member';
    public function register(){

        $labels = array( 
            'name' => __( 'Members', 'townhub-add-ons' ),
            'singular_name' => __( 'Member', 'townhub-add-ons' ), 
            'add_new' => __( 'Add New Member', 'townhub-add-ons' ),
            'add_new_item' => __( 'Add New Member', 'townhub-add-ons' ),
            'edit_item' => __( 'Edit Member', 'townhub-add-ons' ),
            'new_item' => __( 'New Member', 'townhub-add-ons' ),
            'view_item' => __( 'View Member', 'townhub-add-ons' ),
            'search_items' => __( 'Search Members', 'townhub-add-ons' ),
            'not_found' => __( 'No Members found', 'townhub-add-ons' ),
            'not_found_in_trash' => __( 'No Members found in Trash', 'townhub-add-ons' ),
            'parent_item_colon' => __( 'Parent Member:', 'townhub-add-ons' ),
            'menu_name' => __( 'Members', 'townhub-add-ons' ),
        );

        $args = array( 
            'labels' => $labels,
            'hierarchical' => true,
            'description' => __( 'List Members', 'townhub-add-ons' ),
            'supports' => array( 'title', 'editor', 'thumbnail','excerpt'/*,'comments', 'post-formats'*/),
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 25,
            'menu_icon' =>  'dashicons-groups',
            'show_in_nav_menus' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'has_archive' => false,
            'query_var' => true,
            'can_export' => true,
            'rewrite' => array( 'slug' => __('member','townhub-add-ons') ),
            'capability_type' => 'post'
        );
        register_post_type( $this->name, $args );
    }

    protected function set_meta_boxes(){
        $this->meta_boxes = array(
            'socials'       => array(
                'title'                 => __( 'Job/Socials', 'townhub-add-ons' ),
                'context'               => 'normal', // normal - side - advanced
                'priority'              => 'high', // default - high - low
                'callback_args'         => array(),
            ),
        );
    }

    public function member_socials_callback($post, $args){
        wp_nonce_field( 'cth-cpt-fields', '_cth_cpt_nonce' );

        $socials = get_post_meta( $post->ID, ESB_META_PREFIX.'socials', true );
        ?>
        <h4><?php _e( 'Job Position', 'townhub-add-ons' ); ?></h4>
        <div class="custom-form">
            <input type="text" name="job_pos" value="<?php echo get_post_meta( $post->ID, ESB_META_PREFIX.'job_pos', true ); ?>">
        </div>
        <h4><?php _e( 'Socials', 'townhub-add-ons' ); ?></h4>
        <div class="custom-form">
            <div class="repeater-fields-wrap repeater-socials"  data-tmpl="tmpl-user-social">
                <div class="repeater-fields">
                <?php 
                if(!empty($socials)){
                    foreach ($socials as $key => $social) {
                        townhub_addons_get_template_part('templates-inner/social',false, array('index'=>$key,'name'=>$social['name'],'url'=>$social['url']));
                    }
                }
                ?>
                </div>
                <button class="btn addfield" type="button"><?php  esc_html_e( 'Add Social','townhub-add-ons' );?></button>
            </div>
        </div>
        <?php
    }

    public function save_post($post_id, $post, $update){
        if(!$this->can_save($post_id)) return;

        if(isset($_POST['job_pos'])){
            $new_val = sanitize_text_field( $_POST['job_pos'] ) ;
            $origin_val = get_post_meta( $post_id, ESB_META_PREFIX.'job_pos', true );
            if($new_val !== $origin_val){
                update_post_meta( $post_id, ESB_META_PREFIX.'job_pos', $new_val );
            }
        }
        if(isset($_POST['socials'])){
            update_post_meta( $post_id, ESB_META_PREFIX.'socials', $_POST['socials'] );
        }else{
            update_post_meta( $post_id, ESB_META_PREFIX.'socials', array() );
        }
    }

    protected function set_meta_columns(){
        $this->has_columns = true;
    }
    public function meta_columns_head($columns){
        $columns['_thumbnail'] = __( 'Thumbnail', 'townhub-add-ons' );
        $columns['_job'] = __( 'Job', 'townhub-add-ons' );
        $columns['_id'] = __( 'ID', 'townhub-add-ons' );
        return $columns;
    }
    public function meta_columns_content($column_name, $post_ID){
        if ($column_name == '_id') {
            echo $post_ID;
        }
        if ($column_name == '_job') {
            echo get_post_meta( $post_ID, ESB_META_PREFIX.'job_pos', true );
        }
        if ($column_name == '_thumbnail') {
            echo get_the_post_thumbnail( $post_ID, 'thumbnail', array('style'=>'width:100px;height:auto;') );
        }
    }

}

new Esb_Class_Member_CPT();