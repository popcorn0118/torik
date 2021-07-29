<?php
/* add_ons_php */

get_header(  ); 
$css_classes = array(
    'listings-grid-wrap clearfix',
    townhub_addons_get_option('columns_grid').'-cols',
    'template-no-map-filter'
);

$css_class = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $css_classes ) ) );

if( is_singular('page') ) // custom template for page
    get_template_part( 'template-parts/post', 'head' ); 
else{
    townhub_addons_get_template_part( 'templates/page/archive-head' );
}
?>
<div class="<?php echo esc_attr( $css_class );?>">

    <section class="gray-bg small-padding no-top-padding-sec" id="main-sec">
        <div class="container">
            <?php townhub_get_template_part( 'template-parts/breadcrumbs' ); ?>
            <div class="fl-wrap full-map-filter-inner">
                <div class="listings_tax_desc listings-tax-filter-no-map"><?php echo term_description( ); ?></div>
                <div class="container dis-flex mob-search-nav-wrap">
                    <div class="mob-nav-content-btn mncb_half color2-bg shsb_btn_x shsb_btn_act_x show-list-wrap-search ntm fl-wrap"><?php _e( '<i class="fal fa-filter"></i>Filters', 'townhub-add-ons' ); ?></div>
                    <div class="mob-nav-content-btn mncb_half color2-bg schm ntm fl-wrap"><?php _e( '<i class="fal fa-map-marker-alt"></i>View on map', 'townhub-add-ons' ); ?></div>
                </div>
                
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-4">
                        <div class=" fl-wrap  lws_mobile  tabs-act block_box tabs-wrapper" id="lisfw">
                            <div class="filter-sidebar-header fl-wrap" id="filters-column">
                                <?php townhub_addons_get_template_part('template-parts/filter/tabs'); ?>
                            </div>
                            <div class="scrl-content filter-sidebar fs-viscon">
                                <!--tabs -->                       
                                <div class="tabs-container fl-wrap">
                                    <!--tab -->
                                    <div class="tab tab-from-tab">
                                        <div id="filters-search" class="tab-content first-tab listsearch-inputs-sides">
                                            <?php townhub_addons_get_template_part('template-parts/filter/form'); ?>
                                        </div>
                                    </div>
                                    <!--tab end-->
                                    <?php if( townhub_addons_get_option('hide_cats_tab') != 'yes' ): ?>
                                    <!--tab --> 
                                    <div class="tab tab-cats-tab">
                                        <div id="category-search" class="tab-content">
                                            <?php townhub_addons_get_template_part('template-parts/filter/categories', '', array('is_slider'=>false)); ?>
                                        </div>
                                    </div>
                                    <!--tab end-->
                                    <?php endif; ?>
                                </div>
                                <!--tabs end-->                         
                            </div>
                        </div>
                        <a class="back-tofilters color2-bg custom-scroll-link fl-wrap" href="#filters-column"><?php _e( 'Back to filters <i class="fas fa-caret-up"></i>', 'townhub-add-ons' ); ?></a>
                    </div>
                    <div class="col-md-8">
                        <!-- list-main-wrap-header-->
                        <div class="list-main-wrap-headers fl-wrap block_box no-vis-shadow list-main-head-normal">
                            
                            <?php townhub_addons_get_template_part( 'template-parts/filter/head' ,'', array('is_fixed'=>false, 'side_filter'=>false, 'hide_tax_desc'=>true) ); ?>                    
                        </div>
                        <!-- list-main-wrap-header end-->                            
                        

                        <!-- list-main-wrap-->
                        <div class="list-main-wrap fl-wrap card-listing listings-full-map">
                            
                            <?php 
                            if( is_singular('page') ) // custom template for page
                                townhub_addons_get_template_part('templates/loop','custom'); 
                            else
                                townhub_addons_get_template_part('templates/loop'); 
                            ?>
                            
                        </div><!-- list-main-wrap end-->

                    </div>
                </div>
            </div>
        </div>
    </section>


    
</div>
<div class="limit-box fl-wrap"></div>
<?php

get_footer(  );