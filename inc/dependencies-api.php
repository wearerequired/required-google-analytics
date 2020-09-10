<?php
/**
 * Extensions for WordPress' Dependencies API.
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
	if ( ! $async ) {
		return $tag;
	}

	if ( ! preg_match( '#\sasync(=|>|\s)#', $tag ) ) {
		$tag = preg_replace( '#(?=></script>)#', ' async', $tag, 1 );
	}

	return $tag;
}
