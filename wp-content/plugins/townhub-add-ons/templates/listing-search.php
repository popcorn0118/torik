<?php
/* add_ons_php */
$listings_llayout = townhub_addons_get_option('llayout');
switch ($listings_llayout) {
    case 'column-map-filter':
        townhub_addons_get_template_part( 'templates/page/column-map-filter' );
        break;
    case 'full-map':
        townhub_addons_get_template_part( 'templates/page/full-map' );
        break;
    case 'full-map-filter':
        townhub_addons_get_template_part( 'templates/page/full-map-filter' );
        break;
    case 'no-map':
        townhub_addons_get_template_part( 'templates/page/no-map' );
        break;
    case 'no-map-filter':
        townhub_addons_get_template_part( 'templates/page/no-map-filter' );
        break;
    default:
        townhub_addons_get_template_part( 'templates/page/column-map' );
        break;
}
