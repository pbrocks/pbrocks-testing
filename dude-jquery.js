$(function(){
	$(settings.slideEle).slider({
		min : settings.min,
		max: settings.max,
		slide : function(event, ui) {
			updateRangeValue(ui);
		},
		start : function(event, ui){
			$('#slideArrow').fadeOut();
		},
		animate : 'fast'
	});
	_init();
});