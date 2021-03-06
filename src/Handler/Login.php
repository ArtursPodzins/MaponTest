<?php

declare(strict_types=1);

namespace Mapon\Handler;

class Login
{
    public function execute(): void
    {
        require_once __DIR__ . '/../views/login.php';
    }

    public function submitted(): void
    {
        require_once __DIR__ . '/../controllers/Users.php';
    }

    public function success(): void
    {
        require_once __DIR__ . '/../views/map.php';
    }

}