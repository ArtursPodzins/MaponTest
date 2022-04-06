<?php

class LoginContr extends Login
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
            
            exit();
        }

        $this->getUser($this->uid, $this->pwd);
    }

    // Empty input function
    private function emptyInput() 
    {
        if(empty($this->uid) || empty($this->pwd)){
            return false;
        }else return true;
    }

}