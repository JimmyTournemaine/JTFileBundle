$(function(){
	
	/**
	 * Replace the "Add task" link by the create form
	 */
	$('#new_task a').click(function(e){
		e.preventDefault();
		// If there i no form yet
		if($('#new_task > form').length == 0){
			$.ajax({
				url: Routing.generate('task_new'),
				type: 'GET',
				dataType: 'html',
				// Replace link by generated form
				success: function(code_html, status){
					$add = $('#new_task');
					$button = $('<button>'+Translator.trans('task.form.cancel')+'</button>');
					$form = $(code_html).append($button);
					$button.click(function(e){
						e.preventDefault();
						$form.remove();
					});
					$add.append($form);
				},
				// Redirection for Ajax error
				error: function(){
					document.location.href=Routing.generate('task_new');
				}
			});
		}
	});
	
	/**
	 * Show the task description on hover
	 */
	$('.task').hover(function(e){
		$(e.target).popover('show');
	}, function(e){
		$(e.target).popover('hide');
	});
	
	/**
	 * Double click on a task to edit it
	 */
	$('.task').dblclick(function(e){
		var current = $(e.target);
		var id = current.data('id');
		$.ajax({
			url: Routing.generate('task_edit',{'id':id}),
			type: 'GET',
			dataType: 'html',
			success: function(code_html, code){
				$('#new_task').append(code_html);
				$('#myModal').modal('show');
			},
			error: function(){
				window.location = Routing.generate('task_edit',{'id':id});
			}
		});
	});
	
	/**
	 * To "sort" task in three lists
	 */
	$('.sortable').sortable({
		containment: '#task-list',
		connectWith: '.sortable',
		cursor: 'move',
		revert: true,
		opacity: 0.8,
		receive : function(event, ui){
			var current = ui.item;
			var id = current.data('id');
			var status = null;
			if(this.id == 'done')
				status = true;
			else if(this.id == 'wip')
				status = false;
			// Update the task status
			$.ajax({
				url: Routing.generate('task_update_status'),
				type: 'GET',
				data: 'id='+id+'&status='+status,
				error: function(){
					$('#flashes').append('<div class="flash-danger">'+Translator.trans('task.status_update.try_later')+'</div>');
				}
			});
		}
	});
});