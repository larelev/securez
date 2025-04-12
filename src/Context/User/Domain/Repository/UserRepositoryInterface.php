<?php

namespace App\Context\User\Domain\Repository;

use App\Context\User\Domain\Model\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;
}
