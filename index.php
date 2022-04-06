<?php

$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '/mapontest/' :
        require __DIR__ . '/views/login.php';
        break;
    case '/mapontest/login' :
        require __DIR__ . '/views/login.php';
        break;
    case '/mapontest/register' :
        require __DIR__ . '/views/register.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/views/404.php';
        break;
}

