<?php

declare(strict_types=1);

namespace Src\Auth\Command\JoinByEmail\Request;

class Command
{
    public string $email = '';
    public string $password = '';
}
