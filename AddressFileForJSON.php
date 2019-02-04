<?php
	$JsonConnection = mysqli_connect("localhost","root","Password123") or die ("Could not connect to DataBase". mysqli_connect_error());
	$JsonQuesry = "SELECT event_Type_SQL, who_is_making_event_SQL, event_address_SQL, event_x_num_SQL, event_y_num_SQL FROM MILBER.MilberEvents";
	$result = mysqli_query($JsonConnection, $JsonQuesry) or die('Could not query' . mysqli_error());
	mysqli_close($JsonConnection);

	$response = array();

	$type = array();
	$markers = array();
	$address = array();
	$marks = array();
	$x = array();
	$y = array();

	while($row = mysqli_fetch_array($result)){
		$type = $row['event_Type_SQL'];
		$maker = $row['who_is_making_event_SQL'];
		$address = $row['event_address_SQL'];
		$x = $row['event_x_num_SQL'];
		$y = $row['event_y_num_SQL'];

		$markers[] = array('type'=> $type, 'maker'=> $maker, 'address'=> $address, 'lat'=> $x, 'lng'=> $y);
	}


	$response['markers'] = $markers;

	$fp = fopen('test.json', 'w');
	fwrite($fp, json_encode($response));
	fclose($fp);

?>