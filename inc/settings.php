<?php
/**
 * Settings and options.
 */

namespace Required\GoogleAnalytics;

/**
 * Registers settings and their data.
 *
 * @since 1.0.0
 */
function register_settings(): void {
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

	register_setting(
		'reading',
		'required_ga4_measurement_id',
		[
			'show_in_rest'      => true,
			'type'              => 'string',
			'description'       => __( 'Measurement ID of the Google Analytics 4 property to track.', 'required-google-analytics' ),
			'default'           => '',
			'sanitize_callback' => null, // Added below due to missing second argument, see https://core.trac.wordpress.org/ticket/15335.
		]
	);

	add_filter( 'sanitize_option_required_ga4_measurement_id', __NAMESPACE__ . '\sanitize_ga4_measurement_id', 10, 2 );
}

/**
 * Sanitizes a Google Analytics property ID from user input.
 *
 * @since 1.0.0
 *
 * @param string $value The unsanitized option value.
 * @param string $option The option name.
 * @return string The sanitized option value.
 */
function sanitize_ga_property( string $value, string $option ): string {
	$value = (string) $value;
	$value = trim( $value );

	if ( '' === $value ) {
		return $value;
	}

	$error = '';

	// Ensure ID is uppercase.
	$value = strtoupper( $value );

	// Check for the format.
	if ( ! preg_match( '/^UA-\d+-\d+$/', $value ) ) {
		$error = sprintf(
			/* translators: %s UA-XXXXX-XX */
			__( 'The property ID of the Google Analytics property doesn&#8217;t match the required format %s.', 'required-google-analytics' ),
			'<code>UA-XXXXX-XX</code>'
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
 * Sanitizes a Google Analytics property ID from user input.
 *
 * @since 3.0.0
 *
 * @param string $value The unsanitized option value.
 * @param  string $option The option name.
 * @return string The sanitized option value.
 */
function sanitize_ga4_measurement_id( string $value, string $option ): string {
	$value = (string) $value;
	$value = trim( $value );

	if ( '' === $value ) {
		return $value;
	}

	$error = '';

	// Ensure ID is uppercase.
	$value = strtoupper( $value );

	// Check for the format.
	if ( ! preg_match( '/^G-[A-Z0-9]+$/', $value ) ) {
		$error = sprintf(
			/* translators: %s: G-XXXXXXXXXX */
			__( 'The measurement ID of the Google Analytics 4 property doesn&#8217;t match the required format %s.', 'required-google-analytics' ),
			'<code>G-XXXXXXXXXX</code>'
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
function register_settings_ui(): void {
	add_settings_section(
		'required-google-analytics',
		'<span id="google-analytics">' . __( 'Google Analytics', 'required-google-analytics' ) . '</span>',
		static function(): void {
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
		static function(): void {
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
					/* translators: %s UA-XXXXX-XX */
					__( 'The tracking ID and property number in the format %s.', 'required-google-analytics' ),
					'<code>UA-XXXXX-XX</code>'
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

	add_settings_field(
		'required-google-analytics-measurement-id',
		__( 'Measurement ID', 'required-google-analytics' ),
		static function(): void {
			?>
			<input
				name="required_ga4_measurement_id"
				type="text"
				id="required-google-analytics-measurement-id"
				aria-describedby="required-google-analytics-measurement-id-description"
				value="<?php echo esc_attr( get_option( 'required_ga4_measurement_id' ) ); ?>"
				class="regular-text code"
			>
			<p class="description" id="required-google-analytics-measurement-id-description">
				<?php
				printf(
					/* translators: %s G-XXXXXXXXXX */
					__( 'The Measurement ID uses the format %s, and identifies the data stream sending data to your Google Analytics 4 property', 'required-google-analytics' ),
					'<code>G-XXXXXXXXXX</code>'
				);
				?>
			</p>
			<?php
		},
		'reading',
		'required-google-analytics',
		[
			'label_for' => 'required-google-analytics-measurement-id',
		]
	);
}

/**
 * Adds settings link to action links displayed in the Plugins list table.
 *
 * @since 2.2.0
 *
 * @param string[] $actions An array of plugin action links.
 * @return string[] An array of plugin action links.
 */
function add_settings_action_link( array $actions ): array {
	if ( current_user_can( 'manage_options' ) ) {
		$settings_action = sprintf(
			'<a href="%s">%s</a>',
			esc_url( admin_url( 'options-reading.php#google-analytics' ) ),
			__( 'Settings', 'required-google-analytics' )
		);
		array_unshift( $actions, $settings_action );
	}

	return $actions;
}
