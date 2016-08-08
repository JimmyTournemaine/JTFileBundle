$(function(){
	/* Add a + button to create event with a popover */
	var add = '<span class="fc-header-space"></span>'
			+ '	<span class="fc-button fc-button-add fc-state-default fc-corner-left fc-corner-right unselectable="on">'
			+ '  <span class="fa fa-plus">'
			+ ' </span>';
	$('.fc-header-left').append(add);
	

	var add_button = $('.fc-button-add');
	var previousModal;
	var modal;
	var isPopoverVisible = false;
	function closePopover(){ add_button.popover('hide'); isPopoOverVisible = false };
	function closeModal(){ alert('close'); modal.modal('hide') };
	
	/**
	 * Add events to the event form
	 */
	function formHandler(form, prefix){
		/* Form initializing */
		var date 		= form.find('#'+prefix+'_date');
		var start 		= form.find('#'+prefix+'_start');
		var end 		= form.find('#'+prefix+'_end');
		var allDay		= form.find('#'+prefix+'_allDay');
		var users 		= form.find('#'+prefix+'_users');
		var contact 	= form.find('#'+prefix+'_contact');
		var type 		= form.find('#'+prefix+'_type');
		var start_date  = start.find('#'+prefix+'_start_date');
		var end_date  	= end.find('#'+prefix+'_end_date');

		/* DatePickers initialization */
		options = { dateFormat: "yy-mm-dd" };
		date.datepicker(options);
		start_date.datepicker(options);
		end_date.datepicker(options);

		/* All day activate/desactivate */
		function allDayMode(){ date.parent().show(); start.parent().hide(); end.parent().hide(); }
		function notAllDayMode(){ date.parent().hide(); start.parent().show(); end.parent().show(); }
		notAllDayMode(); // default
		allDay.click(function(){ if($(this).is(':checked')){ allDayMode(); } else { notAllDayMode(); }});

		/* Rendezvous / Meeting form updating */
		function toRendezvous(){ users.parent().hide(); contact.parent().show(); }
		function toMeeting(){ users.parent().show(); contact.parent().hide(); }
		function toEventByName(name) {
			switch(name){
				case "toMeeting": toMeeting(); break;
				case "toRendezvous": toRendezvous();
			}
		}
		toEventByName(type.val()); // initialize
		type.change(function(){ toEventByName(type.val()); }); // onchange

		/* Update end date if empty on start change */
		start_date.change(function(){ if(!end_date.val()){ end_date.val(start_date.val());}});
		
		/* Close popover */
		if (prefix == 'event')
			$('#cancel').click(function(){ closePopover() });
		else
			$('#cancel').click(function(){ closeModal() });
		
		/* Submitting */
		form.find('input[type="submit"]').click(function(e){
			e.preventDefault();
			if(modal){
				modal.modal('hide');
			}
			$(this).attr('disabled', true);
			var data = {};
			// Get input fields
			form.find('input[type!="submit"]').each(function(){
				var fieldName = $(this).attr('id').substr(prefix.length + 1);
				var value = $(this).val();
				data[fieldName] = value;
			})
			// All day treatment
			if(!allDay.is(':checked'))
			{
				delete data['allDay'];
				delete data['date'];
				data['start'] = {};
				data['start']['date'] = data['start_date'];
				data['start']['time'] = {};
				data['start']['time']['hour'] = form.find('#'+prefix+'_start_time_hour :selected').val();
				data['start']['time']['minute'] = form.find('#'+prefix+'_start_time_minute :selected').val();
				data['end'] = {};
				data['end']['date'] = data['end_date'];
				data['end']['time'] = {};
				data['end']['time']['hour'] = form.find('#'+prefix+'_end_time_hour :selected').val();
				data['end']['time']['minute'] = form.find('#'+prefix+'_end_time_minute :selected').val();
			}
			delete data['start_date'];
			delete data['end_date'];
			// Hydrate others fields
			data['type'] = type.find(':selected').val();
			usersSelected = [];
			users.find(':selected').each(function(){ usersSelected.push(parseInt($(this).val())) });
			data['users'] = usersSelected;
			data['contact'] = contact.find(':selected').val();

			var postData = {};
			postData[prefix] = data;
			// Ajax to submit form
			$.ajax({
				url: form.attr('action'),
				method: 'POST',
				data: postData,
				success: function(event) {
					$('#calendar-holder').fullCalendar('refetchEvents');
				},
				error: function(request, status, error) {
					if(request.status != 404){
						alert("Une erreur est survenue");
						return;
					}
					modal = $(request.responseText);
					$("body").append(modal);
					prefix = 'modal_event';
					formHandler(modal.find('form'), 'modal_event');
					modal.modal();
					modal.on('hidden.bs.modal', function () {
					    $(this).data('bs.modal', null).remove();
					});
				},
				complete: function() {
					$(this).attr('disabled', false);
					closePopover();
				}
			});
		})
	}
	
	add_button.popover({
		animation: true,
		content: $('#create_form').html(),
		html: true,
		title: false,
		trigger: 'manual'
	});
	add_button.hover(function(){ $(this).addClass('fc-state-hover'); }, function(){ $(this).removeClass('fc-state-hover'); });
	add_button.mousedown(function(){ $(this).addClass('fc-state-down'); });
	add_button.mouseup(function(){ $(this).removeClass('fc-state-down'); });
	add_button.mouseout(function(){ $(this).removeClass('fc-state-down'); });
	add_button.click(function(e){ 
		$(this).popover('show');
		isPopoverVisible = true;
		e.stopPropagation();
	});
	add_button.on('shown.bs.popover', function(){
		form = $('.popover form[name="event"]');
		formHandler(form, 'event');
	});
	
	$(document).on('click', function(e) {
		if (isPopoverVisible && $('.popover').find(':focus').length == 0 && $('.popover').find(':hover').length == 0 ){
			closePopover();
		}
	})
});