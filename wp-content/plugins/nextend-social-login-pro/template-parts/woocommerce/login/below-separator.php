<script type="text/javascript">
    window._nsl.push(function ($) {
        $(document).ready(function () {
            var $container = $('#<?php echo $containerID; ?>'),
                $form = $container.closest('form');

            $('<div class="nsl-clear"></div>').appendTo($form);

            $container.find('.nsl-separator').remove();
            $('<div class="nsl-separator"><?php _e('OR', 'nextend-facebook-connect'); ?></div>').prependTo($container);

            $container.find('.nsl-container')
                .addClass('nsl-container-embedded-login-layout-below-separator')
                .css('display', 'block');

            $container
                .appendTo($form);
        });
    });
</script>
<?php
$style = '
    .nsl-clear {
        clear: both;
    }
    
    {{containerID}} .nsl-container {
        display: none;
    }

    {{containerID}} .nsl-separator {
        display: flex;
        flex-basis: 100%;
        align-items: center;
        color: #72777c;
        margin: 20px 0 20px;
        font-weight: bold;
    }

    {{containerID}} .nsl-separator::before,
    {{containerID}} .nsl-separator::after {
        content: "";
        flex-grow: 1;
        background: #dddddd;
        height: 1px;
        font-size: 0;
        line-height: 0;
        margin: 0 8px;
    }

    {{containerID}} .nsl-container-login-layout-below {
        clear: both;
    }

    .login form {
        padding-bottom: 20px;
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