<?php $title_name = "User Name"; ?>
<?php require "milbertoolbar.php"; 
$curentPage = "userPhotos.php" //curent page?>
<?php require "UsercontentLeftside.php";
	if($_SESSION['userE_login'] == "" ){
   		header('Location: logout.php');
   		die();
   	}
?>

				<aside id="rightTools">
				<script> 
					function toggleNavPanel(element, speed){ 
						$(element).toggle(speed);
						} 
				</script>  
					<div class="sections_btn_holder"> 
						<p onclick="toggleNavPanel('#Option-7', 300);">Albums</p>
					</div> 
					<div id="Option-7" style="display:none">
						hello 7





					</div>
					<hr />
					<div class="sections_btn_holder"> 
						<p onclick="toggleNavPanel('#Option-8', 300);">Events with your friends</p>
					</div> 
					<div id="Option-8" style="display:none">
						hello 8





					</div>
					<hr />
					<div class="sections_btn_holder"> 
						<p onclick="toggleNavPanel('#Option-9', 300);">Videos</p>
					</div> 
					<div id="Option-9" style="display:none">
						hello 9





					</div>
					<hr />
			</aside>
			<?php require "milberMenuEnd.php"; ?>
