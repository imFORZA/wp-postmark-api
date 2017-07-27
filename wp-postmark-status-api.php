<?php
/**
 * WP Postmark API (http://developer.postmarkapp.com/)
 *
 * @package WP-API-Libraries\WP-Postmark-Base\WP-Postmark-Status-API
 */


/* Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Check if class exists. */
if ( ! class_exists( 'PostMarkStatusAPI' ) ) {


	/**
	 * PostMarkStatusAPI class.
	 */
	class PostMarkStatusAPI extends PostMarkBase{


		/**
		 * Status URL
		 * Docs: https://status.postmarkapp.com/api/
		 *
		 * (default value: 'https://status.postmarkapp.com/api/')
		 *
		 * @var string
		 * @access protected
		 */
		protected $status_uri = 'https://status.postmarkapp.com/api/1.0';


		public function get_status() {
			// /status
		}

		public function get_last_incident() {

		}

		public function list_incidents() {

		}

		public function get_incident( $incident_id ) {

		}

		public function get_services_status() {
			// /services
		}

		public function get_services_availability() {
			// /services/availability
		}

		public function get_delivery_stats() {
			// /delivery
		}
	}

}
