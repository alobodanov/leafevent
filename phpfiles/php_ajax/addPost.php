<?php
	require_once "../Library.php"; 
	$rest_json = file_get_contents("php://input");
	$_POST = json_decode($rest_json, true);
	$return["status"]= "error";
	if (isajax() && isset($_POST['eventPostsTextPostMesgPost']) && isset($_POST['user_id'])){
		if($_POST['eventPostsTextPostMesgPost'] != ""){
			$swichLinkStat = new DBLink();
			$input = mysqli_real_escape_string($swichLinkStat->conn(), htmlentities(trim($_POST['eventPostsTextPostMesgPost'])));
			
			$sql = "INSERT INTO MILBER.posts( body_post_SQL, added_by_post_SQL, added_by_post_Name_SQL, user_post_to_SQL) VALUES ('".$input."',".$_POST['user_id'].",'".$_POST['user_name']."','".$_POST['user_posted_to']."')"; 
			$resultStat = $swichLinkStat->query($sql);
			if ($resultStat==true){
				$return["status"]= "success";
				$return["postId"]= $swichLinkStat->lastInsertId();
			}
			else{
				$return["error_msg"] = "<p class='post-error-home'>Data cannot be saved</p>";
			}
		} 
		else{
			$return["error_msg"] = "<p class='post-error-home'>Please fill in your content.</p>";
		}
		echo json_encode($return);
	}
	else{
		$return["error_msg"] = "failure";
		die(json_encode($return));
	}