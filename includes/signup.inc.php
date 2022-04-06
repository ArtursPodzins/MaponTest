<?php

if(isset($_POST["submit"]))
{

    // Grabbing the data from POST
    $uid = $_POST["uid"];
    $pwd = $_POST["pwd"];
    $pwdrepeat = $_POST["pwdrepeat"];

    include "../classes/dbh.classes.php";
    include "../classes/signup.classes.php";
    include "../classes/signup-contr.classes.php";
    $signup = new SignupContr($uid, $pwd, $pwdrepeat);

    $signup->signupUser();

    header("location: ../views/map.php");
}