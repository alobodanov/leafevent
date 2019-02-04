<?php
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
    $password1_error = "";
    $emailexist_error = "";
    $dayMonthYear_error = "";
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


    $nameName = null;
    $eventType = null;
    $eventMaker = null;
    $eventAddress = null;
    $eventStart = null;
    $eventEnd = null;
    $eventPP = null;
    $eventPrice = null;
    $eventTickets = null;
    $eventFolder = null;
    $eventPicture = null;
    $eventDesc = null;
    $event_result_non_filtered = null;
    $moreimagePath = null;
    $look = null;

    if(isset($_GET['b'])){
        $select_events = new DBLink();
        $look = mysqli_real_escape_string($select_events->conn(),htmlentities(trim($_GET['b'])));
        $events = "SELECT * FROM v_event WHERE event_id_SQL = '$look'";
        $event_result_non_filtered = $select_events->query($events);

        while($see = mysqli_fetch_assoc($event_result_non_filtered)){
            $nameName = $see['event_Name_SQL'];
            $eventType = $see['event_type_id_SQL'];
            $eventMaker = $see['who_is_making_event_SQL'];
            $eventAddress = $see['event_address_SQL'];
            $eventStart = $see['event_start_datetime_SQL'];
            $eventEnd = $see['event_end_datetime_SQL'];
            $eventPP = $see['event_Private_Public_SQL'];
            $eventPrice = $see['event_price_SQL'];
            $eventTickets = $see['event_tickets_SQL'];
            $eventFolder = $see['event_pic_folder_SQL'];
            $eventPicture = $see['event_picture_SQL'];
            $eventDesc = $see['event_description_SQL']; 
            if($eventFolder == null || $eventPicture == null){
                $moreimagePath = "<div class='block-one'><img class='view-index-event-pic' src='../Images/no-event-pic.png' alt='img' /></div>";
            } else {
                $moreimagePath = "<div class='block-one'><img class='view-index-event-pic' src='../milberUserPhotos/eventPhotos/$eventFolder/$eventPicture' alt='img' /></div>";
            }
        }
    }

    if($_POST){
        if($_REQUEST['Login-register'] == "Log in"){

            if(isset($_POST['Login-register'])){
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
                if(!preg_match("/^[a-z]{0,50}?[0-9]{0,50}?[a-z0-90-9a-z]{1,50}?$/i", htmlentities(trim($_POST['password_login'])))){ //("/^[a-z0-9\,\<\>\.\%\;\(\)\'\ \!\?\:\"]*$/i",
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
                            //if(isset($_SERVER['HTTPS'])){ 
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
                          /*} else {
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
                            }*/
                        } else {
                            header('Location: activate.php');
                            mysqli_close($link);
                            exit(); 
                            die();
                        }
                        //if the numOfRows is 0, then it will print this message NOTE: will be making a page for that
                        //
                    } else {
                        mysqli_close($link);
                        $user_account_not_exist = "<div class=\"maybe-new-user\" onclick=\"toggleNavPanel('#passForgot', 300);\">Invalid user information, forgot your password?</div>
                                                        <span id=\"passForgot\" style=\"display:none\" class='box-for-forgoton-user'>
                                                            
                                                                <input type=\"text\" name=\"forgoton-email\" placeholder=\"your email\" >
                                                                <input type=\"submit\" name=\"Login-find-user\" value=\"Submit\" >
                                                            
                                                        </span>";
                        //header('Location: invalidInfo.php');
                    }
                }
            }

            if(isset($_POST['Login-find-user'])) {
                $email_sent_to = htmlentities(trim($_POST['forgoton-email']));
                $email_for_send = mysqli_connect("localhost","eafeventroot","93milbefwsdfjyrhnt3252skhsnsser93@","MILBER") or die ("could not connect " . mysqli_connect_error());
                $email_sent_to = mysqli_real_escape_string($email_for_send, $email_sent_to);
                $send_email_info = "SELECT fname_SQL, password_SQL FROM v_user_info WHERE email_SQL = '$email_sent_to'";
                $email_set_quesry = mysqli_query($email_for_send, $send_email_info) or die ("could not query" . mysqli_error($email_for_send));
                $email_to_count = mysqli_num_rows($email_set_quesry);
                //if($email_to_count == 1){
                    $email_forgot_name = null;
                    $email_forgot_pass = null;
                    while($ufe = mysqli_fetch_array($email_set_quesry)){
                        $email_forgot_name = $ufe['fname_SQL'];
                        $email_forgot_pass = $ufe['password_SQL'];
                    }
                    $send = mail (
                        "$email_sent_to",
                        "Leafevent your info",
                        "That will be $email_forgot_pass",
                        "From: Leafevent"
                    );
                //}
                

            }
        } else if($_REQUEST['Login-register'] == "Register"){
            if(!preg_match("/^[a-z]{1,50}$/i", htmlentities(trim($_POST['user-name'])))){
                $fname_error = "Enter only letters.";
                $check = false;
                if(htmlentities(trim($_POST['fName'])) == ""){
                    $fname_error = "Enter your name.";
                    $check = false;
                }
            }
            if(!preg_match("/^[a-z]{1,100}$/i", htmlentities(trim($_POST['user-last-name'])))){
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
            if(!preg_match("/^[a-z]{0,50}?[a-z0-9]{0,50}?[a-z';:.,]{0,50}?[@][a-zA-Z]{1,30}[.][a-zA-Z]{2,3}$/i", htmlentities(trim($_POST['user-email'])))){
                $email1_error = "Enter your email.";
                $check = false;
                if(htmlentities(trim($_POST['email1'])) == ""){
                    $email1_error = "Enter your email.";
                    $check = false;
                }
            }
            if(!preg_match("/^[a-z]{0,50}?[0-9]{0,50}?[a-z0-90-9a-z]{1,50}?$/i", htmlentities(trim($_POST['user-password'])))){
                $password1_error = "Password can only have letters and integers.";
                $check = false;
                if(htmlentities(trim($_POST['pwd1'])) == ""){
                    $password1_error = "Enter your password.";
                    $check = false;
                }
            }
            if(htmlentities(trim($_POST['day'])) == "Day" || htmlentities(trim($_POST['month'])) == "Month" || htmlentities(trim($_POST['year'])) == "Year"){
                $dayMonthYear_error = "Fill in Day Month and the Year.";
                $check = false;
            }
            //$e = htmlentities(trim($_POST['email1']));
            /*
            $swichLinkStat = new DBLink();
            $input = mysqli_real_escape_string($swichLinkStat->conn(),htmlentities(trim($_POST['commentBody'])));
            $sql = "INSERT INTO MILBER.Comments (comment_body_SQL,post_id_SQL,milberUserInfo_id_SQL) VALUES('".$input."',".trim($_POST['post_id']).",".$_POST['user_id'].")";
            $resultStat = $swichLinkStat->query($sql);

            */
            
            $connection = new DBLink();
            $email_check = mysqli_real_escape_string($connection->conn(), htmlentities(trim($_POST['user-email'])));
            $validUser = "SELECT email_SQL FROM MILBER.v_user_info WHERE email_SQL = '$email_check'";
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
                $fn = htmlentities(trim($_POST['user-name']));
                $fn = mysqli_real_escape_string($connection, $fn);
                $ln = htmlentities(trim($_POST['user-last-name']));
                $ln = mysqli_real_escape_string($connection, $ln);
                $g = htmlentities(trim($_POST['gender']));
                $g = mysqli_real_escape_string($connection, $g);
                $e = htmlentities(trim($_POST['user-email']));
                $e = mysqli_real_escape_string($connection, $e);
                $p = htmlentities(trim($_POST['user-password']));
                $p = mysqli_real_escape_string($connection, $p);
                $bd = htmlentities(trim($_POST['day']));
                $bm = htmlentities(trim($_POST['month']));
                $by = htmlentities(trim($_POST['year']));

                $look = mysqli_real_escape_string($connection, htmlentities(trim($look)));
                $rd = $dateReg; //when did the user regirtered
                $data = "INSERT INTO MILBER.MilberUserInfo VALUES('','$fn','$ln','','$g','$e','$p','$bd','$bm','$by','off','n','$rd','','','','','','U')"; //save new users data to the database
                $status = mysqli_query($connection, $data) or die ("Could not query " . mysqli_error($connection));

                $select_events = new DBLink();
                $look = mysqli_real_escape_string($select_events->conn(),htmlentities(trim($_GET['b'])));
                $events = null;
                $ernf = $select_events->query($events);
                //$_SESSION['user_id'] = $userId;
                $_SESSION['userE_login'] = $e;
                setcookie("Leafevent_user","$em", time() + (60 * 10));

                header("Location: index.php?visit=".$look);
                mysqli_close($connection);
                die();
            }
        }
    }
