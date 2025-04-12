<?php

namespace App\Context\User\Infrastructure\Api;

use App\Context\User\Application\Command\RegisterUserCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class UserController extends AbstractController
{
    public function register(Request $request, MessageBusInterface $commandBus): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $command = new RegisterUserCommand($data['email'], $data['password']);
        $commandBus->dispatch($command);

        return new JsonResponse(['status' => 'ok'], 201);
    }
}
