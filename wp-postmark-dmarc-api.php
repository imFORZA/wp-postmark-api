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
		protected $route_uri = 'https://dmarc.postmarkapp.com';

		public function __construct( string $account_token = null ){
			$this->account_token = $account_token;
		}

		public function set_headers(){
			$this->args['headers'] = array(
				'Accept' => 'application/json',
				'Content-Type' => 'application/json',
			);

			if( !empty ( $this->account_token ) ){
				$this->args['headers']['X-Api-Token'] = $this->account_token;
			}
		}

		/**
		 * Create a record.
		 *
		 * @access public
		 * @param string $email
		 * @param string $domain
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

			return $this->build_request( $args )->fetch( '/records' );
		}

		/**
		 * Get records associated with given account key.
		 * @return [type] [description]
		 */
		public function get_record() {
			return $this->build_request()->fetch( '/records/my' );
		}

		/**
		 * Get DNS snippets for this account.
		 * @return [type] [description]
		 */
		public function get_dns_snippet() {
			return $this->build_request()->fetch( '/records/my/dns' );
		}

		/**
		 * Verify DNS records.
		 * @return [type] [description]
		 */
		public function verify_dns() {
			$args = array(
				'method' => 'POST',
			);

			return $this->build_request( $args )->fetch( '/records/my/verify' );
		}

		/**
		 * Delete my records.
		 * @return [type] [description]
		 */
		public function delete_record() {
			$args = array(
				'method' => 'DELETE',
			);

			return $this->build_request( $args )->fetch( '/records/my' );
		}

		/**
		 * List dmarc reports (with optional parameters to specify search).
		 * @param  string $from_date [description]
		 * @param  string $to_date   [description]
		 * @param  string $limit     [description]
		 * @param  string $after     [description]
		 * @return [type]            [description]
		 */
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
			return $this->build_request()->fetch( '/records/my/reports' . $dmarc_report_id );
		}

		// initiate recovery email to email provided at owner. Will return true if email was sent (aka if email is registered to something). Public route.
		public function recover_api_token( $owner ) {
			$args = array(
				'method' => 'POST',
				'body' => array(
					'owner' => $owner,
				),
			);

			return $this->build_request( $args )->fetch( '/tokens/recover' );
		}

		// generate a new api token and replcae your existing one with it.
		public function rotate_api_token() {
			$args = array(
				'method' => 'POST',
			);

			return $this->build_request( $args )->fetch( '/records/my/token/rotate' );
		}

	}

}