?>


<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8" />
        <link rel="shortcut icon" href="../Images/newLogo16by16.png" type="image/png">
        <title class="WelcomeMilber">Leafevent - log in</title>
        <link rel="stylesheet" type="text/css" href="../css/myCcss.css">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <body>
        <div class="BodyTagIndex">
            <a href="index.php"><img class="milberLogoIndePage" src="../Images/indexleafevent.png" alt="milberLogo" /></a>
            <form class="logInForm" method="POST">
                <div class="formInputs">
                    <input type="text" class="userEmail" name="userEmail_login" autofocus="autofocus" placeholder="Email" value="<?php if(isset($_POST['userEmail_login'])) { echo $em; } ?>"/>
                    <input type="password" class="userPassword" name="password_login" placeholder="Password" />
                    <div class="inputErrorE"><?php echo $email_error; ?></div>
                    <div class="inputErrorP"><?php echo $passord_error; ?></div><br />
                    <script> 
                        function toggleNavPanel(element, speed){ $(element).toggle(speed);} 
                    </script> 
<?php      if($numOfRows != 1)echo $user_account_not_exist; ?>
                    <input type="submit" class="login" name="Login-register" value="Log in" />
                </div>
            </form>
            <div class="index-event-box">
                <p class="index-event-name"><?= $nameName;?></p>
                <div class="index-desc-pic">
                    <p class="index-event-desc" class="block-one"><?= $eventDesc;?></p>
                    <?= $moreimagePath;?>
                </div>
                <div class="index-content-plus-registration">
                    <div class="index-event-other-content">
                        <p class="index-event-address"><?= $eventAddress;?></p>
                        <p class="index-event-start">Starts on <?= $eventStart;?> End on <?= $eventEnd;?></p>
                        <p class="index-event-ticket">Tickets <?= $eventTickets;?></p>
                        <p class="index-event-price"><?php if($eventPrice == 0) echo "Free"; else echo $eventPrice; ?></p>
                        <p class="index-event-type"><?= $eventType; ?></p>
                        <p class="index-event-PP"><?= $eventPP; ?></p>
                        <p class="index-event-by">By <?= $eventMaker;?></p>
                    </div>
                    <div class="index-event-togo">
                        <script> 
                            function toggleNavPanel(element, speed){ $(element).toggle(speed);} 
                        </script>  
                        <p onclick="toggleNavPanel('#index-event-form', 300);">Register</p>
                        <form method="POST" id="index-event-form" style="display:none" class="indexevent-form-register">
                            <p> You will have to register first beffor attending an event</p><br />
                            <input type="text" name="user-name" placeholder="Name" value="<?php if(isset($_POST['user-name'])) { echo $_POST['user-name']; } ?>" />
                            <input type="text" name="user-last-name" placeholder="Last name" value="<?php if(isset($_POST['user-last-name'])) { echo $_POST['user-last-name']; } ?>" /><br />
                            <div class="errorStyle2"><?php echo $fname_error ." ". $lname_error; ?><br /></div>
                            <input type="text" name="user-email" placeholder="Email" value="<?php if(isset($_POST['user-email'])) { echo $_POST['user-email']; } ?>" /><br />
                            <div class="errorStyle2"><?php echo $email1_error . "<br />"; ?></div>
                            <div class="errorStyle2"><?php echo $email_exist; ?><br /></div>
                            <input type="password" name="user-password" placeholder="Password" /><br />
                            <div class="errorStyle2"><?php echo $password1_error . "<br />"; ?></div>
                            Birthday:
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
                                <script src="//code.jquery.com/jquery-1.10.2.js"></script>
                                <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
                                <script type="text/javascript"> 
                                    $('.hastip').tooltipsy({
                                        offset: [10, 0],
                                        css: {
                                            'padding': '10px',
                                            'max-width': '100px',
                                            'color': '#303030',
                                            'background-color': '#f5f5b5',
                                            'border': '1px solid #deca7e',
                                            '-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                                            '-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                                            'box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                                            'text-shadow': 'none'
                                        }
                                    });
                                </script> 
                            </select><a href="#" class="hastip" title="That&apos;s what this widget is">Why</a><br />
                            <div class="errorStyle2"><?php echo $dayMonthYear_error . "<br />"; ?></div>
                            <div class="genderLook">
                                <input type="radio" name="gender" value="Male" <?= (isset($_POST['gender']) && $_POST['gender'] == 'Male')? "checked":""; ?> />&nbsp;Male
                                <input type="radio" name="gender" value="Female" <?= (isset($_POST['gender']) && $_POST['gender'] == 'Female')? "checked":""; ?> />&nbsp;Female
                                <div class="errorStyle2"><?php echo $gender_error . "<br />"; ?></div>
                            </div>
                            <input type="submit" class="indexevent-submit" name="Login-register" value="Register" />
                        </form>

                    </div>
                </div>
            </div>











        </div>

        </body>
    </html>