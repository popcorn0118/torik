<?php

namespace Wpmet;

class Instagram {

	private $app_id;
	private $app_secret;
	private $redirect_url;
	private $access_token;
	private $code;

	public function __construct($app_id, $app_secret, $redirect_url, $access_token = null) {
		$this->app_id = $app_id;
		$this->app_secret = $app_secret;
		$this->redirect_url = $redirect_url;
		$this->access_token = $access_token;
	}

	public function get_login_url() {
		$client_id = $this->app_id;
		$redirect_url = $this->redirect_url;
		$scope = 'user_profile,user_media';
		$url = 'https://api.instagram.com/oauth/authorize?client_id=' . $client_id . '&redirect_uri=' . $redirect_url . '&scope=' . $scope . '&response_type=code';

		return $url;
	}
}
