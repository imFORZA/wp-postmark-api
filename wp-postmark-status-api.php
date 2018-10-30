<?php
/**
 * WP Postmark API (http://developer.postmarkapp.com/)
 *
 * @package WP-API-Libraries\WP-Postmark-Base\WP-Postmark-Status-API
 */

 // Exit if accessed directly.
 defined( 'ABSPATH' ) || exit;

/* Check if class exists. */
if ( ! class_exists( 'PostMarkStatusAPI' ) ) {


	/**
	 * PostMarkStatusAPI class.
	 */
	class PostMarkStatusAPI extends PostMarkBase {


		public function __construct( $debug = false ) {
			$this->args['headers'] = array(
				'Accept' => 'application/json',
				'Content-Type' => 'application/json',
			);

			$this->debug = $debug;
		}

		/**
		 * Status URL
		 * Docs: https://status.postmarkapp.com/api/
		 *
		 * (default value: 'https://status.postmarkapp.com/api/')
		 *
		 * @var string
		 * @access protected
		 */
		protected $route_uri = 'https://status.postmarkapp.com/api/1.0';

		/**
		 * Get current system status. Status can be UP MAINTENANCE DELAY DEGRADED DOWN.
		 */
		public function get_status() {
			return $this->build_request()->fetch( '/status/' );
		}

		/**
		 * Get last recorded incident.
		 */
		public function get_last_incident() {
			return $this->build_request()->fetch( '/last_incident/' );
		}

		/**
		 * Get list of all current and past incidents.
		 */
		public function list_incidents() {
			return $this->build_request()->fetch( '/list_incidents/' );
		}

		public function get_incident( $incident_id ) {
			return $this->build_request()->fetch( '/incidents/' . $incident_id );
		}

		public function get_services_status() {
			return $this->build_request()->fetch( '/services/' );
		}

		public function get_services_availability() {
			return $this->build_request()->fetch( '/status/availability' );
		}

		public function get_delivery_stats() {
			return $this->build_request()->fetch( '/delivery/' );
		}
	}

}
