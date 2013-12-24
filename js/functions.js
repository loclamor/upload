var l = document.location;
var HOST_OF_SITE = l.protocol + "//" + l.host + document.location.pathname;
console.log(HOST_OF_SITE);

function notify(message, timeout){
	
	$('<div class="notif">'+message+'</div>').appendTo('#notification-box')
	  .delay(timeout).animate({ height: 'toggle', opacity: 'toggle' }, 'slow').queue(function() { $(this).remove(); });
	//.fadeOut(400).delay(100).slideUp(300)
}

$(document).ready(function(){
	//en fait rien.
});

function generateBBCode(minType, minSize) {
	$('#bbcode-area').html('');
	$('.selected').each(function(){
		var privatecode = $(this).children('img').attr('private');
		
		var bbcodeUrl = '[url='+HOST_OF_SITE+'photo/'+privatecode+'.jpg]';
		
		var bbcodeImg = '   [img]'+HOST_OF_SITE+'photo/'+privatecode+'.min'+minType+minSize+'.jpg[/img]';
		$('#bbcode-area').append(bbcodeUrl+'\n'+bbcodeImg+'\n[/url]\n');
	});
}

function generateBBCodeWithOutUrl(minType, minSize){
	$('#bbcode-area').html('');
	$('.selected').each(function(){
		var privatecode = $(this).children('img').attr('private');
		var bbcodeImg = '   [img]'+HOST_OF_SITE+'photo/'+privatecode+'.min'+minType+minSize+'.jpg[/img]';
		$('#bbcode-area').append(bbcodeImg+'\n');
	});
}