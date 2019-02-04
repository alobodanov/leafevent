<?php
require_once "Library.php";

	$check = true;
	$email_error = null;
	$passord_error = null;
	$email1_error = null;
	$passord_error1 = null;
	$passord_error2 = null;
	$same_error = null;

	if($_POST){
		if($_REQUEST['Login-reset'] == "Reset"){

			if(!preg_match("/^[a-z]{0,50}?[a-z0-9]{0,50}?[a-z';:.,]{0,50}?[@][a-zA-Z]{1,30}[.][a-zA-Z]{1,20}$/i", htmlentities(trim($_POST['email'])))){
                $email1_error = "Please enter a valid email.";
                $check = false;
                if(htmlentities(trim($_POST['userEmail_login'])) == ""){
                    $email1_error = "Please enter your email.";
                    $check = false;
                }
            }
            if(!preg_match("/^[a-z]{0,50}?[0-9]{0,50}?[a-z0-90-9a-z]{1,50}?$/i", htmlentities(trim($_POST['pass1'])))){
                $passord_error1 = "Only letters and numbers.";
                $check = false;
                if(htmlentities(trim($_POST['password_login'])) == ""){
                    $passord_error1 = "Please enter your password.";
                    $check = false;
                }
            }
            if(!preg_match("/^[a-z]{0,50}?[0-9]{0,50}?[a-z0-90-9a-z]{1,50}?$/i", htmlentities(trim($_POST['pass2'])))){
                $passord_error2 = "Only letters and numbers.";
                $check = false;
                if(htmlentities(trim($_POST['password_login'])) == ""){
                    $passord_error2 = "Please enter your password.";
                    $check = false;
                }
            }
            if(htmlentities(trim($_POST['pass2'])) != htmlentities(trim($_POST['pass1']))) {
            	$same_error = "Passwords do not match";
            	$check = false;
            }

            if($_POST && $check){
            	$link2 = new DBLink();
            	$email_reset = mysqli_real_escape_string($link2->conn(), htmlentities(trim($_POST['email'])));
            	$new_password = mysqli_real_escape_string($link2->conn(), htmlentities(trim($_POST['pass1'])));
            	$update = "UPDATE MilberUserInfo SET password_SQL = '$new_password' WHERE email_SQL = '$email_reset'";
            	$query = $link2->query($update);

            	$Name = "Leafevent"; //senders name 
				$email = "reset@leafevent.com"; //senders e-mail adress 
				$recipient = $email_reset; //recipient 
				$mail_body = "Your password was successfully reset"; //mail body 
				$subject = "Leafevent password reset"; //subject 
				$header = "From: ". $Name . " <" . $email . ">\r\n"; //optional headerfields 

            	mail($recipient, $subject, $mail_body, $header);

			    header('Location: index.php');

            }
		} else if($_REQUEST['Login-reset'] == "Log in") {
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
                if(!preg_match("/^[a-z]{0,50}?[0-9]{0,50}?[a-z0-90-9a-z]{1,50}?$/i", htmlentities(trim($_POST['password_login'])))){
                    $passord_error = "Only letters and numbers.";
                    $check = false;
                    if(htmlentities(trim($_POST['password_login'])) == ""){
                        $passord_error = "Please enter your password.";
                        $check = false;
                    }
                }
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
                    $exist = "SELECT * FROM MILBER.MilberUserInfo WHERE email_SQL = '$em' AND password_SQL = '$ps' LIMIT 1";
                    $result = mysqli_query($link,$exist) or die("Could not query ". mysqli_error($link));
                    $numOfRows = mysqli_num_rows($result);
                    if($numOfRows == 1){
                        while($userRow = mysqli_fetch_array($result)){
                            $userId = $userRow['id_SQL'];
                            /*$username = $userRow['fname_SQL'];
                            $userlname = $userRow['lname_SQL'];
                            $usergender = $userRow['gender_SQL'];
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
                                        setcookie("Leafevent_user","$em", time() + (60 * 10));
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
            }
		}
	}

?>
<!DOCTYPE html>
    <html lang="en">
	    <head>
	        <meta charset="UTF-8" />
	        <link rel="stylesheet" type="text/css" href="../css/myCcss.css">
	        <link rel="shortcut icon" href="../Images/newLogo16by16.png" type="image/png">
        	<title class="WelcomeMilber">Leafevent - New Password</title>

	    </head>
	    <body>
	    	<a href="index.php"><img class="milberLogoIndePage" src="../Images/indexleafevent.png" alt="milberLogo" /></a>
	    	<form class="logInForm" method="POST">
                <div class="formInputs">
                    <input type="text" class="userEmail" name="userEmail_login" autofocus="autofocus" placeholder="Email" value="<?php if(isset($_POST['userEmail_login'])) { echo $em; } ?>"/>
                    <input type="password" class="userPassword" name="password_login" placeholder="Password" />
                    <div class="inputErrorE"><?php echo $email_error; ?></div>
                    <div class="inputErrorP"><?php echo $passord_error; ?></div><br />
<?php      if($numOfRows != 1)echo $user_account_not_exist; ?>
                    <input type="submit" class="login" name="Login-reset" value="Log in" />
                </div>
            </form>
	    	<div class="passwordForgot">
	    		<p>Provide us with your email and reset your password</p>
		    	<form method="POST">
		    		<input type="text" name="email" placeholder="Email" />
<p class="reset-error"><?= $email1_error;?></p>
		    		<input type="password" name="pass1" placeholder="Pasword"/>
<p class="reset-error"><?= $passord_error1;?></p>
		    		<input type="password" name="pass2" placeholder="Password Confirm"/>
<p class="reset-error"><?= $passord_error2;?></p>
<p class="reset-error"><?= $same_error;?></p>
		    		<input type="submit" name="Login-reset" value="Reset"/>
		    	</form>
		    </div>
<?php require "milberfooterend.php"; ?>

		</body>
	</html>