<?php

namespace P4NLBKS\Controllers\Blocks;

if ( ! class_exists( 'GPNL_2colembed_Controller' ) ) {

	/**
	 * Class GPNL_2colembed_Controller
	 *
	 * @package P4NLBKS\Controllers\Blocks
	 */
	class GPNL_2colembed_Controller extends Controller {



		/**
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'gpnl_2colembed';


		/**
		Shortcode UI setup for the 2colembed shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {
			$fields = [
				[
					'label' => __( 'Titel boven', 'planet4-gpnl-blocks' ),
					'attr'  => 'title',
					'type'  => 'text',
					'value' => '',
				],
				[
					'label' => __( 'Omschrijving boven', 'planet4-gpnl-blocks' ),
					'attr'  => 'description',
					'type'  => 'textarea',
					'value' => '',
				],
				[
					'label' => __( 'Titel links', 'planet4-gpnl-blocks' ),
					'attr'  => 'column_title',
					'type'  => 'text',
					'value' => '',
				],
				[
					'label' => __( 'Omschrijving links', 'planet4-gpnl-blocks' ),
					'attr'  => 'column_description',
					'type'  => 'textarea',
					'value' => '',
				],
				[
					'label' => __( 'CTA Button tekst', 'planet4-gpnl-blocks' ),
					'attr'  => 'column_cta_text',
					'type'  => 'text',
					'value' => '',
				],
				[
					'label' => __( 'CTA Button link', 'planet4-gpnl-blocks' ),
					'attr'  => 'column_cta_link',
					'type'  => 'text',
					'value' => '',
				],
				[
					'label' => __( 'Iframe link', 'planet4-gpnl-blocks' ),
					'attr'  => 'iframe_src',
					'type'  => 'text',
					'value' => '',
				],
				[
					'label' => __( 'Iframe hoogte', 'planet4-gpnl-blocks' ),
					'attr'  => 'iframe_height',
					'type'  => 'number',
					'value' => '',
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPNL | 2colembed', 'planet4-gpnl-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpnl-plugin-blocks/admin/images/icon_noindex.png' ) . '" />',
				'attrs'         => $fields,
				'post_type'     => P4NLBKS_ALLOWED_PAGETYPE,
			];

			shortcode_ui_register_for_shortcode( 'shortcake_' . self::BLOCK_NAME, $shortcode_ui_args );

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


			$fields = shortcode_atts(
				$fields,
				$shortcode_tag
			);

			$data = [
				'fields' => $fields,
			];

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( self::BLOCK_NAME, $data );

			return ob_get_clean();
		}


	}
}
