<?php

namespace P4NLBKS\Controllers\Blocks;

if ( ! class_exists( 'Helper' ) ) {

	/**
	 * Class Helper
	 *
	 * @package P4NLBKS\Controllers\Blocks
	 */
	class Helper {


		public function __construct() {

			$this_class = get_class();
			$methods    = get_class_methods( $this_class );

			foreach ( $methods as $method_name ) {
				add_action( 'wp_ajax_' . $method_name, 'P4NLBKS\Controllers\Blocks\\' . $method_name );
				add_action( 'wp_ajax_nopriv_' . $method_name, 'P4NLBKS\Controllers\Blocks\\' . $method_name );
			}
		}

		/**
		 * Callback for front end to request nonces
		 *
		 * @return void a true nonce
		 */
		public function request_id() {

			// Generate an id and try to get it from cache.
			$unique_id       = hexdec( bin2hex( openssl_random_pseudo_bytes( 3 ) ) );
			$key_unavailable = wp_cache_get( $unique_id, 'gpnl_cache' );

			// If it's already in the cache, keep on trying to find a open position.
			while ( $key_unavailable ) {
				$unique_id       = hexdec( bin2hex( openssl_random_pseudo_bytes( 3 ) ) );
				$key_unavailable = wp_cache_get( $unique_id, 'gpnl_cache', true );
			}
			wp_cache_add( $unique_id, true, 'gpnl_cache', 300 );

			wp_send_json_success(
				[
					'nonce' => $unique_id,
				],
				200
			);
		}

		/**
		 * Check if a nonce is valid, if so remove it for reusing
		 *
		 * @param string $id nonce to be submitted with a front end form.
		 *
		 * @return bool indicating valid
		 */
		public function check_id( $id ) {
			$key_in_cache = wp_cache_get( $id, 'gpnl_cache' );
			if ( ! $key_in_cache ) {
				return false;
			}
			wp_cache_delete( $id, 'gpnl_cache' );

			return true;
		}

	}
}

