<?php
/* add_ons_php */
if(!isset($settings)){
	$settings = array('cats_text'=>'');
	$term_args = array(
	    'taxonomy' => 'listing_cat',
	    'orderby'  => 'name', //id, count, name, slug, none
	    'order'    => 'ASC',
	    'number'   => 5,
	);
}else{
	$term_args = array(
	    'taxonomy' => 'listing_cat',
	    'orderby'  => $settings['forderby'], //id, count, name, slug, none
	    'order'    => $settings['forder'],
	    'include'  => $settings['finclude'],
	    'number'   => $settings['fnumber'],
	);
}
$cat_terms = get_terms( $term_args );
if ( ! empty( $cat_terms ) && ! is_wp_error( $cat_terms ) ): ?>
<div class="hero-categories fl-wrap">
    <?php if(!empty($settings['cats_text'])): ?><h4 class="hero-categories_title"><?php echo $settings['cats_text'] ?></h4><?php endif; ?>
    <ul class="no-list-style">
    	<?php foreach ($cat_terms as $cterm) {
    		$term_metas = townhub_addons_custom_tax_metas($cterm->term_id); 
    	?>
        <li>
	        <a href="<?php echo townhub_addons_get_term_link( $cterm, 'listing_cat' ); ?>" class="hero-cat-link hero-cat-<?php echo esc_attr($term_metas['color']);?>">
	        	<?php if(!empty($term_metas['icon'])): ?><i class="<?php echo esc_attr($term_metas['icon']); ?>"></i><?php endif; ?>
	        	<span><?php echo $cterm->name; ?></span>
	        </a>
	    </li>
        <?php }?>
    </ul>
</div>
<?php endif; ?>
  
    