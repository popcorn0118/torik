<?php
/* add_ons_php */

if(!isset($index)) $index = false;
if(!isset($name)) $name = false;
if(!isset($widget)) $widget = array(
    'title' => 'Title of your widget area',
    'classes'       => 'col-md-3',
    'widid'       => 'unique-widget-id',
);

$index_text = ($index === false)? '{{data.index}}':$index;
$name_text = ($name == false)? '{{data.field_name}}':$name;
?>
<div class="entry">
    <div class="entry-fields three-cols">
        <input type="text" name="<?php echo esc_attr( $name_text ); ?>[<?php echo esc_attr( $index_text );?>][title]" placeholder="<?php esc_attr_e( 'Widget Title',  'townhub' );?>" value="<?php echo isset($widget['title'])? $widget['title'] : '';?>" required>
        <input type="text" name="<?php echo esc_attr( $name_text ); ?>[<?php echo esc_attr( $index_text );?>][classes]" placeholder="<?php esc_attr_e( 'Widget Classes',  'townhub' );?>" value="<?php echo isset($widget['classes'])? $widget['classes'] : '';?>" required>
        <input type="text" name="<?php echo esc_attr( $name_text ); ?>[<?php echo esc_attr( $index_text );?>][widid]" placeholder="<?php esc_attr_e( 'Widget ID',  'townhub' );?>" value="<?php echo isset($widget['widid'])? $widget['widid'] : '';?>" required>
    </div>
    <button class="no-btn btn-del" type="button" ><i class="fal fa-times"></i></button>
</div>
<!-- end entry -->
