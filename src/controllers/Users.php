<?php

require_once 'src/models/User.php';
require_once 'src/helpers/session_helper.php';

class Users{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User;
    }

    public function register()
    {
        // Filtering input
        $_POST = filter_input_array(INPUT_POST);

        // Setting data from post
        $data = [
            'uid' => trim($_POST['uid']),
            'pwd' => trim($_POST['pwd']),
            'pwdRepeat' => trim($_POST['pwdRepeat'])
        ];

        // Checking if any of the inputs are empty
        if(empty($data['uid']) || empty($data['pwd']) || empty($data['pwdRepeat'])){
            flash("register", "Please fill out all inputs");
            redirect("register");
        }

        // Checking if username consists of any forbidden chars
        if(!preg_match("/^[a-zA-Z0-9]*$/", $data['uid'])){
            flash("register", "Invalid username");
            redirect("register");
        }

        // Checking if password is atleast 6 chars long
        if(strlen($data['pwd']) < 6){
            flash("register", "Invalid password");
            redirect("register");
        }else if($data['pwd'] !== $data['pwdRepeat']){
            flash("register", "Passwords don't match");
            redirect("register");
        }

        // Checking if username already exists in database
        if($this->userModel->findUserByUsername($data['uid'])){
            flash("register", "Username already taken");
            redirect("register");
        }

        // Making password hashed for database
        $data['pwd'] = password_hash($data['pwd'], PASSWORD_DEFAULT);

        if($this->userModel->register($data)){
            redirect("login");
        }else{
            die("Something went wrong");
        }
    }

    public function login(){
        // Filtering post data
        $_POST = filter_input_array(INPUT_POST);

        // Setting data from post
        $data=[
            'users_uid' => trim($_POST['uid']),
            'users_pwd' => trim($_POST['pwd'])
        ];

        // Checking if input isn't empty
        if(empty($data['users_uid']) || empty($data['users_pwd'])){
            flash("login", "Please fill out all inputs");
            header("login");
            exit();
        }

        if($this->userModel->findUserByUsername($data['users_uid'])){
            $loggedInUser = $this->userModel->login($data['users_uid'], $data['users_pwd']);
            if($loggedInUser){
                $this->createUserSession($loggedInUser);
            }else{
                flash("login", "Password Incorrect");
                redirect("login");
            }
        }else{
            flash("login", "No user found");
            redirect("login");
        }
    }

    public function createUserSession($user){
        $_SESSION['users_uid'] = $user->users_uid;
        redirect("success");
    }
}

$init = new Users;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    switch($_POST['type']){
        case 'register' :
            $init->register();
            break;
        case 'login' :
            $init->login();
            break;
    }
}