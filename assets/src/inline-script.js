( ( w ) => {
	const propertyId = w.requiredGAPropertyId || null;
	const measurementId = w.requiredGA4MeasurementId || null;
	const config = w.requiredGAAdditionalConfigInfo || {};
	const storage = w.localStorage;

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
		w[`ga-disable-${ propertyId }`] = hasOptedOut();
	}
	if ( measurementId ) {
		w[`ga-disable-${ measurementId }`] = hasOptedOut();
	}

	// Set up the global site tag.
	w.dataLayer = w.dataLayer || [];
	function gtag() {
		w.dataLayer.push( arguments );
	}

	gtag( 'js', new Date() );
	if ( propertyId ) {
		gtag( 'config', propertyId, config );
	}
	if ( measurementId ) {
		gtag( 'config', measurementId, config );
	}

	// Make gtag object and opt-out function available.
	w.requiredGADoOptOut = doOptOut;
	w.gtag = gtag;
})(window);
