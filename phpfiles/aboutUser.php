<?php require "milbertoolbar.php"; //it has all the code for a "grean bar"
$curentPage = "aboutUser.php" ?>
<?php require "UsercontentLeftside.php"; ?>
<?php require_once "Library.php" ?>
<?php
	if($_SESSION['userE_login'] == "" ){
   		header('Location: logout.php');
   		die();
   	}

   	$curentUserId;
   	$dbLink = new DBLink();
	$uProf = "SELECT * FROM v_user_info WHERE id_SQL = '$curentUserId'";	
	$result = $dbLink->query($uProf);

	while($u = mysqli_fetch_assoc($result)){
			$friend_folder = $u['folder_name_SQL'];
			$friend_pic = $u['user_pic_name_SQL'];
			$friend_gender = $u['gender_SQL'];
			$friend_id = $u['id_SQL'];
			$friend_email = $u['email_SQL'];
			$friend_name = $u['fname_SQL'] . " " . $u['lname_SQL'];
			if($friend_folder == null || $friend_pic == null){
				if($friend_gender == 'Male'){
					$imagePath = "<img class='friendProfilePic' src='../milberUserPhotos/blue.jpg' alt='<?= $friend_name?>' />";
				}else{ 
				 	$imagePath = "<img class='friendProfilePic' src='../milberUserPhotos/pink.jpg' alt='<?= $friend_name?>' />";
				}
			} else {
				$imagePath = "<img class='friendProfilePic' src='../milberUserPhotos/profilePictures/$friend_folder/$friend_pic' alt='<?= $friend_name?>' />";
			}

		}
?>
			<div class="userInfo">
				<div class="userHeader">

				</div>



			</div>
	</body>
</html>

