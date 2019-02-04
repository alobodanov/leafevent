<?php
	require_once "../Library.php"; 
	
	if (isset($_POST['userId'])){
		$picLocation = null;
		$user_id = $_POST['userId'];
		$dbLink = new DBLink();
		$q = "SELECT vfp.* FROM (SELECT @f_param:=".$_POST['userId']." p1) parm , v_friends_posts vfp ORDER BY vfp.date_added_post_SQL DESC";	
		$result = $dbLink->query($q);
		while ($r = mysqli_fetch_array($result)) {
			$post_id =$r['id_post_SQL'];
			$folderName = $r['folder_name_SQL'];
			$picName = $r['user_pic_name_SQL'];
			$userGender = $r['gender_SQL'];
			if($folderName != null && $picName != null){
				$picLocation = "<img class='postedUserPic' src='../milberUserPhotos/profilePictures/$folderName/$picName' alt='img'/>";
			} else {
				if($userGender == 'Male')
					$picLocation = "<img id='userPhoto' src='../milberUserPhotos/blue.jpg' alt='<?= $username?>'/>";
				else
					$picLocation = "<img id='userPhoto' src='../milberUserPhotos/pink.jpg' alt='<?= $username?>'/>";
			}	
			//$q2 = "SELECT vpc.* FROM (SELECT @f_param:=".$post_id." p1) parm , v_post_comments vpc ORDER BY comment_id_SQL DESC";
			//$resultC = $dbLink->query($q2);

			
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
										<button type='button' class='btn btn-default'>Go Event</button> 
									</div>
								</div>
							</div>
						</div>
						
						<div id='Post-".$post_id."' data-id='".$post_id."' style='display:none;'>
							<div class='row'>
								<div class='col-md-6'>
									<form id='form-newComment-".$post_id."' method='POST' >
										<div class='input-group'>
											<textarea id='commentBody-".$post_id."' rows='2' type='text' class='form-control' name='commentBody' placeholder='comment...' value='";
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
	}// END IF	
?>