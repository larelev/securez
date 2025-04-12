<?php

namespace App\Context\User\Domain\Model;

use Symfony\Component\Uid\Uuid;

final class User
{
    public function __construct(
        private Uuid $id,
        private string $email,
        private string $hashedPassword
    ) {}

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
