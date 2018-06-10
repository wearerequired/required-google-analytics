<?php
/**
 * Settings and options.
 *
 * @package Required\GoogleAnalytics
 */

namespace Required\GoogleAnalytics;

/**
 * Register settings and their data.
 *
 * @since 1.0.0
 */
function register_settings() {
	register_setting(
		'reading',
		'required_ga_property_id',
		[
			'show_in_rest'      => true,
			'type'              => 'string',
			'description'       => __( 'Property ID of the Google Analytics property to track.', 'required-google-analytics' ),
			'default'           => '',
			'sanitize_callback' => null, // Added below due to missing second argument, see https://core.trac.wordpress.org/ticket/15335.
		]
	);

	add_filter( 'sanitize_option_required_ga_property_id', __NAMESPACE__ . '\sanitize_ga_property', 10, 2 );
}
add_action( 'init', __NAMESPACE__ . '\register_settings' );

/**
 * Sanitizes a Google Analytics property ID from user input.
 *
 * @since 1.0.0
 *
 * @param string $value The unsanitized option value.
 * @param  string $option The option name.
 * @return string The sanitized option value.
 */
function sanitize_ga_property( $value, $option ) {
	$value = (string) $value;
	$value = trim( $value );

	if ( '' === $value ) {
		return $value;
	}

	$error = '';

	// Ensure 'UA' is uppercase.
	$value = strtoupper( $value );

	// Check for the format.
	if ( ! preg_match( '/^UA-\d+-\d+$/', $value ) ) {
		$error = sprintf(
			/* translators: %s: UA-XXXXX-Y */
			__( 'The property ID of the Google Analytics property doesn&#8217;t match the required format %s.', 'required-google-analytics' ),
			'<code>UA-XXXXX-Y</code>'
		);
	}

	// Fallback to previous value and register a settings error to be displayed to the user.
	if ( ! empty( $error ) ) {
		$value = get_option( $option );
		if ( function_exists( 'add_settings_error' ) ) {
			add_settings_error( $option, "invalid_{$option}", $error );
		}
	}

	return $value;
}

/**
 * Registers the admin UI for the settings.
 *
 * @since 1.0.0
 */
function register_settings_ui() {
	add_settings_section(
		'required-google-analytics',
		__( 'Google Analytics', 'required-google-analytics' ),
		function() {
			?>
			<p>
				<?php
				_e( 'Measure with Google Analytics how users interact with your website content.', 'required-google-analytics' );
				echo '<br>';
				_e( 'The IP address anonymization for all hits sent to Google Analytics is enabled by default.', 'required-google-analytics' );
				?>
			</p>
			<?php
		},
		'reading'
	);

	add_settings_field(
		'required-google-analytics-property-id',
		__( 'Property ID', 'required-google-analytics' ),
		function() {
			?>
			<input
				name="required_ga_property_id"
				type="text"
				id="required-google-analytics-property-id"
				aria-describedby="required-google-analytics-property-id-description"
				value="<?php echo esc_attr( get_option( 'required_ga_property_id' ) ); ?>"
				class="regular-text code"
			>
			<p class="description" id="required-google-analytics-property-id-description">
				<?php
				printf(
					/* translators: %s: UA-XXXXX-Y */
					__( 'The string %s of the the property ID (also called the "tracking ID") of the Google Analytics property you wish to track.', 'required-google-analytics' ),
					'<code>UA-XXXXX-Y</code>'
				);
				?>
			</p>
			<?php
		},
		'reading',
		'required-google-analytics',
		[
			'label_for' => 'required-google-analytics-property-id',
		]
	);
}
add_action( 'admin_init', __NAMESPACE__ . '\register_settings_ui' );

