/**
 * Leafevent Library JQuery file
 * Version: 1.0
 * Date Created: July 18, 2015
 * Date Updated: August 3, 2015
 */

function toggleNavPanel(element, speed){ 	
	if ($(element).attr('style') == 'display:none;'){
		var postId = $(element).attr('data-id');
		loadComments(postId); 
	}
	else if ($(element).attr('style') == ''){
	}
	$(element).toggle(speed);
}		

function loadEvents(userId){
    $.ajax({
      url: 'php_ajax/loadEvents.php',
      type: 'post',
      data: {'userId': userId},
      success: function(result, status) {
        if(status == "success") {
          $('#UserInterestsEvents').html(result);
        }
      },
      error: function(xhr, desc, err) {
        console.log(xhr);
        console.log("Details: " + desc + "\nError:" + err);
      }
    }); // end ajax call
}

function loadFriendsPosts(userId){
    //$('#followbtn').fadeOut(300);
    $.ajax({
      url: 'php_ajax/loadFriendsPosts.php',
      type: 'post',
      data: {'userId': userId},
      success: function(result, status) {
        if(status == "success") {
          $('#viewFriendsPosts').html(result);
        }
      },
      error: function(xhr, desc, err) {
        console.log(xhr);
        console.log("Details: " + desc + "\nError:" + err);
      }
    }); // end ajax call
}

function loadPostsForCurentViewUser(userId){
	$.ajax({
      url: 'php_ajax/loadViewingFriendsPost.php',
      type: 'post',
      data: {'userId': userId},
      success: function(result, status) {
        if(status == "success") {
          $('#usersViewPosts').html(result);
        }
      },
      error: function(xhr, desc, err) {
        console.log(xhr);
        console.log("Details: " + desc + "\nError:" + err);
      }
    }); // end ajax call
}

function loadComments(postId){
    $.ajax({
      url: 'php_ajax/loadFriendsPostComments.php',
      type: 'post',
      data: {'postId': postId},
      success: function(result, status) {
        if(status == "success") {
          $('#friendPostCommnets-'+postId).html(result);          
        }
      },
      error: function(xhr, desc, err) {
        console.log(xhr);
        console.log("Details: " + desc + "\nError:" + err);
      }
    }); // end ajax call
} 

function saveNewPost(element) {
	var data ={};
	$(element).serializeArray().map(function(x){data[x.name] = x.value;});  
	
	$.ajax({
	  url: 'php_ajax/addPost.php',
	  type: 'post',
	  contentType: "application/json",
	  datatType: "json",
	  data:  JSON.stringify(data),
	  success: function(result, status) {
		if(status == "success") {
			result = JSON.parse(result);
			if(result['status'] == "success"){
				//loadComments(result['postId']); 
				$('#eventPost').val('');
				$('#newCPost-error-msg').html("Posted successfully");//for testing
			}
			else if(result['status'] == "db-error" || result['status'] == "error"){
				$('#newCPost-error-msg').html(result['error_msg']);
			}
		}
	  },
	  error: function(xhr, desc, err) {
		console.log(xhr);
		console.log("Details: " + desc + "\nError:" + err);	 
	  }
	}); // end ajax call
	return false;
} 
 
function saveNewComment(element) {
	var data ={};
	$(element).serializeArray().map(function(x){data[x.name] = x.value;});  
	
	$.ajax({
	  url: 'php_ajax/addComment.php',
	  type: 'post',
	  contentType: "application/json",
	  datatType: "json",
	  data:  JSON.stringify(data),
	  success: function(result, status) {
		if(status == "success") {
			result = JSON.parse(result);
			if(result['status'] == "success"){
				loadComments(result['postId']); 
				$('#commentBody-' + result['postId']).val('');
				$('#newComment-error-msg-'+ result['postId']).html('');
			}
			else if(result['status'] == "db-error" || result['status'] == "error"){
				$('#newComment-error-msg-' + result['postId']).html(result['error_msg']);
			}
		}
	  },
	  error: function(xhr, desc, err) {
		console.log(xhr);
		console.log("Details: " + desc + "\nError:" + err);	 
	  }
	}); // end ajax call
	return false;
}



