<?php
/* add_ons_php */

defined('ABSPATH') || exit;

class Esb_Class_Checkout
{
    protected $product_id = 0;
    protected $data       = array();
    protected $data_user  = array(
        'ID'                 => 0,
        'first_name'         => '',
        'last_name'          => '',
        'email'              => '',
        'phone'              => '',

        'billing_first_name' => '',
        'billing_last_name'  => '',
        'billing_company'    => '',
        'billing_city'       => '',
        'billing_country'    => '',
        'billing_address_1'  => '',
        'billing_address_2'  => '',
        'billing_state'      => '',
        'billing_postcode'   => '',
        'billing_phone'      => '',
        'billing_email'      => '',
    );

    protected $user_billing = array(
        'billing_first_name',
        'billing_last_name',
        'billing_company',
        'billing_city',
        'billing_country',
        'billing_address_1',
        'billing_address_2',
        'billing_state',
        'billing_postcode',
        'billing_phone',
        'billing_email',
    );

    public function __construct()
    {

    }

    protected function data_user()
    {
        if (is_user_logged_in()) {
            $user_object     = wp_get_current_user();
            $this->data_user = array(
                'ID'         => $user_object->ID,
                'first_name' => $user_object->first_name,
                'last_name'  => $user_object->last_name,
                'email'      => get_user_meta($user_object->ID, ESB_META_PREFIX . 'email', true), //$user_object->user_email,
                'phone'      => get_user_meta($user_object->ID, ESB_META_PREFIX . 'phone', true),
            );

            if (is_array($this->user_billing) && !empty($this->user_billing)) {
                foreach ($this->user_billing as $meta_key) {
                    $this->data_user[$meta_key] = get_user_meta($user_object->ID, $meta_key, true);

                }
            }

        }
    }

    protected function get_data()
    {
        // if(!isset($this->data)) $this->data = array();
        return $this->data;
    }
    protected function set_data($name, $value)
    {
        $prev = '';
        if (isset($this->data[$name])) {
            $prev = $this->data[$name];
        }

        $this->data[$name] = $value;
        return $prev;
    }

    public function render()
    {
        echo 'this is checkout parent class';
    }
    protected function progressbar()
    {
        if (townhub_addons_get_option('ck_hide_tabs') == 'yes') {
            return;
        }

        $tabs = array();
        if (townhub_addons_get_option('ck_hide_information') != 'yes') {
            $tabs['personal_info'] = __('Personal Info', 'townhub-add-ons');
        }

        if (townhub_addons_get_option('ck_hide_billing') != 'yes') {
            $tabs['user_billing'] = __('Billing Address', 'townhub-add-ons');
        }

        if (townhub_addons_get_option('ck_hide_payments') != 'yes') {
            $tabs['payment_methods'] = __('Payment Method', 'townhub-add-ons');
        }

        $tabs['ck_confirm'] = __('Confirm', 'townhub-add-ons');

        $tabs = apply_filters('esb_checkout_tab', $tabs);
        ?>
        <ul id="ck-progressbar" class="no-list-style ck-progress-bar ck-progress-<?php echo count($tabs); ?>">
            <?php
$count = 1;
        foreach ($tabs as $tab_id => $tab_title) {
            echo '<li class="ck-tab-item' . ($count == 1 ? ' active' : '') . '" id="ck-tab-' . esc_attr($tab_id) . '"><span class="tolt" data-microtip-position="top" data-tooltip="' . $tab_title . '">' . sprintf(__('%02d.', 'townhub-add-ons'), $count) . '</span></li>';
            $count++;
        }?>
        </ul>
        <?php
}

