<?php
	require_once "../Library.php";

	/*$lookingAt = null;
	if(isset($_GET['see'])){
		$lookingAt = htmlentities(trim($_GET['see']));
	}*/
	/*$lookingAt = null;
   	if(isset($_GET['u'])){
   		$lookingAt = htmlentities(trim($_GET['u']));
   	}*/
	$JsonConnection = new DBLink();
	$JsonQuesry =  "select * from v_user_interests_event v ORDER BY event_Start_Year_on_SQL DESC, event_Start_Month_on_SQL, event_Start_Day_on_SQL";
	$result=$JsonConnection->query($JsonQuesry);

	$response = array();

	$type = array();
	$name = array();
	$markers = array();
	$address = array();
	$description = array();
	$marks = array();
	$x = array();
	$y = array();
	$html = "";
	$todayYear = date("Y");
	$todayMonth = date("m");
	$todayDay = date("d");


	while($row = mysqli_fetch_array($result)){
		$type = $row['event_Type_SQL'];
		$name = $row['event_Name_SQL'];
		$maker = $row['who_is_making_event_SQL'];
		$address = $row['event_address_SQL'];	
		$description = $row['event_description_SQL'];
		$x = $row['event_x_num_SQL'];
		$y = $row['event_y_num_SQL'];
		$eventEndDay = $row['event_End_Day_on_SQL'];
		$eventEndMonth = $row['event_End_Month_on_SQL'];
		$eventEndYear = $row['event_End_Year_on_SQL'];
		//$id_of_maker = $row['event_POsted_by_id_SQL'];

		if(($eventEndYear < $todayYear) && ($eventEndMonth < $todayMonth) && ($eventEndDay < $todayDay)){

		} else {

			//JSON
			$markers[] = array('type'=> $type, 'name'=>$name, 'maker'=> $maker, 'address'=> $address, 'description'=>$description,'lat'=> $x, 'lng'=> $y);

			$monthNameStart = $row['event_Start_Month_on_SQL'];
			$monthNameStart = date('M', mktime(0,0,0, $monthNameStart, 10));
			$monthNameEnd = $row['event_End_Month_on_SQL'];
			$monthNameEnd = date('M', mktime(0,0,0, $monthNameEnd, 10));

			
			$html .= "
			<div class=\"friendProfPage\">" .
			 "<img class='profileUserEventPic' src='../images/2323.png' alt='img'/><span class='eventNameUserProf'>".  $name."</span><span class='eventTypeUserProf'>".$type . "</span>".
			"<div class='startEndDateUserProf'>Date: ".$row['event_Start_Day_on_SQL'].' '.$monthNameStart.' '.$row['event_Start_Year_on_SQL'].'-'
			.$eventEndDay.' '.$monthNameEnd.' '.$eventEndYear."</div><div class='addressUserProf'>".$address."</div></div>";
		}
	}
	echo $html;

	$response = "markers = ". json_encode($markers) . ";";
	$fp = fopen('../../userViewMarkers.json', 'w');
	fwrite($fp,$response);
	fclose($fp);




?>