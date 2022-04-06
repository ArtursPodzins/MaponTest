<?php

require_once "route/router.php";

route('/', function(){
    require __DIR__ . "../views/login.php";
});

route('/register', function(){
    require __DIR__ . "../viwes/register.php";
});

$action = $_SERVER['REQUEST_URI'];

dispatch($action);