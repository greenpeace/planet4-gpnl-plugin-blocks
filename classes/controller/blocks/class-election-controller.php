<?php

namespace P4NLBKS\Controllers\Blocks;

if ( ! class_exists( 'GPNL_Election_Controller' ) ) {

	/**
	 * Class GPNL_Election_Controller
	 *
	 * @package P4NLBKS\Controllers\Blocks
	 */
	class GPNL_Election_Controller extends Controller {

		/**
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'gpnl_election';

		/**
		Shortcode UI setup for the election shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {
			$fields = [
				[
					'label' => __( 'Titel', 'planet4-gpnl-blocks' ),
					'attr'  => 'title',
					'type'  => 'text',
					'value' => '',
				],
				[
					'label'       => __( 'Achtergrondafbeelding', 'planet4-blocks-backend' ),
					'attr'        => 'backgroundimage',
					'type'        => 'attachment',
					'libraryType' => [ 'image' ],
					'addButton'   => __( 'Selecteer afbeelding', 'planet4-blocks-backend' ),
					'frameTitle'  => __( 'Selecteer afbeelding', 'planet4-blocks-backend' ),
				],
				[
					'label' => __( 'Bedankt titel', 'planet4-gpnl-blocks' ),
					'attr'  => 'thanktitle',
					'type'  => 'textarea',
					'value' => '',
				],
				[
					'label' => __( 'Bedankt tekst', 'planet4-gpnl-blocks' ),
					'attr'  => 'thanktext',
					'type'  => 'textarea',
					'value' => '',
				],
				[
					'label' => __( 'Doneerknop tekst', 'planet4-gpnl-blocks' ),
					'attr'  => 'donatebutton',
					'type'  => 'text',
					'value' => '',
				],
				[
					'label' => __( 'Link van doneerknop', 'planet4-gpnl-blocks' ),
					'attr'  => 'donatebuttonlink',
					'type'  => 'text',
					'value' => '/doneren',
				],
				[
					'label' => __( 'Opt in tekst', 'planet4-gpnl-blocks' ),
					'attr'  => 'consent',
					'type'  => 'textarea',
					'value' => 'Als je dit aanvinkt, mag Greenpeace je per e-mail op de hoogte houden over onze campagnes. Ook vragen we je af en toe om steun. Afmelden kan natuurlijk altijd.',
				],
				[
					'label' => __( 'Tot wanneer kan er gestemd worden? (<u>tot</u>, niet tot en met)', 'planet4-gpnl-blocks' ),
					'attr'  => 'until',
					'type'  => 'date',
				],
				[
					'label' => __( 'Link naar actievoorwaarden', 'planet4-gpnl-blocks' ),
					'attr'  => 'campaignpolicy',
					'type'  => 'text',
					'meta'  => [
						// translators: placeholder needs to represent the ordinal of the task/column, eg. 1st, 2nd etc.
						'placeholder' => __( 'Link naar de actievoorwaarden van de actie', 'planet4-blocks-backend' ),
					],
				],
				[
					'label' => __( 'Literatuurcode', 'planet4-gpnl-blocks' ),
					'attr'  => 'literaturecode',
					'type'  => 'text',
				],
				[
					'label' => __( 'Campagnecode', 'planet4-gpnl-blocks' ),
					'attr'  => 'campaigncode',
					'type'  => 'text',
				],
				[
					'label' => __( 'Teller: minimaal # stemmen voor zichtbaarheid', 'planet4-gpnl-blocks' ),
					'attr'  => 'countermin',
					'type'  => 'number',
					'meta'  => [
						// translators: placeholder needs to represent the ordinal of the task/column, eg. 1st, 2nd etc.
						'placeholder' => __( 'Vanaf hoeveel stemmen is de teller zichtbaar?', 'planet4-blocks-backend' ),
					],
				],
				[
					'label'   => __( 'Verberg stemresultaat', 'planet4-gpnl-blocks' ),
					'attr'    => 'hideresults',
					'type'    => 'select',
					// somehow setting values to true/false will crash the wordpress editor.
					'options' => [
						[
							'value' => '0',
							'label' => __( 'nee' ),
						],
						[
							'value' => '1',
							'label' => __( 'ja' ),
						],
					],
				],
				[
					'label'   => __( 'Verberg social sharing buttons', 'planet4-gpnl-blocks' ),
					'attr'    => 'hidesharingbuttons',
					'type'    => 'select',
					// somehow setting values to true/false will crash the wordpress editor.
					'options' => [
						[
							'value' => '0',
							'label' => __( 'nee' ),
						],
						[
							'value' => '1',
							'label' => __( 'ja' ),
						],
					],
				],
				[
					'label' => __( 'Sharingtekst voor Twitter', 'planet4-gpnl-blocks' ),
					'attr'  => 'twittertext',
					'type'  => 'textarea',
				],
			];

			for ( $i = 1; $i <= 5; $i++ ) {
				$fields[] =
					[
						'label' => '<hr>' . sprintf( __( 'Optie %s: titel', 'planet4-blocks-backend' ), $i ),
						'attr'  => 'title_' . $i,
						'type'  => 'text',
						'meta'  => [
							// translators: placeholder needs to represent the ordinal of the task/column, eg. 1st, 2nd etc.
							'placeholder' => sprintf( __( 'Titel van optie %s', 'planet4-blocks-backend' ), $i ),
						],
					];

				$fields[] =
					[
						// translators: placeholder needs to represent the ordinal of the task/column, eg. 1st, 2nd etc.
						'label' => sprintf( __( 'Optie %s: Ondertitel', 'planet4-blocks-backend' ), $i ),
						'attr'  => 'subtitle_' . $i,
						'type'  => 'text',
						'meta'  => [
							// translators: placeholder needs to represent the ordinal of the task/column, eg. 1st, 2nd etc.
							'placeholder' => sprintf( __( 'Ondertitel van optie %s', 'planet4-blocks-backend' ), $i ),
						],
					];

				$fields[] =
					[
						// translators: placeholder needs to represent the ordinal of the task/column, eg. 1st, 2nd etc.
						'label' => sprintf( __( 'Optie %s: Omschrijving', 'planet4-blocks-backend' ), $i ),
						'attr'  => 'description_' . $i,
						'type'  => 'textarea',
						'meta'  => [
							// translators: placeholder needs to represent the ordinal of the task/column, eg. 1st, 2nd etc.
							'placeholder' => sprintf( __( 'Omschrijving van optie %s', 'planet4-blocks-backend' ), $i ),
						],
					];

				$fields[] =
					[
						// translators: placeholder needs to represent the ordinal of the task/column, eg. 1st, 2nd etc.
						'label'       => sprintf( __( 'Optie %s: Image', 'planet4-blocks-backend' ), $i ),
						'attr'        => 'attachment_' . $i,
						'type'        => 'attachment',
						'libraryType' => [ 'image' ],
						'addButton'   => __( 'Select Image', 'planet4-blocks-backend' ),
						'frameTitle'  => __( 'Select Image', 'planet4-blocks-backend' ),
					];

				$fields[] =
					[
						// translators: placeholder needs to represent the ordinal of the task/column, eg. 1st, 2nd etc.
						'label' => sprintf( __( 'Optie %s: Marketingcode', 'planet4-blocks-backend' ), $i ),
						'attr'  => 'mcode_' . $i,
						'type'  => 'text',
						'meta'  => [
							// translators: placeholder needs to represent the ordinal of the task/column, eg. 1st, 2nd etc.
							'placeholder' => sprintf( __( 'Marketingcode van optie %s', 'planet4-blocks-backend' ), $i ),
						],
					];
			}

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPNL | Election', 'planet4-gpnl-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpnl-plugin-blocks/admin/images/icon_noindex.png' ) . '" />',
				'attrs'         => $fields,
				'post_type'     => P4NLBKS_ALLOWED_PAGETYPE,
			];

			shortcode_ui_register_for_shortcode( 'shortcake_' . self::BLOCK_NAME, $shortcode_ui_args );

		}
		/**
		 * Get the HTTP(S) URL of the current page.
		 *
		 * @param $server The $_SERVER superglobals array.
		 * @return string The URL.
		 */
		private function current_url( $server ): string {
			//Figure out whether we are using http or https.
			$http = 'http';
			//If HTTPS is present in our $_SERVER array, the URL should
			//start with https:// instead of http://
			if ( isset( $server['HTTPS'] ) ) {
				$http = 'https';
			}
			//Get the HTTP_HOST.
			$host = $server['HTTP_HOST'];
			//Get the REQUEST_URI. i.e. The Uniform Resource Identifier.
			$request_uri = strtok( $_SERVER['REQUEST_URI'], '?' );
			//Finally, construct the full URL.
			//Use the function htmlentities to prevent XSS attacks.
			return $http . '://' . htmlentities( $host ) . htmlentities( $request_uri );
		}


		/**
		 * Get the defined menu with social accounts for usage in sharing buttons
		 *
		 * @param $social_menu
		 *
		 * @return array
		 */
		private function get_social_accounts( $social_menu ) : array {
			$social_accounts = [];
			if ( null !== $social_menu ) {

				$brands = [
					'facebook',
					'twitter',
					'youtube',
					'instagram',
				];
				foreach ( $social_menu as $social_menu_item ) {
					$url_parts = explode( '/', rtrim( $social_menu_item->url, '/' ) );
					foreach ( $brands as $brand ) {
						if ( false !== strpos( $social_menu_item->url, $brand ) ) {
							$social_accounts[ $brand ] = \count( $url_parts ) > 0 ? $url_parts[ \count( $url_parts ) - 1 ] : '';
						}
					}
				}
			}

			return $social_accounts;
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

			wp_enqueue_style( 'gpnl_education_css', P4NLBKS_ASSETS_DIR . 'css/gpnl-educationcovers.css', [], '2.11.0' );
			wp_enqueue_style( 'gpnl_slick_css', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css', [ 'slick' ], '2.11.0' );
			wp_enqueue_style( 'gpnl_election_css', P4NLBKS_ASSETS_DIR . 'css/gpnl-election.css', [], '2.11.0' );
			wp_enqueue_style( 'gpnl_petition_css', P4NLBKS_ASSETS_DIR . 'css/gpnl-petition.css', [], '2.3.6' );

			wp_enqueue_script( 'gpnl_election_js', P4NLBKS_ASSETS_DIR . 'js/gpnl-election.js', [ 'jquery', 'slick' ], '2.11.0', true );
			// Enqueue the script:
			$attributes = [
				'title'              => '',
				'until'              => '',
				'backgroundimage'    => '',
				'thanktitle'         => '',
				'thanktext'          => '',
				'donatebutton'       => '',
				'consent'            => '',
				'campaignpolicy'     => '',
				'literaturecode'     => '',
				'campaigncode'       => '',
				'countermin'         => '',
				'hideresults'        => '',
				'hidesharingbuttons' => '',
				'donatebuttonlink'   => '',
				'twittertext'        => '',
			];

			$options = [];
			for ( $i = 1; $i <= 5; $i++ ) {
				if ( isset( $fields[ 'title_' . $i ] ) ) {
					$option_tmp    =
					[
						'title'       => $fields[ 'title_' . $i ],
						'subtitle'    => $fields[ 'subtitle_' . $i ],
						'description' => $fields[ 'description_' . $i ],
						'mcode'       => $fields[ 'mcode_' . $i ],
						'attachment'  =>
							[
								'src' => wp_get_attachment_image_src( $fields[ 'attachment_' . $i ], 'large' )[0],
							],
					];
					$options[ $i ] = $option_tmp;

					// Pass options to frontend code
					wp_localize_script(
						'gpnl_election_js',
						'election_object_' . $i,
						array(
							'title'       => $fields[ 'title_' . $i ],
							'subtitle'    => $fields[ 'subtitle_' . $i ],
							'description' => $fields[ 'description_' . $i ],
							'mcode'       => $fields[ 'mcode_' . $i ],
						)
					);
				} else {
					break;
				}
			}

			$fields = shortcode_atts(
				$attributes,
				$fields,
				$shortcode_tag
			);

			$social_menu               = wp_get_nav_menu_items( 'Footer Social' );
			$fields['social_accounts'] = $this->get_social_accounts( $social_menu );
			$fields['current_url']     = $this->current_url( $_SERVER );
			$fields['twittertext']     = rawurlencode( $fields['twittertext'] );

			$fields['backgroundimage'] =
							[
								'src' => wp_get_attachment_image_src( $fields['backgroundimage'], 'full' )[0],
							];

			$data = [
				'fields'  => $fields,
				'options' => $options,
			];
			// Pass options to frontend code
			wp_localize_script(
				'gpnl_election_js',
				'election_object',
				array(
					'ajaxUrl'            => admin_url( 'admin-ajax.php' ),
					// url for php file that process ajax request to WP
					'nonce'              => wp_create_nonce( 'GPNL_Petitions' ),
					'analytics_campaign' => $fields['campaigncode'],
					'literature_code'    => $fields['literaturecode'],
					'hideresults'        => $fields['hideresults'],
					'counter_min'        => $fields['countermin'],
				)
			);

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( self::BLOCK_NAME, $data );

			return ob_get_clean();
		}


	}
}
