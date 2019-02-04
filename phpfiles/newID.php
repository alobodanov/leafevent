<?php
		require_once "Library.php";
		//This page helps us to register a new user to the website. In here they will just input a bick info 
		//about them selfs and create a password.
		//
		$fname_error = "";
		$lname_error = "";
		$gender_error = "";
		$email1_error = "";
		$email2_error = "";
		$password1_error = "";
		$password2_error = "";
		$emailexist_error = "";
		$dayMonthYear_error = "";
		$equal_email = "";
		$equal_password = "";
		$email_exist = "";
		$gender = "";
		$e = ""; //e variable has a final email address with what the user will be loging in.
		$check = true;
		$date = date("Y-m-d"); //sysdate

		//Validation, to make sure that the information the user enters is valid and can be stored to the badabase.
		//
		if($_POST){
			//valiation for the user input
			//
			if(!preg_match("/^[a-z]{1,50}$/i", htmlentities(trim($_POST['fName'])))){
				$fname_error = "Please enter only letters.";
				$check = false;
				if(htmlentities(trim($_POST['fName'])) == ""){
					$fname_error = "Please enter your name.";
					$check = false;
				}
			}
			if(!preg_match("/^[a-z]{1,100}$/i", htmlentities(trim($_POST['lName'])))){
				$lname_error = "Please enter only letters.";
				$check = false;
				if(htmlentities(trim($_POST['lName'])) == ""){
					$lname_error = "Please enter your last name.";
					$check = false;
				}
			}
			if(!isset($_POST['gender'])){
				$gender_error = "Please choose your gender.";
				$check = false;
			}
			if(!preg_match("/^[a-z]{0,50}?[a-z0-9]{0,50}?[a-z';:.,]{0,50}?[@][a-zA-Z]{1,30}[.][a-zA-Z]{2,3}$/i", htmlentities(trim($_POST['email1'])))){
				$email1_error = "Please enter your email.";
				$check = false;
				if(htmlentities(trim($_POST['email1'])) == ""){
					$email1_error = "Please enter your email.";
					$check = false;
				}
			}
			if(!preg_match("/^[a-z]{0,50}?[0-9]{0,50}?[a-z0-90-9a-z]{1,50}?$/i", htmlentities(trim($_POST['pwd1'])))){
				$password1_error = "Password can only have letters and integers.";
				$check = false;
				if(htmlentities(trim($_POST['pwd1'])) == ""){
					$password1_error = "Please enter your password.";
					$check = false;
				}
			}
			if(htmlentities(trim($_POST['day'])) == "Day" || htmlentities(trim($_POST['month'])) == "Month" || htmlentities(trim($_POST['year'])) == "Year"){
				$dayMonthYear_error = "Please fill in Day Month and the Year.";
				$check = false;
			}
			$e = htmlentities(trim($_POST['email1']));
		}
		//check if we have the same email on the DB, if yes, we do not register/save the same email two times
		//
        $connection = new DBLink();
        $validUser = "SELECT email_SQL FROM MILBER.MilberUserInfo WHERE email_SQL = '$e'";
        $result = $connection->query($validUser);
		$existingUser = mysqli_num_rows($result);




		//Save new user to a DB
		//
		if($existingUser > 0){
			$email_exist = "Email you are trying to enter already exist.";
			$check = false;
		}
		if ($_POST && $check && $existingUser == 0){
			$connection = new DBLink();
			$fn = htmlentities(trim($_POST['fName']));
			$fn = mysqli_real_escape_string($connection, $fn);
			$ln = htmlentities(trim($_POST['lName']));
			$ln = mysqli_real_escape_string($connection, $ln);
			$g = htmlentities(trim($_POST['gender']));
			$g = mysqli_real_escape_string($connection, $g);
			$e = htmlentities(trim($_POST['email1']));
			$e = mysqli_real_escape_string($connection, $e);
			$p = htmlentities(trim($_POST['pwd1']));
			$p = mysqli_real_escape_string($connection, $p);
			$bd = htmlentities(trim($_POST['day']));
			$bm = htmlentities(trim($_POST['month']));
			$by = htmlentities(trim($_POST['year']));
			$rd = $date; //when did the user regirtered
			$connection = new DBLink();
			$data = "INSERT INTO MILBER.MilberUserInfo VALUES('','$fn','$ln','','$g','$e','$p','$bd','$bm','$by','off','n','$rd','','','','','')"; //save new users data to the database
			$status = $connection->query($data);
			//NOTE: should have an automatic email send to a new registered user.
			//
			/*if($status){
				$send = mail (
					"$e",
					"Milber account confirm",
					"Welcome to Milber! You have successfully created your account.<br /><br />",
					"From: Milber"
				);
			}*/
			header("Location: index.php");
			die();
		} else {
?>
	<?php $title_name = "New ID" ?>
<!DOCTYPE html>
<html lang="en">
  <head>
  	<meta charset="UTF-8" />
	<title><?php echo $title_name; ?></title>
	  <link rel="shortcut icon" href="../images/1616.png" type="image/jpg">
	  <link rel="stylesheet" type="text/css" href="../css/myfcss.css">
	  <link rel="stylesheet" href="../css/milberStyle.css" type="text/css" media="screen">
  </head>
  <body onload="birthdayNewId();">
	<div class="BodyTag">
  		<br />
		<img class="milberLogoONregistration" src="../images/logoPNG.png" alt="milberLogo" />
  		<div class="newIdWelcome">
			<p>New Milber ID</p>
  		</div>
		<br />
		<br />
  	<form name="newID" class="newIdBlock" method="POST">
			<input type="text" name="fName" id="fNameNewId" size="30" autofocus="autofocus" placeholder="First Name" value="<?php if(isset($_POST['fName'])) { echo $_POST['fName']; } ?>" /><div class="errorStyle2"><?php echo $fname_error . "<br />"; ?></div>
		  	<input type="text" name="lName" id="lNameNewId" size="30" placeholder="Last Name" value="<?php if(isset($_POST['lName'])) { echo $_POST['lName']; } ?>"/><div class="errorStyle2"><?php echo $lname_error . "<br />"; ?></div><br />
			<div class="genderLook">
		  		<input type="radio" name="gender" value="Male" <?= (isset($_POST['gender']) && $_POST['gender'] == 'Male')? "checked":""; ?> />&nbsp;Male
		  		<input type="radio" name="gender" value="Female" <?= (isset($_POST['gender']) && $_POST['gender'] == 'Female')? "checked":""; ?> />&nbsp;Female
		  	</div>
		  	<div class="errorStyle2"><?php echo $gender_error . "<br />"; ?></div>
		  	<br />
		  	<input type="text" name="email1" id="emailNewId" size="30" placeholder="Email" value="<?php if(isset($_POST['email1'])) { echo $_POST['email1']; } ?>" /><div class="errorStyle2"><?php echo $email1_error . "<br />"; ?></div><br />
		<!--<div class="newIdForm">Email Confirm</div>
		  	<input type="text" name="email2" id="emailNewId2" size="30" value="<?php if(isset($_POST['email2'])) { echo $_POST['email2']; } ?>" /><div class="errorStyle2"><?php echo $email2_error . "<br />"; ?></div><br /><div class="errorStyle2"><?= $equal_email; ?><br /><?= $email_exist; ?></div><br />-->
		  	<input type="password" name="pwd1" id="pwdNewId" placeholder="Password" size="30"/><div class="errorStyle2"><?php echo $password1_error . "<br />"; ?></div><br />
		<!--<div class="newIdForm">Confirm Password</div>
		  	<input type="password" name="pwdConfirm" id="pwdConfirmNewId" size="30"/><div required="required" class="errorStyle2"><?php echo $password2_error . "<br />"; ?></div><br /><div class="errorStyle2"><?= $equal_password; ?></div><br />	-->
		<div id="bdayDiv">
		  <div class="newIdForm">Birthday</div> 
		  	<div class="selection">
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
		  		</select>
		  		<div class="errorStyle2"><?php echo $dayMonthYear_error . "<br />"; ?></div>
		  	</div>
		  <br />
		</div>
		<br />
		<br />
		<div class="creatIdForm">
	 		<input type="submit" value="Create Account" id="submitNewId" />
		</div>
	</form>
	<div class="emailExist"><?= $emailexist_error; ?></div>
                <div class="CopyRight">
					<a href="#">Terms</a>&nbsp;<a href="developers.php">Developers</a>
					<script>
						var curentDate = new Date();
						var years = curentDate.getFullYear();
						document.write('<p>Leafevent © ' + years + '</p>');
					</script>
				</div>
</body>
</html>
<?php
}
?>