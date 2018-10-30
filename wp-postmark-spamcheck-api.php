<?php
/**
 * WP Postmark API (http://developer.postmarkapp.com/)
 *
 * @package WP-API-Libraries\WP-Postmark-Base\WP-Postmark-Spamcheck-API
 */


 // Exit if accessed directly.
 defined( 'ABSPATH' ) || exit;

/* Check if class exists. */
if ( ! class_exists( 'PostMarkSpamcheckAPI' ) ) {

	if ( ! class_exists( 'PostMarkBase' ) ) {
		include_once( 'wp-postmark-base.php' );
	}

	/**
	 * PostMarkAPI class.
	 */
	class PostMarkSpamcheckAPI extends PostMarkBase {


		/**
		 * SpamCheck URL
		 * Docs: http://spamcheck.postmarkapp.com/doc
		 *
		 * (default value: 'http://spamcheck.postmarkapp.com')
		 *
		 * @var string
		 * @access protected
		 */
		protected $route_uri = 'http://spamcheck.postmarkapp.com';

		// Overriding constructor since is an entirely public library.
		public function __construct( $debug = false ) {

			$this->args['headers'] = array(
				'Accept' => 'application/json',
				'Content-Type' => 'application/json',
			);

			$this->debug = $debug;
		}

		/**
		 * spamcheck function.
		 *
		 * @access public
		 * @param mixed $email The raw dump of the email to be filtered, including all headers.
		 * @param mixed $options Must either be "long" for a full report of processing rules, or "short" for a score request.
		 * @return void
		 */
		public function spamcheck( $email, $options = 'short' ) {

			$args = array(
				'method' => 'POST',
				'body' => array(
					'email' => $email,
					'options' => $options,
				),
			);

			return $this->build_request( $args )->fetch( '/filter/' );
		}

	}

}
