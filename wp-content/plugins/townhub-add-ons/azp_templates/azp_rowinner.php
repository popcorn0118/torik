<?php
/* add_ons_php */
//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $secclass = $fullwidth = $section_title = $title_align = $sec_width = $is_fullheight = $equal_height =  $use_parallax = $overlay_color = $parallax_image = $parallax_value = $column_gap = '';
$layout = '';
extract($azp_attrs);

if($use_parallax == '1'){
	if($background_image != ''){
		if(empty( $parallax_image )) $parallax_image = $background_image ;
		
	}
	$background_image = '';
	$background_color = '';
}

$classes = array(
	'azp_element',
	'azp-element-' . $azp_mID,
    'azp_row_inner_section',
    'azp_row_inner_section-'.$sec_width,
    'azp_row_section-'.$column_gap.'-gap',
    $el_class,
);
if($is_fullheight == '1') $classes[] = 'azp_row_section-fullscreenheight';
if($use_parallax == '1') $classes[] = 'azp_parallax-sec'; //azp_parallax_sec
// $animation_data = self::buildAnimation($azp_attrs);
// $classes[] = $animation_data['trigger'];
// $classes[] = self::buildTypography($azp_attrs);//will return custom class for the element without dot
$classes = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $classes ) ) );
// $rowstyle = self::buildStyle($azp_attrs);
 
if(!empty($el_id)){
	$el_id = 'id="'.$el_id.'"';
}

$azpattributes = array(
	$el_id 
);
?>
<div class="<?php echo $classes;?>" <?php echo implode( ' ', array_filter( $azpattributes ) ) ;?>>
	<?php if($use_parallax === '1'):
		$parallax_attrs = array();
		if($parallax_value !='') $parallax_attrs[] = 'data-top-bottom="transform: translateY('.$parallax_value.'px);" data-bottom-top="transform: translateY(-'.$parallax_value.'px);"';
		if($parallax_image != '') $parallax_attrs[] = 'data-bg="'.$parallax_image.'"';

	?>
	<div class="azp_parallax-inner">
        <div class="azp_parallax-bg" <?php echo implode( ' ', array_filter( $parallax_attrs ) ) ;?>></div>
        <div class="azp_overlay"<?php if($overlay_color!= '') echo ' style="background-color:'.$overlay_color.'"';?>></div>
    </div>
	<?php endif;?>

	<?php if($fullwidth === '1'):?>
	<div class="azp_container azp_container-fluid">
	<?php else:?>
	<div class="azp_container">
	<?php endif;?>
	<?php if($section_title != '' || $azp_content != ''):
		$title_col = 'azp_col-md-8';
		if($title_align == 'setcenter') $title_col .=' azp_col-md-offset-2';

	?>
		<div class="azp_row azp_row_title">

			<div class="<?php echo $title_col;?>">
				<div class="azp_title-head <?php echo $title_align;?>">
				<?php if($section_title != ''):?>
					<h2><?php echo $section_title;?></h2>
				<?php endif;?>
					<?php echo $azp_content;?>
				</div>
			</div>
		</div>
	<?php endif;?>
	<?php 
	$main_row_cls = 'azp_row azp_row-wrap';
	if($equal_height == '1') $main_row_cls .= ' azp_row_equal_cols'; 
	?>
		<div class="<?php echo $main_row_cls;?>">
		<?php if( isset($this->toStoreGlobalVar['columninneritems']) && count( $this->toStoreGlobalVar['columninneritems'] )): 
			foreach ( $this->toStoreGlobalVar['columninneritems'] as $key => $col) : ?>
			<?php
			$colID = '';
			if(!empty($col['el_id'])){
				$colID .= 'id="'.$col['el_id'].'"';
			}
			$colClass = 'class="'.$col['el_class'].'"';
			
			?>
			<div <?php echo $colID . ' ' .$colClass;?>>
				<?php if(!empty($col['wrapclass'])){
					echo '<div class="'.$col['wrapclass'].'">'.$col['content'].'</div>';
				}else{
					echo $col['content'];
				}?>
			</div>
		<?php endforeach; endif;?>
		</div>
	</div>
	
</div>
<?php
$this->toStoreGlobalVar['columninneritems']  = array(); 
