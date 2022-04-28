<?php
/**
 * Plugin Name: Google Analytics
 * Plugin URI:  https://github.com/wearerequired/required-google-analytics
 * Description: Adds Google's global site tag (gtag.js) to your site, the modern way.
 * Version:     3.0.0
 * Author:      required
 * Author URI:  https://required.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * Copyright (c) 2018-2022 required (email: info@required.ch)
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
 */

namespace Required\GoogleAnalytics;

use function Required\Traduttore_Registry\add_project;

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require __DIR__ . '/vendor/autoload.php';
}

// Settings and options.
add_action( 'init', __NAMESPACE__ . '\register_settings' );
add_action( 'admin_init', __NAMESPACE__ . '\register_settings_ui' );
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), __NAMESPACE__ . '\add_settings_action_link', 10, 2 );

// Extended dependencies API.
add_filter( 'script_loader_tag', __NAMESPACE__ . '\enqueue_scripts_async', 50, 2 );

// Translations.
add_action( 'init', __NAMESPACE__ . '\register_traduttore_project' );

/**
 * Registers project for translations via Traduttore.
 *
 * @since 2.1.0
 */
function register_traduttore_project(): void {
	add_project(
		'plugin',
		'required-google-analytics',
		'https://translate.required.com/api/translations/required/required-google-analytics/'
	);
}

/**
 * Enqueues the async tracking snippet with allowing modern browsers to preload the script.
 *
 * @since 1.0.0
 * @since 2.0.0 Switched from analytics.js to gtag.js.
 *
 * @link https://developers.google.com/analytics/devguides/collection/gtagjs/
 */
function enqueue_google_analytics_tracking_script(): void {
	$property_id    = get_option( 'required_ga_property_id' );
	$measurement_id = get_option( 'required_ga4_measurement_id' );
	if ( ! $property_id && ! $measurement_id ) {
		return;
	}

	// phpcs:ignore WordPress.WP.EnqueuedResourceParameters -- External file.
	wp_enqueue_script(
		'google-analytics',
		add_query_arg(
			'id',
			$property_id ?: $measurement_id,
			'https://www.googletagmanager.com/gtag/js'
		),
		[],
		null
	);
	wp_script_add_data( 'google-analytics', 'async', true );

	$additional_config_info = [
		'anonymize_ip' => true,
		'forceSSL'     => true,
	];

	/**
	 * Filters the additional config info passed to the config command.
	 *
	 * @since 2.2.0
	 *
	 * @link https://developers.google.com/gtagjs/reference/api#config
	 *
	 * @param array $additional_config_info Additional config info passed to the config command.
	 */
	$additional_config_info = apply_filters( 'required_ga.additional_config_info', $additional_config_info );

	// Load JavaScript file for inline usage.
	$script    = file_get_contents( __DIR__ . '/assets/dist/inline-script.js' );
	$variables = 'window.requiredGAPropertyId="' . esc_js( $property_id ) . '";' .
		'window.requiredGA4MeasurementId="' . esc_js( $measurement_id ) . '";' .
		'window.requiredGAAdditionalConfigInfo=' . wp_json_encode( $additional_config_info ) . ';';
	wp_add_inline_script(
		'google-analytics',
		$variables . "\n" . $script,
		'before'
	);

	/**
	 * Filters whether to enable event tracking.
	 *
	 * @since 2.1.0
	 *
	 * @param bool $enable Whether to enable event tracking. Default false.
	 */
	$event_tracking_enabled = apply_filters( 'required_ga.enable_event_tracking', false );

	if ( $event_tracking_enabled ) {
		wp_enqueue_script(
			'google-analytics-events',
			plugins_url( '/assets/dist/events.js', __FILE__ ),
			[ 'google-analytics' ],
			'20220428',
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_google_analytics_tracking_script' );

/**
 * Adds hint to prefetch DNS for www.google-analytics.com.
 *
 * The global site tag (gtag.js) loads https://www.google-analytics.com/analytics.js.
 *
 * @since 2.4.0
 *
 * @param string[] $urls          URLs to print for resource hints.
 * @param string   $relation_type The relation type the URLs are printed for.
 * @return string[] URLs to print for resource hints.
 */
function resource_hints( array $urls, string $relation_type ): array {
	if ( 'dns-prefetch' === $relation_type ) {
		$urls[] = 'https://www.google-analytics.com';
	}

	return $urls;
}
add_filter( 'wp_resource_hints', __NAMESPACE__ . '\resource_hints', 10, 2 );
