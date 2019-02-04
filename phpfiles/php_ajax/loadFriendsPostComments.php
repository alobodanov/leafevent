<?php
	require_once "../Library.php"; 
	if (isset($_POST['postId'])){
		$post_id = $_POST['postId'];
		$dbLink = new DBLink();
		$q2 = "SELECT vpc.* FROM (SELECT @f_param:=".$post_id." p1) parm , v_post_comments vpc ORDER BY comment_date_added_SQL DESC LIMIT 15";
			$resultC = $dbLink->query($q2);
		$htmlC = "<table class=\"table-striped\"><tbody>";
  		while ($rC = mysqli_fetch_array($resultC)) {
			$htmlC .= "<tr><td><span class='userNameDate'><span class='userPoster'>".$rC['poster']."</span><span class='userCommentDate'>".$rC['comment_date_added_SQL']."</span></span><br/><div class='userCommentBody'>".substr($rC['comment_body_SQL'],0,250)."</div></td></tr>";
		}
		$htmlC .= "<tr></tr></tbody></table>";
		echo $htmlC;
}