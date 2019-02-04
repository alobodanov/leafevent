<?php
	require_once "../Library.php"; 
	
	$rest_json = file_get_contents("php://input");
	$_POST = json_decode($rest_json, true);
	$check = true;
	$return["status"]= "error";
	if (isajax() && isset($_POST['post_id']) && isset($_POST['user_id'])){	
		$return["postId"] = $_POST['post_id'];
		$commentBody_error = null;
		if(htmlentities(trim($_POST['commentBody'])) == ""){
			$commentBody_error = "<p class='comment-error-home'>Please fill in your comment.</p>";
			$check = false;

			if(!preg_match("/^[a-z0-9\,\<\>\.\%\;\(\)\'\ \!\?\:\"]*$/i", htmlentities(trim($_POST['commentBody'])))){    
				$commentBody_error = "<p class='comment-error-home'>Please enter only latters and numbers.</p>";
				$check = false;
			}
			$return["error_msg"] =  $commentBody_error;
		}
		if($_POST && $check){
			$swichLinkStat = new DBLink();
			$input = mysqli_real_escape_string($swichLinkStat->conn(),htmlentities(trim($_POST['commentBody'])));
			$sql = "INSERT INTO MILBER.Comments (comment_body_SQL,post_id_SQL,milberUserInfo_id_SQL) VALUES('".$input."',".trim($_POST['post_id']).",".$_POST['user_id'].")";
			$resultStat = $swichLinkStat->query($sql);
			if ($resultStat==true){
				$return["status"]= "success";
				$return["sql"]= $sql;
			}
			else{
				$return["error_msg"] = "<p class='comment-error-home'>Data cannot be saved</p>";
			}
		}
		echo json_encode($return);
	}
	else{
		$return["error_msg"] =  "failure";
		die(json_encode($return));
	}