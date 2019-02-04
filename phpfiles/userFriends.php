<?php $title_name = "User Name";?>
<?php require_once "milbertoolbar.php";?>
<?php require_once "UsercontentLeftside.php";?>
<?php
	ob_start();
	if($_SESSION['userE_login'] == "" ){
   		header('Location: logout.php');
   		die();
   	}
	$LogInUsersId  = null;	
	$usersFriendID = null;
	$NewCurrentNot = null; 
	$friendInfoName = null;
	$FriendID	 = null;
	$FriendFname = null;
	$FriendLname = null;
	$newFriendID	= null;
	$newFriendFname = null;
	$newFriendLname = null;
	$confirmationFriend = "Confirmed";
	$oldFriendID	= null;
	$oldFriendFname = null;
	$oldFriendLname = null;
	$onlineFriendID	   = null;
	$onlineFriendFname = null;
	$onlineFriendLname = null;
	$theNumOfNewFriends = null;
	$count = null;
	$Resiver_user = '';
	$numberOfFriendRequests = '';
	$fieldFriend = "Friend Requests"; 

	$link = new DBLink();
	$q = "SELECT * FROM MILBER.milberfriends WHERE id_Resiver_user_SQL = '$userID' AND Friend_status_SQL = 'New'";
	$result = $link->query($q);
	$count = mysqli_num_rows($result);

	$conForOnlineInfo = new DBLink();
	$onlineSelection = "SELECT * FROM MILBER.MilberUserInfo WHERE online_SQL = 'y'";
	$totalOnlineResult = $conForOnlineInfo->query($onlineSelection);
	$howManyFriendsOnline = mysqli_num_rows($totalOnlineResult);

	if($count == 0){
		$theNumOfNewFriends = null;
		$numberOfFriendRequestsMessage = "You have no new friend requests.";
?>
		<aside id="rightTools">
				<script> 
					function toggleNavPanel(element, speed){ $(element).toggle(speed);} 
				</script>
				<p class="pageName">Friends</p>
					<div class="sections_btn_holder"> 
						<p onclick="toggleNavPanel('#Option-10', 300);"><span class="numMessage"><?= $theNumOfNewFriends; ?></span><?= $fieldFriend; ?></p>
					<div id="Option-10" style="display:none">
						<div class="allNewFriends">
<?= $numberOfFriendRequestsMessage; ?>
<?php
	} else {
		//this will display info about new friend requests.
		//
		$theNumOfNewFriends = "&nbsp;&nbsp;" . $count . "&nbsp;&nbsp;";  //count how many new friends
		if($count > 1){
			$fieldFriend = " New Friends";
		}
		if(isset($_GET['con'])){
			$add = $_GET['con'];
			$conn = new DBLink();
			$updateFriend = "UPDATE MILBER.milberfriends 
								SET Friend_status_SQL = 'yes' 
								WHERE id_Sender_request_SQL = '$add' 
								AND id_Resiver_user_SQL = '$userID'";
			$q = $conn->query($updateFriend);
			header('Location: userFriends.php');
			exit();
		}
		if(isset($_GET['dec'])){
			$delete = $_GET['dec'];
			$conn = new DBLink();
			$removeRow = "DELETE FROM MILBER.milberfriends 
							WHERE id_Sender_request_SQL = '$delete' 
							AND id_Resiver_user_SQL = '$userID'";
			$q = $conn->query($removeRow);
			header('Location: userFriends.php');
			ob_end_flush();
			exit();
		}
?>
				<aside id="rightTools">
				<script> 
					function toggleNavPanel(element, speed){ $(element).toggle(speed);} 
				</script>
					<div class="sections_btn_holder"> 
						<p onclick="toggleNavPanel('#Option-10', 300);"><span class="numMessage"><?= $theNumOfNewFriends; ?></span><?= $fieldFriend; ?></p> 
						<div id="Option-10" style="display:none">
							<div class="allNewFriends">
<?php echo $numberOfFriendRequests;
								while($s = mysqli_fetch_assoc($result)){
									$gPic = null;
									$newFriendID = $s['id_Sender_request_SQL'];
									$Resiver_user = $s['id_Resiver_user_SQL'];
									$conn = new DBLink();
									$selectInfoAboutNewFriends = "SELECT * FROM MILBER.MilberUserInfo WHERE id_SQL = $newFriendID";
									$res = $conn->query($selectInfoAboutNewFriends);
										while($r = mysqli_fetch_assoc($res)){
											$FriendID = $r['id_SQL'];
											$FriendFname = $r['fname_SQL'];
											$FriendLname = $r['lname_SQL'];
											$FriendGender = $r['gender_SQL'];
											$FriendFileSet = $r['folder_name_SQL'];
											$FriendPicSet = $r['user_pic_name_SQL'];
											if($FriendFileSet == null && $FriendPicSet == null){
													$gPic = "<img class='NewFriendRequestPic' src='../milberUserPhotos/no_prof_pic.png' alt='<?= $FriendFname?>'>";
											} else {
													$gPic = "<img class='NewFriendRequestPic' src='../milberUserPhotos/profilePictures/$FriendFileSet/profile_$FriendPicSet' alt='<?= $username?>' />";
											}
?>
										<div class="CssIsForNewFriendOnlineFCurentF">
											<div><a href="userFriendInfo.php?u=<?= $FriendID;?>"><?= $gPic; ?></div></a>
											<p class="Name"><a href="userFriendInfo.php?u=<?=$FriendID;?>"><?= $FriendFname; ?><br /><?= $FriendLname; ?></a></p>
											<a href="userFriends.php?con=<?= $FriendID; ?>" class="friendConfirmButton">Confirm</a>
											<a href="userFriends.php?dec=<?= $FriendID; ?>" class="friendDeclineButton">Decline</a>
										</div>
<?php
										}
								}
	}
