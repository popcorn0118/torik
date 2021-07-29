<?php
/* add_ons_php */
$widget_positions = array(
	'0' => __( 'First Position', 'townhub-add-ons' ),
	'1' => __( 'Second Position', 'townhub-add-ons' ),
	'2' => __( 'Third Position', 'townhub-add-ons' ),
	'3' => __( 'Fourth Position', 'townhub-add-ons' ),
	'4' => __( 'Fifth Position', 'townhub-add-ons' ),
	'5' => __( 'Sixth Position', 'townhub-add-ons' ),
	'6' => __( 'Seventh Position', 'townhub-add-ons' ),
	'7' => __( 'Eighth Position', 'townhub-add-ons' ),
	'8' => __( 'Nineth Position', 'townhub-add-ons' ),
	'9' => __( 'Tenth Position', 'townhub-add-ons' ),
	
);
if(!isset($index)) $index = false;
if(!isset($name)) $name = false;
if(!isset($widget)) $widget = array(
	'widget_title'		=>'Widget Title',
	'widget_position'	=>'1',
	'fields'			=>array(

	)
);


$index_text = ($index === false)? '{{data.index}}':$index;
$name_text = ($name == false)? '{{data.field_name}}':$name;
?>
<div class="entry">
    <div class="widget-infos">
    	<input type="text" name="<?php echo $name_text; ?>[<?php echo $index_text;?>][widget_title]" placeholder="<?php esc_attr_e( 'Widget Title',  'townhub-add-ons' );?>" value="<?php echo isset($widget['widget_title'])? $widget['widget_title'] : '';?>" required>
    	<select  name="<?php echo $name_text; ?>[<?php echo $index_text;?>][widget_position]" required>
        	<option value=""><?php _e( 'Widget Position',  'townhub-add-ons' );?></option>
	        <?php
	        foreach ($widget_positions as $pos => $lbl) {
	            echo '<option value="'.$pos.'" '.selected( (isset($widget['widget_position'])? $widget['widget_position'] : ''), $pos, false ).'>'.$lbl.'</option>';
	        }
	        ?>
	    </select>
    	<button class="btn rmwidget" type="button" ><span class="dashicons dashicons-trash"></span></button>
    </div>
    <div class="widget-fields">
    	<div class="repeater-fields-wrap"  data-tmpl="tmpl-content-addwidgetfield">
            <div class="repeater-fields">
            <?php 
            if(!empty($widget['fields'])){
                foreach ((array)$widget['fields'] as $key => $field) {
                    townhub_addons_get_template_part('templates-inner/add-widgetfield',false, array( 'index'=>$key,'name'=>$name.'['.$index.'][fields]','field'=>$field ) );
                }
            }
            ?>
            </div>
            <button class="btn addfield" data-name="<?php echo $name_text; ?>[<?php echo $index_text;?>][fields]" data-parent-index="<?php echo $index_text;?>" type="button"><?php  esc_html_e( 'Add Field','townhub-add-ons' );?></button>
        </div>
    </div>
    
</div>
<!-- end entry -->

