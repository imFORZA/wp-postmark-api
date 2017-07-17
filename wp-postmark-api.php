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


		public function __construct( $account_token, $server_token ) {


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
				return new WP_Error( 'response-error', sprintf( __( 'Server response code: %d', 'wp-postmark-api' ), $code ) );
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
		public function send_email( $from, $to, $cc, $bcc, $subject, $tag, $html_body, $text_body, $reply_to, $headers, $track_opens, $track_links, $attachments ) {

			$args = array(
					'method' => 'POST',
					'timeout' => 45,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking' => true,
					'headers' => array(),
					'body' => array(
						'From' => $from,
						'To' => $to,
						'Cc' => $cc,
						'Bcc' => $bcc,
						'Subject' => $subject,
						'Tag' => $tag,
						'HtmlBody' => $html_body,
						'TextBody' => $text_body,
						'ReplyTo' => $reply_to,
						'Headers' => $headers,
						'TrackOpens' => $track_opens,
						'TrackLinks' => $track_links,
						'Attachments' => $attachments
						),
					'cookies' => array()
			);

			$response = wp_remote_post( $this->base_uri . '/email' , $args );

			return $response;


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


		/**
		 * get_delivery_stats function.
		 *
		 * @access public
		 * @return void
		 */
		public function get_delivery_stats() {

				$request = $this->base_uri . '/deliverystats';
				return $this->fetch( $request );

		}

		/**
		 * get_bounces function.
		 *
		 * @access public
		 * @param mixed $count
		 * @param mixed $offset
		 * @param string $type (default: '')
		 * @param string $inactive (default: '')
		 * @param string $email_filter (default: '')
		 * @param string $tag (default: '')
		 * @param string $message_id (default: '')
		 * @param string $from_date (default: '')
		 * @param string $to_date (default: '')
		 * @return void
		 */
		public function get_bounces( $count, $offset, $type = '', $inactive = '', $email_filter = '', $tag = '', $message_id = '', $from_date = '', $to_date = '' ) {

				$request = $this->base_uri . '/bounces';
				return $this->fetch( $request );

		}

		/**
		 * get_bounce function.
		 *
		 * @access public
		 * @param mixed $bounce_id
		 * @return void
		 */
		public function get_bounce( $bounce_id ) {

				$request = $this->base_uri . '/bounces/' . $bounce_id;
				return $this->fetch( $request );

		}

		/**
		 * get_bounce_dump function.
		 *
		 * @access public
		 * @param mixed $bounce_id
		 * @return void
		 */
		public function get_bounce_dump( $bounce_id ) {

				$request = $this->base_uri . '/bounces/'.$bounce_id.'/dump';
				return $this->fetch( $request );

		}

		/**
		 * activate_bounce function.
		 *
		 * @access public
		 * @param mixed $bounce_id
		 * @return void
		 */
		public function activate_bounce( $bounce_id ) {

				$request = $this->base_uri . '/bounces/'.$bounce_id.'/activate';
				return $this->fetch( $request );

		}

		/**
		 * get_bounced_tags function.
		 *
		 * @access public
		 * @return void
		 */
		public function get_bounced_tags() {

				$request = $this->base_uri . '/bounces/tags';
				return $this->fetch( $request );

		}

		/* TEMPLATES. */

		/**
		 * get_templates function.
		 *
		 * @access public
		 * @param mixed $count
		 * @param mixed $offset
		 * @return void
		 */
		public function get_templates( $count, $offset ) {

				$request = $this->base_uri . '/templatess?count='.$count.'&offset=' . $offset;
				return $this->fetch( $request );

		}

		/**
		 * get_template function.
		 *
		 * @access public
		 * @param mixed $template_id
		 * @return void
		 */
		public function get_template( $template_id ) {

				$request = $this->base_uri . '/templates/' . $template_id;
				return $this->fetch( $request );

		}

		public function create_template() {

			// POST https://api.postmarkapp.com/templates

		}

		public function edit_template( $template_id, $subject, $html_body, $text_body ) {
			// PUT /templates/{templateid}
		}

		public function validate_template() {
			// POST /templates/validate
		}

		public function delete_template() {
			// DELETE /templates/{templateid}
		}

		public function send_email_with_template() {
			// POST /email/withTemplate/
		}

		/* SERVERS. */

		/**
		 * get_server function.
		 *
		 * @access public
		 * @return void
		 */
		public function get_the_server() {

			$request = $this->base_uri . '/server';
			return $this->fetch( $request );

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
			// PUT /server
		}

		/**
		 * get_server function.
		 *
		 * @access public
		 * @param mixed $server_id
		 * @return void
		 */
		public function get_server( $server_id ) {

			$request = $this->base_uri . '/servers/' . $server_id;
			return $this->fetch( $request );

		}

		public function add_server( $name, $color, $raw_email_enabled, $smtp_api_activated, $inbound_hook_url, $bounce_hook_url, $open_hook_url, $post_first_open_only, $track_opens, $track_links, $inbound_domain, $inbound_spam_threshold ) {
			// POST /servers
		}

		public function edit_server() {
			// PUT /servers/{serverid}
		}

		/**
		 * list_servers function.
		 *
		 * @access public
		 * @param mixed $count
		 * @param mixed $offset
		 * @param mixed $name (default: null)
		 * @return void
		 */
		public function list_servers( $count, $offset, $name = null ) {

			$request = $this->base_uri . '/servers?count='.$count.'&offset=' . $offset . '&name=' . $name;
			return $this->fetch( $request );

		}

		public function delete_server( $server_id ) {
			// DELETE /servers/{serverid}
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

		/**
		 * list_domains function.
		 *
		 * @access public
		 * @param int $count (default: 500)
		 * @param int $offset (default: 0)
		 * @return void
		 */
		public function list_domains( $count = 500, $offset = 0 ) {

			$request = $this->base_uri . '/domains?count='.$count.'&offset=' . $offset;
			return $this->fetch( $request );

		}

		/**
		 * get_domain_details function.
		 *
		 * @access public
		 * @param mixed $domain_id
		 * @return void
		 */
		public function get_domain_details( $domain_id ) {

			$request = $this->base_uri . '/domains/'. $domain_id;
			return $this->fetch( $request );

		}

		public function add_domain() {
			// POST /domains
		}

		public function edit_domain() {
			// PUT /domains/{domainid}
		}

		public function delete_domain() {
			// DELETE /domains/{domainid}
		}

		public function verify_spf_record() {
			// POST /domains/{domainid}/verifyspf
		}

		public function rotate_dkim_keys() {
			// POST /domains/{domainid}/rotatedkim
		}

		/* SENDER SIGNATURES */

		/**
		 * list_sender_signatures function.
		 *
		 * @access public
		 * @return void
		 */
		public function list_sender_signatures() {

			$request = $this->base_uri . '/senders';
			return $this->fetch( $request );

		}

		/**
		 * get_sender_signatures_details function.
		 *
		 * @access public
		 * @param mixed $signature_id
		 * @return void
		 */
		public function get_sender_signatures_details( $signature_id ) {

			$request = $this->base_uri . '/senders/'. $signature_id;
			return $this->fetch( $request );

		}

		public function create_signature() {
			// POST /senders
		}

		public function edit_signature() {
			// PUT /senders/{signatureid}
		}

		public function delete_signature() {
			// DELETE /senders/{signatureid}
		}

		public function resend_confirmation() {
			// POST /senders/{signatureid}/resend
		}


		/* STATS. */

		public function get_outbound_stats( $tag, $from_date, $to_date ) {
			$request = $this->base_uri . '/stats/outbound';
			return $this->fetch( $request );
		}

		public function get_send_counts() {
			$request = $this->base_uri . '/stats/outbound/sends';
			return $this->fetch( $request );
		}

		public function get_bounce_counts() {
			$request = $this->base_uri . '/stats/outbound/bounce';
			return $this->fetch( $request );
		}

		public function get_spam_complaints() {
			$request = $this->base_uri . '/stats/outbound/spam';
			return $this->fetch( $request );
		}

		public function get_tracked_email_counts() {
			$request = $this->base_uri . '/stats/outbound/tracked';
			return $this->fetch( $request );
		}

		public function get_email_open_counts() {
			$request = $this->base_uri . '/stats/outbound/opens';
			return $this->fetch( $request );
		}

		public function get_email_platform_usage() {
			$request = $this->base_uri . '/stats/outbound/opens/platforms';
			return $this->fetch( $request );
		}

		public function get_email_client_usage() {
			$request = $this->base_uri . '/stats/outbound/opens/emailclients';
			return $this->fetch( $request );
		}

		public function get_email_read_times() {
			$request = $this->base_uri . '/stats/outbound/opens/readtimes';
			return $this->fetch( $request );
		}

		public function get_click_counts() {
			$request = $this->base_uri . '/stats/outbound/clicks';
			return $this->fetch( $request );
		}

		public function get_browser_usage() {
			$request = $this->base_uri . '/stats/outbound/clicks/browserfamilies';
			return $this->fetch( $request );
		}

		public function get_browser_platform_usage() {
			$request = $this->base_uri . '/stats/outbound/clicks/platforms';
			return $this->fetch( $request );
		}

		public function get_click_location() {
			$request = $this->base_uri . '/stats/outbound/clicks/location';
			return $this->fetch( $request );
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

		/* SPAMCHECK */


	}

}
