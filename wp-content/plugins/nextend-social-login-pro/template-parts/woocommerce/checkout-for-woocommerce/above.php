<script type="text/javascript">
    window._nsl.push(function ($) {
        $(document).ready(function () {
            var $container = $('#<?php echo $containerID; ?>'),
                $customerInfoContainer = $('#cfw-login-details');

            if ($customerInfoContainer.length === 0) {
                $customerInfoContainer = $container.parent();
            }

            $container.find('.nsl-container')
                .addClass('nsl-container-embedded-login-layout-above')
                .css('display', 'block');

            $container
                .prependTo($customerInfoContainer);
        });
    });
</script>
<?php
$style = '   
    {{containerID}} .nsl-container {
        display: none;
        margin-top: 20px;
    }

    {{containerID}} {
        padding-bottom: 20px;
    }
    
    {{containerID}} .nsl-container-embedded-login-layout-above {
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