<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $dates_to_show = $show_end = $title = '';

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
    'azp_element',
    'azp_widget_event-dates',
    'azp-element-' . $azp_mID,
    $el_class,
);
$classes = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $classes ) ) ); 

if($el_id!=''){
    $el_id = 'id="'.$el_id.'"';
}
if(( $hide_widget_on_check = townhub_addons_is_hide_on_plans($hide_widget_on) ) !== 'true') :

$event_dates = townhub_addons_get_event_dates( get_the_ID() );

if(empty($event_dates)) 
    return ;
?>
<div class="<?php echo $classes; ?> authplan-hide-<?php echo $hide_widget_on_check;?>" <?php echo $el_id;?>>
    <div class="for-hide-on-author"></div>
    <div class="box-widget-item fl-wrap block_box" id="event-dates-widget">
        
        <?php if($title != ''): ?>
        <div class="box-widget-item-header">
            <h3><?php echo $title; ?></h3>
        </div>
        <?php endif; ?>
        <div class="box-widget">
            
            <div class="box-widget-content">
                
                <?php 
                $count = 1;
                ?>
                <ul class="event-dates-list">
                <?php
                foreach ($event_dates as $date) {
                    if($count <= $dates_to_show){
                        $start_date_str = date_i18n( get_option( 'date_format' ), strtotime( $date['start_date'] ) );
                        $end_date_str = date_i18n( get_option( 'date_format' ), strtotime( $date['end_date'] ) );

                        $start_time_str = date_i18n( get_option( 'time_format' ), strtotime( $date['start_date'] ) );
                        $end_time_str = date_i18n( get_option( 'time_format' ), strtotime( $date['end_date'] ) );
                        if($start_date_str === $end_date_str): 
                            if($show_end == 'yes') $start_time_str = sprintf( __( '%s - %s', 'townhub-add-ons' ), $start_time_str, $end_time_str );
                        ?>
                        <li class="wkhour-li-item"><span class="opening-hours-day"><?php echo $start_date_str;?></span><span class="opening-hours-time"><?php echo $start_time_str; ?></span></li>
                        <?php else: ?>
                        <li class="wkhour-li-item event-start-date"><span class="opening-hours-day"><?php echo sprintf(_x( 'Start: %s', 'Event date start', 'townhub-add-ons' ), $start_date_str);?><?php // echo $start_date_str;?></span><span class="opening-hours-time"><?php echo $start_time_str; ?></span></li>
                        <?php if($show_end == 'yes'): ?><li class="wkhour-li-item event-end-date"><span class="opening-hours-day"><?php echo sprintf(_x( 'End: %s', 'Event date end', 'townhub-add-ons' ), $end_date_str);?></span><span class="opening-hours-time"><?php echo $end_time_str; ?></span></li><?php endif; ?>
                        <?php endif; 
                    }else{
                        break;
                    }
                    $count++;
                } // end foreach
                ?>
                </ul>

            </div>
        </div>
    </div>
</div>
<?php 
endif;// check hide on plans 

