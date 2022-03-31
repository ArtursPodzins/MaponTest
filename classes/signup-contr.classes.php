<?php

class SignupContr extends Signup {

    private $uid;
    private $pwd;
    private $pwdrepeat;

    public function __construct($uid, $pwd, $pwdrepeat) {
        $this->uid = $uid;
        $this->pwd = $pwd;
        $this->pwdrepeat = $pwdrepeat;
    }

    public function signupUser(){
        if($this->emptyInput() == false){
            header("location: ../index.php?error=emptyinput");
            exit();
        }
        if($this->invalidUid() == false){
            header("location: ../index.php?error=username");
            exit();
        }
        if($this->uidTakenCheck() == false){
            header("location: ../index.php?error=usertaken");
            exit();
        }
        if($this->pwdMatch() == false){
            header("location: ../index.php?error=passwordmatch");
            exit();
        }

        $this->setUser($this->uid, $this->pwd);
    }

    private function emptyInput() {
        $result = "";
        if(empty($this->uid) || empty($this->pwd) || empty($this->pwdrepeat)){
            $result = false;
        }else{
            $result = true;
        }
        return $result;
    }

    private function invalidUid(){
        $result = "";
        if(!preg_match("/^[a-zA-Z0-9]*$/", $this->uid)) {
            $result = false;
        }else{
            $result = true;
        }
        return $result;
    }

    private function pwdMatch() {
        $result = "";
        if($this->pwd !== $this->pwdrepeat){
            $result = false;
        }else{
            $result = true;
        }
        return $result;
    }

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