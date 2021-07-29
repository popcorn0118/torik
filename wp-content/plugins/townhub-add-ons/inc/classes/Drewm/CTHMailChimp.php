<?php
/* add_ons_php */


// namespace DrewM\MailChimp;

/**
 * Super-simple, minimum abstraction MailChimp API v3 wrapper
 * MailChimp API v3: http://developer.mailchimp.com
 * This wrapper: https://github.com/drewm/mailchimp-api
 *
 * @author Drew McLellan <drew.mclellan@gmail.com>

 */
if(!class_exists('CTH_MailChimp')){
    class CTH_MailChimp
    {
        private $api_key;
        private $api_endpoint = 'https://<dc>.api.mailchimp.com/3.0';

        // if( !defined('MAILCHIMP_LOG_FILE') ) define("MAILCHIMP_LOG_FILE", "./mailchimp.log");

        /*  SSL Verification
            Read before disabling:
            http://snippets.webaware.com.au/howto/stop-turning-off-curlopt_ssl_verifypeer-and-fix-your-php-config/
        */
        public $verify_ssl = true;

        private $request_successful = false;
        private $last_error         = '';
        private $last_response      = array();
        private $last_request       = array();

        /**
         * Create a new instance
         * @param string $api_key Your MailChimp API key
         * @throws \Exception
         */
        public function __construct($api_key)
        {
            $this->api_key = $api_key;

            if (strpos($this->api_key, '-') === false) {
                throw new \Exception('Invalid MailChimp API key supplied.');
            }

            list(, $data_center) = explode('-', $this->api_key);
            $this->api_endpoint  = str_replace('<dc>', $data_center, $this->api_endpoint);

            $this->last_response = array('headers' => null, 'body' => null);
        }

        /**
         * Was the last request successful?
         * @return bool  True for success, false for failure
         */
        public function success()
        {
            return $this->request_successful;
        }

        /**
         * Get the last error returned by either the network transport, or by the API.
         * If something didn't work, this should contain the string describing the problem.
         * @return  array|false  describing the error
         */
        public function getLastError()
        {
            return $this->last_error ? $this->last_error : false;
        }

        /**
         * Get an array containing the HTTP headers and the body of the API response.
         * @return array  Assoc array with keys 'headers' and 'body'
         */
        public function getLastResponse()
        {
            return $this->last_response;
        }

        /**
         * Get an array containing the HTTP headers and the body of the API request.
         * @return array  Assoc array
         */
        public function getLastRequest()
        {
            return $this->last_request;
        }

        /**
         * Make an HTTP POST request - for creating and updating items
         * @param   string $method URL of the API request method
         * @param   array $args Assoc array of arguments (usually your data)
         * @param   int $timeout Timeout limit for request in seconds
         * @return  array|false   Assoc array of API response, decoded from JSON
         */
        public function post($method, $args = array(), $timeout = 10)
        {
            //return $this->makeRequest('post', $method, $args, $timeout);

            if(!function_exists('wp_remote_post')){
                throw new \Exception("wp_remote_post support is required, but can't be found.");
            }

            $body = json_encode($args);

            $opts = array(
                'method' => 'POST',//POST, PUT, PATCH and DELETE
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'Authorization' => 'apikey ' . $this->api_key
                ),
                'body' => $body
            );

            $url = $this->api_endpoint . '/' . $method;

            $response = wp_remote_post( $url, $opts );

            if ( is_wp_error( $response ) ) {
                $this->last_error = $response->get_error_message();
                $formattedResponse = false;
            } else {
                if (isset($response['headers'])) {
                    $this->last_request['headers'] = $response['headers'];
                }

                $formattedResponse = $this->formatResponse($response);

                $this->determineSuccess($response, $formattedResponse);

            }

            return $formattedResponse;


        }
        /**
         * Decode the response and format any error messages for debugging
         * @param array $response The response from the curl request
         * @return array|false    The JSON decoded into an array
         */
        private function formatResponse($response)
        {
            $this->last_response = $response;

            if (!empty($response['body'])) {
                return json_decode($response['body'], true);
            }

            return false;
        }

        /**
         * Check if the response was successful or a failure. If it failed, store the error.
         * @param array $response The response from the curl request
         * @param array|false $formattedResponse The response body payload from the curl request
         * @return bool     If the request was successful
         */
        private function determineSuccess($response, $formattedResponse)
        {
            $status = $this->findHTTPStatus($response, $formattedResponse);

            if ($status >= 200 && $status <= 299) {
                $this->request_successful = true;
                return true;
            }

            if (isset($formattedResponse['detail'])) {
                $this->last_error = sprintf('%d: %s', $formattedResponse['status'], $formattedResponse['detail']);
                return false;
            }

            $this->last_error = 'Unknown error, call getLastResponse() to find out what happened.';
            return false;
        }

        /**
         * Find the HTTP status code from the headers or API response body
         * @param array $response The response from the curl request
         * @param array|false $formattedResponse The response body payload from the curl request
         * @return int  HTTP status code
         */
        private function findHTTPStatus($response, $formattedResponse)
        {
            if (!empty($response['response']) && isset($response['response']['code'])) {
                return (int) $response['response']['code'];
            }

            if (!empty($response['body']) && isset($formattedResponse['status'])) {
                return (int) $formattedResponse['status'];
            }

            return 418;
        }
    }

}