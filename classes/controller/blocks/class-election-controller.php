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
					'label' => __( 'Test', 'planet4-gpnl-blocks' ),
					'attr'  => 'test',
					'type'  => 'text',
					'value' => 'bla',
				],
			];

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

			// wp_enqueue_style( 'gpnl_election_css', P4NLBKS_ASSETS_DIR . 'css/gpnl-liveblog.css', [], '2.4.0' );
			// wp_enqueue_script( 'gpnl_election_js', P4NLBKS_ASSETS_DIR . 'js/gpnl-election.js', [ 'jquery' ], '2.4.0', true );

			$fields = shortcode_atts(
				[
					'test' => '',
				],
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
