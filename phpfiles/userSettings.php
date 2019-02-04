<?php ob_start(); $title_name = "User Name";?>
<?php require_once "milbertoolbar.php";
$curentPage = "userSettings.php"; //curent page?>
<?php require_once "Library.php"; ?>
<?php require_once "UsercontentLeftside.php";
ini_set("memory_limit","10M");

	if($_SESSION['userE_login'] == "" ){
   		header('Location: logout.php');
   		die();
   	}
	//will have error info
	//
	$nameChange_error = "";
	$middleNameChange_error = "";
	$lastNameChange_error = "";
	$gender_error = "";
	$emailChange_error = "";
	$emailChangeConfirm_error = "";
	$passwordSave_error = "";
	$passwordSaveConfirm_error = "";
	$emailexist_error = "";
	$dayMonthYear_error = "";
	$wrongOldPassword_error = "";
	$NEWpassword_error = "";
	$NEWpasswordCONFIRM_error = "";
	$NEWpasswordNotTheSame_error = "";
	$photo_error = "";
	$equal_email = "";
	$equal_password = "";
	$infoChange = "";
	$newPasswordCheck = true;
	$milber_password = true;
	$flagForMakingNewPassword = false;
	$flagForInfoChange = true;
	$check = true;
	//Event variables
	$eventType = null;
	$eventName = null;
	$eventAddress = null;
	$eventPrice = null;

   	$user_email = $_SESSION['userE_login'];

   	$dbLink = new DBLink();
	$eventTable = "SELECT * FROM MILBER.v_user_interests_event WHERE event_Posted_by_id_SQL = '$userID'";
	$eventResult = $dbLink->query($eventTable);

	$dbLink = new DBLink();
	$eventTopic_select = "SELECT id_SQL, topic_name_SQL FROM MILBER.EventTopic ORDER BY id_SQL";
	$eventTopic_list = $dbLink->query($eventTopic_select);

	$dbLink = new DBLink();
	$eventType_select = "SELECT id_SQL, type_name_SQL FROM MILBER.EventType ORDER BY id_SQL";
	$eventType_list = $dbLink->query($eventType_select);

	$dbLink = new DBLink();
	$events_user_likes = "SELECT * FROM MILBER.UserInterests WHERE user_id_SQL = '$userID'";
	$event_user_select_list = $dbLink->query($events_user_likes);


	if($_POST){
		//this is for user info change
		//
		if($_REQUEST['Uppdate'] == "SubmitUserInfo"){
			if(!preg_match("/^[a-z]{1,50}$/i", htmlentities(trim($_POST['nameChange'])))){
				$n = htmlentities(trim($_POST['nameChange']));
				$nameChange_error = "Please enter only letters.";
				$check = false;
				if($_POST['nameChange'] == ""){
					$nameChange_error = "Please enter your name.";
					$check = false;
				}
			}
			if(!preg_match("/^[a-z]{0,100}$/i", htmlentities(trim($_POST['middleNameChange'])))){
				$n = htmlentities(trim($_POST['middleNameChange']));
				$middleNameChange_error = "You entered $n. Enter only letters.";
				$check = false;
			}
			if(!preg_match("/^[a-z]{1,100}$/i", htmlentities(trim($_POST['lastNameChange'])))){
				$n = htmlentities(trim($_POST['lastNameChange']));
				$lastNameChange_error = "You entered $n. Enter only letters.";
				$check = false;
				if($_POST['lastNameChange'] == ""){
					$lastNameChange_error = "Please enter your last name.";
					$check = false;
				}
			}
			if(!preg_match("/^[a-z]{0,50}?[a-z0-9]{0,50}?[a-z';:.,]{0,50}?[@][a-zA-Z]{1,30}[.][a-zA-Z]{2,3}$/i", htmlentities(trim($_POST['emailChange'])))){
				$n = htmlentities(trim($_POST['emailChange']));
				$emailChange_error = "You entered $n. Enter your email.";
				$check = false;
				if($_POST['emailChange'] == ""){
					$emailChange_error = "Please enter your email.";
					$check = false;
				}
			}
			if(!preg_match("/^[a-z]{0,50}?[a-z0-9]{0,50}?[a-z';:.,]{0,50}?[@][a-zA-Z]{1,30}[.][a-zA-Z]{2,3}$/i", htmlentities(trim($_POST['emailChangeConfirm'])))){
				$n = htmlentities(trim($_POST['emailChangeConfirm']));
				$emailChangeConfirm_error = "You entered $n. Enter the same email.";
				$check = false;
				if($_POST['emailChangeConfirm'] == ""){
					$emailChangeConfirm_error = "Please confirm your email.";
					$check = false;
				}
			}
			if(htmlentities(trim($_POST['emailChange'])) != htmlentities(trim($_POST['emailChangeConfirm']))){
				$equal_email = "Emails do not match.";
				$check = false;
			}
			// will check if the password was entered is the same as the one they have for Milber.
			//
			if(!preg_match("/^[a-z]{0,50}?[0-9]{0,50}?[a-z0-90-9a-z]{1,50}?$/i", htmlentities(trim($_POST['passwordSave'])))){
				$passwordSave_error = "Enter only your Milber password.";
				$check = false;
				if($_POST['passwordSave'] == ""){
					$passwordSave_error = "Enter your password.";
					$check = false;
				}
			}
			if($check){
				$connectionPass = new DBLink();
				$passCheck = "SELECT password_SQL FROM MILBER.MilberUserInfo WHERE id_SQL = '$userID' LIMIT 1";
				$queCheck = $connectionPass->query($passCheck);
				$temp = null;
				while($pp = mysqli_fetch_array($queCheck)){
					$temp = $pp['password_SQL'];
					$user_pass = mysqli_real_escape_string($connectionPass->conn(), htmlentities(trim($_POST['passwordSave'])));
					if($user_pass != $temp){
						$equal_password = "Please enter a correct password";
						$milber_password = false;
					}
				}
			}
			if($_POST && $check && $milber_password) {
				if($file != null){
					if($profPicture == null){
						if($_FILES['profilePicChange']['type'] == 'image/jpeg'
						||  $_FILES['profilePicChange']['type'] == 'image/jpg'
						||  $_FILES['profilePicChange']['type'] == 'image/gif'
						|| $_FILES['profilePicChange']['type'] == 'image/png'){

							$charSet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
							$fileName = substr(str_shuffle($charSet), 0, 15);

							$fileTmpLoc = $_FILES["profilePicChange"]["tmp_name"];
							$pathAndName = "../milberUserPhotos/profilePictures/$file/".$fileName;
							$moveResult = move_uploaded_file($fileTmpLoc, $pathAndName);

							$newPicProffCon = new DBLink();
							$pic_quesry_select = "UPDATE MILBER.MilberUserInfo SET user_pic_name_SQL = '$fileName' WHERE id_SQL = '$userID'";
							$pic_q = $newPicProffCon->query($pic_quesry_select);
						}
					} else {
						$fileName = $_FILES['profilePicChange']['name'];
						$fileTmpLoc = $_FILES["profilePicChange"]["tmp_name"];
						$fileType = $_FILES['profilePicChange']['type'];
						$fileSize['profilePicChange']['size'];
						$fileErrorMsg = $_FILES['profilePicChange']['error'];
						$kaboom = explode(".",$fileName);
						$fileExt = $kaboom[1];
						$error2 = null;

						if(!$fileTmpLoc){
							$error = "ERROR: chose file first before uploading a file";
							echo $error2;
							exit();
						} else if($fileSize > 15242880){
							$error2 = "ERROR: Your file was larger then 15 megabytes in size";
							echo $error2;
							unlink($fileTmpLoc);
							exit();
						} else if(!preg_match("/\.(gif|jpg|png)$/i", $fileName)){
							$error2 = "ERROR: Your image was not .gif, .jpg or .png";
							echo $error2;
							unlink($fileTmpLoc);
							exit();
						} else if($fileTmpLoc == 1){
							$error2 = "ERROR: An error accured while processign the file. Please try again.";
							echo $error2;
							exit();
						}

						$charSet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
						$fileName2 = substr(str_shuffle($charSet), 0, 15);
						$pathAndName = "../milberUserPhotos/profilePictures/$file/".$fileName2;
						$moveResult = move_uploaded_file($fileTmpLoc, $pathAndName);
						if($moveResult != true){
							$error2 = "ERROR: File not uploaded. Please try again.";
							unlink($fileTmpLoc);
							//exit();
						} else {
							$newPicProffCon = new DBLink();
							$pic_quesry_select = "UPDATE MILBER.MilberUserInfo SET user_pic_name_SQL = '$fileName2' WHERE id_SQL = '$userID'";
							$pic_q = $newPicProffCon->query($pic_quesry_select);
						}
						unlink($fileTmpLoc);

						include_once("ak_php_img_lib_1.0.php");
						$target_file = "../milberUserPhotos/profilePictures/$file/$fileName2";
						$resized_file = "../milberUserPhotos/profilePictures/$file/resized_$fileName2";
						$resized_file_prof = "../milberUserPhotos/profilePictures/$file/profile_$fileName2";
						$view_file_prof = "../milberUserPhotos/profilePictures/$file/view_$fileName2";
						$wmax = 125;
						$hmax = 65;
						ak_img_resize($target_file,$resized_file,$wmax,$hmax,$fileExt);
						$wmax2 = 250;
						$hmax2 = 250;
						ak_img_resize($target_file,$resized_file_prof,$wmax2,$hmax2,$fileExt);
						$wmax3 = 150;
						$hmax3 = 150;
						ak_img_resize($target_file,$view_file_prof,$wmax3,$hmax3,$fileExt);
					}
				} else {
					$fileName = $_FILES['profilePicChange']['name'];
					$fileTmpLoc = $_FILES["profilePicChange"]["tmp_name"];
					$fileType = $_FILES['profilePicChange']['type'];
					$fileSize['profilePicChange']['size'];
					$fileErrorMsg = $_FILES['profilePicChange']['error'];
					$kaboom = explode(".",$fileName);
					$fileExt = $kaboom[1];
					$error2 = null;

					if(!$fileTmpLoc){
						$error = "ERROR: chose file first before uploading a file";
						echo $error2;
						//exit();
					} else if($fileSize > 6242880){
						$error2 = "<br />ERROR: Your file was larger then 15 megabytes in size";
						echo $error2;
						unlink($fileTmpLoc);
						//exit();
					} else if(!preg_match("/\.(gif|jpg|png)$/i", $fileName)){
						$error2 = "<br />ERROR: Your image was not .gif, .jpg or .png";
						echo $error2;
						unlink($fileTmpLoc);
						//exit();
					} else if($fileTmpLoc == 1){
						$error2 = "<br />ERROR: An error accured while processign the file. Please try again.";
						echo $error2;
						//exit();
					}
					$charSet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
					$fileName2 = substr(str_shuffle($charSet), 0, 15);
					$file = substr(str_shuffle($charSet), 0, 15);
					$pathAndName = "../milberUserPhotos/profilePictures/$file/".$fileName2;
					$moveResult = move_uploaded_file($fileTmpLoc, $pathAndName);
					if($moveResult != true){
						$error2 = "<br />ERROR: File not uploaded. Please try again.";
						unlink($fileTmpLoc);
						exit();
					} else {
						$newPicProffCon = new DBLink();
						$pic_quesry_select = "UPDATE MILBER.MilberUserInfo SET user_pic_name_SQL = '$fileName2' WHERE id_SQL = '$userID'";
						$pic_q = $newPicProffCon->query($pic_quesry_select);
					}
					unlink($fileTmpLoc);
					include_once("ak_php_img_lib_1.0.php");
					$target_file = "../milberUserPhotos/profilePictures/$file/$fileName2";
					$resized_file = "../milberUserPhotos/profilePictures/$file/resized_$fileName2";
					$resized_file_prof = "../milberUserPhotos/profilePictures/$file/profile_$fileName2";
					$view_file_prof = "../milberUserPhotos/profilePictures/$file/view_$fileName2";
					$wmax = 100;
					$hmax = 50;
					ak_img_resize($target_file,$resized_file,$wmax,$hmax,$fileExt);
					$wmax2 = 150;
					$hmax2 = 150;
					ak_img_resize($target_file,$resized_file_prof,$wmax2,$hmax2,$fileExt);
					$wmax3 = 90;
					$hmax3 = 90;
					ak_img_resize($target_file,$view_file_prof,$wmax3,$hmax3,$fileExt);
				}
				//Will update database with the new user information.
				//
				$infoChange = $_SESSION['userE_login'];
				$existingUser = mysqli_num_rows($result);
				$connection = mysqli_connect("localhost","eafeventroot","93milbefwsdfjyrhnt3252skhsnsser93@","MILBER") or die ("could not connect " . mysqli_connect_error());
        		$newE = htmlentities(trim($_POST['emailChange']));
				$newE = mysqli_real_escape_string($connection, $newE);
				$newName = htmlentities(trim($_POST['nameChange']));
				$newName = mysqli_real_escape_string($connection, $newName);
				$newMname = htmlentities(trim($_POST['middleNameChange']));
				$newMname = mysqli_real_escape_string($connection, $newMname);
				$newLname = htmlentities(trim($_POST['lastNameChange']));
				$newLname = mysqli_real_escape_string($connection, $newLname);
				$update = "UPDATE MILBER.MilberUserInfo SET email_SQL = '$newE', fname_SQL = '$newName', middle_name_SQL = '$newMname', lname_SQL = '$newLname'  WHERE id_SQL = '$userID'";
				$nameUp = mysqli_query($connection,$update) or die ("Could not query" . mysqli_error($connection)); 
				mysqli_close($connection);
				header('Location: userSettings.php');
				ob_end_flush();
				exit();
			}
		}
		//this is for password change 
		//
		else if($_REQUEST['Uppdate'] == "Change Password") {
			if(htmlentities(trim($_POST['oldPassword'])) != $userP) {
				$wrongOldPassword_error = "Please enter your old Milber password.";
				$newPasswordCheck = false;
			}
			if(!preg_match("/^[a-z]{0,50}?[0-9]{0,50}?[a-z0-90-9a-z]{1,50}?$/i", htmlentities(trim($_POST['newPassword'])))){
				$NEWpassword_error = "Password can only have letters and integers.";
				$newPasswordCheck = false;
				if($_POST['newPassword'] == ""){
					$NEWpassword_error = "Please enter your password.";
					$newPasswordCheck = false;
				}
			}
			if(!preg_match("/^[a-z]{0,50}?[0-9]{0,50}?[a-z0-90-9a-z]{1,50}?$/i", htmlentities(trim($_POST['confirmNewPassword'])))){
				$NEWpasswordCONFIRM_error = "Password can only have letters and integers.";
				$newPasswordCheck = false;
				if($_POST['confirmNewPassword'] == ""){
					$NEWpasswordCONFIRM_error = "Please enter your password.";
					$newPasswordCheck = false;
				}
			}
			if(htmlentities(trim($_POST['newPassword'])) != htmlentities(trim($_POST['confirmNewPassword']))){
				$NEWpasswordNotTheSame_error = "You have entered two different passwords, please try again.";
				$newPasswordCheck = false;
			}
			if($_POST && $newPasswordCheck && htmlentities(trim($_POST['newPassword']))){
				$connection = mysqli_connect("localhost","eafeventroot","93milbefwsdfjyrhnt3252skhsnsser93@","MILBER") or die ("could not connect " . mysqli_connect_error());
        		$newPass = htmlentities(trim($_POST['newPassword']));
				$newPass = mysqli_real_escape_string($connection, $newPass);
				$update = "UPDATE MILBER.MilberUserInfo SET password_SQL = '$newPass' WHERE id_SQL = '$userID'";
				$nameUp = mysqli_query($connection,$update) or die ("Could not query" . mysqli_error($connection)); 
				mysqli_close($connection);
			}
			//header('Location: userSettings.php');
		} else if($_REQUEST['Uppdate'] == "Save My Interests"){
			$dbLink = new DBLink();
			$events_user_likes = "DELETE FROM UserInterests WHERE user_id_SQL = '$userID'";
			$event_user_select_list = $dbLink->query($events_user_likes);
			$aCC = null;
			$bSC = null;
			$aCC = $_POST['CC'];
			$bSC = $_POST['SC'];
			$type_list = array();
			$topic_list = array();
			while($type_check = mysqli_fetch_array($event_user_select_list)){
				$type_list[] = $type_check['type_SQL'];
				$topic_list[] = $type_check['topic_SQL'];
			}
			if(empty($aCC)){
			} else {
				$num = count($aCC);
				for($i = 0; $i < $num; $i++){
					$temp1 = $aCC[$i];
					if(in_array($temp1, $type_list)){
					} else {
						$dbLink = new DBLink();
						$user_type_Insert = "INSERT INTO MILBER.UserInterests VALUES('','$userID','$temp1','')";
						$result1 = $dbLink->query($user_type_Insert);
					}
				}
			}
			if(empty($bSC)){
			} else {
				$num1 = count($bSC);
				for($j = 0; $j < $num1; $j++){
					$temp2 = $bSC[$j];
					if(in_array($temp2, $topic_list)){
					} else {
						$dbLink = new DBLink();
						$user_topic_Insert = "INSERT INTO MILBER.UserInterests VALUES('','$userID','','$temp2')";
						$result2 = $dbLink->query($user_topic_Insert);
					}
				}
			}
			header("Location: userSettings.php");
		}
	}
