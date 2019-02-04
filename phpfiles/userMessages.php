<?php require "milbertoolbar.php"; ?>
<?php require_once "Library.php" ?>
<?php
	if($_SESSION['userE_login'] == "" ){
   		header('Location: logout.php');
   		die();
   	}


   	if($_POST){
   		if(!preg_match("/^[a-z0-9\,\<\>\.\%\;\(\)\'\ \!\?\:\"]*$/i", htmlentities(trim($_POST['messagefld'])))){
   			$check = false;
   			$error = "Now valid input";
   			if(htmlentities(trim($_POST['messagefld'])) == ""){
   				$error = "Please enter something first";
   			}
   		}
   	}

   	if($_POST && $check){

   	}

?>
	<div class="messageContainer">
		<div class="messageToolB">
			<span>Talking To Name</span><button type="text" name="delete">Delete conversation</button>
		</div>
		<div class="table-col-2">
			<div class="talkingTo">

			</div>

			<div class="messageBody">
				<div class="messageWindow">

				</div>
				<div class="messageForm">
					<form method="POST" class="mesformSub">
						<textarea cols="50" name="messagefld"></textarea>
						<input type="submit"/>
					</form>
				</div>


			</div>
		</div>
	</div>