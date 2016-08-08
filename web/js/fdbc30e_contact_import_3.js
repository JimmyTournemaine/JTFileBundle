$(function(){
	
	/**
	 * Save selected elements
	 */
	$list = $('#contacts_list');
	$list.selectable();
	$toAdd = $('#contacts_to_import');
	$toAdd.selectable();
	
	/**
	 * Add a contact to the contact-need-to-be-import list
	 */
	$('button#add').click(function(){
		$list.find('.ui-selected').each(function(){
			$(this).removeClass('ui-selected');
			$toAdd.append($(this));
		})
	})
	
	/**
	 * Add all contacts to the contact-need-to-be-import list
	 */
	$('button#addAll').click(function(){
		$list.find('li').each(function(){
			$(this).removeClass('ui-selected');
			$toAdd.append($(this));
		})
	})
	
	/**
	 * Remove a contact from the contact-need-to-be-import list
	 */
	$('button#remove').click(function(){
		$toAdd.find('.ui-selected').each(function(){
			$(this).removeClass('ui-selected');
			$list.append($(this));
		})
	})
	
	/**
	 * Remove all contacts from the contact-need-to-be-import list
	 */
	$('button#removeAll').click(function(){
		$toAdd.find('li').each(function(){
			$(this).removeClass('ui-selected');
			$list.append($(this));
		})
	})
	
	
	/**
	 * Load contacts from google accounts
	 */
	loading = '<li id="loading"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Loading...</li>';
	$nextLink = $('a#next');
	$nextLink.click(function(e){
		e.preventDefault();
		$list.empty();
		$list.append(loading);
		$nextLink.addClass('disabled');
		$.ajax({
			url: e.target.href,
			method: 'GET',
			success: function(resp){
				var response = $.parseJSON(resp);
				$list.prepend(response.html);	// Add html 	
				if(!response.hasNext)			// Remove next button if finished
					$nextLink.remove();
				else
					$nextLink.attr('href', Routing.generate('contact_import', {'page': response.page+1})); // Update next button target
			},
			error: function(request, status, error) {
		        alert(request.responseText);
			},
			complete: function(){
				$list.find('#loading').remove();
				if($nextLink)
					$nextLink.removeClass('disabled');
			}
		})
	})
	
	$('a#import').click(function(e){
		e.preventDefault();
		var contacts = [];
		$toAdd.find('li').each(function(){
			contacts.push($(this).data('contact'));
		});
		$.ajax({
			url: Routing.generate('contact_import_save'),
			method: 'POST',
			data: 'contacts='+JSON.stringify(contacts),
			success: function(url){
				document.location.href = url;
			},
			error: function(request, status, error){
				if (request.status == 404){
					alert(Translator.trans('contact.import.error'));
				}
			}
		})
	})
});