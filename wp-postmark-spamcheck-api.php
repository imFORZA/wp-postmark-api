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


	}

}
