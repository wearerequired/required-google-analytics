<?php
/**
 * Plugin Name: Google Analytics
 * Plugin URI:  https://github.com/wearerequired/required-google-analytics
 * Description: Adds Google's global site tag (gtag.js) to your site, the modern way.
 * Version:     2.1.0
 * Author:      required
 * Author URI:  https://required.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * Copyright (c) 2018-2020 required (email: info@required.ch)
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

// Extended dependencies API.
add_filter( 'script_loader_tag', __NAMESPACE__ . '\enqueue_scripts_async', 50, 2 );

// Translations.
add_action( 'init', __NAMESPACE__ . '\register_traduttore_project' );

/**
 * Registers project for translations via Traduttore.
 *
 * @since 2.1.0
 */
function register_traduttore_project() {
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
function enqueue_google_analytics_tracking_script() {
	$property_id = get_option( 'required_ga_property_id' );
	if ( ! $property_id ) {
		return;
	}

	// phpcs:ignore WordPress.WP.EnqueuedResourceParameters -- External file.
	wp_enqueue_script(
		'google-analytics',
		add_query_arg(
			'id',
			$property_id,
			'https://www.googletagmanager.com/gtag/js'
		),
		[],
		null
	);
	wp_script_add_data( 'google-analytics', 'async', true );

	// Load JavaScript file for inline usage. Replace placeholder for property ID.
	$script = file_get_contents( __DIR__ . '/assets/js/inline-script.js' );
	$script = str_replace( '__PROPERTY_ID__', esc_js( $property_id ), $script );
	wp_add_inline_script(
		'google-analytics',
		$script,
		'before'
	);

	/**
	 * Filters whether to enable event tracking.
	 *
	 * @since 2.1.0
	 *
	 * @param bool $enable Whether to enable event tracking.Default false.
	 */
	$event_tracking_enabled = apply_filters( 'required_ga.enable_event_tracking', false );

	if ( $event_tracking_enabled ) {
		wp_enqueue_script(
			'google-analytics-events',
			plugins_url( '/assets/js/events.js', __FILE__ ),
			[ 'google-analytics' ],
			'20200315',
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_google_analytics_tracking_script' );
