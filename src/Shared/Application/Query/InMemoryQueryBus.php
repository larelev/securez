<?php

namespace App\Shared\Application\Query;

final class InMemoryQueryBus implements QueryBusInterface
{
    public function __construct(private readonly iterable $handlers) {}

    /**
     * @throws \ReflectionException
     */
    public function ask(object $query): mixed
    {
        foreach ($this->handlers as $handler) {
            if ($handler instanceof QueryHandlerInterface && (new \ReflectionParameter([$handler, '__invoke'], 0))->getType()->getName() === $query::class) {
                return $handler($query);
            }
        }

        throw new \RuntimeException('No handler found for query ' . get_class($query));
    }
}
