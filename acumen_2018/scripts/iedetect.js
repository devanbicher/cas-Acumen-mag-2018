$=jQuery;
$(document).ready(function(){
    detectIE();
});

function detectIE() {
    var ua = window.navigator.userAgent;
    var trident = ua.indexOf('Trident/');
    var edge = ua.indexOf('Edge/');
    var msie = ua.indexOf('MSIE ');
    if ((msie > 0) || (trident > 0) || (edge > 0)) {
        // IE 10 or older => return version number
	var sheet = window.document.styleSheets[0];
	sheet.insertRule('* { background-image: none !important; }', sheet.cssRules.length);
	sheet.insertRule('#features-prev-next { width: 97% !important; }', sheet.cssRules.length);
	sheet.insertRule('.briefs-sidebar .title-dept { display: block !important; }', sheet.cssRules.length);
	return
    }
    return;
}
