<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $width = $images_to_show = $title = $filed_rate = ''; 

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
    'azp_element',
    'azp_filter_rating',
    'azp-element-' . $azp_mID,
    'filter-gid-item', 
    'filter-gid-wid-' . $width,
    $el_class,
);
// $animation_data = self::buildAnimation($azp_attrs);
// $classes[] = $animation_data['trigger'];
// $classes[] = self::buildTypography($azp_attrs);//will return custom class for the element without dot
// $azplgallerystyle = self::buildStyle($azp_attrs);

$classes = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $classes ) ) ); 

if($el_id!=''){
    $el_id = 'id="'.$el_id.'"';
}
if (!empty($filed_rate) && $filed_rate != '') {
    $filed_rate = json_decode(urldecode($filed_rate) , true) ;
    $rating_base = (int)townhub_addons_get_option('rating_base'); 
    if(empty($rating_base)) $rating_base = 5;
?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>>
    <!-- listing-filter-rating -->
    <div class="listing-filter-rating">
        <h4 class="field-head"><?php echo $title;?></h4>
        <div class="search-opt-container fl-wrap">
            <!-- Checkboxes -->
            <ul class="fl-wrap filter-ratings">
                <?php foreach ($filed_rate as $key => $rate) { ?>
                        <li class="five-star-rating">
                            <div class="star-rating-item_wrap">
                                <input id="check-aa<?php echo $key ?>" type="radio" name="rating" value="<?php echo $rate['star'] ?>">
                                <label for="check-aa<?php echo $key; ?>"><span class="listing-rating card-popup-raining" data-rating="<?php echo $rate['star']; ?>" data-stars="<?php echo $rating_base; ?>"><span><?php echo $rate['label']; ?></span></span></label>
                            </div>
                        </li>
                <?php   
                    }

                ?>  
            </ul>
            <!-- Checkboxes end -->
        </div>
    </div>
    <!-- listing-filter-rating end--> 
</div>
<?php } ?>