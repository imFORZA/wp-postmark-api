<?php
/**
 * WP Postmark API (http://developer.postmarkapp.com/)
 *
 * @package WP-API-Libraries\WP-Postmark-Base\WP-Postmark-Dmarc-API
 */

/* Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Check if class exists. */
if ( ! class_exists( 'PostMarkDmarcAPI' ) ) {


	/**
	 * PostMarkAPI class.
	 */
	class PostMarkDmarcAPI extends PostMarkBase {

		/**
		 * Dmark Base URI
		 * Docs: https://dmarc.postmarkapp.com/api/
		 *
		 * (default value: 'https://dmarc.postmarkapp.com/api/')
		 *
		 * @var string
		 * @access protected
		 */
		protected $dmark_uri = 'https://dmarc.postmarkapp.com/api/';


				/**
		 * HTTP response code messages.
		 *
		 * @param  [String] $code : Response code to get message from.
		 * @return [String]       : Message corresponding to response code sent in.
		 */
		public function response_code_msg( $code = '' ) {
			switch ( $code ) {
				case 200:
					$msg = __( 'OK.', 'wp-postmark-api' );
				break;
				case 204:
					$msg = __( 'No Content.', 'wp-postmark-api' );
				break;
				case 303:
					$msg = __( 'See Other. Your request is being redirected to a different URI.', 'wp-postmark-api' );
				break;
				case 400:
					$msg = __( 'Bad Request.', 'wp-postmark-api' );
				break;
				case 422:
					$msg = __( 'Unprocessable Entity', 'wp-postmark-api' );
				break;
				case 500:
					$msg = __( 'Internal Server Error', 'wp-postmark-api' );
				break;
			}
			return $msg;
		}



		/**
		 * create_record function.
		 *
		 * @access public
		 * @param mixed $email
		 * @param mixed $domain
		 * @return void
		 */
		public function create_record( $email, $domain ) {

		}

		public function get_record() {

		}

		public function get_dns_snippet() {

		}

		public function verify_dns() {

		}

		public function delete_record() {

		}

		public function list_dmarc_reprots( $from_date = '', $to_date = '', $limit = '', $after = '' ) {

		}

		public function get_dmarc_report( $dmarc_report_id ) {

		}

		public function recover_api_token( $owner ) {
			// tokens/recover
		}

		public function rotate_api_token() {
			// /records/my/token/rotate
		}

	}

}