    protected function render_information()
    {
        if (townhub_addons_get_option('ck_hide_information') == 'yes') {
            return;
        }

        ?>

            <fieldset  id="ck-fieldset-personal_info" class="fl-wrap ck-fieldset-item clearfix">

                <?php townhub_addons_get_template_part( 'template-parts/checkout/tab', 'infos', array('user_datas'=> $this->data_user ) );?>

                <?php 
                if (townhub_addons_get_option('ck_hide_tabs') != 'yes') {
                    $this->render_terms();
                ?>
                    <span class="fw-separator"></span>
                    <?php 
                    if ( townhub_addons_get_option('ck_hide_billing') == 'yes' && townhub_addons_get_option('ck_hide_payments') == 'yes') {
                        $this->render_submit();
                    }elseif( townhub_addons_get_option('ck_hide_billing') != 'yes' ){ ?>
                        <a  href="#"  class="next-form action-button color-bg"><?php esc_html_e('Billing Address ', 'townhub-add-ons');?></a>
                    <?php }elseif( townhub_addons_get_option('ck_hide_payments') != 'yes' ){ ?>
                        <a  href="#"  class="next-form action-button color-bg"><?php esc_html_e('Payment ', 'townhub-add-ons');?></a>
                    <?php } ?>
                <?php } ?>
            </fieldset>
        <?php
}
    protected function render_billingAddress()
    {
        if (townhub_addons_get_option('ck_hide_billing') == 'yes') {
            return;
        }

        ?>
            <fieldset  id="ck-fieldset-user_billing" class="fl-wrap checkout-required ck-fieldset-item clearfix">
                <?php townhub_addons_get_template_part( 'template-parts/checkout/tab', 'billing', array('user_datas'=> $this->data_user ) );?>
                <?php 
                if (townhub_addons_get_option('ck_hide_tabs') != 'yes') {
                    if (townhub_addons_get_option('ck_hide_information') == 'yes') $this->render_terms();
                ?>
                    <span class="fw-separator"></span>
                    <?php 
                    if (townhub_addons_get_option('ck_hide_information') != 'yes') {?>
                        <a  href="#"  class="previous-form action-button back-form color2-bg"><?php esc_html_e('Back', 'townhub-add-ons');?></a>
                    <?php 
                    } ?>
                    <?php 
                    if (townhub_addons_get_option('ck_hide_payments') == 'yes') {
                        $this->render_submit();
                    }else{ ?>
                        <a  href="#"  class="next-form action-button color-bg"><?php esc_html_e('Payment ', 'townhub-add-ons');?></a>
                    <?php } ?>
                <?php } ?>
            </fieldset>
        <?php
    }
    protected function render_payments()
    {
        if (townhub_addons_get_option('ck_hide_payments') == 'yes') {
            return;
        }
        $price_total = ESB_ADO()->cart->get_total();
        ?>
        <fieldset id="ck-fieldset-payment_methods" class="fl-wrap ck-fieldset-item clearfix">
            <?php if($price_total > 0) townhub_addons_get_template_part( 'template-parts/checkout/tab', 'payments' );?>
            
            <?php // $this->render_terms(); ?>
            <!-- .payment-methods end -->

            <?php
            if ( townhub_addons_get_option('ck_hide_tabs') != 'yes' ) {
                if( townhub_addons_get_option('ck_hide_information') == 'yes' && townhub_addons_get_option('ck_hide_billing') == 'yes') $this->render_terms();
                echo '<span class="fw-separator"></span>';
                if( townhub_addons_get_option('ck_hide_information') != 'yes' || townhub_addons_get_option('ck_hide_billing') != 'yes'){
            ?>
                
                <a  href="#" class="previous-form  back-form action-button color2-bg"><?php _e('Back', 'townhub-add-ons');?></a>
                <?php } ?>
            <?php
                $this->render_submit();
            } ?>
            

        </fieldset>
        <?php
    }
    public function render_submit(){
        $is_stripe_first = false;
        $btn_text = esc_html__('Place Order', 'townhub-add-ons');
        $classes = 'btn color-bg lcheckout_btn';
        foreach ( townhub_addons_get_payments() as $method => $data ) {
            if( 'stripe' == $method ){
                $is_stripe_first = true;
                $btn_text = esc_html( $data['checkout_text'] );
                $classes .= ' stripe-checkout';
            }
            break;
        }
        ?>
        <button data-payment="<?php echo rawurlencode(json_encode($this->stripe_data)); ?>" class="<?php echo esc_attr( $classes ); ?>" type="submit" id="lcheckout_btn"><span class="btn-text"><?php echo $btn_text;?></span><i class="fal fa-angle-right i-for-default"></i><i class="fa fa-spinner fa-pulse i-for-loading"></i></button>
        <div class="clearfix"></div>
        <?php
    }
    public function update_user_billing($user_id = 0)
    {
        if ($user_id == 0) {
            $user_id = get_current_user_id();
        }

        if ($user_id == 0) {
            return;
        }

        if (is_array($this->user_billing) && !empty($this->user_billing)) {
            foreach ($this->user_billing as $meta_key) {
                if ( isset($_POST[$meta_key]) && $_POST[$meta_key] ) {
                    update_user_meta( $user_id, $meta_key, esc_html($_POST[$meta_key]) );
                }

            }
        }
    }

}

