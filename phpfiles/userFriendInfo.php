<?php require "milbertoolbar.php"; ?>
<?php require "UsercontentLeftside.php"; ?>
<?php require_once "Library.php" ?>
<?php
	if($_SESSION['userE_login'] == "" ){
   		header('Location: logout.php');
   		die();
   	}
   	$lookingAt = null;
   	if(isset($_GET['u'])){
   		$lookingAt = htmlentities(trim($_GET['u']));
   	}
   	$imagePath = null;
   	$type = array();
	$name = array();
	$markers = array();
	$address = array();
	$description = array();
	$marks = array();
	$x = array();
	$y = array();
	$html = "";
	$todayYear = date("Y");
	$todayMonth = date("m");
	$todayDay = date("d");
	$cound_user_events = null;
	$allFriendsNum = null;
	$friend = null;

   	if(isset($_GET['u'])){
   		$friend_id = null;
   		$friend_email = null;
   		$friend_name = null;

   		$connect = new DBLink();
		$friend = mysqli_real_escape_string($connect->conn(), htmlentities(trim($_GET['u'])));
		$q = "SELECT * FROM MILBER.v_user_info WHERE id_SQL = $friend";
		$result = $connect->query($q);
		$countResults = mysqli_num_rows($result);
	
		while($r = mysqli_fetch_assoc($result)){
			$friend_folder = $r['folder_name_SQL'];
			$friend_pic = $r['user_pic_name_SQL'];
			$friend_gender = $r['gender_SQL'];
			$friend_id = $r['id_SQL'];
			$friend_email = $r['email_SQL'];
			$friend_name = $r['fname_SQL'] . " " . $r['lname_SQL'];
			if($friend_folder == null || $friend_pic == null){
				$imagePath = "<img class='friendProfilePic' src='../milberUserPhotos/no_prof_pic.png' alt='$friend_name;' />";
			} else {
				$imagePath = "<img class='imagePath' src='../milberUserPhotos/profilePictures/$friend_folder/profile_$friend_pic' alt='<?= $friend_name?>' />";
			}
		}
		//this coonect will help us to take all the info that is relevent to a curent friends
		//
		$coonect = new DBLink();
		$k = "SELECT * FROM MILBER.milberfriends
				WHERE Friend_status_SQL = 'Yes'
				AND (id_Resiver_user_SQL = '$friend'
				OR id_Sender_request_SQL = '$friend')";
		$uppdate = $coonect->query($k);
		$allFriendsNum = mysqli_num_rows($uppdate);

		$JsonConnection = new DBLink();
		$JsonQuesry =  "SELECT * from v_event v WHERE v.event_Posted_by_id_SQL = '$friend_id' AND admin1_confirm_SQL = 'Y' AND admin2_confirm_SQL = 'Y' ORDER BY event_start_datetime_SQL DESC";
		$result=$JsonConnection->query($JsonQuesry);
		$cound_user_events = mysqli_num_rows($result);

		$friendConnection = new DBLink();
		$postQuesry =  $q = "SELECT * FROM (SELECT @f_param:=".$friend." p1) parm , v_posts vp ORDER BY vp.date_added_post_SQL DESC";	
		$friendPosts=$friendConnection->query($postQuesry);
   	}
?>
	<div class="friendProfile">
		<div class="friendInfo">
			<div class="user-image-profile-main"><?= $imagePath ?></div>
			<div class="profile-tools-two"><a href="userFriendInfo.php?u=<?=$friend_id;?>" class="viewFriendName"><?= $friend_name; ?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="userFriendInfo.php?u=<?=$friend?>&v=planning" class="planner-attend-friend"><span><?= $cound_user_events;?></span>Planning</a><a href="userFriendInfo.php?u=<?=$friend?>&v=attending" class="planner-attend-friend">Attending</a><a href="userFriendInfo.php?u=<?=$friend?>&v=friends" class="planner-attend-friend"><span><?= $allFriendsNum;?></span>Friends</a></div>
<?php
		if($friend_folder == null || $friend_pic == null) {
?>
			<img src="../milberUserPhotos/no_prof_pic.png" alt="img" />
<?php   }else{
?>
			<img src="../milberUserPhotos/profilePictures/<?=$friend_folder?>/<?=$friend_pic?>" alt="img" />
<?php
		}
?>
		</div>
