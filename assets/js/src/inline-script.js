( () => {
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
	window['ga-disable-__PROPERTY_ID__'] = hasOptedOut();

	// Set up the global site tag.
	window.dataLayer = window.dataLayer || [];
	function gtag() {
		window.dataLayer.push( arguments );
	}

	gtag( 'js', new Date() );
	gtag( 'config', '__PROPERTY_ID__', __ADDITIONAL_CONFIG_INFO__ );

	// Make gtag object and opt-out function available.
	window.requiredGADoOptOut = doOptOut;
	window.gtag = gtag;
})();
