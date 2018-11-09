$=jQuery;
$(document).ready(function(){
    var div0 = $('.region-sidebar-second .view-content')[0];
    var div1= $('.region-sidebar-second .view-content')[1];
    if (div0 && div1) {
	var div0height = div0.scrollHeight;
	var div1height = div1.scrollHeight;
	$(div0).css("max-height", div0height + "px");
	$(div1).css("max-height", "0");
	$("#toggle0-button").click(function() {
	    toggle();
	});
	$("#toggle1-button").click(function() {
	    toggle();
	});
	function toggle() {
	    if ($(div0).css("max-height") === "0px") {
		$(div1).css("max-height", "0");
		setTimeout(function() {
		    $(div0).css("max-height", div0height + "px");
		    $("#toggle0-button").html("- Collapse");
		    $("#toggle1-button").html("+ Expand");
		}, 600);
	    } else {
		$(div0).css("max-height", "0");
		setTimeout(function() {
		    $(div1).css("max-height", div1height + "px");
		    $("#toggle1-button").html("- Collapse");
		    $("#toggle0-button").html("+ Expand");
		}, 600);
	    }
	}
    }
});

