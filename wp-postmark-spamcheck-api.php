<?php
/**
 * WP Postmark API (http://developer.postmarkapp.com/)
 *
 * @package WP-API-Libraries\WP-Postmark-Base\WP-Postmark-Spamcheck-API
 */


/* Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Check if class exists. */
if ( ! class_exists( 'PostMarkSpamcheckAPI' ) ) {


	/**
	 * PostMarkAPI class.
	 */
	class PostMarkSpamcheckAPI extends PostMarkBase{


		/**
		 * SpamCheck URL
		 * Docs: http://spamcheck.postmarkapp.com/doc
		 *
		 * (default value: 'http://spamcheck.postmarkapp.com')
		 *
		 * @var string
		 * @access protected
		 */
		protected $spamcheck_uri = 'http://spamcheck.postmarkapp.com';


		/**
		 * spamcheck function.
		 *
		 * @access public
		 * @param mixed $email The raw dump of the email to be filtered, including all headers.
		 * @param mixed $options Must either be "long" for a full report of processing rules, or "short" for a score request.
		 * @return void
		 */
		public function spamcheck( $email, $options ) {

			// http://spamcheck.postmarkapp.com/filter

		}

	}

}
