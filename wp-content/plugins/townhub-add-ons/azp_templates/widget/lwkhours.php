<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $hide_widget_on = $title = $hide_timezone = '';

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'lwkhours',
    'azp-element-' . $azp_mID,
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
if( get_post_meta( get_the_ID(), ESB_META_PREFIX.'hide_wkhours', true ) == 'yes' ) return;
$working_hours = Esb_Class_Listing_CPT::parse_wkhours(get_the_ID());
// var_dump($working_hours);
if( !empty($working_hours) && ( $hide_widget_on_check = townhub_addons_is_hide_on_plans($hide_widget_on) ) !== 'true') :
?>
<div class="<?php echo $classes; ?> authplan-hide-<?php echo $hide_widget_on_check;?>" <?php echo $el_id;?>>
    <div class="for-hide-on-author"></div>

    <!--box-widget-item -->
    <div class="box-widget-item fl-wrap block_box">
    	<?php if($title != ''): ?>
        <div class="box-widget-item-header">
            <h3><?php echo $title; ?></h3>
        </div>
    	<?php endif; ?>
        <div class="box-widget opening-hours fl-wrap">
            <div class="box-widget-content">
            	<span class="current-status"><i class="fal fa-clock"></i> <?php echo $working_hours['statusText'];?> <?php if($hide_timezone != 'yes'): ?><span class="listing-timezone"><?php echo $working_hours['timezone'];?></span><?php endif; ?></span>
                <?php 
	            $working_days_hours = $working_hours['days_hours'];
	            if( count($working_days_hours) ) :
	            ?>
	            <ul class="no-list-style">
	            	<?php
		            foreach ($working_days_hours as $day => $hours) { 
		            	// if($hours === 'Day Off') continue;
		            	$licls = implode('_', (array)$hours);
		            	$licls = sanitize_title_with_dashes( $licls );
		                ?>
		                <li class="wkhour-item wkhour-item-<?php echo esc_attr( $day );?> <?php echo esc_attr( $licls );?>">
		                	<span class="opening-hours-day"><?php echo $day;?></span>
		                	<span class="opening-hours-time">
		                		<?php
				                foreach ($hours as $hr) {
				                    echo $hr;
				                } ?>
				            </span>
				        </li>
		            <?php
		            } // end foreach
		            ?>
                </ul>
                <?php 
	            endif; // end if count($working_days_hours)
	            ?>
            </div>
        </div>
    </div>
    <!--box-widget-item end --> 

</div>
<?php endif;

