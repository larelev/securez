<?php

namespace App\Shared\Application\Query;

interface QueryHandlerInterface
{
    public function __invoke(object $query): mixed;
}