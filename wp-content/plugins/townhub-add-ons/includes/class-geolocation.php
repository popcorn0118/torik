<?php
/* add_ons_php */

defined('ABSPATH') || exit;

class Esb_Class_Geolocation
{

    public $data = array();
    protected $_cookie_expiration;


    public function __construct()
    {
        $this->_cookie_expiration = date_i18n('U') + intval( apply_filters( 'cth_geo_expiration', 0 ) ); // 2 hours - 60 * 60 * 2
        $this->init();
    }

    private function init(){
        if( townhub_addons_get_option('use_autolocate','no') == 'yes' ) add_action( 'wp_loaded', array($this, 'get_cookie') );
    }

    public function get_cookie(){
        if(isset($_COOKIE["cth-geolocation"])){
            $cookie_data = stripslashes($_COOKIE['cth-geolocation']);
            $data = json_decode($cookie_data, true);
            if($data != null) $this->data = $data; 
        }else{
            require_once ESB_ABSPATH . 'includes/classes/geoplugin.class/locate.php';
            $this->data = (array)cth_addons_locate(); // ny ip: '161.185.160.93'
            $this->set_cookie();
        }

        // var_dump($this->get('lat'));
    }
    public function get($name = '', $default = ''){
        if( !empty($name) ){
            if( isset($this->data[$name]) ) 
                return $this->data[$name];
        }
        return $default;
    }
    public function set_cookie(){
        // https://stackoverflow.com/questions/12846646/dealing-with-plus-signs-showing-up-when-using-json-encode-in-php
        esb_setcookie( 'cth-geolocation', rawurlencode(wp_json_encode($this->data)), $this->_cookie_expiration, false, true );
        
    }
    
}
