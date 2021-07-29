<?php
namespace CTHGeo;
/* add_ons_php */
/*
This PHP class is free software: you can redistribute it and/or modify
the code under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version. 

However, the license header, copyright and author credits 
must not be modified in any form and always be displayed.

This class is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

@author geoPlugin (gp_support@geoplugin.com)
@copyright Copyright geoPlugin (gp_support@geoplugin.com)
$version 1.2


This PHP class uses the PHP Webservice of http://www.geoplugin.com/ to geolocate IP addresses

Geographical location of the IP address (visitor) and locate currency (symbol, code and exchange rate) are returned.

See http://www.geoplugin.com/webservices/php for more specific details of this free service

*/
// namespace CTHGeo;

class geoPlugin {
	
	//the geoPlugin server
	var $host = 'http://www.geoplugin.net/php.gp?ip={IP}&base_currency={CURRENCY}&lang={LANG}';
		
	//the default base currency
	var $currency = 'USD';
	
	//the default language
	var $lang = 'en';
/*
supported languages:
de
en
es
fr
ja
pt-BR
ru
zh-CN
*/

	//initiate the geoPlugin vars
	// var $ip = null;
	// var $city = null;
	// var $region = null;
	// var $regionCode = null;
	// var $regionName = null;
	// var $dmaCode = null;
	// var $countryCode = null;
	// var $countryName = null;
	// var $inEU = null;
	// var $euVATrate = false;
	// var $continentCode = null;
	// var $continentName = null;
	// var $latitude = null;
	// var $longitude = null;
	// var $locationAccuracyRadius = null;
	var $timezone = null;
	// var $currencyCode = null;
	// var $currencySymbol = null;
	// var $currencyConverter = null;

	// new with js
	var $lat = null;
	var $lng = null;
	var $country = null; 
	var $country_code = null; 
	var $administrative_area_level_1 = null; // state
	var $administrative_area_level_2 = null; // city
	var $postal_code = null;
	var $locality = null;
	var $sublocality = null;
	var $formatted_address = null;
	var $result = null;



	
	function __construct() {

	}
	
