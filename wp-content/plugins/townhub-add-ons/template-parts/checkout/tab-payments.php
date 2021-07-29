<?php
/* add_ons_php */

?>
<div class="ck-tab-title fl-wrap">
    <h3><?php _e('Payment method', 'townhub-add-ons');?></h3>
</div>

<div class="payment-methods">
    <div class="payment-methods-wrap">
    <?php
    $idx = 0;
    foreach ( townhub_addons_get_payments() as $method => $data ) {
    ?>
        <div class="payment-method-item payment-method-<?php echo $method; ?>">
            <label for="payment-<?php echo $method; ?>">
                <span class="payment-info"><span class="payment-title"><?php echo $data['title']; ?></span><?php if ($data['icon']): ?><img class="payment-icon" src="<?php echo $data['icon']; ?>" alt="<?php echo esc_attr($data['title']); ?>"><?php endif;?></span>
                <input class="payment-method-radio" type="radio" name="payment-method" id="payment-<?php echo $method; ?>" data-btn="<?php echo $data['checkout_text']; ?>" value="<?php echo $method; ?>" required="required" <?php if ($idx == 0) echo ' checked="checked"';?>>
                <span class="payment-desc-wrap"><span class="payment-desc"><?php echo $data['desc']; ?></span></span>
            </label>
        </div>
        <!-- end <?php echo $method; ?> -->
        <?php
        $idx++;
    } ?>

    </div>
</div>