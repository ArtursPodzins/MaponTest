<?php

class Dbh 
{
    
    protected function connect()
    {
        // Using heroku ClearDB for this project
        $cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
        $host = $cleardb_url["host"];
        $user = $cleardb_url["user"];
        $pwd = $cleardb_url["pass"];
        $dbname = "heroku_d5d58245fb0f454";

        // Basic PDO connection to database
        $pdo = new PDO("mysql:host=' . $host.'; dbname=' . $dbname.';", $user, $pwd);

        // Returning connection
        return $pdo;
    }
}

