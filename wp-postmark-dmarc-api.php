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
