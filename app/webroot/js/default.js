$(function() {
	$('#filterUserId').on('change', function(event) {
		var $that = $(this);
		var $form = $that.closest('form');
		window.location.href = baseUrl + $form.data('url') + $that.prop('value');
	});
});
