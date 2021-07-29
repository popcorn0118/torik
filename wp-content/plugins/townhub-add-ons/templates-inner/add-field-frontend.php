<?php
/* add_ons_php */
if(!isset($addfield)) $addfield = array(
    'type' => 'text',
    'name'  => 'add_field_name',
    'label' => 'Field Label',
    'value' => '',
    'lvalue'    => 'lvalue'

);
if(!isset($name_prefix)) $name_prefix = 'add_fields_';
?>
<div class="listing-add-field-wrap add-field-<?php echo $addfield['type'];?>">
<?php
switch ($addfield['type']) {
	case 'select':
		?>
		<label for="<?php echo $name_prefix.$addfield['name'];?>"><?php echo $addfield['label'];?></label>
        <select id="<?php echo $name_prefix.$addfield['name'];?>" name="<?php echo $name_prefix.$addfield['name'];?>">
        <?php 
        if(is_array($addfield['value'])){
        	foreach ((array)$addfield['value'] as $val) {
        		echo '<option value="'.$val['value'].'" '.selected( $addfield['lvalue'], $val['value'], false ).'>'.$val['name'].'</option>';
        	}
        }
        ?>
        </select>
    <?php
		break;
	case 'checkbox':
		?>
        <input type="checkbox" id="<?php echo $name_prefix.$addfield['name'];?>" name="<?php echo $name_prefix.$addfield['name'];?>" value="<?php echo $addfield['value'];?>" <?php checked( $addfield['lvalue'], $addfield['value'], true );?>/>
        <label for="<?php echo $name_prefix.$addfield['name'];?>"><?php echo $addfield['label'];?></label>
    <?php
		break;
	case 'radio':
	// echo'<pre>';var_dump($addfield);
		?>
		<label><?php echo $addfield['label'];?></label>
		<?php
		if(is_array($addfield['value'])){
		echo '<div class="radios-wrap">';
        	foreach ((array)$addfield['value'] as $key => $val) {
        		?>
                <input type="radio" id="<?php echo $name_prefix.$addfield['name'];?><?php echo $key;?>" name="<?php echo $name_prefix.$addfield['name'];?>" value="<?php echo $val['value'];?>" <?php checked( $addfield['lvalue'], $val['value'], true );?>>
                <label for="<?php echo $name_prefix.$addfield['name'];?><?php echo $key;?>"><?php echo $val['name'];?></label>
                <div class="radios-sep"></div>
        <?php
        	}
        echo '</div>';
        }
        ?>
    <?php
		break;
	case 'switch':
		?>
		<label class="switch-field-label"><?php echo $addfield['label'];?></label>
        <div class="onoffswitch">
            <input type="checkbox" id="<?php echo $name_prefix.$addfield['name'];?>" name="<?php echo $name_prefix.$addfield['name'];?>" value="<?php echo $addfield['value'];?>" class="onoffswitch-checkbox" <?php checked( $addfield['lvalue'], $addfield['value'], true );?>>
            <label class="onoffswitch-label" for="<?php echo $name_prefix.$addfield['name'];?>">
                <span class="onoffswitch-inner"></span>
                <span class="onoffswitch-switch"></span>
            </label>
        </div>
    <?php
		break;
	case 'textarea':
		?>
		<label for="<?php echo $name_prefix.$addfield['name'];?>"><?php echo $addfield['label'];?></label>
        <textarea id="<?php echo $name_prefix.$addfield['name'];?>" name="<?php echo $name_prefix.$addfield['name'];?>"><?php echo $addfield['lvalue'];?></textarea>
    <?php
		break;
    case 'editor':
        wp_editor( $addfield['lvalue'], $name_prefix.$addfield['name'], array('textarea_rows'=>7) ); 
        break;
        
	default:
		?>
		<label for="<?php echo $name_prefix.$addfield['name'];?>"><?php echo $addfield['label'];?></label>
        <input type="text" id="<?php echo $name_prefix.$addfield['name'];?>" name="<?php echo $name_prefix.$addfield['name'];?>" value="<?php echo $addfield['lvalue'];?>"/>
    <?php
		break;
}
?>
</div>

