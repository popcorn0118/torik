<script type="text/javascript">
    window._nsl.push(function ($) {
        $(document).ready(function () {
            var $container = $('#<?php echo $containerID; ?>'),
                $customerInfoContainer = $('#cfw-login-details');

            if ($customerInfoContainer.length === 0) {
                $customerInfoContainer = $container.parent();
            }

            $container.find('.nsl-container')
                .addClass('nsl-container-embedded-login-layout-below')
                .css('display', 'block');

            $container
                .appendTo($container.closest($customerInfoContainer));
        });
    });
</script>
<?php
$style = '
    {{containerID}} {
        margin-top: 20px;
    }
    
    {{containerID}} .nsl-container {
        display: none;
    }

    {{containerID}} .nsl-container-embedded-login-layout-below {
        clear: both;
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