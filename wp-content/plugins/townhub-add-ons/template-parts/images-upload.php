<?php
/* add_ons_php */
if(!isset($name)) $name = 'images_upload';
if(!isset($is_single)) $is_single = false;
if(!isset($desc_text)) $desc_text = __( '<i class="fa fa-picture-o"></i> Click here or drop files to upload', 'townhub-add-ons' );
?>
<div class="add-list-media-wrap<?php if($is_single) echo ' single-image-upload';?>">
    <div class="fuzone">
        <div class="fu-text">
            <span><?php echo $desc_text;?></span>
        </div>
        <input type="file" name="<?php echo esc_attr( $name );?>" class="upload"<?php if($is_single==false) echo ' multiple';?>>
        <div class="fu-imgs"></div>
    </div>
</div>
<!-- add-list-media-wrap end -->