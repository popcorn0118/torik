<?php
/* banner-php */
// if ( is_front_page() ) return;
if(!isset($is_top)) $is_top = false;
if(!isset($classes)) $classes = '';
$cls = 'breadcrumbs-wrapper inline-breadcrumbs block-breadcrumbs';
if($is_top) $cls = 'breadcrumbs-wrapper top-breadcrumbs';

$cls .= '' .$classes;
?>
<div class="<?php echo esc_attr( $cls ); ?>">
	<?php if($is_top) echo '<div class="container">'; ?>
	    <?php townhub_breadcrumbs(); ?>
	    <?php if( is_singular( 'post' ) && function_exists('townhub_addons_breadcrumbs_socials_share') ) townhub_addons_breadcrumbs_socials_share(); ?>  
	<?php if($is_top) echo '</div>'; ?>
</div>

