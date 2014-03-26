$(function() {
	$('#filterName').on('change', function(event) {
		var $that = $(this);
		window.location.href = baseUrl + 'cases/index/name:' + $that.prop('value');
	});
});