	function locate($ip = null) {
		
		global $_SERVER;
		
		if ( is_null( $ip ) ) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		
		$host = str_replace( '{IP}', $ip, $this->host );
		$host = str_replace( '{CURRENCY}', $this->currency, $host );
		$host = str_replace( '{LANG}', $this->lang, $host );
		
		$data = array();
		
		$response = $this->fetch($host);

		// https://stackoverflow.com/questions/10152904/how-to-repair-a-serialized-string-which-has-been-corrupted-by-an-incorrect-byte
		// $response = preg_replace('!s:(\d+):"(.*?)";!e', "'s:'.strlen('$2').':\"$2\";'", $response);
		
		// $response = preg_replace_callback ( '!s:(\d+):"(.*?)";!', function($match) {      
		//     return ($match[1] == strlen($match[2])) ? $match[0] : 's:' . strlen($match[2]) . ':"' . $match[2] . '";';
		// },$response );

		if( strpos($response, '<html>') === false ){
			$data = unserialize($response);
		}

		
		
		// delete vars
		unset($this->host);
		unset($this->currency);
		unset($this->lang);
		
		//set the geoPlugin vars
		// $this->ip = $ip;
		// if( isset($data['geoplugin_city']) ) $this->city = $data['geoplugin_city'];
		// if( isset($data['geoplugin_region']) ) $this->region = $data['geoplugin_region'];
		// if( isset($data['geoplugin_regionCode']) ) $this->regionCode = $data['geoplugin_regionCode'];
		// if( isset($data['geoplugin_regionName']) ) $this->regionName = $data['geoplugin_regionName'];
		// if( isset($data['geoplugin_dmaCode']) ) $this->dmaCode = $data['geoplugin_dmaCode'];
		// if( isset($data['geoplugin_countryCode']) ) $this->countryCode = $data['geoplugin_countryCode'];
		// if( isset($data['geoplugin_countryName']) ) $this->countryName = $data['geoplugin_countryName'];
		// if( isset($data['geoplugin_inEU']) ) $this->inEU = $data['geoplugin_inEU'];
		// if( isset($data['euVATrate']) ) $this->euVATrate = $data['euVATrate'];
		// if( isset($data['geoplugin_continentCode']) ) $this->continentCode = $data['geoplugin_continentCode'];
		// if( isset($data['geoplugin_continentName']) ) $this->continentName = $data['geoplugin_continentName'];
		// if( isset($data['geoplugin_latitude']) ) $this->latitude = $data['geoplugin_latitude'];
		// if( isset($data['geoplugin_longitude']) ) $this->longitude = $data['geoplugin_longitude'];
		// if( isset($data['geoplugin_locationAccuracyRadius']) ) $this->locationAccuracyRadius = $data['geoplugin_locationAccuracyRadius'];
		if( isset($data['geoplugin_timezone']) ) $this->timezone = $data['geoplugin_timezone'];
		// if( isset($data['geoplugin_currencyCode']) ) $this->currencyCode = $data['geoplugin_currencyCode'];
		// if( isset($data['geoplugin_currencySymbol']) ) $this->currencySymbol = $data['geoplugin_currencySymbol'];
		// if( isset($data['geoplugin_currencyConverter']) ) $this->currencyConverter = $data['geoplugin_currencyConverter'];


		// new with js
		if( isset($data['geoplugin_latitude']) ) $this->lat = $data['geoplugin_latitude'];
		if( isset($data['geoplugin_longitude']) ) $this->lng = $data['geoplugin_longitude'];
		if( isset($data['geoplugin_region']) ) $this->administrative_area_level_1 = $data['geoplugin_region'];
		if( isset($data['geoplugin_city']) ) $this->administrative_area_level_2 = $data['geoplugin_city'];
		
		if( isset($data['geoplugin_countryName']) ) $this->country = $data['geoplugin_countryName'];
		if( isset($data['geoplugin_countryCode']) ) $this->country_code = $data['geoplugin_countryCode'];

		$this->formatted_address = $this->result = implode( __( ', ', 'townhub-add-ons' ), array_filter(array($this->administrative_area_level_2,$this->administrative_area_level_1,$this->country)) );
	}
	
	function fetch($host) {

		if ( function_exists('curl_init') ) {
						
			//use cURL to fetch data
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $host);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, 'geoPlugin PHP Class v1.1');
			$response = curl_exec($ch);
			curl_close ($ch);
			
		} else if ( ini_get('allow_url_fopen') ) {
			
			//fall back to fopen()
			$response = file_get_contents($host, 'r');
			
		} else {

			trigger_error ('geoPlugin class Error: Cannot retrieve data. Either compile PHP with cURL support or enable allow_url_fopen in php.ini ', E_USER_ERROR);
			return;
		
		}
		
		return $response;
	}
	
	function convert($amount, $float=2, $symbol=true) {
		
		//easily convert amounts to geolocated currency.
		if ( !is_numeric($this->currencyConverter) || $this->currencyConverter == 0 ) {
			trigger_error('geoPlugin class Notice: currencyConverter has no value.', E_USER_NOTICE);
			return $amount;
		}
		if ( !is_numeric($amount) ) {
			trigger_error ('geoPlugin class Warning: The amount passed to geoPlugin::convert is not numeric.', E_USER_WARNING);
			return $amount;
		}
		if ( $symbol === true ) {
			return $this->currencySymbol . round( ($amount * $this->currencyConverter), $float );
		} else {
			return round( ($amount * $this->currencyConverter), $float );
		}
	}
	
	function nearby($radius=10, $limit=null) {

		if ( !is_numeric($this->latitude) || !is_numeric($this->longitude) ) {
			trigger_error ('geoPlugin class Warning: Incorrect latitude or longitude values.', E_USER_NOTICE);
			return array( array() );
		}
		
		$host = "http://www.geoplugin.net/extras/nearby.gp?lat=" . $this->latitude . "&long=" . $this->longitude . "&radius={$radius}";
		
		if ( is_numeric($limit) )
			$host .= "&limit={$limit}";
			
		return unserialize( $this->fetch($host) );

	}

	
}

?>
