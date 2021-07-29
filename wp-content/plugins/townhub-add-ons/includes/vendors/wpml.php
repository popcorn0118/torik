<?php 
class CTH_Class_WPML{

    public static function init(){
        add_action( 'icl_make_duplicate', array( __CLASS__, 'make_duplicate' ), 10, 4 ); 
        // add_action( 'icl_copy_from_original', array( __CLASS__, 'copy_from_original' ), 10, 1 ); 
    }
    public static function make_duplicate($master_post_id, $lang, $post_array, $id){
    	$master_wkhours = Esb_Class_Listing_CPT::wkhours($master_post_id, true);
    	Esb_Class_Listing_CPT::update_working_hours($id, $master_wkhours);
    }
    public static function copy_from_original($master_post_id ){

    }
}

CTH_Class_WPML::init();








