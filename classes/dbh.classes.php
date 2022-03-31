<?php

class Dbh {

    protected function connect(){
        try{
            $cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
            $host = $cleardb_url["host"];
            $username = $cleardb_url["user"];
            $password = $cleardb_url["pass"];
            $active_group = 'default';
            $query_builder = TRUE;
            $dbh = new PDO('mysql:host=', $host,';dbname=heroku_d5d58245fb0f454', $username, $password);
            return $dbh;
        }catch(PDOException $e){
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }
}