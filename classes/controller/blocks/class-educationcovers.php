<?php

/**
 * Did you just autogenerate this?
 * Don't forget (if applicable) to create a twig template
 * Template should be called:
 * 'gpnl_educationcovers.twig'
 * Placed under: '/includes/blocks'
 */

namespace P4NLBKS\Controllers\Blocks;

if ( ! class_exists( 'GPNL_Educationcovers_Controller' ) ) {

	/**
	 * Class GPNL_Educationcovers_Controller
	 *
	 * @package P4NLBKS\Controllers\Blocks
	 */
	class GPNL_Educationcovers_Controller extends Controller {



		/**
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'gpnl_educationcovers';


		/**
		Shortcode UI setup for the educationcovers shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {
			$fields = [];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPNL | Lesmateriaal overzicht', 'planet4-gpnl-blocks' ),
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

			 wp_enqueue_style( 'gpnl_educationcovers_css', P4NLBKS_ASSETS_DIR . 'css/gpnl-educationcovers.css', [], '2.11.0' );
			 wp_enqueue_script( 'gpnl_educationcovers_js', P4NLBKS_ASSETS_DIR . 'js/gpnl-educationcovers.js', [ 'jquery' ], '2.11.0', true );

			$args     = array(
				'numberposts'   => -1, // magic number for retrieving all
				'category_name' => 'lesmateriaal',
				'post_type'     => 'page',
			);
			$tagcloud = [];
			$i        = 0;

			$pages = get_posts( $args );
			foreach ( $pages as $page ) {
				// Fetch the featured image and tags from each of the entries
				$page_id            = $page->ID;
				$pages[ $i ]->image = get_the_post_thumbnail_url( $page_id, 'large' );
				$post_tags          = get_the_tags( $page_id );
				// Add the names of the tags to the associated page and the global tagcloud
				$post_tag_names = [];
				if ( ! empty( $post_tags ) ) {
					foreach ( $post_tags as $post_tag ) {
						array_push( $post_tag_names, $post_tag->name );
					}
					$pages[ $i ]->tags = wp_json_encode( $post_tag_names );
					$tagcloud          = array_merge( $tagcloud, $post_tag_names );
				}

				$pages[ $i ]->link = get_permalink( $page_id );
				$i++;
			}

			// Filter out audiences, remove duplicates and sort the tags
			$audiences = [ 'PO', 'VO', 'DO' ];
			$tagcloud  = array_diff( array_unique( $tagcloud ), $audiences );
			sort( $tagcloud );

			$fields = shortcode_atts(
				[
					'pages'     => $pages,
					'tags'      => $tagcloud,
					'audiences' => $audiences,
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
