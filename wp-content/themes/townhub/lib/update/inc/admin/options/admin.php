<?php
/* banner-php */

?>
<div class="wrap">
    <form action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>" method="POST">

        <?php 
            settings_fields( cththemes_auto_update()->get_slug() ); 

            do_settings_sections( cththemes_auto_update()->get_slug() );

            submit_button(); 
        ?>
    </form>
</div>