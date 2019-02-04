<?php
	//This page is used for activating the users profile back if they have deactivated in the past.
	require_once "Library.php";
	$email_error = "";
	$password_error = "";
	$check = true;

	$event_result = null;
    $filter_count = null;
    $eventResult = null;
    $name_search = null;

    $typelink = new DBLink();
    $types = "SELECT * FROM v_eventtype ORDER BY id_SQL";
    $typeResult = $typelink->query($types);

    $select_events = new DBLink();
    $events = "SELECT * FROM v_event";
    $event_result_non_filtered = $select_events->query($events);


	if($_POST){
		if($_REQUEST['submitActivation-find'] == "Activate"){
			//validation for the users email input
			//
			if(!preg_match("/^[a-z]{0,50}?[a-z0-9]{0,50}?[a-z';:.,]{0,50}?[@][a-zA-Z]{1,30}[.][a-zA-Z]{2,3}$/i", trim($_POST['userEmailActivate']))){
				$email_error = "Please enter your email.";
				$check = false;
				if($_POST['userEmailActivate'] == ""){
					$email_error = "Please enter your email.";
					$check = false;
				}
			}
			//validation for the users password input
			//
			if(!preg_match("/^[a-z]{0,50}?[0-9]{0,50}?[a-z0-90-9a-z]{1,50}?$/i", trim($_POST['userPasswordActivate']))){
				$password_error = "Password can only have letters and integers.";
				$check = false;
				if($_POST['userPasswordActivate'] == ""){
					$password_error = "Please enter your password.";
					$check = false;
				}
			}
		}
		//if the check is true & post is valid, make a connection to the DB and look for the email teh user typed in.
		//
		if($_POST && $check){
			$connection = new DBLink();
			$activateEmail = mysqli_real_escape_string($connection->conn(), htmlentities(trim($_POST['userEmailActivate'])));
			$activatePassword = mysqli_real_escape_string($connection->conn(), htmlentities(trim($_POST['userPasswordActivate'])));
			$validUser = "SELECT * FROM MILBER.v_user_info WHERE email_SQL = '$activateEmail'";
			$result = $connection->query($validUser);
			$existingUser = mysqli_num_rows($result);
			//if the email they typed in is exist in the DB, then reseat a flag from y to n
			//
			if($existingUser === 1){
				$connection = new DBLink();
				$activate = "UPDATE MILBER.MilberUserInfo SET disable_SQL = 'n' WHERE email_SQL = '$activateEmail'";
				$activateSuccess = mysqli_query($connection, $activate) or die ("Could not activate your account". mysqli_error());
				header('Location: index.php');
				die();
			}
		}
	} else if($_REQUEST['submitActivation-find'] == "Find"){
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
    }

?>
<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8" />
        <!--<meta property="og:site_name" content="Leafevent" />
        <meta property="og:url" content="https://www.leafevent.com/" />
        <meta property="og:description" content="Create or login to Leafevent. Create, discover and rate different events with your friends, family, co workers and others. Leafevent brings people together through common interests." />
        <meta name="robots" content="noodp,noydir">-->
        <link rel="shortcut icon" href="../Images/newLogo16by16.png" type="image/png">
        <title class="WelcomeMilber">Leafevent - Activate</title>
        <link rel="stylesheet" type="text/css" href="../css/myCcss.css">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script type="text/javascript">
            sliderInt = 1;
            sliderNext = 2;

            $(document).ready(function() {
                $("#bg > img#1").fadeIn(300);
                checkSize();
                startSlider();
            });

            function startSlider() {
                count = $("#bg > img").size();

                function loop() {
                    $("#bg > img").fadeOut(400);
                    $("#bg > img#" + sliderNext).fadeIn(400);

                    sliderInt = sliderNext;
                    sliderNext = sliderNext + 1;

                    if(sliderNext > count) {
                    sliderNext = 1;
                    }
                }

                interval = setInterval(loop, 4000);

                $("img").hover(function() {
                    clearInterval(interval);
                    
                }, function() {
                    interval = setInterval(loop, 4000);
                    
                });

            };

            function checkSize() { 

                    var num = document.getElementsByClassName("event_name_size").length;
                    var i;
                    for(i = 0; i < num; i++){
                        if(document.getElementsByClassName("event_name_size")[i].innerHTML.length > 32){
                            var text = document.getElementsByClassName("event_name_size")[i].innerHTML.substring(0,24);
                            text = text + " . . .";
                            document.getElementsByClassName("event_name_size")[i].innerHTML = text;
                        }
                    }
            };

            </script>
        </head>

        <body>
            <div class="BodyTagIndex">
                <div id="bg" class="flash-images">
                        <img src="../Images/flashPhotos/d1.jpg" alt="img" class="indexPhoto" id="3">
                        <img src="../Images/flashPhotos/d2.jpg" alt="img" class="indexPhoto" id="2">
                        <img src="../Images/flashPhotos/d3.jpg" alt="img" class="indexPhoto" id="1">
                </div>
			<br />
	  		<div class="activateAccountPageName">
				<p class="activate-headed">Activate your Leafevent account</p>
				<p class="info1">If you will activate your account, all informatin that you had beffore will be restored.<br /><br />
					To acctivate your account, simply type in your Leafevent email and a password.</p>
					<form method="POST" class="activateForm">
						<input type="text" name="userEmailActivate" placeholder="Email" /><br />
						<input type="password" name="userPasswordActivate" placeholder="Password" /><br />
						<a href="#">Forgot your password?</a>
						<input type="submit" name="submitActivation-find" value="Activate" />
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
                            <input type="submit" name="submitActivation-find" value="Find" />
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
                                        $eventPIC = "<img src='../milberUserPhotos/eventPhotos/$event_folder/$event_pic'>";
                                    }
?>
                                        <a href="indexEvent.php?b=<?=$event_ID?>" class="indexevent"><div class="view-all-events-index-pag">
                                            <div><?= $eventPIC;?></div>
                                            <p class="event_name_size"><?= $e['event_Name_SQL'].'</p>&nbsp;&nbsp;&nbsp;&nbsp;By '.$planer_name;?><br />
                                            <?= $e['event_start_datetime_SQL'];?>
                                        </div></a>
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
                                            $eventPIC = "<img src='../milberUserPhotos/eventPhotos/$event_folder/$event_pic'>";
                                        }
?>
                                            <a href="indexEvent.php?b=<?=$event_ID?>" class="indexevent"><div class="view-all-events-index-pag">
                                                <div><?= $eventPIC;?></div>
                                                <p class="event_name_size"><?= $e['event_Name_SQL'].'</p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;By '.$planer_name;?><br />
                                                <?= $e['event_start_datetime_SQL'];?>
                                            </div></a>
<?php
                                    }
                                } else {
                                    echo "<br /><div class='error-msg-for-search-index'>We could not find anything based on your search.</div>";
                               }
                            }
?>
                        </div>
<?php require "milberfooterend.php"; ?>
                    </div>
	</div>