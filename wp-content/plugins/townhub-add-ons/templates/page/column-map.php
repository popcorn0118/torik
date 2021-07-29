<?php
/* add_ons_php */

get_header(  ); 
$css_classes = array(
    'listings-grid-wrap clearfix',
    townhub_addons_get_option('columns_grid').'-cols',
    'template-column-map'
);

$css_class = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $css_classes ) ) );

?>
<div class="<?php echo esc_attr( $css_class );?>">
    <!-- Map -->
    <div class="map-container listings-has-map column-map right-pos-map fix-map hid-mob-map no-fix-scroll-map">
        <?php townhub_addons_get_template_part('template-parts/filter/map'); ?>
    </div>
    <!-- Map end -->
    <div class="col-list-wrap novis_to-top">
        <?php townhub_addons_get_template_part( 'template-parts/filter/head' ); ?>
        <?php townhub_addons_get_template_part( 'templates/filter_form' ); ?>
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