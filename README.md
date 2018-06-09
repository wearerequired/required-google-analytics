# Google Analytics

A lightweight WordPress plugin to add Google's analytics.js to your site, the modern way.

The modern way? Google suggests the [alternative async tracking snippet](https://developers.google.com/analytics/devguides/collection/analyticsjs/#alternative_async_tracking_snippet) to add support for preloading, which will provide a small performance boost on modern browsers.  
Modern browsers are those that support the `async` script attribute. This does not include IE 9 and older mobile browsers.

Beside that, the plugin doesn't do more. No fancy report view in your admin, no authentication requirements, no additional scripts, and no ads for random blog posts.

## Installation

Install via Composer

	$ composer require wearerequired/google-analytics

Add you property ID at Settings > Reading > Google Analytics.

## Changelog

### 1.0.0

* Initial release.
