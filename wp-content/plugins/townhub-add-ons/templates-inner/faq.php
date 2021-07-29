<?php
/* add_ons_php */
if(!isset($index)) $index = false;
if(!isset($title)) $title = __( 'Question', 'townhub-add-ons' );
if(!isset($content)) $content = __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'townhub-add-ons' );
?>
<?php if($index === false) : ?>
<div class="entry">
    <input type="text" name="lfaqs[{{data.index}}][title]" placeholder="<?php esc_attr_e( 'Question',  'townhub-add-ons' );?>" value="<?php echo $title;?>" required>
    <textarea rows="7" name="lfaqs[{{data.index}}][content]" placeholder="<?php esc_attr_e( 'Answer',  'townhub-add-ons' );?>"><?php echo $content;?></textarea>
    <button class="btn rmfield" type="button" ><i class="fa fa-trash"></i></button>
</div>
<!-- end entry -->
<?php else : ?>
<div class="entry">
    <input type="text" name="lfaqs[<?php echo $index; ?>][title]" placeholder="<?php esc_attr_e( 'Question',  'townhub-add-ons' );?>" value="<?php echo $title;?>" required>
    <textarea rows="7" name="lfaqs[<?php echo $index; ?>][content]" placeholder="<?php esc_attr_e( 'Answer',  'townhub-add-ons' );?>"><?php echo $content;?></textarea>
    <button class="btn rmfield" type="button" ><i class="fa fa-trash"></i></button>
</div>
<!-- end entry -->
<?php endif;?>