?>
			<link rel="stylesheet" type="text/css" href="css/imgareaselect-default.css" />
			<script type="text/javascript" src="scripts/jquery.min.js"></script>
			<script type="text/javascript" src="scripts/jquery.imgareaselect.pack.js"></script>
			<aside id="rightTools">
				<script>
					function toggleNavPanel(element, speed){
						$(element).toggle(speed);
					}
				</script>
				<p class="pageName">Settings</p>
					<div class="sections_btn_holder">
						<p onclick="toggleNavPanel('#Option-22', 300);">Change your information</p>
						<div id="Option-22" style="display:none">
							<div class="accountSettingsInfo">
								<form method="POST" enctype="multipart/form-data" name="changeUserInfoForm">
									<div class="accountSettingsPic">
<?= $image_path_settings; ?>
									</div>
									&nbsp; <span class="accountNamesDisplay">First name :</span>
									<div class="prosileInputSettings">
										<input type="text" name="nameChange" value="<?= $username; ?>" />
									</div>
									<div class="newInfoError"><?= $nameChange_error; ?></div>
									&nbsp; <span class="accountNamesDisplay">Middle name :</span>
									<div class="prosileInputSettings">
										<input type="text" name="middleNameChange" placeholder="Optional" value="<?= $usermname; ?>" />
									</div>
									<div class="newInfoError"><?= $middleNameChange_error; ?></div>
									&nbsp; <span class="accountNamesDisplay">Last name :</span>
									<div class="prosileInputSettings">
										<input type="text" name="lastNameChange" value="<?= $userlname; ?>" />
									</div>
									<div class="newInfoError"><?= $lastNameChange_error; ?></div>


									&nbsp; <span class="accountNamesDisplay">Born :</span>
									<div class="prosileInputSettings">
										<select name="day">
											<option>Day</option>