?>
							</div>
						</div>
					</div>
					<div class="sections_btn_holder"> 
						<p onclick="toggleNavPanel('#Option-11', 300);">Online Friends</p>
						<div id="Option-11" style="display:none">
<?php
					//echo $noOneIsOnline = "No one is online.";    //this variable will be set to null if they have online friends.
					$curentlyOnline = null;         //will have online info of all DB users
					$curentlyUserOnlineName = null; //will contain the name of the persone who is online
					$curentlyUserOnlineId = null;   //will have the id of teh online person.
					$thereIsOnlineNow = null;       //will keep the count of all online friends.

					//this connection connects to a milberfriends table and takes all the info about people who are friends and resiver or sender 
					//should be equals to a curently online person.
					//
					$forFrindsCon = new DBLink();
					$friendsNow = "SELECT * FROM MILBER.milberfriends WHERE Friend_status_SQL = 'Yes' AND (id_Resiver_user_SQL = '$userID' OR id_Sender_request_SQL = '$userID')";
					$res = $forFrindsCon->query($friendsNow);
					$howManyFriendsOnline = mysqli_num_rows($res);

					if($howManyFriendsOnline != 0){

						while($whoIsOnline = mysqli_fetch_assoc($res)){
							$gPic2 = null;
							if($userID == $whoIsOnline['id_Resiver_user_SQL'] && $whoIsOnline['Friend_status_SQL'] == 'Yes'){
								//$resiver = $res['id_Resiver_user_SQL'];
								$sender = $whoIsOnline['id_Sender_request_SQL'];

								$accept = $whoIsOnline['Friend_status_SQL'];
								//if($resiver == $userID || $sender == $userID && ($accept == 'Yes')){
									$tempCon = new DBLink();
									$tempInfo = "SELECT * FROM MILBER.MilberUserInfo WHERE id_SQL = '$sender'";
									$ress = $tempCon->query($tempInfo);
									while($g = mysqli_fetch_assoc($ress)){
										$fname = $g['fname_SQL'];
										$lname = $g['lname_SQL'];
										$gender = $g['gender_SQL'];
										$onlineStatus = $g['online_SQL'];
										$FriendFileSet = $g['folder_name_SQL'];
										$FriendPicSet = $g['user_pic_name_SQL'];
										$id = $g['id_SQL'];
										if($FriendFileSet == null && $FriendPicSet == null){
											$gPic2 = "<img class='NewFriendRequestPic' src='../milberUserPhotos/no_prof_pic.png' alt=''>";
										} else {
											$gPic2 = "<img class='NewFriendRequestPic' src='../milberUserPhotos/profilePictures/$FriendFileSet/profile_$FriendPicSet' alt='' />";
										}
										if($onlineStatus == 'y'){
?>
									<div class="CssIsForNewFriendOnlineFCurentF">
										<div><a class="friendView" href="userFriendInfo.php?u=<?= $id; ?>"><?= $gPic2; ?></a></div>
										<p class="Name"><a class="friendView" href="userFriendInfo.php?u=<?= $id; ?>"><?= $fname; ?></a></p>
										<p class="lastName"><a class="friendView" href="userFriendInfo.php?u=<?= $id; ?>"><?= $lname; ?></a></p>
										<a class="friendView" href="userFriendInfo.php?u=<?= $id; ?>">View</a>
									</div>
<?php
										}
									}
								//}
							} else if($userID == $whoIsOnline['id_Sender_request_SQL'] && $whoIsOnline['Friend_status_SQL'] == 'Yes'){
								$resiver = $whoIsOnline['id_Resiver_user_SQL'];
								$accept = $whoIsOnline['Friend_status_SQL'];
								//if($resiver == $userID || $sender == $userID && ($accept == 'Yes')){
								$tempCon = new DBLink();
								$tempInfo = "SELECT * FROM MILBER.MilberUserInfo WHERE id_SQL = '$resiver'";
								$ress = $tempCon->query($tempInfo);
								while($g = mysqli_fetch_array($ress)){
									$fname = $g['fname_SQL'];
									$lname = $g['lname_SQL'];
									$gender = $g['gender_SQL'];
									$onlineStatus = $g['online_SQL'];
									$FriendFileSet = $g['folder_name_SQL'];
									$FriendPicSet = $g['user_pic_name_SQL'];
									$id = $g['id_SQL'];
									if($FriendFileSet == null && $FriendPicSet == null){
										$gPic2 = "<img class='NewFriendRequestPic' src='../milberUserPhotos/no_prof_pic.png' alt=''>";
									} else {
										$gPic2 = "<img class='NewFriendRequestPic' src='../milberUserPhotos/profilePictures/$FriendFileSet/profile_$FriendPicSet' alt='' />";
									}
									if($onlineStatus == 'y'){
?>
										<div class="CssIsForNewFriendOnlineFCurentF">
											<div><a class="friendView" href="userFriendInfo.php?u=<?= $id; ?>"><?= $gPic2; ?></a></div>
											<p class="Name"><a class="friendView" href="userFriendInfo.php?u=<?= $id; ?>"><?= $fname; ?>
											<p class="lastName"><a class="friendView" href="userFriendInfo.php?u=<?= $id; ?>"><?= $lname; ?></a></p>
											<a class="friendView" href="userFriendInfo.php?u=<?= $id; ?>">View</a>
										</div>
<?php
									}
								}
							}
					    }
					}
