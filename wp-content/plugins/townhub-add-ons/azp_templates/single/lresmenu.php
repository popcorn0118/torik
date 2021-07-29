<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $cols = $space = $title = $hide_widget_on = $all_item_last = ''; 

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
    'azp_element',
    'lresmenu',
    // 'list-single-main-item fl-wrap', 
    'azp-element-' . $azp_mID,
    $el_class,
);

$classes = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $classes ) ) );  

if($el_id!=''){
    $el_id = 'id="'.$el_id.'"';
}
if(( $hide_widget_on_check = townhub_addons_is_hide_on_plans($hide_widget_on) ) !== 'true') :
$css_classes = array(
    'cthiso-items cthiso-flex',
    'cthiso-'.$space.'-pad',
    'cthiso-'.$cols.'-cols',
);

$css_class = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $css_classes ) ) );
$resmenus = get_post_meta( get_the_ID(), ESB_META_PREFIX.'resmenus', true );
$menu_pdf = get_post_meta( get_the_ID(), ESB_META_PREFIX.'menu_pdf', true );
// var_dump($resmenus);
if ( !empty($resmenus) || $menu_pdf != '' ) {
?>
<div class="<?php echo $classes;?> authplan-hide-<?php echo $hide_widget_on_check;?>" <?php echo $el_id;?>>
    <div class="for-hide-on-author"></div>
    <!-- lsingle-block-box --> 
    <div class="lsingle-block-box">
        <?php if($title != ''): ?>
        <div class="lsingle-block-title">
            <h3><?php echo $title; ?></h3>
        </div>
        <?php endif; ?>
        <div class="lsingle-block-content">
        <?php if( !empty($resmenus) ): ?>
            <?php 
            $cats = array();
            $child_items = '';
            foreach ((array)$resmenus as $key => $child) {
                $ccats = array();
                if( isset($child['cats']) && !empty($child['cats']) ){
                    $ccats = explode(",", $child['cats']);
                    $cats = array_merge($cats, $ccats);
                }
                $ccats = array_map( 'townhub_addons_escapse_class', $ccats);
                $photos = isset($child['photos']) ? $child['photos'] : '';
                $child_items .=     '<!--restmenu-item-->';
                $child_items .=     '<div class="cthiso-item restmenu-item '. implode(" ", $ccats) .'">';
                if( !empty($photos) && !is_array($photos) ){
                    $photos = explode(',', $photos);
                    $photos_gal = array();
                    foreach ($photos as $iid) {
                        $photos_gal[] = array('src'=> wp_get_attachment_url( $iid ));
                    }
                    $child_items .=     '<div class="restmenu-item-img dynamic-gal" data-dynamicPath=\''.json_encode($photos_gal).'\'> 
                                            '.wp_get_attachment_image( reset($photos), 'thumbnail', false, '' ).'
                                        </div>';
                }
                    
                    $child_items .=     '<div class="restmenu-item-det">
                                            <div class="restmenu-item-det-header fl-wrap">';
                    if( isset($child['url']) && !empty($child['url']) ){
                        $child_items .=             '<h4><a href="'.$child['url'].'">'.$child['name'].'</a></h4>';
                    }else{
                        $child_items .=             '<h4>'.$child['name'].'</h4>';
                    }
                    
                    if( $child['price'] !== '' ) 
                        $child_items .=             '<div class="restmenu-item-det-price">'.townhub_addons_get_price_formated( $child['price'] ).'</div>';
                    $child_items .=         '</div>
                                            <div class="restmenu-item-desc">'.wpautop( $child['desc'], true ).'</div>
                                        </div>';
                $child_items .=     '</div><!--restmenu-item end-->';
            }
            ?>
            <div class="cthiso-isotope-wrapper cthiso-resmenu">
            <?php 
            $cats = array_unique($cats);
            if(!empty($cats)):
            ?>
                <div class="cthiso-filters">
                    <?php 
                    if( $all_item_last != 'yes' ): ?>
                    <a href="#" class="cthiso-filter cthiso-filter-active" data-filter="*"><?php _e( 'All', 'townhub-add-ons' ); ?></a>
                    <?php endif; ?>
                    <?php
                    $ccount = 0;
                    foreach ($cats as $key => $cat) {
                        if( $all_item_last == 'yes' && $ccount == 0 ){
                            echo '<a href="#" class="cthiso-filter cthiso-filter-active" data-filter=".'. townhub_addons_escapse_class($cat) .'">'.$cat.'</a>';
                        }else{
                            echo '<a href="#" class="cthiso-filter" data-filter=".'. townhub_addons_escapse_class($cat) .'">'.$cat.'</a>';
                        }
                        $ccount++;
                    } ?>
                    <?php 
                    if( $all_item_last == 'yes' ): ?>
                    <a href="#" class="cthiso-filter" data-filter="*"><?php _ex( 'All', 'Restaurant menus', 'townhub-add-ons' ); ?></a>
                    <?php endif; ?>
                </div>
            <?php 
            endif; ?>
                <div class="<?php echo $css_class;?>">
                    <div class="cthiso-sizer"></div>
                    <?php echo $child_items; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if( !empty($menu_pdf) ): ?>
        <a href="<?php echo wp_get_attachment_url( $menu_pdf ); ?>" target="_blank" class="btn color2-bg"><?php _e( 'Download PDF', 'townhub-add-ons' ); ?><i class="fal fa-file-pdf"></i></a>
        <?php endif; ?>   
        </div>
    </div>
    <!-- lsingle-block-box end -->  
</div>
<?php 
    } 

endif;// check hide on plans
