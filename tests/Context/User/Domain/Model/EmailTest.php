<?php

namespace App\Tests\Context\User\Domain\Model;

use PHPUnit\Framework\TestCase;
use App\Context\User\Domain\Model\Email;

class EmailTest extends TestCase
{
    public function testValidEmail(): void
    {
        $email = new Email('john@example.com');
        $this->assertEquals('john@example.com', $email->getValue());
    }

    public function testInvalidEmailThrows(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Email('not-an-email');
    }
}
