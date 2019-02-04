<?php
	require_once "../Library.php";	
	$rest_json = file_get_contents("php://input");
	$_POST = json_decode($rest_json, true);
	$html = "";
	if (isajax() && isset($_POST['type'])) {
		if($_POST['type']=='1'){//event type
			$JsonQuesry =  "SELECT v.* FROM v_eventtype v ORDER BY v.type_name_SQL";
			$promt = "Select the type of event";
			$selectorId='id_event_type';
		}
		elseif($_POST['type']=='2'){//event topic
			$JsonQuesry =  "SELECT v.* FROM v_eventtopic v ORDER BY v.topic_name_SQL";
			$promt = "Select a topic";
			$selectorId='id_event_topic';
		}
		elseif($_POST['type']=='3' && isset($_POST['data'])){//event subtopic
			$JsonQuesry =  "SELECT v.* FROM (SELECT @f_param:=".$_POST['data']." p1) parm1, v_eventsubtopic v ORDER BY v.name_SQL;";
			$promt = "Select a sub-topic";
			$selectorId='id_event_subtopic';
		}
		if(isset($JsonQuesry)){
			$JsonConnection = new DBLink();	
			$result=$JsonConnection->query($JsonQuesry);			
			if ($rowCount = mysqli_num_rows($result)){
				
				$html .= "<select id='".$selectorId."' name='".$selectorId."'";
				if ($_POST['type']=='2'){
					 $html .= " onchange=\"changeDLLValue()\">";
				}
				else{
					$html .= ">";
				}
				$html .= "<option value=\"-1\" selected=\"selected\">".$promt."</option>";
				
				while($row = mysqli_fetch_array($result)){
					$html .= "<option value=".$row[0].">".$row[1]."</option>"; 
				}
				$html .= "</select>";
			}
		}
	}
	echo $html; 
?>