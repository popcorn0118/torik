<?php
/* add_ons_php */
$support_types = array(
    'input' => __( 'Text Field', 'townhub-add-ons' ),
    'number' => __( 'Number', 'townhub-add-ons' ),
    'select' => __( 'Select', 'townhub-add-ons' ),
    'muti'      => __( 'Select Multiple', 'townhub-add-ons' ),


    'checkbox' => __( 'Checkbox', 'townhub-add-ons' ),
    'radio' => __( 'Radio', 'townhub-add-ons' ),
    'switch' => __( 'Switch', 'townhub-add-ons' ),
    
    'textarea' => __( 'Textarea', 'townhub-add-ons' ),
    'editor' => __( 'Editor', 'townhub-add-ons' ),
    'file' => _x( 'File Upload', 'Listing type', 'townhub-add-ons' ),
    'raw_html' => _x( 'HTML Code', 'Listing type', 'townhub-add-ons' ),
);
$compares = array(
    '=' => __( 'Listing value equal search value', 'townhub-add-ons' ),
    '!=' => __( 'Listing value not equal search value', 'townhub-add-ons' ),
    '>' => __( 'Listing value greater than search value', 'townhub-add-ons' ),


    '>=' => __( 'Listing value greater than or equal search value', 'townhub-add-ons' ),
    '<' => __( 'Listing value less than search value', 'townhub-add-ons' ),
    '<=' => __( 'Listing value less than or equal search value', 'townhub-add-ons' ),
    'LIKE' => __( 'LIKE', 'townhub-add-ons' ),
    'NOT LIKE' => __( 'NOT LIKE', 'townhub-add-ons' ),
    'IN' => __( 'IN', 'townhub-add-ons' ),
    'NOT IN' => __( 'NOT IN', 'townhub-add-ons' ),
    'BETWEEN' => __( 'BETWEEN', 'townhub-add-ons' ),
    'NOT BETWEEN' => __( 'NOT BETWEEN', 'townhub-add-ons' ),
);

$types = array(
    'CHAR' => __( 'CHAR', 'townhub-add-ons' ),
    'NUMERIC' => __( 'NUMERIC', 'townhub-add-ons' ),
    'DATE' => __( 'DATE', 'townhub-add-ons' ),


    'DATETIME' => __( 'DATETIME', 'townhub-add-ons' ),
    'DECIMAL' => __( 'DECIMAL', 'townhub-add-ons' ),
    'TIME' => __( 'TIME', 'townhub-add-ons' ),
);

if(!isset($index)) $index = false;
if(!isset($name)) $name = 'content_addfields';
if(!isset($field)) $field = array('field_type'=>'input','field_name'=>'field_name','field_label'=>'Field Label','compare'  => '=', 'ctype'=>'CHAR', 'forsearch'=>'yes');
?>
<div class="entry">
    <div class="entry-fields six-cols">
        <select class="custom-select" name="<?php echo $name; ?>[<?php echo $index === false ? '{{data.index}}':$index;?>][field_type]" required>
            <?php
            foreach ($support_types as $val => $lbl) {
                echo '<option value="'.$val.'" '.selected( $field['field_type'], $val, false ).'>'.$lbl.'</option>';
            }
            ?>
        </select>
        <input type="text" name="<?php echo $name; ?>[<?php echo $index === false ? '{{data.index}}':$index;?>][field_name]" placeholder="<?php esc_attr_e( 'Field Name',  'townhub-add-ons' );?>" value="<?php echo isset($field['field_name'])? $field['field_name'] : '';?>" required>
        <input type="text" name="<?php echo $name; ?>[<?php echo $index === false ? '{{data.index}}':$index;?>][field_label]" placeholder="<?php esc_attr_e( 'Field Label',  'townhub-add-ons' );?>" value="<?php echo isset($field['field_label'])? $field['field_label'] : '';?>">
        
        <div class="for-usersearch-checkbox">
            <input type="checkbox" name="<?php echo $name; ?>[<?php echo $index === false ? '{{data.index}}':$index;?>][forsearch]" value="yes" <?php echo isset($field['forsearch']) && $field['forsearch'] == 'yes' ? ' checked="checked"' : '';?>>
        </div>

        <select class="custom-select" name="<?php echo $name; ?>[<?php echo $index === false ? '{{data.index}}':$index;?>][compare]" required>
            <?php
            foreach ($compares as $val => $lbl) {
                echo '<option value="'.$val.'" '.selected( $field['compare'], $val, false ).'>'.$lbl.'</option>';
            }
            ?>
        </select>

        <select class="custom-select" name="<?php echo $name; ?>[<?php echo $index === false ? '{{data.index}}':$index;?>][ctype]" required>
            <?php
            foreach ($types as $val => $lbl) {
                echo '<option value="'.$val.'" '.selected( $field['ctype'], $val, false ).'>'.$lbl.'</option>';
            }
            ?>
        </select>
        
    </div>
        
    <button class="btn rmfield" type="button" ><span class="dashicons dashicons-trash"></span></button>
</div>
<!-- end entry -->

