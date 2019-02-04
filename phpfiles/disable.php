<?php require "milbertoolbar.php"; ?>
<?php
	//Instead of deleting the info from DB, a user can disable it.
	//This file helps us to acomplish this task 
	//

	require_once "Library.php";
	$userID = "";
	$userP = "";
	if($_SESSION['userE_login'] == ""){
   		header('Location: logout.php');
   		die();
   	}
	if(isset($_GET['e'])){
		$link = new DBLink();
		$userEmail = htmlentities(trim($_GET['e']));
		$find = "SELECT * FROM MILBER.v_user_info WHERE email_SQL = '$userEmail' LIMIT 1";
		$result = $link->query($find);
		$count = mysqli_num_rows($result);
		if($count === 1){
			while($get = mysqli_fetch_array($result)){
				$userID = $get['id_SQL'];
				$username = $get['fname_SQL'];
				$userlname = $get['lname_SQL'];
				$useremail = $get['email_SQL'];
				$userP = $get['password_SQL'];
				$userDisable = $get['disable_SQL'];
			}
		} else {
			echo "Profile does not exist";
			exit();
		}
	}
	$feedback_error = "";
	$password1_error = "";
	$check = true;
	if($_POST){
		if(!preg_match( '/^[a-z ]{0,300}+[0-9 ]{0,}?[a-z ]{0,}?[0-9 ]{0,}?$/i', htmlentities(trim($_POST['feedback'])))){
			$feedback_error = "Please enter only letters and numbers.";
			$check = false;
		}
		if(!preg_match("/^[a-z]{0,50}?[0-9]{0,50}?[a-z0-90-9a-z]{1,50}?$/i", htmlentities(trim($_POST['disapleAccountPassword'])))){
			$password1_error = "Password can only have letters and integers.";
			$check = false;
			if($_POST['disapleAccountPassword'] == ""){
				$password1_error = "Please enter your password.";
				$check = false;
			}
		}
		if(!preg_match("/^[0-9 ]{0,}?[a-z]{0,50}?[0-9]{0,50}?[a-z0-90-9a-z]{1,50}?$/i", htmlentities(trim($_POST['disapleAccountPasswordConfirm'])))){
			$password1_error = "Password can only have letters and integers.";
			$check = false;
			if($_POST['disapleAccountPasswordConfirm'] == ""){
				$password1_error = "Please enter your password.";
				$check = false;
			}
		}
		if($_POST['disapleAccountPassword'] != $_POST['disapleAccountPasswordConfirm']){
			$diferentPassword = "Password has to match.";
			$check = false;
		}
		if($_POST['disapleAccountPassword'] != $userP){
			$diferentPassword = "You must enter your Leafevent password.";
			$check = false;
		}
	}
	if($_POST && $check){
		//Save the comment from the user that is disabling the account (if they did type something in)
		//
		$connection = new DBLink();
		$fbn = $_POST['feedback'];
		$fbn = mysqli_real_escape_string($connection->conn(), htmlentities(trim($fbn)));
		$d = date("Y-m-d");
		$uid = $userID;
		$validUser = "INSERT INTO MILBER.feedback VALUES('','$fbn','$d','$uid')";
		$result = $connection->query($validUser);
		
		$connection = new DBLink();
		$currentUser = "UPDATE MILBER.MilberUserInfo SET disable_SQL = 'y' WHERE id_SQL = '$userID'";
		$disableAccount = $connection->query($currentUser);
		header("Location: logout.php");
	}


?>
<?php $theTitle = "Disable your Leafevent account"; ?>
	<div class="disable-user-account">
		<p>If you disable your account: you won't be able to log in or see any events, no one will be able to post events on your page or see your content, information, or where you've been.</p>
		<form method="POST" class="disableform"/>
			<textarea class="feedback" name="feedback" placeholder="You are welcome to tell us why you are disabling your account."></textarea><br />
			<p>To disable your account, please confirm it with your password</p><br />
			<input type="password" name="disapleAccountPassword" placeholder="Password"/><br />
			<input type="password" name="disapleAccountPasswordConfirm" placeholder="Password confrm"/><br />
			<input type="submit" name="feedbackSubmit" value="Disable" />
			<a href="home.php">Cancel</a>
		</form>   
	</div>
<?php require "milberMenuEnd.php"; ?>