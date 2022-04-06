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
    // 2 Diffrent case sections, below is for heroku.com
    case '/' :
        require __DIR__ . '/views/login.php';
        break;
    case '/login' :
        require __DIR__ . '/views/login.php';
        break;
    case '/register' :
        require __DIR__ . '/views/register.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/views/404.php';
        break;
}

