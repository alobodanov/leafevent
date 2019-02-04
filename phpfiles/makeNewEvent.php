<?php require "milbertoolbar.php"; //it has all the code for a "grean bar"?>

		<image src="../images/logoPNG.png" alt="milberLogo" size="" class="registrationLogo"/>
		<p class="milberRegistration">Milber Event Registration</p>
<?php
		if($_SESSION['userE_login'] == "" ){
			header('Location: logout.php');
			die();
		}

		$check = true;
		$eventName_error = null;
		$plannerName_error = null;
		$postCode_error = null;
		$dayMonthYear_error = null;
		$price_error = null;
		$numOfTicket_error = null;
		$eventDesc_error = null;
		$Phone_error = null;
		$password_error = null;

		if($_POST){

			if(!preg_match("/^[a-z]{1,50}$/i", htmlentities(trim($_POST['eventName'])))){
				$eventName_error = "Please enter only letters.";
				$check = false;
				if(trim($_POST['eventName']) == ""){
					$fname_error = "Please enter your name.";
					$check = false;
				}
			}
			if(!preg_match("/^[a-z]{1,50}$/i", htmlentities(trim($_POST['plannerName'])))){
				$plannerName_error = "Please enter only letters.";
				$check = false;
				if(trim($_POST['plannerName']) == ""){
					$fname_error = "Please enter your name.";
					$check = false;
				}
			}
			if(!preg_match("/^[a-z][0-9][a-z][ ]?[0-9][a-z][0-9][ ]{0,}$/i", htmlentities(trim($_POST['postCode'])))){
				$postCode_error = "Please entr a valid postal code A1A A1A or A1A1A1.";
				$check = false;
				if(trim($_POST['postCode']) == ""){
					$postCode_error = "Please enter post code of the event location.";
					$check = false;
				}
			}
			if(htmlentities(trim($_POST['day'])) == "Day" || htmlentities(trim($_POST['month'])) == "Month" || htmlentities(trim($_POST['year'])) == "Year"){
				$dayMonthYear_error = "Please fill in Day Month and the Year.";
				$check = false;
			}
            if(!preg_match("/^[a-z]{0,500}?[,.']?[a-z]{0,500}?[ ]?[0-9]{0,20}?[ ]?[a-z]{0,500}?$/i", htmlentities(trim($_POST['eventDesc'])))){     
				$eventDesc_error = "Please enter only latters and numbers.";
				$check = false;
				if(htmlentities(trim($_POST['eventDesc'])) == ""){
					$eventDesc_error = "Please fill in the description.";
					$check = false;
				}
			}
			if(!preg_match("/^[0-9]{0,3}?[.]{0,1}?[0-9]{0,2}?$/" , htmlentities(trim($_POST['price'])))){
				$price_error = "Please enter only numbers or nothing.";
				$check = false;
			}
			if(!preg_match("/^[0-9]{0,}?$/", htmlentities(trim($_POST['numOfTicket'])))){
				$numOfTicket_error = "Please enter only numbers or nothing.";
				$check = false;
			}
			if(!preg_match("/^[0-9]{5,}$/", htmlentities(trim($_POST['homePhone']))) || 
				!preg_match("/^[0-9]{5,}$/",htmlentities(trim($_POST['cellPhone']))) || 
				!preg_match("/^[0-9]{5,}$/",htmlentities(trim($_POST['workPhone'])))){
				$Phone_error = "Please enter a current phone number for home, cell or work number.";
				$check = false;
				if(trim($_POST['homePhone']) == "" || trim($_POST['cellPhone']) == "" || trim($_POST['workPhone']) == ""){
					$Phone_error = "Please enter or home or cell or work phone number.";
					$check = false;
				}
			}
			if(htmlentities(trim($_POST['password'])) != $userP){
				$password_error = "Please enter your milber password.";
				$check = false;
				if(htmlentities(trim($_POST['password'])) == ""){
					$password_error = "Please enter your password to confirm.";
					$check = false;
				}
			}
			if($_POST && $check){
				echo "all good";
			}

		}
