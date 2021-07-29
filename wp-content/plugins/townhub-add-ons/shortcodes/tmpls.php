<?php
	/* add_ons_php */
?>
<!-- templates tmpls.php-->
<script type="text/template" id="tmpl-features-new">
<# _.each(data.features, function(fea){ #>
<?php townhub_addons_get_template_part('templates-inner/feature'); ?>
<# }) #>
</script>

<script type="text/template" id="tmpl-add-features">
<# _.each(data.addFeatures, function(addfea){ #>
<?php townhub_addons_get_template_part('templates-inner/add-feature');?>
<# }) #>
</script>
<script type="text/template" id="tmpl-wrhour">
    <?php townhub_addons_get_template_part('templates-inner/hour');?>
</script>
<script type="text/template" id="tmpl-imageslist">
    <?php townhub_addons_get_template_part('templates-inner/image');?>
</script>
<script type="text/template" id="tmpl-faq">
    <?php townhub_addons_get_template_part('templates-inner/faq');?>
</script>



<!-- templates end -->
