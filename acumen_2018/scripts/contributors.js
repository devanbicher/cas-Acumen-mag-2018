$=jQuery;
$(document).ready(function(){
    var div = $(".region-blockgroup-contributors")[0];
    var block = $("#block-block-4");
    if (block && div) {
	var divheight = (div).scrollHeight;
	var caret = $("#con-caret img");
	$(div).css("max-height", "0px");
	$(block).click(function() {
	    divheight = (div).scrollHeight;
            toggle(div, divheight, caret);
        });	
    }
});

function toggle(div, divheight, caret) {
    if ($(div).css("max-height") === "0px") {
        $(div).css("max-height", divheight + "px");
	$(caret).css("transform", "rotateX(180deg)");
    } else {
	$(div).css("max-height", "0px");
	$(caret).css("transform", "rotateX(0deg)");
    }
}
