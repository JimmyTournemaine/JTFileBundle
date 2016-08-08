$(function(){
	$link = $('#login_link > a')
	$loaded = false;
	$.ajax(Routing.generate('login_popover'),{
		async: false,
		success: function(html) {
			$link.popover({
				container: 'body',
				content: html,
				delay: { "show": 500, "hide": 100 },
				html: true,
				placement: 'bottom',
				trigger: 'manual'
			});
			$loaded = true;
		}
	});
	
	if($loaded){
		$link.click(function(e){
			e.preventDefault();
			$link.popover('show');
		});
	}
})