<?php

class Dbh {
    
    protected function connect(){
        $cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
        $host = $cleardb_url["host"];
        $user = $cleardb_url["user"];
        $pwd = $cleardb_url["pass"];
        $dbname = "heroku_d5d58245fb0f454";

        $pdo = new PDO("mysql:host=' . $host.'; dbname=' . $dbname.';", $user, $pwd);
        return $pdo;
    }
}

