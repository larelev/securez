<?php

namespace App\Shared\Application\Query;

interface QueryBusInterface
{
    public function ask(object $query): mixed;
}