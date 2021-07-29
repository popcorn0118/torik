<?php
/* banner-php */
?>
<input type="text" name="<?php echo esc_attr( cththemes_auto_update()->get_envato_purchase_code_option_name() ); ?>" class="widefat" value="<?php echo esc_html( cththemes_auto_update()->get_envato_purchase_code_option_value( ) ); ?>" autocomplete="off">

<h4><?php esc_html_e( 'Where Is My Purchase Code?', 'townhub' ); ?></h4>
<blockquote>
    <ol>
        <li><?php esc_html_e( 'Log into your Envato Market account.', 'townhub' ); ?></li>
        <li><?php esc_html_e( 'Hover the mouse over your username at the top of the screen.', 'townhub' ); ?></li>
        <li><?php esc_html_e( 'Click "Downloads" from the drop down menu.', 'townhub' ); ?><a href="https://themeforest.net/downloads" target="_blank"><?php esc_html_e( ' Go to Downloads -->', 'townhub' ); ?></a></li>
        <li><?php esc_html_e( 'Click "License certificate & purchase code" (available as PDF or text file).', 'townhub' ); ?></li>
    </ol>
</blockquote>
<p><?php echo sprintf(__( 'Read <a href="%s" target="_blank">Envato guide</a> for more details.', 'townhub' ), 'https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-');?></p>

