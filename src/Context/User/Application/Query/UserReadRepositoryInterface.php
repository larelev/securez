<?php

namespace App\Context\User\Application\Query;

use App\Context\User\Application\DTO\UserReadModel;

interface UserReadRepositoryInterface
{
    /** @return UserReadModel[] */
    public function findAll(): array;
}
