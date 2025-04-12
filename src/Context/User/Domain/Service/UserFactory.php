<?php

namespace App\Context\User\Domain\Service;

use App\Context\User\Domain\Model\Email;
use App\Context\User\Domain\Model\Password;
use App\Context\User\Domain\Model\User;
use Symfony\Component\Uid\Uuid;

final class UserFactory
{
    public function create(string $email, string $plainPassword): User
    {
        $emailVO = new Email($email);
        $passwordVO = new Password($plainPassword);

        return new User(Uuid::v4(), $emailVO, $passwordVO->hash());
    }
}
