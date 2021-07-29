<?php
/* add_ons_php */
$widget_positions = townhub_addons_get_currency_array();

$currency_positions = array(
    'left'      => __( 'Left ($100)', 'townhub-add-ons' ),
    'right'     => __( 'Right (100$)', 'townhub-add-ons' ),
);

if(!isset($index)) $index = false;
if(!isset($name)) $name = false;


if(!isset($currency)) $currency = townhub_addons_get_base_currency();
if(!isset($base)) $base = townhub_addons_get_option('currency', 'USD');

$index_text = ($index === false)? '{{data.index}}':$index;
$name_text = ($name == false)? '{{data.field_name}}':$name;
?>
<div class="entry">
    <div class="widget-infos">
        <!-- <input type="radio" class="currency-base curr-col-active" name="<?php echo $name_text; ?>[base]" <?php checked( $currency['currency'], $base, true ); ?> value="<?php echo isset($currency['currency'])? $currency['currency'] : '';?>"> -->

        <select class="col-first currency-cur curr-col-code" name="<?php echo $name_text; ?>[<?php echo $index_text;?>][currency]" required>
            
            <?php
            foreach ($widget_positions as $pos => $lbl) {
                echo '<option value="'.$pos.'" '.selected( (isset($currency['currency'])? $currency['currency'] : ''), $pos, false ).'>'.$lbl.'</option>';
            }
            ?>
        </select>

        <input class="curr-col-symbol" type="text" name="<?php echo $name_text; ?>[<?php echo $index_text;?>][symbol]" placeholder="<?php esc_attr_e( 'Symbol',  'townhub-add-ons' );?>" value="<?php echo isset($currency['symbol'])? $currency['symbol'] : '';?>" required>

        <input class="curr-col-rate curr-rate-input" type="text" name="<?php echo $name_text; ?>[<?php echo $index_text;?>][rate]" placeholder="<?php esc_attr_e( 'Rate',  'townhub-add-ons' );?>" value="<?php echo isset($currency['rate'])? $currency['rate'] : '';?>" required>
        
        <button class="btn btn-rate get-curr-rate curr-col-get-rate" type="button" data-base="<?php echo $base; ?>" data-cur="<?php echo $currency['currency'];?>">
            <span class=""><?php esc_html_e( 'currencyconverterapi.com', 'townhub-add-ons' ) ?></span>
            <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
        </button>
            
        <select class="curr-col-spos"  name="<?php echo $name_text; ?>[<?php echo $index_text;?>][sb_pos]" required>
            <?php
            foreach ($currency_positions as $pos => $lbl) {
                echo '<option value="'.$pos.'" '.selected( (isset($currency['sb_pos'])? $currency['sb_pos'] : ''), $pos, false ).'>'.$lbl.'</option>';
            }
            ?>
        </select>

        <input class="curr-col-nod" type="text" name="<?php echo $name_text; ?>[<?php echo $index_text;?>][decimal]" placeholder="<?php esc_attr_e( 'Number decimal',  'townhub-add-ons' );?>" value="<?php echo isset($currency['decimal'])? $currency['decimal'] : '';?>" required>

        <input class="curr-col-tsep" type="text" name="<?php echo $name_text; ?>[<?php echo $index_text;?>][ths_sep]" placeholder="<?php esc_attr_e( 'Thousand separator',  'townhub-add-ons' );?>" value="<?php echo isset($currency['ths_sep'])? $currency['ths_sep'] : '';?>" required>

        <input class="curr-col-dsep" type="text" name="<?php echo $name_text; ?>[<?php echo $index_text;?>][dec_sep]" placeholder="<?php esc_attr_e( 'Decimal separator',  'townhub-add-ons' );?>" value="<?php echo isset($currency['dec_sep'])? $currency['dec_sep'] : '';?>" required>
        <button class="btn rmwidget" type="button" data-min="1"><span class="dashicons dashicons-trash"></span></button>
    </div> 
</div>
<!-- end entry -->
