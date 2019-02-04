<?php require_once "Library.php" ?>
<?php
    if(isset($_POST['send'])){
        $dbLink = new DBLink();
        $userLostEmail = null;
        $userEmailEntered = htmlentities(trim($_POST['userEmailProvided']));
        //$userEmailEntered = mysqli_real_escape_string($dbLink, $userEmailEntered);
        $userTable = "SELECT * FROM MILBER.MilberUserInfo WHERE email_SQL = '$userEmailEntered'";
        $userResult = $dbLink->query($userTable);
        $count = mysqli_num_rows($userResult);

        while($u = mysqli_fetch_array($userResult)){
            $userLostEmail = $u['email_SQL'];
        }

        if($userLostEmail == $userEmailEntered && $count == 1){
            echo "hello";
            mail (
                "$userEmailEntered",
                "Password Reset Milbur",
                "The hint to your password is 2 Please do not reply",
                "From: Admin"
            );
        }
    }

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Password Reset</title>
      <link rel="shortcut icon" href="../images/1616.png" type="image/jpg">
<?php require "ChoiseOFCssForDifferentBrowsers.php"; ?>
  </head>
  <body>
    <div class="passwordChangeRequest">
        <form method="POST" class="accountPassword">
            <input type="text" name="userEmailProvided" placeholder="send email to"/>
            <input type="submit" name="send"/>

        </form>





    </div>



  </body>
</html>