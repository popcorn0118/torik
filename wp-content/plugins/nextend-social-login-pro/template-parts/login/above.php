<script type="text/javascript">
    window._nsl.push(function ($) {
        $(document).ready(function () {
            var $form = $('#loginform,#registerform,#front-login-form,#setupform'),
                $main = $('#nsl-custom-login-form-main');
            $main.find('.nsl-container')
                .addClass('nsl-container-login-layout-above')
                .css('display', 'block');

            if ($form.length === 0) {
                $form = $main.closest('form');

                if ($form.length === 0) {
                    $form = $main.parent();
                }
            }

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

    #nsl-custom-login-form-main .nsl-container-login-layout-below {
        padding: 0 0 20px;
    }
</style>
<noscript>
    <style>
        #nsl-custom-login-form-main .nsl-container {
            display: block;
        }
    </style>
</noscript>