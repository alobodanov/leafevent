<?php
	require_once "../Library.php"; 
	$rest_json = file_get_contents("php://input");
	$_POST = json_decode($rest_json, true);
	$return["status"]= "error";
	$return["countpostlikes"]= 0;
	if (isajax() && isset($_POST['postId']) && isset($_POST['action'])){
		if($_POST['postId'] == "" ||$_POST['action']==""){
			$return["error_msg"] = "<p class='post-error-home'>Invalid action cannot be done.</p>";
		}
		$swichLinkStat = new DBLink();
		if($_POST['action']=="like" || $_POST['action']=="dislike"){
			$return["action"]= 'like';	
			$return["postId"]= $_POST['postId'];
			if(isset($_POST['userId']) && $_POST['userId']!=""){
				$return["userId"]= $_POST['userId'];
				//test if user already click 'like'
				$sql = "SELECT count(user_id_SQL) as userliked FROM (SELECT @f_param:=".$_POST['postId']." p1) parm , v_postlikes vpl WHERE user_id_SQL='".$_POST['userId']."'";
				$resultState = $swichLinkStat->query($sql);				
				$count=mysqli_fetch_assoc($resultState);
				if($count['userliked'] == 0 && $_POST['action']=="like"){
					$sql = "INSERT INTO MILBER.postlikes(post_id_SQL, user_id_SQL) VALUES ('".$_POST['postId']."','".$_POST['userId']."')";
				}
				else if($count['userliked'] != 0 && $_POST['action']=="dislike"){
					$sql = "DELETE FROM MILBER.postlikes WHERE post_id_SQL='".$_POST['postId']."' AND user_id_SQL='".$_POST['userId']."'";
				}
				$resultState = $swichLinkStat->query($sql);
				if ($resultState==true){
					$return["status"]= "success";
					$return["postlike_Id"]= $swichLinkStat->lastInsertId();
					$sql = "SELECT count(user_id_SQL) as totallikes FROM (SELECT @f_param:=".$_POST['postId']." p1) parm , v_postlikes vpl";
					$resultState = $swichLinkStat->query($sql);
					if ($resultState==true){
						$total=mysqli_fetch_assoc($resultState);
						$return["status"]= "success";
						$return["countpostlikes"]= $total['totallikes'];
						$postTotal = $total['totallikes'];
						$sql = "UPDATE MILBER.posts SET totallikes_SQL = '$postTotal' WHERE id_post_SQL='".$_POST['postId']."'";
						$resultState = $swichLinkStat->query($sql);
					}
					else{
						$return["error_msg"] = "<p class='post-error-home'>cannot get likes</p>";						
					}
				}
				else{
					$return["error_msg"] = "<p class='post-error-home'>Data cannot be saved</p>";
				}
			}
		}
		echo json_encode($return);
	}
	else{
		$return["error_msg"] = "failure";
		die(json_encode($return));
	}