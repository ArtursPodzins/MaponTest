<?php

declare(strict_types=1);

namespace Mapon\Handler;

class Register
{
    public function execute(): void
    {
        require_once __DIR__ . '/../views/register.php';
    }

    public function success(): void
    {
        require_once __DIR__ . '/../views/map.php';
    }
}