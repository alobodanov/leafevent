<?php $title_name = "User Name"; ?>
<?php require "milbertoolbar.php"; ?>
<?php require "UsercontentLeftside.php"; ?>
<?php
	$curentUserId  = $_SESSION['user_id'];
	$LogInUsersId  = null;	//comes from DB and it's been user as a check
	$usersFriendID = null;
	$NewCurrentNot = null; //to save the info if they are friends or not
	//this variables contain info about a friend
	$FriendID	 = null;
	$FriendFname = null;
	$FriendLname = null;
	//this var contain the info only if the friend is new
	$newFriendID	= null;
	$newFriendFname = null;
	$newFriendLname = null;
	$confirmationFriend = "Confirmed"; //that will be saved to DB if the user accepted a friend request.
	//this var will contain the info only if the user was accepted.
	$oldFriendID	= null;
	$oldFriendFname = null;
	$oldFriendLname = null;
	//this var for online friends
	$onlineFriendID	   = null;
	$onlineFriendFname = null;
	$onlineFriendLname = null;

	$theNumOfNewFriends = 0;
	$count = 0;

	/*if(isset($_GET['useradded'])){
		$link = mysqli_connect("localhost", "root", "Password123") or die("could not connect ". mysqli_connect_error());
		$q = "UPDATE MILBER.MilberFriends 
			SET Friend_accept_SQL = 'Accepted' 
			WHERE id_Friends_OfCurrentUser_SQL = '$newFriendID'";
		$result = mysqli_query($link,$q) or die ('could not query' . mysqli_error($link));
		mysqli_close($link);
	}
	//selectting all the info from frind BD so we can know after who is new friend, who was accepted and who was not.
	//
	$link = mysqli_connect("localhost", "root", "Password123") or die("could not connect ". mysqli_connect_error());
	//$q = "SELECT * FROM MILBER.MilberFriends WHERE id_Current_user_SQL = $curentUserId AND Friend_accept_SQL = NULL";
	$q = "SELECT * FROM MILBER.MilberFriends WHERE id_Friends_ofCurrentUser_SQL = $curentUserId AND Friend_accept_SQL = NULL";
	$count = count($q);
	if($count > 0){
		$theNumOfNewFriends = $count;
	}
	$result = mysqli_query($link, $q) or die ("could not query" . mysqli_error($link));
*/?>
				<aside id="rightTools">
				<script> 
					function toggleNavPanel(element, speed){ 
						$(element).toggle(speed);
					} 
				</script>  
					
					<div class="sections_btn_holder"> 
						<p onclick="toggleNavPanel('#Option-10', 300);"><?= $theNumOfNewFriends . " "; ?>New Friends</p>
					</div> 
					<div id="Option-10" style="display:none">


<?php/*
	while($s = mysqli_fetch_array($result)){
		$usersFriendID = $s['id_Friends_OfCurrentUser_SQL'];
		$LogInUsersId = $s['id_Current_user_SQL'];
		$NewCurrentNot = $s['Friend_accept_SQL']; //saves the variable that tells us if he is a current friend or not
		//want to select all the info about the users
		//
		$w = "SELECT * FROM MILBER.MilberUserInfo WHERE id_SQL = $usersFriendID";
		$contains = mysqli_query($link, $w) or die ("could not query " . mysqli_error($link));
		//save thiers info the var for latter use
		//
		while($u = mysqli_fetch_array($contains)){
			$FriendID	 = $u['id_SQL'];
			$FriendFname = $u['fname_SQL'];
			$FriendLname = $u['lname_SQL'];
		}
		//if NewCurrentNot is null, it will display info in the new friend section
		//
		if($NewCurrentNot == NULL && $curentUserId == $LogInUsersId){
			$newFriendID	= $FriendID;
			$newFriendFname = $FriendFname;
			$newFriendLname = $FriendLname;
			$userNameCombined = $newFriendFname . " " . $newFriendLname;
*/?>
		<!--<div class="NewFriendList">
			<img class="NewFriendPic" src="../images/girl.jpg" alt="user" />
			<div class="names"><?= $userNameCombined; ?></div>
			<a href="userFriends.php?useradded=<?php echo $newFriendID; ?>" class="confirms">Confirm</a>

		</div>-->

<?php/*
		}
	}*/

?>
					</div>
					<hr />
					<div class="sections_btn_holder"> 
						<p onclick="toggleNavPanel('#Option-11', 300);">Online Friends</p>
					</div> 
					<div id="Option-11" style="display:none">
						hello 11



<?php/*
	while($s = mysqli_fetch_array($result)){
		$usersFriendID = $s['id_Friends_OfCurrentUser_SQL'];
		$NewCurrentNot = $s['Friend_accept_SQL']; //saves the variable that tells us if he is a current friend or not
		//want to select all the info about the users
		//
		$w = "SELECT * FROM MILBER.MilberUserInfo WHERE id_SQL = $usersFriendID";
		$contains = mysqli_query($link, $w) or die ("could not query " . mysqli_error($link));
		//save thiers info the var for latter use
		//
		while($u = mysqli_fetch_array($contains)){
			$FriendID	 = $u['id_SQL'];
			$FriendFname = $u['fname_SQL'];
			$FriendLname = $u['lname_SQL'];
		}
		//if NewCurrentNot is null, it will display info in the new friend section
		//
		if($NewCurrentNot == NULL){
			$newFriendID	= $FriendID;
			$newFriendFname = $FriendFname;
			$newFriendLname = $FriendLname;
		//for online section
		//
		} else {
			$onlineFriendID	   = $FriendID;
			$onlineFriendFname = $FriendFname;
			$onlineFriendLname = $FriendLname;
			echo $onlineFriendFname;

		}
	}*/

?>
					</div>
					<hr />
					<div class="sections_btn_holder"> 
						<p onclick="toggleNavPanel('#Option-12', 300);">All Friends</p>
					</div> 
					<div id="Option-12" style="display:none">
<?php/*
	while($s = mysqli_fetch_array($result)){
		$usersFriendID = $s['id_Friends_OfCurrentUser_SQL'];
		$NewCurrentNot = $s['Friend_accept_SQL']; //saves the variable that tells us if he is a current friend or not
		//want to select all the info about the users
		//
		$w = "SELECT * FROM MILBER.MilberUserInfo WHERE id_SQL = $usersFriendID";
		$contains = mysqli_query($link, $w) or die ("could not query " . mysqli_error($link));
		//save thiers info the var for latter use
		//
		while($u = mysqli_fetch_array($contains)){
			$FriendID	 = $u['id_SQL'];
			$FriendFname = $u['fname_SQL'];
			$FriendLname = $u['lname_SQL'];
		}
		//if NewCurrentNot contains Accepted, it will display info in the current friend list
		//
		if ($NewCurrentNot == 'Accepted'){
			$oldFriendID	= $FriendID;
			$oldFriendFname = $FriendFname;
			$oldFriendLname = $FriendLname;
		}
	}*/

?>







					</div>
					<hr />
					<br />
			</aside>
			<?php require "milberMenuEnd.php"; ?>
