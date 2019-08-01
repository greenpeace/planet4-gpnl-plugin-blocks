<?php

namespace P4NLBKS\Controllers\Blocks;

if ( ! class_exists( 'GPNL_Inforequest_Controller' ) ) {

	/**
	 * Class GPNL_Inforequest_Controller
	 *
	 * @package P4NLBKS\Controllers\Blocks
	 */
	class GPNL_Inforequest_Controller extends Controller {

		/**
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'gpnl_inforequest';


		/**
		Shortcode UI setup for the inforequest shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {
			$fields = [
				[
					'label' => __( 'Formuliertitel', 'planet4-gpnl-blocks' ),
					'attr'  => 'formtitle',
					'type'  => 'text',
					'value' => '',
				],
				[
					'label' => __( 'Benaming van het aangevraagde', 'planet4-gpnl-blocks' ),
					'attr'  => 'itemtitle',
					'type'  => 'text',
					'value' => '',
				],
				[
					'label' => '<hr class="hr-dashed"><br><div class="message notice-info">' .
						__( 'Voer hieronder minimaal 1 en maximaal 5 artikelen in ' ) . '</div>' .
						'<p>' . __( 'Optie 1: Marketingcode', 'planet4-gpnl-blocks' ) . '</p>',
					'attr'  => 'mcode1_code',
					'type'  => 'text',
					'value' => '',
				],
				[
					'label' => __( 'Optie 1: Naam', 'planet4-gpnl-blocks' ),
					'attr'  => 'mcode1_label',
					'type'  => 'text',
					'value' => '',
				],
				[
					'label' => __( 'Optie 2: Marketingcode', 'planet4-gpnl-blocks' ),
					'attr'  => 'mcode2_code',
					'type'  => 'text',
					'value' => '',
				],
				[
					'label' => __( 'Optie 2: Naam', 'planet4-gpnl-blocks' ),
					'attr'  => 'mcode2_label',
					'type'  => 'text',
					'value' => '',
				],
				[
					'label' => __( 'Optie 3: Marketingcode', 'planet4-gpnl-blocks' ),
					'attr'  => 'mcode3_code',
					'type'  => 'text',
					'value' => '',
				],
				[
					'label' => __( 'Optie 3: Naam', 'planet4-gpnl-blocks' ),
					'attr'  => 'mcode3_label',
					'type'  => 'text',
					'value' => '',
				],
				[
					'label' => __( 'Optie 4: Marketingcode', 'planet4-gpnl-blocks' ),
					'attr'  => 'mcode4_code',
					'type'  => 'text',
					'value' => '',
				],
				[
					'label' => __( 'Optie 4: Naam', 'planet4-gpnl-blocks' ),
					'attr'  => 'mcode4_label',
					'type'  => 'text',
					'value' => '',
				],
				[
					'label' => __( 'Optie 5: Marketingcode', 'planet4-gpnl-blocks' ),
					'attr'  => 'mcode5_code',
					'type'  => 'text',
					'value' => '',
				],
				[
					'label' => __( 'Optie 5: Marketingcode', 'planet4-gpnl-blocks' ),
					'attr'  => 'mcode5_label',
					'type'  => 'text',
					'value' => '',
				],
				[
					'label' => __( 'Opt in tekst', 'planet4-gpnl-blocks' ),
					'attr'  => 'consent',
					'type'  => 'textarea',
					'value' => 'Als je dit aanvinkt, mag Greenpeace je per e-mail op de hoogte houden over onze campagnes. Ook vragen we je af en toe om steun. Afmelden kan natuurlijk altijd.',
				],
				[
					'label' => __( 'Registreer knop', 'planet4-gpnl-blocks' ),
					'attr'  => 'sign',
					'type'  => 'text',
					'value' => 'Registreer',
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPNL | Infoaanvraag', 'planet4-gpnl-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpnl-plugin-blocks/admin/images/icon_noindex.png' ) . '" />',
				'attrs'         => $fields,
				'post_type'     => P4NLBKS_ALLOWED_PAGETYPE,
			];

			shortcode_ui_register_for_shortcode( 'shortcake_' . self::BLOCK_NAME, $shortcode_ui_args );

		}

		private function isMarketingcodeAttribute( $string ) {
			return 0 === strpos( $string, 'mcode' );
		}

		/**
		 * Callback for the shortcake_noindex shortcode.
		 * It renders the shortcode based on supplied attributes.
		 *
		 * @param array  $fields        Array of fields that are to be used in the template.
		 * @param string $content       The content of the post.
		 * @param string $shortcode_tag The shortcode tag (shortcake_blockname).
		 *
		 * @return string The complete html of the block
		 */
		public function prepare_template( $fields, $content, $shortcode_tag ) : string {

			// wp_enqueue_style( 'gpnl_inforequest_css', P4NLBKS_ASSETS_DIR . 'css/gpnl-liveblog.css', [], '2.4.0' );
			 wp_enqueue_script( 'gpnl_request_js', P4NLBKS_ASSETS_DIR . 'js/gpnl-inforequest.js', [ 'jquery' ], '2.4.0', true );

			$fields = shortcode_atts(
				[
					'formtitle'    => '',
					'itemtitle'    => '',
					'mcode1_code'  => '',
					'mcode1_label' => '',
					'mcode2_code'  => '',
					'mcode2_label' => '',
					'mcode3_code'  => '',
					'mcode3_label' => '',
					'mcode4_code'  => '',
					'mcode4_label' => '',
					'mcode5_code'  => '',
					'mcode5_label' => '',
					'consent'      => '',
					'sign'         => '',

				],
				$fields,
				$shortcode_tag
			);

			$mcode_attributes = array_filter( $fields, [ $this, 'isMarketingcodeAttribute' ], ARRAY_FILTER_USE_KEY );
			$sorted_mcodes    = [];
			$postfixes        = [ '_code', '_label' ];
			for ( $i = 1; $i < 6; $i++ ) {
				$key = 'mcode' . $i;
				foreach ( $postfixes as $postfix ) {
					if ( '' === $mcode_attributes[ $key . $postfix ] ) {
						continue; }
					$sorted_mcodes[ $i ][ $postfix ] = $mcode_attributes[ $key . $postfix ];
				}
			}

			$fields['mcodes'] = $sorted_mcodes;

			$data = [
				'fields' => $fields,
			];

			// Pass options to frontend code
			wp_localize_script(
				'gpnl_request_js',
				'request_form_object',
				[
					'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
					'nonce'          => wp_create_nonce( 'GPNL_Newsletters' ),
					'literaturecode' => '04935',
				]
			);

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( self::BLOCK_NAME, $data );

			return ob_get_clean();
		}


	}
}

