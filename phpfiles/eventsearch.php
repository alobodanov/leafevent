<?php $title_name = "Leafevent"; ?>
<?php require_once "milbertoolbar.php"; ?>
<?php require_once "Library.php" ?>
<?php
		$seeAllEvent = new DBLink();
		$showQuesry = "SELECT * FROM v_event";
		$all_result=$seeAllEvent->query($showQuesry);

		$typelink = new DBLink();
        $types = "SELECT * FROM v_eventtype ORDER BY id_SQL";
        $typeResult = $typelink->query($types);
        $event_result = null;
        $filter_count = null;
        $eventResult = null;
        $location_search = null;
		if($_POST){
			$type_search = null;
            $name_search = null;
            $type_search = htmlentities(trim($_POST['event_type']));
            $location = htmlentities(trim($_POST['event_search']));
            if($type_search == "Search by type"){
                $type_search = null;
            }
            $name_search = htmlentities(trim($_POST['event_name_type']));
            if($type_search && $name_search){
                $search_for_name = mysqli_connect("localhost","eafeventroot","93milbefwsdfjyrhnt3252skhsnsser93@","MILBER") or die ("could not connect " . mysqli_connect_error());
                $name_search = mysqli_real_escape_string($search_for_name, $name_search);
                $type_search = mysqli_real_escape_string($search_for_name, $type_search);
                $eventS = "SELECT * FROM v_event WHERE event_Name_SQL LIKE '{$name_search}%' OR event_type_id_SQL = '$type_search'";
                $eventResult = mysqli_query($search_for_name, $eventS) or die ("could not query" . mysqli_error($search_for_name));
                $filter_count = mysqli_num_rows($eventResult);
            } else if($type_search && $name_search == ""){
                $search_for_name = mysqli_connect("localhost","eafeventroot","93milbefwsdfjyrhnt3252skhsnsser93@","MILBER") or die ("could not connect " . mysqli_connect_error());
                $name_search = mysqli_real_escape_string($search_for_name, $name_search);
                $type_search = mysqli_real_escape_string($search_for_name, $type_search);
                $eventS = "SELECT * FROM v_event WHERE event_type_id_SQL = '$type_search'";
                $eventResult = mysqli_query($search_for_name, $eventS) or die ("could not query" . mysqli_error($search_for_name));
                $filter_count = mysqli_num_rows($eventResult);
            } else if($type_search == "" && $name_search){
                $search_for_name = mysqli_connect("localhost","eafeventroot","93milbefwsdfjyrhnt3252skhsnsser93@","MILBER") or die ("could not connect " . mysqli_connect_error());
                $name_search = mysqli_real_escape_string($search_for_name, $name_search);
                $type_search = mysqli_real_escape_string($search_for_name, $type_search);
                $eventS = "SELECT * FROM v_event WHERE event_Name_SQL LIKE '{$name_search}%'";
                $eventResult = mysqli_query($search_for_name, $eventS) or die ("could not query" . mysqli_error($search_for_name));
                $filter_count = mysqli_num_rows($eventResult);
            }
		}
?>
	<div class="main_filed_search">
		<form method="POST" class="event_search_form">
			<input type="test" name="event_name_type" placeholder="Search for event name" value="<?php if(isset($_POST['event_name_type']))echo $name_search;?>" />
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
			<input type="text" name="event_location" placeholder="Where it's located" value="<?php if(isset($_POST['event_location']))echo $location_search;?>"/>
			<input type="submit" name="search" />
		</form>
		<script>
		$(document).ready(function() {
            $("#bg > img#1").fadeIn(300);
            checkSize();
        });
		function checkSize() { 
            var num = document.getElementsByClassName("event-found-name").length;
            var i;
            for(i = 0; i < num; i++){
                if(document.getElementsByClassName("event-found-name")[i].innerHTML.length > 32){
                    var text = document.getElementsByClassName("event-found-name")[i].innerHTML.substring(0,24);
                    text = text + "...";
                    document.getElementsByClassName("event-found-name")[i].innerHTML = text;
                }
            }
            var num2 = document.getElementsByClassName("event-found-address").length;
            var j;
            for(j = 0; j < num2; j++){
                if(document.getElementsByClassName("event-found-address")[j].innerHTML.length > 37){
                    var text = document.getElementsByClassName("event-found-address")[j].innerHTML.substring(0,37);
                    text = text + "...";
                    document.getElementsByClassName("event-found-address")[j].innerHTML = text;
                }
            }
        };
		</script>
		<div class="resurch_result">
<?php
			if($eventResult == null){
				while($r = mysqli_fetch_array($all_result)){
					$event_id = $r['event_id_SQL'];
					$event_name = $r['event_Name_SQL'];
					$event_address = $r['event_address_SQL'];
					$event_file_pic = $r['event_pic_folder_SQL'];
					$event_pic = $r['event_picture_SQL'];
					$event_admin1 = $r['admin1_confirm_SQL'];
					$event_admin2 = $r['admin2_confirm_SQL'];
					if($event_admin1 == 'Y' && $event_admin2 == 'Y'){
?>
						<a href="viewEvent.php?event=<?=$event_id?>">
								<div class="img-div"><img  src="../milberUserPhotos/eventPhotos/<?=$event_file_pic.'/resized_'.$event_pic;?>" class="looked-up-event-pic" ></div>
								<p class="event-found-name"><?= $event_name;?></p>
								<p class="event-found-address"><?= $event_address;?></p>
						</a>
<?php
					}
				}
			} else {
				if($filter_count != 0){
					while($r = mysqli_fetch_array($eventResult)){
						$event_id = $r['event_id_SQL'];
						$event_name = $r['event_Name_SQL'];
						$event_address = $r['event_address_SQL'];
						$event_file_pic = $r['event_pic_folder_SQL'];
						$event_pic = $r['event_picture_SQL'];
						$event_admin1 = $r['admin1_confirm_SQL'];
						$event_admin2 = $r['admin2_confirm_SQL'];
						if($event_admin1 == 'Y' && $event_admin2 == 'Y'){
?>
							<a href="viewEvent.php?event=<?=$event_id?>">
								<div class="img-div"><img  src="../milberUserPhotos/eventPhotos/<?=$event_file_pic.'/resized_'.$event_pic;?>" class="looked-up-event-pic" ></div>
								<p class="event-found-name"><?= $event_name;?></p>
								<p class="event-found-address"><?= $event_address;?></p>
							</a>
<?php
						}
					}
				} else {
                    echo "<br /><div class='error-msg-for-eventsearch'>We could not find anything based on your search.</div>";
               }
			}
?>
		</div>
	</div>