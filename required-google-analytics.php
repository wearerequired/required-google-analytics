<?php
/**
 * Plugin Name: Google Analytics
 * Plugin URI:  https://github.com/wearerequired/google-analytics/
 * Description: Adds Google's analytics.js to your site, the modern way.
 * Version:     1.1.0
 * Author:      required
 * Author URI:  https://required.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * Copyright (c) 2018 required (email: info@required.ch)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @package Required\GoogleAnalytics
 */

namespace Required\GoogleAnalytics;

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require __DIR__ . '/vendor/autoload.php';
}

// Settings and options.
add_action( 'init', __NAMESPACE__ . '\register_settings' );
add_action( 'admin_init', __NAMESPACE__ . '\register_settings_ui' );

// Extended dependencies API.
add_filter( 'script_loader_tag', __NAMESPACE__ . '\enqueue_scripts_async', 50, 2 );
add_filter( 'script_loader_src', __NAMESPACE__ . '\enqueue_scripts_without_version', 50, 2 );

/**
 * Enqueues the async tracking snippet with allowing modern browsers to preload the script.
 *
 * @since 1.0.0
 *
 * @link https://developers.google.com/analytics/devguides/collection/analyticsjs/#alternative_async_tracking_snippet
 */
function enqueue_google_analytics_tracking_script() {
	$property_id = get_option( 'required_ga_property_id' );
	if ( ! $property_id ) {
		return;
	}

	wp_enqueue_script(
		'google-analytics',
		'https://www.google-analytics.com/analytics.js'
	);
	wp_script_add_data( 'google-analytics', 'async', true );
	wp_script_add_data( 'google-analytics', 'no-version', true );

	$property_id = wp_json_encode( $property_id );
	wp_add_inline_script(
		'google-analytics',
		<<<JS
window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;ga("create", {$property_id}, "auto");ga("set", "forceSSL", true);ga("set", "anonymizeIp", true);ga("send","pageview");
JS
		,
		'before'
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_google_analytics_tracking_script' );
