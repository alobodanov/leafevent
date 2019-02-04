<?php 
    session_start();
    if (!isset($_SESSION['userEmail'])){

    } else {
        header("Location: home.php");
    }
?>