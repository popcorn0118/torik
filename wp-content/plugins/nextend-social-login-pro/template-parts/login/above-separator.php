<script type="text/javascript">
    window._nsl.push(function ($) {
        $(document).ready(function () {

            var $form = $('#loginform,#registerform,#front-login-form,#setupform'),
                $main = $('#nsl-custom-login-form-main');

            if ($form.length === 0) {
                $form = $main.closest('form');

                if ($form.length === 0) {
                    $form = $main.parent();
                }
            }


            $('<div class="nsl-clear"></div>').prependTo($form);

            $main.find('.nsl-separator').remove();
            $('<div class="nsl-separator"><?php _e('OR', 'nextend-facebook-connect'); ?></div>').appendTo($main);
            $main.find('.nsl-container')
                .addClass('nsl-container-login-layout-above-separator')
                .css('display', 'block');

            $main.prependTo($form);

            var $jetpackSSO = $('#jetpack-sso-wrap__action');
            if ($jetpackSSO.length) {

                $main.clone().attr('id', 'nsl-custom-login-form-jetpack-sso').prependTo($jetpackSSO);
            }
        });
    });
</script>
<style type="text/css">
    #nsl-custom-login-form-main .nsl-container {
        display: none;
    }

    .nsl-clear {
        clear: both;
    }

    #nsl-custom-login-form-main .nsl-separator,
    #nsl-custom-login-form-jetpack-sso .nsl-separator {
        display: flex;
        flex-basis: 100%;
        align-items: center;
        color: #777;
        margin: 20px -8px 20px;
    }

    #nsl-custom-login-form-main .nsl-separator::before,
    #nsl-custom-login-form-main .nsl-separator::after,
    #nsl-custom-login-form-jetpack-sso .nsl-separator::before,
    #nsl-custom-login-form-jetpack-sso .nsl-separator::after {
        content: "";
        flex-grow: 1;
        background: #dddddd;
        height: 1px;
        font-size: 0;
        line-height: 0;
        margin: 0 8px;
    }

    #nsl-custom-login-form-main .nsl-container-login-layout-below {
        clear: both;
    }
</style>
<noscript>
    <style>
        #nsl-custom-login-form-main .nsl-container {
            display: block;
        }
    </style>
</noscript>