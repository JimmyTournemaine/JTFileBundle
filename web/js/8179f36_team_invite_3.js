$(function(){
	/**
	 * Select a user by username
	 */
	$table = $('#invitations_table');
	
	$form = $('form[name="team_invite_form"]');
	$field = $form.find('input[type="text"]');
	$submit = $form.find('input[type="submit"]');
	var defaultSubmit = $submit.val();
	
	$submit.click(function(e){
		e.preventDefault();
		$submit.val(Translator.trans('invitation.by_team.form.sending'));
		$submit.addClass('disabled');
		$.ajax({
			url: Routing.generate('invitation_by_team'),
			type: 'POST',
			dataType: 'html',
			data: {
				'username': $field.val()
			},
			success: function(code_html, status){
				$table.find('tbody').after(code_html);
			},
			error: function(request, status, error){
				if(request.status == 404){
					if(null != ($error = $form.find('#invitations_form_error')))
						$error.remove();
					$form.prepend('<p id="invitations_form_error">'+request.responseText+'</p>');
				} else {
					$form.submit();
				}
			},
			complete: function(){
				$submit.removeClass('disabled');
				$submit.val(defaultSubmit);
			}
		});
	});
});