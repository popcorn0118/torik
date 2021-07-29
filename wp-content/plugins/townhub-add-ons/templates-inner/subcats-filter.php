<?php
/* add_ons_php */
if(!isset($subcat)) $subcat = false;

?>
<?php if($subcat === false) : ?>
<div class="listing-feature-wrap">
    <input id="filter_subcats_{{subcat.id}}" type="checkbox" value="{{subcat.id}}" name="filter_subcats[]">
    <label for="filter_subcats_{{subcat.id}}">{{subcat.name}}</label>
</div>
<!-- end listing-feature-wrap -->
<?php else : ?>
<div class="listing-feature-wrap">
    <input id="filter_subcats_<?php echo $subcat['id'];?>" type="checkbox" value="<?php echo $subcat['id'];?>" name="filter_subcats[]">
    <label for="filter_subcats_<?php echo $subcat['id'];?>"><?php echo $subcat['name'];?></label>
</div>
<!-- end listing-feature-wrap -->
<?php endif;?>