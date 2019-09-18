<?php

namespace P4NLBKS\Controllers\Blocks;

if ( ! class_exists( 'Donation_Controller' ) ) {

	/**
	 * Class Donation_Controller
	 *
	 * @package P4NLBKS\Controllers\Blocks
	 */
	class Donation_Controller extends Controller {

		/** @const string BLOCK_NAME */
		const BLOCK_NAME = 'donation';

		/**
		 * Shortcode UI setup for the donationblock shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {
			$fields = array(
				array(
					'label' => __( 'Titel', 'planet4-gpnl-blocks' ),
					'attr'  => 'title',
					'type'  => 'text',
				),
				array(
					'label' => __( 'Omschrijving', 'planet4-gpnl-blocks' ),
					'attr'  => 'description',
					'type'  => 'textarea',
				),
				array(
					'label'   => __( 'Voorgestelde periodiek', 'planet4-gpnl-blocks' ),
					'attr'    => 'suggested_frequency',
					'type'    => 'select',
					'meta'    => [ 'onchange' => 'hideNonForcesOptions()' ],
					'options' => [
						[
							'value' => 'E',
							'label' => __( 'Eenmalig' ),
						],
						[
							'value' => 'M',
							'label' => __( 'Maandelijks' ),
						],
						[
							'value' => 'F',
							'label' => __( 'Maandelijks voor 12 maanden (Forces)' ),
						],
					],
				),
				array(
					'label'   => __( 'Donateur kan periodiek wijzigen', 'planet4-gpnl-blocks' ),
					'attr'    => 'allow_frequency_override',
					'type'    => 'checkbox',
					'checked' => 'checked',
				),
				array(
					'label' => __( 'Minimum bedrag', 'planet4-gpnl-blocks' ),
					'attr'  => 'min_amount',
					'type'  => 'number',
					'value' => 5,

				),
				array(
					'label' => __( 'Eenmalig: Bedrag 1', 'planet4-gpnl-blocks' ),
					'attr'  => 'oneoff_amount1',
					'type'  => 'number',
					'value' => 5,
				),
				array(
					'label' => __( 'Eenmalig: Bedrag 2', 'planet4-gpnl-blocks' ),
					'attr'  => 'oneoff_amount2',
					'type'  => 'number',
					'value' => 10,

				),
				array(
					'label' => __( 'Eenmalig: Bedrag 3', 'planet4-gpnl-blocks' ),
					'attr'  => 'oneoff_amount3',
					'type'  => 'number',
					'value' => 25,

				),
				array(
					'label' => __( 'Eenmalig: Voorgesteld bedrag', 'planet4-gpnl-blocks' ),
					'attr'  => 'oneoff_suggested_amount',
					'type'  => 'number',
					'value' => 10,

				),
				array(
					'label' => __( 'Periodiek: Bedrag 1', 'planet4-gpnl-blocks' ),
					'attr'  => 'recurring_amount1',
					'type'  => 'number',
					'value' => 5,
				),
				array(
					'label' => __( 'Periodiek: Bedrag 2', 'planet4-gpnl-blocks' ),
					'attr'  => 'recurring_amount2',
					'type'  => 'number',
					'value' => 10,

				),
				array(
					'label' => __( 'Periodiek: Bedrag 3', 'planet4-gpnl-blocks' ),
					'attr'  => 'recurring_amount3',
					'type'  => 'number',
					'value' => 25,

				),
				array(
					'label' => __( 'Periodiek: Voorgesteld bedrag', 'planet4-gpnl-blocks' ),
					'attr'  => 'recurring_suggested_amount',
					'type'  => 'number',
					'value' => 10,

				),
				array(
					'label' => __( 'Bedankt Titel', 'planet4-gpnl-blocks' ),
					'attr'  => 'thanktitle',
					'type'  => 'text',
				),
				array(
					'label' => __( 'Bedankt Omschrijving', 'planet4-gpnl-blocks' ),
					'attr'  => 'thankdescription',
					'type'  => 'textarea',
				),
				array(
					'label' => __( 'Literatuurcode', 'planet4-gpnl-blocks' ),
					'attr'  => 'literatuurcode',
					'type'  => 'text',
					'value' => 'EN999',
				),
				array(
					'label' => __( 'Marketingcode - Terugkerende betalingen', 'planet4-gpnl-blocks' ),
					'attr'  => 'marketingcode_recurring',
					'type'  => 'text',
					'value' => '04888',
				),
				array(
					'label' => __( 'Marketingcode  - Eenmalige betalingen', 'planet4-gpnl-blocks' ),
					'attr'  => 'marketingcode_oneoff',
					'type'  => 'text',
					'value' => '04888',
				),
				array(
					'label' => __( 'iDeal bedanktpagina', 'planet4-gpnl-blocks' ),
					'attr'  => 'returnpage',
					'type'  => 'text',
					'value' => 'https://www.greenpeace.org/nl/',
				),
				array(
					'label' => __( 'iDeal errorpagina', 'planet4-gpnl-blocks' ),
					'attr'  => 'errorpage',
					'type'  => 'text',
					'value' => 'https://www.greenpeace.org/nl/',
				),
			);

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = array(
				'label'         => __( 'GPNL | Donation', 'planet4-gpnl-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpnl-plugin-blocks/admin/images/icon_donation.png' ) . '" />',
				'attrs'         => $fields,
				'post_type'     => P4NLBKS_ALLOWED_PAGETYPE,
			);

			shortcode_ui_register_for_shortcode( 'shortcake_' . self::BLOCK_NAME, $shortcode_ui_args );
		}

		/**
		 * Callback for the shortcake_twocolumn shortcode.
		 * It renders the shortcode based on supplied attributes.
		 *
		 * @param array  $fields Array of fields that are to be used in the template.
		 * @param string $content The content of the post.
		 * @param string $shortcode_tag The shortcode tag (shortcake_blockname).
		 *
		 * @return string The complete html of the block
		 */
		public function prepare_template( $fields, $content, $shortcode_tag ) : string {

			$fields = shortcode_atts(
				[
					'title'                      => '',
					'description'                => '',
					'min_amount'                 => '',
					'oneoff_amount1'             => '',
					'oneoff_amount2'             => '',
					'oneoff_amount3'             => '',
					'oneoff_suggested_amount'    => '',
					'recurring_amount1'          => '',
					'recurring_amount2'          => '',
					'recurring_amount3'          => '',
					'recurring_suggested_amount' => '',
					'suggested_frequency'        => '',
					'allow_frequency_override'   => '',
					'thanktitle'                 => '',
					'thankdescription'           => '',
					'literatuurcode'             => '',
					'marketingcode_recurring'    => '',
					'marketingcode_oneoff'       => '',
					'returnpage'                 => '',
					'errorpage'                  => '',
					'drplus_amount1'             => '',
					'drplus_amount2'             => '',
					'drplus_amount3'             => '',
				],
				$fields,
				$shortcode_tag
			);

			$frequencies = [
				'E' => 'Eenmalig',
				'M' => 'Maandelijks',
				'K' => 'Kwartaal',
				'H' => 'Halfjaarlijks',
				'J' => 'Jaarlijks',
				'F' => 'Maandelijks voor 12 maanden',
			];

			$fields['suggested_frequency'] = [ $fields['suggested_frequency'], strtolower( $frequencies[ $fields['suggested_frequency'] ] ) ];

			$data = [
				'fields' => $fields,
			];

			wp_enqueue_script( 'vue', 'https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.15/vue.min.js', null, '', true );
			wp_enqueue_script( 'vueform', P4NLBKS_ASSETS_DIR . 'js/vue-form-wizard.min.js', [ 'vue' ], '0.8.4', true );
			wp_enqueue_script( 'vueresource', 'https://cdnjs.cloudflare.com/ajax/libs/vue-resource/1.5.0/vue-resource.min.js', [ 'vue', 'vueform' ], '1.5.0', true );
			wp_enqueue_script( 'vuelidate', P4NLBKS_ASSETS_DIR . 'js/vuelidate.min.js', [ 'vue', 'vueform' ], '0.7.4', true );
			wp_enqueue_script( 'vuelidators', P4NLBKS_ASSETS_DIR . 'js/validators.min.js', [ 'vue', 'vueform' ], '0.7.4', true );
			wp_enqueue_script( 'donationform', P4NLBKS_ASSETS_DIR . 'js/donationform.js', [ 'vue', 'vueresource', 'vueform', 'vuelidate', 'vuelidators' ], '2.13.0', true );
			// wp_enqueue_script( 'gpnl_address_autofill', P4NLBKS_ASSETS_DIR . 'js/gpnl-address-autofill.js', [ 'jquery' ], '0.0.1', true );

			// Pass options to frontend code
			wp_localize_script(
				'donationform',
				'formconfig',
				array(
					'min_amount'                 => $fields['min_amount'],
					'oneoff_amount1'             => $fields['oneoff_amount1'],
					'oneoff_amount2'             => $fields['oneoff_amount2'],
					'oneoff_amount3'             => $fields['oneoff_amount3'],
					'oneoff_suggested_amount'    => $fields['oneoff_suggested_amount'],
					'recurring_amount1'          => $fields['recurring_amount1'],
					'recurring_amount2'          => $fields['recurring_amount2'],
					'recurring_amount3'          => $fields['recurring_amount3'],
					'recurring_suggested_amount' => $fields['recurring_suggested_amount'],
					'suggested_frequency'        => $fields['suggested_frequency'],
					'allow_frequency_override'   => $fields['allow_frequency_override'],
					'literatuurcode'             => $fields['literatuurcode'],
					'marketingcode_recurring'    => $fields['marketingcode_recurring'],
					'marketingcode_oneoff'       => $fields['marketingcode_oneoff'],
					'thanktitle'                 => $fields['thanktitle'],
					'thankdescription'           => $fields['thankdescription'],
					'returnpage'                 => $fields['returnpage'],
					'errorpage'                  => $fields['errorpage'],
					'drplus_amount1'             => $fields['drplus_amount1'],
					'drplus_amount2'             => $fields['drplus_amount2'],
					'drplus_amount3'             => $fields['drplus_amount3'],

				)
			);

			// Pass option for address autofill to frontend code.
			wp_localize_script(
				'donationform',
				'get_address_object',
				[
					'ajaxUrl' => admin_url( 'admin-ajax.php' ),
					'nonce'   => wp_create_nonce( 'GPNL_get_address_donation_form' ),
				]
			);

			wp_enqueue_style( 'vueform_style', P4NLBKS_ASSETS_DIR . 'css/vue-form-wizard.min.css', [], '2.7.3' );
			wp_enqueue_style( 'gpnl_donationform_style', P4NLBKS_ASSETS_DIR . 'css/donationform.css', 'vueform_style', '2.10.3' );

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( self::BLOCK_NAME, $data );

			return ob_get_clean();
		}
	}
}


