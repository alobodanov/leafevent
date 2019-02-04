<?php ob_start(); $title_name = "leafevent";?>
<?php require_once "milbertoolbar.php";?>
<?php require_once "Library.php";?>
<?php
	if($_SESSION['userE_login'] == "" ){
   			header('Location: logout.php');
   			die();
   	}

   	$check = true;
   	$logInFlag = false;
	$eventName_error = null;
	$eventAddress_error = null;
	$plannerName_error = null;

	$price_error = null;
	$numOfTicket_error = null;
	$eventDesc_error = null;
	$password_error = null;
	$homePhone_error = null;
	$cellPhone_error = null;
	$workPhone_error = null;
	$EndDayIsGreater_error = null;
	$rand_dir_name = null;
	$fileName = null;
	$success = null;

	$eventSTARTS_error  = null;
	$eventENDS_error  = null;
	$eventType_error = null;	
	$eventTopic_error  = null;		
	$eventSubTopic_error  = null;

	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		if(htmlentities(trim($_POST['eventName'])) == ""){
			$eventName_error = "Please enter an event name.";
			$check = false;
			if(!preg_match("/^[a-z0-9\,\<\>\.\%\;\(\)\'\/s\!\?\:\"]*$/i", htmlentities(trim($_POST['eventName'])))){
				$eventName_error = "Name can have only letters.";
				$check = false;
			}
		}
		if(!preg_match("/^[a-z0-9\,\.\:\(\)\'\ \-\"]*$/i", htmlentities(trim($_POST['address'])))){ 
			$eventAddress_error = "Please enter only letters and numbers.";
			$check = false;
			if(trim($_POST['address']) == ""){
				$eventAddress_error = "Please enter address.";
				$check = false;
			}
		}

		if(htmlentities(trim($_POST['plannerName'])) == ""){
			$plannerName_error = "Please enter your name.";
			$check = false;
			if(!preg_match("/^[a-z\ \]*$/i", htmlentities(trim($_POST['plannerName'])))){
				$plannerName_error = "Please enter only letters.";
				$check = false;
			}
		}
		//check start datetime 
		if(verifyDateTime($_POST['eventSTARTS'])==false ){
			$eventSTARTS_error = "Please input a valid event starts time.";
			$check = false;
		}
		//check ENDS datetime 
		if(verifyDateTime($_POST['eventENDS'])==false ){
			$eventENDS_error = "Please input a valid event ends time.";
			$check = false;
		}
		if($_POST['eventENDS']<$_POST['eventSTARTS']){
			$eventENDS_error = "event starts time must be before ends time.";
			$check = false;
		}
		if(htmlentities(trim($_POST['id_event_type'])) == "-1"){
			$eventType_error = "Please choose event type.";
			$check = false;
		}
		if(htmlentities(trim($_POST['id_event_topic'])) == "-1"){
			$eventTopic_error = "Please choose event topic.";
			$check = false;
		}

		if(!isset($_POST['eventPublicPrivate'])){
			$eventPublicPrivate_error = "Is it public or private event?";
			$check = false;
		}
        if(htmlentities(trim($_POST['eventDesc'])) == ""){     
			$eventDesc_error = "Please fill in a description.";
			$check = false;
			if(!preg_match("/^[a-z0-9\,\>\.\%\;\(\)\'\ \/n\/t!\?\:\"]*$/i", htmlentities(trim($_POST['eventDesc'])))){
				$eventDesc_error = "Please enter only latters and numbers.";
				$check = false;
			}
		}
		if(!preg_match("/^[0-9]{0,3}?[.]{0,1}?[0-9]{0,2}?$/" , htmlentities(trim($_POST['price'])))){
			$price_error = "Please enter only numbers or nothing.";
			$check = false;
		}
		if(htmlentities(trim($_POST['numOfTicket'])) == ""){
			$numOfTicket_error = "Please enter a number of tickets.";
			$check = false;
			if(!preg_match("/^[0-9]*$/", htmlentities(trim($_POST['numOfTicket'])))){
				$numOfTicket_error = "Please enter only numbers.";
				$check = false;
			}
		}
		
		if(trim($_POST['homePhone']) == "" && trim($_POST['cellPhone']) == "" && trim($_POST['workPhone']) == ""){
			$workPhone_error = "Please enter at list one of home, cell or work number.";
			$check = false;
		}
		else{
			if(trim($_POST['homePhone']) != "" && !preg_match("/^[0-9\-]{5,}$/", htmlentities(trim($_POST['homePhone'])))){
				$Phone_error = "Please enter a current phone number for home.";
				$check = false;		
			}
			if(trim($_POST['cellPhone']) != "" && !preg_match("/^[0-9\-]{5,}$/", htmlentities(trim($_POST['cellPhone'])))){
				$Phone_error = "Please enter a current phone number for cell.";
				$check = false;		
			}
			if(trim($_POST['workPhone']) != "" && !preg_match("/^[0-9\-]{5,}$/", htmlentities(trim($_POST['workPhone'])))){
				$Phone_error = "Please enter a current phone number for work.";
				$check = false;		
			}
		}

		if(htmlentities(trim($_POST['password'])) == ""){
			$password_error = "Please enter your password to confirm.";
			$check = false;
		}
		if(htmlentities(trim($_POST['password']))){
			$ps = htmlentities(trim($_POST['password']));
			$dblink = new DBLink();
			$select = "SELECT password_salt_SQL, password_SQL FROM MILBER.MilberUserInfo WHERE id_SQL = '$userID'";
			$checkQuery = $dblink->query($select);
			if(mysqli_num_rows($checkQuery) == 1){
				while($pass = mysqli_fetch_assoc($checkQuery)){
					$saltFromDatabase = $pass['password_salt_SQL'];
                    $hashFromDatabase = $pass['password_SQL'];
                    if(testPassword($ps, $saltFromDatabase, $hashFromDatabase)){
                      $logInFlag = true;
                    }else{
                      $logInFlag = false;
                      $password_error = "You have entered a wrong password";
                    }
				}
			}
		}
	}
		if($_POST && $check && $logInFlag){
			//make a new connection for saving users event
			//

			$dbLink = new DBLink();
			$connection = $dbLink->conn();
			$eventName_htmlcheck = htmlentities(trim($_POST['eventName']));
			$eventName_htmlcheck = mysqli_real_escape_string($connection, $eventName_htmlcheck);
			
			$eventType_htmlcheck = htmlentities(trim($_POST['id_event_type']));
			$eventType_htmlcheck = mysqli_real_escape_string($connection, $eventType_htmlcheck);

			$eventTopic_htmlcheck = htmlentities(trim($_POST['id_event_topic']));
			$eventTopic_htmlcheck = mysqli_real_escape_string($connection, $eventTopic_htmlcheck);
			
			/*$eventSubTopic_htmlcheck = htmlentities(trim($_POST['id_event_subtopic']));
			$eventSubTopic_htmlcheck = mysqli_real_escape_string($connection, $eventSubTopic_htmlcheck);*/
			
			$plannerName_htmlcheck = htmlentities(trim($_POST['plannerName']));
			$plannerName_htmlcheck = mysqli_real_escape_string($connection, $plannerName_htmlcheck);

			$address_htmlcheck = htmlentities(trim($_POST['addressForm']));
			$address_htmlcheck = mysqli_real_escape_string($connection, $address_htmlcheck);

			$x_num_check = htmlentities(trim($_POST['X']));
			$x_num_check = mysqli_real_escape_string($connection, $x_num_check);
			$y_num_check = htmlentities(trim($_POST['Y']));
			$y_num_check = mysqli_real_escape_string($connection, $y_num_check);

			$eventSTARTS_htmlcheck = htmlentities(trim($_POST['eventSTARTS']));
			$eventSTARTS_htmlcheck = mysqli_real_escape_string($connection, $eventSTARTS_htmlcheck);

			$eventENDS_htmlcheck = htmlentities(trim($_POST['eventENDS']));
			$eventENDS_htmlcheck = mysqli_real_escape_string($connection, $eventENDS_htmlcheck);
			
			$eventPublicPrivate_htmlcheck = htmlentities(trim($_POST['eventPublicPrivate']));
			$eventPublicPrivate_htmlcheck = mysqli_real_escape_string($connection, $eventPublicPrivate_htmlcheck);

			$eventDesc_htmlcheck = htmlentities(trim($_POST['eventDesc']));
			$eventDesc_htmlcheck = mysqli_real_escape_string($connection, $eventDesc_htmlcheck);

			$price_htmlcheck = htmlentities(trim($_POST['price']));
			$price_htmlcheck = mysqli_real_escape_string($connection, $price_htmlcheck);

			$numOfTicket_htmlcheck = htmlentities(trim($_POST['numOfTicket']));
			$numOfTicket_htmlcheck = mysqli_real_escape_string($connection, $numOfTicket_htmlcheck);

			$homePhone_htmlcheck = htmlentities(trim($_POST['homePhone']));
			$homePhone_htmlcheck = mysqli_real_escape_string($connection, $homePhone_htmlcheck);

			$cellPhone_htmlcheck = htmlentities(trim($_POST['cellPhone']));
			$cellPhone_htmlcheck = mysqli_real_escape_string($connection, $cellPhone_htmlcheck);

			$workPhone_htmlcheck = htmlentities(trim($_POST['workPhone']));
			$workPhone_htmlcheck = mysqli_real_escape_string($connection, $workPhone_htmlcheck);
		

			$file = null;
			$fileName2 = null;
			if(isset($_FILES['IMGE'])){
/*
						$charSet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
						$rand_dir_name = substr(str_shuffle($charSet), 0, 15);
						$fileName = substr(str_shuffle($charSet), 0, 15);

						$fileTmpLoc = $_FILES["IMGE"]["tmp_name"];
						mkdir("../milberUserPhotos/eventPhotos/".$rand_dir_name, 0777, true);
						$pathAndName = "../milberUserPhotos/eventPhotos/$rand_dir_name/".$fileName;
						$moveResult = move_uploaded_file($fileTmpLoc, $pathAndName);
				}*/
				$fileName = $_FILES['IMGE']['name'];
				$fileTmpLoc = $_FILES["IMGE"]["tmp_name"];
				$fileType = $_FILES['IMGE']['type'];
				$fileSize['IMGE']['size'];
				$fileErrorMsg = $_FILES['IMGE']['error'];
				$kaboom = explode(".",$fileName);
				$fileExt = $kaboom[1];
				$error2 = null;
				if(!$fileTmpLoc){
					$error = "<br />ERROR: chose file first before uploading a file";
					echo $error2;
					exit();
				} else if($fileSize > 6242880){
					$error2 = "<br />ERROR: Your file was larger then 15 megabytes in size";
					echo $error2;
					unlink($fileTmpLoc);
					exit();
				} else if(!preg_match("/\.(gif|jpg|png)$/i", $fileName)){
					$error2 = "<br />ERROR: Your image was not .gif, .jpg or .png";
					echo $error2;
					unlink($fileTmpLoc);
					exit();
				} else if($fileTmpLoc == 1){
					$error2 = "<br />ERROR: An error accured while processign the file. Please try again.";
					echo $error2;
					exit();
				}
				$charSet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
				$fileName2 = substr(str_shuffle($charSet), 0, 15);
				$file = substr(str_shuffle($charSet), 0, 15);
				mkdir("../milberUserPhotos/eventPhotos/".$file, 0777, true);
				$pathAndName = "../milberUserPhotos/eventPhotos/$file/".$fileName2;
				$moveResult = move_uploaded_file($fileTmpLoc, $pathAndName);

				if($moveResult != true){
					$error2 = "<br />ERROR: File not uploaded. Please try again.";
					unlink($fileTmpLoc);
					exit();
				}
				unlink($fileTmpLoc);

				include_once("ak_php_img_lib_1.0.php");
				$target_file = "../milberUserPhotos/eventPhotos/$file/$fileName2";
				$resized_file = "../milberUserPhotos/eventPhotos/$file/resized_$fileName2";
				$wmax = 330; //330
				$hmax = 330; //195
				ak_event_img_resize($target_file,$resized_file,$wmax,$hmax,$fileExt);
			}
			$useremail = mysqli_real_escape_string($dbLink->conn(), htmlentities(trim($useremail)));
			$userLocationTime = htmlentities(trim($_GET['usertime']));

			$eventName_htmlcheck = ucfirst($eventName_htmlcheck);

			$q = "INSERT INTO MILBER.milberevents VALUES('','$eventName_htmlcheck', '$eventType_htmlcheck', '$eventTopic_htmlcheck', 
				'$eventSubTopic_htmlcheck','$plannerName_htmlcheck', '$userID', '$address_htmlcheck', '$x_num_check', '$y_num_check', 
				'$eventSTARTS_htmlcheck', '$eventENDS_htmlcheck', '$eventPublicPrivate_htmlcheck','$price_htmlcheck', '$numOfTicket_htmlcheck',0, 
				'$homePhone_htmlcheck', '$cellPhone_htmlcheck', '$workPhone_htmlcheck','$file','$fileName2', '$eventDesc_htmlcheck','$userLocationTime','W','W')";

			$result = $dbLink->query($q);

				$Name = "Leafevent"; //senders name 
				$email = "newevent@leafevent.com"; //senders e-mail adress 
				$recipient = $useremail; //recipient 
				$mail_body = "Congratulations!\n You have created a new event. It will be confirmed within 24 hours."; //mail body 
				$subject = "Leafevent new event"; //subject 
				$header = "From: ". $Name . " <" . $email . ">\r\n"; //optional headerfields 
				mail($recipient, $subject, $mail_body, $header);
				$success = "You have successfully created a new event, please check your email.";
			    header('Location: userFriendInfo.php?u='.$userID);
		}
