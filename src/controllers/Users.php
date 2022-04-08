<?php

require_once '../models/User.php';
require_once '../helpers/session_helper.php';

class Users{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User;
    }

    public function register()
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $data = [
            'uid' => trim($_POST['uid']),
            'pwd' => trim($_POST['pwd']),
            'pwdRepeat' => trim($_POST['pwdRepeat'])
        ];

        if(empty($data['uid']) || empty($data['pwd']) || empty($data['pwdRepeat'])){
            flash("register", "Please fill out all inputs");
            redirect("../views/register.php");
        }

        if(!preg_match("/^[a-zA-Z0-9]*$/", $data['uid'])){
            flash("register", "Invalid username");
            redirect("../views/register.php");
        }

        if(strlen($data['pwd']) < 6){
            flash("register", "Invalid password");
            redirect("../views/register.php");
        }else if($data['pwd'] !== $data['pwdRepeat']){
            flash("register", "Passwords don't match");
            redirect("../views/register.php");
        }

        if($this->userModel->findUserByUsername($data['uid'])){
            flash("register", "Username already taken");
            redirect("../views/register.php");
        }

        $data['pwd'] = password_hash($data['pwd'], PASSWORD_DEFAULT);

        if($this->userModel->register($data)){
            redirect("../views/login.php");
        }else{
            die("Something went wrong");
        }
    }
}

$init = new Users;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $init->register();
}