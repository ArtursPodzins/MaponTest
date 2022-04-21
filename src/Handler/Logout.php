<?php

declare(strict_types=1);

namespace Mapon\Handler;

class Logout
{
    public function logout(): void
    {
        require_once __DIR__ . "/../views/login.php";
    }

}