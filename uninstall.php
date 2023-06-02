<?php
/**
 * Functionality that is executed when the plugin is uninstalled via built-in WordPress commands.
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'required_ga_property_id' );
delete_option( 'required_ga4_measurement_id' );
