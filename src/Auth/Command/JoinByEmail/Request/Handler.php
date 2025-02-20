<?php

declare(strict_types=1);

namespace MASK\Auth\Command\JoinByEmail\Request;

use Cassandra\Uuid;

class Handler
{
    public function handle(Command $command): void
    {
        $user = new User(
            Uuid::uuid4()->toString(),
            mb_strtolower($command->email),
            password_hash($command->password, PASSWORD_ARGON2I)
        );
    }
}