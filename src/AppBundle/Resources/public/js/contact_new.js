/**
 * This script has a dependency on company_new.js
 */
$(function(){
	/**
	 * Company field in contact form autocompletion with ajax request
	 */
	$companyField = $('input#contact_company');
	$companyField.autocomplete({
		minLength: 3,
		source: function(request, response){
			$.ajax({
				url: Routing.generate('company_autocompletion', {'name': $companyField.val() }),
				dataType: 'json',
				success: function(donnees){
					response($.map(donnees, function(obj){
						return obj.name;
					}));
				},
			})
		}
	});
	
	var form;
	/**
	 * Call this function on AJAX request to get the company_new form.
	 * Submit the form by Ajax to stay on the page an add the newly created company to create the contact.
	 * We call onCompanyFormLoadingComplete() of contact_new.js to load this new form javascripts events.
	 */
	function onAddNewCompanyFormResponse(htmlForm) 
	{
		// If previous form, we remove it
		if(form)
			form.remove();
		// The new form will be treated with ajax
		form = $(htmlForm);
		$submit = form.find('input[type="submit"]');
		$submit.click(function(e){
			e.preventDefault();
			var data = {};
			// Get fields
			form.find('input[type!="submit"]').each(function(){
				var fieldName = $(this).attr('id').substr(8);
				var value = $(this).val();
				data[fieldName] = value;
			})
			// Ajax to submit form
			$.ajax({
				url: Routing.generate('company_new'),
				method: 'POST',
				data: data,
				success: function(response) {
					alert(Translator.trans('company.new.alert.success', {'name': response}))
					form.fadeOut("slow");
				},
				error: function(request, status, error) {
					onAddNewCompanyFormResponse(request.responseText);
				}
			});
		});
		// Add form on the view
		form.hide();
		form.prepend('<h2>'+Translator.trans('contact.new.add_company')+'</h2>')
		$('form[name="contact"]').after(form);
		form.fadeIn("slow");
		onCompanyFormLoadingComplete();
	}
	
	/**
	 * Create a new company if needed
	 */
	$newCompanyLink = $('#company_new_button');
	$newCompanyLink.click(function(e){
		e.preventDefault();
		$.ajax({
			url: Routing.generate('company_new'),
			method: 'GET',
			dataType: 'html',
			success: function(response){
				onAddNewCompanyFormResponse(response);
			},
			error: function(){
				document.location.href = url;
			}
		})
		$newCompanyLink.remove();
	})
});