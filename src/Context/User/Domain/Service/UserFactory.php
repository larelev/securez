<?php

namespace App\Context\User\Domain\Service;

use App\Context\User\Domain\Model\User;
use Symfony\Component\Uid\Uuid;

final class UserFactory
{
    public function create(string $email, string $plainPassword): User
    {
        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
        return new User(Uuid::v4(), $email, $hashedPassword);
    }
}
