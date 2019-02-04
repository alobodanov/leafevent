<?php $title_name = "Milber"; ?>
<?php require_once "milbertoolbar.php"; // has a "grean bar" code ?>
<?php require_once "Library.php" ?>
<?php
		$dbLink = new DBLink();
		if($_SESSION['userE_login'] == "" ){
   			header('Location: logout.php');
   			die();
   		}
   		if(isset($_GET['view'])){
   			$userToView = htmlentities(trim($_GET['view']));

   			$userTable = "SELECT * FROM MILBER.v_user_info, MILBER.milberfriends WHERE id_SQL = $userToView";
			$userResult = $dbLink->query($userTable);



   			
   		}

?>






	</body>
</html>