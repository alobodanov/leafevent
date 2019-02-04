<?php $title_name = "User Name"; 
//On this page, a user should be able to view thier calendar. Calendar will display 
//events that they have assigned/registered to. 
?>
<?php require "milbertoolbar.php"; 
$curentPage = "userLookMain.php" //curent page?>
<?php require "UsercontentLeftside.php";
	if($_SESSION['userE_login'] == "" ){
   		header('Location: logout.php');
   		die();
   	}
?>
			<aside id="UserPage">
				<h6>Your calendar events</h6>
				<br />
				<table class="calendar">
					<tr>
						<th>Today</th>
						<th>Events</th>
						<th>Time</th>
						<th>Description</th>
						<th>Location</th>
					</tr>
					<tr>
						<td class="WeekDay" id="DaySunday">Sunday</td>
						<td>Name of the event and more information will be in here</td>
						<td>Time it starts</td>
						<td>About</td>
						<td>Where</td>
					</tr>
					<tr>
						<td class="WeekDay" id="DayMonday">Monday</td>
					</tr>
					<tr>
						<td class="WeekDay" id="DayTuesday">Tuesday</td>
					</tr>
					<tr>
						<td class="WeekDay" id="DayWednesday">Wednesday</td>
					</tr>
					<tr>
						<td class="WeekDay" id="DayThursday">Thursday</td>
					</tr>
					<tr>
						<td class="WeekDay" id="DayFriday">Friday</td>
					</tr>
					<tr>
						<td class="WeekDay" id="DaySaturday">Saturday</td>
					</tr>
				</table>

			</aside>
			
<?php require "milberMenuEnd.php"; ?>