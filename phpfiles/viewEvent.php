<?php require "milbertoolbar.php"; ?>
<?php require_once "Library.php"; ?>
<?php
	if($_SESSION['userE_login'] == "" ){
   		header('Location: logout.php');
   		die();
   	}

   	$viewEvent = null; //eventId
   	$address = null;
   	$type = null;
	$name = null;
	$maker = null;
   	$startDay = null;
	$startMonth = null;
	$startYear = null;
	$endDay = null;
	$endMonth = null;
	$endYear = null;
	$description = null;
	$price = null;
	$tickets = null;
	$home = null;
	$cell = null;
	$work = null;
	$efile = null;
	$eprofPicture = null;
	$makerId = null;
	$imagePath_event = null;
   	if(isset($_GET['event'])){
   		$seeEvent = new DBLink();
   		$viewEvent = mysqli_real_escape_string($seeEvent->conn(), htmlentities(trim($_GET['event']))); 
		$eventQuesry =  "SELECT * from v_event WHERE event_id_SQL = '$viewEvent' AND admin1_confirm_SQL = 'Y' AND admin2_confirm_SQL = 'Y'";
		$result=$seeEvent->query($eventQuesry);

		$attending_event = new DBLink();
		$query_list = "SELECT * FROM Invoice WHERE user_id_SQL = '$userID' AND event_id_SQL = '$viewEvent'";
		$resultatt = $attending_event->query($query_list);
		$count_attending = mysqli_num_rows($resultatt);
		while($e = mysqli_fetch_assoc($result)){
			$address = $e['event_address_SQL'];
			$type = $e['event_type_id_SQL'];
			$type_type =  "SELECT * from v_eventtype WHERE id_SQL = '$type' LIMIT 1";
			$typeresult=$seeEvent->query($type_type);
			while($typee = mysqli_fetch_array($typeresult)){
				$type = $typee['type_name_SQL'];
			}
			$name = $e['event_Name_SQL'];
			$maker = $e['who_is_making_event_SQL'];
			$makerId = $e['event_Posted_by_id_SQL'];
			$start_time = $e['event_start_datetime_SQL'];
			$end_time = $e['event_end_datetime_SQL'];
			$description = $e['event_description_SQL'];
			$eventpp = $see['event_Private_Public_SQL'];
			$price = $e['event_price_SQL'];
			$efile = $e['event_pic_folder_SQL'];
			$eprofPicture = $e['event_picture_SQL'];
			$tickets = $e['event_tickets_SQL'];
			$attenging_number = $e['attending_event_SQL'];
			$home = $e['event_home_phone_SQL'];
			$cell = $e['event_cell_phone_SQL'];
			$work = $e['event_work_phone_SQL'];
			if($efile == null && $eprofPicture == null){
				$imagePath_event = "<div><img class='view-event-pic' src='../Images/no-event-pic.png' alt='img' /></div>";
			} else {
				$imagePath_event = "<div><img class='view-event-pic' src='../milberUserPhotos/eventPhotos/$efile/$eprofPicture' alt='img' /></div>";
			}
		}
   	}
   	$ticketsBuy = 0;

   	if($_POST){
   		$link = new DBLink();
   		$buying = mysqli_real_escape_string($link->conn() ,htmlentities(trim($_POST['ticket-to-buy'])));
   		$num1 = rand(0000,9999);
   		$num2 = rand(0000,9999);
   		for($i = 0; $i < 2; $i++){
   			$num1 .= mt_rand(0,9);
   			$num2 .= mt_rand(0,9);
   		}
   		$num3 = $num1 . $num2;
   		//11096983785

   		if($buying <= $tickets){
   			$pay = 0;
   			$tax = 0;
   			$total = 0;
   			if($price != 0){
   				$pay = $price * $buying;
   				$total = number_format($pay, 2);
   			}
   			$created_on = date("Y-m-d");
   			$ticketsLeft = $tickets - $buying; // will update database with a new number of tickets
   			$eventUpdate = new DBLink();
   			$updateTickets = "UPDATE milberevents SET event_tickets_SQL = '$ticketsLeft' WHERE event_id_SQL = '$viewEvent'";
   			$queryTick = $eventUpdate->query($updateTickets);
   			$insert = "INSERT INTO Invoice VALUES('','$viewEvent','$userID','$num3','$created_on','$buying','$tax','$total','','')";
   			//$insert = "INSERT INTO InvoiceLine VALUES('','$num3','$viewEvent','$buying','$pay','$created_on')";
   			$query = $link->query($insert);
   			$invoiceLine = new DBLink();
   			$linkquery = "INSERT INTO InvoiceLine (Invoice_id_SQL, event_id_SQL, subtotals_SQL, dateCreated_SQL)
   													SELECT id_SQL, event_id_SQL, totals_SQL, dateCreated_SQL
   														FROM Invoice
   															WHERE user_id_SQL = '$userID' 
   															AND event_id_SQL = '$viewEvent'";
   			$query2 = $invoiceLine->query($linkquery);
   			$Name = "Leafevent"; //senders name 
			$email = "attending@leafevent.com"; //senders e-mail adress 
			$recipient = $useremail; //recipient 
			$mail_body = "Congratulations!\n You now have $buying tickets for $name, that starts on $start_time.\n Order number: $num3"; //mail body 
			$subject = "Leafevent registration"; //subject 
			$header = "From: ". $Name . " <" . $email . ">\r\n"; //optional headerfields 

			mail($recipient, $subject, $mail_body, $header);

   			header('Location: viewEvent.php?event='.$viewEvent);
   		}
   	}
