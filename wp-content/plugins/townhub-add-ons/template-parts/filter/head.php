<?php
/* add_ons_php */
// $title = post_type_archive_title('', false);
$title = '';
if( is_tax('listing_cat') || is_tax('listing_feature') || is_tax('listing_location') || is_tax('listing_tag') ) 
    $title =  single_term_title( '', false );
if( isset($_GET['search_term']) && $_GET['search_term'] != '' ) 
    $title = stripslashes(esc_html($_GET['search_term']));

if( empty($title) ){
    if( !empty($_GET['lcats']) ){
        if( is_array($_GET['lcats']) ){
            $cat_id = reset($_GET['lcats']);
        }else{
            $cat_id = $_GET['lcats'];
        }
        if( !empty($cat_id) ){
            $cat_term = get_term( (int)$cat_id, 'listing_cat' );
            if ( ! empty( $cat_term ) && ! is_wp_error( $cat_term ) ){
                $title = $cat_term->name;
            }
        }
    }
}

if( empty($title) ) $title = _x( 'Listings', 'Filter title', 'townhub-add-ons' );

$title = apply_filters( 'cth_search_results_text', $title );

if(!isset($is_fixed)) $is_fixed = true;
if(!isset($side_filter)) $side_filter = true;
$dynamiccls = 'shsb_btn shsb_btn_act show-list-wrap-search_x';
if($side_filter) $dynamiccls = 'shsb_btn_x shsb_btn_act_x show-list-wrap-search';
if(!isset($hide_tax_desc)) $hide_tax_desc = false;

?>
            <!-- list-main-wrap-header-->
            <div class="list-main-wrap-header fl-wrap<?php echo $is_fixed == true ? ' fixed-listing-header': ' anim_clw'; ?>">
                <div class="container">
                    <div class="list-filter-head-wrapper flex-items-center">
                        <!-- list-main-wrap-title-->
                        <div class="list-main-wrap-title">
                            <h2 id="lsearch-results-title"><?php printf( esc_html__( 'Results for: %s', 'townhub-add-ons' ), '<span>' . $title . '</span>' ); ?></h2>
                        </div>
                        <!-- list-main-wrap-title end-->
                        <!-- list-main-wrap-opt-->
                        <div class="list-main-wrap-opt flex-items-center">
                            <?php if( townhub_addons_get_option('filter_hide_sortby') != 'yes' ) townhub_addons_get_template_part('template-parts/filter/sortby'); ?>
                            <?php townhub_addons_get_template_part('template-parts/filter/grid-list'); ?>
                            <div class="show-hidden-sb shsb_btn shsb_btn_act"><?php _e( '<i class="fal fa-sliders-h"></i> <span>Show Filters</span>', 'townhub-add-ons' ); ?></div>              
                        </div>
                        <!-- list-main-wrap-opt end-->  
                    </div>
                            
                    
                </div>
                <a class="custom-scroll-link back-to-filters clbtg" href="#lisfw"><i class="fal fa-search"></i></a>
            </div>
            <!-- list-main-wrap-header end-->  
            <div class="clearfix"></div>
            <?php if( false == $hide_tax_desc ): ?>
            <div class="listings_tax_desc listings-tax-column"><?php echo term_description( ); ?></div>
            <?php endif; ?>
            <div class="container dis-flex mob-search-nav-wrap">
                <div class="mob-nav-content-btn mncb_half color2-bg <?php echo esc_attr( $dynamiccls ); ?> fl-wrap"><?php _e( '<i class="fal fa-filter"></i>Filters', 'townhub-add-ons' ); ?></div>
                <div class="mob-nav-content-btn mncb_half color2-bg schm  fl-wrap"><?php _e( '<i class="fal fa-map-marker-alt"></i>View on map', 'townhub-add-ons' ); ?></div>
            </div>
            <div class="clearfix"></div>