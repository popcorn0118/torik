<?php 
/* add_ons_php */

defined( 'ABSPATH' ) || exit;
class Esb_Class_Payment{
    private static $_instance;
    public static function getInstance() {
        if ( ! ( self::$_instance instanceof self ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
	public function int() {
        $this->includes();
    }
    public function includes() {

    }
    public function payment_methods(){

    }
    public function process_payment_checkout($data_checkout){ 

    }
    public function process_payment_check_webhooks(){
    	
    }
}