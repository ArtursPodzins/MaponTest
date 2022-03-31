<?php

class Dbh {
    
    protected function connect(){
        $cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
        $active_group = 'default';
        $query_builder = TRUE;
        $host = $cleardb_url["host"];
        $user = $cleardb_url["user"];
        $pwd = $cleardb_url["pass"];
        $dbname = "heroku_d5d58245fb0f454";
        $dsn = 'mysql:host='. $host . ';dbname=' . $dbname;
        $pdo = new PDO($dsn, $user, $pwd);
        return $pdo;
    }
}

