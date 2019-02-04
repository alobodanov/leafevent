<?php $title_name = "User Name";?>
<?php 
 require_once "milbertoolbar.php";
 require_once "Library.php";
$curentPage = "view.php";
	if($_SESSION['userE_login'] == "" ){
   		header('Location: logout.php');
   		die();
   	}
?>
<?php
	$countResults = null; // will help us to know how many people and things in total were found.
	$notfound = null;
	if(isset($_GET['new'])){
		$link = new DBLink();
		$FriendIdnew = mysqli_real_escape_string($link->conn(), htmlentities(trim($_GET['new'])));	//an id of the new friend that a loged in user is trying to add
		$curentUser = $_SESSION['user_id']; //a loged in users id
		if($FriendIdnew == $curentUser){
			echo "You cannot send friend request to your self";
			die();
		} else {
			$q = "INSERT INTO MILBER.milberfriends VALUES('','$curentUser','$FriendIdnew','new')";
			$result = $link->query($q);
			$_SESSION['searchResoult'];
			$LookingFor = htmlentities(trim($_SESSION['searchResoult']));
			header("Location: view.php?c=$LookingFor");		
		}
	}
	if(isset($_GET['c'])){
		if(isset($_GET['s'])){
			if($_GET['s'] == "people"){
				$connect = mysqli_connect("localhost","eafeventroot","93milbefwsdfjyrhnt3252skhsnsser93@","MILBER") or die ("could not connect " . mysqli_connect_error());
				$looking = htmlentities(trim($_GET['c']));
				$looking = mysqli_real_escape_string($connect, $looking);
				$q = "SELECT * FROM MILBER.v_user_info 
						WHERE (fname_SQL LIKE '{$looking}%'  
						OR lname_SQL LIKE '{$looking}%')";
				$result = mysqli_query($connect, $q) or die ("could not query" . mysqli_error($connect));
				$countResults = mysqli_num_rows($result);
				mysqli_close($connect);
				if($countResults == 0){
					$notfound = "A persone that you are lookign for does not exists.";
				}
			} else if($_GET['s'] == "events"){
				$connect = mysqli_connect("localhost","eafeventroot","93milbefwsdfjyrhnt3252skhsnsser93@","MILBER") or die ("could not connect " . mysqli_connect_error());
				$looking = htmlentities(trim($_GET['c']));
				$looking = mysqli_real_escape_string($connect, $looking);						
				$q = "SELECT * FROM MILBER.v_event 
						WHERE event_Name_SQL LIKE '{$looking}%'  
						AND admin1_confirm_SQL = 'Y' 
						AND admin2_confirm_SQL = 'Y'";
				$result = mysqli_query($connect, $q) or die ("could not query" . mysqli_error($connect));
				$countResults = mysqli_num_rows($result);
				mysqli_close($connect);
				if($countResults == 0){
					$notfound = "No events were found.";
				}
			}
		} else {
			$connect = mysqli_connect("localhost","eafeventroot","93milbefwsdfjyrhnt3252skhsnsser93@","MILBER") or die ("could not connect " . mysqli_connect_error());
			$looking = htmlentities(trim($_GET['c']));
			$looking = mysqli_real_escape_string($connect, $looking);
			$q = "SELECT * FROM MILBER.v_event 
					WHERE event_Name_SQL LIKE '{$looking}%'  
					AND admin1_confirm_SQL = 'Y' 
					AND admin2_confirm_SQL = 'Y' ";
			$result = mysqli_query($connect, $q) or die ("could not query" . mysqli_error($connect));
			$countResults = mysqli_num_rows($result);
			mysqli_close($connect);
			if($countResults == 0){
				$notfound = "No events were found.";
			}
		}
	}
?>
<div class="view-options-by"><a href="view.php?c=<?=$_GET['c']?>&s=people">People</a><a href="view.php?c=<?=$_GET['c']?>&s=events">Events</a></div>
<div class="CopyRightView">
			<a href="termsL.php">Terms</a>
			<script>
				var curentDate = new Date();
				var years = curentDate.getFullYear();
				document.write('<p>Leafevent © ' + years + '</p>');  
			</script>
		</div>
