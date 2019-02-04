/*$('#formInput').submit(function(){
	var message = $('#msgInput').val();
	var sender = $().val();

	$.ajax({
		//url1: '../phpfiles/messageSentToSystem.php',
		data: { message: message },
		success: function(data){
			$('#userFeed').html(data);
		}

	});

	return false;

});*/
$("#link").focus();


$(document).read(function(){
	$('msgSend').click(function(){
		comemnt_post_btn_click();
	});
});

function comment_post_btn_click(){
	var _comment = $('#msgInput');
	var _userId = $('')
}

function comment_insert(){
	
}