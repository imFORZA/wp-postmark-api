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
		 * Route: https://dmarc.postmarkapp.com/
		 *
		 * (default value: 'https://dmarc.postmarkapp.com/')
		 *
		 * @var string
		 * @access protected
		 */
		protected $dmark_uri = 'https://dmarc.postmarkapp.com/';


		/*
		 * HTTP response code messages.
		 * Commented out until it's different from base class.
		 *
		 * @param  [String] $code : Response code to get message from.
		 * @return [String]       : Message corresponding to response code sent in.
		 */
		// public function response_code_msg( $code = '' ) {
		// 	switch ( $code ) {
		// 		case 200:
		// 			$msg = __( 'OK.', 'wp-postmark-api' );
		// 		break;
		// 		case 204:
		// 			$msg = __( 'No Content.', 'wp-postmark-api' );
		// 		break;
		// 		case 303:
		// 			$msg = __( 'See Other. Your request is being redirected to a different URI.', 'wp-postmark-api' );
		// 		break;
		// 		case 400:
		// 			$msg = __( 'Bad Request.', 'wp-postmark-api' );
		// 		break;
		// 		case 422:
		// 			$msg = __( 'Unprocessable Entity', 'wp-postmark-api' );
		// 		break;
		// 		case 500:
		// 			$msg = __( 'Internal Server Error', 'wp-postmark-api' );
		// 		break;
		// 	}
		// 	return $msg;
		// }



		/**
		 * create_record function.
		 *
		 * @access public
		 * @param mixed $email
		 * @param mixed $domain
		 * @return void
		 */
		public function create_record( $email, $domain ) {
			$args = array(
				'method' => 'POST',
				'body' => array(
					'email' => $email,
					'domain' => $domain,
				),
			);

			return $this->build_request( $args )->fetch( '/records/' );
		}

		public function get_record() {
			return $this->build_request()->fetch( '/records/my/' );
		}

		public function get_dns_snippet() {
			return $this->build_request()->fetch( '/records/my/dns/' );
		}

		public function verify_dns() {
			$args = array(
				'method' => 'POST',
			);

			return $this->build_request( $args )->fetch( '/records/my/verify/' );
		}

		public function delete_record() {
			$args = array(
				'method' => 'DELETE',
			);

			return $this->build_request( $args )->fetch( '/records/my/' );
		}

		public function list_dmarc_reports( $from_date = '', $to_date = '', $limit = '', $after = '' ) {

			$request = '';
			if(	$from_date === '' &&	$to_date === '' &&	$limit === '' &&	$after === '' ) {
				$request = '/records/my/reports';
			}else{
				$args = array(
					'from_date' => $from_date,
					'to_date' => $to_date,
					'limit' => $limit,
					'after' => $after,
				);

				$request = '/records/my/reports?' . http_build_query( array_filter( $args ) );
			}

			return $request;
		}

		public function get_dmarc_report( $dmarc_report_id ) {
			return $this->build_request()->fetch( '/records/my/reports/' . $dmarc_report_id );
		}

		// initiate recovery email to email provided at owner. Will return true if email was sent (aka if email is registered to something). Public route.
		public function recover_api_token( $owner ) {
			$args = array(
				'method' => 'POST',
				'body' => array(
					'owner' => $owner,
				),
			);

			return $this->build_request( $args )->fetch( '/tokens/recover/' );
		}

		// generate a new api token and replcae your existing one with it.
		public function rotate_api_token() {
			$args = array(
				'method' => 'POST',
			);

			return $this->build_request( $args )->fetch( '/records/my/token/rotate/' );
		}

	}

}
