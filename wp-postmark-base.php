<?php
/**
 * WP Postmark API (http://developer.postmarkapp.com/)
 *
 * @package WP-API-Libraries\WP-Postmark-Base
 */

 // Exit if accessed directly.
 defined( 'ABSPATH' ) || exit;

/* Check if class exists. */
if ( ! class_exists( 'PostMarkBase' ) ) {


	/**
	 * PostMarkAPI class.
	 */
	class PostMarkBase {

		/**
		 * BaseAPI Endpoint
		 *
		 * @var string
		 * @access protected
		 */
		protected $route_uri = 'https://api.postmarkapp.com';

		/**
		 * blackhole_email
		 *
		 * (default value: 'test@blackhole.postmarkapp.com')
		 *
		 * @var string
		 * @access protected
		 */
		protected $blackhole_email = 'test@blackhole.postmarkapp.com';

		/**
		 * Arguments to be used by fetch.
		 */
		protected $args = array();

		protected $account_token;

		protected $server_token;

		private $debug;

		/**
		 * __construct function.
		 *
		 * @access public
		 * @param mixed $account_token
		 * @param mixed $server_token
		 * @return void
		 */
		public function __construct( $account_token, $server_token = '', bool $debug = false ) {
			$this->account_token = $account_token;
			$this->server_token = $server_token;
			$this->debug = $debug;
		}

		/**
		 * Fetch the request from the API.
		 *
		 * @access private
		 * @param mixed $request Request URL.
		 * @return $body Body.
		 */
		protected function fetch( $route ) {

			$response = wp_remote_request( $this->route_uri . $route, $this->args );

			if ( $this->debug ) {
				return $response;
			}

			$code = wp_remote_retrieve_response_code( $response );
			$body = json_decode( wp_remote_retrieve_body( $response ) );

			$this->clear();

			if ( ! $this->is_status_ok( $code ) ) {
				return new WP_Error( 'response-error', sprintf( __( 'Status: %d', 'wp-postmark-api' ), $code ), $body );
			}

			return $body;
		}

		protected function build_request( $args = array() ) {
			$this->set_headers();

			// Setting arguments based passsed array.
			$this->args = wp_parse_args( $args, $this->args );

			if ( $this->debug ) { // Prevents spam emails during debug mode.
				if ( isset( $this->args['body'] ) && isset( $this->args['body']['To'] ) ) {
					$this->args['body']['To'] = $this->blackhole_email;
				}
			}

			if ( isset( $args['body'] ) && gettype( $args['body'] ) !== 'string' ) {

				$this->args['body'] = wp_json_encode( $this->args['body'] );
			}

			return $this;
		}

		/**
		 * Clear query data.
		 */
		protected function clear() {
			$this->args = array();
		}

		protected function set_headers() {
			$this->args['headers'] = array(
				'Accept' => 'application/json',
				'Content-Type' => 'application/json',
			);

			if ( $this->server_token == '' ) {
				$this->args['headers']['X-Postmark-Account-Token'] = $this->account_token;
			} else {
				$this->args['headers']['X-Postmark-Server-Token'] = $this->server_token;
			}
		}

		/**
		 * Check if HTTP status code is a success.
		 *
		 * @param  int $code HTTP status code.
		 * @return boolean       True if status is within valid range.
		 */
		protected function is_status_ok( $code ) {
			return ( 200 <= $code && 300 > $code );
		}

		/**
		 * Set account token.
		 * Unsets server API token.
		 *
		 * @param string $token Account token.
		 */
		public function set_account_token( $token ) {
			// I think this is how it's supposed to work?
			$this->args['headers']['X-Postmark-Account-Token'] = $token;
			unset( $this->args['headers']['X-Postmark-Server-Token'] );
		}

		/**
		 * Set server token.
		 * Unsets account API token.
		 *
		 * @param string $token Server API token.
		 */
		public function set_server_token( $token ) {
			// Not 100% sure tbh.
			$this->args['headers']['X-Postmark-Server-Token'] = $token;
			unset( $this->args['headers']['X-Postmark-Account-Token'] );
		}

		/**
		 * HTTP response code messages.
		 *
		 * @param  int $code 			: Response code to get message from.
		 * @return [String]       : Message corresponding to response code sent in.
		 */
		public function response_code_msg( $code = '' ) {
			switch ( $code ) {
				case 200:
					$msg = __( 'Success.', 'wp-postmark-api' );
				break;
				case 401:
					$msg = __( 'Unauthorized: Missing or incorrect API token in header.', 'wp-postmark-api' );
				break;
				case 422:
					$msg = __( 'Unprocessable Entity: Something with the message isn’t quite right, this could be malformed JSON or incorrect fields.', 'wp-postmark-api' );
				break;
				case 500:
					$msg = __( 'Internal Server Error: This is an issue with Postmark’s servers processing your request. In most cases the message is lost during the process, and we are notified so that we can investigate the issue.', 'wp-postmark-api' );
				break;
				case 503:
					$msg = __( 'Service Unavailable: During planned service outages, Postmark API services will return this HTTP response and associated JSON body.', 'wp-postmark-api' );
				break;
			}
			return $msg;
		}


		/**
		 * Function to get API response messages function.
		 *
		 * @access public
		 *
		 * @param  int $code 			: Response code to get message from.
		 * @return [String]       : Message corresponding to response code sent in.
		 */
		public function api_code_msg( $code = '' ) {
			switch ( $code ) {
				case 10:
					$msg = __( 'Bad or missing API token.', 'wp-postmark-api' );
				break;
				case 100:
					$msg = __( 'Maintenance', 'wp-postmark-api' );
				break;
				case 300:
					$msg = __( ' Invalid email request', 'wp-postmark-api' );
				break;
				case 400:
					$msg = __( 'Sender Signature not found', 'wp-postmark-api' );
				break;
				case 401:
					$msg = __( 'Sender signature not confirmed', 'wp-postmark-api' );
				break;
				case 402:
					$msg = __( 'Invalid JSON', 'wp-postmark-api' );
				break;
				case 403:
					$msg = __( 'Incompatible JSON', 'wp-postmark-api' );
				break;
				case 405:
					$msg = __( 'Not allowed to send', 'wp-postmark-api' );
				break;
				case 406:
					$msg = __( 'Inactive recipient', 'wp-postmark-api' );
				break;
				case 409:
					$msg = __( 'JSON required', 'wp-postmark-api' );
				break;
				case 410:
					$msg = __( 'Too many batch messages', 'wp-postmark-api' );
				break;
				case 411:
					$msg = __( 'Forbidden attachment type', 'wp-postmark-api' );
				break;
				case 412:
					$msg = __( 'Account is Pending', 'wp-postmark-api' );
				break;
				case 413:
					$msg = __( 'Account May Not Send', 'wp-postmark-api' );
				break;
				case 500:
					$msg = __( 'Sender signature query exception', 'wp-postmark-api' );
				break;
				case 501:
					$msg = __( 'Sender Signature not found by id', 'wp-postmark-api' );
				break;
				case 502:
					$msg = __( 'No updated Sender Signature data received', 'wp-postmark-api' );
				break;
				case 503:
					$msg = __( 'You cannot use a public domain', 'wp-postmark-api' );
				break;
				case 504:
					$msg = __( 'Sender Signature already exists', 'wp-postmark-api' );
				break;
				case 505:
					$msg = __( 'DKIM already scheduled for renewal', 'wp-postmark-api' );
				break;
				case 506:
					$msg = __( 'This Sender Signature already confirmed', 'wp-postmark-api' );
				break;
				case 507:
					$msg = __( 'You do not own this Sender Signature', 'wp-postmark-api' );
				break;
				case 510:
					$msg = __( 'This domain was not found', 'wp-postmark-api' );
				break;
				case 511:
					$msg = __( 'Invalid fields supplied', 'wp-postmark-api' );
				break;
				case 512:
					$msg = __( 'Domain already exists', 'wp-postmark-api' );
				break;
				case 513:
					$msg = __( 'You do not own this Domain', 'wp-postmark-api' );
				break;
				case 514:
					$msg = __( 'Name is a required field to create a Domain', 'wp-postmark-api' );
				break;
				case 515:
					$msg = __( 'Name field must be less than or equal to 255 characters', 'wp-postmark-api' );
				break;
				case 516:
					$msg = __( 'Name format is invalid', 'wp-postmark-api' );
				break;
				case 520:
					$msg = __( 'You are missing a required field to create a Sender Signature.', 'wp-postmark-api' );
				break;
				case 521:
					$msg = __( 'A field in the Sender Signature request is too long.', 'wp-postmark-api' );
				break;
				case 522:
					$msg = __( 'Value for field is invalid.', 'wp-postmark-api' );
				break;
				case 600:
					$msg = __( 'Server query exception', 'wp-postmark-api' );
				break;
				case 601:
					$msg = __( 'Server does not exist', 'wp-postmark-api' );
				break;
				case 602:
					$msg = __( 'Duplicate Inbound Domain', 'wp-postmark-api' );
				break;
				case 603:
					$msg = __( 'Server name already exists', 'wp-postmark-api' );
				break;
				case 604:
					$msg = __( 'You don’t have delete access', 'wp-postmark-api' );
				break;
				case 605:
					$msg = __( 'Unable to delete Server', 'wp-postmark-api' );
				break;
				case 606:
					$msg = __( 'Invalid webhook URL', 'wp-postmark-api' );
				break;
				case 607:
					$msg = __( 'Invalid Server color', 'wp-postmark-api' );
				break;
				case 608:
					$msg = __( 'Server name missing or invalid', 'wp-postmark-api' );
				break;
				case 609:
					$msg = __( 'No updated Server data received', 'wp-postmark-api' );
				break;
				case 610:
					$msg = __( 'Invalid MX record for Inbound Domain', 'wp-postmark-api' );
				break;
				case 611:
					$msg = __( 'InboundSpamThreshold value is invalid. Please use a number between 0 and 30 in incrememts of 5.', 'wp-postmark-api' );
				break;
				case 700:
					$msg = __( 'Messages query exception: You provided invalid querystring parameters in your request. Refer to the Messages (http://developer.postmarkapp.com/developer-api-messages.html) API reference for a list of accepted querystring parameters.', 'wp-postmark-api' );
				break;
				case 701:
					$msg = __( 'Message doesn’t exist', 'wp-postmark-api' );
				break;
				case 702:
					$msg = __( 'Could not bypass this blocked inbound message, please contact support.', 'wp-postmark-api' );
				break;
				case 703:
					$msg = __( 'Could not retry this failed inbound message, please contact support.', 'wp-postmark-api' );
				break;
				case 800:
					$msg = __( 'Trigger query exception', 'wp-postmark-api' );
				break;
				case 801:
					$msg = __( 'Trigger for this tag doesn’t exist', 'wp-postmark-api' );
				break;
				case 803:
					$msg = __( ' Tag with this name already has trigger associated with it', 'wp-postmark-api' );
				break;
				case 808:
					$msg = __( 'Name to match is missing', 'wp-postmark-api' );
				break;
				case 809:
					$msg = __( 'No trigger data received', 'wp-postmark-api' );
				break;
				case 810:
					$msg = __( 'This inbound rule already exists.', 'wp-postmark-api' );
				break;
				case 811:
					$msg = __( 'Unable to remove this inbound rule, please contact support.', 'wp-postmark-api' );
				break;
				case 812:
					$msg = __( 'This inbound rule was not found.', 'wp-postmark-api' );
				break;
				case 813:
					$msg = __( 'Not a valid email address or domain.', 'wp-postmark-api' );
				break;
				case 900:
					$msg = __( 'Stats query exception', 'wp-postmark-api' );
				break;
				case 1000:
					$msg = __( 'Bounces query exception', 'wp-postmark-api' );
				break;
				case 1001:
					$msg = __( 'Bounce was not found.', 'wp-postmark-api' );
				break;
				case 1002:
					$msg = __( ' BounceID parameter required.', 'wp-postmark-api' );
				break;
				case 1003:
					$msg = __( 'Cannot activate bounce.', 'wp-postmark-api' );
				break;
				case 1100:
					$msg = __( 'Template query exception', 'wp-postmark-api' );
				break;
				case 1101:
					$msg = __( 'TemplateId not found.', 'wp-postmark-api' );
				break;
				case 1105:
					$msg = __( 'Template limit would be exceeded.', 'wp-postmark-api' );
				break;
				case 1109:
					$msg = __( 'No Template data received.', 'wp-postmark-api' );
				break;
				case 1120:
					$msg = __( 'A required Template field is missing.', 'wp-postmark-api' );
				break;
				case 1121:
					$msg = __( 'Template field is too large.', 'wp-postmark-api' );
				break;
				case 1122:
					$msg = __( 'A Templated field has been submitted that is invalid.', 'wp-postmark-api' );
				break;
				case 1123:
					$msg = __( 'A field was included in the request body that is not allowed.', 'wp-postmark-api' );
				break;
			}
			return $msg;
		}
	}
}
