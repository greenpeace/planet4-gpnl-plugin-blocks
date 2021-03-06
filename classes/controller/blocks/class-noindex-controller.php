<?php

namespace P4NLBKS\Controllers\Blocks;

if ( ! class_exists( 'No_Index_Controller' ) ) {
	/**
	 * @noinspection AutoloadingIssuesInspection
	 */

	/**
	 * Class No_Index_Controller
	 *
	 * @package P4NLBKS\Controllers\Blocks
	 */
	class No_Index_Controller extends Controller {



		/**
	* @const string BLOCK_NAME
*/
		const BLOCK_NAME = 'noindex';

		public function load() {
			parent::load();

			//	add a function on saving of a post and add a function on loading of the page, before outputting the header
			if ( is_admin() ) {
				add_action( 'save_post', [ $this, 'delete_tags_and_categories' ] );
			} else {
				add_action( 'template_redirect', [ $this, 'add_robots_noindex_to_wp_header' ] );
			}
		}


		/**
			Shortcode UI setup for the noindexblock shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {
			$fields = array(
				array(
					'label' => __( 'Waarschuwing!', 'planet4-gpnl-blocks' ),
					'attr'  => 'title',
					'type'  => 'textarea',
					'value' => "Dit blok dient om pagina's te 'verbergen'. Gebruik dit enkel voor dingen als kopie-pagina's voor petities en speciale pagina's maar mailmarketing.\nWat dit doet?\n-Een instructie aan zoekmachines om deze pagina niet te indexeren\n-Verwijderen van tags\n-Verwijderen van categorien",
					'meta'  => array( 'readonly' => true ),
				),
			);

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = array(
				'label'         => __( 'GPNL | Hide Page', 'planet4-gpnl-blocks' ),
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
					'test' => '',
				),
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


		/**
		 * Should only run on saving of pages/posts.
		 * Checks if the noindex block is used, if so, removes the categories and tags
		 */
		public function delete_tags_and_categories() {
			if ( ! empty( $_POST ) && defined( $_POST['content']) && has_shortcode( $_POST['content'], 'shortcake_noindex' ) ) {
				wp_set_post_terms( $_POST['post_ID'], [], 'post_tag' );
				wp_set_post_terms( $_POST['post_ID'], [], 'category' );
			}
		}

		/**
		 * Is meant to be use just before the rendering starts, so to modify the header dependent of the page content.
		 * Checks if the noindex block is used, if so add the robots noindex metatag
		 */
		public function add_robots_noindex_to_wp_header() {
			global $post;

			if ( ! is_object( $post ) ) {
				return;
			}
			$post_content = $post->post_content;

			if ( ( null !== $post_content ) && has_shortcode( $post_content, 'shortcake_noindex' ) ) {
				add_action( 'wp_head', 'wp_no_robots' );
			}
		}

	}
}