/**
 * Get address with API call.
 */
function get_address_donation_form() {

	// getting the options from the gnnp-settings where the API-key and API-URL are stored.
	$options = get_option( 'planet4nl_options' );

	// Get data from form and validate
	$zipcode = wp_strip_all_tags( $_POST['zipcode'] );
	validate_zipcode_donation_form( $zipcode ) or die();
	$house_no = wp_strip_all_tags( $_POST['house_no'] );
	is_numeric( $house_no ) or die();

	$data_array = [
		'postcode'   => $zipcode,
		'huisnummer' => $house_no,
		'wachtwoord' => $options['gpnl_api_key'],
	];

	$data = wp_json_encode( $data_array );

	// URL for production
	$url = $options['register_url'] . '/validate/postcode';

	$curl = curl_init( $url );

	// API call options:
	curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );
	curl_setopt( $curl, CURLOPT_URL, $url );
	curl_setopt(
		$curl,
		CURLOPT_HTTPHEADER,
		[
			'Content-Type:application/json',
			'Content-Length: ' . strlen( $data ),
		]
	);
	curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'POST' );
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );

	// Execute API call
	$result = json_decode( curl_exec( $curl ) );
	// Get HTTP statuscode
	$http_code = curl_getinfo( $curl, CURLINFO_HTTP_CODE );

	curl_close( $curl );

	// Give the appropriate response to the frontend
	if ( false === $result || 200 !== $http_code ) {
		wp_send_json_error(
			[
				'statuscode' => $http_code,
			],
			$http_code
		);
	}

	wp_send_json_success(
		[
			'statuscode' => $http_code,
			'cUrlresult' => $result,
		],
		$http_code
	);
}

