<?php

	if(isset($_GET['id'])){
		$showIMGid = $_GET['id'];
		$imageData = null;
		$imgType = null;
		$imgShow = mysqli_connect("localhost","root","Password123") or die ("Could not connect to DataBase". mysqli_connect_error());
		$findIMG = "SELECT * FROM MILBER.MilberPhotos WHERE id_SQL = '$showIMGid'";
		$result = mysqli_query($imgShow,$findIMG) or die("Could not query ". mysqli_error($imgShow));
		//$count = mysqli_num_rows($result);
		mysqli_close($imgShow);

		//if($count === 1){
			while($get = mysqli_fetch_assoc($result)){

				$imageData = $get["image_SQL"];
				$imgType = $get["image_type_SQL"];
			}
			header("content-type: image/$imgType");
			echo $imageData;

		//} else {
		//	echo "Profile does not exist";
		//	exit();
		//}			
    } else {
    	echo "error";
    }

?>