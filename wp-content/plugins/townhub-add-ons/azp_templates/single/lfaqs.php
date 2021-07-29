<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $title = $sec_id = $cols = $hide_widget_on = $expand_first_item = ''; 

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
    'azp_element',
	'lfaqs',
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
$lFAQs = get_post_meta( get_the_ID(), ESB_META_PREFIX.'lfaqs', true );
if ( is_array( $lFAQs) && !empty($lFAQs) ) {
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
        <div class="lsingle-block-content no-padding">

            <div class="accordion accordion-wrap mar-top">
                <?php
                $count = 0;
                foreach ($lFAQs as $lfaq) { ?>
                    <a class="toggle<?php if( $count == 0 && $expand_first_item == 'yes' ) echo ' act-accordion';?>" href="#"> <?php echo esc_html($lfaq['title']); ?><span></span></a>
                    <div class="accordion-inner<?php if( $count == 0 && $expand_first_item == 'yes' ) echo ' visible';?>">
                        <?php echo wp_kses_post( $lfaq['content'] ); ?>
                        <?php //echo townhub_addons_nofollow($lfaq['content']); ?>
                    </div>
                <?php
                    $count++;
                }
                ?>
            </div>

        </div>
    </div>
    <!-- lsingle-block-box end -->  
</div>
<?php 
    endif;// check hide on plans
}