<?php
		if(isset($_GET['v']) && isset($_GET['u'])){
			if($_GET['v'] == "planning"){
				if($cound_user_events == 0 && $friend_id == $userID){
					echo "<div class='user-has-no-events'>You dont have any planed events, make one <a href='creaneNeevents vent.php'>here</a></div>";
				} else if($cound_user_events == 0 && $friend_id != $userID){
					echo "<div class='user-has-no-events'><br /><br />Looks like they don't have any planned events.</div>";
				}
?>
		<div class="user-profile-event-list-view">
<?php
				while($row = mysqli_fetch_array($result)){
					$eventId = $row['event_id_SQL'];
					$name = $row['event_Name_SQL'];
					$maker = $row['who_is_making_event_SQL'];
					$picture_folder = $row['event_pic_folder_SQL'];
					$picture_real = $row['event_picture_SQL'];
					$event_end = $row[12];
					if($picture_folder == null || $picture_real == null){
						$dispay_img = "<div><img class='profileUserEventPic' src='../Images/no-event-pic.png' alt='img' /></div>";
					} else {
						$dispay_img  = "<div><img class='profileUserEventPic' src='../milberUserPhotos/eventPhotos/$picture_folder/resized_$picture_real' alt='img' /></div>";
					}
?>
					<script>
						$(document).ready(function() {
			                checkSize();
			            });
						function checkSize() { 
		                    var num = document.getElementsByClassName("event_name_size").length;
		                    var i;
		                    for(i = 0; i < num; i++){
		                        if(document.getElementsByClassName("event_name_size")[i].innerHTML.length > 32){
		                            var text = document.getElementsByClassName("event_name_size")[i].innerHTML.substring(0,32);
		                            text = text + "...";
		                            document.getElementsByClassName("event_name_size")[i].innerHTML = text;
		                        }
		                    }
			            };
	            	</script>
					<a href="viewEvent.php?event=<?=$eventId?>" class="friend-prof-eventLink">
						<div class="view-all-events-prof-pag">
                        	<div><?= $dispay_img;?></div>
                        	<p class="event_name_size2"><?= $name?></p>
                        	<p class="event-maker-name-profile"><?=$maker;?></p>
                    	</div>
                    </a>
<?php
				}
   echo "</div>";
			} else if($_GET['v'] == "attending"){
				if($countattending == 0 && $friend_id == $userID){
					echo "<div class='user-has-no-events'>You are not attending any events. You can look for something <a href=\"eventsearch.php\">here</a></div>";
				} else if($countattending == 0 && $friend_id != $userID){
					echo "A user is not attendidng any events at the moment.</div>";
				}
?>
		<div class="user-profile-attending-list-view">
		</div>
<?php			
			} else if($_GET['v'] == "friends"){
				if($allFriendsNum == 0 && $friend_id == $userID){
					echo "<div class='user-has-no-events'>Oh no, looks like you need to find some friends.</div>";
				} else if($allFriendsNum == 0 && $friend_id != $userID){
					echo "<div class='user-has-no-events'><br /><br />Looks like they did not add any friends yet.</div>";
				}

				//this coonect will help us to take all the info that is relevent to a curent friends
				//
				$coonect2 = new DBLink();
				$temp_cehck = mysqli_real_escape_string($coonect2->conn(), htmlentities(trim($_GET['u'])));
				$k = "SELECT * FROM MILBER.milberfriends
						WHERE Friend_status_SQL = 'Yes'
						AND (id_Resiver_user_SQL = '$temp_cehck'
						OR id_Sender_request_SQL = '$temp_cehck')";
				$uppdateFriend = $coonect2->query($k);
				$allFriendsNum = mysqli_num_rows($uppdateFriend);
?>
				<div class="user-profile-friend-list">
<?php
				while($res = mysqli_fetch_assoc($uppdateFriend)){
						$gPic2 = null;
						if($temp_cehck == $res['id_Resiver_user_SQL']){
							//$resiver = $res['id_Resiver_user_SQL'];
							$sender = $res['id_Sender_request_SQL'];

							$accept = $res['Friend_status_SQL'];
							//if($resiver == $curentUserId || $sender == $curentUserId && ($accept == 'Yes')){
								$tempCon = new DBLink();
								$tempInfo = "SELECT * FROM MILBER.MilberUserInfo WHERE id_SQL = '$sender'";
								$ress = $tempCon->query($tempInfo);
								while($g = mysqli_fetch_assoc($ress)){
									$fname = $g['fname_SQL'];
									$lname = $g['lname_SQL'];
									$gender = $g['gender_SQL'];
									$FriendFileSet = $g['folder_name_SQL'];
									$FriendPicSet = $g['user_pic_name_SQL'];
									$id = $g['id_SQL'];
									if($FriendFileSet == null && $FriendPicSet == null){
										$gPic2 = "<img class='NewFriendRequestPic2' src='../milberUserPhotos/no_prof_pic.png' alt='' />";
									} else {
										$gPic2 = "<img class='NewFriendRequestPic2' src='../milberUserPhotos/profilePictures/$FriendFileSet/profile_$FriendPicSet' alt='' />";
									}
?>
								<div class="friend-list-users">
									<div class="userFriends-profile"><a class="friendView2" href="userFriendInfo.php?u=<?= $id; ?>"><?= $gPic2; ?></a></div>
									<p class="Name"><?= $fname; ?><br /><?= $lname; ?></p>
									<a class="friendView" href="userFriendInfo.php?u=<?= $id; ?>">View</a>
								</div>
<?php
								}
							//}
						} else if($temp_cehck == $res['id_Sender_request_SQL']){
							$resiver = $res['id_Resiver_user_SQL'];
							$accept = $res['Friend_status_SQL'];
							//if($resiver == $curentUserId || $sender == $curentUserId && ($accept == 'Yes')){
								$tempCon = new DBLink();
								$tempInfo = "SELECT * FROM MILBER.MilberUserInfo WHERE id_SQL = '$resiver'";
								$ress = $tempCon->query($tempInfo);
								while($g = mysqli_fetch_array($ress)){
									$fname = $g['fname_SQL'];
									$lname = $g['lname_SQL'];
									$gender = $g['gender_SQL'];
									$FriendFileSet = $g['folder_name_SQL'];
									$FriendPicSet = $g['user_pic_name_SQL'];
									$id = $g['id_SQL'];
									if($FriendFileSet == null && $FriendPicSet == null){
										$gPic2 = "<img class='NewFriendRequestPic2' src='../milberUserPhotos/no_prof_pic.png' alt=''>";
									} else {
										$gPic2 = "<img class='NewFriendRequestPic2' src='../milberUserPhotos/profilePictures/$FriendFileSet/profile_$FriendPicSet' alt='' />";
									}
?>
										<div class="friend-list-users">
											<div class="userFriends-profile"><a class="friendView2" href="userFriendInfo.php?u=<?= $id; ?>"><?= $gPic2; ?></a></div>
											<p class="Name"><?= $fname; ?><br /><?= $lname; ?></p>
											<a class="friendView" href="userFriendInfo.php?u=<?= $id; ?>">View</a>
										</div>
<?php
								}
						}
				}
?>
				</div>
<?php
			}
		} else {
?>
		<script type="text/javascript">
			//JS function that helps us to have a sliding efect on a opening and closing "vies"
			$(document).ready( function() {
				loadFriendViewEvents('<?php echo $friend_id; ?>');
				loadPostsForCurentViewUser('<?php echo $friend_id; ?>');
			});
		</script>
		<div id="usersViewPosts" class="usersAllPosts">
<?php
		$friendFolderName = null;
		$friendPicName = null;
			while ($p = mysqli_fetch_assoc($friendPosts)) {
				$post_id = $p['id_post_SQL'];
				$friendFolderName = $p['folder_name_SQL'];
				$friendPicName = $p['user_pic_name_SQL'];
				$userGender = $p['gender_SQL'];
				if($friendFolderName != null && $friendPicName != null){
					$picLocation = "<img class='postedUserPic' src='../milberUserPhotos/profilePictures/$friendFolderName/resized_$friendPicName' alt='img'/>";
				} else {
					$picLocation = "<img class='postedUserPic' src='../milberUserPhotos/no_prof_pic.png' alt='$friend_name;' />";
				}
?>
				<div class="Posts">
					<div class="panel-heading">
						<div class='picDisplay'><?=$picLocation; ?></div>
						<div class='userPostDate'><blockquote class="OneB"><?=$p['added_by_post_Name_SQL'];?><span><?=$p['date_added_post_SQL'];?></span></blockquote></div>
					</div>
					<div id="viewFriendsPosts" class="panel-body">
						<div class='row'>
							<div class='col-lg-6'>
								<blockquote class='contentPOST'><?=$p['body_post_SQL'];?></blockquote>
								<div class='btn-group' role='group' aria-label='commentBtn'>
									<button type='button' class='btn btn-default' onclick="toggleNavPanel('#Post-<?=$post_id;?>', 300)"><img src='../Images/test.png' alt='img'/>&nbsp;&nbsp;Comments</button>
									<button type='button' class='btn btn-default'><span class="glyphicon  glyphicon-thumbs-up" onclick="postLikes(like,<?=$post_id?>,<?=$userID?>);" aria-hidden="true">Like</span><span id='countpostlikes_".$post_id."'>&nbsp;<?php ($r['totallikes_SQL']==0?"":$r['totallikes_SQL'])?></span>&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down" onclick="postLikes(dislike,<?=$post_id?>,<?=$user_id;?>);" aria-hidden="true">dislike</span></button> 
								</div>
							</div>
						</div>
					</div>
					<div id="Post-<?=$post_id;?>" data-id="<?=$post_id;?>" style="display:none;">
						<div class='row'>
							<div class='col-md-6'>
								<form id='form-newComment-<?=$post_id;?>' method='POST' >
									<div class='input-group'>
										<textarea id="commentBody-<?=$post_id?>" rows='3' type='text' class='form-control' name='commentBody' placeholder='Comment...' onkeyup="textAreaAdjustUser(this)"></textarea><br />								
										<input type='hidden' name='post_id' value='<?php if(isset($post_id)) { echo $post_id; }?>' />
										<input type='hidden' name='user_id' value='<?php if(isset($curentUserId)) { echo $curentUserId; }?>' />
										<span class='input-group-btn'>
											<button id='newCommeleafevent.commentnt-<?=$post_id;?>' type='button' onclick="saveNewComment('#form-newComment-<?=$post_id?>')" class='btn btn-success'>Edit</button>
										</span>
									</div><!-- /input-group -->
									<div id='newComment-error-msg-<?=$post_id;?>' class='text-danger'></div>
								</form>								
								<div id='friendPostCommnets-<?=$post_id;?>' class='friendsComments'></div>
							</div>
						</div>
					</div>
				</div>
<?php
			}
?>
	</div>
		<div class="display-events-right">
			<div id="selectedFriendEvents" class="friendsEventsPage">
<?php
				$dispay_img = null;
				if($cound_user_events == 0 && $friend_id == $userID){
					echo "<div class='user-has-no-events'>You dont have any planed events, make one <a href='creaneNewEvent.php'>here</a></div>";
				} else if($cound_user_events == 0 && $friend_id != $userID){
					echo "<div class='user-has-no-events'><br /><br />Looks like they don't have any planned events.</div>";
				}
				while($row = mysqli_fetch_array($result)){
					$eventId = $row['event_id_SQL'];
					$type = $row['event_type_id_SQL'];
					$type_type =  "SELECT * from v_eventtype WHERE id_SQL = '$type' LIMIT 1";
					$typeresult=$JsonConnection->query($type_type);
					while($typee = mysqli_fetch_array($typeresult)){
						$type = $typee['type_name_SQL'];
					}
					$cound_user_events = mysqli_num_rows($result);
					$name = $row['event_Name_SQL'];
					$maker = $row['who_is_making_event_SQL'];
					$address = $row['event_address_SQL'];	
					$description = $row['event_description_SQL'];
					$picture_folder = $row['event_pic_folder_SQL'];
					$picture_real = $row['event_picture_SQL'];
					$x = $row['event_x_num_SQL'];
					$y = $row['event_y_num_SQL'];
					$event_start = $row[11];
					$event_end = $row[12];
					if($picture_folder == null || $picture_real == null){
						$dispay_img = "<div><p class='eventNameUserProf'>$name</p><p class='eventTypeUserProf'>$type</p><img class='profileUserEventPic' src='../Images/no-event-pic.png' alt='img' />";
					} else {
						$dispay_img  = "<div><p class='eventNameUserProf'>$name</p><p class='eventTypeUserProf'>$type</p><img class='profileUserEventPic' src='../milberUserPhotos/eventPhotos/$picture_folder/resized_$picture_real' alt='img' />";
					}
					//$id_of_maker = $row['event_POsted_by_id_SQL'];
					//$markers[] = array('type'=> $type, 'name'=>$name, 'maker'=> $maker, 'address'=> $address, 'description'=>$description,'lat'=> $x, 'lng'=> $y);					
?>
				<script>
				$(document).ready(function() {
		            checkSize();
		        });
				function checkSize() {
	                var num = document.getElementsByClassName("eventNameUserProf").length;
	                var i;
	                for(i = 0; i < num; i++){
	                    if(document.getElementsByClassName("eventNameUserProf")[i].innerHTML.length > 32){
	                        var text = document.getElementsByClassName("eventNameUserProf")[i].innerHTML.substring(0,32);
	                        text = text + "...";
	                        document.getElementsByClassName("eventNameUserProf")[i].innerHTML = text;
	                    }
	                }
		        };
				</script>
				<a href="viewEvent.php?event=<?= $eventId; ?>">
					<div class="friendProfPage">
<?=$dispay_img;?>
					</div>
					</div>
				</a>
<?php
				}
?>
			</div>
			<div class="CopyRightfP">
					<a href="#">Terms</a>
					<script>
						var curentDate = new Date();
						var years = curentDate.getFullYear();
						document.write('<p>Leafevent © ' + years + '</p>');
					</script>
				</div>
			</div>
		</div>
<?php
		}
?>
		<div id="goUpfP"><a href="javascript:scroll(0,0)">Up</a></div>
	</body>
</html>