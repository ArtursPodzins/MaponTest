<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Mapon\Handler\Login;
use Mapon\Handler\Register;
use Mapon\Router;

$router = new Router();

$router->get('/', Login::class . '::execute');
$router->post('/submitted', Login::class . '::submitte');
$router->get('/login', Login::class . '::execute');
$router->get('/success', Login::class . '::success');

$router->get('/register', Register::class . '::execute');
$router->post('/submitted', Register::class . '::submitted');





$router->addNotFoundHandler(function(){
    require_once __DIR__ . '/src/views/404.html';
});

$router->run();