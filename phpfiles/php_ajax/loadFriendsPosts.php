<?php
	require_once "../Library.php"; 
	
	if (isset($_POST['userId'])){
		$picLocation = null;
		$user_id = $_POST['userId'];
		$dbLink = new DBLink();
		$q = "SELECT vfp.* FROM (SELECT @f_param:=".$_POST['userId']." p1) parm , v_friends_posts vfp ORDER BY vfp.date_added_post_SQL DESC";
		$result = $dbLink->query($q);
		$count = mysqli_num_rows($result);
		if($count != 0){
			while ($r = mysqli_fetch_array($result)) {
				$post_id = $r['id_post_SQL'];
				$folderName = $r['folder_name_SQL'];
				$picName = $r['user_pic_name_SQL'];
				$userGender = $r['gender_SQL'];
				$totalPostLikes = $r['totallikes_SQL'];
				if($folderName != null && $picName != null){
					$picLocation = "<img class='postedUserPic' src='../milberUserPhotos/profilePictures/$folderName/resized_$picName' alt='img'/>";
				} else {
					$picLocation = "<img class='postedUserPic' src='../milberUserPhotos/no_prof_pic.png' alt='$friend_name;' />";
				}
				$html = "<div class=\"allPosts\">
							<div class=\"panel-heading\">
								<div class='picDisplay'>".$picLocation."</div>
								"."<div class='userPostDate'><blockquote class=\"OneB\">".$r['added_by_post_Name_SQL']."<span>".$r['date_added_post_SQL']."</span></blockquote></div>
							</div>
							<div id=\"viewFriendsPosts\" class=\"panel-body\">
								<div class='row'>
									<div class='col-lg-6'>
										<blockquote class='contentPOST'>".$r['body_post_SQL']."</blockquote>
										<div class='btn-group' role='group' aria-label='commentBtn'>
											<button type='button' class='btn btn-default' onclick=\"toggleNavPanel('#Post-".$post_id."', 300)\"><img src='../Images/test.png' alt='img'/>&nbsp;&nbsp;Comments</button>
											<button type='button' class='btn btn-default'><span class=\"glyphicon  glyphicon-thumbs-up\" onclick=\"postLikes('like','".$post_id."','".$user_id."');\" aria-hidden=\"true\">Like</span><span id='countpostlikes_".$post_id."'>&nbsp;".($r['totallikes_SQL']==0?"":$r['totallikes_SQL'])."</span>&nbsp;&nbsp;&nbsp;<span class=\"glyphicon glyphicon-thumbs-down\" onclick=\"postLikes('dislike','".$post_id."','".$user_id."');\" aria-hidden=\"true\">dislike</span></button> 
										</div>
									</div>
								</div>
							</div>
							<div id='Post-".$post_id."' data-id='".$post_id."' style='display:none;'>
								<div class='row'>
									<div class='col-md-6'>
										<form id='form-newComment-".$post_id."' method='POST' >
											<div class='input-group'>
												<textarea id='commentBody-".$post_id."' rows='2' type='text' class='form-control' name='commentBody' onkeyup='textAreaAdjust(this)' placeholder='comment...' value='";
												if(isset($_POST['commentBody'])) { $html .= $_POST['commentBody']; } 
												$html .="'></textarea><br />								
												<input type='hidden' name='post_id' value='";
												if(isset($post_id)) { $html .= $post_id; }
												$html .="' />
												<input type='hidden' name='user_id' value='";
												if(isset($user_id)) { $html .= $user_id; }
												$html .="' />
												<span class='input-group-btn'>
													<button id='newComment-".$post_id."' type='button' onclick=\"saveNewComment('#form-newComment-".$post_id."')\" class='btn btn-success'>Edit</button>
													<button id='newComment-".$post_id."' type='button' class='btn btn-success'>More comments</button>
												</span>					
											</div><!-- /input-group -->
											<div id='newComment-error-msg-".$post_id."' class='text-danger'></div>
										</form>
										<div id='friendPostCommnets-".$post_id."' class='friendsComments'></div>
									</div>
								</div>
							</div>
						</div>";
				echo $html;
			}//END WHILE
		} else {
			$html = "<div class=\"allPosts\">
							<p class='no-friend-post-found'>Your friends have not made any new posts yeat.</p><br />
		
					</div>";
			echo $html;
		}
		//}//END WHILE
	}// END IF
?>