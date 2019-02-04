<?php
	//Terminal commands to work with Db from the terminal
	//PATH=$PATH:/usr/local/mysql/bin
	//mysql -h 127.0.0.1 -P 3306 -u root -p Milber



	//Pages liek home, profile, photos, invite and settign all have the same "green bar" the grean bar code located here
	//whatever the canges will be made, it will be changed everywhere.
	//
	session_start();
	$userID = "";			//will have users id from DB
    $username = ""; 		//will have users name
    $userlname = "";		//will have last name
    $usermname = "";		//will have middle name (it's optiona; in DB)
    $useremail = "";		//will have email
    $usergender = "";		//will have thir gender
    $userP = "";			//will have thier password
    $userBd = "";			//will have a day of birth
    $userBm = "";			//will have a month of birth
   	$userBy = null;			//will have a year of birth
   	$switchStatus = null;   //will keep switch status
   	$becameAmemberOn = "";	//will have info when they where registered 
   	$LookingFor = "";		//will contain info on what a user is looking for
   	if($_SESSION['userE_login']){ 
   		$_GET['e'] = $_SESSION['userE_login'];		//will contain user log in email info from log in page
   	} else {
   		header('Location: logout.php');
   		die();
   	}
   	$curentUserId = $_SESSION['user_id'];		//will have a current users log in's ID
   	$theNumOfNewFriends = null;
   	$curentPage = null;     //it keep a var of the curent page user is at. So when you press the switch, it will still be on that page.
   	//$switchOnOff = null; 			//this variable will contain the info if the light switch is on off
   	//$switchTitle = null;   //will have on or off title config from DB for the user and will be updated if the user will click on switch

   	//if(isset($_SESSION['totalNewFriends'])){
   	//	$theNumOfNewFriends = $_SESSION['totalNewFriends']; // will contain a number of new friend 
   	//} else {
   	//	$theNumOfNewFriends = null;
   	//}
   	//this code helps use to print a number next to "Friends" button a number that represents how many new friend requests user has
   	//
   	$Flink = mysqli_connect("localhost", "root", "Password123") or die("could not connect ". mysqli_connect_error());
	$q = "SELECT * FROM MILBER.MilberFriends 
			WHERE (id_Resiver_user_SQL = '$curentUserId' OR id_Sender_request_SQL = '$curentUserId')
			AND Friend_status_SQL = 'New'";
	$result = mysqli_query($Flink, $q) or die ("could not query" . mysqli_error($Flink));
	$count = mysqli_num_rows($result);
	mysqli_close($Flink);
	if($count == 0){
		$theNumOfNewFriends = null;
	} else {
		$count = $theNumOfNewFriends;
	}

   	if($_SESSION['userE_login'] == "" ){
   		header('Location: logout.php');
   		die();
   	} else {
   		//This connection will help us to add a picture to a user profile if thier ID does not exist
   		//in the MilberPhoto table.
   		$userCheck_picture = mysqli_connect("localhost","root","Password123") or die("could not connect " . mysqli_connect_error());
   		$id_check_photo = "SELECT id_SQL FROM MILBER.MilberPhotos WHERE id_SQL = $curentUserId";
   		$find_the_user_id_photo = mysqli_query($userCheck_picture, $id_check_photo) or die ("could not find " . mysqli_error($userCheck_picture));
   		$id_check_photo = mysqli_num_rows($find_the_user_id_photo);
   		if($id_check_photo == 1){
   			//echo  "<br /><br /><br /><br /><br />" . $curentUserId;
   			$selectUserInfo = "SELECT gender_SQL FROM MILBER.MilberUserInfo WHERE id_SQL = $curentUserId";
   			$selectUserInfoQuesry = mysqli_query($userCheck_picture, $selectUserInfo) or die("could not retrive info" . mysqli_error($userCheck_picture));
   			while($UID = mysqli_fetch_array($selectUserInfoQuesry)){
   				$userGender = $UID["gender_SQL"];
   				if($userGender == "Male"){
   					$updatePhotoTable = "INSERT INTO MILBER.MilberPhotos VALUES('',$curentUserId,'')";
   				} else {
   					//$updatePhotoTable = "INSERT INTO MILBER.MilberPhotos VALUES('',$curentUserId,)";
   				}
   			}
   		} else {

   		}
   		mysqli_close($userCheck_picture);

		if(isset($_GET['e'])){
			$link = mysqli_connect("localhost", "root", "Password123") or die("could not connect ". mysqli_connect_error());
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
						$usermname = $get['middle_name_SQL'];
						$useremail = $get['email_SQL'];
	    				$usergender = $get['gender_SQL'];
	    				$userP = $get['password_SQL'];
	    				$userBd = $get['birthdayday_SQL'];
	    				$userBm = $get['birthdaymonth_SQL'];
	    				$userBy = $get['birthdayyear_SQL'];
	    				$switchStatus = $get['switch_SQL'];
	    				$becameAmemberOn = $get['SIGN_UP_DATE'];
	    				//$imageData = $count['user_mainPic_SQL'];
					}
				} else {
					echo "Profile does not exist";
					exit();
				}
			}
		}
		//if the user want's to look something up, they will be reduracted to a view page 
		//that conains all the search code.
		//
		if(isset($_GET['searchingFor'])){
			$_SESSION['searchResoult'] = htmlentities(trim($_GET['searchingFor']));
			$LookingFor = htmlentities(trim($_GET['searchingFor']));
			header("Location: view.php?c=$LookingFor");
			die();
		}
		//this if statment will be executed if the var stats form the switch button was presed.
		//After it will run check what does the var stats contains. If it contains off, it will run one 
		//statment if no, it will run the other. When it runs one of them, it will update DB MilberUserInfo
		//switch_SQL column for that user from off to on OR from on to ff.
		// 
		if(isset($_GET['stats'])){
			if($_GET['stats'] == 'off'){
				$swichLinkStat = mysqli_connect("localhost", "root", "Password123") or die("could not connect ". mysqli_connect_error());
				$q = "UPDATE MILBER.MilberUserInfo SET switch_SQL = 'on' WHERE id_SQL = '$curentUserId'";
				$resultStat = mysqli_query($swichLinkStat, $q) or die ("could not query" . mysqli_error($swichLinkStat));
				$switchStatus = 'on';
				mysqli_close($swichLinkStat);
			}
			if($_GET['stats'] == 'on'){
				$swichLinkStat = mysqli_connect("localhost", "root", "Password123") or die("could not connect ". mysqli_connect_error());
				$q = "UPDATE MILBER.MilberUserInfo SET switch_SQL = 'off' WHERE id_SQL = '$curentUserId'";
				$resultStat = mysqli_query($swichLinkStat, $q) or die ("could not query" . mysqli_error($swichLinkStat));
				$switchStatus = 'off';
				mysqli_close($swichLinkStat);
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="UTF-8" />
    <title class="WelcomeMilber"><?= $username ." ".$userlname; ?></title>
    <link rel="shortcut icon" href="../images/1616.png" type="image/png" />
    <!--<link href='http://fonts.googleapis.com/css?family=Forum|Alice|Asul' rel='stylesheet' type='text/css' />-->
    <!--<link href='http://fonts.googleapis.com/css?family=Crimson+Text' rel='stylesheet' type='text/css'>-->
    <link href='http://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>
    <!--<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css" />-->
	<!--<script src="../homeJavaScript.js" type="text/javascript"></script>-->
    <script type="text/javascript" src="../javascript/homePage.js"></script>
    <script type="text/javascript" src="../javascript/location.js"></script>
	
	<script type="text/javascript" src="../javascript/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

	<!--script type="text/javascript" src="../javascript/bootstrap.min.js"></script-->
	<script type="text/javascript" src="../javascript/library.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

	<!--link rel='stylesheet' type='text/css' href='../css/bootstrap.min.css'/-->
<?php
	if($switchStatus == 'on'){
		require "ChoiseOFCssForNightTime.php";
	} else {
		require "ChoiseOFCssForDifferentBrowsers.php"; 
	}
?>
</head>
<body id ="mainBODYLook">
	<div id="header">
		<div id="headerContent">
			<nav class="navbar">
					<ul class="nav navbar-nav navbar-fixed-top">
						<!-- HOME -->
						<li><a href="home.php" class="navbar-brand"><img src="../images/maplogo.png" alt="img" style="width:30%;height:30%;"></a></li>
						<!-- SEARCH BAR -->
						<li><form method="GET"><input type="text" size="35" placeholder="Search..." name="searchingFor" value="<?php if(isset($_GET['c'])) echo $_GET['c']; ?>"/></form></li>
						<li role="presentation" class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
								<img id="userPhoto" src="showImage.php?id=<?php echo $userID; ?> 320w" sizes="33.3vw" alt="<?= $username?>" />
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li id="invites"><a href="userFriends.php?e=<?php echo $userEmail; ?>" onclick="allButtons();"><span class="showMesToUserOnToolB"><?= $theNumOfNewFriends; ?></span>Friends</a></li>
								<li><a href="<?= $curentPage ?>?stats=<?php echo $switchStatus?>" title="<?php echo $switchStatus; ?>" onclick="baseFunction()">Night Vision</a></li>
								<li id="settings"><a href="userSettings.php?e=<?php echo $userEmail; ?>" onclick="allButtons();">Settings</a></li>
								
								<li><a href="logout.php">Log out</a></li>
							</ul>
						</li>
						
						<li class="pull-right userName"><a href="aboutUser.php?e=<?php echo $userEmail; ?>"><?= $username; ?></a></li>					
						<li class="userPhotoClass pull-right">
							<img id="userPhoto" src="showImage.php?id=<?php echo $userID; ?> 320w" sizes="33.3vw" alt="<?= $username?>" />
						</li>
						<!--<li><ul class="onenavLogOut"><li><p class="userName"><a href="aboutUser.php?e=<?php echo $userEmail; ?>"><?= $username; ?></a></p></li>
							<li><a href="<?= $curentPage ?>?stats=<?php echo $switchStatus?>" title="<?php echo $switchStatus; ?>" onclick="baseFunction()"><img id="<?php echo $switchStatus; ?>" src="../images/switch3.png" alt="img"></a></li>
							<li><a href="logout.php"><img src="../images/turnoff2.png" alt="logout" title="Log out"></a></li>
						</ul></li>-->
					</ul>
			
			</nav>
		</div>
		<!--<hr />-->
	</div>
	<div class="BodyTag">