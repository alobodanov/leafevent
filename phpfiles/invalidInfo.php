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

?>





<?php echo "This is invalid user information, please try again."; ?>