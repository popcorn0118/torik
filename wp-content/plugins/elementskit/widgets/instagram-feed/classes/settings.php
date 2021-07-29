<?php

class Ekit_instagram_settings {


	private $user_id;
	private $user_access_token;


	public function setup(array $config) {

		$this->user_id = $config['user_id'];

		$this->user_access_token = $config['token'];
	}


	public function get_time_ago($time) {
		$time_difference = time() - $time;

		if($time_difference < 1) {
			return '1 sec';
		}

		$condition = [
			12 * 30 * 24 * 60 * 60 => 'y',
			30 * 24 * 60 * 60      => 'mth',
			24 * 60 * 60           => 'd',
			60 * 60                => 'hrs.',
			60                     => 'min',
			1                      => 'sec',
		];

		foreach($condition as $secs => $str) {
			$d = $time_difference / $secs;

			if($d >= 1) {
				$t = round($d);

				return '' . $t . ' ' . $str . ($t > 1 ? 's' : '') . ' ';
			}
		}
	}



	public function check_for_token_validity($current_token) {

		$expires = get_option('ekit_pro_instagram_access_token_cache', []);

		if(empty($expires)) {

			$md5 = md5($current_token);

			$expires[$md5] = time();

			$curl = curl_init();

			$url = 'graph.facebook.com/v10.0/debug_token?input_token='.$current_token;

			curl_setopt_array($curl, array(
				CURLOPT_URL            => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING       => "",
				CURLOPT_MAXREDIRS      => 10,
				CURLOPT_TIMEOUT        => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST  => "GET",

			));

			$response = curl_exec($curl);
			curl_close($curl);

			$output = json_decode($response);
		}

	}
}
