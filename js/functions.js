function notify(message, timeout){
	
	$('<div class="notif">'+message+'</div>').appendTo('#notification-box')
	  .delay(timeout).fadeOut(400).queue(function() { $(this).remove(); });
	
}

$(document).ready(function(){

});
