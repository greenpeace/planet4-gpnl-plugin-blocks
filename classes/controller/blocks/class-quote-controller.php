<?php

namespace P4NLBKS\Controllers\Blocks;

if ( ! class_exists( 'GPNL_quote_Controller' ) ) {
	/**
	 * @noinspection AutoloadingIssuesInspection
	 */

	/**
	 * Class GPNL_quote_Controller
	 *
	 * @package P4NLBKS\Controllers\Blocks
	 */
	class GPNL_quote_Controller extends Controller {



		/**
	* @const string BLOCK_NAME
*/
		const BLOCK_NAME = 'gpnl_quote';


		/**
			Shortcode UI setup for the noindexblock shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {
			$fields = array(
				array(
					'label' => __( 'Quote', 'planet4-gpnl-blocks' ),
					'attr'  => 'quote',
					'type'  => 'textarea',
					'value' => "We are going to exit the fossil fuel era.
It is inevitable",
				),
				array(
					'label' => __( 'Quotee', 'planet4-gpnl-blocks' ),
					'attr'  => 'quotee',
					'type'  => 'text',
					'value' => "Elon Musk",
				),
				array(
					'label'       => __( 'Afbeelding', 'planet4-blocks-backend' ),
					'attr'        => 'image',
					'type'        => 'attachment',
					'libraryType' => [ 'image' ],
					'addButton'   => __( 'Selecteer afbeelding', 'planet4-blocks-backend' ),
					'frameTitle'  => __( 'Selecteer afbeelding', 'planet4-blocks-backend' ),
				),
			);

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = array(
				'label'         => __( 'GPNL | Quote', 'planet4-gpnl-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpnl-plugin-blocks/admin/images/icon_noindex.png' ) . '" />',
				'attrs'         => $fields,
				'post_type'     => P4NLBKS_ALLOWED_PAGETYPE,
			);

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
				array(
					'quote'  => '',
					'quotee' => '',
					'image'  => '',
				),
				$fields,
				$shortcode_tag
			);

			// If an image is selected
			if ( isset( $fields['image'] ) && $image = wp_get_attachment_image_src( $fields['image'], 'full' ) ) {
				// load the image from the library
				$fields['image']        = $image[0];
				$fields['alt_text']     = get_post_meta( $fields['image'], '_wp_attachment_image_alt', true );
				$fields['image_srcset'] = wp_get_attachment_image_srcset( $fields['image'], 'full', wp_get_attachment_metadata( $fields['image'] ) );
				$fields['image_sizes']  = wp_calculate_image_sizes( 'full', null, null, $fields['image'] );
			}

			$data = [
				'fields' => $fields,
			];

			wp_enqueue_style( 'gpnl_quote_css', P4NLBKS_ASSETS_DIR . 'css/gpnl-quote.css', [], '2.2.29' );

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( self::BLOCK_NAME, $data );

			return ob_get_clean();
		}


	}
}

