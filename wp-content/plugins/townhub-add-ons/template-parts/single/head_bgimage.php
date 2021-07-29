<?php
/* add_ons_php */
if(!isset($photos)) $photos = array();
if(!isset($azp_attrs)) $azp_attrs = array();
$images_size = 'full';
if( !empty($azp_attrs['images_size']) ) $images_size = $azp_attrs['images_size'];
$bgimg = '';
if(!empty($photos)){
    $bgimg = townhub_addons_get_attachment_thumb_link( reset($photos), $images_size );
} 
if( empty($bgimg) ){
    $default_thumbnail = townhub_addons_get_option('default_thumbnail');
    if( $default_thumbnail && !empty($default_thumbnail['id']) ){
        $bgimg = townhub_addons_get_attachment_thumb_link( $default_thumbnail['id'], $images_size );
    }
}
?>
<section class="listing-hero-section hidden-section" data-scrollax-parent="true" id="lhead_sec">
    <div class="bg-parallax-wrap">
        <div class="bg par-elem "  data-bg="<?php echo esc_url( $bgimg ); ?>" data-scrollax="properties: { translateY: '30%' }"></div>
        <div class="overlay"></div>
    </div>
    <div class="container">

        <?php townhub_addons_get_template_part( 'template-parts/single/head_infos', '', array('azp_attrs'=>$azp_attrs) ); ?>
        
    </div>

    <!-- <div class="resp-video lhead-iframe-wrap">
		<iframe src="https://plentyofpaws.ca/virtualtours/MontgomeryVetVT.html" frameborder="0"></iframe>
	</div> -->
    
</section>