<?php
	session_start();
	$curentUserId = $_SESSION['user_id'];

	if(isset($_GET['delete'])){

		$var = htmlentities(trim($_GET['delete']));
		$msgDeleteFor = $curentUserId . $var;

		echo $curentUserId;



		$allUserslink = mysqli_connect("localhost", "root", "Password123") or die("could not connect ". mysqli_connect_error());
		$msgDeleteFor = mysqli_real_escape_string($allUserslink, $msgDeleteFor);
		$qq = "SELECT * 
					FROM MILBER.MilberMessages m, MILBER.MilberUserInfo u
					WHERE (m.user_to_SQL = u.id_SQL OR m.user_to_SQL = $curentUserId)
					AND (m.user_from_SQL = $curentUserId OR m.user_from_SQL = u.id_SQL) 
					AND (m.from_to_user_key_SQL LIKE '{$curentUserId}%' OR m.to_from_user_SQL LIKE '{$curentUserId}%')";
	   	$Deleteresults = mysqli_query($allUserslink, $qq) or die("Could not connect" . mysqli_error($allUserslink));
	   	mysqli_close($allUserslink);
	   	while($s = mysqli_fetch_assoc($Deleteresults)){

	   		if($msgDeleteFor == $s['from_to_user_key_SQL'] && $s['to_from_user_SQL'] == ''){
	   			$deleteFrom_to = mysqli_connect("localhost", "root", "Password123") or die("could not connect ". mysqli_connect_error());
				$delete1 = "DELETE FROM MILBER.MilberMessages
							WHERE (user_from_SQL = '$curentUserId' OR user_to_SQL = '$curentUserId')
							AND (user_from_SQL = '$var' OR user_to_SQL = '$var')";
			   	$results1 = mysqli_query($deleteFrom_to, $delete1) or die("Could not connect" . mysqli_error($deleteFrom_to));
			   	mysqli_close($deleteFrom_to);

	   		} else if($msgDeleteFor == $s['to_from_user_SQL'] && $s['from_to_user_key_SQL'] == ''){
	   			$deleteTo_from = mysqli_connect("localhost", "root", "Password123") or die("could not connect ". mysqli_connect_error());
				$delete2 = "DELETE FROM MILBER.MilberMessages
							WHERE (user_from_SQL = '$curentUserId' OR user_to_SQL = $curentUserId)
							AND (user_from_SQL = $var OR user_to_SQL = $var)";
			   	$results2 = mysqli_query($deleteTo_from, $delete2) or die("Could not connect" . mysqli_error($deleteTo_from));
			   	mysqli_close($deleteTo_from);

			} else if($msgDeleteFor == $s['from_to_user_key_SQL']){
				echo $msgDeleteFor;
	   			$deleteFrom_to_user = mysqli_connect("localhost", "root", "Password123") or die("could not connect ". mysqli_connect_error());
				$delete3 = "UPDATE MILBER.MilberMessages
							SET from_to_user_key_SQL = ''
							WHERE from_to_user_key_SQL = $msgDeleteFor";
			   	$results3 = mysqli_query($deleteFrom_to_user, $delete3) or die("Could not connect" . mysqli_error($deleteFrom_to_user));
			   	mysqli_close($deleteFrom_to_user);

	   		} else if($msgDeleteFor == $s['to_from_user_SQL']){
	   			//echo $msgDeleteFor;
	   			$deleteTo_from_user = mysqli_connect("localhost", "root", "Password123") or die("could not connect ". mysqli_connect_error());
				$delete4 = "UPDATE MILBER.MilberMessages
							SET to_from_user_SQL = ''
							WHERE to_from_user_SQL = $msgDeleteFor";
			   	$results4 = mysqli_query($deleteTo_from_user, $delete4) or die("Could not connect" . mysqli_error($deleteTo_from_user));
			   	mysqli_close($deleteTo_from_user);
	   		} else {

	   		}




	   	}

	}

?>