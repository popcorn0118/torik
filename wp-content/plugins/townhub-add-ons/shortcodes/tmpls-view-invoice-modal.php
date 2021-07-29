<?php
/* add_ons_php */

?>
<div class="ctb-modal-wrap ctb-modal" id="ctb-view-invoice-modal">
    <div class="ctb-modal-holder">
        <div class="ctb-modal-inner modal_main">
            <div class="ctb-modal-close"><i class="fa fa-times"></i></div>
            <div class="ctb-modal-title"><?php _e( 'Invoice ', 'townhub-add-ons' );?><span class="lauthor-msg-title"><?php esc_html_e( 'Details', 'townhub-add-ons' ); ?></span></div>
            <div class="ctb-modal-content">
                <div class="invoice-details-holder"></div>
            </div>
            <!-- end modal-content -->
        </div>
    </div>
</div>
<!-- end modal -->
<script type="text/template" id="tmpl-invoice-data">
    <table class="cth-table table-invoice-details">
        <tbody>
            <tr>
                <td class="w40" colspan="2"><?php _e( 'Date', 'townhub-add-ons' ); ?></td>
                <td class="w60 text-bold" colspan="3">{{{data.date}}}</td>
            </tr>
            <tr>
                <td class="w40" colspan="2"><?php _e( 'Subscribed with', 'townhub-add-ons' ); ?></td>
                <td class="w40 text-bold" colspan="3">{{{data.author}}}</td>
            </tr>
            <tr>
                <td class="w40" colspan="2"><?php _e( 'Charged via', 'townhub-add-ons' ); ?></td>
                <td class="w40 text-bold" colspan="3">{{{data.method}}}</td>
            </tr>
            <tr>
                <td class="w40" colspan="2"><?php _e( 'Expiration date', 'townhub-add-ons' ); ?></td>
                <td class="w40 text-bold" colspan="3">{{{data.expire}}}</td>
            </tr>
            <tr>
                <td class="w80" colspan="4"><?php _e( 'Subscription to', 'townhub-add-ons' ); ?> {{{data.plan}}}</td>
                <td class="w20 text-right">{{{data.amount}}}</td>
            </tr>
            <tr>
                <td class="w40 text-right text-bold text-blur" colspan="2"><?php _e( 'Subtotal', 'townhub-add-ons' ); ?></td>
                <td class="w40 text-right" colspan="3">{{{data.amount}}}</td>
            </tr>
            <tr>
                <td class="w40 text-right text-bold text-blur" colspan="2"><?php _e( 'Total', 'townhub-add-ons' ); ?></td>
                <td class="w40 text-right" colspan="3">{{{data.amount}}}</td>
            </tr>
            <tr>
                <td class="w40 text-right text-bold" colspan="2"><?php _e( 'Paid', 'townhub-add-ons' ); ?></td>
                <td class="w60 text-right text-bold" colspan="3">{{{data.amount}}}</td>
            </tr>
        </tbody>
    </table>
    <div class="invoice-footer">
        <div class="invoice-thanks text-blur"><?php _e( 'Thank you!', 'townhub-add-ons' ); ?></div>
        <div class="invoice-sitename"><?php echo get_bloginfo('name') ?></div>
    </div>
</script>

    