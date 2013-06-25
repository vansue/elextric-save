jQuery(document).ready(function($) {
	$('a.tool').hover(function() {
		var str = $(this).attr('data-tooltip');
		$('<p class="tooltip"></p>').text(str).appendTo('body').fadeIn(600);
	}, function() {
		$('.tooltip').remove();
	}).mousemove(function(e) {
		var mousex = e.pageX + 12;
		var mousey = e.pageY + 22;
		$('.tooltip').css({top: mousey, left: mousex})
	});
});