<?php
/**
 * Extensions for WordPress' Dependencies API.
 *
 * @package Required\GoogleAnalytics
 */

namespace Required\GoogleAnalytics;

/**
 * Extends the HTML script tag of an enqueued script with async="async".
 *
 * Register support with:
 *
 *     wp_script_add_data( 'script-handle', 'async', true );
 *
 * @since 1.0.0
 *
 * @param string $tag    The `<script>` tag for the enqueued script.
 * @param string $handle The script's registered handle.
 * @return string The `<script>` tag for the enqueued script.
 */
function enqueue_scripts_async( string $tag, string $handle ): string {
	$async = wp_scripts()->get_data( $handle, 'async' );
	if ( $async ) {
		return str_replace( ' src=', ' async="async" src=', $tag );
	}

	return $tag;
}

/**
 * Removes the version string appended to a script URL.
 *
 * Register support with:
 *
 *   wp_script_add_data( 'handle', 'no-version', true );
 *
 * @since 1.0.0
 *
 * @param string $src    The source URL for the enqueued script.
 * @param string $handle The script's registered handle.
 * @return string The source URL for the enqueued script.
 */
function enqueue_scripts_without_version( string $src, string $handle ): string {
	if ( false === strpos( $src, 'ver=' ) ) {
		return $src;
	}

	$no_version = wp_scripts()->get_data( $handle, 'no-version' );
	if ( $no_version ) {
		return remove_query_arg( 'ver', $src );
	}

	return $src;
}