?>
		<script>
			$(document).ready(function() {
	            checkSize();
	        });
	        
	        function calculate(){
				var price = document.getElementsByClassName('view-event-price')[0].innerHTML;
				var num = document.ticketForm.ticket.selectedIndex;
				if(price != "Free"){
					var pay = 0;
					var tax = 0;
					var total = 0;
					pay = num * price;
					tax = parseFloat(pay * 0.13);
					total = pay + tax;
					total = total.toFixed(2);
					//total = '$ ' + total;
					total = total.toString();
					total = '$ ' + total;
					document.getElementsByClassName('hidden-price')[0].innerHTML =  total;
				} else {
					document.getElementsByClassName('hidden-price')[0].innerHTML = "Free";
				}
			}

			function checkSize() { 
                var num = document.getElementsByClassName("eventnameView").length;
                var i;
                for(i = 0; i < num; i++){
                    if(document.getElementsByClassName("eventnameView")[i].innerHTML.length > 32){
                        var text = document.getElementsByClassName("eventnameView")[i].innerHTML.substring(0,24);
                        text = text + "...";
                        document.getElementsByClassName("eventnameView")[i].innerHTML = text;
                    }
                }
            };
		</script>
		<div class="viewEvent-login-view">
			<p class="view-event-name" id="event_name"><?=$name;?></p>
			<div class="view-event-picture-description">
				<p class="view-event-desc"><?=$description;?></p>
<?= $imagePath_event; ?>
			</div>
			<div class="view-event-content-moreEvents">
				<div class="view-event-content">
					<p class="view-event-addr"><?=$address;?></p>
					<p class="view-event-time">Starting time <?= $start_time;?> Ending on <?= $end_time;?></p><br />
					<span>Available tickets: </span><p class="view-event-ticket"><?=$tickets;?></p><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Attending <?= $attenging_number;?></span>
					<p class="view-event-price"><?php if($price==0) echo "Free"; else echo " ".$price; ?></p>
					<p class="view-event-type">Type <?= $type; ?></p>
					<p class="view-event-public-privat"><?= $eventpp; ?></p>
					<p class="view-event-maker">By <?=$maker;?></p>
					<p>Contact: </p>
				</div>
				<script> 
                    function toggleNavPanel(element, speed){ $(element).toggle(speed);} 
                </script>
<?php
				if($makerId != $userID){
?>
					<p class="want-to-go-event" onclick="toggleNavPanel('#view-event-registration', 300);">Register</p>
<?php
				if($count_attending > 0){
					while($attending_not = mysqli_fetch_array($resultatt)){

						$ticket_num += $attending_not['quantity_SQL'];
						$total_price = $attending_not['totals_SQL'];
					}
					echo "<p class='info-have-ticket'>You already have ".$ticket_num." tickets from this event. The total is $".$total_price."</p>";
				}
?>
					<form method="POST" id="view-event-registration" style="display:none" name="ticketForm">
						<span class="side-left">LEAFEVENT TICKETS</span><br />
						<hr />
						<div>
							<span class="registration-title">Number of tickets&nbsp;&nbsp;&nbsp;&nbsp;Left</span><br />
							<select name="ticket-to-buy" id="ticket" onchange="calculate()">
								<script>
								var num = document.getElementsByClassName('view-event-ticket')[0].innerHTML;
								//alert(num);
									for(var i = 0; i <= num; i++){
										document.write("<option value='"+ i +"'>"+ i +"</option>");
									}
								</script>
							</select>
							<span class="number-soldout"><?php if($tickets == 0) echo "<span class='sold-out'>Sold Out</span>"; else echo $tickets;?></span>
							<input type="submit" name="" value="Ready" /><br />
							<span class="hidden-price"></span>
						</div>
						<span class="side-rigth">ADMIT ONE</span>
					</form>
<?php
				}
?>
				<p>More events by the Host</p><br />
				<div class="more-events-from-user">
					<br />
<?php
					$selectUserEvents = new DBLink();
					$selectedEventsQuesry =  "SELECT * from v_event WHERE event_Posted_by_id_SQL = '$makerId' AND admin1_confirm_SQL = 'Y' AND admin2_confirm_SQL = 'Y'";
					$results=$selectUserEvents->query($selectedEventsQuesry);

						while($ev = mysqli_fetch_assoc($results)){
							$id = $ev['event_id_SQL'];
							$pic = $ev['event_picture_SQL'];
							$fol = $ev['event_pic_folder_SQL'];
							if($pic == null || $fol == null){
								$moreimagePath = "<div><img class='view-more-event-pic' src='../Images/no-event-pic.png' alt='img' /></div>";
							} else {
								$moreimagePath = "<div><img class='view-more-event-event-pic' src='../milberUserPhotos/eventPhotos/$fol/$pic' alt='img' /></div>";
							}
							echo "<a href='viewEvent.php?event=$id'><div class='event-i'>".$moreimagePath."<span class='eventnameView'>".$ev['event_Name_SQL']."</span></div></a>";
						}
?>
				</div>
			</div>
			</div>
		</div>
	</html>
</body>