<?php
											for($i = 0; $i <= 31; $i++){
												if($i == $userBd){
?>
													<option value="<?=$i;?>" selected="selected"><?=$i;?></option>
<?php
												} else {
?>
													<option value="<?=$i;?>"><?=$i;?></option>
<?php
												}
											}
?>
										</select>
										<select name="month">
											<option>Month</option>
<?php
											for($j = 0; $j <= 12; $j++){
												if($j == $userBm){
													$monthNameStart = date('F', mktime(0,0,0, $userBm, 10));
?>
													<option value="<?=$j;?>" selected="selected"><?=$monthNameStart;?></option>
<?php
												} else {
													$monthNameStart = date('F', mktime(0,0,0, $j, 10));
?>
													<option value="<?=$j;?>"><?=$monthNameStart;?></option>
<?php
												}
											}
?>

										</select>
										<select name="year">
											<option>Year</option>
<?php
											for($k = 1900; $k <= date("Y"); $k++){
												if($k == $userBy){
?>
													<option value="<?=$k;?>" selected="selected"><?=$k;?></option>
<?php
												} else {
?>
													<option value="<?=$k;?>"><?=$k;?></option>
<?php
												}
											}
?>

										</select>
									</div>
									<br />
									&nbsp; <span class="accountNamesDisplay">Email :</span>
									<div class="prosileInputSettings">
										<input type="text" name="emailChange" value="<?= $useremail; ?>" />
									</div>
									<div class="newInfoError"><?= $emailChange_error; ?></div>
									&nbsp; <span class="accountNamesDisplay">Confirm email :</span>
									<div class="prosileInputSettings">
										<input type="text" name="emailChangeConfirm" value="<?= $useremail; ?>" />
									</div>
									<div class="newInfoError"><?= $emailChangeConfirm_error; ?></div>
									<div class="newInfoError"><?= $equal_email; ?></div>
									<br />
									<span class="accountNamesDisplay">
										<p>To save any changes, please confirm it with your password.</p>
									</span>
									<br />
									<br />
									<br />
									<br />
									&nbsp; <span class="accountNamesDisplay">Password :</span>
									<div class="prosileInputSettings">
										<input type="password" name="passwordSave">
									</div>


									<!--&nbsp; <span class="accountNamesDisplay">Password confirm :</span>
									<div class="prosileInputSettings">
										<input type="password" name="passwordSaveConfirm">
									</div>-->
									<script type="text/javascript">
										 function readURL(input) {
									            if (input.files && input.files[0]) {
									                var reader = new FileReader();

									                reader.onload = function (e) {
									                    $('#tmp')
									                        .attr('src', e.target.result)
									                        .width(350)
									                        .height(360);
									                };

									                reader.readAsDataURL(input.files[0]);
									            }
									        }
									</script>
									</script>
									<div class="prosileInputSettingsFILE" id="photoFileChnage">
										<input type="file" name="profilePicChange" onchange="readURL(this);" />
										<div class="PhotoError"><?= $photo_error; ?></div>
									</div>
									<div class="prosileInputSettings">
										<input type="submit" name="Uppdate" value="SubmitUserInfo" />
									</div>
									<input type="hidden" name="registered" value="<?=$becameAmemberOn;?>">
							</div>
							<p class="MemberOn">Became a mamber in<?= $becameAmemberOn; ?></p>
						</div>
					</div> 
					<div class="sections_btn_holder"> 
						<p onclick="toggleNavPanel('#Option-15', 300);">Password change</p>
						<div id="Option-15" style="display:none">
							<div class="passwordChange">
									<p>Please enter your old password first</p>
									<input type="password" name="oldPassword" placeholder="Current password"/><br />
									<div class="PasswordChnageFromOldToNew"><?= $wrongOldPassword_error; ?></div>
									<p>Now please enter your new password, it can only contain letters and integers.</p>
									<input type="password" name="newPassword" placeholder="New password"/><br />
									<div class="PasswordChnageFromOldToNew"><?= $NEWpassword_error; ?></div>
									<input type="password" name="confirmNewPassword" placeholder="Confirm New password"/><br />
									<div class="PasswordChnageFromOldToNew"><?= $NEWpasswordCONFIRM_error; ?></div>
									<div class="PasswordChnageFromOldToNew"><?= $NEWpasswordNotTheSame_error; ?></div>
									<input type="submit" name="Uppdate" value="Change Password" />
								</form>
							</div>
						</div>
					</div> 
					<div class="sections_btn_holder"> 
						<p onclick="toggleNavPanel('#Option-16', 300);">What would you like to see on your map?</p>
						<div id="Option-16" style="display:none" class="map-display-list">
							<p>By selecting any of this options, you are choosing what type and topic of events you wish to see on the map and event list.</p>
							<form method="POST" class="user-likes">
								<ul class="ulOne-type">
								Type
