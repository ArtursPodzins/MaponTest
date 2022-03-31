<?php

class Dbh {

    private $host = "host=eu-cdbr-west-02.cleardb.net";
    private $user = "b71f20df94555f";
    private $pwd = "8527f4eb";
    private $dbname = "heroku_d5d58245fb0f454";
    protected function connect(){
        $dsn = 'mysql:host='. $this->host . ';dbname=' . $this->dbname;
        $pdo = new PDO($dsn, $this->user, $this->pwd);
        return $pdo;
    }
}