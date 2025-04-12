<?php

namespace App\Context\User\Application\DTO;

final class UserReadModel
{
    public function __construct(
        public string $id,
        public string $email
    ) {
    }
}
