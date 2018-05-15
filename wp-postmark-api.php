<?php
/**
 * WP Postmark API (http://developer.postmarkapp.com/)
 *
 * @package WP-API-Libraries\WP-Postmark-Base\WP-Postmark-API
 */

/* Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Check if class exists. */
if ( ! class_exists( 'PostMarkAPI' ) ) {

	if ( ! class_exists( 'PostMarkBase' ) ) {
		include_once('wp-postmark-base.php');
	}

	/**
	 * PostMarkAPI class.
	 */
	class PostMarkAPI extends PostMarkBase {

		/* EMAIL. */

		/**
		 * Send a Single Email.
		 *
		 * @access public
		 * @param string $from [REQUIRED] The sender email address. Must have a registered and confirmed Sender Signature.
		 * @param string $to [REQUIRED] Recipient email address. Multiple addresses are comma seperated. Max 50.
		 * @param string $cc Cc recipient email address. Multiple addresses are comma seperated. Max 50.
		 * @param string $bcc Bcc recipient email address. Multiple addresses are comma seperated. Max 50.
		 * @param string $subject Email subject
		 * @param string $tag Email tag that allows you to categorize outgoing emails and get detailed statistics.
		 * @param string $html_body [REQUIRED] HTML email message (If no TextBody specified)
		 * @param string $text_body [REQUIRED] Plain text email message (If no HtmlBody specified)
		 * @param string $replyto Reply To override email address. Defaults to the Reply To set in the sender signature.
		 * @param object $headers List of custom headers to include.
		 * @param bool   $track_opens Activate open tracking for this email.
		 * @param string $track_links Activate link tracking for links in the HTML or Text bodies of this email.
		 * @param object $attachments 	List of attachments
		 * @return Object Server response.
		 */
		public function send_email( $from, $to, $cc, $bcc, $subject, $tag, $html_body, $text_body, $reply_to = '', $headers = array(), $track_opens = true, $track_links = 'HtmlAndText', $attachments = array() ) {

			$args = array(
					'method' => 'POST',
					'timeout' => 45,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking' => true,
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
						'Attachments' => $attachments,
						),
			);

			$response = $this->build_request( $args )->fetch( '/email' );

			return $response;

		}

		/**
		 * Send Batch Emails.
		 *
		 * Format it as an array of the emails to send. This is not a route for being efficient with sending emails, it's for doing a bunch of emails in one request.
		 * It's also possible for you to instead of making an array of email objects to send, to comma separate recipients.
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
		 * @return Object Server response.
		 */
		public function send_batch_emails( $emails ) {

			$args = array(
					'method' => 'POST',
					'timeout' => 45,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking' => true,
					'body' => $emails,
			);

			return $this->build_request( $args )->fetch( '/email/batch' );

		}

		/* BOUNCE. */


		/**
		 * Get Delivery Stats.
		 * It is recommended you use server token as opposed to account token for this command (and other bounce data).
		 *
		 * @access public
		 * @return Object Server response.
		 */
		public function get_delivery_stats() {
				return $this->build_request()->fetch( '/deliverystats' );
		}

		/**
		 * Get Bounces. Used as a paginator for viewing bounce data.
		 *
		 * @access public
		 * @param mixed  $count (Default: 50) Count.
		 * @param mixed  $offset Offset (default: 0) Offset.
		 * @param string $type (default: '') Type.
		 * @param string $inactive (default: '') Inactive.
		 * @param string $email_filter (default: '') Email Filter.
		 * @param string $tag (default: '') Tag.
		 * @param string $message_id (default: '') Message ID.
		 * @param string $from_date (default: '') From Date.
		 * @param string $to_date (default: '') To Date.
		 * @return Object Server response.
		 */
		public function get_bounces( $count = 50, $offset = 0, $type = '', $inactive = '', $email_filter = '', $tag = '', $message_id = '', $from_date = '', $to_date = '' ) {

			$args = array(
				'count' => $count,
				'offset' => $offset,
				'type' => $type,
				'inactive' => $inactive,
				'email_filter' => $email_filter,
				'tag' => $tag,
				'message_id' => $message_id,
				'from_date' => $from_date,
				'to_date' => $to_date,
			);

			$request = '/bounces?' . http_build_query( array_filter( $args ) );

			return $this->build_request()->fetch( $request );

		}

		/**
		 * Get a specific bounce by ID.
		 *
		 * @access public
		 * @param mixed $bounce_id Bounce ID.
		 * @return Object Server response.
		 */
		public function get_bounce( $bounce_id ) {
			return $this->build_request()->fetch( '/bounces/' . $bounce_id );
		}

		/**
		 * Get a Bounce Dump by ID.
		 *
		 * @access public
		 * @param mixed $bounce_id Bounce ID.
		 * @return Object Server response.
		 */
		public function get_bounce_dump( $bounce_id ) {
			return $this->build_request()->fetch( '/bounces/' . $bounce_id . '/dump' );
		}

		/**
		 * Activate a Bounce by ID.
		 *
		 * @access public
		 * @param mixed $bounce_id Bounce ID.
		 * @return Object Server response.
		 */
		public function activate_bounce( $bounce_id ) {
				$args = array();
				$args['method'] = 'PUT';

				return $this->build_request( $args )->fetch( '/bounces/' . $bounce_id . '/activate' );
		}

		/**
		 * Get Bounced Tags.
		 *
		 * @access public
		 * @return Object Server response.
		 */
		public function get_bounced_tags() {
				return $this->build_request()->fetch( '/bounces/tags' );
		}

		/**
		 * Get a list of all possible bounce types.
		 *
		 * @access public
		 * @return list of bounce types.
		 */
		public function get_bounce_types(){
			return array(
				array(
					'Type' => 'HardBounce',
					'Code' => 1,
					'Name' => 'Hard bounce',
					'Description' => __('The server was unable to deliver your message (ex: unknown user, mailbox not found).', 'wp-postmark-api')
				),
				array(
					'Type' => 'Transient',
					'Code' => 2,
					'Name' => 'Message delayed',
					'Description' => __('The server could not temporarily deliver your message (ex: Message is delayed due to network troubles).', 'wp-postmark-api')
				),
				array(
					'Type' => 'Unsubscribe',
					'Code' => 16,
					'Name' => 'Unsubscribe request',
					'Description' => __('Unsubscribe or Remove request.', 'wp-postmark-api')
				),
				array(
					'Type' => 'Subscribe',
					'Code' => 32,
					'Name' => 'Subscribe request',
					'Description' => __('Subscribe request from someone wanting to get added to the mailing list.', 'wp-postmark-api')
				),
				array(
					'Type' => 'AutoResponder',
					'Code' => 64,
					'Name' => 'Auto responder',
					'Description' => __('Automatic email responder (ex: "Out of Office" or "On Vacation").', 'wp-postmark-api')
				),
				array(
					'Type' => 'AddressChange',
					'Code' => 128,
					'Name' => 'Address change',
					'Description' => __('The recipient has requested an address change.', 'wp-postmark-api')
				),
				array(
					'Type' => 'DnsError',
					'Code' => 256,
					'Name' => 'DNS error',
					'Description' => __('A temporary DNS error.', 'wp-postmark-api')
				),
				array(
					'Type' => 'SpamNotification',
					'Code' => 512,
					'Name' => 'Spam notification',
					'Description' => __('The message was delivered, but was either blocked by the user, or classified as spam, bulk mail, or had rejected content.', 'wp-postmark-api')
				),
				array(
					'Type' => 'OpenRelayTest',
					'Code' => 1024,
					'Name' => 'Open relay test',
					'Description' => __('The NDR is actually a test email message to see if the mail server is an open relay.', 'wp-postmark-api')
				),
				array(
					'Type' => 'Unknown',
					'Code' => 2048,
					'Name' => 'Unknown',
					'Description' => __('Unable to classify the NDR.', 'wp-postmark-api')
				),
				array(
					'Type' => 'SoftBounce',
					'Code' => 4096,
					'Name' => 'Soft bounce',
					'Description' => __('Unable to temporarily deliver message (i.e. mailbox full, account disabled, exceeds quota, out of disk space).', 'wp-postmark-api')
				),
				array(
					'Type' => 'VirusNotification',
					'Code' => 8192,
					'Name' => 'Virus notification',
					'Description' => __('The bounce is actually a virus notification warning about a virus/code infected message.', 'wp-postmark-api')
				),
				array(
					'Type' => 'ChallengeVerification',
					'Code' => 16384,
					'Name' => 'Spam challenge verification',
					'Description' => __('The bounce is a challenge asking for verification you actually sent the email. Typcial challenges are made by Spam Arrest, or MailFrontier Matador.', 'wp-postmark-api')
				),
				array(
					'Type' => 'BadEmailAddress',
					'Code' => 100000,
					'Name' => 'Invalid email address',
					'Description' => __('The address is not a valid email address.', 'wp-postmark-api')
				),
				array(
					'Type' => 'SpamComplaint',
					'Code' => 100001,
					'Name' => 'Spam complaint',
					'Description' => __('The subscriber explicitly marked this message as spam.', 'wp-postmark-api')
				),
				array(
					'Type' => 'ManuallyDeactivated',
					'Code' => 100002,
					'Name' => 'Manually deactivated',
					'Description' => __('The email was manually deactivated.', 'wp-postmark-api')
				),
				array(
					'Type' => 'Unconfirmed',
					'Code' => 100003,
					'Name' => 'Registration not confirmed',
					'Description' => __('The subscriber has not clicked on the confirmation link upon registration or import.', 'wp-postmark-api')
				),
				array(
					'Type' => 'Blocked',
					'Code' => 100006,
					'Name' => 'ISP block',
					'Description' => __('Blocked from this ISP due to content or blacklisting.', 'wp-postmark-api')
				),
				array(
					'Type' => 'SMTPApiError',
					'Code' => 100007,
					'Name' => 'SMTP API error',
					'Description' => __('An error occurred while accepting an email through the SMTP API.', 'wp-postmark-api')
				),
				array(
					'Type' => 'InboundError',
					'Code' => 100008,
					'Name' => 'Processing failed',
					'Description' => __('Unable to deliver inbound message to destination inbound hook.', 'wp-postmark-api')
				),
				array(
					'Type' => 'DMARCPolicy',
					'Code' => 100009,
					'Name' => 'DMARC Policy',
					'Description' => __('Email rejected due DMARC Policy.', 'wp-postmark-api')
				),
				array(
					'Type' => 'TemplateRenderingFailed',
					'Code' => 100010,
					'Name' => 'Template rendering failed',
					'Description' => __('An error occurred while attempting to render your template.', 'wp-postmark-api')
				),

			);
		}

		/* TEMPLATES. */

		/**
		 * Get Templates. AKA List Templates
		 *
		 * @access public
		 * @param  mixed  $count  Number of results from offset to display.
		 * @param  mixed  $offset (Default: 0) Offset from first entry in order.
		 * @return Object Server response.
		 */
		public function get_templates( $count = 50, $offset = 0 ) {
				return $this->build_request()->fetch( '/templates?count=' . $count . '&offset=' . $offset );
		}

		/**
		 * List templates. Redirects to get_templates()
		 *
		 * @param  mixed  $count  Number of results from offset to display.
		 * @param  mixed  $offset (Default: 0) Offset from first entry in order.
		 * @return [type]          [description]
		 */
		public function list_templates( $count = 50, $offset = 0 ){
			return $this->get_templates( $count, $offset );
		}

		/**
		 * Get a Template by ID.
		 *
		 * @access public
		 * @param mixed $template_id Template ID.
		 * @return Object Server response.
		 */
		public function get_template( $template_id ) {
				return $this->build_request()->fetch( '/templates/' . $template_id );
		}

		/**
		 * Create a template
		 * https://api.postmarkapp.com/templates
		 *
		 * @param  string $name     name
		 * @param  string $subject  subject
		 * @param  string $htmlbody htmlbody
		 * @param  string $textbody textbody
		 * @return Object           Server response
		 */
		public function create_template( $name, $subject, $htmlbody, $textbody ) {
			$args = array(
				'method' => 'POST',
				'body'   => array(
					'Name' 		 => $name,
					'Subject'  => $subject,
					'HtmlBody' => $htmlbody,
					'TextBody' => $textbody,
				)
			);

			return $this->build_request( $args )->fetch( '/templates/' );
		}

		/**
		 * Edit template.
		 * Subject, html body and text body are required, name is optional.
		 *
		 * @param  mixed  $template_id template_id
		 * @param  string $subject     subject
		 * @param  string $html_body   html_body
		 * @param  string $text_body   text_body
		 * @param  string $name        (Default: '') name
		 * @return [type]              [description]
		 */
		public function edit_template( $template_id, $subject, $html_body, $text_body, $name = '' ) {
			$args = array(
				'method' => 'PUT',
				'body'   => array(
					'Subject'  => $subject,
					'HtmlBody' => $htmlbody,
					'TextBody' => $textbody,
				)
			);

			return $this->build_request( $args )->fetch( '/templates/' . $template_id );
		}

		/**
		 * Validate a template.
		 *
		 * @param  string $subject         (Default: '') Subject of email
		 * @param  string $htmlbody        (Default: '') HTML body.
		 * @param  string $textbody        (Default: '') Plaintext body.
		 * @param  array  $testrendermodel (Default: array) Test render model obj.
		 * @param boolean $inlinecss 			 (Default: false) Whether to allow inlinecss through the email.
		 * @return Object                  Server Response.
		 */
		public function validate_template( $subject = '', $htmlbody = '', $textbody = '', $testrendermodel = array(), $inlinecss = false ) {

			if( $subject == '' && $htmlbody == '' && $textbody = '' ){
				return new WP_Error( 'missing-args', __( 'You must specify at least one of the first three arguments', 'wp-api-libraries' ) );
			}

			$args = array(
				'method' => 'POST',
				'body' => array(
					'Subject' => $subject,
					'HtmlBody' => $htmlbody,
					'TextBody' => $textbody,
					'TestRenderModel' => $testrendermodel,
					'InlineCssForHtmlTestRender' => $inlinecss,
				)
			);

			return $this->build_request( $args )->fetch( '/templates/validate' );
		}

		/**
		 * Delete a template.
		 *
		 * @param  mixed  $template_id ID of template to delete
		 * @return Object              Server response
		 */
		public function delete_template( $template_id ) {
			$args = array(
				'method' => 'DELETE',
			);

			return $this->build_request( $args )->fetch( '/templates/' . $template_id );
		}

		/**
		 * Send an email using a template.
		 *
		 * @param  mixed  $template_id    ID of template to reference.
		 * @param  object $template_model Object containing values to fill template with.
		 * @param  bool   $inlinecss      Whether to use inlinecss or not.
		 * @param  string $from           Email address to send email from.
		 * @param  string $to             (Default: '') Email address(es) to sent email to.
		 * @param  string $cc             (Default: '') Email address(es) to cc email to.
		 * @param  string $bcc            (Default: '') Email address(es) to bcc email to.
		 * @param  string $tag            (Default: '') Tags for internal tracking/data stuff.
		 * @param  string $replyto        (Default: '') Email that clicking 'reply' will be prepared to send to.
		 * @param  array  $headers        (Default: array) Optional headers to include in the email.
		 * @param  bool   $trackopens 		(Default: true) Whether to track opens or not.
		 * @param  string $tracklinks 		(Default: "HtmlAndText") What sorta things to track.
		 * @param  object $attachments 		(Default: array) Attachments.
		 * @return object                 Server response.
		 */
		public function send_email_with_template( $template_id, $template_model, $inlinecss, $from, $to, $cc = '', $bcc = '', $tag = '', $replyto = '', $headers = array(), $trackopens = true, $tracklinks = "HtmlAndText", $attachments = array() ) {
			$args = array(
				'method' => 'POST',
				'body'	 => array(
					'TemplateId' => $template_id,
					'TemplateModel' => $template_model,
					'InlineCss' => $inlinecss,
					'From' => $from,
					'To' => $to,
					'Cc' => $cc,
					'Bcc' => $bcc,
					'Tag' => $tag,
					'Headers' => $headers,
					'TrackOpens' => $trackopens,
					'TrackLinks' => $tracklinks,
					'Attachments' => $attachments,
				)
			);

			if( $replyto != '' ){
				$args['body']['ReplyTo'] = $replyto;
			}

			return $this->build_request( $args )->fetch( '/email/withTemplate/' );
		}

		/**
		 * Send batch with templates
		 *
		 * @link https://postmarkapp.com/developer/api/templates-api#send-batch-with-templates
		 *
		 * @param  array $messages An array of messages to send, each being an email
		 *                         following the structure from previous emails.
		 * @return object          The response, an array of responses per message.
		 */
		public function send_batch_email_with_templates( $messages ){
			$args = array(
				'method' => 'POST',
				'body'   => array(
					'messages' => $messages
				)
			);

			return $this->build_request( $args )->fetch( '/email/batchWithTemplates' );
		}

		/* SERVERS. */

		/**
		 * Get Server.
		 *
		 * @access public
		 * @return Object Server response.
		 */
		public function get_the_server() {
			return $this->build_request()->fetch( '/server' );
		}

		/**
		 * edit_server function.
		 * If you want to reset/clear a value, pass in a space character (ie: " ").
		 *
		 * @access public
		 * @param mixed $name										(Default: '') Name
		 * @param mixed $color									(Default: '') Color
		 * @param mixed $raw_email_enabled			(Default: '') Raw email embedded
		 * @param mixed $smtp_api_activated			(Default: '') SMTP API Activated
		 * @param mixed $inbound_hook_url				(Default: '') Inbound hook url
		 * @param mixed $bounce_hook_url				(Default: '') Bounce hook url
		 * @param mixed $open_hook_url					(Default: '') Open hook url
		 * @param mixed $post_first_open_only		(Default: '') Post first open only
		 * @param mixed $track_opens						(Default: '') Track opens
		 * @param mixed $track_links						(Default: '') Track links
		 * @param mixed $inbound_domain					(Default: '') Inbound domain
		 * @param mixed $inbound_spam_threshold	(Default: '') Inbound spam threshhold
		 * @return Object Server response.
		 */
		public function edit_the_server( $name = '', $color = '', $raw_email_enabled = '', $smtp_api_activated = '', $inbound_hook_url = '', $bounce_hook_url = '', $open_hook_url = '', $post_first_open_only = '', $track_opens = '', $track_links = '', $inbound_domain = '', $inbound_spam_threshold = '' ) {
			$keys = array( 'Name', 'Color', 'RawEmailEnabled', 'SmtpApiActivated', 'DeliveryHookUrl', 'InboundHookUrl', 'BounceHookUrl', 'IncludeBounceContentInHook', 'OpenHookUrl', 'PostFirstOpenOnly', 'TrackOpens', 'TrackLinks', 'InboundDomain', 'InboundSpamThreshold');
			$values = array( $name, $color, $raw_email_enabled, $smtp_api_activated, $inbound_hook_url, $bounce_hook_url, $open_hook_url, $post_first_open_only, $track_opens, $track_links, $inbound_domain, $inbound_spam_threshold );
			$args = array(
				'method' => 'PUT',
				'body' => array()
			);

			for( $i=0;$i<count($values);$i++){
				if( $values[$i] != '' && $key[$i] ){
					$args[$keys[$i]] = $values[$i];
				}
			}

			if( count($args['body']) == 0 ){
				return new WP_Error( 'missing-arguments', __( 'You cannot edit a post with no data', 'wp-api-libraries' ) );
			}

			return $this->build_request( $args )->fetch( '/server/' );
		}

		/**
		 * get_server function.
		 *
		 * @access public
		 * @param mixed $server_id
		 * @return Object Server response.
		 */
		public function get_server( $server_id ) {
			return $this->build_request()->fetch( '/servers/' . $server_id );
		}

		/**
		 * Add a server. Possible values to pass into other_args are below (along with their default values).
		 * You do not need to have every key and value there, only name is mandatory.
		 * You can alternatively pass in a single string as the name of the server to be created.
		 *
		 * array(
		 * 	'name' => '',
		 * 	'Color' => 'Green',
		 * 	'SmtpApiActivated' => true,
		 * 	'RawEmailEnabled' => true,
		 * 	'DeliveryHookUrl' => '',
		 * 	'InboundHookUrl' => '',
		 * 	'BounceHookUrl' => '',
		 * 	'IncludeBounceContentInHook' => '',
		 * 	'OpenHookUrl' => '',
		 * 	'PostFirstOpenOnly' => true,
		 * 	'TrackOpens' => true,
		 * 	'TrackLinks' => 'HtmlAndText',
		 * 	'InboundDomain' => '',
		 * 	'InboundSpamThreshold' => '15',
		 * )
		 * @param object $other_args Object as described above.
		 */
		public function add_server( $other_args = array() ) {

			if( gettype( $other_args ) != 'string' && !isset( $other_args['Name'] ) && !isset( $other_args['name'] ) ) {
				return new WP_Error( 'missing-args', __("You must at least include a Name key/value within the arguments array.") );
			}

			$args = array(
				'method' => 'POST',
				'body' => $other_args
			);

			if( gettype( $other_args ) == 'string' ){
				$args['body'] = array( 'Name' => $other_args );
			}

			return $this->build_request( $args )->fetch( '/servers/' );
		}

		/**
		 * Edit a server.
		 * Pass in an object with the values you'd like to change.
		 *
		 * array(
		 * 	'name' => '',
		 * 	'Color' => 'Green',
		 * 	'SmtpApiActivated' => true,
		 * 	'RawEmailEnabled' => true,
		 * 	'DeliveryHookUrl' => '',
		 * 	'InboundHookUrl' => '',
		 * 	'BounceHookUrl' => '',
		 * 	'IncludeBounceContentInHook' => '',
		 * 	'OpenHookUrl' => '',
		 * 	'PostFirstOpenOnly' => true,
		 * 	'TrackOpens' => true,
		 * 	'TrackLinks' => 'HtmlAndText',
		 * 	'InboundDomain' => '',
		 * 	'InboundSpamThreshold' => '15',
		 * )
		 *
		 * @param  mixed  $server_id  server id.
		 * @param  object $other_args arguments to modify.
		 * @return object             Server response.
		 */
		public function edit_server( $server_id, $other_args = array() ) {
			$args = array(
				'method' => 'PUT',
				'body' => $other_args
			);

			return $this->build_request( $args )->fetch( '/servers/' . $server_id );
		}

		/**
		 * list_servers function.
		 *
		 * @access public
		 * @param  mixed  $count  Number of results from offset to display.
		 * @param  mixed  $offset (Default: 0) Offset from first entry in order.
		 * @param  mixed  $name 	(Default: null) name of server (search filter).
		 * @return Object Server response.
		 */
		public function list_servers( $count = 50, $offset = 0, $name = null ) {
			return $this->build_request()->fetch( '/servers?count=' . $count . '&offset=' . $offset . '&name=' . $name );
		}

		/**
		 * Delete a server by ID.
		 *
		 * @param  mixed $server_id  ID of server to delete.
		 * @return object            Server response.
		 */
		public function delete_server( $server_id ) {
			$args = array(
				'method' => 'DELETE',
			);

			return $this->build_request( $args )->fetch( '/servers/' . $server_id );
		}


		/* MESSAGES. */

		/**
		 * search_message_opens function.
		 *
		 * @access public
		 * @param mixed $count  Number of results from offset to display.
		 * @param mixed $offset (Default: 0) Offset from first entry in order.
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
		 * @return Object Server response.
		 */
		public function search_outbound_messages( $count = 50, $offset = 0, $recipient = '', $fromemail = '', $tag = '', $status = '', $todate = '', $fromdate = '' ) {

			$request = '/messages/outbound?count=' . $count . '&';

			$request .= http_build_query(array_filter(array(
				'offset' => $offset,
				'recipient' => $recipient,
				'fromemail' => $fromemail,
				'tag' => $tag,
				'status' => $status,
				'todate' => $todate,
				'fromdate' => $fromdate,
			)));

			return $this->build_request()->fetch( $request );
		}

		/**
		 * Get Outbound Message Details
		 *
		 * @access public
		 * @param mixed $message_id
		 * @return Object Server response.
		 */
		public function get_outbound_message_details( $message_id ) {
			return $this->build_request()->fetch( '/messages/outbound/' . $message_id . '/details' );
		}

		/**
		 * Get Outbound Message Dump
		 */
		public function get_outbound_message_dump( $message_id ){
			return $this->build_request()->fetch( '/messages/outbound/' . $message_id . '/dump' );
		}

		/**
		 * Search inbound messages
		 * @param m ixed    $count  Number of results from offset to display.
		 * @param  mixed    $offset (Default: 0) Offset from first entry in order.
		 * @param  string   $recipient   recipient
		 * @param  string   $fromemail   fromemail
		 * @param  string   $tag         tag
		 * @param  string   $subject     subject
		 * @param  string   $mailboxhash mailboxhash
		 * @param  string   $status      status
		 * @param  string   $todate      todate
		 * @param  string   $fromdate    fromdate
		 * @return [type]               [description]
		 */
		public function search_inbound_messages( $count = 50, $offset = 0, $recipient = '', $fromemail = '', $tag = '', $subject = '', $mailboxhash = '', $status = '', $todate = '', $fromdate = '' ){
			$request = '/messages/inbound?count=' . $count . '&';

			$request .= http_build_query(array_filter(array(
				'offset' => $offset,
				'recipient' => $recipient,
				'fromemail' => $fromemail,
				'tag' => $tag,
				'subject' => $subject,
				'mailboxhash' => $mailboxhash,
				'status' => $status,
				'todate' => $todate,
				'fromdate' => $fromdate,
			)));

			return $this->build_request()->fetch( $request );
		}

		public function get_inbound_message_details( $message_id ){
			return $this->build_request()->fetch( '/messages/inbound/' . $message_id . '/details' );
		}

		public function bypass_blocked_inbound_message( $message_id ){
			$args = array( 'method' => 'PUT' );

			return $this->build_request( $args )->fetch( '/messages/inbound/' . $message_id . '/bypass' );
		}

		public function retry_failed_inbound( $message_id ){
			$args = array( 'method' => 'PUT' );

			return $this->build_request( $args )->fetch( '/messages/inbound/' . $message_id . '/retry' );
		}

		/**
		 * get_message_opens function.
		 *
		 * @access public
		 * @param mixed $message_id
		 * @param mixed  $count  (Default: 50) Number of results from offset to display.
		 * @param mixed  $offset (Default: 0) Offset from first entry in order.
		 * @return Object Server response.
		 */
		public function get_message_opens( $count = 50, $offset = 0 ) {

			$request = '/messages/outbound/opens/?' . http_build_query(array(
				'count' => $count,
				'offset' => $offset,
			));

			return $this->build_request()->fetch( $request );
		}

		public function get_single_message_opens( $message_id ){
			return $this->build_request()->fetch( '/messages/outbound/opens/' . $message_id );
		}

		/* DOMAINS. */

		/**
		 * List Domains.
		 *
		 * @access public
		 * @param int $count (default: 500) Count. Max 500
		 * @param int $offset (default: 0) Offset.
		 * @return Object Server response.
		 */
		public function list_domains( $count = 500, $offset = 0 ) {
			return $this->build_request()->fetch( '/domains?count=' . $count . '&offset=' . $offset );
		}

		/**
		 * Get Domain Details.
		 *
		 * @access public
		 * @param mixed $domain_id
		 * @return Object Server response.
		 */
		public function get_domain_details( $domain_id ) {
			return $this->build_request()->fetch( '/domains/' . $domain_id );
		}

		public function add_domain( $name, $return_path_domain = '' ) {

			$args = array(
				'method' => 'POST',
				'body' => array(
					'Name' => $name,
				),
			);

			if( $return_path_domain != '' ){
				$args['body']['ReturnPathDomain'] = $return_path_domain;
			}

			return $this->build_request( $args )->fetch( '/domains' );
		}

		/**
		 * Interestingly enough, the only option you can edit for a domain is the
		 * return path. Kinda makes sense? Otherwise delete it and make a new one if
		 * you want to change the name.
		 * @param  [type] $domain_id          [description]
		 * @param  [type] $return_path_domain [description]
		 * @return [type]                     [description]
		 */
		public function edit_domain( $domain_id, $return_path_domain ) {

			$args = array(
				'method' => 'PUT',
				'body' => array(
					'ReturnPathDomain' => $return_path_domain,
				)
			);

			return $this->build_request( $args )->fetch( '/domains/' . $domain_id );
		}

		/**
		 * delete_domain function.
		 *
		 * @access public
		 * @param mixed $domain_id
		 * @return void
		 */
		public function delete_domain( $domain_id ) {

			$args = array(
				'method' => 'DELETE',
			);

			return $this->build_request( $args )->fetch( '/domains/' . $domain_id );
		}

		/**
		 * verify_domain_spf_record function.
		 *
		 * @access public
		 * @param mixed $domain_id
		 * @return void
		 */
		public function verify_domain_spf_record( $domain_id ) {
		  $args = array(
				'method' => 'POST'
			);

			return $this->build_request( $args )->fetch( '/domains/' . $domain_id . '/verifyspf' );
		}

		/**
		 * rotate_dkim_keys function.
		 *
		 * @access public
		 * @param mixed $domain_id
		 * @return void
		 */
		public function rotate_dkim_keys( $domain_id ) {
			$args = array(
				'method' => 'POST',
			);

			return $this->build_request( $args )->fetch( '/domains/' . $domain_id . '/rotatedkim' );
		}

		/* SENDER SIGNATURES */

		/**
		 * list_sender_signatures function.
		 *
		 * @access public
		 * @return Object Server response.
		 */
		public function list_sender_signatures( $count = 500, $offset = 0 ) {
			return $this->build_request()->fetch( "/senders?count=$count&offset=$offset" );
		}

		/**
		 * get_sender_signatures_details function.
		 *
		 * @access public
		 * @param mixed $signature_id
		 * @return Object Server response.
		 */
		public function get_sender_signatures_details( $signature_id ) {
			return $this->build_request()->fetch( '/senders/' . $signature_id );
		}

		public function create_signature( $from_email, $name, $reply_to_email = '', $return_path_domain = '') {
			$args = array(
				'method' => 'POST',
				'body' => array(
					'FromEmail' => $from_email,
					'Name' => $name,
					'ReplyToEmail' => $reply_to_email,
					'ReturnPathDomain' => $return_path_domain,
				)
			);

			return $this->build_request( $args )->fetch( '/senders/' );
		}

		public function edit_signature( $signature_id, $name, $reply_to_email = '', $return_path_domain = '' ) {
			$args = array(
				'method' => 'PUT',
				'body' => array(
					'Name' => $name,
					'ReplyToEmail' => $reply_to_email,
					'ReturnPathDomain' => $return_path_domain,
				)
			);

			return $this->build_request( $args )->fetch( '/senders/' . $signature_id );
		}

		public function delete_signature( $signature_id ) {
			$args = array(
				'method' => 'DELETE',
			);

			return $this->build_request( $args )->fetch( '/senders/' . $signature_id );
		}

		public function resend_confirmation( $signature_id ) {
			$args = array(
				'method' => 'POST',
			);

			return $this->build_request( $args )->fetch( '/senders/' . $signature_id . '/resend' );
		}

		/* STATS. */

		/**
		 * get_outbound_stats function.
		 *
		 * @access public
		 * @param mixed $tag
		 * @param mixed $from_date
		 * @param mixed $to_date
		 * @return Object Server response.
		 */
		public function get_outbound_stats( $tag = '', $from_date = '', $to_date = '' ) {
			$request = '/stats/outbound?' . http_build_query( array_filter(array(
				'tag' => $tag,
				'fromdate' => $from_date,
				'todate' => $to_date,
			)));

			return $this->build_request()->fetch( $request );
		}

		/**
		 * get_send_counts function.
		 *
		 * @access public
		 * @param mixed $tag
		 * @param mixed $from_date
		 * @param mixed $to_date
		 * @return Object Server response.
		 */
		public function get_send_counts( $tag = '', $from_date = '', $to_date = '' ) {
			$request = '/stats/outbound/sends';
			if( $tag !== '' || $from_date !== '' ||	$to_date !== '' ){
				$request .= '?' . http_build_query( array_filter( array(
					'tag' => $tag,
					'fromdate' => $from_date,
					'todate' => $todate,
				)));
			}

			return $this->build_request()->fetch( $request );
		}

		/**
		 * get_bounce_counts function.
		 *
		 * @access public
		 * @param mixed $tag
		 * @param mixed $from_date
		 * @param mixed $to_date
		 * @return Object Server response.
		 */
		public function get_bounce_counts( $tag = '', $from_date = '', $to_date = '' ) {
			$request = '/stats/outbound/bounce?' . http_build_query( array_filter( array(
				'tag' => $tag,
				'fromdate' => $from_date,
				'todate' => $to_date,
			)));

			return $this->build_request()->fetch( $request );
		}

		/**
		 * get_spam_complaints function.
		 *
		 * @access public
		 * @param mixed $tag
		 * @param mixed $from_date
		 * @param mixed $to_date
		 * @return Object Server response.
		 */
		public function get_spam_complaints( $tag = '', $from_date = '', $to_date = '' ) {
			$request = '/stats/outbound/spam?' . http_build_query( array_filter( array(
				'tag' => $tag,
				'fromdate' => $from_date,
				'todate' => $to_date,
			)));

			return $this->build_request()->fetch( $request );
		}

		/**
		 * get_tracked_email_counts function.
		 *
		 * @access public
		 * @param mixed $tag
		 * @param mixed $from_date
		 * @param mixed $to_date
		 * @return Object Server response.
		 */
		public function get_tracked_email_counts( $tag = '', $from_date = '', $to_date = '' ) {
			$request = '/stats/outbound/tracked?' . http_build_query( array_filter( array(
				'tag' => $tag,
				'fromdate' => $from_date,
				'todate' => $to_date,
			)));

			return $this->build_request()->fetch( $request );
		}

		/**
		 * get_email_open_counts function.
		 *
		 * @access public
		 * @param mixed $tag
		 * @param mixed $from_date
		 * @param mixed $to_date
		 * @return Object Server response.
		 */
		public function get_email_open_counts( $tag = '', $from_date = '', $to_date = '' ) {
			$request = '/stats/outbound/opens?' . http_build_query( array_filter( array(
				'tag' => $tag,
				'fromdate' => $from_date,
				'todate' => $to_date,
			)));

			return $this->build_request()->fetch( $request );
		}

		/**
		 * get_email_platform_usage function.
		 *
		 * @access public
		 * @param mixed $tag
		 * @param mixed $from_date
		 * @param mixed $to_date
		 * @return Object Server response.
		 */
		public function get_email_platform_usage( $tag = '', $from_date = '', $to_date = '' ) {
			$request = '/stats/outbound/opens/platforms?' . http_build_query( array_filter( array(
				'tag' => $tag,
				'fromdate' => $from_date,
				'todate' => $to_date,
			)));

			return $this->build_request()->fetch( $request );
		}

		/**
		 * get_email_client_usage function.
		 *
		 * @access public
		 * @param mixed $tag
		 * @param mixed $from_date
		 * @param mixed $to_date
		 * @return Object Server response.
		 */
		public function get_email_client_usage( $tag = '', $from_date = '', $to_date = '' ) {
			$request = $this->route_uri . '/stats/outbound/opens/emailclients';
			$request = '/stats/outbound/opens/emailclients?' . http_build_query( array_filter( array(
				'tag' => $tag,
				'fromdate' => $from_date,
				'todate' => $to_date,
			)));

			return $this->build_request()->fetch( $request );
		}

		/**
		 * get_email_read_times function.
		 *
		 * @access public
		 * @param mixed $tag
		 * @param mixed $from_date
		 * @param mixed $to_date
		 * @return Object Server response.
		 */
		public function get_email_read_times( $tag = '', $from_date = '', $to_date = '' ) {
			$request = '/stats/outbound/opens/readtimes?' . http_build_query( array_filter( array(
				'tag' => $tag,
				'fromdate' => $from_date,
				'todate' => $to_date,
			)));

			return $this->build_request()->fetch( $request );
		}

		/**
		 * get_click_counts function.
		 *
		 * @access public
		 * @param mixed $tag
		 * @param mixed $from_date
		 * @param mixed $to_date
		 * @return Object Server response.
		 */
		public function get_click_counts( $tag = '', $from_date = '', $to_date = '' ) {
			$request = '/stats/outbound/clicks?' . http_build_query( array_filter( array(
				'tag' => $tag,
				'fromdate' => $from_date,
				'todate' => $to_date,
			)));

			return $this->build_request()->fetch( $request );
		}

		/**
		 * get_browser_usage function.
		 *
		 * @access public
		 * @param mixed $tag
		 * @param mixed $from_date
		 * @param mixed $to_date
		 * @return Object Server response.
		 */
		public function get_browser_usage( $tag = '', $from_date = '', $to_date = '' ) {
			$request = '/stats/outbound/clicks/browserfamilies?' . http_build_query( array_filter( array(
				'tag' => $tag,
				'fromdate' => $from_date,
				'todate' => $to_date,
			)));

			return $this->build_request()->fetch( $request );
		}

		/**
		 * get_browser_platform_usage function.
		 *
		 * @access public
		 * @param mixed $tag
		 * @param mixed $from_date
		 * @param mixed $to_date
		 * @return Object Server response.
		 */
		public function get_browser_platform_usage( $tag = '', $from_date = '', $to_date = '' ) {
			$request = '/stats/outbound/clicks/platforms?' . http_build_query( array_filter( array(
				'tag' => $tag,
				'fromdate' => $from_date,
				'todate' => $to_date,
			)));

			return $this->build_request()->fetch( $request );
		}

		/**
		 * get_click_location function.
		 *
		 * @access public
		 * @param mixed $tag
		 * @param mixed $from_date
		 * @param mixed $to_date
		 * @return Object Server response.
		 */
		public function get_click_location( $tag = '', $from_date = '', $to_date = '' ) {
			$request = '/stats/outbound/clicks/location?' . http_build_query( array_filter( array(
				'tag' => $tag,
				'fromdate' => $from_date,
				'todate' => $to_date,
			)));

			return $this->build_request()->fetch( $request );
		}

		/* TRIGGERS. */


		/**
		 * create_trigger_for_tag function.
		 *
		 * @access public
		 * @param mixed $name
		 * @param bool $track_opens (default: true)
		 * @return void
		 */
		public function create_trigger_for_tag( $name, $track_opens = true ) {
			$args = array(
				'method' => 'POST',
				'body' => array(
					'MatchName' => $name,
					'TrackOpens' => $track_opens,
				)
			);

			return $this->build_request( $args )->fetch( '/triggers/tags' );
		}

		/**
		 * get_single_trigger function.
		 *
		 * @access public
		 * @param mixed $trigger_id
		 * @return void
		 */
		public function get_single_trigger( $trigger_id ) {
			return $this->build_request()->fetch( '/triggers/tags/' . $trigger_id );
		}

		/**
		 * edit_single_trigger function.
		 *
		 * @access public
		 * @param mixed $trigger_id
		 * @param mixed $name
		 * @param string $track_opens (default: '')
		 * @return void
		 */
		public function edit_single_trigger( $trigger_id, $name, $track_opens = '' ) {
			$args = array(
				'method' => 'PUT',
				'body' => array(
					'MatchName' => $name
				)
			);

			if( $track_opens !== '' ){
				$args['body']['TrackOpens'] = $track_opens;
			}

			return $this->build_request( $args )->fetch( '/triggers/tags/' . $trigger_id );
		}

		/**
		 * delete_single_trigger function.
		 *
		 * @access public
		 * @param mixed $trigger_id
		 * @return void
		 */
		public function delete_single_trigger( $trigger_id ) {
			$args = array(
				'method' => 'DELETE',
			);

			return $this->build_request( $args )->fetch( '/triggers/tags/' . $trigger_id );
		}

		/**
		 * Search Triggers.
		 *
		 * @access public
		 * @param mixed  $count  (Default: 50) Number of results from offset to display.
		 * @param mixed  $offset (Default: 0) Offset from first entry in order.
		 * @param mixed $match_name Match Name (def. '').
		 * @return Object Server response.
		 */
		public function search_triggers( $count = 50, $offset = 0, $match_name = '') {
			return $this->build_request()->fetch( '/triggers/tags?match_name=' . $match_name . '&count=' . $count . '&offset=' . $offset );
		}

		/* Inbound Rules Triggers */


		/**
		 * create_trigger_for_inbound_rule function.
		 *
		 * @access public
		 * @param mixed $rule
		 * @return void
		 */
		public function create_trigger_for_inbound_rule( $rule ) {
			$args = array(
				'method' => 'POST',
				'body' => array(
					'Rule' => $rule
				)
			);
			return $this->build_request( $args )->fetch( '/triggers/inboundrules' );
		}

		/**
		 * delete_single_inbound_trigger function.
		 *
		 * @access public
		 * @param mixed $trigger_id
		 * @return void
		 */
		public function delete_single_inbound_trigger( $trigger_id ) {
			$args = array(
				'method' => 'DELETE',
			);

			return $this->build_request( $args )->fetch( 'triggers/inboundrules/' . $trigger_id );
		}

		/**
		 * List Inbound Rule Triggers.
		 *
		 * @access public
		 * @param mixed  $count  (Default: 500) Number of results from offset to display.
		 * @param mixed  $offset (Default: 0) Offset from first entry in order.
		 * @return Object Server response.
		 */
		public function list_inbound_triggers( $count = 500, $offset = 0 ) {
			return $this->build_request( $args )->fetch( '/triggers/inboundrules?count=' . $count . '&offset=' . $offset );
		}
	}

}