function validate_zipcode_donation_form( $zipcode ) {
	$regex = '/^(?:NL-)?(\d{4})\s*([A-Z]{2})$/i';

	if ( preg_match( $regex, $zipcode ) ) {
		return true;
	}
}

// call php function whenever the ajax call is made to get the address for non-logged in users
add_action( 'wp_ajax_nopriv_get_address_donation_form', 'P4NLBKS\Controllers\Blocks\get_address_donation_form' );
// call php function whenever the ajax call is made to get the address for logged in users
add_action( 'wp_ajax_get_address_donation_form', 'P4NLBKS\Controllers\Blocks\get_address_donation_form' );

/**
 * Store donation for analytics
 */
function cache_donation() {

	$nonce        = htmlspecialchars( wp_strip_all_tags( $_POST['nonce'] ) );
	$key_in_cache = wp_cache_get( $nonce, 'gpnl_cache' );
	if ( ! $key_in_cache ) {
		wp_send_json_error(
			[
				'statuscode' => 400,
			],
			500
		);
	}
	wp_cache_delete( $nonce, 'gpnl_cache' );

	$transaction = wp_strip_all_tags( $_POST['transaction'] );
	$data        = wp_strip_all_tags( $_POST['data'] );

	wp_cache_add( $transaction, $data, 'gpnl_cache', 900 );

	wp_send_json_success(
		[],
		200
	);
}


// call php function whenever the ajax call is made to get the address for non-logged in users
add_action( 'wp_ajax_nopriv_cache_donation', 'P4NLBKS\Controllers\Blocks\cache_donation' );
// call php function whenever the ajax call is made to get the address for logged in users
add_action( 'wp_ajax_cache_donation', 'P4NLBKS\Controllers\Blocks\cache_donation' );
