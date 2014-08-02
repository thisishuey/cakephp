$(function() {

	$('#filterUserId').on('change', function(event) {
		var $that = $(this);
		var $form = $that.closest('form');
		window.location.href = baseUrl + $form.data('url') + $that.prop('value');
	});

	// $('.cases').each(function() {
	// 	var $that = $(this);
	// 	var $header = $that.find('h4');
	// 	var addLink = false;
	// 	var empty = true;
	// 	var $sprints = $that.children('ul').children('li');
	// 	$sprints.each(function() {
	// 		var $sprint = $(this);
	// 		var showSprint = false;
	// 		var $cases = $sprint.children('ul').children('li');
	// 		$cases.each(function() {
	// 			var $case = $(this);
	// 			if ($case.hasClass('resolved_deployed_to_qa')) {
	// 				$case.hide();
	// 				addLink = true;
	// 			} else {
	// 				showSprint = true
	// 				empty = false
	// 			}
	// 		});
	// 		if (!showSprint) {
	// 			$sprint.hide();
	// 		}
	// 	});
	// });

});
