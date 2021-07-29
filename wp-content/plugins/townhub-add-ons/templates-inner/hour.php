<?php
/* add_ons_php */
$working_hours = Esb_Class_Date::wkhours_select();
if(!isset($index)) $index = false;
if(!isset($day)) $day = false;

if(!isset($hour)) $hour = array( 
	'open'=> '8:00',
	'close'=> '22:00'
);
?>
<div class="entry">
    <select class="custom-select chosen-select" name="working_hours[<?php echo $day === false ? '{{data.day}}':$day;?>][hours][<?php echo $index === false ? '{{data.index}}':$index;?>][open]">
    	<?php
    	foreach ($working_hours as $hval => $hlbl){
            echo "<option value=\"{$hval}\" ".selected( $hour['open'], $hval, false ).">{$hlbl}</option>";
        } 
        ?>                                          
    </select>
    <select class="custom-select chosen-select" name="working_hours[<?php echo $day === false ? '{{data.day}}':$day;?>][hours][<?php echo $index === false ? '{{data.index}}':$index;?>][close]">
        <?php
    	foreach ($working_hours as $hval => $hlbl){
            echo "<option value=\"{$hval}\" ".selected( $hour['close'], $hval, false ).">{$hlbl}</option>";
        } 
        ?> 
    </select>
    <button class="btn rmfield" type="button" ><i class="fa fa-trash"></i></button>
</div>
<!-- end entry -->
