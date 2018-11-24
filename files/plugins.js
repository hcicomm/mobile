// Avoid `console` errors in browsers that lack a console.
(function() {
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());

// Place any jQuery/helper plugins in here.

// 화면 사이즈 변경시
var supportsOrientationChange = "onorientationchange" in window,
    orientationEvent = supportsOrientationChange ? "orientationchange" : "resize";

var ua = navigator.userAgent.toLowerCase(),
    match = /(chrome)[ \/]([\w.]+)/.exec(ua) ||
    /(webkit)[ \/]([\w.]+)/.exec(ua) ||
    /(opera)(?:.*version|)[ \/]([\w.]+)/.exec(ua) ||
    /(msie) ([\w.]+)/.exec(ua) ||
    ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(ua) || [],
    browser = match[1] || "",
    version =  match[2] || "0";

jQuery.browser = {};

if (browser) {
    jQuery.browser[browser] = true;
    jQuery.browser.version = version;
}

// Chrome is Webkit, but Webkit is also Safari.
if (jQuery.browser.chrome) {
    jQuery.browser.webkit = true;
} else if (jQuery.browser.webkit) {
    jQuery.browser.safari = true;
}

//function.bind() polyfill
//taken from: https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/Function/bind#Compatibility
if (!Function.prototype.bind) {
Function.prototype.bind = function (oThis) {
 if (typeof this !== "function") {
   throw new TypeError(
     "Function.prototype.bind - what is trying to be bound is not callable"
   );
 }

 var aArgs = Array.prototype.slice.call(arguments, 1), 
   fToBind = this, 
   fNOP = function() { },
   fBound = function() {
     return fToBind.apply(
       this instanceof fNOP ? this : oThis || window,
       aArgs.concat( Array.prototype.slice.call(arguments) )
     );
   };

 fNOP.prototype = this.prototype;
 fBound.prototype = new fNOP();

 return fBound;
};
}