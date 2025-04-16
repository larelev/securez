<?php
// api/src/Dto/ResetPasswordRequest.php

namespace App\Dto;
use Symfony\Component\Validator\Constraints as Assert;

final class ResetPasswordRequest
{
    public string $username;
}
