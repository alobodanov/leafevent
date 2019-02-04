<?php require "adminheader.php"; ?>

<?php

	$aLink = new DBLink();
    $adminL = "SELECT * FROM MILBER.MilberUserInfo WHERE user_type_SQL = 'A'";
    $newEResult = $aLink->query($adminL);
?>
<br />
<br />
	<table class="aList">
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Photo</th>
			<th>Email</th>
			<th>Online</th>
			<th>Register Date</th>
		</tr>
<?php
	while($a = mysqli_fetch_assoc($newEResult)){
?>
		<tr>
			<td><?= $a['id_SQL'];?></td>
			<td><?= $a['fname_SQL']." ".$a['lname_SQL'];?></td>
			<td><?= $a['user_pic_name_SQL'];?></td>
			<td><?= $a['email_SQL'];?></td>
			<td><?= $a['online_SQL'];?></td>
			<td><?= $a['SIGN_UP_DATE'];?></td>
		</tr>
<?php
	}
?>
	</table>
	<br />
	<a href="newAdmin.php">Create new Admin</a>








	</body>
</html>