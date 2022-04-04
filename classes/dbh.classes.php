<?php

class Dbh 
{
    
    protected function connect()
    {
        // Heroku database connection
        try{
        // Using heroku ClearDB for this project
        $cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
        $host = $cleardb_url["host"];
        $user = $cleardb_url["user"];
        $pwd = $cleardb_url["pass"];
        $dbname = substr($cleardb_url["path"],1);

        // Basic PDO connection to database
        $dbh = new PDO("mysql:host=" . $host."; dbname=" . $dbname.";", $user, $pwd);
        return $dbh;
        }
        catch (PDOException $e){
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }

        //Localhost connection for debugging
        // try{
        //     $username = "root";
        //     $password = "";
        //     $dbh = new PDO("mysql:host=localhost;dbname=maponlogin", $username, $password);
        //     return $dbh;
        // }
        // catch(PDOException $e){
        //     print "Error!: " . $e->getMessage() . "<br/>";
        //     die();
        // }
    }
}

