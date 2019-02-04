<?php $title_name = "New Member" ?>
<?php
	require_once "Library.php";
	session_start();
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
	$dateReg = date("Y-m-d"); //sysdate
	$event_result = null;
    $filter_count = null;
    $eventResult = null;
    $name_search = null;

	$rowsperpage = null;

	$typelink = new DBLink();
	$types = "SELECT * FROM v_eventtype ORDER BY id_SQL";
	$typeResult = $typelink->query($types);

	$select_events = new DBLink();
	$events = "SELECT * FROM v_event";
	$event_result_non_filtered = $select_events->query($events);

	if($_POST){
		if($_REQUEST['Login-find'] == "Log in"){
            if(isset($_POST['Login-find'])){
                //user email check
                //
                if(!preg_match("/^[a-z]{0,50}?[a-z0-9]{0,50}?[a-z';:.,]{0,50}?[@][a-zA-Z]{1,30}[.][a-zA-Z]{1,20}$/i", htmlentities(trim($_POST['userEmail_login'])))){
                    $email_error = "Please enter a valid email.";
                    $check = false;
                    if(htmlentities(trim($_POST['userEmail_login'])) == ""){
                        $email_error = "Please enter your email.";
                        $check = false;
                    }
                }
                //user password check
                //
                if(!preg_match("/^.*(?=.{8,100})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/i", htmlentities(trim($_POST['password_login'])))){
                    $passord_error = "Only letters and numbers.";
                    $check = false;
                    if(htmlentities(trim($_POST['password_login'])) == ""){
                        $passord_error = "Please enter your password.";
                        $check = false;
                    }
                }
                $logInFlag = false;
                //if the POST is set and check is still true, we want to connect to the DB and retrive all the info about the user.
                //if numOfRows is 1, means that the user is real and we want to collect all important info.
                //one of the info that we have collected is if the user is still using their account or if it's deleted.
                if($_POST && $check){
                    $em = htmlentities(trim($_POST['userEmail_login']));
                    $ps = htmlentities(trim($_POST['password_login']));
                    /*$online = new DBLink();
                    $onoff = "UPDATE MILBER.MilberUserInfo SET online_SQL = 'y' WHERE id_SQL = '$userId'";
                    $onOffUpdate = $online->query($onoff);*/
                    $link = mysqli_connect("localhost","eafeventroot","93milbefwsdfjyrhnt3252skhsnsser93@","MILBER") or die ("could not connect " . mysqli_connect_error());
                    $em = mysqli_real_escape_string($link, $em);
                    $ps = mysqli_real_escape_string($link, $ps);
                    $exist = "SELECT * FROM MILBER.MilberUserInfo WHERE email_SQL = '$em' LIMIT 1";
                    $result = mysqli_query($link,$exist) or die("Could not query ". mysqli_error($link));
                    $numOfRows = mysqli_num_rows($result);
                    while($userRow = mysqli_fetch_array($result)){
                        $saltFromDatabase = $userRow['password_salt_SQL'];
                        $hashFromDatabase = $userRow['password_SQL'];
                        if(testPassword($ps, $saltFromDatabase, $hashFromDatabase)){
                          $logInFlag = true;
                        }else{
                          $logInFlag = false;
                        }
                    }
                    if($numOfRows == 1 && $logInFlag == true){
                        while($userRow = mysqli_fetch_array($result)){
                            $userId = $userRow['id_SQL'];
                            $username = $userRow['fname_SQL'];
                            $userlname = $userRow['lname_SQL'];
                            /*$usergender = $userRow['gender_SQL'];
                            $userBd = $userRow['birthdayday_SQL'];
                            $userBm = $userRow['birthdaymonth_SQL'];
                            $userBy = $userRow['birthdayyear_SQL'];*/
                            $disabledAccount = $userRow['disable_SQL']; // a variable that has a info if the user is active or deleted 
                            $userT = $userRow['user_type_SQL'];
                        }
                        if($disabledAccount != 'y'){
                            if(isset($_SERVER['HTTPS'])){ 
                                $online = new DBLink();
                                $onoff = "UPDATE MILBER.MilberUserInfo SET online_SQL = 'y' WHERE id_SQL = '$userId'";
                                $onOffUpdate = $online->query($onoff);
                                    $_SESSION['user_id'] = $userId;
                                    $_SESSION['userE_login'] = $em;
                                    $_SESSION['userName_login'] = $username;
                                    $_SESSION['userlName_login'] = $userlname;
                                    if($userT == "A"){
                                        header("Location: AdminPHP/weDoThisThingRight.php");
                                        exit();
                                        die();
                                    } else {
                                        $_SESSION['user_id'] = $userId;
                                        $_SESSION['userE_login'] = $em;
                                        setcookie("Leafevent_user","$em", time() + (60 * 1));
                                        header("Location: home.php");
                                        mysqli_close($link);
                                    }
                                    exit();
                                    die();
                          } else {
                                //this connection will set a flag in DB to a On on online_SQL colomn witch means, the user is curently online.
                                //
                                $online = mysqli_connect("localhost","eafeventroot","93milbefwsdfjyrhnt3252skhsnsser93@","MILBER") or die ("could not connect " . mysqli_connect_error());
                                $onOff = "UPDATE MILBER.MilberUserInfo SET online_SQL = 'y' WHERE id_SQL = '$userId'";
                                $onOffUpdate = mysqli_query($online, $onOff) or die("could not set online" . mysqli_error($online)); 
                                mysqli_close($online);
                                $_SESSION['user_id'] = $userId;
                                $_SESSION['userE_login'] = $em;
                                $_SESSION['userName_login'] = $username;
                                $_SESSION['userlName_login'] = $userlname;
                                setcookie("Leafevent_user","$em", time() + (60 * 10));
                                header("Location: https://leafevent.com/phpfiles/home.php");
                                mysqli_close($link);
                                exit(); 
                                die();
                            }
                        } else {
                            header('Location: https://leafevent.com/phpfiles/activate.php');
                            mysqli_close($link);
                            exit();
                            die();
                        }
                        //if the numOfRows is 0, then it will print this message NOTE: will be making a page for that
                        //
                    } else {
                        mysqli_close($link);
                        $user_account_not_exist = "<div class=\"maybe-new-user\" onclick=\"toggleNavPanel('#passForgot', 300);\">Invalid user information, <a href='forgotPassword.php'>forgot your password?</a></div>";
                        //header('Location: invalidInfo.php');
                    }
                }
            } else if(isset($_POST['Login-find-user'])) {
                die();
                $email_sent_to = htmlentities(trim($_POST['forgoton-email']));
                $email_for_send = mysqli_connect("localhost","eafeventroot","93milbefwsdfjyrhnt3252skhsnsser93@","MILBER") or die ("could not connect " . mysqli_connect_error());
                $email_sent_to = mysqli_real_escape_string($email_for_send, $email_sent_to);
                $send_email_info = "SELECT fname_SQL, password_SQL FROM v_user_info WHERE email_SQL = '$email_sent_to'";
                $email_set_quesry = mysqli_query($email_for_send, $send_email_info) or die ("could not query" . mysqli_error($email_for_send));
                $email_to_count = mysqli_num_rows($email_set_quesry);
                if($email_to_count == 1){//
                    $email_forgot_name = null;
                    $email_forgot_pass = null;
                    while($ufe = mysqli_fetch_array($email_set_quesry)){
                        $email_forgot_name = $ufe['fname_SQL'];
                        $email_forgot_pass = $ufe['password_SQL'];
                    }
                }//
            }

        } else if($_REQUEST['Login-find'] == "Find"){
            $type_search = null;
            $name_search = null;
            $type_search = htmlentities(trim($_POST['event_type']));
            if($type_search == "Search by type"){
                $type_search = null;
            }
            $name_search = htmlentities(trim($_POST['event_search']));
            if($type_search && $name_search){
                $search_for_name = mysqli_connect("localhost","eafeventroot","93milbefwsdfjyrhnt3252skhsnsser93@","MILBER") or die ("could not connect " . mysqli_connect_error());
                $name_search = mysqli_real_escape_string($search_for_name, $name_search);
                $type_search = mysqli_real_escape_string($search_for_name, $type_search);
                $eventS = "SELECT * FROM v_event WHERE event_Name_SQL LIKE '{$name_search}%' OR event_type_id_SQL = '$type_search' OR who_is_making_event_SQL LIKE '{$name_search}%' LIMIT 15";
                $eventResult = mysqli_query($search_for_name, $eventS) or die ("could not query" . mysqli_error($search_for_name));
                $filter_count = mysqli_num_rows($eventResult);
            } else if($type_search && $name_search == ""){
                $search_for_name = mysqli_connect("localhost","eafeventroot","93milbefwsdfjyrhnt3252skhsnsser93@","MILBER") or die ("could not connect " . mysqli_connect_error());
                $name_search = mysqli_real_escape_string($search_for_name, $name_search);
                $type_search = mysqli_real_escape_string($search_for_name, $type_search);
                $eventS = "SELECT * FROM v_event WHERE event_type_id_SQL = '$type_search' LIMIT 15";
                $eventResult = mysqli_query($search_for_name, $eventS) or die ("could not query" . mysqli_error($search_for_name));
                $filter_count = mysqli_num_rows($eventResult);
            } else if($type_search == "" && $name_search){
                $search_for_name = mysqli_connect("localhost","eafeventroot","93milbefwsdfjyrhnt3252skhsnsser93@","MILBER") or die ("could not connect " . mysqli_connect_error());
                $name_search = mysqli_real_escape_string($search_for_name, $name_search);
                $type_search = mysqli_real_escape_string($search_for_name, $type_search);
                $eventS = "SELECT * FROM v_event WHERE (event_Name_SQL LIKE '{$name_search}%' OR who_is_making_event_SQL LIKE '{$name_search}%') LIMIT 15";
                $eventResult = mysqli_query($search_for_name, $eventS) or die ("could not query" . mysqli_error($search_for_name));
                $filter_count = mysqli_num_rows($eventResult);
            }
        } else if($_REQUEST['Login-find'] == "Create Account"){
			//valiation for the user input
			//
			if(!preg_match("/^[a-z]{1,50}$/i", htmlentities(trim($_POST['fName'])))){
				$fname_error = "Enter only letters.";
				$check = false;
				if(htmlentities(trim($_POST['fName'])) == ""){
					$fname_error = "Enter your name.";
					$check = false;
				}
			}
			if(!preg_match("/^[a-z]{1,100}$/i", htmlentities(trim($_POST['lName'])))){
				$lname_error = "Enter only letters.";
				$check = false;
				if(htmlentities(trim($_POST['lName'])) == ""){
					$lname_error = "Enter your last name.";
					$check = false;
				}
			}
			if(!isset($_POST['gender'])){
				$gender_error = "Choose your gender.";
				$check = false;
			}
			if(!preg_match("/^[a-z]{0,50}?[a-z0-9]{0,50}?[a-z';:.,]{0,50}?[@][a-zA-Z]{1,30}[.][a-zA-Z]{2,3}$/i", htmlentities(trim($_POST['email1'])))){
				$email1_error = "Enter your email.";
				$check = false;
				if(htmlentities(trim($_POST['email1'])) == ""){
					$email1_error = "Enter your email.";
					$check = false;
				}
			}
			if(!preg_match("/^.*(?=.{8,100})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/i", htmlentities(trim($_POST['pwd1'])))){
				$password1_error = "Password must have at list one number and a latter.";
				$check = false;
				if(htmlentities(trim($_POST['pwd1'])) == ""){
					$password1_error = "Enter your password.";
					$check = false;
				}
                if(strlen(htmlentities(trim($_POST['pwd1']))) < 8 ){
                    $password1_error = "Password is too short. Enter at least 8 characters.";
                    $check = false;
                }
			}
			if(htmlentities(trim($_POST['day'])) == "Day" || htmlentities(trim($_POST['month'])) == "Month" || htmlentities(trim($_POST['year'])) == "Year"){
				$dayMonthYear_error = "Fill in Day Month and the Year.";
				$check = false;
			}
			if(htmlentities(trim($_POST['day'])) > 31 && htmlentities(trim($_POST['day'])) < 0){
				$check = false;
			}
			if(htmlentities(trim($_POST['year'])) < 1900 && htmlentities(trim($_POST['year'])) > date("Y")){
				$check = false;
			}
			if(htmlentities(trim($_POST['month'])) != "January" && htmlentities(trim($_POST['month'])) != "February" && htmlentities(trim($_POST['month'])) != "March" && htmlentities(trim($_POST['month'])) != "April" && htmlentities(trim($_POST['month'])) != "May" &&
			   htmlentities(trim($_POST['month'])) != "June" && htmlentities(trim($_POST['month'])) != "July" && htmlentities(trim($_POST['month'])) != "August" && htmlentities(trim($_POST['month'])) != "September" && htmlentities(trim($_POST['month'])) != "October" &&
			   htmlentities(trim($_POST['month'])) != "November" && htmlentities(trim($_POST['month'])) != "December"){
				$check = false;
			}
			$e = htmlentities(trim($_POST['email1']));
		} else {
        }
	}
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
			$connection = mysqli_connect("localhost","eafeventroot","93milbefwsdfjyrhnt3252skhsnsser93@","MILBER") or die ("could not connect " . mysqli_connect_error());
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
            $salt = 0;
            $salt = generateSalt();
            $hash = hash_hmac("sha256", $p, $salt);
			$bd = htmlentities(trim($_POST['day']));
			$bm = htmlentities(trim($_POST['month']));
			$by = htmlentities(trim($_POST['year']));
			$fn = ucfirst($fn);
			$ln = ucfirst($ln);
			$rd = $dateReg; //when did the user regirtered
			$data = "INSERT INTO MILBER.MilberUserInfo VALUES('','$fn','$ln','','$g','$e','$hash','$salt','$bd','$bm','$by','off','n','$rd','','','','','','U')"; //save new users data to the database
			$status = mysqli_query($connection, $data) or die ("Could not query " . mysqli_error($connection));
			header("Location: index.php");
			mysqli_close($connection);
			die();
		} else {
?>
<!DOCTYPE html>
<html lang="en">
  <head>
  	<meta charset="UTF-8" />
	<title><?php echo $title_name; ?></title>
	<link rel="shortcut icon" href="../Images/newLogo16by16.png" type="image/jpg">
	<link rel="stylesheet" href="../css/myCcss.css" type="text/css" media="screen">
	<link rel="stylesheet" href="../css/bootstrap-3.3.4-css-bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  </head>
  <body class="registration">
  	<div id="bg" class="flash-images">
  		 <img src="../Images/flashPhotos/registration.png" alt="img" class="indexPhoto" id="1">
  	</div>
	<a href="index.php"><img class="milberLogoIndePage" src="../Images/indexleafevent.png" alt="milberLogo" /></a>
	<form class="logInForm" method="POST">
        <div class="formInputs">
            <input type="text" id="emailLogIN" class="userEmail" name="userEmail_login" autofocus="autofocus" placeholder="Email" value="<?php if(isset($_POST['userEmail_login'])) { echo $em; } ?>"/>
            <input type="password" id="passwordLogIN" class="userPassword" name="password_login" placeholder="Password" />
            <div class="inputErrorE"><?php echo $email_error; ?></div>
            <div class="inputErrorP"><?php echo $passord_error; ?></div><br />
<?php      if($numOfRows != 1)echo $user_account_not_exist; ?>
                <input type="submit" class="login" name="Login-find" value="Log in" />
        </div>
    </form>
	<div class="registrationform">
        <p class="newMember">New members free registration.</p>
		<form method="POST" class="newIdBlock" name="newID">
    		<input type="text" name="fName" id="fNameNewId" autofocus="autofocus" placeholder="First Name" value="<?php if(isset($_POST['fName'])) { echo $_POST['fName']; } ?>" />
      		<input type="text" name="lName" id="lNameNewId" placeholder="Last Name" value="<?php if(isset($_POST['lName'])) { echo $_POST['lName']; } ?>" /><div class="errorStyle2"><?php echo $fname_error ." ". $lname_error; ?></div><br />
      		<input type="text" name="email1" id="emailNewId" placeholder="Email" value="<?php if(isset($_POST['email1'])) { echo $_POST['email1']; } ?>" /><div class="errorStyle2"><?php echo $email1_error . "<br />"; ?></div>
      		<input type="password" name="pwd1" id="pwdNewId" placeholder="Password" /><div class="errorStyle2"><?php echo $password1_error . "<br />"; ?></div>
      		<div id="bdayDiv">
    		    <div class="newIdForm">Birthday</div> 
    		  	    <div class="selection">
        		  		<select name="day">
        		  			<option>Day</option>
<?php
        			  				for ( $i = 1; $i <= 31; $i++ ) {
        							    echo "<option value=\"$i\"";
        							    echo $rowsperpage == $i ? 'selected="selected"' : '';
        							    echo ">$i</option>";
        							}
?>
        			  	</select>
        		  		<select name="month">
        		  				<option>Month</option><option value="January">January</option><option value="February">February</option><option value="March">March</option><option value="April">April</option><option value="May">May</option><option value="June">June</option><option value="July">July</option><option value="August">August</option><option value="September">September</option><option value="October">October</option><option value="November">November</option><option value="December">December</option> 
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
			  		    <a href="#" data-toggle="tooltip" data-placement="right" title="By providing us with your birth information, we will able to know if you are eligible enough to view the right content.">Why</a> 
        	  			<script> 
        			        $(document).ready(function(){
        					    $('[data-toggle="tooltip"]').tooltip();
        					});
        			    </script>
				  	    <div class="errorStyle2"><?php echo $dayMonthYear_error . "<br />"; ?></div>
				    </div>
			    </div>
				<div class="genderLook">
		  			<input type="radio" name="gender" value="Male" <?= (isset($_POST['gender']) && $_POST['gender'] == 'Male')? "checked":""; ?> />&nbsp;Male
		  			<input type="radio" name="gender" value="Female" <?= (isset($_POST['gender']) && $_POST['gender'] == 'Female')? "checked":""; ?> />&nbsp;Female
		  			<div class="errorStyle2"><?php echo $gender_error . "<br />"; ?></div>
		  		</div>
				<div class="creatIdForm">
			 		<input type="submit" name="Login-find" value="Create Account" id="submitNewId" />
				</div>
                <p class="registration-agreetment">By registrating, you agree to <a href="terms.php">Leafevent Terms</a></p>
		</form>
	</div>
		<div class="index-form-search-result-div">
            <form method="POST" class="look-event-main-page">
                <input type="text" placeholder="Search by event name or planners name" name="event_search" value="<?= $name_search; ?>"/>&nbsp;OR
                <select name="event_type">
                    <option>Search by type</option>
<?php
                        while($t = mysqli_fetch_array($typeResult)){
?>
                            <option value="<?= $t['id_SQL'];?>"><?= $t['type_name_SQL'];?></option>
<?php 
                        }
?>
                </select>
                <input type="submit" name="Login-find" value="Find" />
            </form>
            <div class="events_to_display">
<?php
               if($eventResult == null){
                    while($e = mysqli_fetch_array($event_result_non_filtered)){
                        $event_ID = $e['event_id_SQL'];
                        $event_folder = $e['event_pic_folder_SQL'];
                        $event_pic = $e['event_picture_SQL'];
                        $planer_name = $e['who_is_making_event_SQL'];
                        if($event_folder == null && $event_pic == null){
                            $eventPIC = "<img src='../milberUserPhotos/cal_green.png' alt='img'>";
                        } else {
                            $eventPIC = "<img src='../milberUserPhotos/eventPhotos/$event_folder/resized_$event_pic'>";
                        }
?>
                            <!--<a href="indexEvent.php?b=<?=$event_ID?>" class="indexevent">
                                <div class="view-all-events-index-pag"><?= $eventPIC;?>
                                    <p class="event_name_size"><?= $e['event_Name_SQL']; ?><br /></p>
                                    <p class="event_maker_name_start"><?=$planer_name;?><br /><?= $e['event_start_datetime_SQL'];?></p>
                                </div>
                            </a>-->
                            <a href="indexEvent.php?b=<?=$event_ID?>" class="indexevent">
                                <div><?= $eventPIC;?>
                                    <p class="event_name_size"><?= $e['event_Name_SQL']; ?><br /></p>
                                    <p class="event_maker_name_start"><?=$planer_name;?><br /><?= $e['event_start_datetime_SQL'];?></p>
                                </div>
                            </a>
<?php
                    }
                } else {
                    if($filter_count != 0){
                        while($e = mysqli_fetch_array($eventResult)){
                            $event_ID = $e['event_id_SQL'];
                            $event_folder = $e['event_pic_folder_SQL'];
                            $event_pic = $e['event_picture_SQL'];
                            $planer_name = $e['who_is_making_event_SQL'];
                            if($event_folder == null && $event_pic == null){
                                $eventPIC = "<img src='../milberUserPhotos/cal_green.png' alt='img'>";
                            } else {
                                $eventPIC = "<img src='../milberUserPhotos/eventPhotos/$event_folder/resized_$event_pic'>";
                            }
?>
                            <a href="indexEvent.php?b=<?=$event_ID?>" class="indexevent">
                                <div><?= $eventPIC;?>
                                    <p class="event_name_size"><?= $e['event_Name_SQL']; ?><br /></p>
                                    <p class="event_maker_name_start"><?=$planer_name;?><br /><?= $e['event_start_datetime_SQL'];?></p>
                                </div>
                            </a>
<?php
                        }
                    } else {
                        echo "<br /><div class='error-msg-for-search-index'>We could not find anything based on your search.<br /><br /><br /></div>";
                   }
                }
?>
            </div>
            <br />
            <p></p>
<?php require "milberfooterend.php"; ?>
        </div>
  </body>
</html>
<?php
}
?>