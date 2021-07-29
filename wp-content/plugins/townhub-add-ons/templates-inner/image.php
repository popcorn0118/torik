<?php
/* add_ons_php */

if(!isset($data)) $data = false;
if(!isset($name)) $name = 'images';
?>
<?php if($data === false) : ?><li class="select-image-item" data-field="{{data.field}}">
    <img  src="{{data.url}}" class="dfsdf" alt="">
    <span><a href="#" class="remove-img-btn" ><i class="fa fa-trash"></i></a></span>
    <input type="hidden" id="imagelist_{{data.field}}-{{data.id}}" data-id="{{data.id}}" name="{{data.field}}[]" value="{{data.id}}">
</li><?php else : ?><li class="select-image-item" data-field="<?php echo $name;?>">
    <?php echo wp_get_attachment_image( $data['id'], 'medium_large' ); ?>
    <span><a href="#" class="remove-img-btn" ><i class="fa fa-trash"></i></a></span>
    <input type="hidden" id="imagelist_<?php echo $name;?>-<?php echo $data['id'];?>" data-id="<?php echo $data['id'];?>" name="<?php echo $name;?>[]" value="<?php echo $data['id'];?>">
</li><?php endif; ?>

