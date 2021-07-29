<?php
/* add_ons_php */
$support_types = array(
    'select' => __( 'Select', 'townhub-add-ons' ),
    'checkbox' => __( 'Checkbox', 'townhub-add-ons' ),
    'radio' => __( 'Radio', 'townhub-add-ons' ),
    'switch' => __( 'Switch', 'townhub-add-ons' ),
    'text' => __( 'Text Field', 'townhub-add-ons' ),
    'textarea' => __( 'Textarea', 'townhub-add-ons' ),
    'editor' => __( 'Editor', 'townhub-add-ons' ),
);
if(!isset($index)) $index = false;
if(!isset($name)) $name = 'content_addfields';
if(!isset($field)) $field = array('field_type'=>'text','field_name'=>'field_name','field_label'=>'Field Label');
?>
<div class="entry">
    <select class="custom-select" name="<?php echo $name; ?>[<?php echo $index === false ? '{{data.index}}':$index;?>][field_type]" required>
        <?php
        foreach ($support_types as $val => $lbl) {
            echo '<option value="'.$val.'" '.selected( $field['field_type'], $val, false ).'>'.$lbl.'</option>';
        }
        ?>
    </select>
    <input type="text" name="<?php echo $name; ?>[<?php echo $index === false ? '{{data.index}}':$index;?>][field_name]" placeholder="<?php esc_attr_e( 'Field Name',  'townhub-add-ons' );?>" value="<?php echo isset($field['field_name'])? $field['field_name'] : '';?>" required>
    <input type="text" name="<?php echo $name; ?>[<?php echo $index === false ? '{{data.index}}':$index;?>][field_label]" placeholder="<?php esc_attr_e( 'Field Label',  'townhub-add-ons' );?>" value="<?php echo isset($field['field_label'])? $field['field_label'] : '';?>">
    <button class="btn rmfield" type="button" ><span class="dashicons dashicons-trash"></span></button>
</div>
<!-- end entry -->

