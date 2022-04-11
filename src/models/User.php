<?php

require_once '../lib/Database.php';

class User
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function findUserByUsername($username){
        $this->db->query('SELECT * FROM users WHERE users_uid = :username');
        $this->db->bind(':username', $username);

        $row = $this->db->single();

        if($this->db->rowCount() > 0){
            return $row;
        }else{
            return false;
        }
    }

    public function register($data){
        $this->db->query('INSERT INTO users (users_uid, users_pwd) VALUES (:name, :password)');

        $this->db->bind(':name', $data['uid']);
        $this->db->bind(':password', $data['pwd']);

        if($this->db->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function login($name, $pass){
        $row = $this->findUserByUsername($name);

        if($row == false) return false;

        $hashedPwd = $row->users_pwd;
        if(password_verify($pass, $hashedPwd)){
            return $row;
        }else{
            return false;
        }
    }
}