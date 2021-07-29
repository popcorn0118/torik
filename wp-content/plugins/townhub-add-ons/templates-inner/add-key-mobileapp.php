<?php
/* add_ons_php */
	if(!isset($index)) $index = false;
	$index = ($index === false)? '{{data.index}}':$index;

	if(!isset($name)) $name = false;
	$name_text = ($name == false)? '{{data.field_name}}':$name;

	if(!isset($key)) $key = false;
	$key_text = ($key == false) ? '{{data.key}}' : $key['key'];

	$permissions_text = ($key == false) ? 'read' :$key['permissions'];
	// var_dump($key);
	// var_dump($permissions_text);
	$user_id = $key['user'];
	$all_user = get_users();
	$all_permissions = ['read','read/write'];
?>

<div class="entry">
	<!-- <p><?php esc_html_e('API Key generated successfully. Make sure to copy your new keys now as the secret key will be hidden once you leave this page.','townhub-add-ons') ?></p> -->
	<input type="text" name="<?php echo $name_text; ?>[<?php echo $index ?>][key]" value='<?php echo $key_text ?>' size='40' required>
	<select name="<?php echo $name_text; ?>[<?php echo $index ?>][user]" >
		<?php
			foreach ($all_user as $key => $user) {
				$selected = '';
                if($user_id==$user->ID){
                    $selected = 'selected="selected" ';
                }
                echo '<option value="'.$user->ID.'" '.$selected.'>'.$user->user_login."</option>\n";
            }
		?>
	</select>
	<select name="<?php echo $name_text; ?>[<?php echo $index ?>][permissions]">
		<?php  
			foreach ($all_permissions as $key => $permissions) {
				# code...
				$selected = '';
				if($permissions_text==$permissions){
					$selected= 'selected="selected" ';
				}
				echo '<option value="'.$permissions.'" '.$selected.'>'.$permissions."</option>\n";
			}
		?>
	</select>
	<button class="btn rmkey button-secondary" type="button"> <?php esc_html_e('remove','townhub-add-ons') ?></button>
</div>

