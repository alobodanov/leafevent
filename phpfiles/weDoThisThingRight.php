<?php
	session_start();
	require_once "Library.php";
	$adminEmail = null;
	$adminName = null;

	if($_SESSION['userE_login']){ 
   		$adminEmail = $_SESSION['userE_login'];		//will contain user log in email info from log in page
   		$adminEmail = htmlentities(trim($adminEmail));
   	} else {
   		header('Location: logout.php');
   		die();
   	}
   	$dbLink = new DBLink();
   	$usrTable = "SELECT * FROM MILBER.v_user_info WHERE email_SQL = '$adminEmail'";
	$usrResult = $dbLink->query($usrTable);

	while($a = mysqli_fetch_array($usrResult)){
		$adminName = $a['fname_SQL'];
	}



?>

<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8" />
            <link rel="shortcut icon" href="../images/1616.png" type="image/png">
            <title class="WelcomeMilber">Welcome</title>
            <link rel="stylesheet" type="text/css" href="../css/itsAlways534322andYou23421.css">
            <script type='text/javascript' src='../............json'></script>
        </head>

        <body>
        	<h2>Welcome <?= $adminName;?>, Admin</h2>
        	<a href="adminEventConferm.php">Confirm New Events<a> <a href="newAdmin.php">New Admin</a> <a href="logout.php">Log out</a>
        	<div>
        		<br />
        		<p>New events to be confirmed</p>




        	</div>