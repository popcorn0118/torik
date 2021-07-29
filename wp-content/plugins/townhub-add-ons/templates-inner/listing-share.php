<?php
/* add_ons_php */

?>
<div class="fl-wrap list-single-header-column">
    <a class="custom-scroll-link" href="#listing-add-review"><i class="fa fa-hand-o-right"></i><?php esc_html_e( 'Add Review ', 'townhub-add-ons' ); ?></a>
    <span class="viewed-counter"><i class="fa fa-eye"></i> <?php esc_html_e( 'Viewed - ',  'townhub-add-ons' );?><?php echo Esb_Class_LStats::get_stats(get_the_ID());?> </span>
    <?php townhub_addons_echo_socials_share(); ?>
</div>


