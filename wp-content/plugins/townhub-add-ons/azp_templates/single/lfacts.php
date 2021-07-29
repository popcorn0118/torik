<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $title = $sec_id = $cols = $hide_widget_on = ''; 

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
    'azp_element',
	'lfacts',
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
if ( is_array( $facts) && !empty($facts)) {
    if(( $hide_widget_on_check = townhub_addons_is_hide_on_plans($hide_widget_on) ) !== 'true') :
?>
<div class="<?php echo $classes; ?> authplan-hide-<?php echo $hide_widget_on_check;?>" <?php echo $el_id;?>>
    <div class="for-hide-on-author"></div>
    <!-- lsingle-block-box --> 
    <div class="lsingle-block-box lsingle-block-full mb-0">
        <?php if($title != ''): ?>
        <div class="lsingle-block-title">
            <h3><?php echo $title; ?></h3>
        </div>
        <?php endif; ?>
        <div class="lsingle-block-content">
            <div class="lsingle-facts <?php echo $cols ?>-cols">
                <?php 
                foreach( $facts as $key => $fact): ?>
                <!-- inline-facts -->
                <div class="inline-facts-wrap gradient-bg flex-fact-wrap">
                    <div class="inline-facts">
                        <i class="<?php echo esc_attr($fact['icon']); ?>"></i>
                        <div class="milestone-counter">
                            <div class="stats animaper">
                                <div class="num" data-content="0" data-num="<?php echo esc_attr($fact['number']); ?>"><?php echo esc_attr($fact['number']); ?></div>
                            </div>
                        </div>
                        <h6><?php echo esc_html($fact['title']); ?></h6>
                    </div>
                    <?php 
                    if($key % 2 == 0): ?>
                    <div class="stat-wave">
                        <svg viewbox="0 0 100 25">
                            <path fill="#fff" d="M0 30 V12 Q30 17 55 12 T100 11 V30z" />
                        </svg>
                    </div>
                    <?php elseif($key % 3 == 0): ?>
                    <div class="stat-wave">
                        <svg viewbox="0 0 100 25">
                            <path fill="#fff" d="M0 30 V12 Q30 12 55 5 T100 11 V30z" />
                        </svg>
                    </div>
                    <?php else: ?>
                    <div class="stat-wave">
                        <svg viewbox="0 0 100 25">
                            <path fill="#fff" d="M0 30 V12 Q30 17 55 2 T100 11 V30z" />
                        </svg>
                    </div>
                    <?php endif; ?>
                </div>
                <!-- inline-facts end -->
            <?php
            endforeach; ?>
            </div>
        </div>
    </div>
    <!-- lsingle-block-box end -->  
</div>
<?php 
    endif;// check hide on plans
} 
