function notify(message, timeout){
	
	$('<div class="notif">'+message+'</div>').appendTo('#notification-box')
	  .delay(timeout).animate({ height: 'toggle', opacity: 'toggle' }, 'slow').queue(function() { $(this).remove(); });
	//.fadeOut(400).delay(100).slideUp(300)
}

$(document).ready(function(){
	//en fait rien.
});
