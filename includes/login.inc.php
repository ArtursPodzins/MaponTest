<?php

if(isset($_POST["submit"]))
{

    // Grabbing the data from POST
    $uid = $_POST["uid"];
    $pwd = $_POST["pwd"];

    include "../classes/dbh.classes.php";
    include "../classes/login.classes.php";
    include "../classes/login-contr.classes.php";
    $login = new LoginContr($uid, $pwd);

    $login->loginUser();

    header("location: ../usermap.php");
}