<?php
	session_start();
	$curentUserId = $_SESSION['user_id'];	
	if(isset($_GET['messageTo'])){

		$msgSendTo = $_GET['messageTo'];
   		$msgTo_id = null;
   		$msgTo_name = null;
   		$msgTo = null;
   		$msgContent = null;
   		$msgFrom = null;
   		$msgTime = null;
   		$opened = "no";
   		$date = date("Y-m-d");

   		//check if the user exists on MilberUserInfo database
   		//
   		$connect = mysqli_connect("localhost", "root", "Password123") or die("could not connect ". mysqli_connect_error());
   		$MsgTo = mysqli_real_escape_string($connect, $msgSendTo);
		$q = "SELECT * FROM MILBER.MilberUserInfo WHERE id_SQL = '$MsgTo'";
		$resultM = mysqli_query($connect, $q) or die ("could not query " . mysqli_error($connect));
		$counter = mysqli_num_rows($resultM);
		mysqli_close($connect);
		if($counter === 1){

			//check if teh user is not sending them selfs a message
			//
			if($MsgTo != $curentUserId){
				//this var will be used as a title for private message that a person is talking to 
				//
				$fromTo = $curentUserId . $MsgTo;
				$toFrom = $MsgTo . $curentUserId;
				$datenow = date("l, d F Y");
				$timenow = date("g:i a", time());

				$connect = mysqli_connect("localhost", "root", "Password123") or die("could not connect ". mysqli_connect_error());
				$q = "INSERT INTO MILBER.MilberMessages VALUES('','$curentUserId','$MsgTo','$fromTo','$toFrom','Hey!','','$datenow','$timenow','$date','$opened')";
				$status = mysqli_query($connect, $q) or die ("could not send " . mysqli_error($q));
				mysqli_close($connect);
				header("Location: userMessages.php?");
				die();
				exit();
			} else {
				header("Location: aboutUser.php");
			}
		}
	}

	//if(isset($_GET['message']) && !empty(trim($_GET['message']))){
	//	$message = htmlentities(trim($_GET['message']));
	//}



?>