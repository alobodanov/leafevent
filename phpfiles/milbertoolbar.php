<?php
	//Pages liek home, profile, photos, invite and settign all have the same "green bar" the grean bar code located here
	//whatever the canges will be made, it will be changed everywhere.
	//
	ob_start();
	session_start();
	require_once "Library.php";
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
   	$nightDayButtonView = null;  //will stor a string that will be used in the menu. The string will have day vision or night vision
   	$file = null;			//will be used to see if the user has a file on the disc
	$profPicture = null;		//will be used to check if the user has a profile pic
	$imagePath = null;
	$image_path_settings = null;
	$orderBY_people = null;  //this variable will be set only when a user is looking for a person
	$orderBy_events = null;  //this variable will be set only when a user is lookign for an event 
	$userEmail = null;
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
   		/*$link = new DBLink();
        $exist = "SELECT * FROM MILBER.MilberUserInfo WHERE email_SQL = '$em' AND password_SQL = '$ps' LIMIT 1";
        $result = $link->query($exist);*/

	$Flink = new DBLink();
	$q = "SELECT * FROM MILBER.milberfriends 
			WHERE (id_Resiver_user_SQL = '$curentUserId' OR id_Sender_request_SQL = '$curentUserId')
			AND Friend_status_SQL = 'New'";
	$result = $Flink->query($q);
	//$result = mysqli_query($Flink, $q) or die ("could not query" . mysqli_error($Flink));

	$comming = new DBLink();
	//"SELECT vui.* from (SELECT @f_param:=".$_POST['userId']." p1) parm , v_user_interests_event vui ORDER BY event_start_datetime_SQL";
	$selectc = "SELECT * FROM v_event WHERE event_type_id_SQL = 2 AND date(event_start_datetime_SQL) >= date(now() + interval 2 day) AND admin1_confirm_SQL = 'Y' AND admin2_confirm_SQL = 'Y'";
	$comq = $comming->query($selectc);
	$count_events = mysqli_num_rows($comq);
	while($getName = mysqli_fetch_array($comq)){
		$row_event_name = $getName['event_Name_SQL'];
	}
	$upcomming_event_name = $row_event_name;
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
		if(isset($_GET['e'])){
			/*
			$JsonConnection = new DBLink();
			$JsonQuesry =  "SELECT vui.* from (SELECT @f_param:=".$_POST['userId']." p1) parm , v_user_interests_event vui ORDER BY event_start_datetime_SQL DESC";
			$result=$JsonConnection->query($JsonQuesry);*/


			$link = new DBLink();
			if($_GET['e']){
				$userEmail = $_GET['e'];
				$find = "SELECT * FROM MILBER.v_user_info WHERE email_SQL = '$userEmail' LIMIT 1";
				//$result = mysqli_query($link,$find) or die("Could not query ". mysqli_error($link));
				$result = $link->query($find);
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
	    				$file = $get['folder_name_SQL'];
	    				$profPicture = $get['user_pic_name_SQL'];
	    				if($file == null || $profPicture == null){
	    						$imagePath = "<div><img id='userPhoto' src='../milberUserPhotos/25-25-no-user-pic.png' alt='$username' /></div>";
	    				} else {
	    					$imagePath = "<div><img id='userPhoto' src='../milberUserPhotos/profilePictures/$file/resized_$profPicture' alt='$username' /></div>";
	    					$image_path_settings = "<div><img id='tmp' src='../milberUserPhotos/profilePictures/$file/$profPicture' alt='$username' /></div>";
	    				}
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
			$userFinalLook = null;
			$temp = new DBLink();
			$_SESSION['searchResoult'] = mysqli_real_escape_string($temp->conn(), htmlentities(trim($_GET['searchingFor'])));
			$LookingFor = mysqli_real_escape_string($temp->conn(), htmlentities(trim($_GET['searchingFor'])));
			header("Location: view.php?c=$LookingFor");
			exit();
		}
		//this if statment will be executed if the var stats form the switch button was presed.
		//After it will run check what does the var stats contains. If it contains off, it will run one 
		//statment if no, it will run the other. When it runs one of them, it will update DB MilberUserInfo
		//switch_SQL column for that user from off to on OR from on to ff.
		// 
		if(isset($_GET['stats'])){
				if($_GET['stats'] == 'off'){
					$swichLinkStat = new DBLink();
					$q = "UPDATE MILBER.v_user_info SET switch_SQL = 'on' WHERE id_SQL = '$curentUserId'";
					$resultStat = $swichLinkStat->query($q);
					$switchStatus = 'on';
				}
				if($_GET['stats'] == 'on'){
					$swichLinkStat = new DBLink();
					$q = "UPDATE MILBER.v_user_info SET switch_SQL = 'off' WHERE id_SQL = '$curentUserId'";
					$resultStat = $swichLinkStat->query($q);
					$switchStatus = 'off';
				}
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title class="WelcomeMilber"><?= $username ." ".$userlname; ?></title>
    <link rel="shortcut icon" href="../Images/newLogo16by16.png" type="image/png" />
    <script type='text/javascript' src='../markers.json'></script>

    <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js'></script> <!-- To display posts-->
    <!--<script src="https://maps.google.com/maps/api/js?sensor=false"></script>-->

  	<!--<script type='text/javascript' src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>-->
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <!--	<script src='https://api.mapbox.com/mapbox.js/v2.2.1/mapbox.js'></script>
  	<link href='https://api.mapbox.com/mapbox.js/v2.2.1/mapbox.css' rel='stylesheet' />-->
  	<link href='https://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>
  	<!--<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>////////////////////-->
 	<!--<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css" />-->

 	<!--<link rel="stylesheet" type="text/css" href="../css/slick.css"/>
    <link rel="stylesheet" type="text/css" href="../css/slick-theme.css"/>.............-->

 	<script src='https://api.mapbox.com/mapbox.js/v2.2.2/mapbox.js'></script>
 	<link href='https://api.mapbox.com/mapbox.js/v2.2.2/mapbox.css' rel='stylesheet' />
<?php
	if($switchStatus == 'on'){
?>
	<link rel="stylesheet" type="text/css" href="../css/myNightCcss.css">
<?php
		$nightDayButtonView = "Day Vision";	
	} else {
?>
	<link rel="stylesheet" type="text/css" href="../css/myCcss.css">
<?php
		$nightDayButtonView = "Night Vision";
	}
?>
    <!--<script type="text/javascript" src="../javascript/homePage.js"></script>-->
    <script type="text/javascript" src="../javascript/location.js"></script>
    <script type="text/javascript" src="../javascript/library.js"></script>
    <script type="text/javascript" src="../javascript/jquery-ui-1.11.4.js"></script>
</head>
<body id ="mainBODYLook">
	  	<nav id="header">
	  		<div id="headerContent">
	  			<ul class="onenav">
					<li><a href="home.php" class="homeButton" title="Home"><img src="../Images/nwlogo30.png" alt="img"></a></li>
					<li><form method="GET"><input id="userCurrentTime" type="hidden" value="" name="usertime"><input type="text" size="35" placeholder="Search" name="searchingFor" value="<?php if(isset($_GET['c'])) echo $_GET['c']; ?>" /></form></li>
					<li class="userPhotoClass" title="Profile"><a href="userFriendInfo.php?u=<?=$userID;?>"><?= $imagePath; ?><span><?= $username; ?></span></a></li>
					<li class="dropMenu" title="Menu"><a href="userFriends.php" title="Friends" class="friends"><img src="../Images/LogoWhiteFriends.png" alt="" onMouseOut=src="../Images/LogoWhiteFriends.png" onMouseOver=src="../Images/LogoWhiteFriends-dark.png"></a>&nbsp;&nbsp;<a href="creaneNewEvent.php" title="New Event" class="newX"><img src="../Images/LogoWhiteCalender.png" onMouseOut=src="../Images/LogoWhiteCalender.png" onMouseOver=src="../Images/LogoWhiteCalender-dark.png" alt=""></a><a class="options"><img src="../Images/logowhitemenu.png" onMouseOut=src="../Images/logowhitemenu.png" onMouseOver=src="../Images/logowhitemenu-dark.png" alt=""></a>
						<ul class="navTools">
							<a href="<?= $curentPage ?>?stats=<?= $switchStatus;?><?php if(isset($_GET['u'])) echo "&u=".$_GET['u'];?><?php if(isset($_GET['c'])) echo "&c=".$_GET['c'];?>" title="<?= $switchStatus; ?>" id="nightandday" onclick="baseFunction()"><li><?= $nightDayButtonView; ?></li></a>
							<hr />
					    	<a href="userSettings.php" title="Settings"><li id="settings">Settings</li></a>
					    	<hr />
					    	<a href="logout.php" title="Log out"><li>Log out</li></a>
						</ul>
					</li>
				</ul>
			</div>
		</nav>
		<div class="BodyTag">