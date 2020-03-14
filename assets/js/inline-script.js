"use strict";(function(){function a(){window.dataLayer.push(arguments)}var b=window.localStorage;// Disable Analytics for opted-out users.
// Set up the global site tag.
// Make gtag object and opt-out function available.
window["ga-disable-__PROPERTY_ID__"]=function hasOptedOut(){try{return"1"===b.getItem("ga-opted-out")}catch(a){return!1}}(),window.dataLayer=window.dataLayer||[],a("js",new Date),a("config","__PROPERTY_ID__",{anonymize_ip:!0,forceSSL:!0}),window.requiredGADoOptOut=function doOptOut(){try{b.setItem("ga-opted-out","1")}catch(a){}},window.gtag=a})();
