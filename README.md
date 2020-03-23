# Google Analytics

A lightweight WordPress plugin to add Google's global site tag (gtag.js) to your site, the modern way.

The modern way? The script is added with support for preloading, which will provide a small performance boost on modern browsers.  
Modern browsers are those that support the `async` script attribute. This does not include IE 9 and older mobile browsers.

Beside that, the plugin doesn't do more. No fancy report view in your admin, no authentication requirements, no additional scripts, and no ads for random blog posts.

## Installation

Install via Composer

	$ composer require wearerequired/required-google-analytics

Add you property ID at Settings > Reading > Google Analytics.

## Disable Analytics for opted-out users

The plugin provides a function to let users [opt out of Google Analytics measurement](https://developers.google.com/analytics/devguides/collection/gtagjs/user-opt-out). If the function `window.requiredGADoOptOut()` is called a `ga-opted-out` item is stored in the local browser storage and the `window` property `window['ga-disable-PROPERTY_ID']` will be set to `true`.

Example:

	<a href="#" onclick="requiredGADoOptOut();return false">Opt-out from Google Analytics for this site.</a>

## Event Tracking

The plugin provides a small layer that transform data attributes on an HTML element to a [Google Analytics event](https://developers.google.com/analytics/devguides/collection/gtagjs/events). To enable the layer use the following filter:

	add_filter( 'required_ga.enable_event_tracking', '__return_true' );

To track the click event of a button you can now use the following data attributes:

* `data-ga-event-action`: Event action.
* `data-ga-event-on`: Optional. Event trigger. Defaults to `click` and can be any [DOM event](https://developer.mozilla.org/en-US/docs/Web/Events).
* `data-ga-event-category`:  Optional. Event category.
* `data-ga-event-label`: Optional. Event label.
* `data-ga-event-value`: Optional. Event value.

Example usage:

	<button data-ga-event-action="xyz">Click me!</button>

	<button data-ga-event-action="play_video" data-ga-event-category="engagement">Click me!</button>

## Additional Config Info

To configure [custom dimensions and metrics](https://developers.google.com/analytics/devguides/collection/gtagjs/custom-dims-mets) you can use the `required_ga.additional_config_info` filter. By default the [`forceSSL`](https://developers.google.com/analytics/devguides/collection/analyticsjs/field-reference#forceSSL) and [`anonymizeIp`](https://developers.google.com/analytics/devguides/collection/analyticsjs/field-reference#anonymizeIp) fields are set to true.

Example usage:

	add_filter( 'required_ga.enable_event_tracking', function( $config ) {
		$config['custom_map'] = [
			'dimension1' => 'author',
		];

		if ( is_singular( 'post' ) ) {
			$config['author'] = get_the_author_meta( 'display_name', get_post()->post_author )
		}

		return $config;
	} );

## Support for analytics.js (Universal Analytics)

Starting with version 2.0 the plugin enqueues [gtag.js](https://developers.google.com/analytics/devguides/collection/gtagjs/). If you need support for analytics.js you can continue using the 1.x branch.

	$ composer require wearerequired/required-google-analytics:"^1.0"
