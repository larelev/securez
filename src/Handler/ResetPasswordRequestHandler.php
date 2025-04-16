<?php

namespace App\Handler;

use App\Dto\ResetPasswordRequest;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class ResetPasswordRequestHandler
{
    public function __invoke(ResetPasswordRequest $forgotPassword)
    {
        // do something with the resource
    }
}
