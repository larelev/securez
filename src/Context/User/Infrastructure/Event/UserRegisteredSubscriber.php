<?php

namespace App\Context\User\Infrastructure\Event;

use App\Context\User\Domain\Event\UserRegisteredEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class UserRegisteredSubscriber implements EventSubscriberInterface
{
    public function __construct(private MailerInterface $mailer) {}

    public static function getSubscribedEvents(): array
    {
        return [
            UserRegisteredEvent::class => 'onUserRegistered',
        ];
    }

    public function onUserRegistered(UserRegisteredEvent $event): void
    {
        $user = $event->user;

        $email = (new Email())
            ->from('noreply@yourapp.com')
            ->to((string) $user->getEmail())
            ->subject('Welcome to Our App!')
            ->text('Thanks for signing up!');

        $this->mailer->send($email);
    }
}
