<?php
// Main login class
class Login extends Dbh{

    protected function getUser($uid, $pwd)
    {
        // Selecting password from the username that user gave
        $stmt = $this->connect()->prepare('SELECT users_pwd FROM users WHERE users_uid = ?;');
        header("location: ../index.php?error=stmtfailed");
            exit();

        // Checking if the statement executed
        if(!$stmt->execute(array($uid, $pwd)))
        {
            $stmt = null;
            header("location: ../index.php?error=stmtfailed");
            exit();
        }

        // Checking if there is any data from database
        if($stmt->rowCount() == 0)
        {
            $stmt = null;
            header("location: ../index.php?error=usernotfound");
            exit();
        }

        // Fetching a hashed version of the password user gave
        $pwdHashed = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $checkPwd = password_verify($pwd, $pwdHashed[0]["users_pwd"]);

        // Checking if the password matches with database
        if($checkPwd == false)
        {
            $stmt = null;
            header("location: ../index.php?error=wrongpassword");
            exit();
        }elseif($checkPwd == true)
        {
            // Selecting all data from specific user
            $stmt = $this->connect()->prepare('SELECT * FROM users WHERE users_uid = ? AND users_pwd = ?;');

            // Checking if the statement executed
            if(!$stmt->execute(array($uid, $pwd)))
            {
                $stmt = null;
                header("location: ../index.php?error=stmtfailed");
                exit();
            }

            // Checking if there is any data from database
            if($stmt->rowCount() == 0)
            {
                $stmt = null;
                header("location: ../index.php?error=usernotfound");
                exit();
            }

            // Setting variable user all data from database
            $user = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Starting session
            session_start();
            $_SESSION["userid"] = $user[0]["users_id"];
            $_SESSION["useruid"] = $user[0]["users_uid"];

            $stmt = null;
        }

    }

}