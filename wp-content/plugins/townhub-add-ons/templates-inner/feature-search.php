<?php
/* add_ons_php */
if(!isset($fea)) $fea = false;

?>
<?php if($fea === false) : ?>
<div class="listing-feature-wrap">
    <input id="features_{{fea.value}}" type="checkbox" value="{{fea.value}}" name="lfeas[]">
    <label for="features_{{fea.value}}">{{fea.label}}</label>
</div>
<!-- end listing-feature-wrap -->
<?php else : ?>
<div class="listing-feature-wrap">
    <input id="features_<?php echo $fea['value'];?>" type="checkbox" value="<?php echo $fea['value'];?>" name="lfeas[]">
    <label for="features_<?php echo $fea['value'];?>"><?php echo $fea['label'];?></label>
</div>
<!-- end listing-feature-wrap -->
<?php endif;?>