?>
				<form method="POST" class="eventClass">
				<div class="fieldNamesOnNewEvent">Name of an event</div>
				<input type="text" name="eventName" value="<?php if(isset($_POST['eventName'])) { echo $_POST['eventName']; } ?>" /><div class="newEventErrorMsg"><?= $eventName_error; ?></div>
				<br />
				<div class="fieldNamesOnNewEvent">Type of an event</div>
				<select name="eventType">
				<option>Choice</option>
					<option>Board Meetings</option>
					<option>Party</option>
				</select><br />
				<div class="fieldNamesOnNewEvent">Who is making</div>
				<input type="text" name="plannerName" value="<?php if(isset($_POST['plannerName'])) { echo $_POST['plannerName']; } ?>" /><div class="newEventErrorMsg"><?= $plannerName_error; ?></div>
				<br />
				<div class="fieldNamesOnNewEvent">Address</div>
				<input type="text" name="address" value="<?php if(isset($_POST['address'])) { echo $_POST['address']; } ?>" /><div class="newEventErrorMsg"><?= $eventName_error; ?></div>
				<br />
				<div class="fieldNamesOnNewEvent">Post Code</div>
				<input type="text" name="postCode" value="<?php if(isset($_POST['postCode'])) { echo $_POST['postCode']; } ?>" /><div class="newEventErrorMsg"><?= $postCode_error; ?></div>
				<br />
				<select name="day">
		  			<option>Day</option>
		  			<script>
		  			//loop to print days from 1 to 31
		  			//
		  				for(var day = 1; day <= 31; day++){
		  					document.write("<option value='"+ day +"'>"+ day +"</option>");
		  				}
		  			</script>	
		  		</select>
		  		<select name="month">
		  				<option>Month</option><option value="1">January</option><option value="2">February</option><option value="3">March</option><option value="4">April</option><option value="5">May</option><option value="6">June</option><option value="7">July</option><option value="8">August</option><option value="9">September</option><option value="10">October</option><option value="11">November</option><option value="12">December</option> 
		  		</select>
		  		<select name="year">
		  			<option>Year</option>
		  			<script>
		  			//loop to print years from 1900 till the current year
		  			//
		  				var curentDate = new Date();
                    	var years = curentDate.getFullYear();
		  				for(var year = 1900; year <= years; year++){
		  					document.write("<option value='"+ year +"'>"+ year +"</option>");
		  				}
		  			</script>	
		  		</select><div class="newEventErrorMsg"><?= $dayMonthYear_error; ?></div>
		  		<textarea name="eventDesc" placeholder="Write a small description about your event"><?php if(isset($_POST['eventDesc'])) { echo $_POST['eventDesc']; } ?></textarea><div class="newEventErrorMsg"><?= $eventDesc_error; ?></div>
		  		<br />
		  		<div class="fieldNamesOnNewEvent">Price</div>
		  		<input type="text" name="price" placeholder="If any" value="<?php if(isset($_POST['price'])) { echo $_POST['price']; } ?>" /><div class="newEventErrorMsg"><?= $price_error; ?></div>
		  		<br />
		  		<div class="fieldNamesOnNewEvent">Total tickets</div>
		  		<input type="text" name="numOfTicket" placeholder="If any" value="<?php if(isset($_POST['numOfTicket'])) { echo $_POST['numOfTicket']; } ?>" /><div class="newEventErrorMsg"><?= $numOfTicket_error; ?></div>
		  		<br />
		  		<div class="fieldNamesOnNewEvent">Home phone</div>
		  		<input type="text" name="homePhone" value="<?php if(isset($_POST['homePhone'])) { echo $_POST['homePhone']; } ?>" />
		  		<br />
		  		<div class="fieldNamesOnNewEvent">Cell phone</div>
		  		<input type="text" name="cellPhone" value="<?php if(isset($_POST['cellPhone'])) { echo $_POST['cellPhone']; } ?>" />
		  		<br />
		  		<div class="fieldNamesOnNewEvent">Work phone</div>
		  		<input type="text" name="workPhone" value="<?php if(isset($_POST['workPhone'])) { echo $_POST['workPhone']; } ?>" /><div class="newEventErrorMsg"><?= $Phone_error; ?></div>
		  		<br />
		  		<br />
		  		<div class="fieldNamesOnNewEvent">Password for saving</div>
		  		<input type="password" name="password" /><div class="newEventErrorMsg"><?= $password_error; ?></div>
		  		<br />
		  		<input type="submit" name="Registration" value="New Event" />
			</form>
<?php require "milberMenuEnd.php"; ?>