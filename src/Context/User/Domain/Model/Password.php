<?php

namespace App\Context\User\Domain\Model;

use InvalidArgumentException;

final class Password
{
    public function __construct(private string $plainText)
    {
        if (strlen($plainText) < 8 || !preg_match('/[A-Z]/', $plainText)) {
            throw new InvalidArgumentException('Password must be at least 8 characters long and contain an uppercase letter.');
        }
    }

    public function hash(): string
    {
        return password_hash($this->plainText, PASSWORD_DEFAULT);
    }

    public function getRaw(): string
    {
        return $this->plainText;
    }
}
