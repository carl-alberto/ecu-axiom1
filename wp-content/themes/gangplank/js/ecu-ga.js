//Google Analytics Code
(function (i, s, o, g, r, a, m) {
    i['GoogleAnalyticsObject'] = r; i[r] = i[r] || function () {
        (i[r].q = i[r].q || []).push(arguments)
    }, i[r].l = 1 * new Date(); a = s.createElement(o),
        m = s.getElementsByTagName(o)[0]; a.async = 1; a.src = g; m.parentNode.insertBefore(a, m)
})(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

function triggerGAPageView() {
    //Find canonical url
    try {
        canonical_link = $('link[rel=canonical]').attr('href') || undefined;
    } catch (e) {
        canonical_link = undefined;
    }

    ga('create', 'UA-15287402-1', 'auto', { name: "ecuAssetJs" });  //create the beacon
    ga('ecuAssetJs.require', 'displayfeatures');       //require displayfeatures for demographic data
    if (canonical_link == undefined) {
        ga('ecuAssetJs.send', 'pageview');             //send beacon without canonical url
    } else {
        ga('ecuAssetJs.send', 'pageview', { 'location': canonical_link });  //send beacon with canonical url
    }
}

$(document).ready(function () {
    triggerGAPageView();
});

//Facebook Pixel Code
!function (f, b, e, v, n, t, s) {
    if (f.fbq) return; n = f.fbq = function () {
        n.callMethod ?
            n.callMethod.apply(n, arguments) : n.queue.push(arguments)
    }; if (!f._fbq) f._fbq = n;
    n.push = n; n.loaded = !0; n.version = '2.0'; n.queue = []; t = b.createElement(e); t.async = !0;
    t.src = v; s = b.getElementsByTagName(e)[0]; s.parentNode.insertBefore(t, s)
}(window,
    document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');

fbq('init', '138156206628208');
fbq('track', "PageView");

// Start Quantcast Tag
var _qevents = _qevents || [];

(function () {
    var elem = document.createElement('script');
    elem.src = (document.location.protocol == "https:" ? "https://secure" : "http://edge") + ".quantserve.com/quant.js";
    elem.async = true;
    elem.type = "text/javascript";
    var scpt = document.getElementsByTagName('script')[0];
    scpt.parentNode.insertBefore(elem, scpt);
})();

_qevents.push({ qacct: "p-DG7ZghkzhLCqw" });
// End Quantcast tag