function request_form_process() {

	check_ajax_referer( 'GPNL_Newsletters', 'nonce' );

	// get codes for processing in the database and sanitize
	$literatuurcode = htmlspecialchars( wp_strip_all_tags( $_POST['literaturecode'] ) );

	// Get and sanitize the formdata
	$firstname           = wp_strip_all_tags( $_POST['name'] );
	$surname             = wp_strip_all_tags( $_POST['surname'] );
	$schoolname          = wp_strip_all_tags( $_POST['schoolname'] );
	$postalcode          = wp_strip_all_tags( $_POST['postal-code'] );
	$housenumber         = wp_strip_all_tags( $_POST['housenumber'] );
	$housenumberaddition = wp_strip_all_tags( $_POST['housenumberaddition'] );
	$subscription        = wp_strip_all_tags( $_POST['subscription'] );
	$email               = wp_strip_all_tags( $_POST['mail'] );
	$human               = wp_strip_all_tags( $_POST['human'] );

	if ( '' !== $human ) {
		wp_send_json_error(
			[
				'statuscode' => 400,
			],
			500
		);
	}

	$viewstate        = '__VIEWSTATE=%2FwEPDwUJODM1ODY2MzcwD2QWAgIFD2QWDAIBDxYCHgdWaXNpYmxlaBYCAgEPDxYCHwBoZGQCCw8PFgQeCENzc0NsYXNzBQ5MVl92YWxpZF9maWVsZB4EXyFTQgICFgIeBm9uQmx1cgUWZ2V0U3RyZWV0QW5kQ2l0eSh0aGlzKWQCDQ8PFgQfAQUOTFZfdmFsaWRfZmllbGQfAgICFgIfAwUWZ2V0U3RyZWV0QW5kQ2l0eSh0aGlzKWQCEQ8PFgIeBFRleHQFC0JldWtlbnBsZWluZGQCEw8PFgIfBAUJQU1TVEVSREFNZGQCHQ8PFgIfBGVkZGRBJmxdGtoaSLo%2F4Yi4X9hGSG3BwbhVvG%2FGOq834aaRQw%3D%3D';
	$eventtvalidation = '&__EVENTVALIDATION=%2FwEdABF3LI3RPuN7VHIB%2FiC9SlnC5sFTdyioL9pc1FvWQxJrID4jDC7t6U5%2BT1Fxh8AN9w2o09zcEmiclrRScTXzJ8FlQ9MQqRNiLV4OC1bGEeyI2u30iXrw69nPdRKAWw4ecGXEvqGbohdYyespYGy1DHDpUDt%2FnXA9JX91mkdr03M6%2B0hWWUwWWgRWz8A804pl68fKkRvhDumKNu%2F2zV1hQX9mQwdmH1m48FGJ7a8D8d%2BhEstn5%2BZCICZ19mqj6AzHUngialgueJ0eEOhAI8Wb89daj6AQmA6qRm%2BF%2FBwR5oQ46vlHvbRgi3aNqiRYRyUhmic85pbWlDO2hADfoPXD%2F5tdhHaoPqq3GUazmx9aagqOG%2BUDf6sSkQbZaH85xqArAoJeZOGMF%2B00SrRbKPtRrkrQ';

	$data_array = [
		'txtVoornaamDocent' => $firstname,
		'txtTussenvoegsel'  => '',
		'txtAchternaam'     => $surname,
		'txtNaamSchool'     => $schoolname,
		'txtPostcode'       => $postalcode,
		'txtNummer'         => $housenumber,
		'txtToev'           => $housenumberaddition,
		'txtEmail'          => $email,
		'ddlEN'             => $subscription,
		'btnSubmit'         => 'Registreer',
	];

	$data = $viewstate . $eventtvalidation . '&' . http_build_query( $data_array );

	$url = 'https://www.mygreenpeace.nl/registreren/schoolaanmelding.aspx?source=' . $literatuurcode;

	// initiate a cUrl request to the database
	$request = curl_init( $url );
	curl_setopt( $request, CURLOPT_POSTFIELDS, $data );
	curl_setopt( $request, CURLOPT_POST, 1 );
	curl_setopt( $request, CURLOPT_HEADER, true );
	curl_setopt(
		$request,
		CURLOPT_HTTPHEADER,
		[
			'Content-Length: ' . strlen( $data ),
		]
	);
	curl_setopt( $request, CURLOPT_RETURNTRANSFER, true );

	$http_code     = curl_getinfo( $request, CURLINFO_HTTP_CODE );
	$http_location = curl_getinfo( $request, CURLINFO_REDIRECT_URL );
	curl_close( $request );

	$errorpage       = '/registreren/errorpage.htm?aspxerrorpath=/registreren/schoolaanmelding.aspx';
	$iserrorpage = (false !== strpos( $http_location, $errorpage ) ? 1 : 0);

	// Give the appropriate response to the frontend
	if ( $http_code >= 400 || $iserrorpage ) {
		wp_send_json_error(
			[
				'statuscode' => $http_code,
			],
			500
		);
	}
	wp_send_json_success(
		[
			'statuscode' => $http_code,
		],
		200
	);
}

// use this version for if you want the callback to work for users who are logged in
add_action( 'wp_ajax_request_form_process', 'P4NLBKS\Controllers\Blocks\request_form_process' );
// use this version for if you want the callback to work for users who are not logged in
add_action( 'wp_ajax_nopriv_request_form_process', 'P4NLBKS\Controllers\Blocks\request_form_process' );
