<?php

namespace App\Context\User\UI\Api;

namespace App\Context\User\UI\Api;

use App\Context\User\Application\Command\RegisterUserCommand;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    operations: [new Post()]
)]
final class RegisterUserInput
{
    #[Assert\NotBlank]
    public string $email;

    #[Assert\Length(min: 8)]
    public string $password;

    public function __construct(
        private MessageBusInterface $commandBus
    ) {}

    public function __invoke(): void
    {
        $this->commandBus->dispatch(new RegisterUserCommand($this->email, $this->password));
    }
}
