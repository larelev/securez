<?php

namespace App\Context\User\Tests;

use PHPUnit\Framework\TestCase;
use App\Context\User\Application\Command\RegisterUserCommand;
use App\Context\User\Application\Handler\RegisterUserHandler;
use App\Context\User\Domain\Service\UserFactory;
use App\Context\User\Domain\Model\Email;
use App\Context\User\Domain\Model\User;
use App\Context\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class RegisterUserHandlerTest extends TestCase
{
    public function testItRegistersUserAndDispatchesEvent(): void
    {
        $factory = new UserFactory();
        $repository = $this->createMock(UserRepositoryInterface::class);
        $eventBus = $this->createMock(MessageBusInterface::class);

        $repository->expects($this->once())->method('save');
        $eventBus->expects($this->once())->method('dispatch');

        $handler = new RegisterUserHandler($factory, $repository, $eventBus);

        $command = new RegisterUserCommand('test@example.com', 'securePassword123');
        $handler($command); // using __invoke
    }
}
