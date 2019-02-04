<?php
	//This file will delete all the users information from DB if they want to and there 
	//will be nothing left about that user. So if they register back again, they will have to 
	//add everything again.
	//
	session_start();
	$userID = "";
	$userP = "";
	$check = true;
	$incorectInput_error = "";
	$notMilberCheck = false;
	$_GET['e'] = $_SESSION['userE_login'];
	//check if session still has the log in info
	//
	if($_SESSION['userE_login'] == ""){
   		header('Location: logout.php');
   	}
   	//GET[e] conta
	if(isset($_GET['e'])){
		$link = mysqli_connect("localhost", "root", "Password123") or die("could not connect ". mysqli_connect_error());
		//$ue =  $_GET['e'];
		if($_GET['e']){
			$userEmail = $_GET['e'];
			$find = "SELECT * FROM MILBER.MilberUserInfo WHERE email_SQL = '$userEmail' LIMIT 1";
			$result = mysqli_query($link,$find) or die("Could not query ". mysqli_error($link));
			$count = mysqli_num_rows($result);
			mysqli_close($link);
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
		if($_POST){
			if(!preg_match("/^[a-z]{0,50}?[0-9]{0,50}?[a-z0-90-9a-z]{1,50}?$/i", trim($_POST['passwordDelete']))){
				$password1_error = "Password can only have letters and integers.";
				$check = false;
				if($_POST['pwd1'] == ""){
					$password1_error = "Please enter your password.";
					$check = false;
				}
			}
			if(!preg_match("/^[a-z]{0,50}?[0-9]{0,50}?[a-z0-90-9a-z]{1,50}?$/i", trim($_POST['passwordDeleteConfirm']))){
				$password1_error = "Password can only have letters and integers.";
				$check = false;
				if($_POST['pwd1'] == ""){
					$password1_error = "Please enter your password.";
					$check = false;
				}
			}
			if($_POST['passwordDelete'] != $_POST['passwordDeleteConfirm']){
				$incorectInput_error = "One of your passwords is wrong, please try again.";
				$check = false;
			}
			if($_POST['passwordDelete'] == $userP){
				$notMilberCheck = true;
			}
			if($_POST && $notMilberCheck && $check){
				$connection = mysqli_connect("localhost","root","Password123") or die ("Could not connect to DataBase". mysqli_connect_error());
				$readyToDelete = "DELETE FROM MILBER.MilberUserInfo WHERE id_SQL = '$userID'";
				$done = mysqli_query($connection, $readyToDelete) or die ("Could not delete". mysqli_error());
				mysqli_close($connection);
				header('Location: index.php');
			}
		}
	}
?>
<?php $theTitle = "Deleting your Leafevent account"; ?>
<?php require "disableDeleteHeader.php"; ?>
			<p>If you will delete our profile, all information you have on your Leafevent account will be deleted.<br /><br />
				Please type your password to confirm it.</p>
			<form method="POST" class="deletingForm">
				<input type="password" name="passwordDelete" placeholder="Password" /><br />
				<input type="password" name="passwordDeleteConfirm" placeholder="Password Confirm" /><br />
				<input type="submit" name="deleteAccountConfirm" value="Delete" />
				<a href="home.php">Cancel</a>
			</form>

<?php require "milberMenuEnd.php"; ?>
