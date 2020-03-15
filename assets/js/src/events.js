// @wordpress/dom-ready
const domReady = ( callback ) => {
	if ( document.readyState === 'complete' || document.readyState === 'interactive' ) {
		return void callback();
	}

	document.addEventListener( 'DOMContentLoaded', callback );
}

// Polyfill for NodeList.prototype.forEach
// Source: https://developer.mozilla.org/en-US/docs/Web/API/NodeList/forEach#Polyfill
if ( window.NodeList && !NodeList.prototype.forEach ) {
	NodeList.prototype.forEach = Array.prototype.forEach;
 }

domReady( () => {
	document.querySelectorAll( '[data-ga-event-action]' ).forEach( ( element ) => {
		const data = element.dataset;

		if ( ! data.gaEventAction ) {
			return;
		}

		const trigger = data.gaEventOn || 'click';

		element.addEventListener( trigger, () => {
			const eventData = {};

			data.gaEventCategory && ( eventData.event_category = String( data.gaEventCategory ) );
			data.gaEventLabel && ( eventData.event_label = String( data.gaEventLabel ) );
			data.gaEventValue && ( eventData.value = Number( data.gaEventValue ) );

			gtag( 'event', data.gaEventAction, eventData );
		} );
	} );
} );