<?php
	if($countResults == 0){
?>
		<div class="resultWindow-error">
			<p><?=$notfound;?></p>
		</div>
<?php
		die();
	} else {
?>
	<div class="searchDisplayResult">
		<script>
			 $(document).ready(function() {
                $("#bg > img#1").fadeIn(300);
                checkSize();
              });
			 function checkSize() {
	            var num = document.getElementsByClassName("SearchedEventName").length;
	            var i;
	            for(i = 0; i < num; i++){
	                if(document.getElementsByClassName("SearchedEventName")[i].innerHTML.length > 25){
	                    var text = document.getElementsByClassName("SearchedEventName")[i].innerHTML.substring(0,25);
	                    text = text + "...";
	                    document.getElementsByClassName("SearchedEventName")[i].innerHTML = text;
	                }
	            }
	        };
		</script>
<?php
		$gPic = null;
		$buttonToFriend = null;
		if(isset($_GET['s'])){
			if($_GET['s'] == "people"){
				while($s = mysqli_fetch_assoc($result)){
					$FriendGender = $s['gender_SQL'];
					$id = $s['id_SQL'];
					$folderName = $s['folder_name_SQL'];
					$picName = $s['user_pic_name_SQL'];
					$con = mysqli_connect("localhost","eafeventroot","93milbefwsdfjyrhnt3252skhsnsser93@","MILBER") or die ("could not connect " . mysqli_connect_error());
					$sel = "SELECT * FROM MILBER.milberfriends WHERE id_Sender_request_SQL = '$id'";
					$res = mysqli_query($con, $sel) or die ('could not sel' . mysqli_error($con));
					if($folderName != null && $picName != null){
						$picLocation = "<img class='searchResultPic' src='../milberUserPhotos/profilePictures/$folderName/view_$picName' alt='img'/>";
					} else {
							$picLocation = "<img class='searchResultPic' src='../milberUserPhotos/no_prof_pic.png' alt='<?= $username?>'/>";
					}
					while($l = mysqli_fetch_assoc($res)){
						$confFriends = $l['Friend_status_SQL'];
						$sender = $l['id_Sender_request_SQL'];
						$resiver = $l['id_Resiver_user_SQL'];
						if($curentUserId == $sender || $curentUserId == $resiver && ($confFriends == 'yes')){
							//$buttonToFriend = "<span class='friendsAlready'>Friends</span>";
						} else {
							//$fId = $s['id_SQL'];
							//$buttonToFriend = "<a href="view.php?new=<?= $fId; " class="resultAdd">Add</a>";
						}
					}
					if($id != $curentUserId){
	?>
						<div class="resultWindow">
							<a href="viewEvent.php?event=<?=$eventID;?>"><?= $picLocation; ?></a>
							<p class="SearchedName"><?= $s['fname_SQL']; ?><br /><?= $s['lname_SQL']; ?><br /></p>
							<a href="userFriendInfo.php?u=<?= $s['id_SQL']; ?>" class="resultView">View</a>
							<a href="view.php?new=<?= $s['id_SQL']; ?>" class="resultAdd">Add</a>
							<!--<span><?= $buttonToFriend; ?></span>-->
						</div>
<?php
					}
				}
			} else if($_GET['s'] == "events"){
				while($s = mysqli_fetch_assoc($result)){
					$eventName = $s['event_Name_SQL'];
					$eventID = $s['event_id_SQL'];
					$eventMaker = $s['who_is_making_event_SQL'];
					$eventfolder = $s['event_pic_folder_SQL'];
					$eventPic = $s['event_picture_SQL'];
					if($eventfolder != null && $eventPic != null){
						$picLocation = "<img class='searchResultPic' src='../milberUserPhotos/eventPhotos/$eventfolder/$eventPic' alt='img'/>";
					} else {
						$picLocation = "<img class='searchResultPic' src='../milberUserPhotos/no_prof_pic.png' alt='img'/>";
					}
?>
					<div class="resultWindow">
						<a href="viewEvent.php?event=<?=$eventID;?>"><?= $picLocation; ?></a>
						<p class="SearchedEventName"><?= $eventName;?><br /></p>
						<p class="MakerName">By <?= $eventMaker;?></p>
						<a href="viewEvent.php?event=<?=$eventID;?>" class="resultView">View</a>
					</div>
<?php
				}
			}
		} else {
			while($s = mysqli_fetch_assoc($result)){
				$eventName = $s['event_Name_SQL'];
				$eventID = $s['event_id_SQL'];
				$eventMaker = $s['who_is_making_event_SQL'];
				$eventfolder = $s['event_pic_folder_SQL'];
				$eventPic = $s['event_picture_SQL'];
				if($eventfolder != null && $eventPic != null){
					$picLocation = "<img class='searchResultPic' src='../milberUserPhotos/eventPhotos/$eventfolder/$eventPic' alt='img'/>";
				} else {
					$picLocation = "<img class='searchResultPic' src='../milberUserPhotos/no_prof_pic.png' alt='img'/>";
				}
?>
				<div class="resultWindow">
					<a href="viewEvent.php?event=<?=$eventID;?>"><?= $picLocation; ?></a>
					<p class="SearchedEventName"><?= $eventName;?><br /></p>
					<p class="MakerName">By <?= $eventMaker;?></p>
					<a href="viewEvent.php?event=<?=$eventID;?>" class="resultView">View</a>
				</div>
<?php
			}

		}
	}
?>
	</div>
	<!--<div id="goUpfview"><a href="javascript:scroll(0,0)">Up</a></div>-->
	</body>
</html>