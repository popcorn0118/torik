<?php
/* add_ons_php */
if(!isset($photos)) $photos = array();
if(!isset($azp_attrs)) $azp_attrs = array();
$images_size = 'full';
if( !empty($azp_attrs['images_size']) ) $images_size = $azp_attrs['images_size'];
$slperview = 'auto';
if( !empty($azp_attrs['slperview']) ) $slperview = $azp_attrs['slperview'];

?>
<!-- listing-carousel-wrap -->
<div class="listing-carousel-wrap fl-wrap listing-carousel-slides-<?php echo esc_attr( $slperview ); ?>" id="lhead_sec">
	<?php 
	if(!empty($photos)){ ?>
    <div class="listing-carousel fl-wrap full-height lightgallery">
        <div class="swiper-container" data-slides="<?php echo esc_attr( $slperview ); ?>">
            <div class="swiper-wrapper">
            	<?php
	            foreach ($photos as $id ) {
                    $image = get_post($id);
                    if( !$image ) continue;
                    $galCaptionID = uniqid('gal-cap');
	            ?>
	            	<!-- swiper-slide-->
	                <div class="swiper-slide hov_zoom">
	                    <?php echo wp_get_attachment_image( $id, $images_size ); ?>
	                    <a href="<?php echo wp_get_attachment_url( $id );?>" class="box-media-zoom popup-image" data-sub-html="#<?php echo esc_attr( $galCaptionID );?>">
	                    	<i class="fal fa-search"></i>
	                    	<?php 
                            
                            $image_title = $image->post_title;
                            $image_caption = $image->post_excerpt;
                            ?>
                            <div id="<?php echo esc_attr( $galCaptionID );?>" class="listing-caption dis-none">
                                <h3><?php echo esc_html( $image_title ); ?></h3>
                                <?php echo $image_caption; ?>
                            </div>
	                    </a>
	                </div>
	                <!-- swiper-slide end-->
	            <?php
	            }
	        	?>
            </div>
        </div>
    </div>
    <div class="listing-carousel_pagination">
        <div class="listing-carousel_pagination-wrap"></div>
    </div>
    <div class="listing-carousel-button listing-carousel-button-next"><i class="fas fa-caret-right"></i></div>
    <div class="listing-carousel-button listing-carousel-button-prev"><i class="fas fa-caret-left"></i></div>
	<?php 
	} ?>
</div>
<!-- listing-carousel-wrap end--> 