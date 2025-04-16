<?php

namespace App\Handler;

use App\Entity\Person;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class PersonHandler
{
    public function __invoke(Person $person)
    {
        // do something with the resource
    }
}
