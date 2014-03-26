$(function() {
	$('#filterUserId').on('change', function(event) {
		var $that = $(this);
		window.location.href = baseUrl + 'cases/index/' + $that.prop('value');
	});
});
