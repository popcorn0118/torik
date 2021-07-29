<?php
/* add_ons_php */

get_header(  );
$filter_wrap_cl = '';
switch (townhub_addons_get_option('map_pos')) {
    case 'left':
        $map_wrap_cl = 'listings-has-map column-map left-pos-map';
        $list_wrap_cl = 'right-list';
        $filter_wrap_cl = 'right-filter';
        break;
    case 'right':
        $map_wrap_cl = 'listings-has-map column-map right-pos-map';
        $list_wrap_cl = 'left-list';
        break;
    case 'top':
        $map_wrap_cl = 'listings-has-map fw-map top-post-map';
        $list_wrap_cl = 'fh-col-list-wrap left-list';
        break;
    default:
        $map_wrap_cl = 'listings-has-map column-map right-pos-map';
        $list_wrap_cl = 'fh-col-list-wrap left-list';
        break;
}

if(townhub_addons_get_option('filter_pos') == 'left_col'){
    $map_wrap_cl .= ' map-lcol-filter';
    $list_wrap_cl .= ' list-lcol-filter';
}

?>
<div class="listings-grid-wrap <?php echo townhub_addons_get_option('columns_grid') ?>-cols clearfix">
    <?php 
    if(townhub_addons_get_option('map_pos') != 'hide'): ?>
    <div class="map-container <?php echo esc_attr( $map_wrap_cl ); ?>">

        <div id="map-main" class="main-map-ele main-map-<?php echo esc_attr( townhub_addons_get_option('map_provider') );?>"></div>

        <ul class="mapnavigation">
            <li><a href="#" class="prevmap-nav"><?php esc_html_e( 'Prev', 'townhub-add-ons' ); ?></a></li>
            <li><a href="#" class="nextmap-nav"><?php esc_html_e( 'Next', 'townhub-add-ons' ); ?></a></li>
        </ul>
    </div>
    <!-- Map end -->  
    <?php endif; ?>
    <?php if(townhub_addons_get_option('filter_pos') == 'left_col'): ?>
    <div class="col-filter-wrap <?php echo esc_attr( $filter_wrap_cl ); ?>">
        <div class="container">
            
            <div class="fl-wrap listing-search-sidebar listsearch-options">
                <?php townhub_addons_get_template_part('templates/filter_form'); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!--col-list-wrap -->   
    <div class="col-list-wrap <?php echo esc_attr( $list_wrap_cl ); ?>">
        <?php if(townhub_addons_get_option('filter_pos') == 'top'): ?>
        <div class="listsearch-options fl-wrap" id="lisfw" >
            <div class="container">
                <div class="listsearch-header fl-wrap">
                    <?php 
                    if( isset($_GET['search_term']) && $_GET['search_term'] != '' ){
                        ?>
                        <h1 class="head-sec-title"><?php printf( esc_html__( 'Results for: %s', 'townhub-add-ons' ), '<span>' . esc_html($_GET['search_term']) . '</span>' ); ?></h1>
                        <?php
                    }else{
                        the_archive_title('<h1 class="head-sec-title">','</h1>');
                    }
                    ?>
                    
                    <div class="listing-view-layout">
                        <ul>
                            <li><a class="grid<?php if(townhub_addons_get_option('listings_grid_layout')=='grid') echo ' active';?>" href="#"><i class="fa fa-th-large"></i></a></li>
                            <li><a class="list<?php if(townhub_addons_get_option('listings_grid_layout')=='list') echo ' active';?>" href="#"><i class="fa fa-list-ul"></i></a></li>
                            <?php if(townhub_addons_get_option('map_pos') == 'left'||townhub_addons_get_option('map_pos') == 'right'): ?>
                            <li><a href="#" class="expand-listing-view"><i class="fa fa-expand"></i></a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <div class="listing-term-desc"></div>
                <?php townhub_addons_get_template_part('templates/filter_form'); ?>
            </div>

        </div>
        <?php elseif(townhub_addons_get_option('filter_pos') == 'left_col'): ?>
        <div class="listsearch-options fl-wrap" id="lisfw">
            <div class="container">
                <div class="listsearch-header fl-wrap">
                    <?php 
                    if( isset($_GET['search_term']) && $_GET['search_term'] != '' ){
                        ?>
                        <h1 class="head-sec-title"><?php printf( esc_html__( 'Results for: %s', 'townhub-add-ons' ), '<span>' . esc_html($_GET['search_term']) . '</span>' ); ?></h1>
                        <?php
                    }else{
                        the_archive_title('<h1 class="head-sec-title">','</h1>');
                    }
                    ?>
                    
                    <div class="listing-view-layout">
                        <ul>
                            <li><a class="grid<?php if(townhub_addons_get_option('listings_grid_layout')=='grid') echo ' active';?>" href="#"><i class="fa fa-th-large"></i></a></li>
                            <li><a class="list<?php if(townhub_addons_get_option('listings_grid_layout')=='list') echo ' active';?>" href="#"><i class="fa fa-list-ul"></i></a></li>
                            <?php if(townhub_addons_get_option('map_pos') == 'left'||townhub_addons_get_option('map_pos') == 'right'): ?>
                            <li><a href="#" class="expand-listing-view"><i class="fa fa-expand"></i></a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <div class="listing-term-desc"></div>
            </div>
            
        </div>
        <?php endif; ?>
        <!-- list-main-wrap-->
        <div class="list-main-wrap fl-wrap card-listing">
            <a class="custom-scroll-link back-to-filters btf-l" href="#lisfw"><?php _e( '<i class="fa fa-angle-double-up"></i><span>Back to Filters</span>', 'townhub-add-ons' ); ?></a> 
            <div class="container"> 
                <div class="row">
                    <?php 
                    if(townhub_addons_get_option('filter_pos') == 'left'):?>
                    <div class="col-md-4">
                        <div class="fl-wrap listing-search-sidebar listsearch-options">
                            <?php townhub_addons_get_template_part('templates/filter_form'); ?>
                        </div>
                    </div>
                    <?php endif;?>
                    <?php 
                    if(townhub_addons_get_option('filter_pos') == 'left'||townhub_addons_get_option('filter_pos') == 'right'):?>
                    <div class="col-md-8">
                        <div class="listsearch-header fl-wrap listsearch-header-sidebar">
                            <?php 
                            if( isset($_GET['search_term']) && $_GET['search_term'] != '' ){
                                ?>
                                <h1 class="head-sec-title"><?php printf( esc_html__( 'Results for: %s', 'townhub-add-ons' ), '<span>' . esc_html($_GET['search_term']) . '</span>' ); ?></h1>
                                <?php
                            }else{
                                the_archive_title('<h1 class="head-sec-title">','</h1>');
                            }
                            ?>
                            
                            <div class="listing-view-layout">
                                <ul>
                                    <li><a class="grid<?php if(townhub_addons_get_option('listings_grid_layout')=='grid') echo ' active';?>" href="#"><i class="fa fa-th-large"></i></a></li>
                                    <li><a class="list<?php if(townhub_addons_get_option('listings_grid_layout')=='list') echo ' active';?>" href="#"><i class="fa fa-list-ul"></i></a></li>
                                    <?php if(townhub_addons_get_option('map_pos') == 'left'||townhub_addons_get_option('map_pos') == 'right'): ?>
                                    <li><a href="#" class="expand-listing-view"><i class="fa fa-expand"></i></a></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                        <div class="listing-term-desc"></div>
                    <?php else : ?>
                    <div class="col-md-12">
                    <?php endif;?>
                        <?php 

                        echo townhub_addons_get_option('search_infor_before');

                        townhub_addons_get_template_part('templates/loop'); 

                        echo townhub_addons_get_option('search_infor_after');

                        ?>
                    </div>
                    <!-- end col-md-8 -->
                    <?php 
                    if(townhub_addons_get_option('filter_pos') == 'right'):?>
                    <div class="col-md-4">
                        <div class="fl-wrap listing-search-sidebar listsearch-options">
                            <?php townhub_addons_get_template_part('templates/filter_form'); ?>
                        </div>
                    </div>
                    <?php endif;?>

                </div> 
                <!-- end row -->
            </div>
            <!-- end container -->
            
        </div>
        <!-- list-main-wrap end-->
    </div>
    <!--col-list-wrap -->  
</div>
<!-- listings-grid-wrap -->  
<div class="limit-box fl-wrap"></div>

<?php // townhub_addons_get_template_part('templates/tmpls'); ?>
<?php

get_footer(  );
