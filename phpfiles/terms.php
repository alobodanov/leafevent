<?php $title_name = "Terms of Use" ?>
<?php
    require_once "Library.php";
	session_start();
	
		//variable for log in info
        //
        $email_error = null;
        $passord_error = null;
        $em = null;
        $ps = null;
        $check = true; // this variable is used as a flag for email & password

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
        $userT = null;
        $e = ""; //e variable has a final email address with what the user will be loging in.
        $check = true;
        $date = date("Y-m-d"); //sysdate
        $userId = null;
        $disabledAccount = null;

	    if($_POST){
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
    	}
    	$logInFlag = false;
    	//if the POST is set and check is still true, we want to connect to the DB and retrive all the info about the user.
	    //if numOfRows is 1, means that the user is real and we want to collect all important info.
	    //one of the info that we have collected is if the user is still using their account or if it's deleted.
	    if($_POST && $check){
	        $em = htmlentities(trim($_POST['userEmail_login']));
	        $ps = htmlentities(trim($_POST['password_login']));
	        $link = mysqli_connect("localhost", "eafeventroot", "93milbefwsdfjyrhnt3252skhsnsser93@", "MILBER") or die("could not connect ". mysqli_connect_error());
	        $em = mysqli_real_escape_string($link, $em);
	        $ps = mysqli_real_escape_string($link, $ps);
	        $exist = "SELECT * FROM MILBER.MilberUserInfo WHERE email_SQL = '$em' LIMIT 1";
	        $result = mysqli_query($link,$exist) or die("Could not query ". mysqli_error($link));
	        $numOfRows = mysqli_num_rows($result);
	        while($r = mysqli_fetch_assoc($result)){
        		$saltFromDatabase = $r['password_salt_SQL'];
                $hashFromDatabase = $r['password_SQL'];
                if(testPassword($ps, $saltFromDatabase, $hashFromDatabase)){
                  $logInFlag = true;
                }else{
                  $logInFlag = false;
                }
        	}
	        if($numOfRows == 1 && $logInFlag == true){
	        	$userAccount = null;
	        	while($r = mysqli_fetch_assoc($result)){
	        		$userId = $r['id_SQL'];
	        		$userName = $r['fname_SQL'];
	        		$userEmail = $r['email_SQL'];
	        		$userAccount = $r['disable_SQL'];
	        		$userT = $r['user_type_SQL'];
	        	}
	        		$_SESSION['user_id'] = $userId;
                    $_SESSION['userE_login'] = $userEmail;
                    $_SESSION['userName_login'] = $username;
                    $_SESSION['userlName_login'] = $userlname;
	        	if($userAccount != 'y'){
	        		if($userT != 'A'){
		        		header("Location: home.php");
		        		mysqli_close($link);
	                    exit();
	                    die();
	                } else {
	                	header("Location: adminPHP/weDoThisThingRight.php");
	                	mysqli_close($link);
	                    exit();
	                    die();
	                }
	        	} else {
	        		header('Location: activate.php');
	                mysqli_close($link);
	                exit(); 
	                die();
	        	}
	        }
	    } 
