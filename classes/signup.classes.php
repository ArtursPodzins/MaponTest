<?php

class Signup extends Dbh {

    protected function setUser($uid, $pwd){
        // Preparing SQL statement
        $stmt = $this->connect()->prepare('INSERT INTO users (users_uid, users_pwd) VALUES (?,?);');
        
        // Password is stored as hashed version
        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

        // Checks if statement executed
        if(!$stmt->execute(array($uid, $hashedPwd))){
            $stmt = null;
            header("location: ../index.php?error=stmtfailed");
            exit();
        }

        $stmt = null;
    }

    protected function checkUser($uid){
        // Preparing SQL statement
        $stmt = $this->connect()->prepare('SELECT users_uid FROM users WHERE users_uid = ?;');
    
        // Checking if statement executed
        if(!$stmt->execute(array($uid))){
            $stmt = null;
            header("location: ../index.php?error=stmtfailed");
            exit();
        }

        $resultCheck = "";
        // Checking if there are any results returned
        if($stmt->rowCount() > 0){
            $resultCheck = false;
        }else{
            $resultCheck = true;
        }

        return $resultCheck;
    }

}