<?php
								$user_selected_list_type = array();
								$user_selected_list_topic = array();
								while($user_int = mysqli_fetch_array($event_user_select_list)){
									$user_selected_list_type[] = $user_int['type_SQL'];
									$user_selected_list_topic[] = $user_int['topic_SQL'];
								}
								while($row1 = mysqli_fetch_array($eventType_list)){
									if(in_array($row1['id_SQL'], $user_selected_list_type)){
										echo "<li><input type='checkbox' name='CC[]' value=".$row1["id_SQL"]." checked='checked'/>".$row1["type_name_SQL"]."</li>";
									} else {
										echo "<li><input type=\"checkbox\" name=\"CC[]\" value=".$row1["id_SQL"]." />".$row1["type_name_SQL"]."</li>";
									}
								}
?>
								</ul>
								<ul class="ulTwo-topic">
								Topic
<?php
								while($row2 = mysqli_fetch_array($eventTopic_list)){
									if(in_array($row2['id_SQL'], $user_selected_list_topic)){
										echo "<li><input type='checkbox' name='SC[]' value=".$row2['id_SQL']." checked='checked' />".$row2['topic_name_SQL']."</li>";
									} else {
										echo "<li><input type=\"checkbox\" name=\"SC[]\" value=".$row2['id_SQL']." />".$row2['topic_name_SQL']."</li>";
									}
								}
?>
								</ul>
								<input type="submit" name="Uppdate" value="Save My Interests" />
							</form>
						</div>
					</div> 
					<div class="sections_btn_holder"> 
						<p onclick="toggleNavPanel('#Option-19', 300);">Leafevent Account</p>
						<div id="Option-19" style="display:none">
							<a href="disable.php?e=<?=$userID;?>">
								<div class="disableAccount">Disable Account</div>
							</a>
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