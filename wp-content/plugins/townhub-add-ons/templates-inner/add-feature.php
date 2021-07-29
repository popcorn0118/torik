<?php
/* add_ons_php */
if(!isset($addfea)) $addfea = false;


?>
<?php if($addfea === false) : ?>
<div class="listing-add-feature-wrap add-feature-{{addfea.type}}">
    <# if(addfea.type == 'select'){ #>
        <label for="add-features[{{addfea.name}}]">{{{addfea.label}}}</label>
        <select id="add-features[{{addfea.name}}]" name="add-features[{{addfea.name}}]">
        <# if(addfea.value && _.isObject(addfea.value)){ #>
            <# _.each(addfea.value, function(val,key){ #>
                <option value="{{val.value}}"<# if(val.value == addfea.lvalue){ #> selected<# } #>>{{{val.name}}}</option>
            <# }) #>
        <# } #>
        </select>

    <# }else if(addfea.type == 'checkbox'){ #>
        <input id="add-features[{{addfea.name}}]" type="checkbox" value="{{addfea.value}}" name="add-features[{{addfea.name}}]"<# if(addfea.value == addfea.lvalue){ #> checked<# } #>>
        <label for="add-features[{{addfea.name}}]">{{{addfea.label}}}</label>
    <# }else if(addfea.type == 'radio'){ #>
        <label>{{{addfea.label}}}</label>
        <# if(addfea.value && _.isObject(addfea.value)){ #>
        <div class="radios-wrap">
            <# _.each(addfea.value, function(val,key){ #>
                <input type="radio" id="add-features[{{addfea.name}}]{{key}}" name="add-features[{{addfea.name}}]" value="{{val.value}}"<# if(val.value == addfea.lvalue){ #> checked<# } #>>
                <label for="add-features[{{addfea.name}}]{{key}}">{{{val.name}}}</label>
                <div class="radios-sep"></div>
            <# }) #>
        </div>
        <# } #>

    <# }else if(addfea.type == 'switch'){ #>
        <label class="switch-field-label">{{{addfea.label}}}</label>
        <div class="onoffswitch">
            <input type="checkbox" id="add-features[{{addfea.name}}]" name="add-features[{{addfea.name}}]" value="{{addfea.value}}" class="onoffswitch-checkbox"<# if(addfea.value == addfea.lvalue){ #> checked<# } #>>
            <label class="onoffswitch-label" for="add-features[{{addfea.name}}]">
                <span class="onoffswitch-inner"></span>
                <span class="onoffswitch-switch"></span>
            </label>
        </div>
    <# }else if(addfea.type == 'textarea'){ #>
        <label for="add-features[{{addfea.name}}]">{{{addfea.label}}}</label>
        <textarea id="add-features[{{addfea.name}}]" name="add-features[{{addfea.name}}]">{{{addfea.lvalue}}}</textarea>
    <# }else{ #>
        <label for="add-features[{{addfea.name}}]">{{{addfea.label}}}</label>
        <input type="text" id="add-features[{{addfea.name}}]" name="add-features[{{addfea.name}}]" value="{{addfea.lvalue}}"/>
    <# } #>
</div>
<?php else : ?>
<div class="listing-add-feature-wrap add-feature-<?php echo $addfea['type'];?>">
<?php
switch ($addfea['type']) {
	case 'select':
		?>
		<label for="add-features[<?php echo $addfea['name'];?>]"><?php echo $addfea['label'];?></label>
        <select id="add-features[<?php echo $addfea['name'];?>]" name="add-features[<?php echo $addfea['name'];?>]">
        <?php 
        if(is_array($addfea['value'])){
        	foreach ($addfea['value'] as $val) {
        		echo '<option value="'.$val['value'].'" '.selected( $addfea['lvalue'], $val['value'], false ).'>'.$val['name'].'</option>';
        	}
        }
        ?>
        </select>
    <?php
		break;
	case 'checkbox':
		?>
        <input type="checkbox" id="add-features[<?php echo $addfea['name'];?>]" name="add-features[<?php echo $addfea['name'];?>]" value="<?php echo $addfea['value'];?>" <?php checked( $addfea['lvalue'], $addfea['value'], true );?>/>
        <label for="add-features[<?php echo $addfea['name'];?>]"><?php echo $addfea['label'];?></label>
    <?php
		break;
	case 'radio':
	// echo'<pre>';var_dump($addfea);
		?>
		<label><?php echo $addfea['label'];?></label>
		<?php
		if(is_array($addfea['value'])){
		echo '<div class="radios-wrap">';
        	foreach ($addfea['value'] as $key => $val) {
        		?>
                <input type="radio" id="add-features[<?php echo $addfea['name'];?>]<?php echo $key;?>" name="add-features[<?php echo $addfea['name'];?>]" value="<?php echo $val['value'];?>" <?php checked( $addfea['lvalue'], $val['value'], true );?>>
                <label for="add-features[<?php echo $addfea['name'];?>]<?php echo $key;?>"><?php echo $val['name'];?></label>
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
		<label class="switch-field-label"><?php echo $addfea['label'];?></label>
        <div class="onoffswitch">
            <input type="checkbox" id="add-features[<?php echo $addfea['name'];?>]" name="add-features[<?php echo $addfea['name'];?>]" value="<?php echo $addfea['value'];?>" class="onoffswitch-checkbox" <?php checked( $addfea['lvalue'], $addfea['value'], true );?>>
            <label class="onoffswitch-label" for="add-features[<?php echo $addfea['name'];?>]">
                <span class="onoffswitch-inner"></span>
                <span class="onoffswitch-switch"></span>
            </label>
        </div>
    <?php
		break;
	case 'textarea':
		?>
		<label for="add-features[<?php echo $addfea['name'];?>]"><?php echo $addfea['label'];?></label>
        <textarea id="add-features[<?php echo $addfea['name'];?>]" name="add-features[<?php echo $addfea['name'];?>]"><?php echo $addfea['lvalue'];?></textarea>
    <?php
		break;
	default:
		?>
		<label for="add-features[<?php echo $addfea['name'];?>]"><?php echo $addfea['label'];?></label>
        <input type="text" id="add-features[<?php echo $addfea['name'];?>]" name="add-features[<?php echo $addfea['name'];?>]" value="<?php echo $addfea['lvalue'];?>"/>
    <?php
		break;
}
?>
</div>
<?php endif;?>