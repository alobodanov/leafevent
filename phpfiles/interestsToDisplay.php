<?php $title_name = "User Name";?>
<?php require "milbertoolbar.php";?>
<?php require_once "Library.php";?>
<?php
	if($_SESSION['userE_login'] == ""){
   		header('Location: logout.php');
   	}
	$dbLink = new DBLink();
	$eventTopic_select = "SELECT id_SQL, topic_name_SQL FROM MILBER.EventTopic ORDER BY id_SQL";
	$eventTopic_list = $dbLink->query($eventTopic_select);

	$dbLink = new DBLink();
	$eventType_select = "SELECT id_SQL, type_name_SQL FROM MILBER.EventType ORDER BY id_SQL";
	$eventType_list = $dbLink->query($eventType_select);

	$dbLink = new DBLink();
	$events_user_likes = "SELECT * FROM MILBER.UserInterests WHERE user_id_SQL = '$userID'";
	$event_user_select_list = $dbLink->query($events_user_likes);

	if($_POST){
		$aCC = null;
		$bSC = null;
		$aCC = $_POST['CC'];
		$bSC = $_POST['SC'];
		$type_list = array();
		$topic_list = array();
		while($type_check = mysqli_fetch_array($event_user_select_list)){
			$type_list[] = $type_check['type_SQL'];
			$topic_list[] = $type_check['topic_SQL'];
		}
		if(empty($aCC)){
		} else {
			$num = count($aCC);
			for($i = 0; $i < $num; $i++){
				$temp1 = $aCC[$i];
				if(in_array($temp1, $type_list)){
				} else {
					$dbLink = new DBLink();
					$user_type_Insert = "INSERT INTO MILBER.UserInterests VALUES('','$userID','$temp1','')";
					$result1 = $dbLink->query($user_type_Insert);
				}
			}
		}
		if(empty($bSC)){
		} else {
			$num1 = count($bSC);
			for($j = 0; $j < $num1; $j++){
				$temp2 = $bSC[$j];
				if(in_array($temp2, $topic_list)){
				} else {
					$dbLink = new DBLink();
					$user_topic_Insert = "INSERT INTO MILBER.UserInterests VALUES('','$userID','','$temp2')";
					$result2 = $dbLink->query($user_topic_Insert);
				}
			}
		}
		header('Location: home.php');
	}


?>
	<p class="expo-for-interest-list">By selecting any of this options, you are choosing what type and topic of events you wish to see on the map and event list.</p>
	<form method="POST" class="new-interest-list">
		<ul class="important-type">
		Type
	<?php
		$user_selected_list_type = array();
		$user_selected_list_topic = array();
		while($user_int = mysqli_fetch_array($event_user_select_list)){
			$user_selected_list_type[] = $user_int['type_SQL'];
			$user_selected_list_topic[] = $user_int['topic_SQL'];

		}
		while($row1 = mysqli_fetch_array($eventType_list)){
			if(in_array($row1['id_SQL'], $user_selected_list_type)){
				echo "<li><input type='checkbox' name='CC[]' value=".$row1["id_SQL"]." checked='checked'/>".$row1["type_name_SQL"]."</li>";
			} else {
				echo "<li><input type='checkbox' name='CC[]' value=".$row1["id_SQL"]."/>".$row1["type_name_SQL"]."</li>";
			}
		}
	?>
		</ul>
		<ul class="important-topic">
		Topic
	<?php
		while($row2 = mysqli_fetch_array($eventTopic_list)){
			if(in_array($row2['id_SQL'], $user_selected_list_topic)){
				echo "<li><input type='checkbox' name='SC[]' value=".$row2['id_SQL']." checked='checked' />".$row2['topic_name_SQL']."</li>";
			} else {
				echo "<li><input type='checkbox' name='SC[]' value=".$row2['id_SQL']."/>".$row2['topic_name_SQL']."</li>";
			}
		}
	?>
		</ul>
		<input type="submit" name="Uppdate" value="Save My Interests" />
	</form>