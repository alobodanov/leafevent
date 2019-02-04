<?php
session_start();
require_once "Library.php";
if(isset($_COOKIE['Leafevent_user'])){
	unset($_COOKIE['leafevent']);
	setcookie("Leafevent_user", null, time() - (60 * 10));
}
$userIdLogOut = $_SESSION['user_id'];
$fp = fopen('../markers.json', 'w');
fwrite($fp," ");
fclose($fp);
$online = new DBLink();
$onOff = "UPDATE MILBER.MilberUserInfo SET online_SQL = 'n' WHERE id_SQL = '$userIdLogOut'";
$onOffUpdate = $online->query($onOff);
session_destroy();
header("Location: index.php");
?>