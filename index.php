<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Mapon\Handler\Login;
use Mapon\Handler\Register;
use Mapon\Router;

$router = new Router();

$router->get('/mapontest/', Login::class . '::execute');
$router->post('/mapontest/login', Login::class . '::execute');

$router->get('/mapontest/register', Register::class . '::execute');
$router->post('/mapontest/success', Register::class . '::success');

$router->addNotFoundHandler(function(){
    require_once __DIR__ . '/src/views/404.html';
});

$router->run();