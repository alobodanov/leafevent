<?php
require_once "Library.php";
if($_POST){
	$link = new DBLink();
	$forgot = mysqli_real_escape_string($link->conn(), htmlentities(trim($_POST['email'])));
	$select = "SELECT email_SQL FROM v_user_info WHERE email_SQL = '$forgot'";
	$query = $link->query($select);

	$Name = "Leafevent"; //senders name 
	$email = "reset@leafevent.com"; //senders e-mail adress 
	$recipient = $forgot; //recipient 
	$mail_body = "You can reset your password by following this link www.leafevent.com/phpfiles/resetpassword.php"; //mail body 
	$subject = "Leafevent password request"; //subject 
	$header = "From: ". $Name . " <" . $email . ">\r\n"; //optional headerfields 
	mail($recipient, $subject, $mail_body, $header);                  
    $status = "Please check your email";
}
?>
<!DOCTYPE html>
    <html lang="en">
	    <head>
	        <meta charset="UTF-8" />
	        <link rel="stylesheet" type="text/css" href="../css/myCcss.css">
	        <link rel="shortcut icon" href="../Images/newLogo16by16.png" type="image/png">
	        <script type='text/javascript' src="../javascript/library.js"></script>
        	<title class="WelcomeMilber">Leafevent - Password Reset</title>
	    </head>
	    <body>
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
	    	<div class="passwordForgot">
	    		<p>Type in your email and we will send you a link shortly</p>
		    	<form method="POST">
		    		<input type="text" name="email" />
		    		<input type="submit" name="send" />
		    	</form>
		    	<br />
				<p class="emailStat"><?= $status;?></p>
		    </div>
<?php require "milberfooterend.php"; ?>