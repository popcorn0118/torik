<?php
/* add_ons_php */
if(!isset($photos)) $photos = array();
if(!isset($azp_attrs)) $azp_attrs = array();
$images_size = 'full';
if( !empty($azp_attrs['images_size']) ) $images_size = $azp_attrs['images_size'];
?>
<section class="listing-hero-section hidden-section" data-scrollax-parent="true" id="lhead_sec">
	<div class="bg-parallax-wrap">
		<?php 
		if(!empty($photos)){ ?>
		<!--ms-container-->
        <div class="slideshow-container" data-scrollax="properties: { translateY: '300px' }" >
            <div class="swiper-container">
                <div class="swiper-wrapper">
					<?php
		            foreach ($photos as $id ) {
		            	$image = get_post($id);
                    	if( !$image ) continue;
		            ?>
		            	<!--ms_item-->
	                    <div class="swiper-slide">
	                        <div class="ms-item_fs fl-wrap full-height">
	                            <div class="bg" data-bg="<?php echo wp_get_attachment_image_url( $id, $images_size );?>"></div>
	                            <div class="overlay"></div>
	                        </div>
	                    </div>
	                    <!--ms_item end-->
		            <?php
		            }
		        	?>
                </div>
            </div>
        </div>
        <!--ms-container end-->
		<?php 
		} ?>
        <div class="overlay"></div>
    </div>
    <div class="slide-progress-wrap">
        <div class="slide-progress"></div>
    </div>
    <div class="container">
        <?php townhub_addons_get_template_part( 'template-parts/single/head_infos', '', array('azp_attrs'=>$azp_attrs) ); ?>
    </div>
</section>