?>
						</div>
					</div>
<?php
	//this coonect will help us to take all the info that is relevent to a curent friends
	//
	$coonect = new DBLink();
	$k = "SELECT * FROM MILBER.milberfriends
			WHERE Friend_status_SQL = 'Yes'
			AND (id_Resiver_user_SQL = '$userID'
			OR id_Sender_request_SQL = '$userID')";
	$uppdate = $coonect->query($k);
	$allFriendsNum = mysqli_num_rows($uppdate);
	//this if statment checks if the user wants to delete a friend. If yes, this this if statment will run.
	//
	if(isset($_GET['dell'])){
		$userToBeDeleted = $_GET['dell'];
		$c = new DBLink();
		$delUser = "DELETE FROM MILBER.milberfriends WHERE id_Resiver_user_SQL = '$userID' AND id_Sender_request_SQL = '$userToBeDeleted'";
		$tableUppdated = $c->query($delUser);
		ob_end_flush();
		header('Location: userFriends.php');
		exit();
	}
	if($allFriendsNum == 0){
		$theNumOfAllFriends = "You have no friends";
?>
					<div class="sections_btn_holder"> 
						<p onclick="toggleNavPanel('#Option-12', 300);">All Friends</p> 
						<div id="Option-12" style="display:none">
<?php 				echo $theNumOfAllFriends;
	} else {
?>
						<div class="sections_btn_holder"> 
							<p onclick="toggleNavPanel('#Option-12', 300);">All Friends<span class="numOfTotalFriends"><?= $allFriendsNum; ?></span></p>
						<div id="Option-12" style="display:none">
<?php
					$sender = null;
					$resiver = null;
					$accept = null;
					while($res = mysqli_fetch_assoc($uppdate)){
						$gPic2 = null;
						$resiver = $res['id_Resiver_user_SQL'];
						$sender = $res['id_Sender_request_SQL'];
						$accept = $res['Friend_status_SQL'];
						if($resiver == $userID || $sender == $userID && ($accept == 'Yes')){
							$tempCon = new DBLink();
							$tempInfo = "SELECT * FROM MILBER.MilberUserInfo WHERE (id_SQL = '$sender' OR id_SQL = '$resiver')";
							$ress = $tempCon->query($tempInfo);
							while($g = mysqli_fetch_assoc($ress)){
								$fname = $g['fname_SQL'];
								$lname = $g['lname_SQL'];
								$gender = $g['gender_SQL'];
								$FriendFileSet = $g['folder_name_SQL'];
								$FriendPicSet = $g['user_pic_name_SQL'];
								$id = $g['id_SQL'];
								if($FriendFileSet == null && $FriendPicSet == null){
									$gPic2 = "<img class='NewFriendRequestPic' src='../milberUserPhotos/no_prof_pic.png' alt='<?= $FriendFname?>'>";
								} else {
									$gPic2 = "<img class='NewFriendRequestPic' src='../milberUserPhotos/profilePictures/$FriendFileSet/profile_$FriendPicSet' alt='<?= $username?>' />";
								}
								if($id != $userID){
?>
							<div class="CssIsForNewFriendOnlineFCurentF">
								<div><a class="friendView" href="userFriendInfo.php?u=<?= $id; ?>"><?= $gPic2; ?></a></div>
								<p class="Name"><a class="friendView" href="userFriendInfo.php?u=<?= $id; ?>"><?= $fname; ?></a></p>
								<p class="lastName"><a class="friendView" href="userFriendInfo.php?u=<?= $id; ?>"><?= $lname; ?></a></p>
								<a class="friendView" href="userFriendInfo.php?u=<?= $id; ?>">View</a><a href="userFriends.php?dell=<?= $id; ?>" class="delCurFriend" title="Remove <?= $fname; ?>">Unfriend</a>
							</div>
<?php
								}
							}
						}
					}
	}
?>
						</div>
					</div>
					<div class="CopyRight">
						<a href="termsL.php">Terms</a>
						<script>
							var curentDate = new Date();
							var years = curentDate.getFullYear();
							document.write('<p>Leafevent © ' + years + '</p>');
						</script>
					</div>
			</aside>
	</body>
</html>