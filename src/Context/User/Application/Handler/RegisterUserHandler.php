<?php

namespace App\Context\User\Application\Handler;

use App\Context\User\Application\Command\RegisterUserCommand;
use App\Context\User\Domain\Event\UserRegisteredEvent;
use App\Context\User\Domain\Repository\UserRepositoryInterface;
use App\Context\User\Domain\Service\UserFactory;
use Symfony\Component\Messenger\MessageBusInterface;

final class RegisterUserHandler
{
    public function __construct(
        private UserFactory $userFactory,
        private UserRepositoryInterface $userRepository,
        private MessageBusInterface $eventBus
    ) {}

    public function __invoke(RegisterUserCommand $command): void
    {
        $user = $this->userFactory->create($command->email, $command->password);
        $this->userRepository->save($user);

        $this->eventBus->dispatch(new UserRegisteredEvent($user));
    }
}