?>
<!DOCTYPE html>
<html lang="en">
  <head>
  	<meta charset="UTF-8" />
	<title><?php echo $title_name; ?></title>
	  <link rel="shortcut icon" href="../images/1616.png" type="image/jpg">
	  <link rel="stylesheet" href="../css/myCcss.css" type="text/css" media="screen">
	  <script type='text/javascript' src="../javascript/library.js"></script>
  </head>
  <body>
  	<div class="termsLog">
  		<a href="index.php"><img src="../Images/leafeventT.png" alt="t" /></a>
  		<form method="POST" class="loginTopB">
  			<input type="text" id="emailLogIN" class="userEmailt" name="userEmail_login" size="20" autofocus="autofocus" placeholder="Email" value="<?php if(isset($_POST['userEmail_login'])) { echo $em; } ?>"/>
            <input type="password" id="passwordLogIN" class="userPasswordt" name="password_login" size="20" placeholder="Password" />
            <input type="submit" class="login" name="logIn" value="Log in" />
  		</form>
  	</div>
  	<br /><br /><br /><br /><br />
  	<div class="termSize">
	  	<p>
			<b>Leafevent Terms of Service<br /><br />
				Date of Last Revision: Sept 14, 2015<br />
				Definitions for this document<br />
				By "Leafevent" we mean the website www.Leafevent.com<br />
				By “us” or “we” we mean the Leafevent company.<br />
				By "content" we mean anything that you put on Leafevent.<br />
				By “Information” we mean anything that you put on Leafevent.<br />
				By "post" we mean putting content or information on Leafevent.<br /><br />
				Regarding Changes to Leafevent Terms of Use<br />
				You will be notified before we make any changes to our terms of service.<br />
				You will also be notified when we make changes to the Leafevent terms of service.<br />
				If you continue to use Leafevent after changes have been made to the Leafevent terms of service this means that you agree to the new terms.<br /><br />
				Leafevent Rules<br />
				Anyone who uses, accesses, or interacts with Leafevent agrees to everything in the Leafevent’s Terms of Service.<br />
				This most recent Leafevent’s Terms of Service is the main agreement between you and Leafevent, these terms replace any prior agreements between you and Leafevent.<br />
				These terms apply no matter what you think the rules might be.<br />
				These terms do not change unless we change them.<br />
				If any specific term in the Leafevent’s Terms of Service prevent us from complying with the law, that specific term will not apply.<br />
				You will follow all applicable laws when using, accessing, or interacting with Leafevent.<br />
				Your rights and responsibilities under these terms cannot be transferred to anyone else without our consent.<br />
				If you repeatedly violate our terms, your account may be suspended, disabled, or completely deleted, depending on the severity of the circumstances.<br />
				Leafevent’s rights and obligations may be transferred partially or entirely to a new party in the event that leafevent.com is aquired by another company, or for other valid reasons within the limits of the law.<br /><br />
				You are not allowed to use Leafevent without the supervision of a parent or legal guardian if you are under the age of 18.<br />
				You are not allowed to use Leafevent if you are a convicted sex offender.<br />
				People and organizations with a history of violent criminal activity are not allowed on Leafevent.<br />
				You are not allowed to make fake accounts, or provide false personal information.<br />
				You are not allowed to pretend to be another person or organization.<br />
				You are not allowed to post content that you do not own or have the right to use.<br />
				You are not allowed to post other peoples personal information without their consent.<br />
				You are not allowed to post anyone's identification documents or ID’s.<br />
				You are not allowed to post anyone’s sensitive financial information.<br />
				You are not allowed to make posts that could threaten a person's privacy or security.<br />
				If you make posts that threaten the wellbeing of a person or their property.<br />
				You are not allowed to bully or harass other users.<br />
				You are not allowed to post content that promotes self-harm or mutilation.<br />
				You are not allowed to make threats of any kind.<br />
				You are not allowed to use Leafevent to violate the rights of others.<br />
				You are not allowed to use Leafevent for anything unlawful or malicious.<br />
				You are not allowed to do anything that will interfere with the working operation of the site.<br />
				You are not allowed to modify or plagiarize our code.<br />
				You are not allowed to upload viruses, spyware, or any code that might threaten or interfere with the privacy, security, or functionality of Leafevent or its users.<br />
				You are not allowed to post spam.<br />
				You are not allowed to use phishing.<br />
				You are not allowed to access Leafevent through automated means (such as bots).<br />
				You are not allowed to collect information through automated means.<br />
				You are not allowed to share your login information or give others access to your account.<br />
				You are not allowed access or use anyone else's account.<br />
				You are not allowed to do anything that would jeopardize the security of your account.<br />
				You are not allowed to do anything that would jeopardize the security of anyone else's account.<br />
				You are not allowed to make another account without our permission if we have disabled your previous account.<br />
				We will remove any posts that violates our terms.<br />
				If you believe Leafevent has wrongfully remove your content you have the ability to notify us.<br />
				If you violate our terms or present us with legal risks we can stop providing you with part or all of our services at any time.<br />
				If deemed necessary law enforcement will be called upon.<br /><br />
				You are not allowed to encourage or facilitate violations of any Leafevent terms.<br />
				If you see anything that violates our terms please report it to us.<br /><br />
				Privacy/Data Policy<br />
				All the content and information that you put on Leafevent is saved and processed on our servers in Canada and is used to provide you with our services.<br />
				We use your Information to maintain the preferences, structure, and functionality of your Leafevent account. Such information may include:<br />
				-Your contact information (Providing us with your email allows use to help you regain access to your account if you forget your password),<br />
				-Your language and timezone,<br />
				-Your location/device location (if you allow it),<br />
				-Time spent logged in<br />
				-Your Leafevent interests,<br />
				-Your Leafevent friend list,<br />
				-Your settings related to who you authorize to see certain messages and posts,<br />
				-The person you last messaged,<br />
				-Your file and software names and types (for content you upload to leafevent),<br />
				-The type of browser you use<br /><br />
				In the event that we are required by law, we may share or preserve information in response to a legal request like search warrants, court orders, or subpoenas.<br />
				You have the ability to delete your account and any content you post, unless we have suspended or disabled your account for valid reasons.<br />
				Once you delete your account, the account and all its contents will be permanently deleted from our servers.<br />
				If you delete content or information from your account it will be permanently deleted from our servers.<br />
				If the ownership of Leafevent changes, control over your information will be transferred to the new owner.<br />
				Aside from your username, profile pictures, and profile banner you will have complete control over the visibility of content on your page.<br />
				We use cookies to provide you with Leafevent services and features as well a security.<br />
				We are happy to take your suggestions, but we are not obligated to compensate you for them.<br /><br />
				Disputes<br />
				Only Canadian laws apply to Leafevent. All legal disputes involving Leafevent will be settled in Ontario, Canada and the laws of the Ontario justice system are the only laws that will apply, and they will apply to all parties involved.<br />
				Leafevent is not responsible for any of your actions and any damages you may cause. Leafevent’s users are fully responsible for all their actions and any damages they may cause while using Leafevent.<br />
				We are not responsible for any of the content or information that is uploaded or posted to Leafevent.<br />
				Leafevent’s users are fully responsible for any of the content or information that they uploaded or posted to Leafevent.<br /> 
				We are not responsible for anything involving third parties who are not directly working in collaboration with Leafevent.<br />
				We are not responsible for our uses conduct. If someone bothers you, you can just block them and then move on with your life.<br /><br />
				The internet is never 100% safe, we do not guarantee that you are 100% safe or that Leafevent will function or be error-free 100% of the time. You use Leafevent at your own risk and you do not hold us responsible for any losses or inconveniences.<br /><br />
				How to contact Leafevent with question<br />
		</p>
	</div>
  </body>
</html>