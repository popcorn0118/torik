<?php
/* add_ons_php */

get_header(  ); 
$css_classes = array(
    'listings-grid-wrap clearfix',
    townhub_addons_get_option('columns_grid').'-cols',
    'template-full-map'
);

$css_class = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $css_classes ) ) );

?>
<div class="<?php echo esc_attr( $css_class );?>">
    
    <?php townhub_get_template_part( 'template-parts/breadcrumbs', '', array( 'is_top'=>true) ); ?>
    <!-- Map -->
    <div class="map-container listings-has-map fw-map big_map hid-mob-map top-pos-map">
        <?php townhub_addons_get_template_part('template-parts/filter/map'); ?>
    </div>
    <!-- Map end -->
    <div class="clearfix"></div>
    <section class="gray-bg small-padding no-top-padding-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <!-- list-main-wrap-header-->
                    <div class="list-main-wrap-headers fl-wrap no-vis-shadow no-bg-header">
                        <?php townhub_addons_get_template_part( 'template-parts/filter/head' ); ?>                    
                    </div>
                    <!-- list-main-wrap-header end-->  


                    <div class="fl-wrap full-map-filter-inner">

                        <div class="clearfix"></div>

                        <div class=" fl-wrap  lws_mobile  tabs-act tabs-wrapper inline-lsiw">
                            <div class="filter-sidebar-header fl-wrap" id="filters-column">
                                <?php townhub_addons_get_template_part('template-parts/filter/tabs'); ?>
                            </div>
                            <div class="scrl-content filter-sidebar fs-viscon">
                                <!--tabs -->                       
                                <div class="tabs-container fl-wrap">
                                    <!--tab -->
                                    <div class="tab tab-from-tab">
                                        <div id="filters-search" class="tab-content first-tab">
                                            <?php townhub_addons_get_template_part('template-parts/filter/form'); ?>
                                        </div>
                                    </div>
                                    <!--tab end-->
                                    <?php if( townhub_addons_get_option('hide_cats_tab') != 'yes' ): ?>
                                    <!--tab --> 
                                    <div class="tab tab-cats-tab">
                                        <div id="category-search" class="tab-content">
                                            <?php townhub_addons_get_template_part('template-parts/filter/categories'); ?>
                                        </div>
                                    </div>
                                    <!--tab end-->
                                    <?php endif; ?>
                                </div>
                                <!--tabs end-->                         
                            </div>
                        </div>
                                             
                                

                        <!-- list-main-wrap-->
                        <div class="list-main-wrap fl-wrap card-listing listings-full-map">
                            
                            <?php 
                            if( is_singular('page') ) // custom template for page
                                townhub_addons_get_template_part('templates/loop','custom'); 
                            else
                                townhub_addons_get_template_part('templates/loop'); 
                            ?>
                            
                        </div><!-- list-main-wrap end-->

                    </div><!-- full-map-filter-inner end -->
                </div><!-- col-m-12 end -->
            </div><!-- row end -->
        </div><!-- container end -->
    </section>


    
</div>
<div class="limit-box fl-wrap"></div>
<?php

get_footer(  );