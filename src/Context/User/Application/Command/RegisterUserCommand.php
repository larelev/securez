<?php

namespace App\Context\User\Application\Command;

use App\Context\User\Domain\Model\Email;

final class RegisterUserCommand
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {}
}
