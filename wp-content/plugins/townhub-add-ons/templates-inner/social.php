<?php
/* add_ons_php */
$socials = townhub_addons_get_socials_list();
if(!isset($index)) $index = false;
if(!isset($url)) $url = '#';
if(!isset($name)) $name = 'facebook';
?>
<div class="entry">
	<div class="entry-fields two-cols">
		<select class="custom-select" name="socials[<?php echo $index === false ? '{{data.index}}':$index;?>][name]" required>
	        <?php
	        foreach ($socials as $val => $lbl) {
	            echo '<option value="'.$val.'" '.selected( $name, $val, false ).'>'.$lbl.'</option>';
	        }
	        ?>
	    </select>
	    <input type="text" name="socials[<?php echo $index === false ? '{{data.index}}':$index;?>][url]" placeholder="<?php esc_attr_e( 'Social URL',  'townhub-add-ons' );?>" value="<?php echo esc_url($url);?>" required>
	</div>
    <button class="no-btn btn-del" type="button" ><i class="fal fa-times"></i></button>
</div>
<!-- end entry -->

