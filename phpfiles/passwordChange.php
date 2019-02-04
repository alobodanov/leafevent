<?php
	//This code will help us to change password in DB form old to new
	// 
session_start();
	$oldPassword = $_POST['old'];

	if($oldPassword != $userP && $oldPassword) {
		$wrongOldPassword_error = "Please enter your old Milber password.";
		$newPasswordCheck = false;
	}
	if(!preg_match("/^[a-z]{0,50}?[0-9]{0,50}?[a-z0-90-9a-z]{1,50}?$/i", trim($_GET['newPassword']))){
		$NEWpassword_error = "Password can only have letters and integers.";
		$newPasswordCheck = false;
		if($_GET['newPassword'] == ""){
			$NEWpassword_error = "Please enter your password.";
			$newPasswordCheck = false;
		}
	}
	if(!preg_match("/^[a-z]{0,50}?[0-9]{0,50}?[a-z0-90-9a-z]{1,50}?$/i", trim($_GET['confirmNewPassword']))){
		$NEWpasswordCONFIRM_error = "Password can only have letters and integers.";
		$newPasswordCheck = false;
		if($_GET['confirmNewPassword'] == ""){
			$NEWpasswordCONFIRM_error = "Please enter your password.";
			$newPasswordCheck = false;
		}
	}
	if($_GET['newPassword'] != $_GET['confirmNewPassword']){
		$NEWpasswordNotTheSame_error = "You have entered two different passwords, please try again.";
		$newPasswordCheck = false;
	}
	if($newPasswordCheck && $_GET['newPassword']){
		$newPass = $_GET['newPassword'];
		$connection = mysqli_connect("localhost","root","Password123") or die ("Could not connect to DataBase". mysqli_connect_error());
		$update = "UPDATE MILBER.MilberUserInfo SET password_SQL = '$newPass' WHERE id_SQL = '$userID'";
		$nameUp = mysqli_query($connection,$update) or die ("Could not query" . mysqli_error($connection)); 
		mysqli_close($connection);
	}
//header('Location: userSettings.php');
?>