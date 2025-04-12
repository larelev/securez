<?php

namespace App\Context\User\Domain\Event;

use App\Context\User\Domain\Model\User;

final class UserRegisteredEvent
{
    public function __construct(public readonly User $user) {}
}
