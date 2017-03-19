<?php
/**
 * WP Postmark API (http://developer.postmarkapp.com/)
 *
 * @package WP-Postmark-API
 */

/*
* Plugin Name: WP Postmark API
* Plugin URI: https://github.com/wp-api-libraries/wp-postmark-api
* Description: Perform API requests to Postmark in WordPress.
* Author: WP API Libraries
* Version: 1.0.0
* Author URI: https://wp-api-libraries.com
* GitHub Plugin URI: https://github.com/wp-api-libraries/wp-postmark-api
* GitHub Branch: master
*/

/* Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Check if class exists. */
if ( ! class_exists( 'PostMarkAPI' ) ) {


	/**
	 * PostMarkAPI class.
	 */
	class PostMarkAPI {

		/**
		 * BaseAPI Endpoint
		 *
		 * @var string
		 * @access protected
		 */
		protected $base_uri = 'https://api.postmarkapp.com';


		public function __construct(  ) {


			$this->args['headers'] = array(
				'Content-Type' => 'application/json',
				'X-Postmark-Account-Token' => '',
				'X-Postmark-Server-Token' => ''
			);
		}

		/**
		 * Fetch the request from the API.
		 *
		 * @access private
		 * @param mixed $request Request URL.
		 * @return $body Body.
		 */
		private function fetch( $request ) {

			$response = wp_remote_request( $request, $this->args );

			var_dump($response);

			$code = wp_remote_retrieve_response_code($response );
			if ( 200 !== $code ) {
				return new WP_Error( 'response-error', sprintf( __( 'Server response code: %d', 'text-domain' ), $code ) );
			}
			$body = wp_remote_retrieve_body( $response );
			return json_decode( $body );
		}

		/* EMAIL. */

		/**
		 * Send a Single Email.
		 *
		 * @access public
		 * @param mixed $from [REQUIRED] The sender email address. Must have a registered and confirmed Sender Signature.
		 * @param mixed $to [REQUIRED] Recipient email address. Multiple addresses are comma seperated. Max 50.
		 * @param mixed $cc Cc recipient email address. Multiple addresses are comma seperated. Max 50.
		 * @param mixed $bcc Bcc recipient email address. Multiple addresses are comma seperated. Max 50.
		 * @param mixed $subject Email subject
		 * @param mixed $tag Email tag that allows you to categorize outgoing emails and get detailed statistics.
		 * @param mixed $html_body [REQUIRED] HTML email message (If no TextBody specified)
		 * @param mixed $text_body [REQUIRED] Plain text email message (If no HtmlBody specified)
		 * @param mixed $replyto Reply To override email address. Defaults to the Reply To set in the sender signature.
		 * @param mixed $headers List of custom headers to include.
		 * @param mixed $track_opens Activate open tracking for this email.
		 * @param mixed $track_links Activate link tracking for links in the HTML or Text bodies of this email.
		 * @param mixed $attachments 	List of attachments
		 * @return void
		 */
		public function send_email( $from, $to, $cc, $bcc, $subject, $tag, $html_body, $text_body, $replyto, $headers, $track_opens, $track_links, $attachments ) {

		}

		/**
		 * Send Batch Emails.
		 *
		 * @access public
		 * @param mixed $from [REQUIRED] The sender email address. Must have a registered and confirmed Sender Signature.
		 * @param mixed $to [REQUIRED] Recipient email address. Multiple addresses are comma seperated. Max 50.
		 * @param mixed $cc Cc recipient email address. Multiple addresses are comma seperated. Max 50.
		 * @param mixed $bcc Bcc recipient email address. Multiple addresses are comma seperated. Max 50.
		 * @param mixed $subject Email subject
		 * @param mixed $tag Email tag that allows you to categorize outgoing emails and get detailed statistics.
		 * @param mixed $html_body [REQUIRED] HTML email message (If no TextBody specified)
		 * @param mixed $text_body [REQUIRED] Plain text email message (If no HtmlBody specified)
		 * @param mixed $replyto Reply To override email address. Defaults to the Reply To set in the sender signature.
		 * @param mixed $headers List of custom headers to include.
		 * @param mixed $track_opens Activate open tracking for this email.
		 * @param mixed $track_links Activate link tracking for links in the HTML or Text bodies of this email.
		 * @param mixed $attachments 	List of attachments
		 * @return void
		 */
		public function send_batch_emails( $from, $to, $cc, $bcc, $subject, $tag, $html_body, $text_body, $replyto, $headers, $track_opens, $track_links, $attachments ) {

		}

		/* BOUNCE. */

		public function get_delivery_stats() {

		}

		public function get_bounces( $count, $offset, $type, $inactive, $email_filter, $tag, $message_id, $from_date, $to_date ) {

		}

		public function get_bounce( $bounce_id ) {

		}

		public function get_bounce_dump( $bounce_id ) {

		}

		public function activate_bounce( $bounce_id ) {

		}

		public function get_bounced_tags() {

		}

		/* TEMPLATES. */

		public function get_templates( $count, $offset ) {

		}

		public function get_template( $template_id, $subject, $html_body, $text_body, $associated_server_id, $active ) {

		}

		public function create_template() {

		}

		public function edit_template( $template_id, $subject, $html_body, $text_body ) {

		}

		public function validate_template() {

		}

		public function send_email_with_template() {

		}

		/* SERVERS. */

		/**
		 * get_server function.
		 *
		 * @access public
		 * @return void
		 */
		public function get_the_server() {

		}

		/**
		 * edit_server function.
		 *
		 * @access public
		 * @param mixed $name
		 * @param mixed $color
		 * @param mixed $raw_email_enabled
		 * @param mixed $smtp_api_activated
		 * @param mixed $inbound_hook_url
		 * @param mixed $bounce_hook_url
		 * @param mixed $open_hook_url
		 * @param mixed $post_first_open_only
		 * @param mixed $track_opens
		 * @param mixed $track_links
		 * @param mixed $inbound_domain
		 * @param mixed $inbound_spam_threshold
		 * @return void
		 */
		public function edit_the_server( $name, $color, $raw_email_enabled, $smtp_api_activated, $inbound_hook_url, $bounce_hook_url, $open_hook_url, $post_first_open_only, $track_opens, $track_links, $inbound_domain, $inbound_spam_threshold ) {

		}

		public function get_server( $server_id ) {

		}

		public function add_server( $name, $color, $raw_email_enabled, $smtp_api_activated, $inbound_hook_url, $bounce_hook_url, $open_hook_url, $post_first_open_only, $track_opens, $track_links, $inbound_domain, $inbound_spam_threshold ) {

		}

		public function list_servers( $count, $offset, $name = null ) {

		}

		public function delete_server( $server_id ) {

		}


		/* MESSAGES. */

		/**
		 * search_message_opens function.
		 *
		 * @access public
		 * @param mixed $count
		 * @param mixed $offset
		 * @param mixed $recipient
		 * @param mixed $tag
		 * @param mixed $client_name
		 * @param mixed $client_company
		 * @param mixed $client_family
		 * @param mixed $os_name
		 * @param mixed $os_family
		 * @param mixed $os_company
		 * @param mixed $platform
		 * @param mixed $country
		 * @param mixed $region
		 * @param mixed $city
		 * @return void
		 */
		public function search_message_opens( $count, $offset, $recipient, $tag, $client_name, $client_company, $client_family, $os_name, $os_family, $os_company, $platform, $country, $region, $city ) {

		}

		/**
		 * get_message_opens function.
		 *
		 * @access public
		 * @param mixed $message_id
		 * @param mixed $count
		 * @param mixed $offset
		 * @return void
		 */
		public function get_message_opens( $message_id, $count, $offset ) {

		}

		/* DOMAINS. */

		public function list_domains() {

		}
		public function get_domain_details() {

		}

		public function add_domain() {

		}

		public function edit_domain() {

		}

		public function delete_domain() {

		}

		public function verify_spf_record() {

		}

		public function rotate_dkim_keys() {

		}

		/* SENDER SIGNATURES */

		public function list_sender_signatures() {

		}

		public function get_sender_signatures_details() {

		}

		public function create_signature() {

		}

		public function edit_signature() {

		}

		public function delete_signature() {

		}

		public function resend_confirmation() {

		}

		public function verify_sender_sig_spf_record() {

		}

		/* STATS. */

		public function get_outbound_stats( $tag, $fromdate, $todate ) {

		}

		public function get_send_counts() {

		}

		public function get_bounce_counts() {

		}

		public function get_spam_complaints() {

		}

		public function get_tracked_email_counts() {

		}

		public function get_email_open_counts() {

		}

		public function get_email_platform_usage() {

		}

		public function get_email_client_usage() {

		}

		public function get_email_read_times() {

		}

		public function get_click_counts() {

		}

		public function get_browser_usage() {

		}

		public function get_browser_platform_usage() {

		}

		public function get_click_location() {

		}

		/* TRIGGERS. */

		public function create_trigger_for_tag() {

		}

		public function get_single_trigger() {

		}

		public function edit_single_trigger() {

		}

		public function delete_single_trigger() {

		}

		public function search_triggers() {

		}

		/* Inbound Rules Triggers */

		public function create_trigger_for_inbound_rule() {

		}

		public function delete_single_inbound_trigger() {

		}

		public function list_inbound_triggers() {

		}

		/* WEBHOOKS. */


	}

}
