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


	}

}
