<?php

if(isset($_POST["submit"]))
{

    // Grabbing the data from POST
    $uid = $_POST["uid"];
    $pwd = $_POST["pwd"];
    $pwdrepeat = $_POST["pwdrepeat"];

    include "../.../dbh.classes.php";
    include "../.../signup.classes.php";
    include "../.../signup-contr.classes.php";
    $signup = new SignupContr($uid, $pwd, $pwdrepeat);

    $signup->signupUser();

    header("location: ../usermap.php");
}