function GetDDL(selector, url, type, data) {
	var myDropDownList = $(selector);
	if (url == null){
		url = 'php_ajax/loadEventsSettings.php';
	}
	var JSONdata ="{\"type\": \""+type+"\",\"data\":"+data+"}";
	$.ajax({
		type: "POST",
		url: url,
		data: JSONdata,
		contentType: "application/json; charset=utf-8",
		success: function (result) {
		   $(selector).html(result);
		},
		failure: function (response) {
			alert(response.d);
		}
	});
}
	
function changeDLLValue () {
	//$("#id_event_topic").change(function () {
	var data = $("#id_event_topic").val();
	if(data !="-1"){
		GetDDL("#event-subtopic-ddl",null,'3',data);
	}
}
function GetDropDownData1(selector, url) {
        var myDropDownList = $('.myDropDownLisTId');
        $.ajax({
            type: "POST",
            url: "../loadEventsSettings.php",
            data: '{name: "abc" }',
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (data) {
                $.each(jQuery.parseJSON(data.d), function () {
                    myDropDownList.append($("<option></option>").val(this['FieldDescription']).html(this['FieldCode']));
                });
            },
            failure: function (response) {
                alert(response.d);
            }
        });
}
function OnSuccess(response) {
	console.log(response.d);
	alert(response.d);
}

$(document).ready(function(){
	
	//Check to see if the window is top if not then display button
	$(window).scroll(function(){
		if ($(this).scrollTop() > 600) {
			$('#goUp').fadeIn();
		} else {
			$('#goUp').fadeOut();
		}
	});
	
	//Click event to scroll to top
	$('#goUp').click(function(){
		$('html, body').animate({scrollTop : 0},800);
		return false;
	});
	
});

$(document).ready(function(){
	
	//Check to see if the window is top if not then display button
	$(window).scroll(function(){
		if ($(this).scrollTop() > 600) {
			$('#goUpfP').fadeIn();
		} else {
			$('#goUpfP').fadeOut();
		}
	});
	
	//Click event to scroll to top
	$('#goUpfP').click(function(){
		$('html, body').animate({scrollTop : 0},800);
		return false;
	});
	
});

$(document).ready(function(){
	
	//Check to see if the window is top if not then display button
	$(window).scroll(function(){
		if ($(this).scrollTop() > 300) {
			$('#goUpfview').fadeIn();
		} else {
			$('#goUpfview').fadeOut();
		}
	});
	
	//Click event to scroll to top
	$('#goUpfview').click(function(){
		$('html, body').animate({scrollTop : 0},800);
		return false;
	});
	
});

$(function() {
  
	// Dropdown toggle
	$('.options').click(function(){
	  $(this).next('.navTools').toggle();

	});

	$(document).click(function(e) {
	  var target = e.target;
	  if (!$(target).is('.options') && !$(target).parents().is('.options')) {
	    $('.navTools').hide();
	  }
	});

	// Dropdown toggle
	$('.worningLogo').click(function(){
	  $(this).next('.Worning').toggle();

	});

	$(document).click(function(e) {
	  var target = e.target;
	  if (!$(target).is('.worningLogo') && !$(target).parents().is('.worningLogo')) {
	    $('.Worning').hide();
	  }
	});

});

function textAreaAdjust(o) {
    o.style.height = "0px";
    o.style.height = (1+o.scrollHeight)+"px";
}

function textAreaEventDesk(o){
	o.style.height = "88px";
    o.style.height = (1+o.scrollHeight)+"px";
}

function textAreaAdjustUser(o){
	o.style.height = "34px";
    o.style.height = (1+o.scrollHeight)+"px";
}

function postLikes(action,postId,userId){
	var data ={"action":action,"postId": postId,"userId": userId};
    $.ajax({
      url: 'php_ajax/likePost.php',
      type: 'post',
	  contentType: "application/json",
	  datatType: "json",
      data: JSON.stringify(data),
      success: function(result, status) {
        if(status == "success") {
			result = JSON.parse(result);
			if(result['countpostlikes']!='0'){
				$('#countpostlikes_'+postId).html('&nbsp;'+result['countpostlikes']+'&nbsp;');
			}
			else if(result['countpostlikes']=='0'){
				$('#countpostlikes_'+postId).html('');
			}
        }
      },
      error: function(xhr, desc, err) {
        console.log(xhr);
        console.log("Details: " + desc + "\nError:" + err);
      }
    }); // end ajax call
	return false;
}


























