<?php 

    session_start();
    //unset($_SESSION['date']);//remove item from session
    session_destroy();//clean session
    header("location:login/index.php");

?>