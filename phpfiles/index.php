<?php
    //This is a log in page that can take a user to a home page. Also, it can take a new user to a registration page 
    //where they will able to register successfully
    //
    //make new sessions variables if is not empty.
    //
    session_start();
    require_once "Library.php";

    if(!isset($_SESSION['userEmail_login'])){
        $userEmail = "";
    } else {
        $uid = $_SESSION['user_id'];
        $userEmail = $_SESSION['userEmail_login'];
        $userName = $_SESSION['userName_login'];
        $userlName = $_SESSION['userlName_login'];
    }
        $typelink = new DBLink();
        $types = "SELECT * FROM v_eventtype ORDER BY id_SQL";
        $typeResult = $typelink->query($types);

        $select_events = new DBLink();
        $events = "SELECT * FROM v_event";
        $event_result_non_filtered = $select_events->query($events);
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
        $event_result = null;
        $filter_count = null;
        $eventResult = null;
        $name_search = null;
    //in this part, we are doing a validation for the user email log in and for the password 
    //
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
                if(!preg_match("/^[a-z]{0,50}?[0-9]{0,50}?[a-z0-90-9a-z]{1,50}?$/i", htmlentities(trim($_POST['password_login'])))){
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
                        $userT = $userRow['user_type_SQL'];
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
        }
    }
?>
<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta property="og:site_name" content="Leafevent" />
        <meta property="og:url" content="https://www.leafevent.com/" />
        <meta property="og:description" content="Create or login to Leafevent. Create, discover and rate different events with your friends, family, co workers and others. Leafevent brings people together through common interests." />
        <meta name="robots" content="noodp,noydir">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link rel="shortcut icon" href="../Images/newLogo16by16.png" type="image/png">
        <title class="WelcomeMilber">Leafevent - log in</title>
        <link rel="stylesheet" type="text/css" href="../css/myCcss.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script type='text/javascript' src="../javascript/library.js"></script>
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
                        if(document.getElementsByClassName("event_name_size")[i].innerHTML.length > 44){
                            var text = document.getElementsByClassName("event_name_size")[i].innerHTML.substring(0,44);
                            text = text + " . . .";
                            document.getElementsByClassName("event_name_size")[i].innerHTML = text;
                        }
                    }
            };
            </script>
        </head>
        <body class="indexBody">
            <div class="BodyTagIndex">
<?php
            if (strstr($_SERVER['HTTP_USER_AGENT'], 'iPad')) {
?>
                <div id="bg" class="flash-images">
                    <img src="../Images/flashPhotos/d1.jpg" alt="img" class="indexPhoto" id="3">
                    <img src="../Images/flashPhotos/d2.jpg" alt="img" class="indexPhoto" id="2">
                    <img src="../Images/flashPhotos/d3.jpg" alt="img" class="indexPhoto" id="1">
                </div>
<?php
            } else {
?>
                <div id="bg" class="flash-images">
                    <img src="../Images/flashPhotos/d1.jpg" alt="img" class="indexPhoto" id="3">
                    <img src="../Images/flashPhotos/d2.jpg" alt="img" class="indexPhoto" id="2">
                    <img src="../Images/flashPhotos/d3.jpg" alt="img" class="indexPhoto" id="1">
                </div>
<?php
}
?>
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
                <div class="middle-box-start">
                    <div class="middle-mox-register">
                        <p class="middle-box-content">Stay Connected &#38; Promote<br /><span>Have fun with friends, organize events and expand</span></p><br />
                        <a href="registrationForm.php">Start Now</a>
                    </div>
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
            </div>
    </body>
</html>