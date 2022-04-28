( ( window, config ) => {
	const propertyId = config.propertyId || null;
	const measurementId = config.measurementId || null;
	const additionalConfigInfo = config.additionalConfigInfo || {};
	const storage = window.localStorage;

	const hasOptedOut = () =>  {
		try {
			return '1' === storage.getItem( 'ga-opted-out' );
		} catch ( error ) {
			return false;
		}
	}
	const doOptOut = () => {
		try {
			storage.setItem( 'ga-opted-out', '1' );
		} catch ( e ) {}
	}

	// Disable Analytics for opted-out users.
	if ( propertyId ) {
		window[`ga-disable-${ propertyId }`] = hasOptedOut();
	}
	if ( measurementId ) {
		window[`ga-disable-${ measurementId }`] = hasOptedOut();
	}

	// Set up the global site tag.
	window.dataLayer = window.dataLayer || [];
	function gtag() {
		window.dataLayer.push( arguments );
	}

	gtag( 'js', new Date() );
	if ( propertyId ) {
		gtag( 'config', propertyId, additionalConfigInfo );
	}
	if ( measurementId ) {
		gtag( 'config', measurementId, additionalConfigInfo );
	}

	// Make gtag object and opt-out function available.
	window.requiredGADoOptOut = doOptOut;
	window.gtag = gtag;
})( window, window.requiredGA );
