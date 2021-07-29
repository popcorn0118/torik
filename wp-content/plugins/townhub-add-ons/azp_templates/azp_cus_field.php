<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $f_title = $cus_field = $f_class = $fiels_class = $f_wid = $f_type = $use_sec_style = '';

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
	'azp-element-' . $azp_mID,
    'azp_cus_field',
    'list-single-main-items fl-wrap',
    'lcus-sec-style-' . $use_sec_style,
    $el_class,
);
$classes = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $classes ) ) );  

if($el_id!=''){
    $el_id = 'id="'.$el_id.'"';
}

// if( townhub_addons_is_hide_on_plans('logout_user') ) === 'true' ) return;



$cus_field = json_decode(urldecode($cus_field) , true) ;
?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>>
	
	<?php 
    if( $use_sec_style == 'yes' ): ?>
    <div class="lsingle-block-box">
        <?php if( !empty($field_title) ): ?>
        <div class="lsingle-block-title">
            <h3><?php echo esc_html( $field_title ); ?></h3>
        </div>
        <?php endif; ?>
        <div class="lsingle-block-content">
    <?php else: ?>
	    <?php if($field_title != ''): ?>
		<div class="cus-field-header list-single-main-item-title fl-wrap">
			<h3><?php echo $field_title;?></h3>
		</div>
		<?php endif; ?>
    <?php endif; ?>

		<div class="cus-field-body">
			<div class="cus-fields-wrap">
			<?php 
				foreach ($cus_field as $key => $field) { 
					switch($field['f_type']) {
						case 'gallery':
							$fiels_class = 'cus-field-items' .' '.$field['f_class'].' '. $field['f_wid'];
							break;
						default:
							$fiels_class = 'cus-field-item' .' '. $field['f_class'].' '. $field['f_wid'];
							break;
					}
					// cus-field-item
					$field_name = get_post_meta( get_the_ID(), ESB_META_PREFIX.$field['f_name'], true );

					// $field_name = explode(",",$field_name);
					if(is_array($field_name) && !empty($field_name)){?>
						<div class="<?php echo $fiels_class;?>">
							<div class="cus-field-title">
								<?php echo $field['f_title']; ?>
							</div>
							<div class="cus-field-content">
							<?php 
								switch($field['f_type']){
									case 'gallery':
										?>
											<div class="gallery-items big-pad">
												<div class="grid-sizer"></div>
											<?php
												foreach ($field_name as $image) {?>
													<div class="gallery-item item">
														<?php echo wp_get_attachment_image($image,'featured', false, array('class'=>'respimg'));?>
													</div>
											<?php } ?>
											</div>
										<?php 
										break;
									case 'image':
										foreach ($field_name as $image) {
										?>
											<div class="image-item">
												<?php echo wp_get_attachment_image($image,'featured', false, array('class'=>'respimg'));?>
											</div>
										<?php
											}
										break;
									case 'list':?>
										<ul>
											<?php
												foreach ($field_name as $key => $value) {
												echo '<li>'.$value.'</li>';
												}; 
											?>
										</ul>
										<?php
										break;
									case 'currency':
										foreach ($field_name as $value) {
												echo'<div class="cus-field-cont-item">'.townhub_addons_get_price_formated($value).'</div>';
										}
										break;
									default:
										foreach ($field_name as $value) {
												echo'<div class="cus-field-cont-item">'.$value.'</div>';
										}
										break;
								};
							?>
							</div>
						</div>
				<?php	
					}elseif(!empty($field_name)){?>
						<div class="<?php echo $fiels_class;?>">
							<div class="cus-field-title">
								<?php echo $field['f_title']; ?>
							</div>
							<div class="cus-field-content">
								<?php 
								if( $field['f_type'] == 'gallery' ): 
									$field_name = explode(",", $field_name); ?>
									<div class="gallery-items big-pad lightgallery">
										<div class="grid-sizer"></div>
										<?php
										foreach ($field_name as $image) {?>
											<div class="gallery-item item">
												<a href="<?php echo wp_get_attachment_url( $image );?>" class="gal-link popup-image">
													<?php echo wp_get_attachment_image($image,'featured', false, array('class'=>'respimg'));?>
												</a>
											</div>
										<?php 
										} ?>
									</div>
								<?php 
								elseif( $field['f_type'] == 'image' ): 
									$field_name = explode(",", $field_name);
									foreach ($field_name as $image) {
									?>
										<div class="image-item">
											<?php echo wp_get_attachment_image($image,'featured', false, array('class'=>'respimg'));?>
										</div>
									<?php
									}
								elseif( $field['f_type'] == 'currency' ): 
									echo townhub_addons_get_price_formated($field_name);
								else: ?>
								<?php echo $field_name;?>
								<?php endif; ?>
							</div>
						</div>
				<?php 	
					}		
				}
			?>
			</div>
		</div>
	<?php 
    if( $use_sec_style == 'yes' ): ?>
        </div>
    </div>
    <?php endif; ?>
</div>