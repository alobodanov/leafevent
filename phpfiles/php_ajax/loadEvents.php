<?php
	require_once "../Library.php"; 
	if(!isset($_POST['userId'])){ 
		echo "--";
		die();
	}
	$user_id = htmlentities(trim($_POST['userId']));
	$what_user_likes = new DBLink();
	$user_likes_type_topic = "SELECT * FROM UserInterests WHERE user_id_SQL = '$user_id'";
	$like_result=$what_user_likes->query($user_likes_type_topic);
	$number_of_likes = mysqli_num_rows($like_result);

	$JsonConnection = new DBLink();
	$JsonQuesry =  "SELECT vui.* from (SELECT @f_param:=".$_POST['userId']." p1) parm , v_user_interests_event vui ORDER BY event_start_datetime_SQL";
	$result=$JsonConnection->query($JsonQuesry);
	$number_of_events = mysqli_num_rows($result);
	
	$response = array();
	$type = array();
	$name = array();
	$markers = array();
	$address = array();
	$description = array();
	$marks = array();
	$x = array();
	$y = array();
	$efile = array();
	$eprofPicture = array();
	$html = "";
	$todayYear = date("Y");
	$todayMonth = date("m");
	$todayDay = date("d");
	if($number_of_likes === 0){
		$html .= "<a href='eventsearch.php' class='moreeventshome eventShowAfterMap' style='float: left;'><div class='eventDescOnSide'><p class=''>Search for more events</p></div></a>";
		$html.= "<p class='user-has-no-selection-no-events'>We are not sure what to show. Please select from the list the things you <a href='interestsToDisplay.php'>wish to see</a></p>";
	} else {
		if($number_of_events === 0){
			$html .= "<a href='eventsearch.php' class='moreeventshome eventShowAfterMap' style='float: left;'><div class='eventDescOnSide'><p class=''>Search for more events</p></div></a>";
			$html.="<p class='user-has-no-selection-no-events'>We could not find events that best suits you. So make one of <a href='creaneNewEvent.php'>your own.</a></p>";
		} else {
			//$html .= "<a href='eventsearch.php' class='moreeventshome eventShowAfterMap'><div class='eventDescOnSide'><p class=''>Search for more events</p></div></a>";
			while($row = mysqli_fetch_array($result)){
				$eventId = $row['event_id_SQL'];
				$type = $row['type_name_SQL'];
				$name = $row['event_Name_SQL'];
				$maker = $row['who_is_making_event_SQL'];
				$address = $row['event_address_SQL'];
				$description = $row['event_description_SQL'];
				$x = $row['event_x_num_SQL'];
				$y = $row['event_y_num_SQL'];
				$efile = $row['event_pic_folder_SQL'];
				$eprofPicture = $row['event_picture_SQL'];
				$eventDateStart =($row[10]);
				$eventDateEnd = ($row[11]);

				/*$eventEndDay = $row['event_End_Day_on_SQL'];
				$eventEndMonth = $row['event_End_Month_on_SQL'];
				$eventEndYear = $row['event_End_Year_on_SQL'];*/

				if($efile == null && $eprofPicture == null){
						$imagePath = "<div class='user-events-main'><img class='user-events-main-event' src='../Images/no-event-pic.png' alt='img' /></div>";
				} else {
					$imagePath = "<div class='user-events-main'><img class='user-events-main-event' src='../milberUserPhotos/eventPhotos/$efile/resized_$eprofPicture' alt='img' /></div>";
				}
		//$dateTime = DateTime::createFromFormat('m/d/Y H:i', $dt);

				if(strlen($name) > 30){
					$name = substr($name,0,30);
					$name = $name ."...";
				}
				$name = ucfirst($name);
			
					//JSON
					$markers[] = array('type'=> $type, 'name'=>$name, 'maker'=> $maker, 'address'=> $address, 'description'=>$description,'lat'=> $x, 'lng'=> $y);
		/*
					$monthNameStart = $row[10];

					$monthNameStart = date('M', mktime(0,0,0, $monthNameStart, 10));
					$monthNameEnd = $row['event_end_datetime_SQL'];
					$monthNameEnd = date('M', mktime(0,0,0, $monthNameEnd, 10));
		*/
					$html .= "<a href='viewEvent.php?event=$eventId' class='eventShowAfterMap'><div>" .
					 "$imagePath<span class='eventName'>". $name."</span><span class='eventType'>".$type . "</span></div></a><br /><br />";
			}
		}
	}
	echo $html;
	$response = "markers = ". json_encode($markers) . ";";
	$fp = fopen('../../markers.json', 'w');
	fwrite($fp,$response);
	fclose($fp);
?>