?>
		  <link rel="stylesheet" href="../css/ui_1_11_4_themes_smoothness_jquery-ui.css">
		  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
		  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
			<p class="newEventPage">Create New Event<span><?=$success;?></span></p>
			<form id="geoform" method="POST" name="myForm" class="eventClass" enctype="multipart/form-data">
				<span class="eventClassHeadings">1 Event Information</span>
				<hr />
		        <br />
				<div class="fieldNamesOnNewEvent">Event Name</div>
				<input type="text" name="eventName" id="newEventName" placeholder="Names play a big difference" value="<?php if(isset($_POST['eventName'])) { echo $_POST['eventName']; } ?>" /><div class="newEventErrorMsg"><?= $eventName_error; ?></div>
				<br />
				<!--<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>-->
				<script src="https://maps.google.com/maps?file=api&amp;v=2&amp;key=AIzaSyDx62BgXvaZaht8z0AeHNvM71jHuOhwico" type="text/javascript"></script> 
				<div class="fieldNamesOnNewEvent">Address</div>
				<input id="address" type="text" name="addressForm" value="<?php if(isset($_POST['address'])) { echo $_POST['address']; } ?>" /><div class="newEventErrorMsg"><?= $eventAddress_error; ?></div>
				<br />
				<div class="form-group">
					<label for="eventSTARTS" class="col-sm-2 control-label eventStart">STARTS</label>
					<div class="col-sm-2">
						<input id="eventSTARTS" type="text" class="form-control" name="eventSTARTS" value="<?php if(isset($_POST['eventSTARTS'])) { echo $_POST['eventSTARTS']; } ?>" />
					</div>
					<div class="newEventErrorMsg"><?= $eventSTARTS_error; ?></div>	
					<br />
					<label for="eventENDS" class="col-sm-1 control-label">ENDS</label>
					<div class="col-sm-2"> 
						<input id="eventENDS" type="text"  class="form-control" name="eventENDS" value="<?php if(isset($_POST['eventENDS'])) { echo $_POST['eventENDS']; } ?>" />
					</div>
					<div class="newEventErrorMsg"><?= $eventENDS_error; ?></div>
				</div>
				<br />
				<div class="form-group">
					<label for="eventType" class="col-sm-2 control-label">Event Type</label>
					<div class="col-sm-5">
						<div id='event-type-ddl'></div>
					</div>	 
				</div>
				<br />
				<div class="form-group topic-display">
					<label for="eventTopic" class="col-sm-2 control-label">Event Topic</label>
					<div class="col-sm-3">
						<div id='event-topic-ddl'></div>
					</div> 
					<div class="newEventErrorMsg"><?= $eventType_error; ?></div>
					<div class="newEventErrorMsg"><?= $eventTopic_error; ?></div>
				</div>
				<br />
		  		<div class="fieldNamesOnNewEvent">Privacy</div><br />
				<input type="radio" name="eventPublicPrivate" value="public" checked="checked"/> Public Event
				<input type="radio" name="eventPublicPrivate" value="private" /> Private Event<br />
		  		<br />
		  		<script type="text/javascript">
				 function readURL(input) {
			            if (input.files && input.files[0]) {
			                var reader = new FileReader();
			                reader.onload = function (e) {
			                    $('#tmp')
			                        .attr('src', e.target.result)
			                        .width(600)
			                        .height(300);
			                };
			                reader.readAsDataURL(input.files[0]);
			            }
			        }
				</script>
				<div class="newEventPicBOX"><img class="newEventPicture" id="tmp" src="../Images/event-temp-pic.png" alt="img" /><div>Event picture can attract more people <input type="file" name="IMGE" onchange="readURL(this);" /></div></div>
				<textarea name="eventDesc" placeholder="Write a small description about your event" onkeyup="textAreaEventDesk(this)"><?php if(isset($_POST['eventDesc'])) { echo $_POST['eventDesc']; } ?></textarea>
				<div class="newEventErrorDesc"><?= $eventDesc_error; ?></div>
				<br />
				<br />
		  		<span class="eventClassHeadings">2 Ticket Details</span>
		  		<hr />
			    <br />
		  		<div class="fieldNamesOnNewEvent">Price</div>
		  		<input type="text" name="price" id="newEventPrice" placeholder="Optional" value="<?php if(isset($_POST['price'])) { echo $_POST['price']; } ?>" /><div class="newEventErrorMsg"><?= $price_error; ?></div>
		  		<br />
		  		<div class="fieldNamesOnNewEvent">Total Tickets</div>
		  		<input type="text" name="numOfTicket" id="newEventTicket" oplaceholder="If any" value="<?php if(isset($_POST['numOfTicket'])) { echo $_POST['numOfTicket']; } ?>" />
		  		<div class="newEventErrorMsg"><?= $numOfTicket_error; ?></div>
		  		<br />
		  		3 Organizer Details
		  		<hr />
			    <br />
				<div class="fieldNamesOnNewEvent">Planner's Name</div>
				<input type="text" name="plannerName" id="eventPlanner" value="<?php if(isset($_POST['plannerName'])) { echo $_POST['plannerName']; } ?>" />
				<div class="newEventErrorMsg"><?= $plannerName_error; ?></div>
				<br />
		  		<div class="form-group">
					<label for="homePhone" class="col-sm-2 control-label">Home phone</label>
					 <div class="col-sm-6">
						<input type="text" class="form-control" name="homePhone" value="<?php if(isset($_POST['homePhone'])) { echo $_POST['homePhone']; } ?>" />
					</div>
					<div class="newEventErrorMsg"><?= $homePhone_error; ?></div>
				</div>
				<br />
				<div class="form-group">
					<label for="cellPhone" class="col-sm-2 control-label">Cell phone</label>
					<div class="col-sm-6">
						<input type="text" class="form-control" name="cellPhone" value="<?php if(isset($_POST['cellPhone'])) { echo $_POST['cellPhone']; } ?>" />
					</div>
					<div class="newEventErrorMsg"><?= $cellPhone_error; ?></div>
				</div>
				<br />
				<div class="form-group">
					<label for="workPhone" class="col-sm-2 control-label">Work phone</label>
					<div class="col-sm-6">
						<input type="text" class="form-control" name="workPhone" value="<?php if(isset($_POST['workPhone'])) { echo $_POST['workPhone']; } ?>" />
					</div>
					<div class="newEventErrorMsg"><?= $workPhone_error; ?></div>
				</div>
				<br />
		  		<br />
		  		<span class="eventClassHeadings">4 Save Your Event</span>
		  		<hr />
			    <br />
		  		<div class="fieldNamesOnNewEvent">Password for saving</div>
		  		<input type="password" name="password" /><div class="newEventErrorMsg"><?= $password_error; ?></div>
		  		<br />
		  		<!--<input type="submit" name="Registration" value="New Event" onclick="codeAddress()" />-->
		  		<input type="button" value="New Event" onclick="showLocation(); return false"/>
		  		<input type="hidden" name="X" size="20"></p>
                <input type="hidden" name="Y" size="20"></p>
				<div class="CopyRight">
					<a href="termsL.php">Terms</a>&nbsp;<a href="developers.php">Developers</a>
					<script>
						var curentDate = new Date();
						var years = curentDate.getFullYear();
						document.write('<p>Leafevent © ' + years + '</p>');
					</script>
				</div>
			</form>
		</div>
	</div>
	<script type="text/javascript" src="../javascript/jquery-ui-timepicker-addon.js"></script>
	<link rel="stylesheet" href="../css/jquery-ui-timepicker-addon.css">
	<script type="text/javascript">
		$(document).ready(function() {
			GetDDL("#event-type-ddl",null,'1',null);
			GetDDL("#event-topic-ddl",null,'2',null);

			$( "#eventSTARTS" ).datetimepicker({
				showSecond: true,
				dateFormat: "yy-mm-dd", 
				timeFormat: "HH:mm:ss"
			});
			$(eventENDS).datetimepicker({
				showSecond: true,
				dateFormat: "yy-mm-dd", 
				timeFormat: "HH:mm:ss"
			});
			
		});
	</script>
	</body>
</html>