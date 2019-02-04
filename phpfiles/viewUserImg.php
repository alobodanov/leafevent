<?php
require_once "Library.php";




if(isset($_GET['uimg'])){

	$temp = $_GET['uimg'];
	$link = new DBLink();
	$querry = "SELECT * FROM v_user_info WHERE id_SQL = '$temp'";
	$q = $link->query($querry);

	while($u = mysqli_fetch_array($q)){
		$file = $u['folder_name_SQL'];
		$pic = $u['user_pic_name_SQL'];

?>
		<img src='../milberUserPhotos/profilePictures/$file/$pic' alt='' />
<?php
	}

}

?>