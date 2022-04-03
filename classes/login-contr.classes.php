<?php

class loginContr extends Login
{

    // Variables only used in this class
    private $uid;
    private $pwd;

    // Setting values for variables above
    public function __construct($uid, $pwd) 
    {
        $this->uid = $uid;
        $this->pwd = $pwd;
    }

    // Login user function
    public function loginUser()
    {
        // Checking if the input is empty
        if($this->emptyInput() == false)
        {
            header("location: ../index.php?error=emptyinput");
            exit();
        }

        $this->getUser($this->uid, $this->pwd);
    }

    // Empty input function
    private function emptyInput() 
    {
        $result = "";
        if(empty($this->uid) || empty($this->pwd))
        {
            $result = false;
        }else{
            $result = true;
        }
        return $result;
    }

}