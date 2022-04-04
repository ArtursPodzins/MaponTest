<?php

// Main signup class
class SignupContr extends Signup 
{

    // Setting variables only for this class
    private $uid;
    private $pwd;
    private $pwdrepeat;

    // Giving variables above, value from form
    public function __construct($uid, $pwd, $pwdrepeat) 
    {
        $this->uid = $uid;
        $this->pwd = $pwd;
        $this->pwdrepeat = $pwdrepeat;
    }

    // Main signup function
    public function signupUser()
    {
        // Checking if input is empty
        if($this->emptyInput() == false)
        {
            header("location: ../index.php?error=emptyinput");
            exit();
        }
        // Checking if username is valid
        if($this->invalidUid() == false)
        {
            header("location: ../index.php?error=username");
            exit();
        }
        // Checking if username is available
        if($this->uidTakenCheck() == false)
        {
            header("location: ../index.php?error=usertaken");
            exit();
        }
        // Checking if passwords match
        if($this->pwdMatch() == false)
        {
            header("location: ../index.php?error=passwordmatch");
            exit();
        }

        $this->setUser($this->uid, $this->pwd);
    }

    // Empty input function
    private function emptyInput() {
        $result = "";
        if(empty($this->uid) || empty($this->pwd) || empty($this->pwdrepeat)){
            $result = false;
        }else{
            $result = true;
        }
        return $result;
    }

    // Invalid username function
    private function invalidUid(){
        $result = "";
        if(!preg_match("/^[a-zA-Z0-9]*$/", $this->uid)) {
            $result = false;
        }else{
            $result = true;
        }
        return $result;
    }

    // Password checker function
    private function pwdMatch() {
        $result = "";
        if($this->pwd !== $this->pwdrepeat){
            $result = false;
        }else{
            $result = true;
        }
        return $result;
    }

    // Function that checks if username is available
    private function uidTakenCheck() {
        $result = "";
        if(!$this->checkUser($this->uid)){
            $result = false;
        }else{
            $result = true;
        }
        return $result;
    }

}