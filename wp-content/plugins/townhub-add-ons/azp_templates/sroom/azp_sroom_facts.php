<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $images_to_show = '';

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'azp_rfacts',
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
$facts = get_post_meta( get_the_ID(), ESB_META_PREFIX.'facts', true );
if ( !empty($facts) ) {
?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>>
    <div class="ajax-modal-list listing-single-facts lroom-facts fl-wrap">
    	<ul>
        	<?php 
        	 	foreach( $facts as $key => $fact): ?>
        	 		<li>
	                    <i class="<?php echo esc_attr($fact['icon']); ?>"></i>
	                    <h5><span> <?php echo esc_html($fact['number']); ?></span> <?php echo esc_html($fact['title']); ?></h5>
	                </li>
				<?php
                endforeach;
        	?>
        </ul>
    </div>
</div>
<?php } ?> 