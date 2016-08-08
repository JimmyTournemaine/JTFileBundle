$(function(){
	/**
	 * Search a team by name
	 */
	$form = $('form[name="team_search_form"]');
	$search = $form.find('input[type="search"]');
	$submit = $form.find('input[type="submit"]');
	
	$submit.click(function(e){
		e.preventDefault();
		$.ajax({
			url: Routing.generate('team_join'),
			type: 'POST',
			dataType: 'html',
			data: {
				'search': $search.val()
			},
			success: function(code_html, status){
				$('#team_search_results_table').remove();
				$form.after(code_html);
			},
			error: function(){
				$form.submit();
			}
		});
	});
});