<script type="text/javascript">
    window._nsl.push(function ($) {
        $(document).ready(function () {
            var $container = $('#<?php echo $containerID; ?>'),
                $form = $container.closest('form'),
                $customerDetails = $form.find('#customer_details');

            $container.find('.nsl-container')
                .addClass('nsl-container-embedded-login-layout-above')
                .css('display', 'block');

            $container
                .prependTo($customerDetails);
        });
    });
</script>
<?php
$style = '   
    {{containerID}} .nsl-container {
        display: none;
        margin-top: 5px;
    }

    {{containerID}} {
        padding-bottom: 5px;
    }';
?>
<style type="text/css">
    <?php echo str_replace('{{containerID}}','#' . $containerID, $style); ?>
</style>
<?php
$style = '
    {{containerID}} .nsl-container {
        display: block;
    }';
?>
<noscript>
    <style type="text/css">
        <?php echo str_replace('{{containerID}}','#' . $containerID, $style); ?>
    </style>
</noscript>