<?php
/* add_ons_php */

get_header(  ); 
$css_classes = array(
    'listings-grid-wrap clearfix',
    townhub_addons_get_option('columns_grid').'-cols',
    'template-column-map-filter'
);

$css_class = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $css_classes ) ) );

?>
<div class="<?php echo esc_attr( $css_class );?>">
    <!-- Map -->
    <div class="map-container listings-has-map column-map right-pos-map fix-map hid-mob-map no-fix-scroll-map">
        <?php townhub_addons_get_template_part('template-parts/filter/map'); ?>
    </div>

    <!-- Map end -->
    <!-- hidden-search-column-->
    <div class="hidden-search-column">
        
        <div class="hidden-search-column-container fl-wrap full-height tabs-act tabs-wrapper" id="lisfw">
            <div class="filter-sidebar-header fl-wrap">
                <?php townhub_addons_get_template_part('template-parts/filter/tabs'); ?>
            </div>
            <div class="scrl-content-fix filter-sidebar">
                <!--tabs -->                       
                <div class="tabs-container fl-wrap">
                    <!--tab -->
                    <div class="tab tab-from-tab">
                        <div id="filters-search" class="tab-content  first-tab listsearch-inputs-sides">
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

        <div class="close_sbfilters"><i class="fal fa-long-arrow-right"></i></div>
    </div>
    <!-- hidden-search-column end-->                    
    <div class="col-list-wrap anim_clw show_toggle_filter">
        <?php townhub_addons_get_template_part( 'template-parts/filter/head' ,'', array('is_fixed'=>false, 'side_filter'=>false) ); ?>
        <!-- list-main-wrap-->
        <div class="list-main-wrap fl-wrap card-listing">
            
            <div class="container"> 
                <div class="row">
                    
                    <div class="col-md-12">
                        <?php 
                        if( is_singular('page') ) // custom template for page
                            townhub_addons_get_template_part('templates/loop','custom'); 
                        else
                            townhub_addons_get_template_part('templates/loop'); 
                        ?>
                    </div><!-- end col-md-12 -->

                </div><!-- end row -->
            </div><!-- end container -->
            
        </div><!-- list-main-wrap end-->
        
    </div>
    
</div>
<div class="limit-box fl-wrap"></div>
<?php

get_footer(  );